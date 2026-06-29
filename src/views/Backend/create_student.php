<?php
$formData = $formData ?? [];
$errors = $errors ?? [];
$listKhoa = $listKhoa ?? [];
$listNganh = $listNganh ?? [];
$listNienKhoa = $listNienKhoa ?? [];
$listLop = $listLop ?? [];
$statusOptions = $statusOptions ?? [
    ['value' => 'Đang học', 'label' => 'Đang học'],
    ['value' => 'Tạm ngừng', 'label' => 'Tạm ngừng'],
    ['value' => 'Kết thúc', 'label' => 'Kết thúc'],
];

$value = static fn(string $key): string => htmlspecialchars($formData[$key] ?? '');
$selected = static fn(string $key, string $val): string => (string) ($formData[$key] ?? '') === $val ? 'selected' : '';
$error = static fn(string $key): string => '<span class="field-error' . (empty($errors[$key]) ? ' is-empty' : '') . '">' . (!empty($errors[$key]) ? htmlspecialchars($errors[$key]) : '&nbsp;') . '</span>';
?>

<div class="student-form-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">TẠO SINH VIÊN MỚI</h2>
        </div>

        <div class="panel-body card-body">
            <form method="post" action="/KhoaLuan/public/admin.php?page=create_student">
                <div class="form-grid">
                    <div class="form-field full-width">
                        <label class="field-label form-label" for="full_name">Họ và tên <span class="required">*</span></label>
                        <input id="full_name" name="full_name" class="field-input form-control" type="text" value="<?= $value('full_name') ?>" placeholder="Họ và tên" />
                        <?= $error('full_name') ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="department_id">Khoa/Bộ môn <span class="required">*</span></label>
                        <select id="department_id" name="department_id" class="field-input form-select">
                            <option value="">-- Chọn khoa/bộ môn --</option>
                            <?php foreach ($listKhoa as $khoa): ?>
                                <option value="<?= htmlspecialchars($khoa['MA_KHOA']) ?>" <?= $selected('department_id', (string) $khoa['MA_KHOA']) ?>><?= htmlspecialchars($khoa['TEN_KHOA']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= $error('department_id') ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="major_id">Ngành học <span class="required">*</span></label>
                        <select id="major_id" name="major_id" class="field-input form-select">
                            <option value="">-- Chọn ngành học --</option>
                            <?php foreach ($listNganh as $nganh): ?>
                                <option value="<?= htmlspecialchars($nganh['MA_NGANH']) ?>" data-ma-khoa="<?= htmlspecialchars($nganh['MA_KHOA']) ?>" <?= $selected('major_id', (string) $nganh['MA_NGANH']) ?>><?= htmlspecialchars($nganh['TEN_NGANH']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= $error('major_id') ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="academic_year_id">Niên khóa <span class="required">*</span></label>
                        <select id="academic_year_id" name="academic_year_id" class="field-input form-select">
                            <option value="">-- Chọn niên khóa --</option>
                            <?php foreach ($listNienKhoa as $nk): ?>
                                <option value="<?= htmlspecialchars($nk['MA_NIEN_KHOA']) ?>" <?= $selected('academic_year_id', (string) $nk['MA_NIEN_KHOA']) ?>><?= htmlspecialchars($nk['TEN_NIEN_KHOA']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= $error('academic_year_id') ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="class_id">Lớp học <span class="required">*</span></label>
                        <select id="class_id" name="class_id" class="field-input form-select">
                            <option value="">-- Chọn lớp học --</option>
                            <?php foreach ($listLop as $lop): ?>
                                <option value="<?= htmlspecialchars($lop['MA_LOP']) ?>" data-ma-khoa="<?= htmlspecialchars($lop['MA_KHOA']) ?>" data-ma-nganh="<?= htmlspecialchars($lop['MA_NGANH']) ?>" data-ma-nien-khoa="<?= htmlspecialchars($lop['MA_NIEN_KHOA']) ?>" <?= $selected('class_id', (string) $lop['MA_LOP']) ?>><?= htmlspecialchars($lop['TEN_LOP']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= $error('class_id') ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="birth_date">Ngày sinh <span class="required">*</span></label>
                        <input id="birth_date" name="birth_date" class="field-input form-control" type="date" value="<?= $value('birth_date') ?>" />
                        <?= $error('birth_date') ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="gender">Giới tính <span class="required">*</span></label>
                        <select id="gender" name="gender" class="field-input form-select">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="Nam" <?= $selected('gender', 'Nam') ?>>Nam</option>
                            <option value="Nữ" <?= $selected('gender', 'Nữ') ?>>Nữ</option>
                        </select>
                        <?= $error('gender') ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="email">Email <span class="required">*</span></label>
                        <input id="email" name="email" class="field-input form-control" type="email" value="<?= $value('email') ?>" placeholder="Email" />
                        <?= $error('email') ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="phone">Số điện thoại <span class="required">*</span></label>
                        <input id="phone" name="phone" class="field-input form-control" type="tel" value="<?= $value('phone') ?>" placeholder="Số điện thoại" />
                        <?= $error('phone') ?>
                    </div>

                    <div class="form-field full-width">
                        <label class="field-label form-label">Địa chỉ <span class="required">*</span></label>
                        <label class="sub-label">Số nhà</label>
                        <input id="address_line1" name="address_line1" class="field-input form-control" type="text" value="<?= $value('address_line1') ?>" placeholder="Số nhà" />
                        <?= $error('address_line1') ?>

                        <label class="sub-label">Ấp / Khóm / Đường</label>
                        <input id="address_line2" name="address_line2" class="field-input form-control" type="text" value="<?= $value('address_line2') ?>" placeholder="Ấp / Khóm / Đường" />
                        <?= $error('address_line2') ?>

                        <label class="sub-label">Xã / Phường / Thị trấn</label>
                        <input id="address_line3" name="address_line3" class="field-input form-control" type="text" value="<?= $value('address_line3') ?>" placeholder="Xã / Phường / Thị trấn" />
                        <?= $error('address_line3') ?>

                        <label class="sub-label">Quận / Huyện / Tỉnh / Thành phố</label>
                        <input id="address_line4" name="address_line4" class="field-input form-control" type="text" value="<?= $value('address_line4') ?>" placeholder="Quận / Huyện / Tỉnh / Thành phố" />
                        <?= $error('address_line4') ?>
                    </div>

                    <div class="form-field full-width">
                        <label class="field-label form-label" for="status">Trạng thái học tập</label>
                        <select id="status" name="status" class="field-input form-select">
                            <?php foreach ($statusOptions as $option): ?>
                                <option value="<?= htmlspecialchars($option['value']) ?>" <?= $selected('status', $option['value']) ?>><?= htmlspecialchars($option['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="/KhoaLuan/public/admin.php?page=list_students" class="action-btn secondary btn btn-outline-secondary">Hủy</a>
                    <button type="submit" class="action-btn primary btn btn-primary">Tạo sinh viên</button>
                </div>
            </form>

            <p class="hint">MSSV sẽ được tạo tự động; mật khẩu sinh viên được sinh từ họ tên + <strong>#tdu1234</strong>.</p>
        </div>
    </div>
</div>

<style>
    .student-form-page { display: grid; gap: 0; padding: 24px; }
    .page-panel { background: #ffffff; border: 1px solid #e8ecf3; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden; }
    .panel-header { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .panel-title { font-size: 14px; font-weight: 700; color: #0f2a5a; margin: 0; }
    .panel-body { padding: 20px; }
    .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .form-field { display: grid; gap: 6px; }
    .full-width { grid-column: 1 / -1; }
    .field-label { font-size: 12px; font-weight: 700; color: #0f2a5a; }
    .required { color: #dc2626; }
    .field-input { padding: 10px; border: 1px solid #e5e7eb; border-radius: 10px; background: #f9fafb; font-size: 13px; }
    .field-input:focus { outline: none; border-color: #0f2a5a; background: #ffffff; }
    .field-error { color: #dc2626; font-size: 12px; min-height: 18px; }
    .field-error.is-empty { visibility: hidden; }
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; }
    .action-btn { padding: 10px 18px; border-radius: 10px; font-weight: 700; text-decoration: none; }
    .btn-outline-secondary { border: 1px solid #cbd5f5; background: #fff; color: #0f2a5a; }
    .btn-primary { background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%); border-color: #0f2a5a; color: #fff; }
    .hint { margin-top: 16px; color: #475569; font-size: 13px; }
    @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
</style>
