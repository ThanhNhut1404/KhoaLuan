<?php

require_once __DIR__ . '/../../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? 'dashboard';
if ($page === 'role_permission') {
    $page = 'roles';
    $_GET['page'] = 'roles';
}

const ADMIN_SESSION_TIMEOUT_SECONDS = 1800;
$adminLoginPath = '/KhoaLuan/public/admin.php?page=login';
$viewPath = __DIR__ . '/../views/backend/';

function destroyCurrentSession(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            (bool) $params['secure'],
            (bool) $params['httponly']
        );
    }

    session_destroy();
}

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

function adminHasRole(array $adminSession, string $roleCode): bool
{
    $roleCode = trim($roleCode);
    if ($roleCode === '') {
        return false;
    }

    foreach (['role_code', 'TEN_VAI_TRO', 'role_name'] as $key) {
        if ((string) ($adminSession[$key] ?? '') === $roleCode) {
            return true;
        }
    }

    $roles = is_array($adminSession['roles'] ?? null) ? $adminSession['roles'] : [];
    foreach ($roles as $role) {
        if (!is_array($role)) {
            continue;
        }

        foreach (['role_code', 'TEN_VAI_TRO', 'role_name'] as $key) {
            if ((string) ($role[$key] ?? '') === $roleCode) {
                return true;
            }
        }
    }

    return false;
}

function showAdminForbiddenPage(): void
{
    global $viewPath;

    http_response_code(403);
    $content = $viewPath . '403.php';
    $title = 'Không có quyền truy cập';
    include $viewPath . 'layout.php';
    exit;
}

function adminRedirectLocation(string $redirect): string
{
    $redirect = trim($redirect);
    if ($redirect === '') {
        return '/KhoaLuan/public/admin.php';
    }

    if (preg_match('#^https?://#i', $redirect) || str_starts_with($redirect, '/KhoaLuan/')) {
        return $redirect;
    }

    if ($redirect[0] === '?') {
        return '/KhoaLuan/public/admin.php' . $redirect;
    }

    return '/KhoaLuan/public/' . ltrim($redirect, '/');
}

function adminRequiredPermissionForPage(string $page): string
{
    $map = [
        'list_khoa' => 'list_departments',
        'roles' => 'role_permission',
        'list_major' => 'list_majors',
        'list_class' => 'list_classes',
        'list_year' => 'list_academic_years',
        'list_activity' => 'list_activities',
        'list_criteria' => 'setup_criteria',
        'configure_criteria' => 'setup_criteria',
        'apply_criteria' => 'setup_criteria',
    ];

    return $map[$page] ?? $page;
}

function adminRequiredPermissionForAction(string $page, array $post, string $method): ?string
{
    if ($method !== 'POST') {
        return null;
    }

    $action = trim((string) ($post['action'] ?? ''));
    $hasStatusPayload = isset($post['status']) && is_array($post['status']);

    return match ($page) {
        'create_khoa' => 'create_khoa',
        'create_major' => 'create_major',
        'create_class' => 'create_class',
        'create_year' => 'create_year',
        'create_semester' => 'create_semester',
        'create_student' => 'create_student',
        'create_account' => 'create_account',
        'create_activity' => 'create_activity',
        'edit_khoa' => 'edit_khoa',
        'edit_major' => 'edit_major',
        'edit_class' => 'edit_class',
        'edit_year' => 'edit_year',
        'edit_semester' => 'edit_semester',
        'edit_student' => 'edit_student',
        'edit_account' => 'edit_account',
        'edit_activity' => 'edit_activity',
        'delete_student' => 'delete_student',
        'list_students' => $action === 'status' ? 'change_status_student' : null,
        'list_khoa' => $action === 'delete' ? 'delete_khoa' : null,
        'list_major' => $action === 'delete'
            ? 'delete_major'
            : ($hasStatusPayload ? 'change_status_major' : null),
        'list_class' => match ($action) {
            'delete' => 'delete_class',
            'status' => 'change_status_class',
            default => null,
        },
        'list_year' => match ($action) {
            'edit' => 'edit_year',
            'delete' => 'delete_year',
            'update_status' => 'change_status_year',
            default => null,
        },
        'list_semester' => match ($action) {
            'delete' => 'delete_semester',
            'status' => 'change_status_semester',
            default => !empty($post['status_change']) ? 'change_status_semester' : null,
        },
        'list_accounts' => match ($action) {
            'delete' => 'delete_account',
            'approve' => 'approve_account',
            'reject' => 'reject_account',
            default => $hasStatusPayload ? 'change_status_account' : null,
        },
        'list_activity' => match ($action) {
            'delete' => 'delete_activity',
            'approve' => 'approve_activity',
            'reject' => 'reject_activity',
            default => $hasStatusPayload ? 'change_status_activity' : null,
        },
        'configure_criteria' => match ($action) {
            'save' => isset($post['id']) && trim((string) ($post['id'] ?? '')) !== '' ? 'edit_criteria' : 'create_criteria',
            default => null,
        },
        'list_criteria' => match ($action) {
            'save' => isset($post['id']) && trim((string) ($post['id'] ?? '')) !== '' ? 'edit_criteria' : 'create_criteria',
            default => null,
        },
        default => null,
    };
}

