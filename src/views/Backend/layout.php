<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap');

        :root {
            --ink: #1f2937;
            --muted: #6b7280;
            --brand: #1d4ed8;
            --brand-2: #1047a1;
            --accent: #00a8e8;
            --bg: #f0f2f5;
            --card: #ffffff;
            --line: #e8ecf3;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Space Grotesk', system-ui, sans-serif;
            background: var(--bg);
            color: var(--ink);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 230px;
            background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%);
            color: #e2e8f0;
            padding: 22px 16px;
            transition: width 0.2s ease;
        }

        .sidebar.collapsed { width: 86px; }

        .main { flex: 1; min-width: 0; }

        .header {
            background: var(--card);
            padding: 14px 20px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .content { padding: 22px; }

        .icon-btn {
            border: 1px solid var(--line);
            background: #ffffff;
            color: #0f2a5a;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .notif-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ef4444;
            color: #ffffff;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 999px;
            border: 2px solid #ffffff;
        }

        .header-search {
            flex: 1;
            max-width: 420px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8f9fb;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 8px 12px;
            color: #0f2a5a;
        }

        .header-search input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 14px;
            color: #0f2a5a;
        }

        .header-search input::placeholder { color: #94a3b8; }

        .header-search svg { color: #0f2a5a; }

        .header-right { display: flex; align-items: center; gap: 10px; }
        .header-title { font-weight: 700; letter-spacing: 0.3px; color: var(--brand); }

        .header-icon-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 10px;
            text-decoration: none;
            color: #0f2a5a;
            font-size: 13px;
            font-weight: 600;
            position: relative;
            background: #ffffff;
            border: none;
        }

        .header-icon-link svg { color: #0f2a5a; }

        .header-icon-link:hover { background: #f1f5f9; }

        .admin-user-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: none;
            padding: 6px 10px;
            border-radius: 999px;
            cursor: pointer;
            color: #0f2a5a;
            font-size: 13px;
            font-weight: 600;
        }

        .admin-user-btn:hover { background: #f1f5f9; }

        .admin-user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand), var(--accent));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }

        .user-menu { position: relative; }
        .user-dropdown {
            position: absolute;
            right: 0;
            top: 44px;
            min-width: 190px;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
            padding: 6px;
            display: none;
            z-index: 20;
        }

        .user-dropdown.open { display: block; }
        .user-dropdown a {
            display: block;
            padding: 10px 12px;
            text-decoration: none;
            color: #0f2a5a;
            border-radius: 8px;
            font-size: 14px;
        }

        .user-dropdown a svg {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            vertical-align: -3px;
            color: #0f2a5a;
            stroke: currentColor;
        }

        .user-dropdown a:hover { background: #f1f5f9; }

        .menu-divider {
            height: 1px;
            background: var(--line);
            margin: 6px 4px;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            background: rgba(255,255,255,0.08);
            margin-bottom: 16px;
        }

        .brand-badge {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
        }

        .nav { display: grid; gap: 6px; }
        .nav a {
            color: #dbeafe;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .nav a:hover { background: rgba(255,255,255,0.12); color: #ffffff; }
        .nav .nav-text { white-space: nowrap; }
        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .brand-text { display: none; }

        .sidebar.collapsed .brand { justify-content: center; }
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

<script>
    (function(){
        var toggle = document.getElementById('sidebarToggle');
        var sidebar = document.querySelector('.sidebar');
        var userBtn = document.getElementById('userMenuBtn');
        var userMenu = document.getElementById('userMenu');
        if (!toggle || !sidebar) return;
        toggle.addEventListener('click', function(){
            sidebar.classList.toggle('collapsed');
        });

        if (userBtn && userMenu) {
            userBtn.addEventListener('click', function(e){
                e.stopPropagation();
                var isOpen = userMenu.classList.toggle('open');
                userBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            document.addEventListener('click', function(){
                userMenu.classList.remove('open');
                userBtn.setAttribute('aria-expanded', 'false');
            });

            document.addEventListener('keydown', function(e){
                if (e.key === 'Escape') {
                    userMenu.classList.remove('open');
                    userBtn.setAttribute('aria-expanded', 'false');
                }
            });
        }
    })();
</script>

</body>
</html>
