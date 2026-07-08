<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\ForgotPasswordModel;
use KhoaLuan\QLDRL\Services\MailService;
use Throwable;

class StudentForgotPasswordController
{
    private const OTP_EXPIRES_MINUTES = 5;
    private const MAX_OTP_ATTEMPTS = 5;
    private const GENERIC_EMAIL_MESSAGE = 'Nếu email hợp lệ, hệ thống sẽ gửi mã OTP về email đã đăng ký.';
    private const INELIGIBLE_ACCOUNT_MESSAGE = 'Không thể thực hiện yêu cầu. Vui lòng liên hệ quản trị viên để được hỗ trợ.';

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
        ]);
    }

    public function handleForgotPassword(): void
    {
        $email = trim((string) ($_POST['email'] ?? ''));
        $errors = [];

        if ($email === '') {
            $errors['email'] = 'Vui lòng nhập email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        }

        if (!empty($errors)) {
            $this->render('forgot_password', [
                'email' => $email,
                'errors' => $errors,
                'toast' => null,
            ]);
            return;
        }

        $this->clearResetSession();
        $_SESSION['student_reset_requested'] = true;

        try {
            $student = $this->model->findStudentByEmail($email);

            if ($student !== null && !$this->canSendResetOtp((string) ($student['account_status'] ?? ''))) {
                $_SESSION['student_reset_toast'] = [
                    'type' => 'error',
                    'message' => self::INELIGIBLE_ACCOUNT_MESSAGE,
                ];
                $this->clearResetSession();
                $this->redirect('/KhoaLuan/public/student.php?page=forgot_password');
            }

            if ($student !== null) {
                $otp = (string) random_int(100000, 999999);
                $hashedOtp = password_hash($otp, PASSWORD_DEFAULT);
                $expiresAt = date('Y-m-d H:i:s', time() + (self::OTP_EXPIRES_MINUTES * 60));
                $username = (string) ($student['username'] ?? '');

                $this->model->disableOldOtps($username);
                $otpId = $this->model->createOtp($username, $hashedOtp, $expiresAt);

                $_SESSION['student_reset_username'] = $username;
                $_SESSION['student_reset_otp_id'] = $otpId;
                $_SESSION['student_reset_otp_verified'] = false;

                $sent = $this->mailService->sendOtp(
                    (string) ($student['email'] ?? $email),
                    (string) ($student['full_name'] ?? ''),
                    $otp
                );

                if (!$sent) {
                    error_log('Unable to send student password reset OTP to ' . $email);
                }
            }
        } catch (Throwable $exception) {
            error_log('Student forgot password failed: ' . $exception->getMessage());
        }

        $_SESSION['student_reset_toast'] = [
            'type' => 'success',
            'message' => self::GENERIC_EMAIL_MESSAGE,
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
        ]);
    }

    public function handleVerifyOtp(): void
    {
        if (empty($_SESSION['student_reset_requested'])) {
            $this->redirect('/KhoaLuan/public/student.php?page=forgot_password');
        }

        $otp = trim((string) ($_POST['otp'] ?? ''));
        $errors = [];

        if ($otp === '') {
            $errors['otp'] = 'Vui lòng nhập mã OTP.';
        } elseif (!preg_match('/^\d{6}$/', $otp)) {
            $errors['otp'] = 'Mã OTP phải gồm 6 chữ số.';
        }

        if (!empty($errors)) {
            $this->render('verify_otp', [
                'otp' => $otp,
                'errors' => $errors,
                'toast' => null,
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
                $errors['otp'] = 'Mã OTP không hợp lệ hoặc đã hết hạn.';
            } elseif ((int) ($otpRow['SO_LAN_NHAP_SAI'] ?? 0) >= self::MAX_OTP_ATTEMPTS) {
                $errors['otp'] = 'Mã OTP đã bị khóa do nhập sai quá nhiều lần.';
            } elseif ($this->isExpired((string) ($otpRow['THOI_GIAN_HET_HAN'] ?? ''))) {
                $errors['otp'] = 'Mã OTP đã hết hạn.';
            } elseif (!password_verify($otp, (string) ($otpRow['MA_BAM_OTP'] ?? ''))) {
                $this->model->increaseAttempts((int) ($otpRow['ID'] ?? 0));
                $errors['otp'] = 'Mã OTP không hợp lệ hoặc đã hết hạn.';
            }

            if (!empty($errors)) {
                $this->render('verify_otp', [
                    'otp' => '',
                    'errors' => $errors,
                    'toast' => null,
                ]);
                return;
            }

            $_SESSION['student_reset_otp_verified'] = true;
            $_SESSION['student_reset_otp_id'] = (int) ($otpRow['ID'] ?? 0);
            $this->redirect('/KhoaLuan/public/student.php?page=reset_password');
        } catch (Throwable $exception) {
            error_log('Student verify OTP failed: ' . $exception->getMessage());
            $this->render('verify_otp', [
                'otp' => '',
                'errors' => ['otp' => 'Có lỗi xảy ra. Vui lòng thử lại.'],
                'toast' => null,
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
            $_SESSION['student_reset_otp_verified']
        );
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
