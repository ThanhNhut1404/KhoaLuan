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
