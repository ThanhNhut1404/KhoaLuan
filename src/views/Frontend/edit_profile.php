<?php
$student = $student ?? [];
$profileErrors = $profileErrors ?? [];
$profileFormData = $profileFormData ?? [];
$openEditProfileModal = !empty($openEditProfileModal);

$dateValue = '';
if (!empty($profileFormData['birth_date'])) {
    $dateValue = (string) $profileFormData['birth_date'];
} elseif (!empty($student['ngay_sinh'])) {
    $rawDate = (string) $student['ngay_sinh'];
    $date = DateTime::createFromFormat('Y-m-d', $rawDate) ?: DateTime::createFromFormat('d/m/Y', $rawDate);
    $dateValue = $date instanceof DateTime ? $date->format('Y-m-d') : $rawDate;
}

$addressSource = !empty($profileFormData) ? $profileFormData : [
    'address_line1' => '',
    'address_line2' => '',
    'address_line3' => '',
    'address_province' => '',
];

if (empty($profileFormData)) {
    $parts = array_map('trim', explode(',', (string) ($student['dia_chi'] ?? ($student['dia_chi_thuong_tru'] ?? ''))));
    $parts = array_pad($parts, 4, '');
    $addressSource = [
        'address_line1' => $parts[0],
        'address_line2' => $parts[1],
        'address_line3' => $parts[2],
        'address_province' => $parts[3],
    ];
}

$value = static function (string $key) use ($student, $profileFormData, $dateValue, $addressSource): string {
    $map = [
        'ho_ten' => 'full_name',
        'ngay_sinh' => 'birth_date',
        'gioi_tinh' => 'gender',
        'email' => 'email',
        'so_dien_thoai' => 'phone',
    ];

    if (isset($addressSource[$key])) {
        return htmlspecialchars((string) $addressSource[$key], ENT_QUOTES, 'UTF-8');
    }

    if ($key === 'ngay_sinh') {
        return htmlspecialchars($dateValue, ENT_QUOTES, 'UTF-8');
    }

    $formKey = $map[$key] ?? $key;
    $fallback = match ($key) {
        'ho_ten' => $student['ho_ten'] ?? '',
        'gioi_tinh' => $student['gioi_tinh'] ?? '',
        'email' => $student['email'] ?? '',
        'so_dien_thoai' => $student['so_dien_thoai'] ?? '',
        default => $student[$key] ?? '',
    };

    return htmlspecialchars((string) ($profileFormData[$formKey] ?? $fallback), ENT_QUOTES, 'UTF-8');
};

$error = static function (string $key) use ($profileErrors): string {
    $message = (string) ($profileErrors[$key] ?? '');
    $class = $message === '' ? 'modal-error is-empty' : 'modal-error';
    return '<span class="' . $class . '">' . ($message !== '' ? htmlspecialchars($message, ENT_QUOTES, 'UTF-8') : '&nbsp;') . '</span>';
};

$avatar = (string) ($student['avatar_url'] ?? ($student['avatar'] ?? ''));
$hasAvatar = trim($avatar) !== '';
$studentName = trim((string) ($student['ho_ten'] ?? ''));
$avatarInitial = $studentName !== ''
    ? (function_exists('mb_substr') ? mb_substr($studentName, 0, 1, 'UTF-8') : substr($studentName, 0, 1))
    : '?';
?>

