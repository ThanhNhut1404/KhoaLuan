<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f4f6f8;
        }
        header {
            background: #2c387e;
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
        }
        .menu-btn {
            font-size: 22px;
            cursor: pointer;
            margin-right: 15px;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100%;
            background: #2c387e;
            color: white;
            padding: 20px;
            transition: 0.3s;
        }
        .sidebar.active {
            left: 0;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>

<header>
    <div class="menu-btn" onclick="toggleMenu()">☰</div>
    <div>Hệ thống sinh viên</div>
</header>

<div class="sidebar" id="sidebar">
    <p>🏠 Trang chủ</p>
    <p>📸 Điểm danh</p>
    <p>📊 Hoạt động</p>
    <p>🚪 Đăng xuất</p>
</div>

<div class="content">
    <?php require $content; ?>
</div>

<script>
function toggleMenu() {
    document.getElementById('sidebar').classList.toggle('active');
}
</script>

</body>
</html>
