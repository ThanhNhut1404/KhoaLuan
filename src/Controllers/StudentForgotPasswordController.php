<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\ForgotPasswordModel;
use KhoaLuan\QLDRL\Services\MailService;
use Throwable;

class StudentForgotPasswordController
{
    private const OTP_EXPIRES_MINUTES = 5;
    private const OTP_RESEND_COOLDOWN_SECONDS = 60;
    private const MAX_OTP_ATTEMPTS = 5;

    public function __construct(
        private ?ForgotPasswordModel $model = null,
        private ?MailService $mailService = null
    ) {
        $this->model = $this->model ?? new ForgotPasswordModel(Database::getConnection());
        $this->mailService = $this->mailService ?? new MailService();
    }

    public function forgotPassword(): void
    {
        $this->clearResetSession();
        $this->render('forgot_password', [
            'email' => '',
            'errors' => [],
            'toast' => $this->pullToast(),
            'cooldownRemaining' => 0,
        ]);
    }

    public function handleForgotPassword(): void
    {
        $email = trim((string) ($_POST['email'] ?? ''));
        $errors = [];
        $cooldownRemaining = 0;

        if ($email === '') {
            $errors['email'] = 'Vui lòng nhập email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        } else {
            $cooldownRemaining = $this->otpCooldownRemaining($email);
            if ($cooldownRemaining > 0) {
                $errors['email'] = 'Vui lòng chờ ' . $cooldownRemaining . ' giây trước khi gửi lại mã OTP.';
            }
        }

        if (!empty($errors)) {
            $this->render('forgot_password', [
                'email' => $email,
                'errors' => $errors,
                'toast' => null,
                'cooldownRemaining' => $cooldownRemaining,
            ]);
            return;
        }

        try {
            $student = $this->model->findStudentByEmail($email);

            if ($student === null) {
                $this->render('forgot_password', [
                    'email' => $email,
                    'errors' => ['email' => 'Không tìm thấy tài khoản sinh viên với email này.'],
                    'toast' => null,
                    'cooldownRemaining' => 0,
                ]);
                return;
            }

            if (!$this->canSendResetOtp((string) ($student['account_status'] ?? ''))) {
                $this->render('forgot_password', [
                    'email' => $email,
                    'errors' => ['email' => 'Tài khoản sinh viên đang bị khóa, không thể gửi OTP.'],
                    'toast' => null,
                    'cooldownRemaining' => 0,
                ]);
                return;
            }

            $this->clearResetSession();

            $otp = (string) random_int(100000, 999999);
            $hashedOtp = password_hash($otp, PASSWORD_DEFAULT);
            $expiresAt = date('Y-m-d H:i:s', time() + (self::OTP_EXPIRES_MINUTES * 60));
            $username = (string) ($student['username'] ?? '');

            $this->model->disableOldOtps($username);
            $otpId = $this->model->createOtp($username, $hashedOtp, $expiresAt);

            $sent = $this->mailService->sendOtp(
                (string) ($student['email'] ?? $email),
                (string) ($student['full_name'] ?? ''),
                $otp
            );

            if (!$sent) {
                $this->model->markOtpUsed($otpId);
                error_log('Unable to send student password reset OTP to ' . $email);
                $this->render('forgot_password', [
                    'email' => $email,
                    'errors' => [],
                    'toast' => ['type' => 'error', 'message' => 'Không thể gửi mã OTP lúc này. Vui lòng thử lại sau.'],
                    'cooldownRemaining' => 0,
                ]);
                return;
            }

            $_SESSION['student_reset_requested'] = true;
            $_SESSION['student_reset_username'] = $username;
            $_SESSION['student_reset_otp_id'] = $otpId;
            $_SESSION['student_reset_otp_verified'] = false;
            $_SESSION['student_reset_email'] = strtolower($email);
            $_SESSION['student_reset_last_sent_at'] = time();
            unset($_SESSION['student_reset_locked_until']);
        } catch (Throwable $exception) {
            error_log('Student forgot password failed: ' . $exception->getMessage());
            $this->render('forgot_password', [
                'email' => $email,
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi gửi mã OTP. Vui lòng thử lại.'],
                'cooldownRemaining' => 0,
            ]);
            return;
        }

        $_SESSION['student_reset_toast'] = [
            'type' => 'success',
            'message' => 'Mã OTP đã được gửi đến email sinh viên.',
        ];

        $this->redirect('/KhoaLuan/public/student.php?page=verify_otp');
    }

