<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eef0f2;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 220px;
            background: #1e1e1e;
            color: white;
            min-height: 100vh;
            padding: 20px;
        }

        .sidebar h2 {
            margin-top: 0;
        }

        .sidebar a {
            color: #ddd;
            display: block;
            text-decoration: none;
            margin: 12px 0;
        }

        .main {
            flex: 1;
        }

        .header {
            background: #ffffff;
            padding: 15px;
            border-bottom: 1px solid #ccc;
            font-weight: bold;
        }

        .content {
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <?php include 'sidebar.php'; ?>

    <div class="main">
        <?php include 'header.php'; ?>
        <div class="content">
            <?php include $content; ?>
        </div>
    </div>
</div>

</body>
</html>
