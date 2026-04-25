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
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-left: 20px;
        }

        .logo {
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
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
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 0 12px;
            width: 100%;
            max-width: 400px;
            transition: 0.3s;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .search-box:focus-within {
            background: rgba(255,255,255,0.25);
            border-color: rgba(255,255,255,0.3);
        }

        .search-input {
            flex: 1;
            border: none;
            background: transparent;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            outline: none;
        }

        .search-input::placeholder {
            color: rgba(255,255,255,0.7);
        }

        .search-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
            padding: 4px 8px;
            transition: 0.3s;
        }

        .search-btn:hover {
            color: #fff;
            opacity: 0.8;
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
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }

        .header-icon:hover {
            color: var(--secondary);
            transform: scale(1.1);
        }

        .header-icon-link {
            display: flex;
            align-items: center;
            gap: 6px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 4px;
            white-space: nowrap;
        }

        .header-icon-link:hover {
            color: var(--secondary);
            background: rgba(255,255,255,0.1);
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
            color: white;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .user-btn:hover {
            background: rgba(255,255,255,0.1);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--secondary);
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
            color: #333;
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
            background: #f0f2f5;
            color: #2c387e;
        }

        .dropdown-menu a svg {
            width: 18px;
            height: 18px;
            text-align: center;
            color: #00a8e8;
            stroke: currentColor;
        }

        .dropdown-divider {
            height: 1px;
            background: #e8ecf3;
            margin: 5px 0;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 70px;
            left: calc(-1 * var(--sidebar-width));
            width: var(--sidebar-width);
            height: auto;
            max-height: calc(100vh - 90px);
            background: var(--primary);
            color: white;
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
            padding-top: 14px; /* tạo khoảng cách với nút menu */
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #d0d5e8;
            text-decoration: none;
            transition: 0.3s;
            border-left: 3px solid transparent;
            font-size: 14px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left-color: #00a8e8;
        }

        .sidebar-menu svg {
            width: 20px;
            height: 20px;
            text-align: center;
            stroke: currentColor;
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
        <li><a href="#scores" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 19h16M7 16V9M12 16V5M17 16v-7" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Xem điểm</span></a></li>
        <li><a href="#history" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 12a9 9 0 1 0 3-6.7" stroke-width="2" stroke-linecap="round"/>
                <path d="M3 4v5h5" stroke-width="2" stroke-linecap="round"/>
                <path d="M12 7v5l3 2" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Lịch sử</span></a></li>
        <li><a href="#profile" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke-width="2" stroke-linecap="round"/>
                <path d="M4 20c1.6-3 5-4 8-4s6.4 1 8 4" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Hồ sơ</span></a></li>
        <li><a href="#contact" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72 12.7 12.7 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.22a2 2 0 0 1 2.11-.45 12.7 12.7 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Liên hệ</span></a></li>
    </ul>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

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
</script>

</body>
</html>
