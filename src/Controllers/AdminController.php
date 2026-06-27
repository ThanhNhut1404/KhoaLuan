<?php

require_once __DIR__ . '/../../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? 'dashboard';
$viewPath = __DIR__ . '/../views/backend/';

function showAdminLogin(string $error = '', string $success = '', bool $redirectToAdmin = false): void
{
    global $viewPath;

    require $viewPath . 'login.php';
}

function handleAdminLogin(): void
{
    $tenDangNhap = trim($_POST['admin_user'] ?? '');
    $matKhau = $_POST['admin_pass'] ?? '';

    $authController = new \KhoaLuan\QLDRL\Controllers\AuthController();
    $state = $authController->loginAdmin($tenDangNhap, $matKhau);

    showAdminLogin($state['error'], $state['success'], $state['redirectToAdmin']);
}

function handleAdminChangePassword(): void
{
    global $changePasswordErrors, $openChangePasswordModal, $changePasswordToast, $redirectToAdminLogin;

    $passwordController = new \KhoaLuan\QLDRL\Controllers\PasswordController();
    $state = $passwordController->changePassword($_SESSION['admin']['TEN_DANG_NHAP'] ?? '', $_POST, false);

    $changePasswordErrors = $state['errors'];
    $openChangePasswordModal = $state['openModal'];
    $changePasswordToast = $state['toast'];
    $redirectToAdminLogin = $state['redirectToLogin'];
}

if ($page === 'logout') {
    unset($_SESSION['admin']);
    header('Location: /KhoaLuan/public/admin.php?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'login') {
    handleAdminLogin();
    exit;
}

if (!empty($_SESSION['admin']) && $page === 'login') {
    header('Location: /KhoaLuan/public/admin.php');
    exit;
}

if (empty($_SESSION['admin'])) {
    showAdminLogin();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'change_password') {
    handleAdminChangePassword();
    $page = 'dashboard';
}

if ($page === 'create_account') {
    $accountController = new \KhoaLuan\QLDRL\Controllers\AccountController();
    $accountState = $accountController->create($_POST, $_SERVER['REQUEST_METHOD']);

    $createAccountOptions = $accountState['options'];
    $formData = $accountState['formData'];
    $errors = $accountState['errors'];
    $adminToast = $accountState['toast'];
}

if ($page === 'create_year') {
    try {
        $academicYearController = new \KhoaLuan\QLDRL\Controllers\AcademicYearController();
        $academicYearState = $academicYearController->create($_POST, $_SERVER['REQUEST_METHOD']);

        $formData = $academicYearState['formData'];
        $errors = $academicYearState['errors'];
        $statusOptions = $academicYearState['statusOptions'];
        $adminToast = $academicYearState['toast'];
    } catch (\Throwable $exception) {
        $formData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
        $errors = [];
        $statusOptions = [
            ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
            ['value' => 'Đang diễn ra', 'label' => 'Đang diễn ra'],
            ['value' => 'Đã kết thúc', 'label' => 'Đã kết thúc'],
        ];
        $formData['status'] = $formData['status'] ?? $statusOptions[0]['value'];
        $message = str_contains($exception->getMessage(), 'Khong the ket noi')
            ? 'Không thể kết nối cơ sở dữ liệu.'
            : 'Có lỗi xảy ra khi tạo niên khóa. Vui lòng thử lại.';
        $adminToast = [
            'type' => 'error',
            'message' => $message,
        ];
    }
}

if ($page === 'list_year') {
    try {
        $academicYearController = new \KhoaLuan\QLDRL\Controllers\AcademicYearController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
            $editState = $academicYearController->canEdit((int) ($_POST['year_id'] ?? 0));
            if ($editState['allowed']) {
                header('Location: /KhoaLuan/public/admin.php?page=edit_year&id=' . (int) ($_POST['year_id'] ?? 0));
                exit;
            }

            $academicYearState = $academicYearController->listing([], $_GET, 'GET');
            $academicYearState['toast'] = $editState['toast'];
        } else {
            $academicYearState = $academicYearController->listing($_POST, $_GET, $_SERVER['REQUEST_METHOD']);
        }

        $years = $academicYearState['years'];
        $statusOptions = $academicYearState['statusOptions'];
        $pagination = $academicYearState['pagination'];
        $adminToast = $academicYearState['toast'];
    } catch (\Throwable $exception) {
        $years = [];
        $statusOptions = [
            ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
            ['value' => 'Đang hoạt động', 'label' => 'Đang hoạt động'],
            ['value' => 'Đã hoàn thành', 'label' => 'Đã hoàn thành'],
        ];
        $pagination = [
            'current_page' => 1,
            'total_items' => 0,
            'items_per_page' => 10,
            'total_pages' => 1,
            'from' => 0,
            'to' => 0,
        ];
        $message = str_contains($exception->getMessage(), 'Khong the ket noi')
            ? 'Không thể kết nối cơ sở dữ liệu.'
            : 'Có lỗi xảy ra khi tải dữ liệu.';
        $adminToast = ['type' => 'error', 'message' => $message];
    }
}

if ($page === 'edit_year' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $academicYearController = new \KhoaLuan\QLDRL\Controllers\AcademicYearController();
        $editState = $academicYearController->canEdit((int) ($_GET['id'] ?? 0));

        if (!$editState['allowed']) {
            $page = 'list_year';
            $academicYearState = $academicYearController->listing([], $_GET, 'GET');

            $years = $academicYearState['years'];
            $statusOptions = $academicYearState['statusOptions'];
            $pagination = $academicYearState['pagination'];
            $adminToast = $editState['toast'];
        }
    } catch (\Throwable $exception) {
        $page = 'list_year';
        $years = [];
        $statusOptions = [
            ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
            ['value' => 'Đang hoạt động', 'label' => 'Đang hoạt động'],
            ['value' => 'Đã hoàn thành', 'label' => 'Đã hoàn thành'],
        ];
        $pagination = [
            'current_page' => 1,
            'total_items' => 0,
            'items_per_page' => 10,
            'total_pages' => 1,
            'from' => 0,
            'to' => 0,
        ];
        $message = str_contains($exception->getMessage(), 'Khong the ket noi')
            ? 'Không thể kết nối cơ sở dữ liệu.'
            : 'Có lỗi xảy ra khi tải dữ liệu.';
        $adminToast = ['type' => 'error', 'message' => $message];
    }
}

$content = $viewPath . $page . '.php';

if (!file_exists($content)) {
    $content = $viewPath . 'dashboard.php';
}

$title = 'Quản Trị';

include $viewPath . 'layout.php';
