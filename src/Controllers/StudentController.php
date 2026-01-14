<?php

$page = $_GET['page'] ?? 'dashboard';

$viewPath = __DIR__ . '/../views/frontend/';
$content = $viewPath . $page . '.php';

if (!file_exists($content)) {
    $content = $viewPath . 'dashboard.php';
}

$title = 'Trang Sinh Viên';

include $viewPath . 'layout.php';
