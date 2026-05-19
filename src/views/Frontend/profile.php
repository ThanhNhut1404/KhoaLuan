<?php
    $student = $student ?? [];
    $avatar = $student['avatar'] ?? '';
    $hasAvatar = is_string($avatar) && trim($avatar) !== '';
    $fullName = $student['ho_ten'] ?? 'Nguyen Van A';
    $mssv = $student['mssv'] ?? '22123456';
    $lop = $student['lop_hoc'] ?? 'K15A1';
?>

<style>
    .profile-page {
        display: grid;
        gap: 18px;
        max-width: 1100px;
        margin: 0 auto;
    }

    .profile-hero {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e8ecf3;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        padding: 18px 20px;
        display: grid;
        gap: 16px;
    }

    .profile-hero__top {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 18px;
        align-items: center;
    }

    .profile-avatar {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e2e8f0 0%, #c7d2fe 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .profile-avatar span {
        font-size: 36px;
        font-weight: 700;
        color: #1d4ed8;
    }

    .profile-hero__info {
        display: grid;
        gap: 8px;
    }

    .profile-name {
        font-size: 20px;
        font-weight: 800;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .profile-badge {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #2563eb;
        color: #fff;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .profile-meta {
        color: #64748b;
        font-size: 13px;
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .profile-contact {
        display: grid;
        gap: 6px;
        color: #475569;
        font-size: 12px;
    }

    .profile-contact span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .profile-edit-btn {
        border: 1px solid #c7d2fe;
        color: #1d4ed8;
        background: #eef2ff;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
    }

    .profile-hero__grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 10px 18px;
        color: #1f2937;
        font-size: 12px;
    }

    .profile-hero__item {
        display: grid;
        gap: 4px;
        padding: 8px 10px;
        border-radius: 10px;
        background: #f8faff;
        border: 1px solid #eef2ff;
    }

    .profile-hero__item span:first-child {
        color: #64748b;
        font-weight: 600;
        font-size: 11px;
        text-transform: none;
    }

    .profile-hero__item span:last-child {
        font-weight: 700;
        color: #1f2937;
    }

    .profile-tabs {
        display: flex;
        gap: 18px;
        align-items: center;
        padding: 8px 12px;
        border-bottom: 1px solid #e8ecf3;
        background: #ffffff;
        border-radius: 12px 12px 0 0;
        font-size: 13px;
    }

    .profile-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        font-weight: 600;
        padding: 8px 10px;
        border-radius: 10px;
    }

    .profile-tab.active {
        color: #1d4ed8;
        background: #eef2ff;
    }

    .profile-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 18px;
    }

    .profile-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e8ecf3;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        padding: 14px 16px;
        display: grid;
        gap: 12px;
    }

    .profile-card__title {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
    }

    .profile-list {
        display: grid;
        gap: 10px;
        font-size: 12px;
        color: #475569;
    }

    .profile-row {
        display: grid;
        grid-template-columns: 140px 1fr;
        gap: 10px;
        align-items: center;
    }

    .profile-row span:first-child {
        color: #64748b;
        font-weight: 600;
    }

    .profile-row span:last-child {
        font-weight: 700;
        color: #1f2937;
        word-break: break-word;
    }

    .profile-pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        background: #ecfdf3;
        color: #15803d;
        width: max-content;
    }

    @media (max-width: 900px) {
        .profile-hero__top {
            grid-template-columns: 1fr;
            text-align: left;
        }

        .profile-avatar {
            margin: 0 auto;
        }

        .profile-edit-btn {
            justify-self: start;
        }
    }

    @media (max-width: 640px) {
        .profile-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="profile-page">
    <section class="profile-hero">
        <div class="profile-hero__top">
            <div class="profile-avatar">
                <?php if ($hasAvatar): ?>
                    <img src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar" />
                <?php else: ?>
                    <span><?= htmlspecialchars(substr($fullName, 0, 1), ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
            </div>
            <div class="profile-hero__info">
                <div class="profile-name">
                    <?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?>
                    <span class="profile-badge">V</span>
                </div>
                <div class="profile-meta">
                    <span><?= htmlspecialchars($mssv, ENT_QUOTES, 'UTF-8') ?></span>
                    <span>&#8226;</span>
                    <span><?= htmlspecialchars($lop, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-contact">
                    <span><?= htmlspecialchars($student['email'] ?? 'student@example.com', ENT_QUOTES, 'UTF-8') ?></span>
                    <span><?= htmlspecialchars($student['so_dien_thoai'] ?? '0123 456 789', ENT_QUOTES, 'UTF-8') ?></span>
                    <span><?= htmlspecialchars($student['dia_chi_thuong_tru'] ?? '123 Duong 3/2, TP. Can Tho', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <button class="profile-edit-btn" type="button">Chinh sua thong tin</button>
            </div>
        </div>
        <div class="profile-hero__grid">
            <div class="profile-hero__item">
                <span>Ngay sinh</span>
                <span><?= htmlspecialchars($student['ngay_sinh'] ?? '20/05/2003', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="profile-hero__item">
                <span>Gioi tinh</span>
                <span><?= htmlspecialchars($student['gioi_tinh'] ?? 'Nam', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="profile-hero__item">
                <span>Dan toc</span>
                <span><?= htmlspecialchars($student['dan_toc'] ?? 'Kinh', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="profile-hero__item">
                <span>Ton giao</span>
                <span><?= htmlspecialchars($student['ton_giao'] ?? 'Khong', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="profile-hero__item">
                <span>Ngay vao truong</span>
                <span><?= htmlspecialchars($student['ngay_vao_truong'] ?? '15/08/2021', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div class="profile-hero__item">
                <span>Khoa</span>
                <span><?= htmlspecialchars($student['khoa_hoc'] ?? 'Khoa 15 (2021 - 2025)', ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </div>
    </section>

    <div class="profile-tabs">
        <div class="profile-tab active">Thong tin ca nhan</div>
        <div class="profile-tab">Thong tin hoc tap</div>
        <div class="profile-tab">Thong tin lien he</div>
        <div class="profile-tab">Tai khoan</div>
    </div>

    <div class="profile-content">
        <section class="profile-card">
            <div class="profile-card__title">Thong tin ca nhan</div>
            <div class="profile-list">
                <div class="profile-row">
                    <span>Ho va ten</span>
                    <span><?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-row">
                    <span>Ma sinh vien</span>
                    <span><?= htmlspecialchars($mssv, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-row">
                    <span>Lop</span>
                    <span><?= htmlspecialchars($lop, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-row">
                    <span>Khoa</span>
                    <span><?= htmlspecialchars($student['khoa'] ?? 'Cong nghe thong tin', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-row">
                    <span>Nganh</span>
                    <span><?= htmlspecialchars($student['nganh'] ?? 'Cong nghe thong tin', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-row">
                    <span>He dao tao</span>
                    <span><?= htmlspecialchars($student['loai_hinh_dao_tao'] ?? 'Chinh quy', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-row">
                    <span>Chuong trinh</span>
                    <span><?= htmlspecialchars($student['bac_dao_tao'] ?? 'Dai hoc', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-row">
                    <span>Nam hoc hien tai</span>
                    <span><?= htmlspecialchars($student['nam_hoc'] ?? '2024 - 2025', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-row">
                    <span>Email</span>
                    <span><?= htmlspecialchars($student['email'] ?? 'student@example.com', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        </section>

        <section class="profile-card">
            <div class="profile-card__title">Thong tin lien he</div>
            <div class="profile-list">
                <div class="profile-row">
                    <span>So dien thoai</span>
                    <span><?= htmlspecialchars($student['so_dien_thoai'] ?? '0123 456 789', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-row">
                    <span>Email</span>
                    <span><?= htmlspecialchars($student['email'] ?? 'student@example.com', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-row">
                    <span>Dia chi thuong tru</span>
                    <span><?= htmlspecialchars($student['dia_chi_thuong_tru'] ?? '123 Duong 3/2, TP. Can Tho', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-row">
                    <span>Dia chi hien tai</span>
                    <span><?= htmlspecialchars($student['dia_chi_hien_tai'] ?? '123 Duong 3/2, TP. Can Tho', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        </section>

        <section class="profile-card">
            <div class="profile-card__title">Thong tin tai khoan</div>
            <div class="profile-list">
                <div class="profile-row">
                    <span>Ten dang nhap</span>
                    <span><?= htmlspecialchars($student['ten_dang_nhap'] ?? strtolower($mssv), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-row">
                    <span>Ngay tao tai khoan</span>
                    <span><?= htmlspecialchars($student['ngay_tao'] ?? '15/08/2021 14:30', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="profile-row">
                    <span>Trang thai</span>
                    <span class="profile-pill">Dang hoat dong</span>
                </div>
                <div class="profile-row">
                    <span>Doi mat khau</span>
                    <span>Thay doi</span>
                </div>
            </div>
        </section>
    </div>
</div>
