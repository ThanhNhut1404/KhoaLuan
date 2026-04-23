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
            justify-content: space-between;
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

        .search-box {
            display: flex;
            align-items: center;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
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
            font-size: 24px;
            cursor: pointer;
            background: none;
            border: none;
            color: var(--primary);
            transition: 0.3s;
            padding: 0;
            z-index: 1001;
        }

        .menu-toggle-btn:hover {
            color: var(--secondary);
            transform: scale(1.1);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
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
        }

        .header-icon-link:hover {
            color: var(--secondary);
            background: rgba(255,255,255,0.1);
        }

        .header-icon-link i {
            font-size: 18px;
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
        }

        /* DROPDOWN MENU */
        .dropdown-menu {
            position: absolute;
            top: 50px;
            right: 0;
            background: white;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(29, 78, 216, 0.15);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transition: 0.3s;
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
            padding-left: 20px;
        }

        .dropdown-menu a i {
            width: 18px;
            text-align: center;
            color: #00a8e8;
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
            left: -280px;
            width: 280px;
            height: calc(100vh - 130px);
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
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
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

        .sidebar-menu i {
            width: 20px;
            text-align: center;
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

        /* FOOTER */
        .student-footer {
            background: #1d4ed8;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 13px;
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .footer-content {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 10px;
        }

        .footer-section {
            flex: 1;
            min-width: 150px;
        }

        .footer-section strong {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-weight: 600;
        }

        .footer-section p {
            margin: 5px 0 0 0;
        }

        .footer-section a {
            color: #fff;
            text-decoration: none;
            transition: 0.2s;
            opacity: 0.9;
        }

        .footer-section a:hover {
            opacity: 1;
            text-decoration: underline;
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
                gap: 10px;
                padding-right: 10px;
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
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar" id="sidebar">
    <div style="padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px;">
        <h3 style="margin: 0; font-size: 16px;">Menu</h3>
    </div>
    <ul class="sidebar-menu">
        <li><a href="#dashboard" onclick="closeSidebar()"><i class="fas fa-home"></i> <span>Trang chủ</span></a></li>
        <li><a href="#scores" onclick="closeSidebar()"><i class="fas fa-chart-bar"></i> <span>Xem điểm</span></a></li>
        <li><a href="#history" onclick="closeSidebar()"><i class="fas fa-history"></i> <span>Lịch sử</span></a></li>
        <li><a href="#profile" onclick="closeSidebar()"><i class="fas fa-user-circle"></i> <span>Hồ sơ</span></a></li>
        <li><a href="#contact" onclick="closeSidebar()"><i class="fas fa-headset"></i> <span>Liên hệ</span></a></li>
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
    function toggleMenu() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    }

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
