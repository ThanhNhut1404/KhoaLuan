<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Hệ thống quản lý điểm rèn luyện' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #1d4ed8;
            --primary-dark: #1047a1;
            --secondary: #00a8e8;
            --success: #06a77d;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --sidebar-width: 240px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* HEADER */
        .student-header {
            background: #ffffff;
            color: var(--primary);
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-left: 8px;
        }

        .logo {
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header-logo {
            height: 64px;
            width: auto;
            display: block;
        }

        /* HEADER CENTER - THANH TIM KIEM */
        .header-center {
            flex: 1;
            display: flex;
            justify-content: center;
            padding: 0 20px;
        }

        /* HEADER CENTER - THANH TIM KIEM */
        .header-center {
            flex: 1;
            display: flex;
                opacity: 0.9;
            padding: 0 20px;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: #f5f7ff;
            border-radius: 12px;
            padding: 0 12px;
            width: 100%;
            max-width: 400px;
            transition: 0.3s;
            border: 1px solid #e3e9ff;
        }

        .search-box:focus-within {
            background: #edf2ff;
            border-color: #c7d6ff;
        }

        .search-input {
            flex: 1;
            border: none;
            background: transparent;
            color: var(--primary);
            padding: 8px 12px;
            font-size: 14px;
            outline: none;
        }

        .search-input::placeholder {
            color: rgba(29, 78, 216, 0.6);
        }

        .search-btn {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            font-size: 16px;
            padding: 4px 8px;
            transition: 0.3s;
        }

        .search-btn:hover {
            color: var(--primary-dark);
            opacity: 1;
        }

        /* HEADER RIGHT - ICONS VA USER MENU */

        /* NUT 3 GACH NAM DUOI HEADER */
        .menu-toggle-btn {
            position: fixed;
            top: 75px;
            left: 20px;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            cursor: pointer;
            background: #ffffff;
            border: 1px solid #e8ecf3;
            color: var(--primary);
            transition: 0.3s;
            padding: 0;
            z-index: 1001;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        body.sidebar-open .menu-toggle-btn {
            color: var(--primary);
        }

        .menu-toggle-btn:hover {
            color: var(--primary);
            transform: none;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-right: 20px;
        }

        .header-icon {
            color: var(--primary);
            font-size: 20px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }

        .header-icon:hover {
            color: var(--primary-dark);
            transform: scale(1.1);
        }

        .header-icon-link {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--primary);
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 4px;
            white-space: nowrap;
        }

        .header-icon-link:hover {
            color: var(--primary-dark);
            background: #eef2ff;
        }

        .header-icon-link svg,
        .header-icon svg,
        .search-btn svg,
        .user-avatar svg,
        .menu-toggle-btn svg,
        .sidebar-menu svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
        }

        /* USER DROPDOWN */
        .user-dropdown {
            position: relative;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .user-btn:hover {
            background: #eef2ff;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
        }

        .user-name {
            font-weight: 500;
            white-space: nowrap;
        }

        .user-caret {
            width: 14px;
            height: 14px;
            stroke: currentColor;
        }

        /* DROPDOWN MENU */
        .dropdown-menu {
            position: absolute;
            top: 50px;
            right: 0;
            background: white;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(29, 78, 216, 0.15);
            min-width: 190px;
            opacity: 0;
            visibility: hidden;
            z-index: 1002;
            border: 1px solid #e8ecf3;
        }

        .dropdown-menu.active {
            opacity: 1;
            visibility: visible;
        }

        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: var(--primary);
            text-decoration: none;
            transition: 0.2s;
            font-size: 14px;
        }

        .dropdown-menu a:first-child {
            border-radius: 6px 6px 0 0;
        }

        .dropdown-menu a:last-child {
            border-radius: 0 0 6px 6px;
        }

        .dropdown-menu a:hover {
            background: #eef2ff;
            color: var(--primary-dark);
        }

        .dropdown-menu a svg {
            width: 18px;
            height: 18px;
            text-align: center;
            color: var(--primary);
            stroke: currentColor;
        }

        .dropdown-divider {
            height: 1px;
            background: #e8ecf3;
            margin: 5px 0;
        }

        /* CHANGE PASSWORD MODAL */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.35);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1200;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-card {
            width: min(520px, 100%);
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);
            border: 1px solid #e8ecf3;
            overflow: hidden;
            animation: modalIn 180ms ease-out;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
            border-bottom: 1px solid #eef2ff;
            background: #f8faff;
        }

        .modal-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
        }

        .modal-close {
            border: none;
            background: transparent;
            color: var(--primary-dark);
            font-size: 18px;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: #eef2ff;
        }

        .modal-body {
            padding: 18px;
            display: grid;
            gap: 12px;
        }

        .modal-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 6px;
        }

        .modal-field .req {
            color: #ef4444;
            margin-left: 4px;
        }

        .modal-field input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #e3e9ff;
            background: #fbfdff;
            font-size: 14px;
            outline: none;
        }

        .modal-input-wrap {
            position: relative;
        }

        .modal-input-wrap input {
            padding-right: 44px;
        }

        .modal-toggle {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-45%);
            border: none;
            background: transparent;
            color: var(--primary-dark);
            cursor: pointer;
            padding: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .modal-toggle svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
        }

        .modal-toggle .eye-off { display: none; }
        .modal-toggle.is-visible .eye-on { display: none; }
        .modal-toggle.is-visible .eye-off { display: block; }

        .modal-field input:focus {
            border-color: #c7d6ff;
            box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.12);
        }

        .modal-actions {
            padding: 0 18px 18px;
        }

        .modal-save {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(29, 78, 216, 0.25);
        }

        .modal-save:hover { filter: brightness(0.96); }

        @keyframes modalIn {
            from { transform: translateY(8px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 70px;
            left: calc(-1 * var(--sidebar-width));
            width: var(--sidebar-width);
            height: auto;
            max-height: calc(100vh - 90px);
            background: #ffffff;
            color: var(--primary);
            padding: 20px 0;
            transition: left 0.3s ease;
            z-index: 999;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar-menu {
            list-style: none;
            padding-top: 28px; /* tạo khoảng cách với nút menu */
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--primary);
            text-decoration: none;
            transition: 0.3s;
            border-left: 3px solid transparent;
            font-size: 14px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: var(--primary-dark);
            background: #eef2ff;
            border-left-color: transparent;
        }

        .sidebar-menu svg {
            width: 20px;
            height: 20px;
            text-align: center;
            stroke: currentColor;
        }

        .sidebar-menu .has-submenu {
            position: relative;
        }

        .sidebar-menu .submenu-caret {
            margin-left: auto;
            width: 12px;
            height: 12px;
            stroke: currentColor;
            opacity: 0.7;
            transition: transform 0.2s ease;
        }

        .sidebar-menu .has-submenu:hover .submenu-caret,
        .sidebar-menu .has-submenu:focus-within .submenu-caret {
            transform: rotate(180deg);
        }

        .sidebar-menu .submenu {
            list-style: none;
            margin: 4px 0 8px 0;
            padding: 0 0 0 12px;
            display: none;
        }

        .sidebar-menu .has-submenu:hover .submenu,
        .sidebar-menu .has-submenu:focus-within .submenu {
            display: block;
        }

        .sidebar-menu .submenu a {
            padding: 10px 16px 10px 36px;
            font-size: 13px;
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            margin-top: 70px;
            margin-bottom: 60px;
            padding: 30px 20px;
        }

        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Desktop: sidebar luôn hiện giống cổng thông tin */
        @media (min-width: 992px) {
            .main-content {
                margin-left: 0;
                padding: 30px 30px;
            }

            .content-wrapper {
                max-width: 1400px;
            }
        }

        /* FOOTER */
        .student-footer {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: rgba(255,255,255,0.92);
            font-size: 13px;
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.12);
            padding: 18px 0;
        }

        .footer-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
            align-items: start;
            justify-items: center;
        }

        .footer-section {
            min-width: 0;
            text-align: center;
        }

        @media (min-width: 768px) {
            .footer-content {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 18px;
            }

            .footer-section {
                text-align: left;
                min-height: 80px;
            }

            .footer-inner {
                align-items: stretch;
            }

            .footer-content {
                justify-items: start;
            }
        }

        .footer-heading {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.2px;
        }

        .footer-heading i {
            opacity: 0.9;
        }

        .footer-text {
            margin: 6px 0 0 0;
            font-size: 12px;
            line-height: 1.55;
            opacity: 0.92;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        /* Brand + social */
        .footer-brand {
            font-size: 18px;
            font-weight: 800;
            color: #fff;
        }

        .footer-social {
            margin-top: 12px;
            display: flex;
            gap: 10px;
        }

        .footer-social a {
            display: inline-flex;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.08);
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            transition: background 0.15s, transform 0.12s;
        }

        .footer-social a:hover { background: rgba(255,255,255,0.14); transform: translateY(-2px); }

        .footer-contact, .footer-links { list-style: none; margin: 8px 0 0 0; padding: 0; }
        .footer-contact li, .footer-links li { margin: 6px 0; }

        .footer-links a, .footer-contact a { color: rgba(255,255,255,0.95); }

        .footer-section a {
            color: #fff;
            text-decoration: none;
            transition: 0.2s;
            opacity: 0.9;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .footer-section a:hover {
            opacity: 1;
            text-decoration: underline;
        }

        .footer-bottom {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid rgba(255,255,255,0.16);
            font-size: 12px;
            opacity: 0.9;
            text-align: center;
        }

        .footer-bottom p {
            margin: 0;
            line-height: 1.5;
        }

        @media (min-width: 768px) {
            .footer-bottom { text-align: center; }
            .footer-bottom p { display: flex; gap: 10px; align-items: center; justify-content: center; flex-wrap: wrap; }
            .footer-bottom a { color: rgba(255,255,255,0.95); }
            .footer-bottom .footer-bottom-sep { opacity: 0.6; margin: 0 6px; }
        }

        /* OVERLAY untuk mobile */
        .sidebar-overlay {
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            z-index: 998;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .header-center {
                display: none;
            }

            .header-right {
                gap: 5px;
                padding-right: 10px;
            }

            .header-icon-link span {
                display: none;
            }

            .user-name {
                display: none;
            }

            .user-avatar {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }

            .header-icon-link span {
                display: none;
            }

            .header-icon-link {
                padding: 6px 8px;
            }

            .dropdown-menu {
                right: -10px;
            }
        }

        @media (max-width: 480px) {
            .header-left {
                padding-left: 10px;
            }

            .logo span {
                display: none;
            }
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f0f2f5;
        }

        ::-webkit-scrollbar-thumb {
            background: #d0d5e8;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2c387e;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/header.php'; ?>

<button class="menu-toggle-btn" onclick="toggleMenu()" title="Mở menu">
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 7h16M4 12h16M4 17h16" stroke-width="2" stroke-linecap="round"/>
    </svg>
</button>

<div class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li><a href="#dashboard" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 12h8V3H3v9Zm10 9h8v-7h-8v7ZM3 21h8v-7H3v7Zm10-9h8V3h-8v9Z" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Trang chủ</span></a></li>
        <li><a href="#notifications" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.7 21a2 2 0 0 1-3.4 0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Thông báo</span></a></li>
        <li class="has-submenu">
            <a href="#" onclick="return false;">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="9" stroke-width="2"/>
                    <path d="M12 8h.01" stroke-width="2" stroke-linecap="round"/>
                    <path d="M11 12h1v4h1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Thông tin chung</span>
                <svg class="submenu-caret" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <ul class="submenu">
                <li><a href="#student-info" onclick="closeSidebar()">Thông tin sinh viên</a></li>
                <li><a href="#change-password" onclick="closeSidebar()">Đổi mật khẩu</a></li>
            </ul>
        </li>
        <li class="has-submenu">
            <a href="#" onclick="return false;">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 7h8M8 12h8M8 17h8" stroke-width="2" stroke-linecap="round"/>
                    <rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/>
                </svg>
                <span>Hoạt động</span>
                <svg class="submenu-caret" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <ul class="submenu">
                <li><a href="#activities-new" onclick="closeSidebar()">Đăng ký hoạt động</a></li>
                <li><a href="#activities-registered" onclick="closeSidebar()">Hoạt động đã đăng ký</a></li>
                <li><a href="#activities-joined" onclick="closeSidebar()">Hoạt động đã tham gia</a></li>
                <li><a href="#activities-calendar" onclick="closeSidebar()">Lịch hoạt động</a></li>
            </ul>
        </li>
        <li class="has-submenu">
            <a href="#" onclick="return false;">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 12a9 9 0 1 0 3-6.7" stroke-width="2" stroke-linecap="round"/>
                    <path d="M3 4v5h5" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 7v5l3 2" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Điểm rèn luyện</span>
                <svg class="submenu-caret" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <ul class="submenu">
                <li><a href="#evidence" onclick="closeSidebar()">Khai báo minh chứng</a></li>
                <li><a href="#evaluation" onclick="closeSidebar()">Phiếu đánh giá</a></li>
                <li><a href="#discipline-result" onclick="closeSidebar()">Kết quả rèn luyện</a></li>
            </ul>
        </li>
        <li><a href="#profile" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72 12.7 12.7 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.22a2 2 0 0 1 2.11-.45 12.7 12.7 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Liên hệ</span></a></li>
        <li><a href="#contact" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M16 17l5-5-5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 12H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Đăng xuất</span></a></li>
    </ul>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="modal-overlay" id="passwordModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="passwordModalTitle">
        <div class="modal-header">
            <span class="modal-title" id="passwordModalTitle">Đổi mật khẩu</span>
            <button class="modal-close" type="button" aria-label="Đóng" onclick="closePasswordModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field pwd-wrap">
                <label for="currentPassword">Mật khẩu cũ<span class="req">*</span></label>
                <div class="modal-input-wrap">
                    <input id="currentPassword" type="password" placeholder="Nhập mật khẩu cũ" />
                    <button class="modal-toggle" type="button" aria-label="Hiện mật khẩu" data-target="currentPassword">
                        <svg class="eye-on" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="3" stroke-width="2" />
                        </svg>
                        <svg class="eye-off" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 3l18 18" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10.5 6.5A9.9 9.9 0 0 1 12 6c6.5 0 10 6 10 6a18.2 18.2 0 0 1-3.4 4.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.6 6.6A18 18 0 0 0 2 12s3.5 6 10 6c1.8 0 3.4-.4 4.8-1.1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="modal-field pwd-wrap">
                <label for="newPassword">Mật khẩu mới<span class="req">*</span></label>
                <div class="modal-input-wrap">
                    <input id="newPassword" type="password" placeholder="Nhập mật khẩu mới" />
                    <button class="modal-toggle" type="button" aria-label="Hiện mật khẩu" data-target="newPassword">
                        <svg class="eye-on" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="3" stroke-width="2" />
                        </svg>
                        <svg class="eye-off" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 3l18 18" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10.5 6.5A9.9 9.9 0 0 1 12 6c6.5 0 10 6 10 6a18.2 18.2 0 0 1-3.4 4.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.6 6.6A18 18 0 0 0 2 12s3.5 6 10 6c1.8 0 3.4-.4 4.8-1.1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="modal-field pwd-wrap">
                <label for="confirmPassword">Xác nhận mật khẩu<span class="req">*</span></label>
                <div class="modal-input-wrap">
                    <input id="confirmPassword" type="password" placeholder="Nhập lại mật khẩu mới" />
                    <button class="modal-toggle" type="button" aria-label="Hiện mật khẩu" data-target="confirmPassword">
                        <svg class="eye-on" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="3" stroke-width="2" />
                        </svg>
                        <svg class="eye-off" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 3l18 18" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10.5 6.5A9.9 9.9 0 0 1 12 6c6.5 0 10 6 10 6a18.2 18.2 0 0 1-3.4 4.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.6 6.6A18 18 0 0 0 2 12s3.5 6 10 6c1.8 0 3.4-.4 4.8-1.1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button class="modal-save" type="button">Lưu</button>
        </div>
    </div>
</div>

<div class="main-content">
    <div class="content-wrapper">
        <?php require $content; ?>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>

<script>
    function setSidebarState(isOpen) {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (isOpen) {
            sidebar.classList.add('active');
            document.body.classList.add('sidebar-open');
            overlay.classList.add('active');
        } else {
            sidebar.classList.remove('active');
            document.body.classList.remove('sidebar-open');
            overlay.classList.remove('active');
        }
    }

    function toggleMenu() {
        const sidebar = document.getElementById('sidebar');
        setSidebarState(!sidebar.classList.contains('active'));
    }

    function closeSidebar() {
        setSidebarState(false);
    }

    function syncSidebarForViewport() {
        const sidebar = document.getElementById('sidebar');
        setSidebarState(sidebar.classList.contains('active'));
    }

    function openPasswordModal() {
        const modal = document.getElementById('passwordModal');
        if (!modal) return;
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closePasswordModal() {
        const modal = document.getElementById('passwordModal');
        if (!modal) return;
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }

    window.addEventListener('resize', syncSidebarForViewport);
    setSidebarState(false);

    // Đóng sidebar khi nhấp vào overlay
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.querySelector('.menu-toggle-btn');

        if (sidebar.classList.contains('active') && 
            !sidebar.contains(event.target) && 
            !toggleBtn.contains(event.target)) {
            closeSidebar();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePasswordModal();
        }
    });

    document.querySelectorAll('.modal-toggle').forEach(function(button){
        button.addEventListener('click', function(){
            var targetId = button.getAttribute('data-target');
            var input = document.getElementById(targetId);
            if (!input) return;
            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            button.classList.toggle('is-visible', isHidden);
            button.setAttribute('aria-label', isHidden ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
        });
    });
</script>

</body>
</html>
