<?php

$page = $_GET['page'] ?? 'dashboard';

$viewPath = __DIR__ . '/../views/backend/';
$content = $viewPath . $page . '.php';

if (!file_exists($content)) {
    $content = $viewPath . 'dashboard.php';
}

$title = 'Trang Quản Trị';

include $viewPath . 'layout.php';
