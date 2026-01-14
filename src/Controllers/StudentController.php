<?php

class StudentController
{
    public function dashboard()
    {
        $title = "Trang sinh viên";

        // file nội dung
        $content = __DIR__ . '/../views/frontend/dashboard.php';

        // gọi layout
        require __DIR__ . '/../views/frontend/layout.php';
    }
}