    public function verifyOtp(): void
    {
        if (empty($_SESSION['student_reset_requested'])) {
            $this->redirect('/KhoaLuan/public/student.php?page=forgot_password');
        }

        $this->render('verify_otp', [
            'otp' => '',
            'errors' => [],
            'toast' => $this->pullToast(),
            'resendAvailableIn' => $this->otpCooldownRemaining(),
            'otpRemainingSeconds' => $this->otpTimeRemaining(),
        ]);
    }

    public function handleResendOtp(): void
    {
        if (empty($_SESSION['student_reset_requested']) || empty($_SESSION['student_reset_email'])) {
            $this->redirect('/KhoaLuan/public/student.php?page=forgot_password');
        }

        $remainingSeconds = $this->otpTimeRemaining();
        if ($remainingSeconds > 0) {
            $this->render('verify_otp', [
                'otp' => '',
                'errors' => ['otp' => 'Mã OTP vẫn còn hiệu lực trong ' . $remainingSeconds . ' giây. Vui lòng chờ hết thời gian hoặc nhập mã OTP hiện tại.'],
                'toast' => null,
                'resendAvailableIn' => $remainingSeconds,
                'otpRemainingSeconds' => $remainingSeconds,
            ]);
            return;
        }

        $email = (string) ($_SESSION['student_reset_email'] ?? '');

        try {
            $student = $this->model->findStudentByEmail($email);

            if ($student === null) {
                $this->clearResetSession();
                $this->render('forgot_password', [
                    'email' => $email,
                    'errors' => ['email' => 'Không tìm thấy tài khoản sinh viên với email này.'],
                    'toast' => null,
                    'cooldownRemaining' => 0,
                ]);
                return;
            }

            if (!$this->canSendResetOtp((string) ($student['account_status'] ?? ''))) {
                $this->clearResetSession();
                $this->render('forgot_password', [
                    'email' => $email,
                    'errors' => ['email' => 'Tài khoản sinh viên đang bị khóa, không thể gửi OTP.'],
                    'toast' => null,
                    'cooldownRemaining' => 0,
                ]);
                return;
            }

            $otp = (string) random_int(100000, 999999);
            $hashedOtp = password_hash($otp, PASSWORD_DEFAULT);
            $expiresAt = date('Y-m-d H:i:s', time() + (self::OTP_EXPIRES_MINUTES * 60));
            $username = (string) ($student['username'] ?? '');

            $this->model->disableOldOtps($username);
            $otpId = $this->model->createOtp($username, $hashedOtp, $expiresAt);

            $sent = $this->mailService->sendOtp(
                (string) ($student['email'] ?? $email),
                (string) ($student['full_name'] ?? ''),
                $otp
            );

            if (!$sent) {
                $this->model->markOtpUsed($otpId);
                error_log('Unable to resend student password reset OTP to ' . $email);
                $this->render('verify_otp', [
                    'otp' => '',
                    'errors' => [],
                    'toast' => ['type' => 'error', 'message' => 'Không thể gửi lại mã OTP lúc này. Vui lòng thử lại sau.'],
                    'resendAvailableIn' => 0,
                    'otpRemainingSeconds' => self::OTP_EXPIRES_MINUTES * 60,
                ]);
                return;
            }

            $_SESSION['student_reset_username'] = $username;
            $_SESSION['student_reset_otp_id'] = $otpId;
            $_SESSION['student_reset_otp_verified'] = false;
            $_SESSION['student_reset_email'] = strtolower($email);
            $_SESSION['student_reset_last_sent_at'] = time();
            unset($_SESSION['student_reset_locked_until']);
        } catch (Throwable $exception) {
            error_log('Student resend OTP failed: ' . $exception->getMessage());
            $this->render('verify_otp', [
                'otp' => '',
                'errors' => [],
                'toast' => ['type' => 'error', 'message' => 'Có lỗi khi gửi lại mã OTP. Vui lòng thử lại.'],
                'resendAvailableIn' => 0,
                'otpRemainingSeconds' => 0,
            ]);
            return;
        }

        $_SESSION['student_reset_toast'] = [
            'type' => 'success',
            'message' => 'Mã OTP mới đã được gửi đến email sinh viên.',
        ];

        $this->redirect('/KhoaLuan/public/student.php?page=verify_otp');
    }

