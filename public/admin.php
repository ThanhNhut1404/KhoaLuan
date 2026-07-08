<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? 'dashboard';

require_once __DIR__ . '/../src/Controllers/AdminController.php';
