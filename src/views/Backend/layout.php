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
            width: 247px;
            background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%);
            color: #e2e8f0;
            padding: 22px 16px;
            transition: width 0.2s ease;
        }

        .sidebar.collapsed { width: 86px; }

        .main { flex: 1; min-width: 0; }

        .header {
            background: var(--card);
            padding: 10px 20px;
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

        .admin-logo {
            height: 42px;
            width: auto;
            display: block;
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

        .admin-user-caret {
            width: 14px;
            height: 14px;
            stroke: currentColor;
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

        .modal-overlay.active { display: flex; }

        .modal-card {
            width: min(520px, 100%);
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);
            border: 1px solid var(--line);
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
            color: #0f2a5a;
        }

        .modal-close {
            border: none;
            background: transparent;
            color: #0f2a5a;
            font-size: 18px;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover { background: #f1f5f9; }

        .modal-body {
            padding: 18px;
            display: grid;
            gap: 12px;
        }

        .modal-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #0f2a5a;
            margin-bottom: 6px;
        }

        .modal-field .req { color: #ef4444; margin-left: 4px; }

        .modal-input-wrap { position: relative; }

        .modal-field input {
            width: 100%;
            padding: 12px 44px 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: #fbfdff;
            font-size: 14px;
            outline: none;
        }

        .modal-field input:focus {
            border-color: #c7d6ff;
            box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.12);
        }

        .modal-toggle {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-45%);
            border: none;
            background: transparent;
            color: #0f2a5a;
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

        .modal-actions { padding: 0 18px 18px; }

        .modal-save {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, #0f2a5a, #0b1f45);
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(15, 42, 90, 0.25);
        }

        .modal-save:hover { filter: brightness(0.96); }

        @keyframes modalIn {
            from { transform: translateY(8px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
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

<div class="modal-overlay" id="adminPasswordModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="adminPasswordModalTitle">
        <div class="modal-header">
            <span class="modal-title" id="adminPasswordModalTitle">Đổi mật khẩu</span>
            <button class="modal-close" type="button" aria-label="Đóng" onclick="closeAdminPasswordModal()">✕</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label for="adminCurrentPassword">Mật khẩu cũ<span class="req">*</span></label>
                <div class="modal-input-wrap">
                    <input id="adminCurrentPassword" type="password" placeholder="Nhập mật khẩu cũ" />
                    <button class="modal-toggle" type="button" aria-label="Hiện mật khẩu" data-target="adminCurrentPassword">
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
            <div class="modal-field">
                <label for="adminNewPassword">Mật khẩu mới<span class="req">*</span></label>
                <div class="modal-input-wrap">
                    <input id="adminNewPassword" type="password" placeholder="Nhập mật khẩu mới" />
                    <button class="modal-toggle" type="button" aria-label="Hiện mật khẩu" data-target="adminNewPassword">
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
            <div class="modal-field">
                <label for="adminConfirmPassword">Xác nhận mật khẩu<span class="req">*</span></label>
                <div class="modal-input-wrap">
                    <input id="adminConfirmPassword" type="password" placeholder="Nhập lại mật khẩu mới" />
                    <button class="modal-toggle" type="button" aria-label="Hiện mật khẩu" data-target="adminConfirmPassword">
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
                    closeAdminPasswordModal();
                }
            });
        }
    })();

    function openAdminPasswordModal() {
        var modal = document.getElementById('adminPasswordModal');
        if (!modal) return;
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeAdminPasswordModal() {
        var modal = document.getElementById('adminPasswordModal');
        if (!modal) return;
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }

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
