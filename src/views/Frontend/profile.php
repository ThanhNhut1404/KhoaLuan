<?php
    $student = $student ?? [];
    $avatar = $student['avatar'] ?? '';
    $hasAvatar = is_string($avatar) && trim($avatar) !== '';
?>

<style>
    .profile-page {
        display: grid;
        gap: 20px;
        max-width: 980px;
        margin: 0 auto;
    }

    .profile-card {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #e8ecf3;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .profile-card__header {
        padding: 16px 18px;
        background: #f8f9fb;
        border-bottom: 1px solid #e8ecf3;
        font-size: 18px;
        font-weight: 700;
        color: #1d4ed8;
    }

    .profile-card__body {
        padding: 18px;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 140px 1fr;
        gap: 18px;
        align-items: start;
    }

    .profile-avatar {
        width: 140px;
        height: 140px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1d4ed8 0%, #1047a1 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 54px;
        font-weight: 700;
        overflow: hidden;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .profile-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px 20px;
    }

    .profile-field {
        display: flex;
        align-items: baseline;
        gap: 6px;
    }

    .profile-field__label {
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        white-space: nowrap;
    }

    .profile-field__value {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        word-break: break-word;
        flex: 1;
    }

    @media (max-width: 768px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }

        .profile-fields {
            grid-template-columns: 1fr;
        }

        .profile-field {
            grid-template-columns: 1fr;
        }
    }

    .profile-actions {
        display: flex;
        justify-content: flex-end;
    }

    .profile-update-btn {
        border: none;
        border-radius: 10px;
        padding: 10px 18px;
        font-size: 14px;
        font-weight: 700;
        color: #ffffff;
        background: linear-gradient(135deg, #1d4ed8 0%, #1047a1 100%);
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(29, 78, 216, 0.2);
    }

    .profile-update-btn:hover {
        filter: brightness(0.96);
    }

    @media (max-width: 768px) {
        .profile-actions {
            justify-content: center;
        }
    }
</style>

<div class="profile-page">
    <section class="profile-card">
        <div class="profile-card__header">Thông tin học vấn</div>
        <div class="profile-card__body">
            <div class="profile-grid">
                <div class="profile-avatar">
                    <?php if ($hasAvatar): ?>
                        <img src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar" />
                    <?php else: ?>
                        <span><?= htmlspecialchars(substr($student['ho_ten'] ?? 'S', 0, 1), ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </div>
                <div class="profile-fields">
                    <div class="profile-field">
                        <div class="profile-field__label">MSSV:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['mssv'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="profile-field">
                        <div class="profile-field__label">Lớp học:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['lop_hoc'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="profile-field">
                        <div class="profile-field__label">Họ tên:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['ho_ten'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="profile-field">
                        <div class="profile-field__label">Khoa học:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['khoa_hoc'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="profile-field">
                        <div class="profile-field__label">Ngày sinh:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['ngay_sinh'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="profile-field">
                        <div class="profile-field__label">Bậc đào tạo:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['bac_dao_tao'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="profile-field">
                        <div class="profile-field__label">Nơi sinh:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['noi_sinh'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="profile-field">
                        <div class="profile-field__label">Loại hình đào tạo:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['loai_hinh_dao_tao'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="profile-field">
                        <div class="profile-field__label">Giới tính:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['gioi_tinh'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="profile-field">
                        <div class="profile-field__label">Ngành:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['nganh'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="profile-field">
                        <div class="profile-field__label">Khoa:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['khoa'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="profile-field">
                        <div class="profile-field__label">Ngày vào trường:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['ngay_vao_truong'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="profile-field">
                        <div class="profile-field__label">Trạng thái:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['trang_thai'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="profile-field">
                        <div class="profile-field__label">Cơ sở:</div>
                        <div class="profile-field__value"><?= htmlspecialchars($student['co_so'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="profile-card">
        <div class="profile-card__header">Thông tin cá nhân</div>
        <div class="profile-card__body">
            <div class="profile-fields">
                <div class="profile-field">
                    <div class="profile-field__label">Dân tộc:</div>
                    <div class="profile-field__value"><?= htmlspecialchars($student['dan_toc'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <div class="profile-field">
                    <div class="profile-field__label">Tôn giáo:</div>
                    <div class="profile-field__value"><?= htmlspecialchars($student['ton_giao'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <div class="profile-field">
                    <div class="profile-field__label">Số CMND:</div>
                    <div class="profile-field__value"><?= htmlspecialchars($student['so_cmnd'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <div class="profile-field">
                    <div class="profile-field__label">Ngày cấp:</div>
                    <div class="profile-field__value"><?= htmlspecialchars($student['ngay_cap_cmnd'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <div class="profile-field">
                    <div class="profile-field__label">Nơi cấp:</div>
                    <div class="profile-field__value"><?= htmlspecialchars($student['noi_cap_cmnd'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <div class="profile-field">
                    <div class="profile-field__label">Số điện thoại:</div>
                    <div class="profile-field__value"><?= htmlspecialchars($student['so_dien_thoai'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <div class="profile-field">
                    <div class="profile-field__label">Email</div>
                    <div class="profile-field__value"><?= htmlspecialchars($student['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                </div>
            </div>
        </div>
    </section>

    <div class="profile-actions">
        <button type="button" class="profile-update-btn">Cập nhật thông tin</button>
    </div>
</div>
