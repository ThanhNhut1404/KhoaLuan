<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';

use KhoaLuan\QLDRL\Controllers\StudentController;

$controller = new StudentController();

$action = $_GET['action'] ?? 'dashboard';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
	$controller->handleLogin();
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'change_password') {
	$controller->handleChangePassword();
	exit;
}

if ($action === 'login') {
	$controller->login();
} elseif ($action === 'logout') {
	$controller->logout();
} elseif ($action === 'profile') {
	$controller->profile();
} elseif ($action === 'phieudanhgia') {
	$controller->phieudanhgia();
} elseif ($action === 'lichhoatdong') {
	$controller->lichhoatdong();
} elseif ($action === 'dangkyhoatdong') {
	$controller->dangkyhoatdong();
} elseif ($action === 'hoatdongdathamgia') {
	$controller->hoatdongdathamgia();
} elseif ($action === 'hoatdongdangky') {
	$controller->hoatdongdangky();
} elseif ($action === 'ketquarenluyen') {
	$controller->ketquarenluyen();
} elseif ($action === 'thongbao') {
	$controller->thongbao();
} else {
	$controller->dashboard();
}
