<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use KhoaLuan\QLDRL\Config\Database;

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

function findAdminRoleProfile(PDO $pdo, string $tenDangNhap, string $tenVaiTro): ?array
{
    $profileTables = [
        'ADMIN' => ['table' => 'nha_truong', 'id' => 'MA_TAI_KHOAN'],
        'DOAN_TRUONG' => ['table' => 'doan_truong', 'id' => 'MA_DOAN_TRUONG'],
        'DOAN_KHOA' => ['table' => 'doan_khoa', 'id' => 'MA_DOAN_KHOA'],
        'LIEN_CHI' => ['table' => 'lien_chi_clb', 'id' => 'MA_LIEN_CHI_CLB'],
        'GIANG_VIEN' => ['table' => 'giang_vien', 'id' => 'MA_GV'],
        'CO_VAN_HOC_TAP' => ['table' => 'giang_vien', 'id' => 'MA_GV'],
        'CAN_BO_LOP' => ['table' => 'can_bo_lop', 'id' => 'MA_CAN_BO_LOP'],
        'SINH_VIEN' => ['table' => 'sinh_vien', 'id' => 'MA_SV'],
    ];

    if (!isset($profileTables[$tenVaiTro])) {
        return null;
    }

    $profile = $profileTables[$tenVaiTro];
    $sql = sprintf(
        'SELECT %s AS MA_HO_SO FROM %s WHERE TEN_DANG_NHAP = :ten_dang_nhap LIMIT 1',
        $profile['id'],
        $profile['table']
    );

    $statement = $pdo->prepare($sql);
    $statement->execute(['ten_dang_nhap' => $tenDangNhap]);
    $row = $statement->fetch();

    if (!$row) {
        return null;
    }

    return [
        'TEN_BANG_HO_SO' => $profile['table'],
        'MA_HO_SO' => $row['MA_HO_SO'],
    ];
}

function handleAdminLogin(): void
{
    $tenDangNhap = trim($_POST['admin_user'] ?? '');
    $matKhau = $_POST['admin_pass'] ?? '';

    if ($tenDangNhap === '' || $matKhau === '') {
        showAdminLogin('Sai tên Đăng nhập hoặc Mật khẩu');
        return;
    }

    $pdo = Database::getConnection();

    $accountStatement = $pdo->prepare(
        "SELECT TEN_DANG_NHAP
         FROM nguoi_dung
         WHERE TEN_DANG_NHAP = :ten_dang_nhap
           AND MAT_KHAU = :mat_khau
           AND TRANG_THAI_ND = 'HOAT_DONG'
         LIMIT 1"
    );
    $accountStatement->execute([
        'ten_dang_nhap' => $tenDangNhap,
        'mat_khau' => $matKhau,
    ]);

    $account = $accountStatement->fetch();

    if (!$account) {
        showAdminLogin('Sai tên Đăng nhập hoặc Mật khẩu');
        return;
    }

    $roleStatement = $pdo->prepare(
        "SELECT ndvt.MA_VAI_TRO, vt.TEN_VAI_TRO
         FROM nguoi_dung_vai_tro ndvt
         INNER JOIN vai_tro vt ON vt.MA_VAI_TRO = ndvt.MA_VAI_TRO
         WHERE ndvt.TEN_DANG_NHAP = :ten_dang_nhap
         ORDER BY ndvt.MA_VAI_TRO
         LIMIT 1"
    );
    $roleStatement->execute(['ten_dang_nhap' => $tenDangNhap]);
    $role = $roleStatement->fetch();

    if (!$role) {
        showAdminLogin('Tài khoản chưa được gán vai trò.');
        return;
    }

    $profile = findAdminRoleProfile($pdo, $tenDangNhap, $role['TEN_VAI_TRO']);

    if (!$profile) {
        showAdminLogin('Tài khoản chưa có hồ sơ hợp lệ.');
        return;
    }

    session_regenerate_id(true);
    $_SESSION['admin'] = [
        'TEN_DANG_NHAP' => $tenDangNhap,
        'MA_VAI_TRO' => $role['MA_VAI_TRO'],
        'TEN_VAI_TRO' => $role['TEN_VAI_TRO'],
        'TEN_BANG_HO_SO' => $profile['TEN_BANG_HO_SO'],
        'MA_HO_SO' => $profile['MA_HO_SO'],
    ];

    $updateStatement = $pdo->prepare(
        'UPDATE nguoi_dung
         SET LAN_DANG_NHAP_CUOI = NOW()
         WHERE TEN_DANG_NHAP = :ten_dang_nhap'
    );
    $updateStatement->execute(['ten_dang_nhap' => $tenDangNhap]);

    showAdminLogin('', 'Đăng nhập thành công', true);
    return;
}

