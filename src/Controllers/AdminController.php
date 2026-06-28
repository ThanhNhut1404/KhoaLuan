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

if (in_array($page, ['create_year', 'list_year', 'edit_year'], true)) {
    try {
        $academicYearController = new \KhoaLuan\QLDRL\Controllers\AcademicYearController();
        $academicYearState = $academicYearController->handle($page, $_POST, $_GET, $_SERVER['REQUEST_METHOD']);

        if ($page === 'create_year') {
            $formData = $academicYearState['formData'];
            $errors = $academicYearState['errors'];
            $statusOptions = $academicYearState['statusOptions'];
            $adminToast = $academicYearState['toast'];
        }

        if ($page === 'list_year') {
            if (!empty($academicYearState['redirect'])) {
                header('Location: ' . $academicYearState['redirect']);
                exit;
            }

            $years = $academicYearState['years'];
            $statusOptions = $academicYearState['statusOptions'];
            $pagination = $academicYearState['pagination'];
            $adminToast = $academicYearState['toast'] ?? null;
        }

        if ($page === 'edit_year') {
            if (!empty($academicYearState['redirect'])) {
                if (!empty($academicYearState['toast'])) {
                    $_SESSION['message'] = $academicYearState['toast']['message'] ?? '';
                    $_SESSION['message_type'] = $academicYearState['toast']['type'] ?? 'info';
                }
                header('Location: /KhoaLuan/public/' . ltrim($academicYearState['redirect'], '/'));
                exit;
            }

            $formData = $academicYearState['formData'];
            $errors = $academicYearState['errors'];
            $statusOptions = $academicYearState['statusOptions'];
            $adminToast = $academicYearState['toast'] ?? null;
        }
    } catch (\Throwable $exception) {
        if ($page === 'create_year') {
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

        if ($page === 'list_year') {
            $years = [];
            $statusOptions = [
                ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
                ['value' => 'Đang hoạt động', 'label' => 'Đang hoạt động'],
                ['value' => 'Đã hoàn thành', 'label' => 'Đã hoàn thành'],
            ];
            $pagination = ['current_page' => 1, 'total_items' => 0, 'items_per_page' => 10, 'total_pages' => 1, 'from' => 0, 'to' => 0];
            $adminToast = ['type' => 'error', 'message' => 'Không thể tải danh sách niên khóa.'];
        }

        if ($page === 'edit_year') {
            $formData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
            $errors = [];
            $statusOptions = [
                ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
                ['value' => 'Đang diễn ra', 'label' => 'Đang diễn ra'],
                ['value' => 'Đã hoàn thành', 'label' => 'Đã hoàn thành'],
            ];
            $adminToast = ['type' => 'error', 'message' => 'Có lỗi xảy ra khi tải dữ liệu niên khóa.'];
        }
    }
}

if (in_array($page, ['create_khoa', 'list_khoa', 'edit_khoa'], true)) {
    try {
        $khoaController = new \KhoaLuan\QLDRL\Controllers\KhoaController();
        $khoaState = $khoaController->handle($page, $_POST, $_GET, $_SERVER['REQUEST_METHOD']);

        if ($page === 'list_khoa') {
            $khoas = $khoaState['khoas'];
            $pagination = $khoaState['pagination'];
            $adminToast = $khoaState['toast'] ?? null;
        }

        if ($page === 'create_khoa') {
            $formData = $khoaState['formData'];
            $errors = $khoaState['errors'];
            $adminToast = $khoaState['toast'];
        }

        if ($page === 'edit_khoa') {
            if (!empty($khoaState['redirect'])) {
                if (!empty($khoaState['toast'])) {
                    $_SESSION['message'] = $khoaState['toast']['message'] ?? '';
                    $_SESSION['message_type'] = $khoaState['toast']['type'] ?? 'info';
                }
                header('Location: ' . $khoaState['redirect']);
                exit;
            }

            $formData = $khoaState['formData'];
            $errors = $khoaState['errors'];
            $adminToast = $khoaState['toast'] ?? null;
            $isEdit = true;
        }
    } catch (\Throwable $e) {
        if ($page === 'list_khoa') {
            $khoas = [];
            $pagination = ['current_page' => 1, 'total_items' => 0, 'items_per_page' => 10, 'total_pages' => 1];
            $adminToast = ['type' => 'error', 'message' => 'Không thể tải danh sách khoa.'];
        }

        if ($page === 'create_khoa') {
            $formData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
            $errors = [];
            $adminToast = ['type' => 'error', 'message' => 'Có lỗi khi xử lý yêu cầu tạo khoa.'];
        }

        if ($page === 'edit_khoa') {
            $adminToast = ['type' => 'error', 'message' => 'Có lỗi khi tải dữ liệu.'];
            $page = 'list_khoa';
        }
    }
}


$content = $viewPath . $page . '.php';

if (!file_exists($content)) {
    $content = $viewPath . 'dashboard.php';
}

$title = 'Quản Trị';

include $viewPath . 'layout.php';
