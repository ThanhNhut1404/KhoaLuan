<?php
session_start();
require_once __DIR__ . '/../src/Controllers/StudentController.php';

$controller = new StudentController();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
	$controller->handleLogin();
	exit;
}

if ($action === 'login') {
	$controller->login();
} elseif ($action === 'profile') {
	$controller->profile();
} elseif ($action === 'phieudanhgia') {
	$controller->phieudanhgia();
} elseif ($action === 'lichhoatdong') {
	$controller->lichhoatdong();
} elseif ($action === 'dangkyhoatdong') {
	$controller->dangkyhoatdong();
} else {
	$controller->dashboard();
}
