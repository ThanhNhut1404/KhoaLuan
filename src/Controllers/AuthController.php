<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\UserModel;

class AuthController
{
    private UserModel $users;

    public function __construct(?UserModel $users = null)
    {
        $this->users = $users ?? new UserModel(Database::getConnection());
    }

    public function loginAdmin(string $username, string $password): array
    {
        $state = [
            'error' => '',
            'success' => '',
            'redirectToAdmin' => false,
        ];

        if ($username === '' || $password === '') {
            $state['error'] = 'Sai tên Đăng nhập hoặc Mật khẩu';
            return $state;
        }

        $account = $this->users->findActiveByCredentials($username, $password);
        if (!$account) {
            $state['error'] = 'Sai tên Đăng nhập hoặc Mật khẩu';
            return $state;
        }

        $roles = $this->users->getRoles($username);
        $role = $roles[0] ?? null;
        if (!$role) {
            $state['error'] = 'Tài khoản chưa được gán vai trò.';
            return $state;
        }

        $profile = $this->users->getProfileForRole($username, $role['TEN_VAI_TRO']);
        if (!$profile) {
            $state['error'] = 'Tài khoản chưa có hồ sơ hợp lệ.';
            return $state;
        }

        session_regenerate_id(true);
        $_SESSION['admin'] = [
            'user_id' => $username,
            'username' => $username,
            'role_id' => $role['MA_VAI_TRO'],
            'role_code' => $role['TEN_VAI_TRO'],
            'role_name' => $role['TEN_VAI_TRO'],
            'roles' => array_map(static fn(array $item): array => [
                'role_id' => $item['MA_VAI_TRO'],
                'role_code' => $item['TEN_VAI_TRO'],
                'role_name' => $item['TEN_VAI_TRO'],
                'MA_VAI_TRO' => $item['MA_VAI_TRO'],
                'TEN_VAI_TRO' => $item['TEN_VAI_TRO'],
            ], $roles),
            'TEN_DANG_NHAP' => $username,
            'MA_VAI_TRO' => $role['MA_VAI_TRO'],
            'TEN_VAI_TRO' => $role['TEN_VAI_TRO'],
            'TEN_BANG_HO_SO' => $profile['TEN_BANG_HO_SO'],
            'MA_HO_SO' => $profile['MA_HO_SO'],
        ];
        $_SESSION['admin_last_activity_at'] = time();

        $this->users->updateLastLogin($username);

        $state['success'] = 'Đăng nhập thành công';
        $state['redirectToAdmin'] = true;

        return $state;
    }

    public function loginStudent(string $mssv, string $password): array
    {
        if ($mssv === 'test' && $password === 'password') {
            $_SESSION['student'] = [
                'mssv' => $mssv,
                'ho_ten' => 'Sinh viên mẫu',
            ];

            return [
                'error' => '',
                'redirect' => true,
            ];
        }

        return [
            'error' => 'MSSV hoặc mật khẩu không đúng.',
            'redirect' => false,
        ];
    }
}
