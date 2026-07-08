<?php
// Giao diện đăng nhập sinh viên (hai cột, header, responsive)
$title = 'Đăng nhập Sinh viên';
$error = $error ?? '';
$success = $success ?? '';
$username = $username ?? '';
$redirectToStudent = $redirectToStudent ?? false;
$loginToast = $loginToast ?? null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap');

    :root{
        --primary: #1f6feb;
        --primary-rgb: 31, 111, 235;
        --primary-soft: #eaf3ff;
        --brand: var(--primary);
        --brand-2: #18a7e8;
        --brand-deep: #1649b8;
        --muted: #64748b;
        --ink: #0f172a;
        --line: #dbeafe;
        --success: #22c55e;
    }

    * { box-sizing: border-box; }

    html, body { min-height: 100%; }

    body {
        margin: 0;
        font-family: 'Manrope', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--ink);
        background: #f7fbff;
    }

    button,
    input {
        font: inherit;
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background:
            radial-gradient(560px 280px at 6% 10%, rgba(24, 167, 232, 0.20), transparent 70%),
            radial-gradient(520px 270px at 94% 8%, rgba(var(--primary-rgb), 0.18), transparent 72%),
            radial-gradient(520px 280px at 78% 92%, rgba(34, 197, 94, 0.10), transparent 70%),
            linear-gradient(135deg, #f7fbff 0%, #ffffff 48%, #edf6ff 100%);
        position: relative;
        overflow: hidden;
    }

    .login-wrapper::before,
    .login-wrapper::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        background: rgba(var(--primary-rgb), 0.08);
        filter: blur(2px);
        animation: drift 12s ease-in-out infinite;
        pointer-events: none;
    }

    .login-wrapper::before {
        width: 320px;
        height: 320px;
        left: -150px;
        bottom: -130px;
    }

    .login-wrapper::after {
        width: 270px;
        height: 270px;
        right: -120px;
        top: -100px;
        animation-delay: -3s;
    }

    .login-header {
        background: rgba(255, 255, 255, 0.82);
        border-bottom: 1px solid rgba(219, 234, 254, 0.9);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        padding: 0 clamp(18px, 4vw, 48px);
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
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
        height: 48px;
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

    .header-links {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: var(--muted);
    }

    .header-link {
        color: #334155;
        text-decoration: none;
        font-weight: 800;
        padding: 8px 12px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: color 0.2s ease, background 0.2s ease;
    }

    .header-link:hover {
        color: var(--brand);
        background: rgba(var(--primary-rgb), 0.08);
    }

    .header-link svg {
        width: 17px;
        height: 17px;
        stroke: currentColor;
        fill: none;
        flex: 0 0 17px;
    }

    .login-page {
        flex: 1;
        display: grid;
        grid-template-columns: minmax(520px, 1.35fr) minmax(340px, 0.75fr);
        gap: clamp(28px, 4vw, 56px);
        align-items: center;
        justify-content: center;
        max-width: 1320px;
        margin: 0 auto;
        padding: clamp(28px, 5vw, 54px) 24px;
        width: 100%;
        position: relative;
        z-index: 1;
    }

    .login-left {
        padding: clamp(34px, 5vw, 56px);
        border-radius: 28px;
        background:
            radial-gradient(280px 190px at 92% 12%, rgba(var(--primary-rgb), 0.12), transparent 74%),
            linear-gradient(135deg, rgba(236, 247, 255, 0.96) 0%, rgba(255, 255, 255, 0.96) 68%);
        min-height: 560px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 22px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(219, 234, 254, 0.92);
        box-shadow: inset 0 1px 0 #ffffff, 0 24px 48px rgba(15, 78, 150, 0.08);
    }

    .login-left::after {
        content: "";
        position: absolute;
        right: -72px;
        top: -86px;
        width: 230px;
        height: 230px;
        border-radius: 999px;
        background: rgba(191, 219, 254, 0.56);
        opacity: 0.9;
    }

    .welcome-title {
        font-size: clamp(42px, 5vw, 60px);
        color: var(--ink);
        margin: 0;
        letter-spacing: 1px;
        text-transform: uppercase;
        line-height: 1.15;
        font-weight: 800;
        position: relative;
        z-index: 1;
    }

    .welcome-title span {
        display: block;
    }

    .welcome-title .accent {
        color: var(--brand);
    }

    .welcome-sub {
        color: var(--muted);
        font-size: 16px;
        max-width: 48ch;
        line-height: 1.72;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .welcome-list {
        display: grid;
        gap: 12px;
        margin: 0;
        padding: 0;
        list-style: none;
        color: #334155;
        font-size: 15.5px;
        position: relative;
        z-index: 1;
    }

    .welcome-list li {
        display: flex;
        gap: 11px;
        align-items: center;
        min-width: 0;
    }

    .welcome-dot {
        width: 10px;
        height: 10px;
        flex: 0 0 10px;
        border-radius: 50%;
        background: linear-gradient(135deg,var(--brand),var(--brand-2));
        box-shadow: 0 0 0 5px rgba(var(--primary-rgb), 0.09);
    }

    .illustration {
        width: 100%;
        max-width: 460px;
        margin-top: 8px;
        animation: floaty 6s ease-in-out infinite;
        position: relative;
        z-index: 1;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 22px;
        padding: 34px 30px;
        min-height: 560px;
        max-width: 420px;
        width: 100%;
        margin: 0 0 0 auto;
        box-shadow: 0 26px 58px rgba(15, 78, 150, 0.15);
        border: 1px solid rgba(219, 234, 254, 0.96);
        animation: rise 420ms ease-out;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-card h3 {
        margin: 0 0 22px 0;
        color: var(--brand);
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-size: 22px;
        line-height: 1.25;
        position: relative;
        font-weight: 800;
    }

    .login-card h3::after {
        content: "";
        position: absolute;
        left: 50%;
        bottom: -11px;
        width: 58px;
        height: 4px;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--brand), var(--brand-2));
        transform: translateX(-50%);
    }

    .login-logo {
        display: block;
        height: 78px;
        width: auto;
        margin: -8px auto 18px;
    }

    .card-sub { font-size:13px; color:var(--muted); margin-bottom:16px; text-align:center; }
    .form-row { margin-bottom: 15px; }

    .form-label {
        display: block;
        font-size: 13px;
        color: var(--ink);
        margin-bottom: 7px;
        font-weight: 700;
    }

    .form-control {
        width: 100%;
        min-height: 46px;
        padding: 12px 14px;
        border-radius: 13px;
        border: 1px solid #cfe2ff;
        font-size: 14px;
        background: #fbfdff;
        color: var(--ink);
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    .form-control:focus {
        outline: none;
        border-color: #93c5fd;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.13);
    }

    .form-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 18px;
    }

    .btn-login {
        background: linear-gradient(135deg, #16a34a, var(--success));
        color: #fff;
        min-height: 44px;
        padding: 10px 18px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        font-weight: 800;
        letter-spacing: 0.2px;
        box-shadow: 0 12px 25px rgba(34,197,94,0.26);
        transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
        width: 100%;
    }

    .btn-login:hover {
        filter: brightness(0.93);
    }

    .pwd-wrap { position: relative; }

    .pwd-wrap .form-control {
        padding-right: 48px;
    }

    .toggle-btn {
        position: absolute;
        right: 9px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: var(--muted);
        cursor: pointer;
        padding: 0;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s ease, background 0.2s ease;
    }

    .toggle-btn:hover {
        color: var(--brand);
        background: rgba(var(--primary-rgb), 0.08);
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

    .helper-row {
        display: flex;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        color: var(--muted);
        margin-top: 4px;
    }

    .helper-row label {
        min-width: 0;
        white-space: nowrap;
    }

    .forgot-link {
        white-space: nowrap;
    }

    .captcha-row {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .captcha-input {
        flex: 1 1 132px;
        min-width: 108px;
        margin-right: 10px;
    }

    .captcha-refresh {
        width: auto;
        min-width: 34px;
        height: 46px;
        flex: 0 0 auto;
        border-radius: 12px;
        border: none;
        background: transparent;
        color: var(--brand);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 0;
        padding: 0 5px;
        transition: 0.2s ease;
    }

    .captcha-refresh:hover { background: rgba(var(--primary-rgb), 0.08); }
    .captcha-refresh svg { width:22px; height:22px; stroke: currentColor; fill: none; }

    .captcha-image {
        height: 46px;
        min-width: 128px;
        flex: 1 0 128px;
        border-radius: 12px;
        border: 1px dashed #9fc5ff;
        background: #f3f8ff;
        color: var(--muted);
        font-size: 12px;
        font-weight: 800;
        display: block;
        object-fit: cover;
        letter-spacing: 1px;
        user-select: none;
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
        border-bottom: 1px solid var(--primary-soft);
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
        font-size: 31px;
        line-height: 1;
        cursor: pointer;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        color: var(--brand);
        background: #eef4ff;
    }

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
        box-shadow: 0 10px 24px rgba(var(--primary-rgb), 0.25);
    }

    .modal-btn:hover { filter: brightness(0.96); }
    .login-message {
        font-size: 13px;
        font-weight: 800;
        text-align: center;
        min-height: 18px;
        margin-bottom: 0;
    }

    .login-message.error { color:#b91c1c; }
    .login-message.success { color:#15803d; }

    .login-toast {
        position: fixed;
        top: 18px;
        right: 18px;
        z-index: 2000;
        max-width: min(360px, calc(100vw - 36px));
        padding: 12px 16px;
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid #bbf7d0;
        color: #166534;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18);
        font-size: 13px;
        font-weight: 800;
        transition: opacity 180ms ease, transform 180ms ease;
    }

    .login-toast.error {
        border-color: #fecaca;
        color: #b91c1c;
    }

    .login-toast.is-hiding {
        opacity: 0;
        transform: translateY(-8px);
    }

    @keyframes floaty { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    @keyframes rise { from { transform: translateY(8px); opacity:0; } to { transform: translateY(0); opacity:1; } }
    @keyframes drift { 0%, 100% { transform: translateY(0px) scale(1); } 50% { transform: translateY(12px) scale(1.03); } }
    @keyframes modalIn { from { transform: translateY(8px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    @media (max-width: 980px) {
        .login-page {
            grid-template-columns: 1fr;
            padding: 28px;
            max-width: 760px;
        }

        .login-card {
            order: 1;
            margin: 0 auto;
            max-width: 480px;
            min-height: auto;
        }

        .login-left {
            order: 2;
            min-height: auto;
        }
    }

    @media (max-width: 640px) {
        .login-left {
            display: none;
        }

        .header-logo {
            height: 44px;
        }

        .header-links {
            gap: 4px;
            font-size: 13px;
        }

        .header-link {
            padding: 7px 8px;
        }
    }

    @media (max-width:480px) {
        .login-header {
            padding: 0 14px;
            height: 62px;
        }

        .login-page {
            padding: 18px 14px 24px;
            gap: 18px;
            align-items: start;
        }

        .login-card {
            padding: 28px 18px;
            border-radius: 20px;
            width: 100%;
        }

        .login-logo {
            height: 68px;
        }

        .login-card h3 {
            font-size: 19px;
        }

        .helper-row {
            align-items: flex-start;
            gap: 8px;
            font-size: 12.5px;
        }

        .captcha-row {
            flex-wrap: wrap;
        }

        .captcha-input {
            flex: 1 1 calc(100% - 56px);
        }

        .captcha-image {
            flex: 1 1 100%;
            width: 100%;
        }
    }
</style>
</head>
<body>
<?php if (!empty($loginToast)): ?>
    <div class="login-toast <?= ($loginToast['type'] ?? '') === 'error' ? 'error' : 'success' ?>" role="status" aria-live="polite">
        <?= htmlspecialchars($loginToast['message'] ?? '', ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<div class="login-wrapper container-fluid">
    <header class="login-header navbar">
        <div class="header-left">
            <a href="#" aria-label="Tải lại trang đăng nhập sinh viên" onclick="location.reload(); return false;">
                <img class="header-logo" src="/KhoaLuan/public/images/logo1.png" alt="Logo" />
            </a>
        </div>
        <div class="header-links navbar-nav">
            <a href="#" class="header-link nav-link">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M4 19.5V5.8A2.8 2.8 0 0 1 6.8 3H20v16H6.8A2.8 2.8 0 0 0 4 21.8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 7h8M8 11h6" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Hướng dẫn</span>
            </a>
            <a href="#" class="header-link nav-link">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M12 18h.01" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M9.2 9a3 3 0 1 1 5.2 2c-.8.7-1.5 1.2-1.9 1.9-.3.5-.5 1-.5 1.6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="12" r="9" stroke-width="2"/>
                </svg>
                <span>Hỗ trợ</span>
            </a>
        </div>
    </header>

    <main class="login-page">
        <section class="login-left">
            <h1 class="welcome-title"><span>Chào mừng</span><span class="accent">trở lại!</span></h1>
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
                        <stop offset="0" stop-color="var(--primary)" />
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

            <form method="post" action="/KhoaLuan/public/student.php?action=login">
                <div class="form-row">
                    <label class="form-label" for="mssv">Mã số sinh viên</label>
                    <input id="mssv" name="mssv" class="form-control" type="text" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>" placeholder="Nhập MSSV của bạn" required autofocus />
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
                    <a href="/KhoaLuan/public/student.php?page=forgot_password" class="forgot-link btn btn-link" style="color:var(--brand); text-decoration:none; font-weight:600; background:none; border:none; padding:0; cursor:pointer;">Quên mật khẩu?</a>
                </div>

                <div class="form-row">
                    <label class="form-label" for="captcha">Nhập mã xác thực</label>
                    <div class="captcha-row">
                        <input id="captcha" name="captcha" class="form-control captcha-input" type="text" placeholder="Nhập mã" aria-label="Nhập mã xác thực" autocomplete="off" />
                        <button id="refreshCaptcha" class="captcha-refresh btn btn-light" type="button" aria-label="Tải lại mã">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 12a8 8 0 1 1-2.34-5.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M20 4v6h-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <img id="captchaImage" class="captcha-image" src="/KhoaLuan/public/student.php?action=captcha" alt="Mã xác thực" />
                    </div>
                </div>

                <div class="form-actions">
                    <?php if (!empty($success) && empty($loginToast)): ?>
                        <div class="login-message success"><?= htmlspecialchars($success) ?></div>
                    <?php elseif (!empty($error) && empty($loginToast)): ?>
                        <div class="login-message error"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <button class="btn-login btn btn-success" type="submit">Đăng nhập</button>
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

    window.setTimeout(function() {
        document.querySelectorAll('.login-toast').forEach(function(toast) {
            toast.classList.add('is-hiding');
            window.setTimeout(function() { toast.remove(); }, 220);
        });
    }, 1800);

    (function(){
        var btn = document.getElementById('refreshCaptcha');
        var img = document.getElementById('captchaImage');
        var input = document.getElementById('captcha');
        if (!btn || !img) return;
        btn.addEventListener('click', function(){
            img.src = '/KhoaLuan/public/student.php?action=captcha&t=' + Date.now();
            if (input) {
                input.value = '';
                input.focus();
            }
        });
    })();

    <?php if ($redirectToStudent): ?>
    window.setTimeout(function() {
        window.location.href = '/KhoaLuan/public/student.php';
    }, 2000);
    <?php endif; ?>
</script>
</body>
</html>
