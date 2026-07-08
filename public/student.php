<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';

use KhoaLuan\QLDRL\Controllers\StudentController;
use KhoaLuan\QLDRL\Controllers\StudentForgotPasswordController;

$controller = new StudentController();

$page = $_GET['page'] ?? null;
$action = $_GET['action'] ?? ($page ?? 'dashboard');

if ($page === 'forgot_password') {
	$forgotPasswordController = new StudentForgotPasswordController();

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$forgotPasswordController->handleForgotPassword();
		exit;
	}

	$forgotPasswordController->forgotPassword();
	exit;
}

if ($page === 'verify_otp') {
	$forgotPasswordController = new StudentForgotPasswordController();

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$forgotPasswordController->handleVerifyOtp();
		exit;
	}

	$forgotPasswordController->verifyOtp();
	exit;
}

if ($page === 'reset_password') {
	$forgotPasswordController = new StudentForgotPasswordController();

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$forgotPasswordController->handleResetPassword();
		exit;
	}

	$forgotPasswordController->resetPassword();
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
	$controller->handleLogin();
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'change_password') {
	$controller->handleChangePassword();
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_profile') {
	$controller->handleUpdateProfile();
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_theme') {
	$controller->handleUpdateTheme();
	exit;
}

if ($action === 'captcha') {
	$controller->captcha();
}

if ($action === 'login') {
	$controller->login();
} elseif ($action === 'logout') {
	$controller->logout();
} elseif ($action === 'logout_after_password_change') {
	$controller->logoutAfterPasswordChange();
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
} elseif ($action === 'diemdanhhoatdong') {
	$controller->diemdanhhoatdong();
} elseif ($action === 'ketquarenluyen') {
	$controller->ketquarenluyen();
} elseif ($action === 'thongbao') {
	$controller->thongbao();
} else {
	$controller->dashboard();
}