    public function handleVerifyOtp(): void
    {
        if (empty($_SESSION['student_reset_requested'])) {
            $this->redirect('/KhoaLuan/public/student.php?page=forgot_password');
        }

        $otp = trim((string) ($_POST['otp'] ?? ''));
        $errors = [];
        $lockedRemaining = $this->verificationLockRemaining();

        if ($lockedRemaining > 0) {
            $errors['otp'] = 'Xác minh OTP đang tạm thời bị khóa. Vui lòng thử lại sau ' . $lockedRemaining . ' giây hoặc gửi lại mã mới.';
        } elseif ($otp === '') {
            $errors['otp'] = 'Vui lòng nhập mã OTP.';
        } elseif (!preg_match('/^\d{6}$/', $otp)) {
            $errors['otp'] = 'Mã OTP phải gồm 6 chữ số.';
        }

        if (!empty($errors)) {
            $this->render('verify_otp', [
                'otp' => $otp,
                'errors' => $errors,
                'toast' => null,
                'resendAvailableIn' => $this->otpCooldownRemaining(),
                'otpRemainingSeconds' => $this->otpTimeRemaining(),
            ]);
            return;
        }

        $username = (string) ($_SESSION['student_reset_username'] ?? '');
        $otpRow = null;

        try {
            if ($username !== '') {
                $otpRow = $this->model->findLatestOtp($username);
            }

            if ($otpRow === null) {
                $errors['otp'] = 'Mã OTP không tồn tại, đã hết hạn hoặc đã được sử dụng. Vui lòng gửi lại mã mới.';
            } elseif ((int) ($otpRow['SO_LAN_NHAP_SAI'] ?? 0) >= self::MAX_OTP_ATTEMPTS) {
                $_SESSION['student_reset_locked_until'] = time() + (self::OTP_EXPIRES_MINUTES * 60);
                $errors['otp'] = 'Bạn đã nhập sai OTP quá nhiều lần. Xác minh tạm thời bị khóa, vui lòng gửi lại mã mới.';
            } elseif ($this->isExpired((string) ($otpRow['THOI_GIAN_HET_HAN'] ?? ''))) {
                $this->model->markOtpUsed((int) ($otpRow['ID'] ?? 0));
                $errors['otp'] = 'Mã OTP đã hết hạn. Vui lòng gửi lại mã mới.';
            } elseif (!password_verify($otp, (string) ($otpRow['MA_BAM_OTP'] ?? ''))) {
                $this->model->increaseAttempts((int) ($otpRow['ID'] ?? 0));
                $remainingAttempts = max(0, self::MAX_OTP_ATTEMPTS - ((int) ($otpRow['SO_LAN_NHAP_SAI'] ?? 0) + 1));

                if ($remainingAttempts === 0) {
                    $_SESSION['student_reset_locked_until'] = time() + (self::OTP_EXPIRES_MINUTES * 60);
                    $errors['otp'] = 'Mã OTP không chính xác. Bạn đã nhập sai quá nhiều lần, vui lòng gửi lại mã mới.';
                } else {
                    $errors['otp'] = 'Mã OTP không chính xác. Bạn còn ' . $remainingAttempts . ' lần thử.';
                }
            }

            if (!empty($errors)) {
                $this->render('verify_otp', [
                    'otp' => '',
                    'errors' => $errors,
                    'toast' => null,
                    'resendAvailableIn' => $this->otpCooldownRemaining(),
                    'otpRemainingSeconds' => $this->otpTimeRemaining(),
                ]);
                return;
            }

            $_SESSION['student_reset_otp_verified'] = true;
            $_SESSION['student_reset_otp_id'] = (int) ($otpRow['ID'] ?? 0);
            unset($_SESSION['student_reset_locked_until']);
            $this->redirect('/KhoaLuan/public/student.php?page=reset_password');
        } catch (Throwable $exception) {
            error_log('Student verify OTP failed: ' . $exception->getMessage());
            $this->render('verify_otp', [
                'otp' => '',
                'errors' => ['otp' => 'Có lỗi xảy ra. Vui lòng thử lại.'],
                'toast' => null,
                'resendAvailableIn' => $this->otpCooldownRemaining(),
                'otpRemainingSeconds' => $this->otpTimeRemaining(),
            ]);
        }
    }

