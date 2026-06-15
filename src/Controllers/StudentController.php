<?php

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

    private function isAuthenticated()
    {
        return !empty($_SESSION['student']);
    }
}
