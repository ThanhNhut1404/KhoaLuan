<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\StudentModel;
use Throwable;

class StudentController
{
    private const LIST_PER_PAGE = 10;

    private const STATUS_OPTIONS = [
        ['value' => 'Đang học', 'label' => 'Đang học'],
        ['value' => 'Tạm ngừng', 'label' => 'Tạm ngừng'],
        ['value' => 'Kết thúc', 'label' => 'Kết thúc'],
    ];

    public function __construct(private ?StudentModel $model = null)
    {
        $this->model = $this->model ?? new StudentModel(Database::getConnection());
    }

    public function handle(string $page, array $post, array $get, string $method): array
    {
        if ($page === 'list_students') {
            return $this->listStudent($get);
        }

        if ($page === 'create_student') {
            return $this->createStudent($post, $method);
        }

        if ($page === 'edit_student') {
            return $this->editStudent((int) ($get['id'] ?? 0), $post, $method);
        }

        if ($page === 'delete_student') {
            return $this->deleteStudent((int) ($post['student_id'] ?? 0));
        }

        return [
            'page' => $page,
            'students' => [],
            'formData' => [],
            'errors' => [],
            'classes' => [],
            'statusOptions' => self::STATUS_OPTIONS,
            'pagination' => [],
            'toast' => null,
            'redirect' => null,
            'emptyMessage' => 'Chưa có sinh viên nào.',
        ];
    }

    public function listStudent(array $get): array
    {
        $page = max(1, (int) ($get['page_num'] ?? 1));
        $state = [
            'page' => 'list_students',
            'students' => [],
            'pagination' => [
                'current_page' => $page,
                'total_items' => 0,
                'items_per_page' => self::LIST_PER_PAGE,
                'total_pages' => 1,
                'from' => 0,
                'to' => 0,
            ],
            'toast' => null,
            'emptyMessage' => 'Chưa có sinh viên nào.',
        ];

        try {
            $totalItems = $this->model->countAll();
            $totalPages = max(1, (int) ceil($totalItems / self::LIST_PER_PAGE));
            $page = min(max(1, $page), $totalPages);
            $students = $totalItems > 0 ? $this->model->listPaginated($page, self::LIST_PER_PAGE) : [];

            $state['students'] = $students;
            $state['pagination'] = [
                'current_page' => $page,
                'total_items' => $totalItems,
                'items_per_page' => self::LIST_PER_PAGE,
                'total_pages' => $totalPages,
                'from' => $totalItems === 0 ? 0 : (($page - 1) * self::LIST_PER_PAGE) + 1,
                'to' => min($totalItems, $page * self::LIST_PER_PAGE),
            ];

            return $state;
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $state['toast'] = ['type' => 'error', 'message' => 'Không thể tải danh sách sinh viên. Vui lòng thử lại.'];
            return $state;
        }
    }

    public function createStudent(array $post, string $method): array
    {
        $state = [
            'page' => 'create_student',
            'formData' => $method === 'POST' ? $this->normalizeFormData($post) : [],
            'errors' => [],
            'options' => $this->safeCreateOptions(),
            'statusOptions' => self::STATUS_OPTIONS,
            'toast' => null,
            'redirect' => null,
        ];

        // Map options to DB-like keys so views can use MA_KHOA, TEN_KHOA, etc.
        try {
            $opts = $state['options'];
            $state['listKhoa'] = array_map(fn($d) => ['MA_KHOA' => $d['id'] ?? '', 'TEN_KHOA' => $d['name'] ?? ''], $opts['departments'] ?? []);
            $state['listNganh'] = array_map(fn($m) => [
                'MA_NGANH' => $m['id'] ?? '',
                'MA_KHOA' => $m['department_id'] ?? '',
                'TEN_NGANH' => $m['name'] ?? '',
            ], $opts['majors'] ?? []);
            $state['listNienKhoa'] = array_map(fn($a) => ['MA_NIEN_KHOA' => $a['id'] ?? '', 'TEN_NIEN_KHOA' => $a['name'] ?? ''], $opts['academic_years'] ?? []);
            $state['listLop'] = array_map(fn($c) => [
                'MA_LOP' => $c['id'] ?? '',
                'TEN_LOP' => $c['name'] ?? '',
                'MA_KHOA' => $c['department_id'] ?? '',
                'MA_NGANH' => $c['major_id'] ?? '',
                'MA_NIEN_KHOA' => $c['year_id'] ?? '',
            ], $opts['classes'] ?? []);
        } catch (Throwable $e) {
            // if mapping fails, ensure lists exist
            $state['listKhoa'] = $state['listNganh'] = $state['listNienKhoa'] = $state['listLop'] = [];
        }

        if ($method !== 'POST') {
            return $state;
        }

        $state['errors'] = $this->validateCreate($state['formData']);
        if (!empty($state['errors'])) {
            return $state;
        }

        try {
            $created = $this->model->createStudent($state['formData']);
            if (is_array($created) && ($created['created'] ?? false)) {
                $state['toast'] = ['type' => 'success', 'message' => 'Tạo sinh viên thành công.'];
                $state['createdMssv'] = $created['mssv'] ?? null;
                $state['createdPassword'] = $created['password'] ?? null;
                $state['redirect'] = '?page=list_students';
            } else {
                $state['toast'] = ['type' => 'error', 'message' => 'Tạo sinh viên thất bại. Vui lòng thử lại.'];
            }
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi tạo tài khoản: ' . $exception->getMessage()];
            if ($this->model->isConstraintException($exception)) {
                $state['errors']['class_id'] = 'Lớp học không hợp lệ hoặc không tồn tại.';
            }
        }

        return $state;
    }

