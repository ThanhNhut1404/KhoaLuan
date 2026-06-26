<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use KhoaLuan\QLDRL\Config\Database;

class StudentController
{
    public function dashboard()
    {
        $title = "Cổng Điểm rèn luyện Sinh viên";

        // file nội dung
        $content = __DIR__ . '/../views/Frontend/dashboard.php';

        // gọi layout
        require __DIR__ . '/../views/Frontend/layout.php';
    }

    public function profile()
    {
        $title = "Thông tin cá nhân";

        // Dữ liệu mẫu, thay bằng DB sau
        $student = [
            'mssv' => '227060072',
            'ho_ten' => 'Huynh Thanh Nhut',
            'ngay_sinh' => '14/04/2004',
            'gioi_tinh' => 'Nam',
            'lop_hoc' => 'DHCNTT17A',
            'khoa_hoc' => 'Khoa 17 (2022)',
            'bac_dao_tao' => 'Dai hoc',
            'loai_hinh_dao_tao' => 'Chinh quy',
            'nganh' => 'Cong nghe thong tin',
            'khoa' => 'Khoa Ky thuat Cong nghe',
            'co_so' => 'Truong Dai hoc Tay Do',
            'ngay_vao_truong' => '17/9/2022',
            'ma_ho_so' => '220892',
            'trang_thai' => 'Dang hoc',
            'dan_toc' => 'Kinh',
            'ton_giao' => 'Khong',
            'so_cmnd' => '###########',
            'ngay_cap_cmnd' => '18/11/2021',
            'noi_cap_cmnd' => 'Tinh Hau Giang',
            'so_dien_thoai' => '09xx xxx xxx',
            'email' => 'student@example.com',
            'noi_sinh' => 'Tinh Hau Giang',
            'avatar' => ''
        ];

        $content = __DIR__ . '/../views/Frontend/profile.php';
        require __DIR__ . '/../views/Frontend/layout.php';
    }

    public function phieudanhgia()
    {
        $title = "Phiếu đánh giá";

        $student = [
            'ho_ten' => 'Nguyen Van A',
            'mssv' => '2213405678',
            'lop' => 'D19CNTT01',
            'khoa' => 'Khoa Cong nghe thong tin',
            'hoc_ky' => '2',
            'nam_hoc' => '2024 - 2025'
        ];

        $content = __DIR__ . '/../views/Frontend/phieudanhgia.php';
        require __DIR__ . '/../views/Frontend/layout.php';
    }

    public function lichhoatdong()
    {
        $title = "Lịch hoạt động";

        $content = __DIR__ . '/../views/Frontend/lichhoatdong.php';
        require __DIR__ . '/../views/Frontend/layout.php';
    }

    public function dangkyhoatdong()
    {
        $title = "Đăng ký hoạt động";

        $content = __DIR__ . '/../views/Frontend/dangkyhoatdong.php';
        require __DIR__ . '/../views/Frontend/layout.php';
    }

    public function hoatdongdathamgia()
    {
        $title = "Hoạt động đã tham gia";

        $content = __DIR__ . '/../views/Frontend/hoatdongdathamgia.php';
        require __DIR__ . '/../views/Frontend/layout.php';
    }

    public function hoatdongdangky()
    {
        $title = "Hoạt động đã đăng ký";

        $content = __DIR__ . '/../views/Frontend/hoatdongdangky.php';
        require __DIR__ . '/../views/Frontend/layout.php';
    }

    public function ketquarenluyen()
    {
        $title = "Kết quả rèn luyện";

        $content = __DIR__ . '/../views/Frontend/ketquarenluyen.php';
        require __DIR__ . '/../views/Frontend/layout.php';
    }

    public function thongbao()
    {
        $title = "Thông báo";

        $content = __DIR__ . '/../views/Frontend/thongbao.php';
        require __DIR__ . '/../views/Frontend/layout.php';
    }

    public function login()
    {
        // hiển thị form login
        $error = '';
        $content = __DIR__ . '/../views/Frontend/login.php';
        require __DIR__ . '/../views/Frontend/layout.php';
    }

