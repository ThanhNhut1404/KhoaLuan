<?php

namespace KhoaLuan\QLDRL\Controllers;

use KhoaLuan\QLDRL\Config\Database;
use KhoaLuan\QLDRL\Models\StudentModel;
use KhoaLuan\QLDRL\Services\CaptchaService;
use Throwable;

class StudentController
{
    private const LIST_PER_PAGE = 10;
    private const SESSION_TIMEOUT_SECONDS = 1800;
    private const DEFAULT_STUDENT_PASSWORD = '#Tdu1234';
    private const DEFAULT_THEME_COLOR = 'blue';
    private const ALLOWED_THEME_COLORS = ['blue', 'red', 'green', 'purple', 'cyan', 'orange'];

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
        $loginToast = null;
        if (is_array($flash)) {
            $message = (string) ($flash['message'] ?? '');
            $loginToast = [
                'type' => (string) ($flash['type'] ?? 'success'),
                'message' => $message,
            ];
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

    public function captcha(): void
    {
        CaptchaService::render('student_captcha');
    }

    public function handleLogin(): void
    {
        $username = trim((string) ($_POST['mssv'] ?? $_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $captcha = trim((string) ($_POST['captcha'] ?? ''));
        $expectedCaptcha = (string) ($_SESSION['student_captcha'] ?? '');

        if ($captcha === '') {
            unset($_SESSION['student_captcha']);
            $this->login('Vui lòng nhập mã xác thực.', $username);
            return;
        }

        if ($expectedCaptcha === '' || !hash_equals($expectedCaptcha, $captcha)) {
            unset($_SESSION['student_captcha']);
            $this->login('Mã xác thực không chính xác.', $username);
            return;
        }

        if ($username === '') {
            unset($_SESSION['student_captcha']);
            $this->login('Vui lòng nhập MSSV/tên đăng nhập.', $username);
            return;
        }

        if ($password === '') {
            unset($_SESSION['student_captcha']);
            $this->login('Vui lòng nhập mật khẩu.', $username);
            return;
        }

        try {
            $account = $this->model->findStudentAccountForLogin($username);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            unset($_SESSION['student_captcha']);
            $this->login('Lỗi đăng nhập: ' . $exception->getMessage(), $username);
            return;
        }

        if ($account === null) {
            unset($_SESSION['student_captcha']);
            $this->login('MSSV hoặc mật khẩu không đúng.', $username);
            return;
        }

        if ((string) ($account['role_name'] ?? '') !== 'SINH_VIEN') {
            unset($_SESSION['student_captcha']);
            $this->login('Tài khoản không có quyền truy cập cổng sinh viên.', $username);
            return;
        }

        if (!$this->isActiveAccount((string) ($account['account_status'] ?? ''))) {
            unset($_SESSION['student_captcha']);
            $this->login('Tài khoản sinh viên đang bị khóa hoặc ngừng hoạt động.', $username);
            return;
        }

        if (!password_verify($password, (string) ($account['password_hash'] ?? ''))) {
            unset($_SESSION['student_captcha']);
            $this->login('MSSV hoặc mật khẩu không đúng.', $username);
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
        $_SESSION['student_avatar'] = $this->normalizeAvatarPath((string) ($account['avatar'] ?? ''));
        $_SESSION['student_role'] = 'SINH_VIEN';
        $_SESSION['student_last_activity'] = time();
        $_SESSION['theme_color'] = $this->normalizeThemeColor((string) ($account['theme_color'] ?? self::DEFAULT_THEME_COLOR));
        unset($_SESSION['student_captcha']);

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

    public function diemdanhhoatdong(): void
    {
        $this->renderStudentPage('diemdanhhoatdong', 'Điểm danh hoạt động');
    }

    public function ketquarenluyen(): void
    {
        $this->renderStudentPage('ketquarenluyen', 'Kết quả rèn luyện');
    }

    public function thongbao(): void
    {
        $this->requireStudentLogin();
        $this->sendNoCacheHeaders();

        $student = $this->currentPortalStudent();
        $notificationFilters = $this->normalizeNotificationFilters($_GET);
        $notificationFilterOptions = ['types' => [], 'senders' => []];
        $notifications = [];
        $notificationError = '';
        $username = trim((string) ($_SESSION['student_username'] ?? ($student['username'] ?? '')));

        try {
            $notificationFilterOptions = $this->model->getPortalNotificationFilterOptions($username);
            $notifications = $this->model->listPortalNotifications($username, $notificationFilters);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $notificationError = 'Không thể tải danh sách thông báo lúc này.';
        }

        $title = 'Thông báo';
        $content = __DIR__ . '/../views/Frontend/thongbao.php';
        $passwordToast = $_SESSION['student_profile_toast'] ?? ($_SESSION['student_password_toast'] ?? null);
        $passwordLogoutAfterToast = !empty($_SESSION['student_logout_after_password_change']);
        $changePasswordErrors = $_SESSION['student_change_password_errors'] ?? [];
        $openChangePasswordModal = !empty($changePasswordErrors);
        $profileErrors = $_SESSION['student_profile_errors'] ?? [];
        $profileFormData = $_SESSION['student_profile_form_data'] ?? [];
        $openEditProfileModal = !empty($profileErrors);

        unset(
            $_SESSION['student_password_toast'],
            $_SESSION['student_logout_after_password_change'],
            $_SESSION['student_change_password_errors'],
            $_SESSION['student_profile_toast'],
            $_SESSION['student_profile_errors'],
            $_SESSION['student_profile_form_data']
        );

        require __DIR__ . '/../views/Frontend/layout.php';
    }

    private function normalizeNotificationFilters(array $get): array
    {
        $readStatus = trim((string) ($get['read_status'] ?? ''));
        if (!in_array($readStatus, ['', 'unread', 'read'], true)) {
            $readStatus = '';
        }

        return [
            'read_status' => $readStatus,
            'type' => trim((string) ($get['type'] ?? '')),
            'sender' => trim((string) ($get['sender'] ?? '')),
            'keyword' => trim((string) ($get['keyword'] ?? $get['search'] ?? $get['q'] ?? '')),
        ];
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
        } else {
            $newPasswordError = $this->validateStudentNewPassword($newPassword, $currentPassword);
            if ($newPasswordError !== null) {
                $errors['new_password'] = $newPasswordError;
            }
        }
        if ($confirmPassword === '') {
            $errors['confirm_password'] = 'Vui lòng xác nhận mật khẩu mới.';
        } elseif ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'Xác nhận mật khẩu không khớp.';
        }

        $username = (string) ($_SESSION['student_username'] ?? '');
        $studentId = (int) ($_SESSION['student_id'] ?? 0);
        $account = null;

        if ($currentPassword !== '') {
            try {
                $account = $this->model->findStudentAccountForLogin($username);
            } catch (Throwable $exception) {
                error_log($exception->getMessage());
                $errors['current_password'] = 'Không thể kiểm tra mật khẩu lúc này.';
            }

            if (!isset($errors['current_password']) && ($account === null || !password_verify($currentPassword, (string) ($account['password_hash'] ?? '')))) {
                $errors['current_password'] = 'Mật khẩu cũ không đúng.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['student_change_password_errors'] = $errors;
            header('Location: /KhoaLuan/public/student.php?action=profile');
            exit;
        }

        try {
            $updated = $this->model->updateAccountPassword($studentId, $username, password_hash($newPassword, PASSWORD_DEFAULT));
            $_SESSION['student_password_toast'] = [
                'type' => $updated ? 'success' : 'error',
                'message' => $updated ? 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại bằng mật khẩu mới.' : 'Đổi mật khẩu thất bại. Vui lòng thử lại.',
            ];
            if ($updated) {
                $_SESSION['student_logout_after_password_change'] = true;
            }
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

    public function logoutAfterPasswordChange(): void
    {
        $this->sendNoCacheHeaders();
        $this->clearStudentSession();
        header('Location: /KhoaLuan/public/student.php?action=login');
        exit;
    }

    private function validateStudentNewPassword(string $newPassword, string $currentPassword): ?string
    {
        if (hash_equals(self::DEFAULT_STUDENT_PASSWORD, $newPassword)) {
            return 'Mật khẩu mới không được là mật khẩu mặc định #Tdu1234.';
        }

        if ($currentPassword !== '' && hash_equals($currentPassword, $newPassword)) {
            return 'Mật khẩu mới không được trùng mật khẩu cũ.';
        }

        if (strlen($newPassword) < 8) {
            return 'Mật khẩu mới phải có ít nhất 8 ký tự.';
        }

        if (!preg_match('/[A-Z]/', $newPassword)) {
            return 'Mật khẩu mới phải có ít nhất 1 chữ hoa.';
        }

        if (!preg_match('/[a-z]/', $newPassword)) {
            return 'Mật khẩu mới phải có ít nhất 1 chữ thường.';
        }

        if (!preg_match('/[0-9]/', $newPassword)) {
            return 'Mật khẩu mới phải có ít nhất 1 chữ số.';
        }

        if (!preg_match('/[^A-Za-z0-9]/', $newPassword)) {
            return 'Mật khẩu mới phải có ít nhất 1 ký tự đặc biệt.';
        }

        return null;
    }

    public function handleUpdateProfile(): void
    {
        $this->requireStudentLogin();

        $student = $this->currentPortalStudent();
        $studentId = (int) ($student['student_id'] ?? 0);
        if ($studentId < 1) {
            $_SESSION['student_profile_toast'] = ['type' => 'error', 'message' => 'Không tìm thấy thông tin sinh viên.'];
            header('Location: /KhoaLuan/public/student.php?action=profile');
            exit;
        }

        $form = $this->normalizePortalProfileData($_POST);
        $errors = $this->validatePortalProfile($studentId, $form);
        $avatarFile = $_FILES['avatar'] ?? null;
        $hasAvatarUpload = is_array($avatarFile) && (int) ($avatarFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
        $newAvatarPath = null;
        $newAvatarAbsolutePath = null;

        if ($hasAvatarUpload) {
            $avatarValidationError = $this->validateAvatarUpload($avatarFile);
            if ($avatarValidationError !== null) {
                $errors['avatar'] = $avatarValidationError;
            } elseif (!$this->model->studentAvatarColumnExists()) {
                $errors['avatar'] = 'CSDL chưa có cột AVATAR để lưu ảnh đại diện.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['student_profile_errors'] = $errors;
            $_SESSION['student_profile_form_data'] = $form;
            $_SESSION['student_profile_toast'] = ['type' => 'error', 'message' => reset($errors) ?: 'Dữ liệu cập nhật không hợp lệ.'];
            header('Location: /KhoaLuan/public/student.php?action=profile');
            exit;
        }

        $hasProfileChanges = !$this->portalProfileHasNoChanges($student, $form);
        if (!$hasProfileChanges && !$hasAvatarUpload) {
            $_SESSION['student_profile_toast'] = ['type' => 'info', 'message' => 'Không có thay đổi nào được thực hiện.'];
            header('Location: /KhoaLuan/public/student.php?action=profile');
            exit;
        }

        if ($hasAvatarUpload) {
            try {
                [$newAvatarPath, $newAvatarAbsolutePath] = $this->storeAvatarUpload($studentId, $avatarFile);
            } catch (Throwable $exception) {
                error_log($exception->getMessage());
                $_SESSION['student_profile_errors'] = ['avatar' => 'Không thể tải avatar lên. Vui lòng thử lại.'];
                $_SESSION['student_profile_form_data'] = $form;
                $_SESSION['student_profile_toast'] = ['type' => 'error', 'message' => 'Không thể tải avatar lên. Vui lòng thử lại.'];
                header('Location: /KhoaLuan/public/student.php?action=profile');
                exit;
            }
        }

        $updateData = [
            'full_name' => $form['full_name'],
            'birth_date' => $form['birth_date'],
            'gender' => $form['gender'],
            'email' => $form['email'],
            'phone' => $form['phone'],
            'address' => $form['address'],
        ];
        if ($newAvatarPath !== null) {
            $updateData['avatar'] = $newAvatarPath;
        }

        try {
            $updated = $this->model->updatePortalStudentProfile($studentId, $updateData);
            if (!$updated) {
                if ($newAvatarAbsolutePath !== null && is_file($newAvatarAbsolutePath)) {
                    @unlink($newAvatarAbsolutePath);
                }
                $_SESSION['student_profile_toast'] = ['type' => 'error', 'message' => 'Cập nhật thông tin thất bại. Vui lòng thử lại.'];
                header('Location: /KhoaLuan/public/student.php?action=profile');
                exit;
            }

            if ($newAvatarPath !== null) {
                $this->deleteOldAvatarFile((string) ($student['avatar'] ?? ''), $newAvatarPath);
            }

            $_SESSION['student_name'] = $form['full_name'];
            $_SESSION['student_profile_toast'] = ['type' => 'success', 'message' => 'Cập nhật thông tin sinh viên thành công.'];
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            if ($newAvatarAbsolutePath !== null && is_file($newAvatarAbsolutePath)) {
                @unlink($newAvatarAbsolutePath);
            }
            $_SESSION['student_profile_toast'] = ['type' => 'error', 'message' => 'Cập nhật thông tin thất bại. Vui lòng thử lại.'];
        }

        header('Location: /KhoaLuan/public/student.php?action=profile');
        exit;
    }

    public function handleUpdateTheme(): void
    {
        $this->requireStudentLogin();

        if (strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? '')) !== 'POST') {
            $this->sendJsonResponse(['success' => false, 'message' => 'Phương thức không hợp lệ.'], 405);
        }

        $payload = $_POST;
        $rawInput = file_get_contents('php://input');
        if (is_string($rawInput) && trim($rawInput) !== '') {
            $jsonPayload = json_decode($rawInput, true);
            if (is_array($jsonPayload)) {
                $payload = array_merge($payload, $jsonPayload);
            }
        }

        $requestedTheme = strtolower(trim((string) ($payload['theme_color'] ?? '')));
        if (!in_array($requestedTheme, self::ALLOWED_THEME_COLORS, true)) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Màu giao diện không hợp lệ.'], 422);
        }

        $studentId = (int) ($_SESSION['student_id'] ?? 0);
        $username = trim((string) ($_SESSION['student_username'] ?? ''));
        if ($studentId < 1 || $username === '') {
            $this->sendJsonResponse(['success' => false, 'message' => 'Không tìm thấy phiên sinh viên hợp lệ.'], 401);
        }

        try {
            $updated = $this->model->updateAccountThemeColor($studentId, $username, $requestedTheme);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $this->sendJsonResponse(['success' => false, 'message' => 'Không thể lưu màu giao diện lúc này.'], 500);
        }

        if (!$updated) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Không thể cập nhật màu giao diện.'], 500);
        }

        $_SESSION['theme_color'] = $requestedTheme;
        $this->sendJsonResponse([
            'success' => true,
            'theme_color' => $requestedTheme,
            'message' => 'Đã đổi màu giao diện.',
        ]);
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

        $passwordToast = $_SESSION['student_profile_toast'] ?? ($_SESSION['student_password_toast'] ?? null);
        $passwordLogoutAfterToast = !empty($_SESSION['student_logout_after_password_change']);
        $changePasswordErrors = $_SESSION['student_change_password_errors'] ?? [];
        $openChangePasswordModal = !empty($changePasswordErrors);
        $profileErrors = $_SESSION['student_profile_errors'] ?? [];
        $profileFormData = $_SESSION['student_profile_form_data'] ?? [];
        $openEditProfileModal = !empty($profileErrors);

        unset(
            $_SESSION['student_password_toast'],
            $_SESSION['student_logout_after_password_change'],
            $_SESSION['student_change_password_errors'],
            $_SESSION['student_profile_toast'],
            $_SESSION['student_profile_errors'],
            $_SESSION['student_profile_form_data']
        );

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
        $student['khoa'] = (string) ($student['khoa'] ?? '');
        $student['nganh'] = (string) ($student['nganh'] ?? '');
        $student['khoa_hoc'] = (string) ($student['khoa_hoc'] ?? '');
        $student['nien_khoa'] = (string) ($student['nien_khoa'] ?? '');
        $student['hoc_ky'] = (string) ($student['hoc_ky'] ?? '');
        $student['thoi_gian_bat_dau_danh_gia'] = (string) ($student['thoi_gian_bat_dau_danh_gia'] ?? '');
        $student['thoi_gian_ket_thuc_danh_gia'] = (string) ($student['thoi_gian_ket_thuc_danh_gia'] ?? '');
        $student['trang_thai_hoc_tap'] = (string) ($student['trang_thai_hoc_tap'] ?? ($student['trang_thai'] ?? ''));
        $student['trang_thai'] = $student['trang_thai_hoc_tap'];
        $student['ngay_sinh'] = (string) ($student['ngay_sinh'] ?? '');
        $student['gioi_tinh'] = (string) ($student['gioi_tinh'] ?? '');
        $student['email'] = (string) ($student['email'] ?? '');
        $student['so_dien_thoai'] = (string) ($student['so_dien_thoai'] ?? '');
        $student['dia_chi'] = (string) ($student['dia_chi'] ?? ($student['dia_chi_thuong_tru'] ?? ''));
        $student['dia_chi_thuong_tru'] = $student['dia_chi'];
        $rawAvatar = (string) ($student['avatar'] ?? '');
        $student['avatar'] = $this->normalizeAvatarPath($rawAvatar);
        if ($student['avatar'] !== '' && trim(str_replace('\\', '/', $rawAvatar)) !== $student['avatar']) {
            try {
                $this->model->updateStudentAvatar((int) ($student['student_id'] ?? $studentId), $student['avatar']);
            } catch (Throwable $exception) {
                error_log($exception->getMessage());
            }
        }
        $student['avatar_url'] = $this->publicAssetUrl($student['avatar']);
        $_SESSION['student_id'] = (int) ($student['student_id'] ?? $studentId);
        $_SESSION['student_username'] = $student['username'];
        $_SESSION['student_mssv'] = $student['mssv'];
        $_SESSION['student_name'] = $student['ho_ten'] !== '' ? $student['ho_ten'] : ($_SESSION['student_name'] ?? 'Sinh viên');
        $_SESSION['student_avatar'] = $student['avatar'];
        return $student;
    }

    private function publicAssetUrl(string $path): string
    {
        $path = $this->normalizeAvatarPath($path);
        if ($path === '') {
            return '';
        }

        $absolutePath = $this->publicFilePath($path);
        if ($absolutePath === null || !is_file($absolutePath)) {
            return '';
        }

        return '/KhoaLuan/public/' . $path . '?v=' . filemtime($absolutePath);
    }

    public function avatarUrlFromPath(string $path): string
    {
        return $this->publicAssetUrl($path);
    }

    private function normalizeAvatarPath(string $path): string
    {
        $path = trim(str_replace('\\', '/', $path));
        if ($path === '' || preg_match('#^https?://#i', $path)) {
            return '';
        }

        $path = parse_url($path, PHP_URL_PATH) ?: $path;
        $path = preg_replace('#^[A-Za-z]:/#', '/', $path) ?? $path;
        $path = preg_replace('#/+#', '/', $path) ?? $path;

        $knownPrefixes = [
            '/KhoaLuan/public/',
            'KhoaLuan/public/',
            '/public/',
            'public/',
        ];
        foreach ($knownPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $path = substr($path, strlen($prefix));
                break;
            }
        }

        $path = ltrim($path, '/');
        foreach (['uploads/avatars/', 'uploads/avatar/', 'upload/avatar/'] as $legacyPrefix) {
            $position = strpos($path, $legacyPrefix);
            if ($position !== false) {
                $filename = basename(substr($path, $position + strlen($legacyPrefix)));
                return $filename !== '' ? 'uploads/avatars/' . $filename : '';
            }
        }

        $filename = basename($path);
        if ($filename !== '' && $filename !== '.' && $filename !== '..') {
            return 'uploads/avatars/' . $filename;
        }

        return '';
    }

    private function publicFilePath(string $relativePath): ?string
    {
        $relativePath = $this->normalizeAvatarPath($relativePath);
        if ($relativePath === '') {
            return null;
        }

        $publicDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'public';
        $absolutePath = $publicDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        $avatarDir = realpath($publicDir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'avatars');
        $resolvedPath = realpath($absolutePath);

        if ($avatarDir === false || $resolvedPath === false || !str_starts_with($resolvedPath, $avatarDir)) {
            return null;
        }

        return $resolvedPath;
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
            $_SESSION['student_avatar'],
            $_SESSION['theme_color'],
            $_SESSION['student_change_password_errors'],
            $_SESSION['student_password_toast'],
            $_SESSION['student_logout_after_password_change']
        );
    }

    private function normalizeThemeColor(string $themeColor): string
    {
        $themeColor = strtolower(trim($themeColor));

        return in_array($themeColor, self::ALLOWED_THEME_COLORS, true)
            ? $themeColor
            : self::DEFAULT_THEME_COLOR;
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

    private function sendJsonResponse(array $payload, int $statusCode = 200): void
    {
        if (!headers_sent()) {
            http_response_code($statusCode);
            header('Content-Type: application/json; charset=utf-8');
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        }

        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
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

    private function normalizePortalProfileData(array $data): array
    {
        $addressLines = [
            trim((string) ($data['address_line1'] ?? '')),
            trim((string) ($data['address_line2'] ?? '')),
            trim((string) ($data['address_line3'] ?? '')),
            trim((string) ($data['address_province'] ?? '')),
        ];

        return [
            'full_name' => trim((string) ($data['ho_ten'] ?? '')),
            'birth_date' => trim((string) ($data['ngay_sinh'] ?? '')),
            'gender' => trim((string) ($data['gioi_tinh'] ?? '')),
            'email' => trim((string) ($data['email'] ?? '')),
            'phone' => trim((string) ($data['so_dien_thoai'] ?? '')),
            'address_line1' => $addressLines[0],
            'address_line2' => $addressLines[1],
            'address_line3' => $addressLines[2],
            'address_province' => $addressLines[3],
            'address' => implode(', ', array_filter($addressLines, static fn(string $line): bool => $line !== '')),
        ];
    }

    private function validatePortalProfile(int $studentId, array $form): array
    {
        $errors = [];

        if ($form['full_name'] === '') {
            $errors['ho_ten'] = 'Vui lòng nhập họ và tên.';
        }

        if ($form['birth_date'] === '') {
            $errors['ngay_sinh'] = 'Vui lòng nhập ngày sinh.';
        } else {
            $date = \DateTime::createFromFormat('Y-m-d', $form['birth_date']);
            if (!$date || $date->format('Y-m-d') !== $form['birth_date']) {
                $errors['ngay_sinh'] = 'Ngày sinh không hợp lệ.';
            }
        }

        if ($form['gender'] === '') {
            $errors['gioi_tinh'] = 'Vui lòng chọn giới tính.';
        } elseif (!in_array($form['gender'], ['Nam', 'Nữ'], true)) {
            $errors['gioi_tinh'] = 'Giới tính không hợp lệ.';
        }

        if ($form['email'] === '') {
            $errors['email'] = 'Vui lòng nhập email.';
        } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        } elseif ($this->model->emailExists($form['email'], $studentId)) {
            $errors['email'] = 'Email đã tồn tại.';
        }

        if ($form['phone'] === '') {
            $errors['so_dien_thoai'] = 'Vui lòng nhập số điện thoại.';
        } elseif (!preg_match('/^(0|\+84)(3|5|7|8|9)[0-9]{8}$/', $form['phone'])) {
            $errors['so_dien_thoai'] = 'Số điện thoại không hợp lệ.';
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

        return $errors;
    }

    private function validateAvatarUpload(array $file): ?string
    {
        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error !== UPLOAD_ERR_OK) {
            return 'File avatar không hợp lệ.';
        }

        if ((int) ($file['size'] ?? 0) > 2 * 1024 * 1024) {
            return 'Avatar không được vượt quá 2MB.';
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return 'File avatar không hợp lệ.';
        }

        $mime = mime_content_type($tmpName);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mime, $allowedMimes, true)) {
            return 'Avatar chỉ hỗ trợ JPG, JPEG, PNG hoặc WEBP.';
        }

        return null;
    }

    private function storeAvatarUpload(int $studentId, array $file): array
    {
        $tmpName = (string) $file['tmp_name'];
        $mime = mime_content_type($tmpName);
        $extension = match ($mime) {
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg',
        };

        $uploadDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'avatars';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
            throw new \RuntimeException('Cannot create avatar upload directory.');
        }

        $filename = 'avatar_' . $studentId . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $absolutePath = $uploadDir . DIRECTORY_SEPARATOR . $filename;
        if (!move_uploaded_file($tmpName, $absolutePath)) {
            throw new \RuntimeException('Cannot move avatar upload.');
        }

        return ['uploads/avatars/' . $filename, $absolutePath];
    }

    private function deleteOldAvatarFile(string $oldAvatar, string $newAvatar): void
    {
        $oldAvatar = trim($oldAvatar);
        if ($oldAvatar === '' || $oldAvatar === $newAvatar || !str_starts_with($oldAvatar, 'uploads/avatars/')) {
            return;
        }

        $publicDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'public';
        $avatarDir = realpath($publicDir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'avatars');
        $oldPath = realpath($publicDir . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $oldAvatar));
        if ($avatarDir === false || $oldPath === false || !str_starts_with($oldPath, $avatarDir) || !is_file($oldPath)) {
            return;
        }

        @unlink($oldPath);
    }

    private function portalProfileHasNoChanges(array $student, array $form): bool
    {
        return trim((string) ($student['ho_ten'] ?? '')) === $form['full_name']
            && trim((string) ($student['ngay_sinh'] ?? '')) === $form['birth_date']
            && trim((string) ($student['gioi_tinh'] ?? '')) === $form['gender']
            && trim((string) ($student['email'] ?? '')) === $form['email']
            && trim((string) ($student['so_dien_thoai'] ?? '')) === $form['phone']
            && trim((string) ($student['dia_chi'] ?? ($student['dia_chi_thuong_tru'] ?? ''))) === $form['address'];
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
