<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\AccountModel;

class AccountController
{
    private AccountModel $accounts;

    public function __construct(?AccountModel $accounts = null)
    {
        $this->accounts = $accounts ?? new AccountModel(Database::getConnection());
    }

    public function create(array $data, string $method): array
    {
        $options = $this->accounts->loadCreateOptions();
        $state = [
            'options' => $options,
            'formData' => $method === 'POST' ? $data : $this->buildInitialFormData($data, $options),
            'errors' => [],
            'toast' => null,
        ];

        if ($method !== 'POST') {
            return $state;
        }

        $role = $this->accounts->getRoleById($data['role_id'] ?? '');
        if (($role['TEN_VAI_TRO'] ?? '') === 'SINH_VIEN') {
            $state['errors'] = [
                'role_id' => 'Không được cấp tài khoản sinh viên tại chức năng này.',
            ];

            return $state;
        }

        $state['errors'] = $this->validate($data, $role);

        if (!empty($state['errors']) || $role === null) {
            return $state;
        }

        $created = $this->accounts->createWithProfile($data, $role);
        $state['toast'] = [
            'type' => $created ? 'success' : 'error',
            'message' => $created ? 'Tạo tài khoản thành công.' : 'Tạo tài khoản thất bại. Vui lòng thử lại sau.',
        ];

        if ($created) {
            $state['formData'] = [];
        }

        return $state;
    }

    public function listing(array $post, string $method): array
    {
        $toast = null;

        if ($method === 'POST' && isset($post['status']) && is_array($post['status'])) {
            $this->accounts->updateStatuses($post['status']);
            $toast = ['type' => 'success', 'message' => 'Cập nhật trạng thái tài khoản thành công.'];
        }

        return [
            'accounts' => $this->accounts->getAccountRows(),
            'roles' => $this->accountRoleNames(),
            'toast' => $toast,
        ];
    }

    public function edit(array $get, array $post, string $method): array
    {
        $username = trim((string) ($get['username'] ?? $get['id'] ?? ''));
        $account = $username !== '' ? $this->accounts->getAccountForEdit($username) : null;
        $state = [
            'roles' => $this->accounts->getRoleOptions(),
            'formData' => $method === 'POST' ? array_merge($account ?? [], $post) : ($account ?? []),
            'errors' => [],
            'toast' => null,
            'notFound' => $account === null,
        ];

        if ($account === null || $method !== 'POST') {
            return $state;
        }

        $state['errors'] = $this->validateEdit($post, $username);
        if (!empty($state['errors'])) {
            return $state;
        }

        $updated = $this->accounts->updateAccount($username, $post);
        $state['toast'] = [
            'type' => $updated ? 'success' : 'error',
            'message' => $updated ? 'Cập nhật tài khoản thành công.' : 'Cập nhật tài khoản thất bại. Vui lòng thử lại sau.',
        ];

        if ($updated) {
            $state['formData'] = $this->accounts->getAccountForEdit($username) ?? $state['formData'];
        }

        return $state;
    }

    private function buildInitialFormData(array $data, array $options): array
    {
        $formData = [];

        if (!empty($data['role_id'])) {
            $formData['role_id'] = $data['role_id'];
        } elseif (!empty($data['role_name'])) {
            $roleId = $this->getRoleIdByName($options['roles'], trim($data['role_name']));
            if ($roleId !== null) {
                $formData['role_id'] = $roleId;
            }
        }

        return $formData;
    }

    private function getRoleIdByName(array $roles, string $roleName): ?string
    {
        foreach ($roles as $role) {
            if ($role['TEN_VAI_TRO'] === $roleName) {
                return (string) $role['MA_VAI_TRO'];
            }
        }

        return null;
    }

    private function accountRoleNames(): array
    {
        return array_values(array_filter(array_map(
            static fn(array $role): string => (string) ($role['name'] ?? ''),
            $this->accounts->getRoleOptions()
        )));
    }

    private function validate(array $data, ?array $role): array
    {
        $errors = [];
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';
        $email = trim((string) ($data['email'] ?? ''));
        $roleName = $role['TEN_VAI_TRO'] ?? '';

        if ($username === '') {
            $errors['username'] = $roleName === 'SINH_VIEN' ? 'Vui lòng nhập MSSV.' : 'Vui lòng nhập tên đăng nhập.';
        } elseif (!preg_match('/^[A-Za-z0-9_]{5,50}$/', $username)) {
            $errors['username'] = 'Tên đăng nhập chỉ gồm chữ cái, số, dấu gạch dưới và dài từ 5 đến 50 ký tự.';
        } elseif ($this->accounts->valueExists('nguoi_dung', 'TEN_DANG_NHAP', $username)) {
            $errors['username'] = 'Tên đăng nhập đã tồn tại.';
        }

        if ($email === '') {
            $errors['email'] = 'Vui lòng nhập Email tài khoản.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email tài khoản không hợp lệ.';
        } elseif ($this->accounts->accountEmailExists($email)) {
            $errors['email'] = 'Email tài khoản đã tồn tại.';
        }

        $passwordError = $this->validatePassword($password, $username);
        if ($passwordError !== null) {
            $errors['password'] = $passwordError;
        }

        if ($confirmPassword === '') {
            $errors['confirm_password'] = 'Vui lòng xác nhận mật khẩu.';
        } elseif ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Xác nhận mật khẩu không khớp.';
        }

        if (!$role) {
            $errors['role_id'] = 'Vui lòng chọn vai trò hợp lệ.';
            return $errors;
        }