    public function editStudent(int $id, array $post, string $method): array
    {
        $state = [
            'page' => 'edit_student',
            'formData' => [],
            'errors' => [],
            'classes' => $this->safeClassOptions(),
            'statusOptions' => self::STATUS_OPTIONS,
            'toast' => null,
            'redirect' => null,
            'isEdit' => true,
        ];

        if ($id < 1) {
            $state['toast'] = ['type' => 'error', 'message' => 'Sinh viên không hợp lệ.'];
            $state['redirect'] = '?page=list_students';
            return $state;
        }

        if ($method === 'POST') {
            $state['formData'] = $this->normalizeFormData($post);
            $state['errors'] = $this->validateEdit($id, $state['formData']);
            if (!empty($state['errors'])) {
                return $state;
            }

            try {
                $updated = $this->model->updateStudent($id, $state['formData']);
                $state['toast'] = [
                    'type' => $updated ? 'success' : 'error',
                    'message' => $updated ? 'Cập nhật sinh viên thành công.' : 'Cập nhật sinh viên thất bại.',
                ];
                if ($updated) {
                    $state['redirect'] = '?page=list_students';
                }
            } catch (Throwable $exception) {
                error_log($exception->getMessage());
                $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi cập nhật sinh viên. Vui lòng thử lại.'];
                if ($this->model->isConstraintException($exception)) {
                    $state['errors']['class_id'] = 'Lớp học không hợp lệ hoặc không tồn tại.';
                }
            }

            return $state;
        }

        try {
            $student = $this->model->findById($id);
            if ($student === null) {
                $state['toast'] = ['type' => 'error', 'message' => 'Sinh viên không tồn tại.'];
                $state['redirect'] = '?page=list_students';
                return $state;
            }

            $state['formData'] = [
                'username' => $student['username'] ?? '',
                'class_id' => (string) ($student['class_id'] ?? ''),
                'birth_date' => $student['birth_date'] ?? '',
                'gender' => $student['gender'] ?? '',
                'email' => $student['email'] ?? '',
                'phone' => $student['phone'] ?? '',
                'address' => $student['address'] ?? '',
                'status' => $student['status'] ?? '',
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $state['toast'] = ['type' => 'error', 'message' => 'Không thể tải dữ liệu sinh viên.'];
            $state['redirect'] = '?page=list_students';
        }

        return $state;
    }

    public function deleteStudent(int $id): array
    {
        $state = [
            'page' => 'list_students',
            'toast' => null,
            'redirect' => '?page=list_students',
        ];

        if ($id < 1) {
            $state['toast'] = ['type' => 'error', 'message' => 'Sinh viên không hợp lệ.'];
            return $state;
        }

        try {
            $deleted = $this->model->deleteStudent($id);
            $state['toast'] = [
                'type' => $deleted ? 'success' : 'error',
                'message' => $deleted ? 'Xóa sinh viên thành công.' : 'Xóa sinh viên thất bại.',
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi xóa sinh viên. Vui lòng thử lại.'];
        }

        return $state;
    }

    private function safeClassOptions(): array
    {
        try {
            return $this->model->loadCreateOptions();
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return ['departments' => [], 'majors' => [], 'academic_years' => [], 'classes' => []];
        }
    }

    private function safeCreateOptions(): array
    {
        try {
            return $this->model->loadCreateOptions();
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return ['departments' => [], 'majors' => [], 'academic_years' => [], 'classes' => []];
        }
    }

    private function normalizeFormData(array $data): array
    {
        $addressLines = [
            trim((string) ($data['address_line1'] ?? '')),
            trim((string) ($data['address_line2'] ?? '')),
            trim((string) ($data['address_line3'] ?? '')),
            trim((string) ($data['address_province'] ?? '')),
        ];

        $address = implode(', ', array_filter($addressLines, static fn(string $line): bool => $line !== ''));

        return [
            'full_name' => trim((string) ($data['full_name'] ?? '')),
            'department_id' => trim((string) ($data['department_id'] ?? '')),
            'major_id' => trim((string) ($data['major_id'] ?? '')),
            'academic_year_id' => trim((string) ($data['academic_year_id'] ?? '')),
            'academic_year' => trim((string) ($data['academic_year_id'] ?? '')),
            'username' => trim((string) ($data['username'] ?? '')),
            'password' => $data['password'] ?? '',
            'class_id' => trim((string) ($data['class_id'] ?? '')),
            'birth_date' => trim((string) ($data['birth_date'] ?? '')),
            'gender' => trim((string) ($data['gender'] ?? '')),
            'email' => trim((string) ($data['email'] ?? '')),
            'phone' => trim((string) ($data['phone'] ?? '')),
            'address_line1' => $addressLines[0],
            'address_line2' => $addressLines[1],
            'address_line3' => $addressLines[2],
            'address_province' => $addressLines[3],
            'address' => $address,
            'status' => trim((string) ($data['status'] ?? 'Đang học')),
        ];
    }

    private function validateCreate(array $form): array
    {
        $errors = [];

        if ($form['full_name'] === '') {
            $errors['full_name'] = 'Vui lòng nhập họ và tên.';
        }

        if ($form['department_id'] === '' || !ctype_digit($form['department_id'])) {
            $errors['department_id'] = 'Vui lòng chọn khoa/bộ môn hợp lệ.';
        }

        if ($form['major_id'] === '' || !ctype_digit($form['major_id'])) {
            $errors['major_id'] = 'Vui lòng chọn ngành học hợp lệ.';
        }

        if ($form['academic_year_id'] === '' || !ctype_digit($form['academic_year_id'])) {
            $errors['academic_year_id'] = 'Vui lòng chọn niên khóa hợp lệ.';
        }

        if ($form['full_name'] !== '' && strlen($form['full_name']) < 5) {
            $errors['full_name'] = 'Họ và tên phải dài ít nhất 5 ký tự.';
        }

        // Username and password are generated automatically from MSSV and student full name.

        if ($form['birth_date'] === '') {
            $errors['birth_date'] = 'Vui lòng nhập ngày sinh.';
        }

        if ($form['gender'] === '') {
            $errors['gender'] = 'Vui lòng chọn giới tính.';
        }

        if ($form['email'] === '') {
            $errors['email'] = 'Vui lòng nhập email.';
        } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        } elseif ($this->model->emailExists($form['email'])) {
            $errors['email'] = 'Email đã tồn tại.';
        }

        if ($form['phone'] === '') {
            $errors['phone'] = 'Vui lòng nhập số điện thoại.';
        }

        if ($form['address_line1'] === '') {
            $errors['address_line1'] = 'Vui lòng nhập số nhà.';
        }
        if ($form['address_line2'] === '') {
            $errors['address_line2'] = 'Vui lòng nhập đường / ấp / khóm.';
        }
        if ($form['address_line3'] === '') {
            $errors['address_line3'] = 'Vui lòng nhập xã / phường.';
        }
        if ($form['address_province'] === '') {
            $errors['address_province'] = 'Vui lòng nhập tỉnh / thành phố.';
        }

        if ($form['class_id'] === '' || !ctype_digit($form['class_id'])) {
            $errors['class_id'] = 'Vui lòng chọn lớp học hợp lệ.';
        }

        return $errors;
    }

    private function validateEdit(int $id, array $form): array
    {
        $errors = [];

        if ($form['username'] === '') {
            $errors['username'] = 'Vui lòng nhập tên đăng nhập.';
        } elseif (!preg_match('/^[A-Za-z0-9_]{5,50}$/', $form['username'])) {
            $errors['username'] = 'Tên đăng nhập chỉ gồm chữ cái, số, dấu gạch dưới và dài từ 5 đến 50 ký tự.';
        } elseif ($this->model->usernameExists($form['username'], $id)) {
            $errors['username'] = 'Tên đăng nhập đã tồn tại.';
        }

        if ($form['birth_date'] === '') {
            $errors['birth_date'] = 'Vui lòng nhập ngày sinh.';
        }

        if ($form['gender'] === '') {
            $errors['gender'] = 'Vui lòng chọn giới tính.';
        }

        if ($form['email'] === '') {
            $errors['email'] = 'Vui lòng nhập email.';
        } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        } elseif ($this->model->emailExists($form['email'], $id)) {
            $errors['email'] = 'Email đã tồn tại.';
        }

        if ($form['phone'] === '') {
            $errors['phone'] = 'Vui lòng nhập số điện thoại.';
        }

        if ($form['address'] === '') {
            $errors['address'] = 'Vui lòng nhập địa chỉ.';
        }

        if ($form['class_id'] === '' || !ctype_digit($form['class_id'])) {
            $errors['class_id'] = 'Vui lòng chọn lớp học hợp lệ.';
        }

        return $errors;
    }
}
