<?php
// Giao diện đăng nhập sinh viên (hai cột, header, responsive)
$title = 'Đăng nhập';
$error = $error ?? '';
?>

<style>
    :root{ --brand:#1d4ed8; --brand-2:#0ea5e9; --muted:#6b7280; --ink:#0f172a; }
    * { box-sizing: border-box; }
    .login-wrapper { min-height: 100vh; display:flex; flex-direction:column; background: radial-gradient(1200px 400px at 10% -10%, #eaf3ff 0%, transparent 60%), linear-gradient(180deg,#f8fbff 0%, #ffffff 100%); }
    .login-header { background: #ffffffcc; border-bottom:1px solid #eef2ff; padding:12px 24px; display:flex; align-items:center; justify-content:space-between; backdrop-filter: blur(6px); }
    .header-title { font-weight:800; color:var(--ink); letter-spacing:0.8px; text-transform:uppercase; font-size:14px; }
    .header-sub { font-size:12px; color:var(--muted); }
    .header-meta { display:flex; flex-direction:column; gap:2px; }
    .header-links { display:flex; align-items:center; gap:14px; font-size:13px; color:var(--muted); }
    .header-link { color:var(--muted); text-decoration:none; font-weight:600; }
    .header-link:hover { color:var(--brand); }

    .login-page { flex:1; display:grid; grid-template-columns: 1fr 440px; gap:36px; align-items:center; padding:52px; }

    .login-left { padding:34px; border-radius:16px; background: linear-gradient(135deg, #f1f7ff 0%, #ffffff 65%); min-height:380px; display:flex; flex-direction:column; justify-content:center; gap:18px; position:relative; overflow:hidden; box-shadow: inset 0 1px 0 #ffffff, 0 12px 30px rgba(2,6,23,0.05); }
    .login-left::after { content:""; position:absolute; right:-80px; top:-80px; width:220px; height:220px; background: radial-gradient(circle at center, #dbeafe 0%, transparent 70%); opacity:0.8; }
    .welcome-title { font-size:30px; color: var(--ink); margin:0; letter-spacing:0.2px; }
    .welcome-sub { color:var(--muted); font-size:15px; max-width:46ch; line-height:1.6; }
    .welcome-list { display:grid; gap:8px; margin:0; padding:0; list-style:none; color:#334155; font-size:14px; }
    .welcome-list li { display:flex; gap:8px; align-items:center; }
    .welcome-dot { width:8px; height:8px; border-radius:50%; background:linear-gradient(135deg,var(--brand),var(--brand-2)); }

    .illustration { width:100%; max-width:380px; margin-top:8px; animation: floaty 6s ease-in-out infinite; }

    .login-card { background:#fff; border-radius:14px; padding:30px; box-shadow:0 18px 40px rgba(13,38,76,0.08); border:1px solid #eef2ff; animation: rise 420ms ease-out; }
    .login-card h3 { margin:0 0 10px 0; color:var(--brand); text-align:center; text-transform:uppercase; letter-spacing:0.8px; }
    .card-sub { font-size:13px; color:var(--muted); margin-bottom:16px; text-align:center; }
    .form-row { margin-bottom:12px; }
    .form-label { display:block; font-size:13px; color:var(--muted); margin-bottom:6px; }
    .form-control { width:100%; padding:11px 12px; border-radius:10px; border:1px solid #e6eef8; font-size:14px; background:#fbfdff; transition: border-color 0.2s ease, box-shadow 0.2s ease; }
    .form-control:focus { outline:none; border-color:#bfdbfe; box-shadow:0 0 0 4px rgba(29,78,216,0.12); }
    .form-actions { display:flex; flex-direction:column; gap:10px; margin-top:16px; }
    .btn-login { background:linear-gradient(135deg,var(--brand),#0b63c9); color:#fff; padding:12px 18px; border-radius:10px; border:none; cursor:pointer; font-weight:800; letter-spacing:0.2px; box-shadow:0 8px 20px rgba(29,78,216,0.25); transition: transform 0.15s ease, box-shadow 0.15s ease; width:100%; }
    .btn-login:hover{ transform: translateY(-1px); box-shadow:0 12px 26px rgba(29,78,216,0.3); }
    .btn-face { background:linear-gradient(135deg,#16a34a,#22c55e); color:#ffffff; padding:12px 18px; border-radius:10px; border:none; cursor:pointer; font-weight:800; width:100%; box-shadow:0 8px 20px rgba(34,197,94,0.25); transition: transform 0.15s ease, box-shadow 0.15s ease; }
    .btn-face:hover { transform: translateY(-1px); box-shadow:0 12px 24px rgba(34,197,94,0.32); }
    .or-text { text-align:center; font-size:12px; color:var(--muted); margin:4px 0; }
    .helper-row { display:flex; gap:10px; align-items:center; font-size:13px; color:var(--muted); }
    .error { color:#b91c1c; background:#fff1f2; padding:8px 10px; border-radius:10px; border:1px solid #fecaca; margin-bottom:12px; }

    @keyframes floaty { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    @keyframes rise { from { transform: translateY(8px); opacity:0; } to { transform: translateY(0); opacity:1; } }

    @media (max-width: 980px) {
        .login-page { grid-template-columns: 1fr; padding:28px; }
        .login-left { order:2; }
        .login-card { order:1; }
    }
    @media (max-width:480px) {
        .login-header { padding:10px 14px; }
        .login-page { padding:18px; gap:18px; }
    }
</style>

<div class="login-wrapper">
    <header class="login-header">
        <div class="header-meta">
            <div class="header-title">Cổng thông tin sinh viên</div>
            <div class="header-sub">Đăng nhập để tiếp tục</div>
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
            <h3>Đăng nhập hệ thống</h3>

            <?php if (!empty($error)): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" action="/student.php?action=login">
                <div class="form-row">
                    <label class="form-label" for="mssv"><strong>MSSV</strong></label>
                    <input id="mssv" name="mssv" class="form-control" type="text" placeholder="Nhập MSSV" required autofocus />
                </div>

                <div class="form-row">
                    <label class="form-label" for="password"><strong>Mật khẩu</strong></label>
                    <div style="position:relative;">
                        <input id="password" name="password" class="form-control" type="password" placeholder=" Nhập mật khẩu" required />
                        <button type="button" id="togglePwd" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); background:transparent; border:none; color:var(--muted); cursor:pointer;">Hiện</button>
                    </div>
                </div>

                <div class="form-row helper-row">
                    <label style="display:flex; align-items:center; gap:8px;"><input type="checkbox" name="remember" /> Ghi nhớ đăng nhập</label>
                    <a href="#" style="color:var(--brand); text-decoration:none; font-weight:600;">Quên mật khẩu?</a>
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
            if (pwd.type === 'password') { pwd.type = 'text'; btn.textContent = 'Ẩn'; }
            else { pwd.type = 'password'; btn.textContent = 'Hiện'; }
        });
    })();
</script>