if ($page === 'logout') {
    destroyCurrentSession();
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

$lastActivityAt = (int) ($_SESSION['admin_last_activity_at'] ?? 0);
if ($lastActivityAt > 0 && (time() - $lastActivityAt) > ADMIN_SESSION_TIMEOUT_SECONDS) {
    destroyCurrentSession();
    header('Location: ' . $adminLoginPath);
    exit;
}

$_SESSION['admin_last_activity_at'] = time();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'change_password') {
    handleAdminChangePassword();
    $page = 'dashboard';
}

$permissionService = new \KhoaLuan\QLDRL\Services\PermissionService();
$authorizationPermissionService = $permissionService;
$adminRoles = $_SESSION['admin']['roles'] ?? [];
$isAdmin = adminHasRole($_SESSION['admin'], 'ADMIN');
$adminRoleIds = array_values(array_filter(array_map(
    static fn(array $role): mixed => $role['role_id'] ?? $role['MA_VAI_TRO'] ?? null,
    is_array($adminRoles) ? $adminRoles : []
)));

if (empty($adminRoleIds) && !empty($_SESSION['admin']['MA_VAI_TRO'])) {
    $adminRoleIds[] = $_SESSION['admin']['MA_VAI_TRO'];
}

$canAccessPermission = static function (string $permission) use ($authorizationPermissionService, $adminRoleIds, $isAdmin): bool {
    return $isAdmin || $authorizationPermissionService->canAccessAny($adminRoleIds, $permission);
};