function validateNewAdminPassword(string $newPassword, string $oldPassword, string $tenDangNhap): ?string
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

    if ($newPassword === $oldPassword) {
        return 'Mật khẩu mới phải khác mật khẩu cũ.';
    }

    return null;
}

function handleAdminChangePassword(): void
{
    global $changePasswordErrors, $openChangePasswordModal, $changePasswordToast, $redirectToAdminLogin;

    $changePasswordErrors = [];
    $openChangePasswordModal = true;

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $tenDangNhap = $_SESSION['admin']['TEN_DANG_NHAP'] ?? '';

    if ($currentPassword === '') {
        $changePasswordErrors['current_password'] = 'Vui lòng nhập mật khẩu cũ.';
        return;
    }

    if ($newPassword === '') {
        $changePasswordErrors['new_password'] = 'Vui lòng nhập mật khẩu mới.';
        return;
    }

    if ($confirmPassword === '') {
        $changePasswordErrors['confirm_password'] = 'Vui lòng xác nhận mật khẩu mới.';
        return;
    }

    $pdo = Database::getConnection();
    $accountStatement = $pdo->prepare(
        'SELECT MAT_KHAU
         FROM nguoi_dung
         WHERE TEN_DANG_NHAP = :ten_dang_nhap
         LIMIT 1'
    );
    $accountStatement->execute(['ten_dang_nhap' => $tenDangNhap]);
    $account = $accountStatement->fetch();

    if (!$account || $currentPassword !== $account['MAT_KHAU']) {
        $changePasswordErrors['current_password'] = 'Mật khẩu cũ không chính xác.';
        return;
    }

    $passwordError = validateNewAdminPassword($newPassword, $currentPassword, $tenDangNhap);
    if ($passwordError !== null) {
        $changePasswordErrors['new_password'] = $passwordError;
        return;
    }

    if ($newPassword !== $confirmPassword) {
        $changePasswordErrors['confirm_password'] = 'Mật khẩu xác nhận không khớp.';
        return;
    }

    $updateStatement = $pdo->prepare(
        'UPDATE nguoi_dung
         SET MAT_KHAU = :mat_khau
         WHERE TEN_DANG_NHAP = :ten_dang_nhap'
    );
    $updated = $updateStatement->execute([
        'mat_khau' => $newPassword,
        'ten_dang_nhap' => $tenDangNhap,
    ]);

    if (!$updated || $updateStatement->rowCount() < 1) {
        $changePasswordToast = [
            'type' => 'error',
            'message' => 'Đổi mật khẩu thất bại. Vui lòng thử lại sau.',
        ];
        $openChangePasswordModal = false;
        return;
    }

    $changePasswordToast = [
        'type' => 'success',
        'message' => 'Đổi mật khẩu thành công.',
    ];
    $openChangePasswordModal = false;
    $redirectToAdminLogin = true;

    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function loadCreateAccountOptions(PDO $pdo): array
{
    return [
        'roles' => $pdo->query(
            "SELECT MA_VAI_TRO, TEN_VAI_TRO
             FROM vai_tro
             WHERE TEN_VAI_TRO <> 'ADMIN'
             ORDER BY MA_VAI_TRO"
        )->fetchAll(),
        'classes' => $pdo->query(
            'SELECT MA_LOP, TEN_LOP FROM lop_hoc ORDER BY TEN_LOP'
        )->fetchAll(),
        'departments' => $pdo->query(
            'SELECT MA_KHOA, TEN_KHOA FROM khoa_bo_mon ORDER BY TEN_KHOA'
        )->fetchAll(),
        'students' => $pdo->query(
            "SELECT MA_SV, MSSV, TEN_DANG_NHAP
             FROM sinh_vien
             ORDER BY MSSV"
        )->fetchAll(),
        'unions' => $pdo->query(
            'SELECT MA_DOAN_TRUONG, TEN_DOAN_HOI FROM doan_truong ORDER BY TEN_DOAN_HOI'
        )->fetchAll(),
    ];
}

function getRoleById(PDO $pdo, string $roleId): ?array
{
    $statement = $pdo->prepare(
        "SELECT MA_VAI_TRO, TEN_VAI_TRO
         FROM vai_tro
         WHERE MA_VAI_TRO = :role_id
           AND TEN_VAI_TRO <> 'ADMIN'
         LIMIT 1"
    );
    $statement->execute(['role_id' => $roleId]);
    $role = $statement->fetch();

    return $role ?: null;
}

function fieldRequired(array &$errors, array $data, string $field, string $message): void
{
    if (trim($data[$field] ?? '') === '') {
        $errors[$field] = $message;
    }
}

function valueExists(PDO $pdo, string $table, string $column, string $value): bool
{
    $sql = sprintf('SELECT 1 FROM %s WHERE %s = :value LIMIT 1', $table, $column);
    $statement = $pdo->prepare($sql);
    $statement->execute(['value' => $value]);

    return (bool) $statement->fetchColumn();
}

function validateCreateAccountPassword(string $password, string $username): ?string
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

function validateCreateAccount(PDO $pdo, array $data, ?array $role): array
{
    $errors = [];
    $username = trim($data['username'] ?? '');
    $password = $data['password'] ?? '';
    $confirmPassword = $data['confirm_password'] ?? '';
    $roleName = $role['TEN_VAI_TRO'] ?? '';

    if ($username === '') {
        $errors['username'] = $roleName === 'SINH_VIEN' ? 'Vui lòng nhập MSSV.' : 'Vui lòng nhập tên đăng nhập.';
    } elseif (!preg_match('/^[A-Za-z0-9_]{5,50}$/', $username)) {
        $errors['username'] = 'Tên đăng nhập chỉ gồm chữ cái, số, dấu gạch dưới và dài từ 5 đến 50 ký tự.';
    } elseif (valueExists($pdo, 'nguoi_dung', 'TEN_DANG_NHAP', $username)) {
        $errors['username'] = 'Tên đăng nhập đã tồn tại.';
    }

    $passwordError = validateCreateAccountPassword($password, $username);
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
            fieldRequired($errors, $data, 'full_name', 'Vui lòng nhập họ và tên.');
            fieldRequired($errors, $data, 'gender', 'Vui lòng chọn giới tính.');
            fieldRequired($errors, $data, 'birth_date', 'Vui lòng nhập ngày sinh.');
            fieldRequired($errors, $data, 'email', 'Vui lòng nhập email.');
            fieldRequired($errors, $data, 'phone', 'Vui lòng nhập số điện thoại.');
            fieldRequired($errors, $data, 'address', 'Vui lòng nhập địa chỉ.');
            fieldRequired($errors, $data, 'class_id', 'Vui lòng chọn lớp học.');
            if ($username !== '' && valueExists($pdo, 'sinh_vien', 'MSSV', $username)) {
                $errors['username'] = 'MSSV đã tồn tại.';
            }
            if (!empty($data['email']) && valueExists($pdo, 'sinh_vien', 'EMAIL_SV', trim($data['email']))) {
                $errors['email'] = 'Email sinh viên đã tồn tại.';
            }
            break;

        case 'GIANG_VIEN':
        case 'CO_VAN_HOC_TAP':
        case 'BO_MON':
        case 'KHOA':
            fieldRequired($errors, $data, 'full_name', 'Vui lòng nhập họ và tên.');
            fieldRequired($errors, $data, 'gender', 'Vui lòng chọn giới tính.');
            fieldRequired($errors, $data, 'birth_date', 'Vui lòng nhập ngày sinh.');
            fieldRequired($errors, $data, 'email', 'Vui lòng nhập email.');
            fieldRequired($errors, $data, 'phone', 'Vui lòng nhập số điện thoại.');
            fieldRequired($errors, $data, 'department_id', 'Vui lòng chọn khoa/bộ môn.');
            if (!empty($data['email']) && valueExists($pdo, 'giang_vien', 'EMAIL_GV', trim($data['email']))) {
                $errors['email'] = 'Email giảng viên đã tồn tại.';
            }
            break;

        case 'CAN_BO_LOP':
            fieldRequired($errors, $data, 'student_id', 'Vui lòng chọn sinh viên.');
            fieldRequired($errors, $data, 'class_id', 'Vui lòng chọn lớp học.');
            fieldRequired($errors, $data, 'class_position', 'Vui lòng nhập chức vụ cán bộ lớp.');
            if (!empty($data['class_id']) && !empty($data['class_position'])) {
                $statement = $pdo->prepare(
                    'SELECT 1
                     FROM can_bo_lop
                     WHERE MA_LOP = :class_id
                       AND CHUC_VU_CB = :position
                     LIMIT 1'
                );
                $statement->execute([
                    'class_id' => $data['class_id'],
                    'position' => trim($data['class_position']),
                ]);
                if ($statement->fetchColumn()) {
                    $errors['class_position'] = 'Chức vụ này đã tồn tại trong lớp đã chọn.';
                }
            }
            break;

        case 'DOAN_KHOA':
            fieldRequired($errors, $data, 'union_faculty_name', 'Vui lòng nhập tên Đoàn khoa.');
            fieldRequired($errors, $data, 'email', 'Vui lòng nhập email.');
            fieldRequired($errors, $data, 'department_id', 'Vui lòng chọn khoa/bộ môn.');
            break;

        case 'LIEN_CHI':
            fieldRequired($errors, $data, 'club_name', 'Vui lòng nhập tên Liên chi / CLB.');
            fieldRequired($errors, $data, 'union_id', 'Vui lòng chọn Đoàn trường quản lý.');
            break;

        case 'DOAN_TRUONG':
            fieldRequired($errors, $data, 'union_name', 'Vui lòng nhập tên Đoàn trường.');
            fieldRequired($errors, $data, 'email', 'Vui lòng nhập email.');
            break;

        default:
            $errors['role_id'] = 'Vai trò này chưa được hỗ trợ cấp tài khoản.';
            break;
    }

    return $errors;
}

