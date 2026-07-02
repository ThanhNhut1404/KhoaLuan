<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\StudentModel;
use Throwable;

class StudentController
{
    private const LIST_PER_PAGE = 10;
    private const SESSION_TIMEOUT_SECONDS = 1800;

    private const STATUS_OPTIONS = [
        ['value' => 'Đang học', 'label' => 'Đang học'],
        ['value' => 'Tạm ngừng', 'label' => 'Tạm ngừng'],
        ['value' => 'Kết thúc', 'label' => 'Kết thúc'],
    ];

    private const ALLOWED_STATUS_VALUES = ['Đang học', 'Tạm ngừng', 'Kết thúc'];

    public function __construct(private ?StudentModel $model = null)
    {
        $this->model = $this->model ?? new StudentModel(Database::getConnection());
    }

    public function login(string $error = '', string $username = '', string $success = '', bool $redirectToStudent = false): void
    {
        if (!empty($_SESSION['student_logged_in']) && !$redirectToStudent) {
            $this->redirectToStudentDashboard();
        }

        $flash = $_SESSION['student_login_flash'] ?? null;
        if (is_array($flash)) {
            $message = (string) ($flash['message'] ?? '');
            if (($flash['type'] ?? '') === 'success') {
                $success = $message;
            } elseif (($flash['type'] ?? '') === 'error') {
                $error = $message;
            }
            unset($_SESSION['student_login_flash']);
        }

        $this->sendNoCacheHeaders();
        $title = 'Đăng nhập sinh viên';
        require __DIR__ . '/../views/Frontend/login.php';
    }