<style>
    #editProfileModal .modal-card { width: min(780px, 100%); max-width: 920px; max-height: 80vh; display: flex; flex-direction: column; }
    #editProfileModal .modal-header { padding: 10px 16px; min-height: 48px; }
    #editProfileModal .modal-title { line-height: 1.2; }
    #editProfileModal .modal-close { width: 34px; height: 34px; font-size: 31px; line-height: 1; color: var(--primary-dark); background: transparent; }
    #editProfileModal .modal-close:hover { color: var(--primary-dark); background: #eef4ff; }
    #editProfileModal .modal-body { max-height: calc(80vh - 116px); overflow-y: auto; padding: 14px; display: grid; gap: 12px; }
    #editProfileModal .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 0 12px; align-items: start; }
    #editProfileModal .modal-field { display: grid; gap: 4px; min-width: 0; }
    #editProfileModal .modal-field.full { grid-column: 1 / -1; }
    #editProfileModal .modal-field input,
    #editProfileModal .modal-field select {
        width: 100%;
        height: 40px;
        box-sizing: border-box;
        padding: 10px 12px;
        border: 1px solid var(--primary-border);
        border-radius: 10px;
        background: #fbfdff;
        color: #1f2937;
        font-size: 13px;
        outline: none;
    }
    #editProfileModal .modal-field input:focus,
    #editProfileModal .modal-field select:focus {
        border-color: var(--primary-border-strong);
        box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.12);
        background: #ffffff;
    }
    #editProfileModal .modal-field input[readonly],
    #editProfileModal .modal-field input:disabled {
        cursor: not-allowed;
        color: #64748b;
        background: #eef2f7;
    }
    #editProfileModal select.form-select {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231047a1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 16px;
        padding-right: 34px;
    }
    #editProfileModal .modal-error { min-height: 20px; color: #dc2626; font-size: 12px; font-weight: 600; line-height: 1.25; overflow-wrap: anywhere; }
    #editProfileModal .modal-error.is-empty { visibility: hidden; }
    #editProfileModal .modal-actions { display: flex; gap: 12px; justify-content: flex-end; padding: 14px 18px 22px; border-top: 1px solid var(--primary-soft); }
    #editProfileModal .action-btn {
        min-height: 38px;
        padding: 8px 20px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.2;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s;
        white-space: nowrap;
    }
    #editProfileModal .action-btn.cancel-btn {
        color: #dc2626 !important;
        background: #ffffff !important;
        border-color: #e5e7eb !important;
    }
    #editProfileModal .action-btn.cancel-btn:hover {
        color: #dc2626 !important;
        background: #e5e7eb !important;
        border-color: #cbd5e1 !important;
    }
    #editProfileModal .action-btn.save-change-btn {
        color: #ffffff !important;
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%) !important;
        border-color: #16a34a !important;
    }
    #editProfileModal .action-btn.save-change-btn:hover {
        color: #ffffff !important;
        background: linear-gradient(180deg, #15803d 0%, #166534 100%) !important;
        border-color: #15803d !important;
    }
    #editProfileModal .avatar-section { display: flex; align-items: flex-end; gap: 10px; padding: 6px 2px 18px; }
    #editProfileModal .avatar-preview { width: 104px; height: 139px; aspect-ratio: 3 / 4; border-radius: 8px; overflow: hidden; flex: 0 0 auto; border: 2px solid #e8ecf3; background: linear-gradient(135deg, #e2e8f0 0%, #dbeafe 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 22px rgba(15, 23, 42, 0.10); }
    #editProfileModal .avatar-preview img { width: 100%; height: 100%; object-fit: cover; display: block; }
    #editProfileModal .avatar-preview span { font-size: 30px; font-weight: 800; color: var(--primary); }
    #editProfileModal .avatar-meta { display: grid; gap: 5px; align-self: flex-end; margin-bottom: 2px; transform: translateY(18px); }
    #editProfileModal .avatar-upload-btn { display: inline-flex; align-items: center; gap: 6px; width: fit-content; border: 1px solid var(--primary-border); background: #fbfdff; color: var(--primary-dark); padding: 7px 10px; border-radius: 10px; cursor: pointer; font-size: 12px; font-weight: 700; line-height: 1.1; transition: 0.2s ease; }
    #editProfileModal .avatar-upload-btn i { font-size: 13px; }
    #editProfileModal .avatar-upload-btn:hover { background: var(--primary-soft); }
    #editProfileModal .avatar-hint { font-size: 12px; color: #64748b; }
    #editProfileModal .avatar-meta .modal-error.is-empty { display: none; }
    #editProfileModal .modal-input-wrap { position: relative; }
    #editProfileModal .modal-input-wrap input { padding-right: 44px; }
    #editProfileModal .modal-icon { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); border: none; background: transparent; color: var(--primary-dark); cursor: pointer; padding: 6px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }
    #editProfileModal .modal-icon:hover { background: var(--primary-soft); }
</style>

