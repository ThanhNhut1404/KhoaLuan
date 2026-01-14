<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6fa;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 220px;
            background: #2c387e;
            color: white;
            min-height: 100vh;
            padding: 20px;
        }

        .sidebar h2 {
            margin-top: 0;
        }

        .sidebar a {
            color: white;
            display: block;
            text-decoration: none;
            margin: 12px 0;
        }

        .main {
            flex: 1;
        }

        .header {
            background: white;
            padding: 15px;
            border-bottom: 1px solid #ddd;
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