    public function handleLogin(): void
    {
        $username = trim((string) ($_POST['mssv'] ?? $_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '') {
            $this->login('Vui lòng nhập MSSV/tên đăng nhập.', $username);
            return;
        }

        if ($password === '') {
            $this->login('Vui lòng nhập mật khẩu.', $username);
            return;
        }

        try {
            $account = $this->model->findStudentAccountForLogin($username);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $this->login('Lỗi đăng nhập: ' . $exception->getMessage(), $username);
            return;
        }

        if ($account === null) {
            $this->login('Tài khoản sinh viên hoặc mật khẩu không đúng.', $username);
            return;
        }

        if ((string) ($account['role_name'] ?? '') !== 'SINH_VIEN') {
            $this->login('Tài khoản không có quyền truy cập cổng sinh viên.', $username);
            return;
        }

        if (!$this->isActiveAccount((string) ($account['account_status'] ?? ''))) {
            $this->login('Tài khoản sinh viên đang bị khóa hoặc ngừng hoạt động.', $username);
            return;
        }

        if (!password_verify($password, (string) ($account['password_hash'] ?? ''))) {
            $this->login('Tài khoản sinh viên hoặc mật khẩu không đúng.', $username);
            return;
        }

        session_regenerate_id(true);

        $studentName = trim((string) ($account['ho_ten'] ?? ''));
        if ($studentName === '') {
            $studentName = (string) ($account['mssv'] ?? $account['username'] ?? 'Sinh viên');
        }

        $_SESSION['student_logged_in'] = true;
        $_SESSION['student_id'] = (int) ($account['student_id'] ?? 0);
        $_SESSION['student_username'] = (string) ($account['username'] ?? '');
        $_SESSION['student_mssv'] = (string) ($account['mssv'] ?? '');
        $_SESSION['student_name'] = $studentName;
        $_SESSION['student_role'] = 'SINH_VIEN';
        $_SESSION['student_last_activity'] = time();

        $this->login('', $username, 'Đăng nhập thành công.', true);
    }

    public function logout(): void
    {
        $this->clearStudentSession();
        $_SESSION['student_login_flash'] = [
            'type' => 'success',
            'message' => 'Đăng xuất thành công.',
        ];

        header('Location: /KhoaLuan/public/student.php?action=login');
        exit;
    }

    public function requireStudentLogin(): void
    {
        if (empty($_SESSION['student_logged_in']) || ($_SESSION['student_role'] ?? '') !== 'SINH_VIEN') {
            header('Location: /KhoaLuan/public/student.php?action=login');
            exit;
        }

        $lastActivity = (int) ($_SESSION['student_last_activity'] ?? 0);
        if ($lastActivity > 0 && (time() - $lastActivity) > self::SESSION_TIMEOUT_SECONDS) {
            $this->clearStudentSession();
            $_SESSION['student_login_flash'] = [
                'type' => 'error',
                'message' => 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.',
            ];
            header('Location: /KhoaLuan/public/student.php?action=login');
            exit;
        }

        $_SESSION['student_last_activity'] = time();
    }

    public function dashboard(): void
    {
        $this->renderStudentPage('dashboard', 'Hệ thống Quản lý điểm rèn luyện');
    }

    public function profile(): void
    {
        $this->renderStudentPage('profile', 'Thông tin sinh viên');
    }

    public function phieudanhgia(): void
    {
        $this->renderStudentPage('phieudanhgia', 'Phiếu đánh giá');
    }

    public function lichhoatdong(): void
    {
        $this->renderStudentPage('lichhoatdong', 'Lịch hoạt động');
    }

    public function dangkyhoatdong(): void
    {
        $this->renderStudentPage('dangkyhoatdong', 'Đăng ký hoạt động');
    }

    public function hoatdongdathamgia(): void
    {
        $this->renderStudentPage('hoatdongdathamgia', 'Hoạt động đã tham gia');
    }

    public function hoatdongdangky(): void
    {
        $this->renderStudentPage('hoatdongdangky', 'Hoạt động đã đăng ký');
    }

    public function ketquarenluyen(): void
    {
        $this->renderStudentPage('ketquarenluyen', 'Kết quả rèn luyện');
    }

    public function thongbao(): void
    {
        $this->renderStudentPage('thongbao', 'Thông báo');
    }

    public function handleChangePassword(): void
    {
        $this->requireStudentLogin();

        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
        $errors = [];

        if ($currentPassword === '') {
            $errors['current_password'] = 'Vui lòng nhập mật khẩu cũ.';
        }
        if ($newPassword === '') {
            $errors['new_password'] = 'Vui lòng nhập mật khẩu mới.';
        } elseif (strlen($newPassword) < 6) {
            $errors['new_password'] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
        }
        if ($confirmPassword === '') {
            $errors['confirm_password'] = 'Vui lòng xác nhận mật khẩu mới.';
        } elseif ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'Xác nhận mật khẩu không khớp.';
        }

        $username = (string) ($_SESSION['student_username'] ?? '');
        $account = null;

        if (empty($errors)) {
            try {
                $account = $this->model->findStudentAccountForLogin($username);
            } catch (Throwable $exception) {
                error_log($exception->getMessage());
                $errors['current_password'] = 'Không thể kiểm tra mật khẩu lúc này.';
            }

            if ($account === null || !password_verify($currentPassword, (string) ($account['password_hash'] ?? ''))) {
                $errors['current_password'] = 'Mật khẩu cũ không đúng.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['student_change_password_errors'] = $errors;
            header('Location: /KhoaLuan/public/student.php?action=profile');
            exit;
        }

        try {
            $updated = $this->model->updateAccountPassword($username, password_hash($newPassword, PASSWORD_DEFAULT));
            $_SESSION['student_password_toast'] = [
                'type' => $updated ? 'success' : 'error',
                'message' => $updated ? 'Đổi mật khẩu thành công.' : 'Đổi mật khẩu thất bại. Vui lòng thử lại.',
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $_SESSION['student_password_toast'] = [
                'type' => 'error',
                'message' => 'Có lỗi khi đổi mật khẩu. Vui lòng thử lại.',
            ];
        }

        header('Location: /KhoaLuan/public/student.php?action=profile');
        exit;
    }

    public function handle(string $page, array $post, array $get, string $method): array
    {
        if ($page === 'list_students') {
            if ($method === 'POST' && ($post['action'] ?? '') === 'status') {
                return $this->changeStudentStatus((int) ($post['_row_id'] ?? 0), (string) ($post['status'][$post['_row_id'] ?? ''] ?? ''), $get);
            }

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
        $filters = $this->normalizeListFilters($get);
        $hasFilters = $this->hasListFilters($filters);
        $state = [
            'page' => 'list_students',
            'students' => [],
            'filters' => $filters,
            'filterOptions' => $this->safeListFilterOptions(),
            'pagination' => [
                'current_page' => $page,
                'total_items' => 0,
                'items_per_page' => self::LIST_PER_PAGE,
                'total_pages' => 1,
                'from' => 0,
                'to' => 0,
            ],
            'toast' => null,
            'statusOptions' => self::STATUS_OPTIONS,
            'emptyMessage' => 'Chưa có sinh viên nào.',
        ];

        try {
            $totalItems = $hasFilters ? $this->model->countFiltered($filters) : $this->model->countAll();
            $totalPages = max(1, (int) ceil($totalItems / self::LIST_PER_PAGE));
            $page = min(max(1, $page), $totalPages);
            $students = $totalItems > 0
                ? ($hasFilters
                    ? $this->model->listFilteredPaginated($page, self::LIST_PER_PAGE, $filters)
                    : $this->model->listPaginated($page, self::LIST_PER_PAGE))
                : [];

            $state['students'] = $students;
            $state['emptyMessage'] = $hasFilters ? 'Không có sinh viên phù hợp.' : 'Chưa có sinh viên nào.';
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

        $this->mapStudentOptionLists($state);

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
                $state['formData'] = [];
            } else {
                $state['toast'] = ['type' => 'error', 'message' => 'Tạo sinh viên thất bại. Vui lòng thử lại.'];
            }
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi tạo tài khoản: ' . $exception->getMessage()];
            if (strpos($exception->getMessage(), 'Ngành học') !== false) {
                $state['errors']['major_id'] = $exception->getMessage();
            }
            if (strpos($exception->getMessage(), 'Niên khóa') !== false) {
                $state['errors']['academic_year_id'] = $exception->getMessage();
            }
            if (strpos($exception->getMessage(), 'Lớp học') !== false) {
                $state['errors']['class_id'] = $exception->getMessage();
            }
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
            'options' => $this->safeCreateOptions(),
            'statusOptions' => self::STATUS_OPTIONS,
            'toast' => null,
            'redirect' => null,
            'isEdit' => true,
        ];
        $this->mapStudentOptionLists($state);

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
                $currentStudent = $this->model->findById($id);
                if ($currentStudent === null) {
                    $state['toast'] = ['type' => 'error', 'message' => 'Sinh viên không tồn tại.'];
                    $state['redirect'] = '?page=list_students';
                    return $state;
                }

                if ($this->studentHasNoChanges($currentStudent, $state['formData'])) {
                    $state['toast'] = ['type' => 'info', 'message' => 'Không có thay đổi nào được thực hiện.'];
                    $state['redirect'] = '?page=list_students';
                    return $state;
                }

                $updated = $this->model->updateStudent($id, $state['formData']);
                $state['toast'] = [
                    'type' => $updated ? 'success' : 'info',
                    'message' => $updated ? 'Cập nhật sinh viên thành công.' : 'Không có thay đổi nào được thực hiện.',
                ];
                $state['redirect'] = '?page=list_students';
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

            $addressParts = $this->splitStudentAddress((string) ($student['address'] ?? ''));
            $state['formData'] = [
                'full_name' => $student['full_name'] ?? '',
                'username' => $student['username'] ?? '',
                'mssv' => $student['mssv'] ?? '',
                'department_id' => (string) ($student['department_id'] ?? ''),
                'major_id' => (string) ($student['major_id'] ?? ''),
                'academic_year_id' => (string) ($student['academic_year_id'] ?? ''),
                'class_id' => (string) ($student['class_id'] ?? ''),
                'birth_date' => $student['birth_date'] ?? '',
                'gender' => $student['gender'] ?? '',
                'email' => $student['email'] ?? '',
                'phone' => $student['phone'] ?? '',
                'address_line1' => $addressParts['address_line1'],
                'address_line2' => $addressParts['address_line2'],
                'address_line3' => $addressParts['address_line3'],
                'address_province' => $addressParts['address_province'],
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

    public function changeStudentStatus(int $id, string $status, array $get = []): array
    {
        $state = [
            'page' => 'list_students',
            'toast' => null,
            'redirect' => $this->buildListRedirect($get),
        ];

        if ($id < 1) {
            $state['toast'] = ['type' => 'error', 'message' => 'Sinh viên không hợp lệ.'];
            return $state;
        }

        if (!in_array($status, self::ALLOWED_STATUS_VALUES, true)) {
            $state['toast'] = ['type' => 'error', 'message' => 'Trạng thái sinh viên không hợp lệ.'];
            return $state;
        }

        try {
            $updated = $this->model->updateStudentStatus($id, $status);
            $state['toast'] = [
                'type' => $updated ? 'success' : 'error',
                'message' => $updated ? 'Cập nhật trạng thái sinh viên thành công.' : 'Cập nhật trạng thái sinh viên thất bại.',
            ];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $state['toast'] = ['type' => 'error', 'message' => 'Có lỗi khi cập nhật trạng thái sinh viên. Vui lòng thử lại.'];
        }

        return $state;
    }

    private function renderStudentPage(string $view, string $title): void
    {
        $this->requireStudentLogin();
        $this->sendNoCacheHeaders();

        $student = $this->currentPortalStudent();
        $content = __DIR__ . '/../views/Frontend/' . $view . '.php';

        if (!is_file($content)) {
            http_response_code(404);
            echo 'Không tìm thấy trang sinh viên.';
            return;
        }

        $passwordToast = $_SESSION['student_password_toast'] ?? null;
        $changePasswordErrors = $_SESSION['student_change_password_errors'] ?? [];
        $openChangePasswordModal = !empty($changePasswordErrors);

        unset($_SESSION['student_password_toast'], $_SESSION['student_change_password_errors']);

        require __DIR__ . '/../views/Frontend/layout.php';
    }

    private function currentPortalStudent(): array
    {
        $studentId = (int) ($_SESSION['student_id'] ?? 0);
        $student = null;

        if ($studentId > 0) {
            try {
                $student = $this->model->findPortalStudentById($studentId);
            } catch (Throwable $exception) {
                error_log($exception->getMessage());
            }
        }

        if ($student === null) {
            foreach (['student_username', 'student_mssv'] as $sessionKey) {
                $login = trim((string) ($_SESSION[$sessionKey] ?? ''));
                if ($login === '') {
                    continue;
                }

                try {
                    $student = $this->model->findPortalStudentByLogin($login);
                } catch (Throwable $exception) {
                    error_log($exception->getMessage());
                }

                if ($student !== null) {
                    break;
                }
            }
        }

        if ($student === null) {
            return [];
        }

        $student['ho_ten'] = (string) ($student['ho_ten'] ?? '');
        $student['mssv'] = (string) ($student['mssv'] ?? '');
        $student['username'] = (string) ($student['username'] ?? '');
        $student['lop_hoc'] = (string) ($student['lop_hoc'] ?? '');
        $student['nganh'] = (string) ($student['nganh'] ?? '');
        $student['khoa_hoc'] = (string) ($student['khoa_hoc'] ?? '');
        $student['trang_thai_hoc_tap'] = (string) ($student['trang_thai_hoc_tap'] ?? ($student['trang_thai'] ?? ''));
        $student['trang_thai'] = $student['trang_thai_hoc_tap'];
        $student['ngay_sinh'] = (string) ($student['ngay_sinh'] ?? '');
        $student['gioi_tinh'] = (string) ($student['gioi_tinh'] ?? '');
        $student['email'] = (string) ($student['email'] ?? '');
        $student['so_dien_thoai'] = (string) ($student['so_dien_thoai'] ?? '');
        $student['dia_chi'] = (string) ($student['dia_chi'] ?? ($student['dia_chi_thuong_tru'] ?? ''));
        $student['dia_chi_thuong_tru'] = $student['dia_chi'];
        $student['avatar'] = (string) ($student['avatar'] ?? '');
        return $student;
    }

    private function isActiveAccount(string $status): bool
    {
        $normalized = strtoupper(trim($status));

        return in_array($normalized, ['HOAT_DONG', 'ACTIVE', '1'], true)
            || trim($status) === 'Hoạt động'
            || trim($status) === 'Đang hoạt động';
    }

    private function clearStudentSession(): void
    {
        unset(
            $_SESSION['student_logged_in'],
            $_SESSION['student_id'],
            $_SESSION['student_username'],
            $_SESSION['student_mssv'],
            $_SESSION['student_name'],
            $_SESSION['student_role'],
            $_SESSION['student_last_activity'],
            $_SESSION['student_change_password_errors'],
            $_SESSION['student_password_toast']
        );
    }

    private function sendNoCacheHeaders(): void
    {
        if (headers_sent()) {
            return;
        }

        header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }

    private function redirectToStudentDashboard(): void
    {
        header('Location: /KhoaLuan/public/student.php');
        exit;
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

    private function safeListFilterOptions(): array
    {
        try {
            return $this->model->getListFilterOptions();
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return ['classes' => [], 'academic_years' => []];
        }
    }

    private function mapStudentOptionLists(array &$state): void
    {
        $opts = $state['options'] ?? [];
        $state['listKhoa'] = array_map(fn($d) => [
            'MA_KHOA' => $d['id'] ?? '',
            'TEN_KHOA' => $d['name'] ?? '',
        ], $opts['departments'] ?? []);
        $state['listNganh'] = array_map(fn($m) => [
            'MA_NGANH' => $m['id'] ?? '',
            'MA_KHOA' => $m['department_id'] ?? '',
            'TEN_NGANH' => $m['name'] ?? '',
        ], $opts['majors'] ?? []);
        $state['listNienKhoa'] = array_map(fn($a) => [
            'MA_NIEN_KHOA' => $a['id'] ?? '',
            'TEN_NIEN_KHOA' => $a['name'] ?? '',
        ], $opts['academic_years'] ?? []);
        $state['listLop'] = array_map(fn($c) => [
            'MA_LOP' => $c['id'] ?? '',
            'TEN_LOP' => $c['name'] ?? '',
            'MA_KHOA' => $c['department_id'] ?? '',
            'MA_NGANH' => $c['major_id'] ?? '',
            'MA_NIEN_KHOA' => $c['year_id'] ?? '',
        ], $opts['classes'] ?? []);
    }

    private function normalizeListFilters(array $get): array
    {
        return [
            'keyword' => trim((string) ($get['search'] ?? $get['keyword'] ?? $get['q'] ?? '')),
            'class_id' => trim((string) ($get['class_id'] ?? $get['class'] ?? '')),
            'academic_year' => trim((string) ($get['academic_year'] ?? $get['year'] ?? '')),
            'status' => trim((string) ($get['status'] ?? '')),
        ];
    }

    private function hasListFilters(array $filters): bool
    {
        foreach ($filters as $value) {
            if (trim((string) $value) !== '') {
                return true;
            }
        }

        return false;
    }

    private function buildListRedirect(array $get): string
    {
        $params = $get;
        $params['page'] = 'list_students';

        return '?' . http_build_query($params);
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

    private function studentHasNoChanges(array $student, array $form): bool
    {
        return (int) ($student['class_id'] ?? 0) === (int) $form['class_id']
            && trim((string) ($student['full_name'] ?? '')) === $form['full_name']
            && trim((string) ($student['birth_date'] ?? '')) === $form['birth_date']
            && trim((string) ($student['gender'] ?? '')) === $form['gender']
            && trim((string) ($student['email'] ?? '')) === $form['email']
            && trim((string) ($student['phone'] ?? '')) === $form['phone']
            && trim((string) ($student['address'] ?? '')) === $form['address']
            && trim((string) ($student['status'] ?? '')) === $form['status'];
    }

    private function splitStudentAddress(string $address): array
    {
        $parts = array_map('trim', explode(',', $address));
        $parts = array_pad($parts, 4, '');

        return [
            'address_line1' => $parts[0],
            'address_line2' => $parts[1],
            'address_line3' => $parts[2],
            'address_province' => $parts[3],
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
        } elseif (!$this->model->departmentExists((int) $form['department_id'])) {
            $errors['department_id'] = 'Khoa/bộ môn không tồn tại.';
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

        if (empty($errors['department_id']) && empty($errors['major_id'])) {
            $major = $this->model->findMajorById((int) $form['major_id']);
            if ($major === null) {
                $errors['major_id'] = 'Ngành học không tồn tại.';
            } elseif ((int) $major['department_id'] !== (int) $form['department_id']) {
                $errors['major_id'] = 'Ngành học không thuộc khoa/bộ môn đã chọn.';
            }
        }

        if (empty($errors['department_id']) && empty($errors['major_id']) && empty($errors['class_id'])) {
            $class = $this->model->findClassById((int) $form['class_id']);
            if ($class === null) {
                $errors['class_id'] = 'Lớp học không tồn tại.';
            } elseif ((int) $class['major_id'] !== (int) $form['major_id']) {
                $errors['class_id'] = 'Lớp học không thuộc ngành học đã chọn.';
            } elseif ((int) $class['department_id'] !== (int) $form['department_id']) {
                $errors['class_id'] = 'Lớp học không thuộc khoa/bộ môn đã chọn.';
            } elseif ((int) ($class['year_id'] ?? 0) !== (int) $form['academic_year_id']) {
                $errors['class_id'] = 'Lớp học không thuộc niên khóa đã chọn.';
            }
        }

        return $errors;
    }

    private function validateEdit(int $id, array $form): array
    {
        $errors = [];

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

        if ($form['full_name'] === '') {
            $errors['full_name'] = 'Vui lòng nhập họ và tên.';
        }

        if ($form['department_id'] === '' || !ctype_digit($form['department_id'])) {
            $errors['department_id'] = 'Vui lòng chọn khoa/bộ môn hợp lệ.';
        } elseif (!$this->model->departmentExists((int) $form['department_id'])) {
            $errors['department_id'] = 'Khoa/bộ môn không tồn tại.';
        }

        if ($form['major_id'] === '' || !ctype_digit($form['major_id'])) {
            $errors['major_id'] = 'Vui lòng chọn ngành học hợp lệ.';
        }

        if ($form['academic_year_id'] === '' || !ctype_digit($form['academic_year_id'])) {
            $errors['academic_year_id'] = 'Vui lòng chọn niên khóa hợp lệ.';
        }

        if (empty($errors['department_id']) && empty($errors['major_id'])) {
            $major = $this->model->findMajorById((int) $form['major_id']);
            if ($major === null) {
                $errors['major_id'] = 'Ngành học không tồn tại.';
            } elseif ((int) $major['department_id'] !== (int) $form['department_id']) {
                $errors['major_id'] = 'Ngành học không thuộc khoa/bộ môn đã chọn.';
            }
        }

        if (empty($errors['department_id']) && empty($errors['major_id']) && empty($errors['academic_year_id']) && empty($errors['class_id'])) {
            $class = $this->model->findClassById((int) $form['class_id']);
            if ($class === null) {
                $errors['class_id'] = 'Lớp học không tồn tại.';
            } elseif ((int) $class['department_id'] !== (int) $form['department_id']) {
                $errors['class_id'] = 'Lớp học không thuộc khoa/bộ môn đã chọn.';
            } elseif ((int) $class['major_id'] !== (int) $form['major_id']) {
                $errors['class_id'] = 'Lớp học không thuộc ngành học đã chọn.';
            } elseif ((int) ($class['year_id'] ?? 0) !== (int) $form['academic_year_id']) {
                $errors['class_id'] = 'Lớp học không thuộc niên khóa đã chọn.';
            }
        }

        return $errors;
    }
}
