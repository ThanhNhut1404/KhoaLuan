<?php
// Giao dien dang nhap admin (chi UI)
$title = 'Đăng nhập quản trị';
$error = $error ?? '';
?>

<style>
    :root {
        --ink: #0f172a;
        --muted: #64748b;
        --brand: #0f2a5a;
        --brand-2: #0b1f45;
        --accent: #f59e0b;
        --bg: #f6f3ef;
        --card: #ffffff;
        --line: #e2e8f0;
        --shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
    }

    * { box-sizing: border-box; }

    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: var(--bg);
        color: var(--ink);
    }

    .auth-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 28px;
        height: 64px;
        background: rgba(255, 255, 255, 0.76);
        border-bottom: 1px solid var(--line);
        backdrop-filter: blur(10px);
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .auth-header-left {
        display: flex;
        align-items: center;
        height: 100%;
    }

    .auth-logo {
        height: 64px;
        width: auto;
        display: block;
    }

    .auth-header .meta {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .auth-header .meta strong {
        font-size: 13px;
        letter-spacing: 0.6px;
        text-transform: uppercase;
    }

    .auth-header .meta span {
        color: var(--muted);
        font-size: 12px;
    }

    .auth-links {
        display: flex;
        gap: 14px;
        font-size: 13px;
        align-items: center;
    }

    .auth-links a {
        color: var(--muted);
        text-decoration: none;
        font-weight: 700;
    }

    .auth-links a:hover { color: var(--brand); }

    .auth-shell {
        min-height: calc(100vh - 57px);
        display: grid;
        grid-template-columns: minmax(260px, 1.1fr) minmax(320px, 0.9fr);
        background:
            radial-gradient(580px 240px at 8% 10%, rgba(15, 42, 90, 0.16), transparent 65%),
            radial-gradient(520px 260px at 88% 16%, rgba(11, 31, 69, 0.16), transparent 70%),
            linear-gradient(180deg, #f7f9fc 0%, #f2f5fb 55%, #eef2f7 100%);
        position: relative;
        overflow: hidden;
    }

    .auth-shell::before,
    .auth-shell::after {
        content: "";
        position: absolute;
        width: 320px;
        height: 320px;
        border-radius: 36% 64% 54% 46% / 46% 38% 62% 54%;
        background: rgba(15, 42, 90, 0.1);
        filter: blur(2px);
        animation: drift 12s ease-in-out infinite;
    }

    .auth-shell::before { left: -120px; bottom: -120px; }
    .auth-shell::after { right: -140px; top: -120px; animation-delay: -4s; }

    .auth-left {
        padding: 48px 54px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 26px;
        position: relative;
        z-index: 1;
    }

    .brand-row {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .brand-badge {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--brand), var(--brand-2));
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-weight: 800;
        letter-spacing: 1px;
        box-shadow: 0 12px 24px rgba(15, 118, 110, 0.28);
    }

    .brand-title {
        font-size: 34px;
        letter-spacing: 2px;
        margin: 0;
    }

    .brand-sub {
        margin: 0;
        color: var(--muted);
        font-size: 14px;
    }

    .hero {
        display: grid;
        gap: 16px;
    }

    .hero h1 {
        font-size: 52px;
        line-height: 1.05;
        letter-spacing: 2px;
        margin: 0;
    }

    .hero p {
        margin: 0;
        max-width: 46ch;
        color: var(--muted);
        line-height: 1.7;
        font-size: 15px;
    }

    .stats {
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(2, minmax(120px, 1fr));
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.6);
        padding: 14px 16px;
        border-radius: 14px;
        backdrop-filter: blur(8px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    }

    .stat-title { font-size: 12px; color: var(--muted); margin-bottom: 6px; }
    .stat-value { font-size: 20px; font-weight: 800; color: var(--ink); }

    .auth-right {
        background: rgba(255, 255, 255, 0.8);
        padding: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(8px);
    }

    .login-card {
        width: min(540px, 100%);
        min-height: 520px;
        background: var(--card);
        border-radius: 18px;
        padding: 40px 42px 42px;
        border: 1px solid var(--line);
        box-shadow: var(--shadow);
        animation: lift 500ms ease-out;
    }

    .login-card h2 {
        margin: 0 0 20px 0;
        font-size: 26px;
        color: var(--ink);
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .login-logo {
        height: 66px;
        width: auto;
        display: block;
        margin: -12px auto 10px;
    }

    .error {
        background: #fff1f2;
        color: #9f1239;
        border: 1px solid #fecdd3;
        padding: 10px 12px;
        border-radius: 12px;
        font-size: 13px;
        margin-bottom: 14px;
    }

    .form-row { margin-bottom: 14px; }
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--ink);
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid var(--line);
        font-size: 14px;
        background: #fbfbfb;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: rgba(15, 42, 90, 0.55);
        box-shadow: 0 0 0 4px rgba(15, 42, 90, 0.18);
    }

    .form-inline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        font-size: 13px;
        color: var(--muted);
        margin-top: 4px;
    }

    .form-inline a {
        color: var(--brand);
        text-decoration: none;
        font-weight: 700;
    }

    .btn-primary {
        width: 100%;
        border: none;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 15px;
        font-weight: 800;
        letter-spacing: 0.3px;
        color: #ffffff;
        background: linear-gradient(135deg, #16a34a, #22c55e);
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(34, 197, 94, 0.25);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .btn-primary:hover {
        filter: brightness(0.95);
    }


    .btn-secondary {
        width: 100%;
        border: 1px solid var(--line);
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 700;
        color: var(--ink);
        background: #ffffff;
        cursor: pointer;
    }

    .divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 16px 0;
        color: var(--muted);
        font-size: 12px;
    }

    .divider::before,
    .divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: var(--line);
    }

    .footnote {
        margin-top: 14px;
        font-size: 12px;
        color: var(--muted);
        text-align: center;
    }

    .footnote a {
        color: var(--brand);
        text-decoration: none;
        font-weight: 700;
    }

    .toggle-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: var(--muted);
        cursor: pointer;
        padding: 4px 6px;
    }

    .toggle-btn svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        fill: none;
    }

    .toggle-btn .eye-off { display: none; }
    .toggle-btn.is-visible .eye-on { display: none; }
    .toggle-btn.is-visible .eye-off { display: block; }

    .pwd-wrap { position: relative; }

    @keyframes drift {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(12px) scale(1.03); }
    }

    @keyframes lift {
        from { transform: translateY(10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    @media (max-width: 980px) {
        .auth-header { padding: 12px 20px; }
        .auth-shell { grid-template-columns: 1fr; }
        .auth-left { padding: 36px 26px; }
        .auth-right { padding: 36px 26px 50px; }
        .hero h1 { font-size: 42px; }
        .stats { grid-template-columns: 1fr; }
    }

    @media (max-width: 560px) {
        .auth-header { padding: 10px 16px; flex-direction: column; align-items: flex-start; gap: 8px; }
        .login-card { padding: 26px; }
        .hero h1 { font-size: 36px; }
    }
</style>

<header class="auth-header">
    <div class="auth-header-left">
        <a href="#" aria-label="Tải lại trang đăng nhập quản trị" onclick="location.reload(); return false;">
            <img class="auth-logo" src="/KhoaLuan/public/images/logo1.png" alt="Logo" />
        </a>
    </div>
    <nav class="auth-links" aria-label="Hướng dẫn và hỗ trợ">
        <a href="#">Hướng dẫn</a>
        <a href="#">Hỗ trợ</a>
    </nav>
</header>

<div class="auth-shell">
    <section class="auth-left">
        <div class="hero">
            <h1>Quản trị tập trung. Kiểm soát mọi hoạt động.</h1>
            <p>Đăng nhập để truy cập báo cáo vận hành, quản lý người dùng và theo dõi các cảnh báo quan trọng theo thời gian thực.</p>
        </div>

    </section>

    <section class="auth-right">
        <div class="login-card">
            <img class="login-logo" src="/KhoaLuan/public/images/logo1.png" alt="Logo" />
            <h2>Đăng nhập quản trị</h2>

            <?php if (!empty($error)): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="#" method="post">
                <div class="form-row">
                    <label class="form-label" for="admin-user">Tài khoản</label>
                    <input id="admin-user" name="admin_user" class="form-control" type="text" placeholder="Nhập tài khoản" required />
                </div>

                <div class="form-row">
                    <label class="form-label" for="admin-pass">Mật khẩu</label>
                    <div class="pwd-wrap">
                        <input id="admin-pass" name="admin_pass" class="form-control" type="password" placeholder="Nhập mật khẩu" required />
                        <button class="toggle-btn" type="button" id="toggleAdminPwd" aria-label="Hiện mật khẩu">
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

                <div class="form-inline">
                    <label><input type="checkbox" name="remember" /> Ghi nhớ đăng nhập</label>
                    <a href="#">Quên mật khẩu?</a>
                </div>

                <div style="margin-top: 18px; display: grid; gap: 12px;">
                    <button class="btn-primary" type="submit">Đăng nhập</button>
                </div>

                <div class="footnote">Chưa có tài khoản? <a href="#">Liên hệ</a></div>
            </form>
        </div>
    </section>
</div>

<script>
    (function(){
        var btn = document.getElementById('toggleAdminPwd');
        var pwd = document.getElementById('admin-pass');
        if (!btn || !pwd) return;
        btn.addEventListener('click', function(){
            var isHidden = pwd.type === 'password';
            pwd.type = isHidden ? 'text' : 'password';
            btn.classList.toggle('is-visible', isHidden);
            btn.setAttribute('aria-label', isHidden ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
        });
    })();
</script>
