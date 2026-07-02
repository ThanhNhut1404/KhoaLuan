<?php
    $student = $student ?? [];
    $studentFound = !empty($student);
    $emptyText = 'Chưa cập nhật';
    $valueOrEmpty = static function ($value) use ($emptyText): string {
        $value = trim((string) ($value ?? ''));
        return $value !== '' ? $value : $emptyText;
    };
    $avatar = $student['avatar'] ?? '';
    $hasAvatar = is_string($avatar) && trim($avatar) !== '';
    $fullName = trim((string) ($student['ho_ten'] ?? ''));
    $mssv = trim((string) ($student['mssv'] ?? ''));
    $lop = trim((string) ($student['lop_hoc'] ?? ''));
    $avatarInitial = '?';
    if ($fullName !== '') {
        $avatarInitial = function_exists('mb_substr') ? mb_substr($fullName, 0, 1, 'UTF-8') : substr($fullName, 0, 1);
    }
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap');

    :root {
        --ink: #0f172a;
        --muted: #64748b;
        --accent: #1d4ed8;
        --accent-soft: rgba(29, 78, 216, 0.12);
        --line: #e5e7eb;
        --card: #ffffff;
        --shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
    }

    .profile-shell {
        position: relative;
        display: grid;
        gap: 14px;
        max-width: 980px;
        margin: 0 auto;
        padding: 16px;
        border-radius: 20px;
        font-family: 'Manrope', 'Segoe UI', Tahoma, sans-serif;
    }

    .profile-card {
        background: var(--card);
        border-radius: 10px;
        border: 1px solid var(--line);
    }

    .profile-card-header {
        padding: 12px 18px;
        border-bottom: 1px solid var(--line);
    }

    .profile-card-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--accent);
        letter-spacing: 0.4px;
    }

    .profile-hero {
        padding: 18px 20px;
        display: grid;
        gap: 12px;
        border-bottom: 1px solid var(--line);
    }

    .profile-hero__row {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 18px;
        align-items: center;
    }

    .profile-avatar {
        width: 96px;
        height: 96px;
        border-radius: 20px;
        background: linear-gradient(135deg, #e2e8f0 0%, #dbeafe 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 2px solid #ffffff;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.12);
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .profile-avatar span {
        font-size: 26px;
        font-weight: 800;
        color: var(--accent);
    }

    .profile-name {
        font-size: 20px;
        font-weight: 800;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .profile-badge {
        padding: 2px 10px;
        border-radius: 999px;
        background: var(--accent-soft);
        color: var(--accent);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.4px;
    }

    .profile-meta {
        color: var(--muted);
        font-size: 12px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .profile-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .action-btn {
        border-radius: 10px;
        border: 1px solid var(--line);
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        color: var(--ink);
        transition: 0.2s ease;
    }

    .action-btn:hover {
        border-color: rgba(29, 78, 216, 0.35);
        color: var(--accent);
        background: #eef4ff;
    }

    .action-btn.primary {
        background: var(--accent);
        color: #ffffff;
        border-color: var(--accent);
        box-shadow: 0 10px 20px rgba(29, 78, 216, 0.2);
    }

    .action-btn.primary:hover {
        background: #1b3fb5;
        color: #ffffff;
        border-color: #1b3fb5;
    }

    .action-btn.link {
        color: var(--accent);
        border-color: rgba(29, 78, 216, 0.2);
    }

    .action-btn.link:hover {
        background: #e8f0ff;
        border-color: rgba(29, 78, 216, 0.4);
    }

    .profile-section {
        padding: 16px 18px 18px 18px;
        display: grid;
        gap: 12px;
    }

    .profile-section + .profile-section {
        border-top: 1px solid var(--line);
    }

    .section-title {
        font-size: 12px;
        font-weight: 800;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .section-title .dot {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        background: var(--accent-soft);
        color: var(--accent);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 11px;
    }

    .section-title .dot svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px 16px;
        font-size: 12px;
    }

    .info-item {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: baseline;
    }

    .info-item span:first-child {
        color: var(--muted);
        font-weight: 600;
        font-size: 11px;
        text-transform: none;
    }

    .info-item span:last-child {
        color: var(--ink);
        font-weight: 700;
    }

    .contact-row {
        display: grid;
        gap: 8px;
    }

    .contact-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #eef2f7;
        font-size: 12px;
        color: var(--ink);
        font-weight: 700;
    }

    .contact-icon {
        width: 30px;
        height: 30px;
        border-radius: 10px;
        background: var(--accent-soft);
        color: var(--accent);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
    }

    .contact-icon svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
    }

    @media (max-width: 900px) {
        .profile-hero__row {
            grid-template-columns: 1fr;
            justify-items: start;
        }

        .profile-actions {
            justify-content: flex-start;
        }
    }
</style>

<div class="profile-shell">
    <section class="profile-card card">
        <div class="profile-card-header card-header">
            <div class="profile-card-title">Thông tin sinh viên</div>
        </div>
        <?php if (!$studentFound): ?>
            <div class="profile-section card-body">
                <div class="section-title">Không tìm thấy thông tin sinh viên.</div>
            </div>
        <?php else: ?>
        <div class="profile-hero">
            <div class="profile-hero__row">
                <div class="profile-avatar">
                    <?php if ($hasAvatar): ?>
                        <img src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar" />
                    <?php else: ?>
                        <span><?= htmlspecialchars($avatarInitial, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="profile-name">
                        <?= htmlspecialchars($valueOrEmpty($fullName), ENT_QUOTES, 'UTF-8') ?>
                        <span class="profile-badge badge rounded-pill"><?= htmlspecialchars($valueOrEmpty($student['trang_thai_hoc_tap'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <div class="profile-meta">
                        <span>MSSV: <?= htmlspecialchars($valueOrEmpty($mssv), ENT_QUOTES, 'UTF-8') ?></span>
                        <span>&#8226;</span>
                        <span><?= htmlspecialchars($valueOrEmpty($student['nganh'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                        <span>&#8226;</span>
                        <span>Khóa học <?= htmlspecialchars($valueOrEmpty($student['khoa_hoc'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>
                <div class="profile-actions">
                    <button id="openEditBtn" class="action-btn primary btn btn-primary" type="button">Chỉnh sửa thông tin</button>
                    <button class="action-btn link btn btn-outline-secondary" type="button" onclick="openPasswordModal()">Đổi mật khẩu</button>
                </div>
            </div>
        </div>

        <div class="profile-section card-body">
            <div class="section-title">
                <span class="dot">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="12" cy="7" r="4" stroke-width="2" />
                        <path d="M4 20c1.6-3 5-4 8-4s6.4 1 8 4" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                Thông tin cơ bản
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <span>Ngày sinh:</span>
                    <span><?= htmlspecialchars($valueOrEmpty($student['ngay_sinh'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="info-item">
                    <span>Giới tính:</span>
                    <span><?= htmlspecialchars($valueOrEmpty($student['gioi_tinh'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        </div>

        <div class="profile-section card-body">
            <div class="section-title">
                <span class="dot">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M3 7l9-4 9 4-9 4-9-4Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 10v5c0 2 4 4 5 4s5-2 5-4v-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                Thông tin học tập
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <span>Lớp:</span>
                    <span><?= htmlspecialchars($valueOrEmpty($lop), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="info-item">
                    <span>Ngành:</span>
                    <span><?= htmlspecialchars($valueOrEmpty($student['nganh'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="info-item">
                    <span>Khóa học:</span>
                    <span><?= htmlspecialchars($valueOrEmpty($student['khoa_hoc'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        </div>

        <div class="profile-section card-body">
            <div class="section-title">
                <span class="dot">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="2" />
                        <path d="m4 7 8 6 8-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                Thông tin liên lạc
            </div>
            <div class="contact-row">
                <div class="contact-pill badge rounded-pill">
                    <span class="contact-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="2" />
                            <path d="m4 7 8 6 8-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <?= htmlspecialchars($valueOrEmpty($student['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div class="contact-pill badge rounded-pill">
                    <span class="contact-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7 12.7 12.7 0 0 0 .7 2.8 2 2 0 0 1-.5 2.1l-1.2 1.2a16 16 0 0 0 6 6l1.2-1.2a2 2 0 0 1 2.1-.5 12.7 12.7 0 0 0 2.8.7 2 2 0 0 1 1.7 2Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <?= htmlspecialchars($valueOrEmpty($student['so_dien_thoai'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div class="contact-pill badge rounded-pill">
                    <span class="contact-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M12 21s7-5.3 7-11a7 7 0 1 0-14 0c0 5.7 7 11 7 11Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="10" r="2" stroke-width="2" />
                        </svg>
                    </span>
                    <?= htmlspecialchars($valueOrEmpty($student['dia_chi'] ?? ($student['dia_chi_thuong_tru'] ?? '')), ENT_QUOTES, 'UTF-8') ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </section>
    <?php if ($studentFound): ?>
        <?php include __DIR__ . '/edit_profile.php'; ?>
    <?php endif; ?>
</div>

<script>
    (function(){
        const openBtn = document.getElementById('openEditBtn');
        if (openBtn) openBtn.addEventListener('click', function(){ if (typeof openEditProfileModal === 'function') openEditProfileModal(); });
        // close when clicking outside edit modal
        window.addEventListener('click', function(e){
            const modal = document.getElementById('editProfileModal');
            if (modal && e.target === modal && typeof closeEditProfileModal === 'function') closeEditProfileModal();
        });
    })();
</script>
