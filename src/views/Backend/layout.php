<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }

        /* HEADER */
        .header {
            height: 55px;
            background: #2c387e;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 15px;
        }

        .menu-btn {
            font-size: 24px;
            cursor: pointer;
            margin-right: 15px;
        }

        /* MENU TRƯỢT */
        .menu {
            position: fixed;
            top: 0;
            left: -240px;
            width: 240px;
            height: 100%;
            background: #ffffff;
            box-shadow: 2px 0 6px rgba(0,0,0,0.2);
            padding: 20px;
            transition: 0.3s;
            z-index: 1000;
        }

        .menu.active {
            left: 0;
        }

        .menu h3 {
            margin-top: 0;
        }

        .menu a {
            display: block;
            text-decoration: none;
            color: #333;
            margin: 12px 0;
        }

        /* OVERLAY */
        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.3);
            z-index: 999;
        }

        .overlay.active {
            display: block;
        }

        /* CONTENT */
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="overlay" id="overlay" onclick="toggleMenu()"></div>

<?php include 'header.php'; ?>

<div class="content">
    <?php include $content; ?>
</div>

<script>
    function toggleMenu() {
        document.getElementById('menu').classList.toggle('active');
        document.getElementById('overlay').classList.toggle('active');
    }
</script>

</body>
</html>