function insertRoleProfile(PDO $pdo, array $data, array $role, string $username): void
{
    switch ($role['TEN_VAI_TRO']) {
        case 'SINH_VIEN':
            $statement = $pdo->prepare(
                'INSERT INTO sinh_vien
                 (MA_LOP, TEN_DANG_NHAP, MSSV, NGAY_SINH, GIO_TINH, EMAIL_SV, SO_DIEN_THOAI, DIA_CHI, TRANG_THAI_HOC_TAP)
                 VALUES (:class_id, :username, :mssv, :birth_date, :gender, :email, :phone, :address, :status)'
            );
            $statement->execute([
                'class_id' => $data['class_id'],
                'username' => $username,
                'mssv' => $username,
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'email' => trim($data['email']),
                'phone' => trim($data['phone']),
                'address' => trim($data['address']),
                'status' => 'Đang học',
            ]);
            break;

        case 'GIANG_VIEN':
        case 'CO_VAN_HOC_TAP':
        case 'BO_MON':
        case 'KHOA':
            $statement = $pdo->prepare(
                'INSERT INTO giang_vien
                 (MA_KHOA, TEN_DANG_NHAP, TEN_GV, EMAIL_GV, SO_DIEN_THOAI_GV, CHUC_VU_GV)
                 VALUES (:department_id, :username, :full_name, :email, :phone, :position)'
            );
            $statement->execute([
                'department_id' => $data['department_id'],
                'username' => $username,
                'full_name' => trim($data['full_name']),
                'email' => trim($data['email']),
                'phone' => trim($data['phone']),
                'position' => $role['TEN_VAI_TRO'],
            ]);
            break;

        case 'CAN_BO_LOP':
            $statement = $pdo->prepare(
                'INSERT INTO can_bo_lop
                 (TEN_DANG_NHAP, MA_LOP, CHUC_VU_CB, TRANG_THAI_CB)
                 VALUES (:username, :class_id, :position, :status)'
            );
            $statement->execute([
                'username' => $username,
                'class_id' => $data['class_id'],
                'position' => trim($data['class_position']),
                'status' => 'HOAT_DONG',
            ]);
            break;

        case 'DOAN_KHOA':
            $statement = $pdo->prepare(
                'INSERT INTO doan_khoa
                 (TEN_DANG_NHAP, MA_KHOA, TEN_DOAN_KHOA, EMAIL_DK)
                 VALUES (:username, :department_id, :name, :email)'
            );
            $statement->execute([
                'username' => $username,
                'department_id' => $data['department_id'],
                'name' => trim($data['union_faculty_name']),
                'email' => trim($data['email']),
            ]);
            break;

        case 'LIEN_CHI':
            $statement = $pdo->prepare(
                'INSERT INTO lien_chi_clb
                 (MA_DOAN_TRUONG, TEN_DANG_NHAP, TEN_LIEN_CHI_CLB, TRANG_THAI_LIEN_CHI_CLB)
                 VALUES (:union_id, :username, :name, :status)'
            );
            $statement->execute([
                'union_id' => $data['union_id'],
                'username' => $username,
                'name' => trim($data['club_name']),
                'status' => 'HOAT_DONG',
            ]);
            break;

        case 'DOAN_TRUONG':
            $statement = $pdo->prepare(
                'INSERT INTO doan_truong
                 (TEN_DANG_NHAP, TEN_DOAN_HOI, EMAIL_DT)
                 VALUES (:username, :name, :email)'
            );
            $statement->execute([
                'username' => $username,
                'name' => trim($data['union_name']),
                'email' => trim($data['email']),
            ]);
            break;
    }
}

