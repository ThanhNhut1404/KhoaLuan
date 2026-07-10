<?php
$title = 'Xác thực OTP';
$otp = $otp ?? '';
$errors = $errors ?? [];
$toast = $toast ?? null;
$resendAvailableIn = (int) ($resendAvailableIn ?? 0);
$otpRemainingSeconds = max(0, (int) ($otpRemainingSeconds ?? 0));

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
        padding: 34px 30px 16px;
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
        margin: 0 0 10px;
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
        font-size: 20px;
        font-weight: 800;
        letter-spacing: 7px;
        text-align: center;
    }

    .form-control:focus {
        border-color: #93c5fd;
        box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.13);
    }

    .field-error {
        margin-top: 6px;
        color: #b91c1c;
        font-size: 12.5px;
        font-weight: 700;
    }

    .btn-submit {
        min-height: 44px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #16a34a, var(--success));
        color: #ffffff;
        font-weight: 800;
        box-shadow: 0 12px 25px rgba(34, 197, 94, 0.26);
    }

    .btn-submit:hover {
        filter: brightness(0.93);
    }

    .otp-actions {
        gap: 8px;
    }

    .otp-countdown {
        margin: 0 0 12px;
        color: var(--muted);
        font-size: 13px;
        font-weight: 800;
        line-height: 1.45;
        text-align: center;
    }

    .otp-countdown strong {
        color: var(--brand);
        font-variant-numeric: tabular-nums;
    }

    .otp-countdown.is-expired {
        color: #b91c1c;
    }

    .otp-countdown.is-expired strong {
        color: #b91c1c;
    }

    .back-link {
        color: var(--brand);
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
    }

    .back-link:hover { color: #1649b8; }

    .resend-wait {
        color: var(--muted);
        font-size: 13px;
        font-weight: 800;
        text-align: center;
    }

    .resend-btn {
        border: 0;
        background: transparent;
        color: var(--brand);
        font-size: 13px;
        font-weight: 800;
        text-align: center;
        padding: 0;
        cursor: pointer;
        transition: color 200ms ease;
    }

    .resend-btn:hover:not(:disabled) {
        filter: brightness(0.9);
    }

    .resend-btn:disabled {
        color: #cbd5e1;
        cursor: not-allowed;
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
        <h1>Nhập mã OTP</h1>
        <p class="auth-note">Mã OTP gồm 6 chữ số và có hiệu lực trong 5 phút.</p>
        <p
            class="otp-countdown<?= $otpRemainingSeconds <= 0 ? ' is-expired' : '' ?>"
            id="otpCountdown"
            data-remaining="<?= $otpRemainingSeconds ?>"
        >
            <?php if ($otpRemainingSeconds > 0): ?>
                Mã OTP còn hiệu lực trong: <strong id="otpTimer">00:00</strong>
            <?php else: ?>
                <strong>Mã OTP đã hết hiệu lực.</strong>
            <?php endif; ?>
        </p>

        <form id="verifyOtpForm" method="post" action="/KhoaLuan/public/student.php?page=verify_otp" novalidate>
            <div class="mb-3">
                <label class="form-label" for="otp">Mã OTP</label>
                <input id="otp" name="otp" class="form-control" type="text" inputmode="numeric" maxlength="6" value="<?= $h($otp) ?>" placeholder="000000" autofocus>
                <?php if (!empty($errors['otp'])): ?>
                    <div class="field-error"><?= $h($errors['otp']) ?></div>
                <?php endif; ?>
                <div class="field-error" id="otpExpiredError" style="display:none;">Mã OTP đã hết hạn. Vui lòng yêu cầu mã mới.</div>
            </div>

            <div class="d-grid mt-3 otp-actions">
                <button class="btn-submit btn btn-success" type="submit">Xác thực OTP</button>
                <button
                    class="resend-btn"
                    id="resendOtpButton"
                    type="submit"
                    form="resendOtpForm"
                    <?= $otpRemainingSeconds > 0 ? 'disabled' : '' ?>
                >
                    Gửi lại mã OTP
                </button>
                <a class="back-link text-center" href="/KhoaLuan/public/student.php?action=login">Quay lại đăng nhập</a>
            </div>
        </form>
        <form id="resendOtpForm" method="post" action="/KhoaLuan/public/student.php?page=verify_otp&amp;action=resend_otp" hidden></form>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function() {
        var countdown = document.getElementById('otpCountdown');
        var timer = document.getElementById('otpTimer');
        var resendButton = document.getElementById('resendOtpButton');
        var verifyForm = document.getElementById('verifyOtpForm');
        var expiredError = document.getElementById('otpExpiredError');
        var otpInput = document.getElementById('otp');

        if (!countdown || !resendButton || !verifyForm) return;

        var remaining = parseInt(countdown.getAttribute('data-remaining') || '0', 10);

        function formatTime(seconds) {
            var minutes = Math.floor(seconds / 60);
            var secs = seconds % 60;
            return String(minutes).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        }

        function expireOtp() {
            remaining = 0;
            countdown.classList.add('is-expired');
            countdown.style.display = 'none';
            resendButton.disabled = false;
        }

        function renderTimer() {
            if (remaining <= 0) {
                expireOtp();
                return;
            }

            var formatted = formatTime(remaining);
            if (timer) timer.textContent = formatted;
            resendButton.disabled = true;
        }

        renderTimer();

        // Disable form submission if OTP has expired
        verifyForm.addEventListener('submit', function(event) {
            if (remaining <= 0) {
                event.preventDefault();
                if (expiredError) {
                    expiredError.style.display = 'block';
                }
                if (otpInput) {
                    otpInput.focus();
                }
            }
        });

        if (remaining > 0) {
            var intervalId = window.setInterval(function() {
                remaining -= 1;
                renderTimer();
                if (remaining <= 0) {
                    window.clearInterval(intervalId);
                }
            }, 1000);
        }
    })();

    window.setTimeout(function() {
        document.querySelectorAll('.auth-toast').forEach(function(toast) {
            toast.classList.add('is-hiding');
            window.setTimeout(function() { toast.remove(); }, 220);
        });
    }, 1800);
</script>
</body>
</html>