    public function handleLogin()
    {
        $mssv = trim($_POST['mssv'] ?? '');
        $password = $_POST['password'] ?? '';

        // TODO: Thay bằng kiểm tra DB thực sự sau này
        // Mẫu: MSSV = "test" và password = "password"
        if ($mssv === 'test' && $password === 'password') {
            // đặt session
            $_SESSION['student'] = [
                'mssv' => $mssv,
                'ho_ten' => 'Sinh viên mẫu'
            ];
            header('Location: /student.php');
            exit;
        }

        // lỗi xác thực: hiển thị lại form với biến $error
        $error = 'MSSV hoặc mật khẩu không đúng.';
        $content = __DIR__ . '/../views/Frontend/login.php';
        require __DIR__ . '/../views/Frontend/layout.php';
    }

    public function handleChangePassword()
    {
        $changePasswordErrors = [];
        $openChangePasswordModal = true;
        $passwordToast = null;
        $redirectToStudentLogin = false;

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $tenDangNhap = $_SESSION['student']['TEN_DANG_NHAP'] ?? ($_SESSION['student']['mssv'] ?? '');

        if ($currentPassword === '') {
            $changePasswordErrors['current_password'] = 'Vui lòng nhập mật khẩu cũ.';
            $this->renderWithPasswordState($changePasswordErrors, $openChangePasswordModal, $passwordToast, $redirectToStudentLogin);
            return;
        }

        if ($newPassword === '') {
            $changePasswordErrors['new_password'] = 'Vui lòng nhập mật khẩu mới.';
            $this->renderWithPasswordState($changePasswordErrors, $openChangePasswordModal, $passwordToast, $redirectToStudentLogin);
            return;
        }

        if ($confirmPassword === '') {
            $changePasswordErrors['confirm_password'] = 'Vui lòng xác nhận mật khẩu mới.';
            $this->renderWithPasswordState($changePasswordErrors, $openChangePasswordModal, $passwordToast, $redirectToStudentLogin);
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
            $this->renderWithPasswordState($changePasswordErrors, $openChangePasswordModal, $passwordToast, $redirectToStudentLogin);
            return;
        }

        $passwordError = $this->validateNewPassword($newPassword, $currentPassword, $tenDangNhap);
        if ($passwordError !== null) {
            $changePasswordErrors['new_password'] = $passwordError;
            $this->renderWithPasswordState($changePasswordErrors, $openChangePasswordModal, $passwordToast, $redirectToStudentLogin);
            return;
        }

        if ($newPassword !== $confirmPassword) {
            $changePasswordErrors['confirm_password'] = 'Mật khẩu xác nhận không khớp.';
            $this->renderWithPasswordState($changePasswordErrors, $openChangePasswordModal, $passwordToast, $redirectToStudentLogin);
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

        $openChangePasswordModal = false;

        if (!$updated || $updateStatement->rowCount() < 1) {
            $passwordToast = [
                'type' => 'error',
                'message' => 'Đổi mật khẩu thất bại. Vui lòng thử lại sau.',
            ];
            $this->renderWithPasswordState($changePasswordErrors, $openChangePasswordModal, $passwordToast, $redirectToStudentLogin);
            return;
        }

        $passwordToast = [
            'type' => 'success',
            'message' => 'Đổi mật khẩu thành công.',
        ];
        $redirectToStudentLogin = true;

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();

        $this->renderWithPasswordState($changePasswordErrors, $openChangePasswordModal, $passwordToast, $redirectToStudentLogin);
    }

    private function validateNewPassword(string $newPassword, string $oldPassword, string $tenDangNhap): ?string
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

        if ($tenDangNhap !== '' && stripos($newPassword, $tenDangNhap) !== false) {
            return 'Mật khẩu không được chứa tên đăng nhập.';
        }

        if ($newPassword === $oldPassword) {
            return 'Mật khẩu mới phải khác mật khẩu cũ.';
        }

        return null;
    }

    private function renderWithPasswordState(array $changePasswordErrors, bool $openChangePasswordModal, ?array $passwordToast, bool $redirectToStudentLogin)
    {
        $title = "Cổng Điểm rèn luyện Sinh viên";
        $content = __DIR__ . '/../views/Frontend/dashboard.php';
        require __DIR__ . '/../views/Frontend/layout.php';
    }

    private function isAuthenticated()
    {
        return !empty($_SESSION['student']);
    }
}
