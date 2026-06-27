<?php
// Giao diện đăng nhập sinh viên (hai cột, header, responsive)
$title = 'Đăng nhập';
$error = $error ?? '';
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap');

    :root{ --brand:#1d4ed8; --brand-2:#0ea5e9; --muted:#6b7280; --ink:#0f172a; }
    * { box-sizing: border-box; }

    body { margin: 0; font-family: 'Manrope', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: var(--ink); }

    .login-wrapper {
        min-height: 100vh;
        display:flex;
        flex-direction:column;
        background:
            radial-gradient(600px 260px at 8% 12%, rgba(14, 165, 233, 0.18), transparent 70%),
            radial-gradient(520px 240px at 88% 10%, rgba(29, 78, 216, 0.18), transparent 72%),
            linear-gradient(180deg,#f8fbff 0%, #ffffff 60%, #f2f6ff 100%);
        position: relative;
        overflow: hidden;
    }

    .login-wrapper::before,
    .login-wrapper::after {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        border-radius: 40% 60% 58% 42% / 50% 42% 58% 50%;
        background: rgba(29, 78, 216, 0.08);
        filter: blur(1px);
        animation: drift 12s ease-in-out infinite;
    }

    .login-wrapper::before { left: -120px; bottom: -120px; }
    .login-wrapper::after { right: -140px; top: -120px; animation-delay: -3s; }

    .login-header {
        background: rgba(255, 255, 255, 0.78);
        border-bottom:1px solid #eef2ff;
        padding:0 28px;
        height: 56px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap: 16px;
        backdrop-filter: blur(10px);
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
        height: 100%;
    }

    .header-logo {
        height: 40px;
        width: auto;
        display: block;
    }

    .header-title {
        font-weight:800;
        color:var(--ink);
        letter-spacing:1.4px;
        text-transform:uppercase;
        font-size:20px;
    }

    .header-sub { font-size:12px; color:var(--muted); }
    .header-meta { display:none; }
    .header-links { display:flex; align-items:center; gap:14px; font-size:13px; color:var(--muted); }
    .header-link { color:var(--muted); text-decoration:none; font-weight:700; }
    .header-link:hover { color:var(--brand); }

    .login-page {
        flex:1;
        display:grid;
        grid-template-columns: minmax(280px, 1.15fr) minmax(320px, 0.85fr);
        gap:48px;
        align-items:center;
        justify-content:center;
        max-width:1200px;
        margin:0 auto;
        padding:40px 24px;
        width:100%;
    }

    .login-left {
        padding:48px 52px;
        border-radius:18px;
        background: linear-gradient(135deg, #f1f7ff 0%, #ffffff 65%);
        min-height:550px;
        display:flex;
        flex-direction:column;
        justify-content:center;
        gap:24px;
        position:relative;
        overflow:hidden;
        box-shadow: inset 0 1px 0 #ffffff, 0 18px 36px rgba(2,6,23,0.06);
    }

    .login-left::after {
        content:"";
        position:absolute;
        right:-80px;
        top:-80px;
        width:220px;
        height:220px;
        background: radial-gradient(circle at center, #dbeafe 0%, transparent 70%);
        opacity:0.8;
    }

    .welcome-title {
        font-size:52px;
        color: var(--ink);
        margin:0;
        letter-spacing:1.6px;
        text-transform: uppercase;
        line-height: 1.15;
    }

    .welcome-sub { color:var(--muted); font-size:16.5px; max-width:46ch; line-height:1.7; }
    .welcome-list { display:grid; gap:10px; margin:0; padding:0; list-style:none; color:#334155; font-size:15.5px; }
    .welcome-list li { display:flex; gap:10px; align-items:center; }
    .welcome-dot { width:10px; height:10px; border-radius:50%; background:linear-gradient(135deg,var(--brand),var(--brand-2)); }

    .illustration { width:100%; max-width:440px; margin-top:12px; animation: floaty 6s ease-in-out infinite; }

    .login-card {
        background:#fff;
        border-radius:18px;
        padding:34px 28px;
        min-height:550px;
        max-width:400px;
        width:100%;
        margin:0;
        box-shadow:0 24px 54px rgba(13,38,76,0.14);
        border:1px solid #eef2ff;
        animation: rise 420ms ease-out;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-card h3 {
        margin:0 0 16px 0;
        color:var(--brand);
        text-align:center;
        text-transform:uppercase;
        letter-spacing:1px;
        font-size:22px;
    }

    .login-logo {
        display: block;
        height: 66px;
        width: auto;
        margin: -8px auto 16px;
    }

    .card-sub { font-size:13px; color:var(--muted); margin-bottom:16px; text-align:center; }
    .form-row { margin-bottom:14px; }
    .form-label { display:block; font-size:13px; color:var(--ink); margin-bottom:6px; font-weight:600; }
    .form-control { width:100%; padding:12px 14px; border-radius:12px; border:1px solid #e6eef8; font-size:14px; background:#fbfdff; transition: border-color 0.2s ease, box-shadow 0.2s ease; }
    .form-control:focus { outline:none; border-color:#bfdbfe; box-shadow:0 0 0 4px rgba(29,78,216,0.12); }
    .form-actions { display:flex; flex-direction:column; gap:8px; margin-top:18px; }
    .btn-login { background:linear-gradient(135deg,#16a34a,#22c55e); color:#fff; padding:12px 18px; border-radius:12px; border:none; cursor:pointer; font-weight:800; letter-spacing:0.2px; box-shadow:0 10px 24px rgba(34,197,94,0.25); transition: transform 0.15s ease, box-shadow 0.15s ease; width:100%; }
    .btn-login:hover { filter: brightness(0.95); }
    .pwd-wrap { position: relative; }
    .toggle-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: var(--muted);
        cursor: pointer;
        padding: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
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
    .helper-row { display:flex; gap:10px; align-items:center; justify-content: space-between; font-size:13px; color:var(--muted); margin-top:8px; }
    .captcha-row { display:flex; gap:10px; align-items:center; margin-top:10px; }
    .captcha-input { flex: 0 0 140px; }
    .captcha-refresh {
        width:40px;
        height:40px;
        border-radius:10px;
        border:1px solid #e6eef8;
        background:#ffffff;
        color:var(--brand);
        cursor:pointer;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        transition: 0.2s ease;
    }
    .captcha-refresh:hover { background:#eef2ff; }
    .captcha-refresh svg { width:18px; height:18px; stroke: currentColor; fill: none; }
    .captcha-image {
        height:40px;
        min-width:150px;
        border-radius:10px;
        border:1px dashed #cbd5f5;
        background:#f8faff;
        color:var(--muted);
        font-size:12px;
        font-weight:600;
        display:flex;
        align-items:center;
        justify-content:center;
        letter-spacing:1px;
        user-select:none;
    }
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
        width: min(420px, 100%);
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
        padding: 14px 16px;
        border-bottom: 1px solid #eef2ff;
        background: #f8faff;
    }

    .modal-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--brand);
    }

    .modal-close {
        border: none;
        background: transparent;
        color: var(--brand);
        font-size: 18px;
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover { background: #eef2ff; }

    .modal-body {
        padding: 16px;
        display: grid;
        gap: 12px;
    }

    .modal-note {
        font-size: 13px;
        color: var(--muted);
        line-height: 1.5;
    }

    .modal-actions {
        padding: 0 16px 16px;
    }

    .modal-btn {
        width: 100%;
        border: none;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 700;
        color: #ffffff;
        background: linear-gradient(135deg, var(--brand), #0b63c9);
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(29, 78, 216, 0.25);
    }

    .modal-btn:hover { filter: brightness(0.96); }
    .error { color:#b91c1c; background:#fff1f2; padding:8px 10px; border-radius:10px; border:1px solid #fecaca; margin-bottom:12px; }

    @keyframes floaty { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    @keyframes rise { from { transform: translateY(8px); opacity:0; } to { transform: translateY(0); opacity:1; } }
    @keyframes drift { 0%, 100% { transform: translateY(0px) scale(1); } 50% { transform: translateY(12px) scale(1.03); } }
    @keyframes modalIn { from { transform: translateY(8px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    @media (max-width: 980px) {
        .login-page { grid-template-columns: 1fr; padding:28px; }
        .login-left { order:1; }
        .login-card { order:2; margin: 0 auto; }
    }
    @media (max-width:480px) {
        .login-header { padding:0 14px; height: 58px; }
        .login-page { padding:18px; gap:18px; }
        .welcome-title { font-size:36px; }
    }
</style>

<div class="login-wrapper container-fluid">
    <header class="login-header navbar">
        <div class="header-left">
            <a href="#" aria-label="Tải lại trang đăng nhập sinh viên" onclick="location.reload(); return false;">
                <img class="header-logo" src="/KhoaLuan/public/images/logo1.png" alt="Logo" />
            </a>
        </div>
        <div class="header-links navbar-nav">
            <a href="#" class="header-link nav-link">Hướng dẫn</a>
            <a href="#" class="header-link nav-link">Hỗ trợ</a>
        </div>
    </header>

    <main class="login-page">
        <section class="login-left">
            <h1 class="welcome-title">Chào mừng<br/>trở lại!</h1>
            <p class="welcome-sub">Đăng nhập để truy cập điểm rèn luyện, hồ sơ học tập và thông báo mới nhất. Hệ thống bảo mật nhiều lớp để bảo vệ tài khoản của bạn.</p>
            <ul class="welcome-list">
                <li><span class="welcome-dot"></span>Tra cứu điểm rèn luyện nhanh chóng</li>
                <li><span class="welcome-dot"></span>Nhận thông báo cập nhật từ nhà trường</li>
                <li><span class="welcome-dot"></span>Bảo mật thông tin cá nhân an toàn</li>
            </ul>

            <svg class="illustration" viewBox="0 0 640 220" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true">
                <defs>
                    <linearGradient id="g1" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#dbeafe" />
                        <stop offset="1" stop-color="#eef6ff" />
                    </linearGradient>
                    <linearGradient id="g2" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#1d4ed8" />
                        <stop offset="1" stop-color="#0ea5e9" />
                    </linearGradient>
                </defs>
                <rect x="16" y="24" width="520" height="140" rx="16" fill="url(#g1)" />
                <rect x="56" y="52" width="200" height="16" rx="8" fill="#c7e0ff" />
                <rect x="56" y="80" width="320" height="12" rx="6" fill="#dbeafe" />
                <rect x="56" y="102" width="260" height="12" rx="6" fill="#dbeafe" />
                <rect x="420" y="52" width="80" height="80" rx="14" fill="#ffffff" />
                <circle cx="460" cy="92" r="18" fill="url(#g2)" />
                <rect x="420" y="150" width="120" height="18" rx="9" fill="#e2e8f0" />
            </svg>
        </section>

        <aside class="login-card card">
            <img class="login-logo" src="/KhoaLuan/public/images/logo1.png" alt="Logo" />
            <h3>Đăng nhập hệ thống</h3>

            <?php if (!empty($error)): ?>
                <div class="error alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" action="/student.php?action=login">
                <div class="form-row">
                    <label class="form-label" for="mssv">Mã số sinh viên</label>
                    <input id="mssv" name="mssv" class="form-control" type="text" placeholder="Nhập MSSV" required autofocus />
                </div>

                <div class="form-row">
                    <label class="form-label" for="password">Mật khẩu</label>
                    <div class="pwd-wrap">
                        <input id="password" name="password" class="form-control" type="password" placeholder=" Nhập mật khẩu" required />
                        <button type="button" id="togglePwd" class="toggle-btn btn btn-light" aria-label="Hiện mật khẩu">
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

                <div class="form-row helper-row">
                    <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" name="remember" class="form-check-input" /> Ghi nhớ đăng nhập</label>
                    <button type="button" class="forgot-link btn btn-link" onclick="openForgotModal()" style="color:var(--brand); text-decoration:none; font-weight:600; background:none; border:none; padding:0; cursor:pointer;">Quên mật khẩu?</button>
                </div>

                <div class="form-row">
                    <label class="form-label" for="captchaInput">Nhập mã</label>
                    <div class="captcha-row">
                        <input id="captchaInput" class="form-control captcha-input" type="text" placeholder="Nhập mã" aria-label="Nhập mã xác thực" />
                        <button class="captcha-refresh btn btn-light" type="button" aria-label="Tải lại mã">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 12a8 8 0 1 1-2.34-5.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M20 4v6h-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <div class="captcha-image" aria-label="Mã xác thực">CAPTCHA</div>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="btn-login btn btn-success" type="submit">Đăng nhập</button>
                    <div style="font-size:13px; color:var(--muted); text-align:center;">Chưa có tài khoản? <a href="#" style="color:var(--brand); text-decoration:none; font-weight:700;">Liên hệ</a></div>
                </div>
            </form>
        </aside>
    </main>
</div>

<?php include __DIR__ . '/forgot_password.php'; ?>

<script>
    (function(){
        var btn = document.getElementById('togglePwd');
        var pwd = document.getElementById('password');
        if (!btn || !pwd) return;
        btn.addEventListener('click', function(){
            var isHidden = pwd.type === 'password';
            pwd.type = isHidden ? 'text' : 'password';
            btn.classList.toggle('is-visible', isHidden);
            btn.setAttribute('aria-label', isHidden ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
        });
    })();

    function openForgotModal() {
        var modal = document.getElementById('forgotModal');
        if (!modal) return;
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        var emailInput = document.getElementById('forgotEmail');
        if (emailInput) emailInput.focus();
    }

    function closeForgotModal() {
        var modal = document.getElementById('forgotModal');
        if (!modal) return;
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }

    document.addEventListener('click', function(event) {
        var modal = document.getElementById('forgotModal');
        if (!modal) return;
        if (event.target === modal) {
            closeForgotModal();
        }
    });
</script>
