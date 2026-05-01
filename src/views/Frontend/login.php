<?php
// Giao diện đăng nhập sinh viên (hai cột, header, responsive)
$title = 'Đăng nhập';
$error = $error ?? '';
?>

<style>
    :root{ --brand:#1d4ed8; --brand-2:#0ea5e9; --muted:#6b7280; --ink:#0f172a; }
    * { box-sizing: border-box; }

    body { margin: 0; font-family: Arial, sans-serif; color: var(--ink); }

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
        height: 64px;
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
        height: 100%;
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
        grid-template-columns: minmax(280px, 1.1fr) minmax(320px, 0.9fr);
        gap:36px;
        align-items:stretch;
        padding:52px;
    }

    .login-left {
        padding:36px 40px;
        border-radius:18px;
        background: linear-gradient(135deg, #f1f7ff 0%, #ffffff 65%);
        min-height:520px;
        display:flex;
        flex-direction:column;
        justify-content:center;
        gap:18px;
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
        font-size:44px;
        color: var(--ink);
        margin:0;
        letter-spacing:1.6px;
        text-transform: uppercase;
    }

    .welcome-sub { color:var(--muted); font-size:15px; max-width:46ch; line-height:1.7; }
    .welcome-list { display:grid; gap:8px; margin:0; padding:0; list-style:none; color:#334155; font-size:14px; }
    .welcome-list li { display:flex; gap:8px; align-items:center; }
    .welcome-dot { width:8px; height:8px; border-radius:50%; background:linear-gradient(135deg,var(--brand),var(--brand-2)); }

    .illustration { width:100%; max-width:380px; margin-top:8px; animation: floaty 6s ease-in-out infinite; }

    .login-card {
        background:#fff;
        border-radius:18px;
        padding:30px 32px 32px;
        min-height:520px;
        box-shadow:0 24px 54px rgba(13,38,76,0.14);
        border:1px solid #eef2ff;
        animation: rise 420ms ease-out;
    }

    .login-card h3 {
        margin:0 0 8px 0;
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
        margin: -12px auto 10px;
    }

    .card-sub { font-size:13px; color:var(--muted); margin-bottom:16px; text-align:center; }
    .form-row { margin-bottom:12px; }
    .form-label { display:block; font-size:13px; color:var(--ink); margin-bottom:6px; font-weight:600; }
    .form-control { width:100%; padding:12px 14px; border-radius:12px; border:1px solid #e6eef8; font-size:14px; background:#fbfdff; transition: border-color 0.2s ease, box-shadow 0.2s ease; }
    .form-control:focus { outline:none; border-color:#bfdbfe; box-shadow:0 0 0 4px rgba(29,78,216,0.12); }
    .form-actions { display:flex; flex-direction:column; gap:10px; margin-top:16px; }
    .btn-login { background:linear-gradient(135deg,#16a34a,#22c55e); color:#fff; padding:12px 18px; border-radius:12px; border:none; cursor:pointer; font-weight:800; letter-spacing:0.2px; box-shadow:0 10px 24px rgba(34,197,94,0.25); transition: transform 0.15s ease, box-shadow 0.15s ease; width:100%; }
    .btn-login:hover { filter: brightness(0.95); }
    .btn-face { background:linear-gradient(135deg,var(--brand),#0b63c9); color:#ffffff; padding:12px 18px; border-radius:12px; border:none; cursor:pointer; font-weight:800; width:100%; box-shadow:0 10px 24px rgba(29,78,216,0.25); transition: transform 0.15s ease, box-shadow 0.15s ease; }
    .btn-face:hover { filter: brightness(0.95); }
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
    .or-text {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 6px 0;
        color: var(--muted);
        font-size: 12px;
        justify-content: center;
    }

    .or-text::before,
    .or-text::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e6eef8;
    }
    .helper-row { display:flex; gap:10px; align-items:center; justify-content: space-between; font-size:13px; color:var(--muted); }
    .error { color:#b91c1c; background:#fff1f2; padding:8px 10px; border-radius:10px; border:1px solid #fecaca; margin-bottom:12px; }

    @keyframes floaty { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    @keyframes rise { from { transform: translateY(8px); opacity:0; } to { transform: translateY(0); opacity:1; } }
    @keyframes drift { 0%, 100% { transform: translateY(0px) scale(1); } 50% { transform: translateY(12px) scale(1.03); } }

    @media (max-width: 980px) {
        .login-page { grid-template-columns: 1fr; padding:28px; }
        .login-left { order:1; }
        .login-card { order:2; }
    }
    @media (max-width:480px) {
        .login-header { padding:0 14px; height: 58px; }
        .login-page { padding:18px; gap:18px; }
        .welcome-title { font-size:36px; }
    }
</style>

<div class="login-wrapper">
    <header class="login-header">
        <div class="header-left">
            <a href="#" aria-label="Tải lại trang đăng nhập sinh viên" onclick="location.reload(); return false;">
                <img class="header-logo" src="/KhoaLuan/public/images/logo1.png" alt="Logo" />
            </a>
        </div>
        <div class="header-links">
            <a href="#" class="header-link">Hướng dẫn</a>
            <a href="#" class="header-link">Hỗ trợ</a>
        </div>
    </header>

    <main class="login-page">
        <section class="login-left">
            <h1 class="welcome-title">Chào mừng trở lại!</h1>
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

        <aside class="login-card">
            <img class="login-logo" src="/KhoaLuan/public/images/logo1.png" alt="Logo" />
            <h3>Đăng nhập hệ thống</h3>

            <?php if (!empty($error)): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" action="/student.php?action=login">
                <div class="form-row">
                    <label class="form-label" for="mssv">MSSV</label>
                    <input id="mssv" name="mssv" class="form-control" type="text" placeholder="Nhập MSSV" required autofocus />
                </div>

                <div class="form-row">
                    <label class="form-label" for="password">Mật khẩu</label>
                    <div class="pwd-wrap">
                        <input id="password" name="password" class="form-control" type="password" placeholder=" Nhập mật khẩu" required />
                        <button type="button" id="togglePwd" class="toggle-btn" aria-label="Hiện mật khẩu">
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
                    <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" name="remember" /> Ghi nhớ đăng nhập</label>
                    <a href="#" style="color:var(--brand); text-decoration:none; font-weight:600; margin-left:auto;">Quên mật khẩu?</a>
                </div>

                <div class="form-actions">
                    <button class="btn-login" type="submit">Đăng nhập</button>
                    <div class="or-text">hoặc</div>
                    <button class="btn-face" type="button">Đăng nhập bằng khuôn mặt</button>
                    <div style="font-size:13px; color:var(--muted); text-align:center;">Chưa có tài khoản? <a href="#" style="color:var(--brand); text-decoration:none; font-weight:700;">Liên hệ</a></div>
                </div>
            </form>
        </aside>
    </main>
</div>

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
</script>