    public function resetPassword(): void
    {
        $this->ensureOtpVerified();
        $this->render('reset_password', [
            'errors' => [],
            'toast' => $this->pullToast(),
        ]);
    }

    public function handleResetPassword(): void
    {
        $this->ensureOtpVerified();

        $password = (string) ($_POST['password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
        $errors = $this->validatePassword($password, $confirmPassword);

        if (!empty($errors)) {
            $this->render('reset_password', [
                'errors' => $errors,
                'toast' => null,
            ]);
            return;
        }

        $username = (string) ($_SESSION['student_reset_username'] ?? '');
        $verifiedOtpId = (int) ($_SESSION['student_reset_otp_id'] ?? 0);

        try {
            $otpRow = $username !== '' ? $this->model->findLatestOtp($username) : null;

            if ($otpRow === null
                || (int) ($otpRow['ID'] ?? 0) !== $verifiedOtpId
                || (int) ($otpRow['SO_LAN_NHAP_SAI'] ?? 0) >= self::MAX_OTP_ATTEMPTS
                || !empty($otpRow['THOI_GIAN_DA_SU_DUNG'])
                || $this->isExpired((string) ($otpRow['THOI_GIAN_HET_HAN'] ?? ''))
            ) {
                $this->clearResetSession();
                $_SESSION['student_reset_toast'] = [
                    'type' => 'error',
                    'message' => 'Phiên đặt lại mật khẩu đã hết hạn. Vui lòng thực hiện lại.',
                ];
                $this->redirect('/KhoaLuan/public/student.php?page=forgot_password');
            }

            if (!$this->model->updatePassword($username, $password)) {
                $this->render('reset_password', [
                    'errors' => ['password' => 'Không thể cập nhật mật khẩu. Vui lòng thử lại.'],
                    'toast' => null,
                ]);
                return;
            }

            $this->model->markOtpUsed($verifiedOtpId);
            $this->clearResetSession();
            $_SESSION['student_login_flash'] = [
                'type' => 'success',
                'message' => 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.',
            ];

            $this->redirect('/KhoaLuan/public/student.php?action=login');
        } catch (Throwable $exception) {
            error_log('Student reset password failed: ' . $exception->getMessage());
            $this->render('reset_password', [
                'errors' => ['password' => 'Có lỗi xảy ra. Vui lòng thử lại.'],
                'toast' => null,
            ]);
        }
    }

    private function validatePassword(string $password, string $confirmPassword): array
    {
        $errors = [];

        if ($password === '') {
            $errors['password'] = 'Vui lòng nhập mật khẩu mới.';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Mật khẩu phải có tối thiểu 8 ký tự.';
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errors['password'] = 'Mật khẩu phải có ít nhất một chữ hoa.';
        } elseif (!preg_match('/[a-z]/', $password)) {
            $errors['password'] = 'Mật khẩu phải có ít nhất một chữ thường.';
        } elseif (!preg_match('/[0-9]/', $password)) {
            $errors['password'] = 'Mật khẩu phải có ít nhất một chữ số.';
        } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors['password'] = 'Mật khẩu phải có ít nhất một ký tự đặc biệt.';
        }

        if ($confirmPassword === '') {
            $errors['confirm_password'] = 'Vui lòng xác nhận mật khẩu.';
        } elseif ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Xác nhận mật khẩu không trùng khớp.';
        }

        return $errors;
    }

