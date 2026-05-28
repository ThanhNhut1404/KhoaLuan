<?php
    $student = $student ?? [];
    $avatar = $student['avatar'] ?? '';
    $hasAvatar = is_string($avatar) && trim($avatar) !== '';
    $fullName = $student['ho_ten'] ?? 'Nguyen Van A';
    $mssv = $student['mssv'] ?? '22123456';
    $lop = $student['lop_hoc'] ?? 'K15A1';
?>

<style>
    .profile-shell {
        display: grid;
        gap: 16px;
        max-width: 1100px;
        margin: 0 auto;
    }

    .profile-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e8ecf3;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
    }

    .profile-hero {
        padding: 16px 18px;
        display: grid;
        gap: 14px;
    }

    .profile-hero__row {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 16px;
        align-items: center;
    }

    .profile-avatar {
        width: 82px;
        height: 82px;
        border-radius: 16px;
        background: linear-gradient(135deg, #e2e8f0 0%, #c7d2fe 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 2px solid #ffffff;
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.12);
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .profile-avatar span {
        font-size: 28px;
        font-weight: 800;
        color: #1d4ed8;
    }

    .profile-name {
        font-size: 18px;
        font-weight: 800;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .profile-badge {
        padding: 2px 8px;
        border-radius: 999px;
        background: #e0f2fe;
        color: #1d4ed8;
        font-size: 10px;
        font-weight: 700;
    }

    .profile-meta {
        color: #64748b;
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
        border-radius: 999px;
        border: 1px solid #dbe3f5;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        color: #1f2937;
    }

    .action-btn.primary {
        background: #1d4ed8;
        color: #ffffff;
        border-color: #1d4ed8;
    }

    .profile-section {
        padding: 14px 16px 16px 16px;
        display: grid;
        gap: 12px;
    }

    .section-title {
        font-size: 13px;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title .dot {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #eef2ff;
        color: #1d4ed8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 10px 16px;
        font-size: 12px;
    }

    .info-item {
        display: grid;
        gap: 4px;
    }

    .info-item span:first-child {
        color: #94a3b8;
        font-weight: 600;
        font-size: 11px;
    }

    .info-item span:last-child {
        color: #1f2937;
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
        border: 1px solid #edf2f7;
        font-size: 12px;
        color: #1f2937;
        font-weight: 700;
    }

    .contact-icon {
        width: 30px;
        height: 30px;
        border-radius: 10px;
        background: #eef2ff;
        color: #1d4ed8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
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
    <section class="profile-card profile-hero">
        <div class="profile-hero__row">
            <div class="profile-avatar">
                <?php if ($hasAvatar): ?>
                    <img src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar" />
                <?php else: ?>
                    <span><?= htmlspecialchars(substr($fullName, 0, 1), ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
            </div>
            <div>
                <div class="profile-name">
                    <?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?>
                    <span class="profile-badge">DANG HOC</span>
                </div>
                <div class="profile-meta">
                    <span>MSSV: <?= htmlspecialchars($mssv, ENT_QUOTES, 'UTF-8') ?></span>
                    <span>&#8226;</span>
                    <span><?= htmlspecialchars($student['nganh'] ?? 'Cong nghe thong tin', ENT_QUOTES, 'UTF-8') ?></span>
                    <span>&#8226;</span>
                    <span>Nien khoa <?= htmlspecialchars($student['khoa_hoc'] ?? '2021 - 2025', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
            <div class="profile-actions">
                <button class="action-btn primary" type="button">Chinh sua thong tin</button>
                <button class="action-btn" type="button">Doi mat khau</button>
            </div>
        </div>
    </section>

    <section class="profile-card profile-section">
        <div class="section-title"><span class="dot">i</span>Thong tin co ban</div>
        <div class="info-grid">
            <div class="info-item">
                <span>Ngay sinh</span>
                <span><?= htmlspecialchars($student['ngay_sinh'] ?? '20/05/2003', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-item">
                <span>Gioi tinh</span>
                <span><?= htmlspecialchars($student['gioi_tinh'] ?? 'Nam', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-item">
                <span>Noi sinh</span>
                <span><?= htmlspecialchars($student['noi_sinh'] ?? 'TP. Ho Chi Minh', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-item">
                <span>Dan toc</span>
                <span><?= htmlspecialchars($student['dan_toc'] ?? 'Kinh', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-item">
                <span>Ton giao</span>
                <span><?= htmlspecialchars($student['ton_giao'] ?? 'Khong', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </div>
    </section>

    <section class="profile-card profile-section">
        <div class="section-title"><span class="dot">+</span>Thong tin hoc tap</div>
        <div class="info-grid">
            <div class="info-item">
                <span>Lop</span>
                <span><?= htmlspecialchars($lop, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-item">
                <span>Nganh</span>
                <span><?= htmlspecialchars($student['nganh'] ?? 'Cong nghe thong tin', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-item">
                <span>Bac dao tao</span>
                <span><?= htmlspecialchars($student['bac_dao_tao'] ?? 'Dai hoc', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-item">
                <span>Loai hinh dao tao</span>
                <span><?= htmlspecialchars($student['loai_hinh_dao_tao'] ?? 'Chinh quy', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-item">
                <span>Khoa hoc</span>
                <span><?= htmlspecialchars($student['khoa_hoc'] ?? 'Khoa 2021', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="info-item">
                <span>Nam thu</span>
                <span><?= htmlspecialchars($student['nam_thu'] ?? 'Nam 3', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </div>
    </section>

    <section class="profile-card profile-section">
        <div class="section-title"><span class="dot">@</span>Thong tin lien lac</div>
        <div class="contact-row">
            <div class="contact-pill">
                <span class="contact-icon">@</span>
                <?= htmlspecialchars($student['email'] ?? 'student@example.com', ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div class="contact-pill">
                <span class="contact-icon">P</span>
                <?= htmlspecialchars($student['so_dien_thoai'] ?? '0123 456 789', ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div class="contact-pill">
                <span class="contact-icon">A</span>
                <?= htmlspecialchars($student['dia_chi_thuong_tru'] ?? '123 Duong 3/2, TP. Can Tho', ENT_QUOTES, 'UTF-8') ?>
            </div>
        </div>
    </section>
</div>