        switch ($roleName) {
            case 'SINH_VIEN':
                $this->required($errors, $data, 'full_name', 'Vui lòng nhập họ và tên.');
                $this->required($errors, $data, 'gender', 'Vui lòng chọn giới tính.');
                $this->required($errors, $data, 'birth_date', 'Vui lòng nhập ngày sinh.');
                $this->required($errors, $data, 'email', 'Vui lòng nhập email.');
                $this->required($errors, $data, 'phone', 'Vui lòng nhập số điện thoại.');
                $this->required($errors, $data, 'address', 'Vui lòng nhập địa chỉ.');
                $this->required($errors, $data, 'class_id', 'Vui lòng chọn lớp học.');
                if ($username !== '' && $this->accounts->valueExists('sinh_vien', 'MSSV', $username)) {
                    $errors['username'] = 'MSSV đã tồn tại.';
                }
                if (empty($errors['email']) && !empty($data['email']) && $this->accounts->valueExists('sinh_vien', 'EMAIL_SV', trim($data['email']))) {
                    $errors['email'] = 'Email sinh viên đã tồn tại.';
                }
                break;

            case 'GIANG_VIEN':
            case 'CO_VAN_HOC_TAP':
            case 'BO_MON':
            case 'KHOA':
                $this->required($errors, $data, 'full_name', 'Vui lòng nhập họ và tên.');
                $this->required($errors, $data, 'gender', 'Vui lòng chọn giới tính.');
                $this->required($errors, $data, 'birth_date', 'Vui lòng nhập ngày sinh.');
                $this->required($errors, $data, 'email', 'Vui lòng nhập email.');
                $this->required($errors, $data, 'phone', 'Vui lòng nhập số điện thoại.');
                $this->required($errors, $data, 'department_id', 'Vui lòng chọn khoa/bộ môn.');
                if (empty($errors['email']) && !empty($data['email']) && $this->accounts->valueExists('giang_vien', 'EMAIL_GV', trim($data['email']))) {
                    $errors['email'] = 'Email giảng viên đã tồn tại.';
                }
                break;

            case 'CAN_BO_LOP':
                $this->required($errors, $data, 'student_id', 'Vui lòng chọn sinh viên.');
                $this->required($errors, $data, 'class_id', 'Vui lòng chọn lớp học.');
                $this->required($errors, $data, 'class_position', 'Vui lòng nhập chức vụ cán bộ lớp.');
                if (!empty($data['class_id']) && !empty($data['class_position'])
                    && $this->accounts->classPositionExists($data['class_id'], trim($data['class_position']))) {
                    $errors['class_position'] = 'Chức vụ này đã tồn tại trong lớp đã chọn.';
                }
                break;

            case 'DOAN_KHOA':
                $this->required($errors, $data, 'union_faculty_name', 'Vui lòng nhập tên Đoàn khoa.');
                $this->required($errors, $data, 'email', 'Vui lòng nhập email.');
                $this->required($errors, $data, 'department_id', 'Vui lòng chọn khoa/bộ môn.');
                break;

            case 'LIEN_CHI':
                $this->required($errors, $data, 'club_name', 'Vui lòng nhập tên Liên chi / CLB.');
                $this->required($errors, $data, 'union_id', 'Vui lòng chọn Đoàn trường quản lý.');
                break;

            case 'DOAN_TRUONG':
                $this->required($errors, $data, 'union_name', 'Vui lòng nhập tên Đoàn trường.');
                $this->required($errors, $data, 'email', 'Vui lòng nhập email.');
                break;

            default:
                $errors['role_id'] = 'Vai trò này chưa được hỗ trợ cấp tài khoản.';
                break;
        }

        return $errors;
    }

    private function validateEdit(array $data, string $currentUsername): array
    {
        $errors = [];
        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $confirmPassword = (string) ($data['confirm_password'] ?? '');

        if ($email === '') {
            $errors['email'] = 'Vui lòng nhập Email tài khoản.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email tài khoản không hợp lệ.';
        } elseif ($this->accounts->accountEmailExists($email, $currentUsername)) {
            $errors['email'] = 'Email tài khoản đã tồn tại.';
        }

        if ($password !== '') {
            $passwordError = $this->validatePassword($password, $currentUsername);
            if ($passwordError !== null) {
                $errors['password'] = $passwordError;
            }

            if ($confirmPassword === '') {
                $errors['confirm_password'] = 'Vui lòng xác nhận mật khẩu.';
            } elseif ($password !== $confirmPassword) {
                $errors['confirm_password'] = 'Xác nhận mật khẩu không khớp.';
            }
        }

        return $errors;
    }

    private function validatePassword(string $password, string $username): ?string
    {
        if ($password === '') {
            return 'Vui lòng nhập mật khẩu.';
        }

        if (strlen($password) < 8) {
            return 'Mật khẩu phải có ít nhất 8 ký tự.';
        }

        if (!preg_match('/[a-z]/', $password)) {
            return 'Mật khẩu phải có ít nhất 1 chữ thường.';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return 'Mật khẩu phải có ít nhất 1 chữ in hoa.';
        }

        if (!preg_match('/[0-9]/', $password)) {
            return 'Mật khẩu phải có ít nhất 1 chữ số.';
        }

        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            return 'Mật khẩu phải có ít nhất 1 ký tự đặc biệt.';
        }

        if (preg_match('/\s/', $password)) {
            return 'Mật khẩu không được chứa khoảng trắng.';
        }

        if ($username !== '' && stripos($password, $username) !== false) {
            return 'Mật khẩu không được chứa tên đăng nhập.';
        }

        return null;
    }

    private function required(array &$errors, array $data, string $field, string $message): void
    {
        if (trim($data[$field] ?? '') === '') {
            $errors[$field] = $message;
        }
    }
}
