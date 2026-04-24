<?php

class StudentController
{
    public function dashboard()
    {
        $title = "Cổng điểm rèn luyện sinh viên";

        // file nội dung
        $content = __DIR__ . '/../views/frontend/dashboard.php';

        // gọi layout
        require __DIR__ . '/../views/frontend/layout.php';
    }
}
