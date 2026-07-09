<?php
$title = 'Đặt lại mật khẩu';
$errors = $errors ?? [];
$toast = $toast ?? null;

$h = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $h($title) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap');

    :root {
        --primary: #1f6feb;
        --primary-rgb: 31, 111, 235;
        --brand: var(--primary);
        --muted: #64748b;
        --ink: #0f172a;
        --success: #22c55e;
    }

    * { box-sizing: border-box; }

    body {
        min-height: 100vh;
        margin: 0;
        font-family: 'Manrope', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--ink);
        background:
            radial-gradient(560px 280px at 6% 10%, rgba(24, 167, 232, 0.20), transparent 70%),
            radial-gradient(520px 270px at 94% 8%, rgba(var(--primary-rgb), 0.18), transparent 72%),
            linear-gradient(135deg, #f7fbff 0%, #ffffff 48%, #edf6ff 100%);
    }

    .auth-header {
        height: 64px;
        padding: 0 clamp(18px, 4vw, 48px);
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.82);
        border-bottom: 1px solid rgba(219, 234, 254, 0.9);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        backdrop-filter: blur(10px);
    }

    .auth-logo { height: 48px; width: auto; display: block; }

    .auth-page {
        min-height: calc(100vh - 64px);
        display: grid;
        place-items: center;
        padding: 28px 16px;
    }

    .auth-card {
        width: min(100%, 430px);
        padding: 34px 30px 18px;
        border: 1px solid rgba(219, 234, 254, 0.96);
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 26px 58px rgba(15, 78, 150, 0.15);
    }

    .auth-card h1 {
        margin: 0 0 10px;
        color: var(--brand);
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-size: 22px;
        line-height: 1.25;
        font-weight: 800;
    }

    .auth-note {
        margin: 0 0 12px;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.6;
        text-align: center;
    }

    .form-label { font-size: 13px; color: var(--ink); font-weight: 700; }

    .form-control {
        min-height: 46px;
        border-radius: 13px;
        border: 1px solid #cfe2ff;
        background: #fbfdff;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #93c5fd;
        box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.13);
    }

    .pwd-wrap {
        position: relative;
    }

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

    .field-error {
        margin-top: 6px;
        color: #b91c1c;
        font-size: 12.5px;
        font-weight: 700;
    }

    .btn-submit {
        min-height: 44px;
        padding: 10px 18px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #16a34a, var(--success));
        color: #ffffff;
        font-weight: 800;
        letter-spacing: 0.2px;
        box-shadow: 0 12px 25px rgba(34, 197, 94, 0.26);
        transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
        width: 100%;
    }

    .btn-submit:hover {
        filter: brightness(0.93);
    }

    .back-link {
        color: var(--brand);
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
    }

    .auth-toast {
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

    .auth-toast.error {
        border-color: #fecaca;
        color: #b91c1c;
    }

    .auth-toast.is-hiding {
        opacity: 0;
        transform: translateY(-8px);
    }
</style>
</head>
<body>
<?php if (!empty($toast)): ?>
    <div class="auth-toast <?= ($toast['type'] ?? '') === 'error' ? 'error' : 'success' ?>" role="status" aria-live="polite">
        <?= $h($toast['message'] ?? '') ?>
    </div>
<?php endif; ?>

<header class="auth-header">
    <a href="/KhoaLuan/public/student.php?action=login" aria-label="Đăng nhập sinh viên">
        <img class="auth-logo" src="/KhoaLuan/public/images/logo1.png" alt="Logo">
    </a>
</header>

<main class="auth-page">
    <section class="auth-card card">
        <h1>Đặt lại mật khẩu</h1>
        <p class="auth-note">Tạo mật khẩu mới cho tài khoản sinh viên của bạn.</p>

        <form method="post" action="/KhoaLuan/public/student.php?page=reset_password" novalidate>
            <div class="mb-2">
                <label class="form-label" for="password">Mật khẩu mới</label>
                <div class="pwd-wrap">
                    <input id="password" name="password" class="form-control" type="password" placeholder="Nhập mật khẩu mới" data-password-strength autofocus>
                    <button type="button" class="toggle-btn btn btn-light" aria-label="Hiện mật khẩu" data-target="password">
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
                <?php if (!empty($errors['password'])): ?>
                    <div class="field-error"><?= $h($errors['password']) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-2">
                <label class="form-label" for="confirm_password">Xác nhận mật khẩu</label>
                <div class="pwd-wrap">
                    <input id="confirm_password" name="confirm_password" class="form-control" type="password" placeholder="Nhập lại mật khẩu mới" data-password-match="#password">
                    <button type="button" class="toggle-btn btn btn-light" aria-label="Hiện mật khẩu" data-target="confirm_password">
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
                <?php if (!empty($errors['confirm_password'])): ?>
                    <div class="field-error"><?= $h($errors['confirm_password']) ?></div>
                <?php endif; ?>
            </div>

            <div class="d-grid gap-2 mt-3">
                <button class="btn-submit btn btn-success" type="submit">Cập nhật mật khẩu</button>
                <a class="back-link text-center" href="/KhoaLuan/public/student.php?action=login">Quay lại đăng nhập</a>
            </div>
        </form>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/KhoaLuan/public/js/password-strength.js"></script>
<script>
    document.querySelectorAll('.toggle-btn[data-target]').forEach(function(button) {
        button.addEventListener('click', function() {
            var input = document.getElementById(button.getAttribute('data-target'));
            if (!input) return;
            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            button.classList.toggle('is-visible', isHidden);
            button.setAttribute('aria-label', isHidden ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
        });
    });

    window.setTimeout(function() {
        document.querySelectorAll('.auth-toast').forEach(function(toast) {
            toast.classList.add('is-hiding');
            window.setTimeout(function() { toast.remove(); }, 220);
        });
    }, 1800);
</script>
</body>
</html>