    private function ensureOtpVerified(): void
    {
        if (empty($_SESSION['student_reset_otp_verified']) || empty($_SESSION['student_reset_username'])) {
            $target = !empty($_SESSION['student_reset_requested'])
                ? '/KhoaLuan/public/student.php?page=verify_otp'
                : '/KhoaLuan/public/student.php?page=forgot_password';
            $this->redirect($target);
        }
    }

    private function clearResetSession(): void
    {
        unset(
            $_SESSION['student_reset_requested'],
            $_SESSION['student_reset_username'],
            $_SESSION['student_reset_otp_id'],
            $_SESSION['student_reset_otp_verified'],
            $_SESSION['student_reset_locked_until']
        );
    }

    private function otpCooldownRemaining(?string $email = null): int
    {
        $lastSentAt = (int) ($_SESSION['student_reset_last_sent_at'] ?? 0);
        if ($lastSentAt <= 0) {
            return 0;
        }

        if ($email !== null) {
            $sessionEmail = (string) ($_SESSION['student_reset_email'] ?? '');
            if ($sessionEmail === '' || strtolower(trim($email)) !== $sessionEmail) {
                return 0;
            }
        }

        return max(0, self::OTP_RESEND_COOLDOWN_SECONDS - (time() - $lastSentAt));
    }

    private function otpTimeRemaining(): int
    {
        $username = (string) ($_SESSION['student_reset_username'] ?? '');
        if ($username === '') {
            return 0;
        }

        try {
            $otpRow = $this->model->findLatestOtp($username);
        } catch (Throwable $exception) {
            error_log('Unable to read OTP expiry: ' . $exception->getMessage());
            return 0;
        }

        if ($otpRow === null || !empty($otpRow['THOI_GIAN_DA_SU_DUNG'])) {
            return 0;
        }

        $expiresAt = strtotime((string) ($otpRow['THOI_GIAN_HET_HAN'] ?? ''));
        if ($expiresAt === false) {
            return 0;
        }

        return max(0, $expiresAt - time());
    }

    private function verificationLockRemaining(): int
    {
        $lockedUntil = (int) ($_SESSION['student_reset_locked_until'] ?? 0);
        if ($lockedUntil <= time()) {
            unset($_SESSION['student_reset_locked_until']);
            return 0;
        }

        return $lockedUntil - time();
    }

    private function pullToast(): ?array
    {
        $toast = $_SESSION['student_reset_toast'] ?? null;
        unset($_SESSION['student_reset_toast']);

        return is_array($toast) ? $toast : null;
    }

    private function render(string $view, array $data = []): void
    {
        $this->sendNoCacheHeaders();
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../views/Frontend/auth/' . $view . '.php';
    }

    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    private function isExpired(string $expiresAt): bool
    {
        $timestamp = strtotime($expiresAt);

        return $timestamp === false || $timestamp < time();
    }

    private function canSendResetOtp(string $status): bool
    {
        return strtoupper(trim($status)) === 'HOAT_DONG';
    }

    private function sendNoCacheHeaders(): void
    {
        if (headers_sent()) {
            return;
        }

        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
    }
}