<div class="modal-overlay modal" id="editProfileModal" aria-hidden="true">
    <div class="modal-card modal-content" role="dialog" aria-modal="true" aria-labelledby="editProfileTitle">
        <div class="modal-header">
            <span class="modal-title" id="editProfileTitle">Cập nhật thông tin</span>
            <button class="modal-close btn btn-light" type="button" aria-label="Đóng" onclick="closeEditProfileModal()">×</button>
        </div>

        <form id="editProfileForm" class="modal-body" method="post" action="/KhoaLuan/public/student.php?action=update_profile" enctype="multipart/form-data" novalidate>
            <div class="avatar-section">
                <div class="avatar-preview" id="avatarPreview">
                    <?php if ($hasAvatar): ?>
                        <img id="avatarPreviewImg" src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar" />
                        <span id="avatarPreviewText" style="display:none"><?= htmlspecialchars($avatarInitial, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php else: ?>
                        <span id="avatarPreviewText"><?= htmlspecialchars($avatarInitial, ENT_QUOTES, 'UTF-8') ?></span>
                        <img id="avatarPreviewImg" src="" alt="Avatar" style="display:none" />
                    <?php endif; ?>
                </div>
                <div class="avatar-meta">
                    <button type="button" class="avatar-upload-btn btn btn-outline-secondary" onclick="document.getElementById('avatarInput').click()">
                        <i class="fa-solid fa-upload" aria-hidden="true"></i>
                        Tải ảnh lên
                    </button>
                    <div class="avatar-hint">JPG, JPEG, PNG hoặc WEBP. Tối đa 5MB.</div>
                    <input class="form-control" id="avatarInput" name="avatar" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" style="display:none" />
                    <?= $error('avatar') ?>
                    <span class="modal-error is-empty" id="avatarClientError">&nbsp;</span>
                </div>
            </div>

            <div class="form-grid">
                <div class="modal-field">
                    <label class="form-label" for="profile_ho_ten">Họ và tên <span class="req">*</span></label>
                    <input class="form-control" id="profile_ho_ten" name="ho_ten" value="<?= $value('ho_ten') ?>" />
                    <?= $error('ho_ten') ?>
                </div>
                <div class="modal-field">
                    <label class="form-label" for="profile_mssv">MSSV</label>
                    <input class="form-control" id="profile_mssv" value="<?= htmlspecialchars((string) ($student['mssv'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" readonly disabled />
                    <span class="modal-error is-empty">&nbsp;</span>
                </div>
                <div class="modal-field">
                    <label class="form-label" for="profile_lop">Lớp</label>
                    <input class="form-control" id="profile_lop" value="<?= htmlspecialchars((string) ($student['lop_hoc'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" readonly disabled />
                    <span class="modal-error is-empty">&nbsp;</span>
                </div>
                <div class="modal-field">
                    <label class="form-label" for="profile_nganh">Ngành</label>
                    <input class="form-control" id="profile_nganh" value="<?= htmlspecialchars((string) ($student['nganh'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" readonly disabled />
                    <span class="modal-error is-empty">&nbsp;</span>
                </div>
                <div class="modal-field">
                    <label class="form-label" for="ngaySinh">Ngày sinh <span class="req">*</span></label>
                    <div class="modal-input-wrap">
                        <input class="form-control" id="ngaySinh" name="ngay_sinh" type="date" value="<?= $value('ngay_sinh') ?>" />
                        <button type="button" class="modal-icon btn btn-light" aria-label="Chọn ngày" onclick="openDatePicker('ngaySinh')">
                            <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                        </button>
                    </div>
                    <?= $error('ngay_sinh') ?>
                </div>
                <div class="modal-field">
                    <label class="form-label" for="profile_gioi_tinh">Giới tính <span class="req">*</span></label>
                    <select class="form-select" id="profile_gioi_tinh" name="gioi_tinh">
                        <option value="">-- Chọn giới tính --</option>
                        <option value="Nam" <?= $value('gioi_tinh') === 'Nam' ? 'selected' : '' ?>>Nam</option>
                        <option value="Nữ" <?= $value('gioi_tinh') === 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                    </select>
                    <?= $error('gioi_tinh') ?>
                </div>
                <div class="modal-field">
                    <label class="form-label" for="profile_email">Email <span class="req">*</span></label>
                    <input class="form-control" id="profile_email" name="email" type="email" value="<?= $value('email') ?>" />
                    <?= $error('email') ?>
                </div>
                <div class="modal-field">
                    <label class="form-label" for="profile_phone">Số điện thoại <span class="req">*</span></label>
                    <input class="form-control" id="profile_phone" name="so_dien_thoai" type="tel" value="<?= $value('so_dien_thoai') ?>" />
                    <?= $error('so_dien_thoai') ?>
                </div>
                <div class="modal-field">
                    <label class="form-label" for="profile_address_line1">Số nhà <span class="req">*</span></label>
                    <input class="form-control" id="profile_address_line1" name="address_line1" value="<?= $value('address_line1') ?>" />
                    <?= $error('address_line1') ?>
                </div>
                <div class="modal-field">
                    <label class="form-label" for="profile_address_province">Tỉnh / Thành phố <span class="req">*</span></label>
                    <input class="form-control" id="profile_address_province" name="address_province" value="<?= $value('address_province') ?>" />
                    <?= $error('address_province') ?>
                </div>
                <div class="modal-field">
                    <label class="form-label" for="profile_address_line2">Đường / Ấp / Khóm <span class="req">*</span></label>
                    <input class="form-control" id="profile_address_line2" name="address_line2" value="<?= $value('address_line2') ?>" />
                    <?= $error('address_line2') ?>
                </div>
                <div class="modal-field">
                    <label class="form-label" for="profile_address_line3">Xã / Phường <span class="req">*</span></label>
                    <input class="form-control" id="profile_address_line3" name="address_line3" value="<?= $value('address_line3') ?>" />
                    <?= $error('address_line3') ?>
                </div>
            </div>
        </form>

        <div class="modal-actions">
            <button class="action-btn cancel-btn btn btn-outline-secondary" type="button" onclick="closeEditProfileModal()">Hủy</button>
            <button class="action-btn save-change-btn btn btn-primary" type="submit" form="editProfileForm">Cập nhật</button>
        </div>
    </div>
</div>

<?php include __DIR__ . '/avatar_crop_modal.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script src="/KhoaLuan/public/js/student-avatar-crop.js"></script>
<script>
    function openEditProfileModal() {
        var modal = document.getElementById('editProfileModal');
        if (!modal) return;
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeEditProfileModal() {
        var modal = document.getElementById('editProfileModal');
        if (!modal) return;
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }

    function openDatePicker(id) {
        var input = document.getElementById(id);
        if (!input) return;
        if (typeof input.showPicker === 'function') {
            try { input.showPicker(); return; } catch (e) {}
        }
        try { input.focus(); input.click(); } catch (e) { input.focus(); }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') closeEditProfileModal();
    });

    (function() {
        <?php if ($openEditProfileModal): ?>
        openEditProfileModal();
        <?php endif; ?>
    })();
</script>
