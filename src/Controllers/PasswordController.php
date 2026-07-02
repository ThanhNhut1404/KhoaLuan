<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\UserModel;

class PasswordController
{
    private UserModel $users;

    public function __construct(?UserModel $users = null)
    {
        $this->users = $users ?? new UserModel(Database::getConnection());
    }

    public function changePassword(string $username, array $data, bool $rejectUsernameInPassword): array
    {
        $state = [
            'errors' => [],
            'openModal' => true,
            'toast' => null,
            'redirectToLogin' => false,
        ];

        $currentPassword = $data['current_password'] ?? '';
        $newPassword = $data['new_password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        if ($currentPassword === '') {
            $state['errors']['current_password'] = 'Vui lòng nhập mật khẩu cũ.';
            return $state;
        }

        if ($newPassword === '') {
            $state['errors']['new_password'] = 'Vui lòng nhập mật khẩu mới.';
            return $state;
        }

        if ($confirmPassword === '') {
            $state['errors']['confirm_password'] = 'Vui lòng xác nhận mật khẩu mới.';
            return $state;
        }

        $storedPassword = $this->users->getPasswordByUsername($username);
        if ($storedPassword === null || !password_verify($currentPassword, $storedPassword)) {
            $state['errors']['current_password'] = 'Mật khẩu cũ không chính xác.';
            return $state;
        }

        $passwordError = $this->validateNewPassword($newPassword, $currentPassword, $username, $rejectUsernameInPassword);
        if ($passwordError !== null) {
            $state['errors']['new_password'] = $passwordError;
            return $state;
        }

        if ($newPassword !== $confirmPassword) {
            $state['errors']['confirm_password'] = 'Mật khẩu xác nhận không khớp.';
            return $state;
        }

        $state['openModal'] = false;

        if (!$this->users->updatePassword($username, $newPassword)) {
            $state['toast'] = [
                'type' => 'error',
                'message' => 'Đổi mật khẩu thất bại. Vui lòng thử lại sau.',
            ];
            return $state;
        }

        $state['toast'] = [
            'type' => 'success',
            'message' => 'Đổi mật khẩu thành công.',
        ];
        $state['redirectToLogin'] = true;
        $this->destroySession();

        return $state;
    }

    private function validateNewPassword(string $newPassword, string $oldPassword, string $username, bool $rejectUsernameInPassword): ?string
    {
        if (strlen($newPassword) < 8) {
            return 'Mật khẩu phải có ít nhất 8 ký tự.';
        }

        if (!preg_match('/[A-Z]/', $newPassword)) {
            return 'Mật khẩu phải có ít nhất 1 chữ in hoa.';
        }

        if (!preg_match('/[a-z]/', $newPassword)) {
            return 'Mật khẩu phải có ít nhất 1 chữ thường.';
        }

        if (!preg_match('/[0-9]/', $newPassword)) {
            return 'Mật khẩu phải có ít nhất 1 chữ số.';
        }

        if (!preg_match('/[^A-Za-z0-9]/', $newPassword)) {
            return 'Mật khẩu phải có ít nhất 1 ký tự đặc biệt.';
        }

        if (preg_match('/\s/', $newPassword)) {
            return 'Mật khẩu không được chứa khoảng trắng.';
        }

        if ($rejectUsernameInPassword && $username !== '' && stripos($newPassword, $username) !== false) {
            return 'Mật khẩu không được chứa tên đăng nhập.';
        }

        if ($newPassword === $oldPassword) {
            return 'Mật khẩu mới phải khác mật khẩu cũ.';
        }

        return null;
    }

    private function destroySession(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
    }
}