function handleCreateAccount(PDO $pdo, array $data, array $role): bool
{
    $username = trim($data['username']);

    $pdo->beginTransaction();

    try {
        $accountStatement = $pdo->prepare(
            "INSERT INTO nguoi_dung (TEN_DANG_NHAP, MAT_KHAU, TRANG_THAI_ND)
             VALUES (:username, :password, 'HOAT_DONG')"
        );
        $accountStatement->execute([
            'username' => $username,
            'password' => $data['password'],
        ]);

        $roleStatement = $pdo->prepare(
            'INSERT INTO nguoi_dung_vai_tro (TEN_DANG_NHAP, MA_VAI_TRO)
             VALUES (:username, :role_id)'
        );
        $roleStatement->execute([
            'username' => $username,
            'role_id' => $role['MA_VAI_TRO'],
        ]);

        insertRoleProfile($pdo, $data, $role, $username);

        $pdo->commit();
        return true;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return false;
    }
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

$pdo = Database::getConnection();

if ($page === 'create_account') {
    $createAccountOptions = loadCreateAccountOptions($pdo);
    $formData = $_POST;
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedRole = getRoleById($pdo, $_POST['role_id'] ?? '');
        $errors = validateCreateAccount($pdo, $_POST, $selectedRole);

        if (empty($errors) && $selectedRole !== null) {
            $created = handleCreateAccount($pdo, $_POST, $selectedRole);
            $adminToast = [
                'type' => $created ? 'success' : 'error',
                'message' => $created ? 'Tạo tài khoản thành công.' : 'Tạo tài khoản thất bại. Vui lòng thử lại sau.',
            ];

            if ($created) {
                $formData = [];
            }
        }
    }
}

$content = $viewPath . $page . '.php';

if (!file_exists($content)) {
    $content = $viewPath . 'dashboard.php';
}

$title = 'Quản Trị';

include $viewPath . 'layout.php';
