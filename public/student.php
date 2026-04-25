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
} else {
	$controller->dashboard();
}