if (in_array($page, ['ajax_semesters_by_academic_year', 'ajax_categories_by_semester'], true)) {
    $criteriaController = new \KhoaLuan\QLDRL\Controllers\CriteriaController();
    header('Content-Type: application/json; charset=utf-8');

    if ($page === 'ajax_semesters_by_academic_year') {
        $academicYearId = (int) ($_GET['MA_NIEN_KHOA'] ?? 0);
        echo json_encode($criteriaController->getSemestersByAcademicYear($academicYearId), JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($page === 'ajax_categories_by_semester') {
        $semesterId = (int) ($_GET['MA_HOC_KY'] ?? 0);
        echo json_encode($criteriaController->getCategoriesBySemester($semesterId), JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if (!$canAccessPermission(adminRequiredPermissionForPage($page))) {
    showAdminForbiddenPage();
}

$actionPermission = adminRequiredPermissionForAction($page, $_POST, $_SERVER['REQUEST_METHOD']);
if ($actionPermission !== null && !$canAccessPermission($actionPermission)) {
    showAdminForbiddenPage();
}

if ($isAdmin) {
    $permissionService = new class($permissionService) {
        public function __construct(private \KhoaLuan\QLDRL\Services\PermissionService $inner)
        {
        }

        public function buildSidebarMenu(array $roleIds): array
        {
            $menu = $this->inner->buildSidebarMenu($roleIds);
            $permissionChild = [
                'page' => 'list_role_permissions',
                'label' => 'Danh sách phân quyền',
                'url' => '?page=list_role_permissions',
            ];

            foreach ($menu as &$parent) {
                $children = $parent['children'] ?? [];
                $hasRoles = false;
                $hasList = false;

                foreach ($children as $child) {
                    $hasRoles = $hasRoles || (($child['page'] ?? '') === 'roles');
                    $hasList = $hasList || (($child['page'] ?? '') === 'list_role_permissions');
                }

                if ($hasRoles && !$hasList) {
                    $parent['children'][] = $permissionChild;
                    unset($parent);

                    return $menu;
                }
            }
            unset($parent);

            $menu[] = [
                'label' => 'Quản lý phân quyền',
                'icon' => 'permission',
                'children' => [
                    ['page' => 'roles', 'label' => 'Cấp quyền truy cập', 'url' => '?page=roles'],
                    $permissionChild,
                ],
            ];

            return $menu;
        }
    };
}

if ($page === 'roles') {
    $rolePermissionController = new \KhoaLuan\QLDRL\Controllers\RolePermissionController();
    $rolePermissionState = $rolePermissionController->handle($_GET, $_POST, $_SERVER['REQUEST_METHOD'], $_SESSION['admin']);

    if (!empty($rolePermissionState['forbidden'])) {
        http_response_code(403);
        $content = $viewPath . '403.php';
        $title = 'Không có quyền truy cập';
        include $viewPath . 'layout.php';
        exit;
    }

    $roles = $rolePermissionState['roles'];
    $selectedRoleId = $rolePermissionState['selectedRoleId'];
    $selectedRole = $rolePermissionState['selectedRole'];
    $functionsByModule = $rolePermissionState['functionsByModule'];
    $assignedPermissionIds = $rolePermissionState['assignedPermissionIds'];
    $errors = $rolePermissionState['errors'];
    $adminToast = $rolePermissionState['toast'];
}

if ($page === 'list_role_permissions') {
    $rolePermissionController = new \KhoaLuan\QLDRL\Controllers\RolePermissionController();
    $rolePermissionListState = $rolePermissionController->listPermissions($_GET, $_SESSION['admin']);

    if (!empty($rolePermissionListState['forbidden'])) {
        http_response_code(403);
        $content = $viewPath . '403.php';
        $title = 'KhÃ´ng cÃ³ quyá»n truy cáº­p';
        include $viewPath . 'layout.php';
        exit;
    }

    $roles = $rolePermissionListState['roles'];
    $rolePermissionRows = $rolePermissionListState['rolePermissionRows'];
    $rolePermissionDetails = $rolePermissionListState['rolePermissionDetails'];
    $filters = $rolePermissionListState['filters'] ?? [];
    $pagination = $rolePermissionListState['pagination'];
    $emptyMessage = $rolePermissionListState['emptyMessage'] ?? 'Chưa có vai trò nào.';
    $adminToast = $rolePermissionListState['toast'] ?? null;
}

if ($page === 'create_account') {
    $accountController = new \KhoaLuan\QLDRL\Controllers\AccountController();
    $accountState = $accountController->create(array_merge($_GET, $_POST), $_SERVER['REQUEST_METHOD']);

    $createAccountOptions = $accountState['options'];
    $formData = $accountState['formData'];
    $errors = $accountState['errors'];
    $adminToast = $accountState['toast'];
}

if (in_array($page, ['create_student', 'list_students', 'edit_student', 'delete_student'], true)) {
    try {
        $studentController = new \KhoaLuan\QLDRL\Controllers\StudentController();
        $studentState = $studentController->handle($page, $_POST, $_GET, $_SERVER['REQUEST_METHOD']);

        if ($page === 'create_student') {
            if (!empty($studentState['redirect'])) {
                if (!empty($studentState['toast'])) {
                    $_SESSION['message'] = $studentState['toast']['message'] ?? '';
                    $_SESSION['message_type'] = $studentState['toast']['type'] ?? 'info';
                }
                header('Location: ' . adminRedirectLocation($studentState['redirect']));
                exit;
            }

            $formData = $studentState['formData'];
            $errors = $studentState['errors'];
            $classes = $studentState['classes'] ?? ($studentState['options']['classes'] ?? []);
            $listKhoa = $studentState['listKhoa'] ?? [];
            $listNganh = $studentState['listNganh'] ?? [];
            $listNienKhoa = $studentState['listNienKhoa'] ?? [];
            $listLop = $studentState['listLop'] ?? [];
            $statusOptions = $studentState['statusOptions'];
            $adminToast = $studentState['toast'];
        }

        if ($page === 'list_students') {
            $students = $studentState['students'];
            $pagination = $studentState['pagination'];
            $emptyMessage = $studentState['emptyMessage'] ?? 'Chưa có sinh viên nào.';
            $statusOptions = $studentState['statusOptions'] ?? [];
            $filters = $studentState['filters'] ?? [];
            $studentFilterOptions = $studentState['filterOptions'] ?? ['classes' => [], 'academic_years' => []];
            $adminToast = $studentState['toast'] ?? null;

            if (!empty($studentState['redirect'])) {
                if (!empty($studentState['toast'])) {
                    $_SESSION['message'] = $studentState['toast']['message'] ?? '';
                    $_SESSION['message_type'] = $studentState['toast']['type'] ?? 'info';
                }
                header('Location: ' . adminRedirectLocation($studentState['redirect']));
                exit;
            }
        }

        if ($page === 'edit_student') {
            if (!empty($studentState['redirect'])) {
                if (!empty($studentState['toast'])) {
                    $_SESSION['message'] = $studentState['toast']['message'] ?? '';
                    $_SESSION['message_type'] = $studentState['toast']['type'] ?? 'info';
                }
                header('Location: ' . adminRedirectLocation($studentState['redirect']));
                exit;
            }

            $formData = $studentState['formData'];
            $errors = $studentState['errors'];
            $classes = $studentState['classes'] ?? ($studentState['options']['classes'] ?? []);
            $listKhoa = $studentState['listKhoa'] ?? [];
            $listNganh = $studentState['listNganh'] ?? [];
            $listNienKhoa = $studentState['listNienKhoa'] ?? [];
            $listLop = $studentState['listLop'] ?? [];
            $statusOptions = $studentState['statusOptions'];
            $adminToast = $studentState['toast'];
            $isEdit = true;
        }

        if ($page === 'delete_student') {
            if (!empty($studentState['redirect'])) {
                if (!empty($studentState['toast'])) {
                    $_SESSION['message'] = $studentState['toast']['message'] ?? '';
                    $_SESSION['message_type'] = $studentState['toast']['type'] ?? 'info';
                }
                header('Location: ' . adminRedirectLocation($studentState['redirect']));
                exit;
            }
        }
    } catch (Throwable $e) {
        error_log($e->getMessage());

        if ($page === 'list_students') {
            $students = [];
            $pagination = ['current_page' => 1, 'total_items' => 0, 'items_per_page' => 10, 'total_pages' => 1, 'from' => 0, 'to' => 0];
            $filters = [
                'keyword' => trim((string) ($_GET['search'] ?? $_GET['keyword'] ?? $_GET['q'] ?? '')),
                'class_id' => trim((string) ($_GET['class_id'] ?? $_GET['class'] ?? '')),
                'academic_year' => trim((string) ($_GET['academic_year'] ?? $_GET['year'] ?? '')),
                'status' => trim((string) ($_GET['status'] ?? '')),
            ];
            $studentFilterOptions = ['classes' => [], 'academic_years' => []];
            $emptyMessage = 'Không thể tải danh sách sinh viên.';
            $adminToast = ['type' => 'error', 'message' => 'Không thể tải danh sách sinh viên.'];
        }

        if ($page === 'create_student' || $page === 'edit_student') {
            $formData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
            $errors = [];
            $statusOptions = [
                ['value' => 'Đang học', 'label' => 'Đang học'],
                ['value' => 'Tạm ngừng', 'label' => 'Tạm ngừng'],
                ['value' => 'Kết thúc', 'label' => 'Kết thúc'],
            ];
            $adminToast = ['type' => 'error', 'message' => 'Có lỗi khi xử lý yêu cầu sinh viên.'];
        }
    }
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
            $emptyMessage = $academicYearState['emptyMessage'] ?? 'Chưa có niên khóa nào.';
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
        error_log(sprintf(
            '[AcademicYear:%s] %s in %s:%d',
            $page,
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        ));

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
            $searchKeyword = trim((string) ($_GET['search'] ?? $_GET['keyword'] ?? $_GET['q'] ?? ''));
            $statusFilter = trim((string) ($_GET['status'] ?? ''));
            $years = [];
            $statusOptions = [
                ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
                ['value' => 'Đang hoạt động', 'label' => 'Đang hoạt động'],
                ['value' => 'Đã hoàn thành', 'label' => 'Đã hoàn thành'],
            ];
            $pagination = ['current_page' => 1, 'total_items' => 0, 'items_per_page' => 10, 'total_pages' => 1, 'from' => 0, 'to' => 0];
            $emptyMessage = ($searchKeyword !== '' || $statusFilter !== '') ? 'Không có niên khóa phù hợp.' : 'Chưa có niên khóa nào.';
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

if (in_array($page, ['create_semester', 'list_semester', 'edit_semester'], true)) {
    try {
        $semesterController = new \KhoaLuan\QLDRL\Controllers\SemesterController();
        $semesterState = $semesterController->handle($page, $_POST, $_GET, $_SERVER['REQUEST_METHOD']);

        if ($page === 'create_semester') {
            $formData = $semesterState['formData'];
            $errors = $semesterState['errors'];
            $academic_years = $semesterState['academic_years'];
            $status_options = $semesterState['status_options'];
            $adminToast = $semesterState['toast'];
        }

        if ($page === 'list_semester') {
            if (!empty($semesterState['redirect'])) {
                if (!empty($semesterState['toast'])) {
                    $_SESSION['message'] = $semesterState['toast']['message'] ?? '';
                    $_SESSION['message_type'] = $semesterState['toast']['type'] ?? 'info';
                }
                header('Location: ' . $semesterState['redirect']);
                exit;
            }

            $semesters = $semesterState['semesters'];
            $filters = $semesterState['filters'] ?? [];
            $emptyMessage = $semesterState['emptyMessage'] ?? 'Chưa có học kỳ nào.';
            $pagination = $semesterState['pagination'];
            $status_options = $semesterState['status_options'] ?? [];
            $academic_years = $semesterState['academic_years'] ?? [];
            $adminToast = $semesterState['toast'] ?? null;
        }

        if ($page === 'edit_semester') {
            if (!empty($semesterState['redirect'])) {
                if (!empty($semesterState['toast'])) {
                    $_SESSION['message'] = $semesterState['toast']['message'] ?? '';
                    $_SESSION['message_type'] = $semesterState['toast']['type'] ?? 'info';
                }
                header('Location: ' . $semesterState['redirect']);
                exit;
            }

            $formData = $semesterState['formData'];
            $errors = $semesterState['errors'];
            $academic_years = $semesterState['academic_years'];
            $status_options = $semesterState['status_options'];
            $returnUrl = $semesterState['returnUrl'] ?? '?page=list_semester';
            $adminToast = $semesterState['toast'] ?? null;
            $isEdit = true;
        }
    } catch (\Throwable $e) {
        error_log($e->getMessage());

        if ($page === 'create_semester') {
            $formData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
            $errors = [];
            $academic_years = [];
            $status_options = [
                ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
                ['value' => 'Đang diễn ra', 'label' => 'Đang diễn ra'],
                ['value' => 'Đã hoàn thành', 'label' => 'Đã hoàn thành'],
                ['value' => 'Tạm khóa', 'label' => 'Tạm khóa'],
            ];
            $adminToast = ['type' => 'error', 'message' => 'Có lỗi xảy ra khi tạo học kỳ. Vui lòng thử lại.'];
        }

        if ($page === 'list_semester') {
            $semesters = [];
            $searchKeyword = trim((string) ($_GET['search'] ?? $_GET['keyword'] ?? $_GET['q'] ?? ''));
            $statusFilter = trim((string) ($_GET['status'] ?? ''));
            $academicYearFilter = trim((string) ($_GET['academic_year_id'] ?? $_GET['academic_year'] ?? ''));
            $filters = ['keyword' => $searchKeyword, 'status' => $statusFilter, 'academic_year_id' => $academicYearFilter];
            $emptyMessage = ($searchKeyword !== '' || $statusFilter !== '' || $academicYearFilter !== '') ? 'Không có học kỳ phù hợp.' : 'Chưa có học kỳ nào.';
            $pagination = ['current_page' => 1, 'total_items' => 0, 'items_per_page' => 10, 'total_pages' => 1, 'from' => 0, 'to' => 0];
            $academic_years = [];
            $status_options = [
                ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
                ['value' => 'Đang diễn ra', 'label' => 'Đang diễn ra'],
                ['value' => 'Đã hoàn thành', 'label' => 'Đã hoàn thành'],
                ['value' => 'Tạm khóa', 'label' => 'Tạm khóa'],
            ];
            $adminToast = ['type' => 'error', 'message' => 'Không thể tải danh sách học kỳ.'];
        }

        if ($page === 'edit_semester') {
            $formData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
            $errors = [];
            $academic_years = [];
            $status_options = [
                ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
                ['value' => 'Đang diễn ra', 'label' => 'Đang diễn ra'],
                ['value' => 'Đã hoàn thành', 'label' => 'Đã hoàn thành'],
                ['value' => 'Tạm khóa', 'label' => 'Tạm khóa'],
            ];
            $adminToast = ['type' => 'error', 'message' => 'Có lỗi xảy ra khi tải dữ liệu học kỳ.'];
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
            $emptyMessage = $khoaState['emptyMessage'] ?? 'Chưa có khoa/bộ môn nào.';
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
        error_log($e->getMessage());

        if ($page === 'list_khoa') {
            $searchKeyword = trim((string) ($_GET['search'] ?? $_GET['keyword'] ?? $_GET['q'] ?? ''));
            $khoas = [];
            $pagination = ['current_page' => 1, 'total_items' => 0, 'items_per_page' => 10, 'total_pages' => 1, 'from' => 0, 'to' => 0];
            $emptyMessage = $searchKeyword === '' ? 'Chưa có khoa/bộ môn nào.' : 'Không tìm thấy khoa/bộ môn phù hợp.';
            $adminToast = [
                'type' => 'error',
                'message' => $searchKeyword === '' ? 'Không thể tải danh sách khoa.' : 'Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại.',
            ];
        }

        if ($page === 'create_khoa') {
            $formData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
            $errors = [];
            $adminToast = ['type' => 'error', 'message' => 'Có lỗi khi tạo khoa.'];
        }

        if ($page === 'edit_khoa') {
            $adminToast = ['type' => 'error', 'message' => 'Có lỗi khi tải dữ liệu.'];
            $page = 'list_khoa';
        }
    }
}

if (in_array($page, ['create_major', 'list_major', 'edit_major'], true)) {
    try {
        $majorController = new \KhoaLuan\QLDRL\Controllers\MajorController();
        $majorState = $majorController->handle($page, $_POST, $_GET, $_SERVER['REQUEST_METHOD']);

        if ($page === 'create_major') {
            $formData = $majorState['formData'];
            $errors = $majorState['errors'];
            $departments = $majorState['departments'];
            $statusOptions = $majorState['statusOptions'];
            $adminToast = $majorState['toast'];
        }

        if ($page === 'list_major') {
            $majors = $majorState['majors'];
            $pagination = $majorState['pagination'];
            $statusOptions = $majorState['statusOptions'];
            $filters = $majorState['filters'] ?? [];
            $emptyMessage = $majorState['emptyMessage'] ?? 'Chưa có ngành học nào.';
            $adminToast = $majorState['toast'] ?? null;
        }

        if ($page === 'edit_major') {
            if (!empty($majorState['redirect'])) {
                if (!empty($majorState['toast'])) {
                    $_SESSION['message'] = $majorState['toast']['message'] ?? '';
                    $_SESSION['message_type'] = $majorState['toast']['type'] ?? 'info';
                }
                header('Location: ' . $majorState['redirect']);
                exit;
            }

            $formData = $majorState['formData'];
            $errors = $majorState['errors'];
            $departments = $majorState['departments'];
            $statusOptions = $majorState['statusOptions'];
            $adminToast = $majorState['toast'] ?? null;
            $isEdit = true;
        }
    } catch (\Throwable $e) {
        error_log($e->getMessage());
        $statusOptions = [
            ['value' => 'Hoạt động', 'label' => 'Hoạt động'],
            ['value' => 'Ngừng tuyển sinh', 'label' => 'Ngừng tuyển sinh'],
        ];

        if ($page === 'create_major') {
            $formData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
            $errors = [];
            $departments = [];
            $adminToast = ['type' => 'error', 'message' => 'Có lỗi khi xử lý yêu cầu tạo ngành học. Vui lòng thử lại.'];
        }

        if ($page === 'list_major') {
            $majors = [];
            $filters = [];
            $emptyMessage = 'Chưa có ngành học nào.';
            $pagination = ['current_page' => 1, 'total_items' => 0, 'items_per_page' => 10, 'total_pages' => 1, 'from' => 0, 'to' => 0];
            $adminToast = ['type' => 'error', 'message' => 'Không thể tải danh sách ngành học.'];
        }

        if ($page === 'edit_major') {
            $formData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
            $errors = [];
            $departments = [];
            $adminToast = ['type' => 'error', 'message' => 'Có lỗi khi tải dữ liệu ngành học. Vui lòng thử lại.'];
        }
    }
}

if (in_array($page, ['create_class', 'list_class', 'edit_class'], true)) {
    try {
        $classController = new \KhoaLuan\QLDRL\Controllers\ClassController();
        if ($page === 'create_class') {
            $classState = $classController->create($_POST, $_SERVER['REQUEST_METHOD']);
        } elseif ($page === 'edit_class') {
            $classState = $classController->editState((int) ($_GET['id'] ?? 0), $_POST, $_SERVER['REQUEST_METHOD']);
        } else {
            $classState = $classController->listing($_POST, $_GET, $_SERVER['REQUEST_METHOD']);
        }

        if (in_array($page, ['create_class', 'edit_class'], true) && !empty($classState['redirect'])) {
            if (!empty($classState['toast'])) {
                $_SESSION['message'] = $classState['toast']['message'] ?? '';
                $_SESSION['message_type'] = $classState['toast']['type'] ?? 'info';
            }

            header('Location: ' . $classState['redirect']);
            exit;
        }

        if (in_array($page, ['create_class', 'edit_class'], true)) {
            $formData = $classState['formData'];
            $errors = $classState['errors'];
            $academic_years = $classState['academic_years'];
            $departments = $classState['departments'];
            $majors = $classState['majors'];
            $statusOptions = $classState['statusOptions'];
        }

        if ($page === 'list_class') {
            $classes = $classState['classes'];
            $pagination = $classState['pagination'];
            $filters = $classState['filters'] ?? [];
            $academic_years = $classState['academic_years'] ?? [];
            $statusOptions = $classState['statusOptions'] ?? [];
            $emptyMessage = $classState['emptyMessage'] ?? 'Chưa có lớp học nào.';
        }
        $adminToast = $classState['toast'] ?? null;
    } catch (\Throwable $e) {
        error_log($e->getMessage());

        if (in_array($page, ['create_class', 'edit_class'], true)) {
            $formData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
            $errors = [];
            $academic_years = [];
            $departments = [];
            $majors = [];
            $statusOptions = [
                ['value' => 'Hoạt động', 'label' => 'Hoạt động'],
                ['value' => 'Không hoạt động', 'label' => 'Không hoạt động'],
                ['value' => 'Ngừng tuyển sinh', 'label' => 'Ngừng tuyển sinh'],
            ];
            $adminToast = [
                'type' => 'error',
                'message' => $page === 'edit_class'
                    ? 'Có lỗi khi xử lý yêu cầu cập nhật lớp học. Vui lòng thử lại.'
                    : 'Có lỗi khi xử lý yêu cầu tạo lớp học. Vui lòng thử lại.',
            ];
        }
        if ($page === 'list_class') {
            $classes = [];
            $filters = [];
            $academic_years = [];
            $statusOptions = [];
            $pagination = ['current_page' => 1, 'total_items' => 0, 'items_per_page' => 10, 'total_pages' => 1, 'from' => 0, 'to' => 0];
            $emptyMessage = 'Chưa có lớp học nào.';
            $adminToast = ['type' => 'error', 'message' => 'Không thể tải danh sách lớp học.'];
        }
    }
}

if (in_array($page, ['setup_criteria', 'apply_criteria', 'list_criteria', 'configure_criteria'], true)) {
    try {
        $criteriaController = new \KhoaLuan\QLDRL\Controllers\CriteriaController();
        $criteriaState = $criteriaController->handle($page, $_POST, $_GET, $_SERVER['REQUEST_METHOD']);

        if (!empty($criteriaState['redirect'])) {
            if (!empty($criteriaState['toast'])) {
                $_SESSION['message'] = $criteriaState['toast']['message'] ?? '';
                $_SESSION['message_type'] = $criteriaState['toast']['type'] ?? 'info';
            }
            header('Location: ' . adminRedirectLocation($criteriaState['redirect']));
            exit;
        }

        if ($page === 'setup_criteria') {
            $academicYears = $criteriaState['academicYears'] ?? [];
            $semesters = $criteriaState['semesters'] ?? [];
            $selectedAcademicYearId = $criteriaState['selectedAcademicYearId'] ?? 0;
            $selectedSemesterId = $criteriaState['selectedSemesterId'] ?? 0;
            $masterTemplates = $criteriaState['masterTemplates'] ?? [];
            $appliedTemplate = $criteriaState['appliedTemplate'] ?? null;
            $selectedTemplateId = $criteriaState['selectedTemplateId'] ?? 0;
            $categories = $criteriaState['categories'] ?? [];
            $criteria = $criteriaState['criteria'] ?? [];
            $criteriaByCategory = $criteriaState['criteriaByCategory'] ?? [];
            $formData = $criteriaState['formData'] ?? [];
            $errors = $criteriaState['errors'] ?? [];
            $formType = $criteriaState['formType'] ?? '';
            $adminToast = $criteriaState['toast'] ?? null;
        }

        if ($page === 'apply_criteria') {
            $academicYears = $criteriaState['academicYears'] ?? [];
            $semesters = $criteriaState['semesters'] ?? [];
            $selectedAcademicYearId = $criteriaState['selectedAcademicYearId'] ?? 0;
            $selectedSemesterId = $criteriaState['selectedSemesterId'] ?? 0;
            $masterTemplates = $criteriaState['masterTemplates'] ?? [];
            $selectedTemplateId = $criteriaState['selectedTemplateId'] ?? 0;
            $appliedTemplate = $criteriaState['appliedTemplate'] ?? null;
            $semesterRows = $criteriaState['semesterRows'] ?? [];
            $formData = $criteriaState['formData'] ?? [];
            $errors = $criteriaState['errors'] ?? [];
            $formType = $criteriaState['formType'] ?? '';
            $adminToast = $criteriaState['toast'] ?? null;
        }

        if ($page === 'list_criteria') {
            $academicYears = $criteriaState['academicYears'] ?? [];
            $semesters = $criteriaState['semesters'] ?? $criteriaState['semesterOptions'] ?? [];
            $selectedAcademicYearId = $criteriaState['selectedAcademicYearId'] ?? 0;
            $selectedSemesterId = $criteriaState['selectedSemesterId'] ?? 0;
            $categories = $criteriaState['categories'] ?? [];
            $criteria = $criteriaState['criteria'] ?? [];
            $criteriaByCategory = $criteriaState['criteriaByCategory'] ?? [];
            $formData = $criteriaState['formData'] ?? [];
            $errors = $criteriaState['errors'] ?? [];
            $formType = $criteriaState['formType'] ?? '';
            $toast = $criteriaState['toast'] ?? null;
            $adminToast = $toast;
        }

        if ($page === 'configure_criteria') {
            $semesters = $criteriaState['semesters'];
            $academicYears = $criteriaState['academicYears'] ?? [];
            $hoc_ky_list = $criteriaState['hoc_ky_list'] ?? $criteriaState['semesters'];
            $selectedAcademicYearId = $criteriaState['selectedAcademicYearId'] ?? 0;
            $selectedSemesterId = $criteriaState['selectedSemesterId'];
            $statusOptions = $criteriaState['statusOptions'];
            $formData = $criteriaState['formData'];
            $errors = $criteriaState['errors'];
            $adminToast = $criteriaState['toast'] ?? null;
            $isEdit = $criteriaState['isEdit'] ?? false;
        }
    } catch (\Throwable $e) {
        error_log($e->getMessage());

        $semesters = [];
        $selectedSemesterId = 0;
        $criteria = [];
        $filters = [];
        $statusOptions = [
            ['value' => 'Hoạt động', 'label' => 'Hoạt động'],
            ['value' => 'Tạm khóa', 'label' => 'Tạm khóa'],
        ];
        $formData = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : [];
        $errors = [];
        $adminToast = ['type' => 'error', 'message' => 'Có lỗi khi tải chức năng tiêu chí.'];
    }
}


if (empty($adminToast) && !empty($_SESSION['message'])) {
    $adminToast = [
        'type' => $_SESSION['message_type'] ?? 'info',
        'message' => $_SESSION['message'],
    ];
}

unset($_SESSION['message'], $_SESSION['message_type']);

$content = $viewPath . $page . '.php';

// Provide activity-selectable criteria to activity pages
if (in_array($page, ['create_activity', 'edit_activity'], true)) {
    $semesterId = (int) ($_GET['semester_id'] ?? $_GET['MA_HOC_KY'] ?? $_GET['MA_NIEN_KHOA'] ?? 0);
    $semesterModel = new \KhoaLuan\QLDRL\Models\SemesterModel(\KhoaLuan\QLDRL\Config\Database::getConnection());
    if ($semesterId <= 0) {
        $activeSemesters = $semesterModel->getActiveSemesters();
        $semesterId = !empty($activeSemesters) ? (int) ($activeSemesters[0]['MA_HOC_KY'] ?? $activeSemesters[0]['id'] ?? 0) : 0;
    }
    $criteriaModel = new \KhoaLuan\QLDRL\Models\CriteriaModel(\KhoaLuan\QLDRL\Config\Database::getConnection());
    $activity_selectable_criteria = $criteriaModel->getActivitySelectableCriteriaBySemester($semesterId);
}

if (!file_exists($content)) {
    $content = $viewPath . 'dashboard.php';
}

$title = 'Quản Trị';

include $viewPath . 'layout.php';
