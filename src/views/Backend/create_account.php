<?php
$createAccountOptions = $createAccountOptions ?? [
    'roles' => [],
    'classes' => [],
    'departments' => [],
    'students' => [],
    'unions' => [],
];
$formData = $formData ?? [];
$errors = $errors ?? [];

$value = static fn(string $key): string => htmlspecialchars($formData[$key] ?? '');
$selected = static fn(string $key, string $value): string => (string)($formData[$key] ?? '') === $value ? 'selected' : '';
$error = static fn(string $key): string => !empty($errors[$key]) ? '<span class="field-error">' . htmlspecialchars($errors[$key]) . '</span>' : '';
$roleLabel = static function (string $roleName): string {
    $labels = [
        'DOAN_TRUONG' => 'Đoàn trường',
        'KHOA' => 'Khoa',
        'BO_MON' => 'Bộ môn',
        'DOAN_KHOA' => 'Đoàn khoa',
        'LIEN_CHI' => 'Liên chi / CLB',
        'CO_VAN_HOC_TAP' => 'Cố vấn học tập',
        'CAN_BO_LOP' => 'Cán bộ lớp',
        'SINH_VIEN' => 'Sinh viên',
        'GIANG_VIEN' => 'Giảng viên',
    ];

    return $labels[$roleName] ?? str_replace('_', ' ', $roleName);
};
?>

<div class="create-account-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">CẤP TÀI KHOẢN</h2>
        </div>

        <div class="panel-body card-body">
            <form id="createAccountForm" method="post" action="/KhoaLuan/public/admin.php?page=create_account">
                <div class="form-section">
                    <div class="section-title">Thông tin tài khoản</div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="field-label form-label" for="role_id">Vai trò <span class="required">*</span></label>
                            <select id="role_id" name="role_id" class="field-input form-select" required>
                                <option value="">-- Chọn vai trò --</option>
                                <?php foreach ($createAccountOptions['roles'] as $role): ?>
                                    <option
                                        value="<?= htmlspecialchars($role['MA_VAI_TRO']) ?>"
                                        data-role="<?= htmlspecialchars($role['TEN_VAI_TRO']) ?>"
                                        <?= $selected('role_id', (string)$role['MA_VAI_TRO']) ?>
                                    >
                                        <?= htmlspecialchars($roleLabel($role['TEN_VAI_TRO'])) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= $error('role_id') ?>
                        </div>

                        <div class="form-field">
                            <label class="field-label form-label" for="username" id="usernameLabel">Tên đăng nhập <span class="required">*</span></label>
                            <input id="username" name="username" class="field-input form-control" type="text" value="<?= $value('username') ?>" placeholder="Nhập tên đăng nhập" />
                            <?= $error('username') ?>
                        </div>

                        <div class="form-field">
                            <label class="field-label form-label" for="password">Mật khẩu <span class="required">*</span></label>
                            <input id="password" name="password" class="field-input form-control" type="password" placeholder="Nhập mật khẩu" />
                            <?= $error('password') ?>
                        </div>

                        <div class="form-field">
                            <label class="field-label form-label" for="confirm_password">Xác nhận mật khẩu <span class="required">*</span></label>
                            <input id="confirm_password" name="confirm_password" class="field-input form-control" type="password" placeholder="Nhập lại mật khẩu" />
                            <?= $error('confirm_password') ?>
                        </div>
                    </div>
                </div>

                <div class="form-section profile-section" id="profileSection">
                    <div class="section-title">Thông tin hồ sơ</div>
                    <div class="form-grid">
                        <div class="form-field role-field" data-roles="SINH_VIEN GIANG_VIEN CO_VAN_HOC_TAP BO_MON KHOA">
                            <label class="field-label form-label" for="full_name">Họ và tên <span class="required">*</span></label>
                            <input id="full_name" name="full_name" class="field-input form-control" type="text" value="<?= $value('full_name') ?>" placeholder="Nhập họ và tên" />
                            <?= $error('full_name') ?>
                        </div>

                        <div class="form-field role-field" data-roles="SINH_VIEN GIANG_VIEN CO_VAN_HOC_TAP BO_MON KHOA">
                            <label class="field-label form-label" for="gender">Giới tính <span class="required">*</span></label>
                            <select id="gender" name="gender" class="field-input form-select">
                                <option value="">-- Chọn giới tính --</option>
                                <option value="Nam" <?= $selected('gender', 'Nam') ?>>Nam</option>
                                <option value="Nữ" <?= $selected('gender', 'Nữ') ?>>Nữ</option>
                            </select>
                            <?= $error('gender') ?>
                        </div>

                        <div class="form-field role-field" data-roles="SINH_VIEN GIANG_VIEN CO_VAN_HOC_TAP BO_MON KHOA">
                            <label class="field-label form-label" for="birth_date">Ngày sinh <span class="required">*</span></label>
                            <input id="birth_date" name="birth_date" class="field-input form-control" type="date" value="<?= $value('birth_date') ?>" />
                            <?= $error('birth_date') ?>
                        </div>

                        <div class="form-field role-field" data-roles="SINH_VIEN GIANG_VIEN CO_VAN_HOC_TAP BO_MON KHOA DOAN_KHOA DOAN_TRUONG">
                            <label class="field-label form-label" for="email">Email <span class="required">*</span></label>
                            <input id="email" name="email" class="field-input form-control" type="email" value="<?= $value('email') ?>" placeholder="Nhập email" />
                            <?= $error('email') ?>
                        </div>

                        <div class="form-field role-field" data-roles="SINH_VIEN GIANG_VIEN CO_VAN_HOC_TAP BO_MON KHOA">
                            <label class="field-label form-label" for="phone">Số điện thoại <span class="required">*</span></label>
                            <input id="phone" name="phone" class="field-input form-control" type="tel" value="<?= $value('phone') ?>" placeholder="Nhập số điện thoại" />
                            <?= $error('phone') ?>
                        </div>

                        <div class="form-field role-field" data-roles="SINH_VIEN">
                            <label class="field-label form-label" for="address">Địa chỉ <span class="required">*</span></label>
                            <input id="address" name="address" class="field-input form-control" type="text" value="<?= $value('address') ?>" placeholder="Nhập địa chỉ" />
                            <?= $error('address') ?>
                        </div>

                        <div class="form-field role-field" data-roles="SINH_VIEN CAN_BO_LOP">
                            <label class="field-label form-label" for="class_id">Lớp học <span class="required">*</span></label>
                            <select id="class_id" name="class_id" class="field-input form-select">
                                <option value="">-- Chọn lớp học --</option>
                                <?php foreach ($createAccountOptions['classes'] as $class): ?>
                                    <option value="<?= htmlspecialchars($class['MA_LOP']) ?>" <?= $selected('class_id', (string)$class['MA_LOP']) ?>>
                                        <?= htmlspecialchars($class['TEN_LOP']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= $error('class_id') ?>
                        </div>

                        <div class="form-field role-field" data-roles="GIANG_VIEN CO_VAN_HOC_TAP BO_MON KHOA DOAN_KHOA">
                            <label class="field-label form-label" for="department_id">Khoa/Bộ môn <span class="required">*</span></label>
                            <select id="department_id" name="department_id" class="field-input form-select">
                                <option value="">-- Chọn khoa/bộ môn --</option>
                                <?php foreach ($createAccountOptions['departments'] as $department): ?>
                                    <option value="<?= htmlspecialchars($department['MA_KHOA']) ?>" <?= $selected('department_id', (string)$department['MA_KHOA']) ?>>
                                        <?= htmlspecialchars($department['TEN_KHOA']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= $error('department_id') ?>
                        </div>

                        <div class="form-field role-field" data-roles="CAN_BO_LOP">
                            <label class="field-label form-label" for="student_id">Sinh viên <span class="required">*</span></label>
                            <select id="student_id" name="student_id" class="field-input form-select">
                                <option value="">-- Chọn sinh viên --</option>
                                <?php foreach ($createAccountOptions['students'] as $student): ?>
                                    <option value="<?= htmlspecialchars($student['MA_SV']) ?>" <?= $selected('student_id', (string)$student['MA_SV']) ?>>
                                        <?= htmlspecialchars($student['MSSV'] . ' - ' . $student['TEN_DANG_NHAP']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= $error('student_id') ?>
                        </div>

                        <div class="form-field role-field" data-roles="CAN_BO_LOP">
                            <label class="field-label form-label" for="class_position">Chức vụ cán bộ lớp <span class="required">*</span></label>
                            <input id="class_position" name="class_position" class="field-input form-control" type="text" value="<?= $value('class_position') ?>" placeholder="Nhập chức vụ" />
                            <?= $error('class_position') ?>
                        </div>

                        <div class="form-field role-field" data-roles="DOAN_KHOA">
                            <label class="field-label form-label" for="union_faculty_name">Tên Đoàn khoa <span class="required">*</span></label>
                            <input id="union_faculty_name" name="union_faculty_name" class="field-input form-control" type="text" value="<?= $value('union_faculty_name') ?>" placeholder="Nhập tên Đoàn khoa" />
                            <?= $error('union_faculty_name') ?>
                        </div>

                        <div class="form-field role-field" data-roles="LIEN_CHI">
                            <label class="field-label form-label" for="club_name">Tên Liên chi / CLB <span class="required">*</span></label>
                            <input id="club_name" name="club_name" class="field-input form-control" type="text" value="<?= $value('club_name') ?>" placeholder="Nhập tên Liên chi / CLB" />
                            <?= $error('club_name') ?>
                        </div>

                        <div class="form-field role-field" data-roles="LIEN_CHI">
                            <label class="field-label form-label" for="union_id">Đoàn trường quản lý <span class="required">*</span></label>
                            <select id="union_id" name="union_id" class="field-input form-select">
                                <option value="">-- Chọn Đoàn trường --</option>
                                <?php foreach ($createAccountOptions['unions'] as $union): ?>
                                    <option value="<?= htmlspecialchars($union['MA_DOAN_TRUONG']) ?>" <?= $selected('union_id', (string)$union['MA_DOAN_TRUONG']) ?>>
                                        <?= htmlspecialchars($union['TEN_DOAN_HOI']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= $error('union_id') ?>
                        </div>

                        <div class="form-field role-field" data-roles="DOAN_TRUONG">
                            <label class="field-label form-label" for="union_name">Tên Đoàn trường <span class="required">*</span></label>
                            <input id="union_name" name="union_name" class="field-input form-control" type="text" value="<?= $value('union_name') ?>" placeholder="Nhập tên Đoàn trường" />
                            <?= $error('union_name') ?>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="/KhoaLuan/public/admin.php?page=list_accounts" class="action-btn secondary cancel-btn btn btn-outline-secondary">Hủy</a>
                    <button type="submit" class="action-btn primary btn btn-primary">Cấp tài khoản</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .create-account-page { display: grid; gap: 0; padding: 24px; }
    .page-panel { background: #ffffff; border: 1px solid #e8ecf3; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden; }
    .panel-header { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .panel-title { font-size: 14px; font-weight: 700; color: #0f2a5a; margin: 0; }
    .panel-body { padding: 20px; }
    .form-section { display: grid; gap: 14px; margin-bottom: 22px; }
    .profile-section { display: none; }
    .section-title { font-size: 13px; font-weight: 800; color: #0f2a5a; text-transform: uppercase; letter-spacing: 0.04em; }
    .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px 20px; }
    .form-field { display: grid; gap: 6px; }
    .role-field { display: none; }
    .field-label { font-size: 12px; font-weight: 700; color: #0f2a5a; display: block; }
    .required { color: #dc2626; font-weight: 700; }
    .field-input { padding: 10px; border-radius: 10px; border: 1px solid #e5e7eb; background: #f9fafb; font-size: 13px; color: #1f2937; font-family: inherit; height: 40px; box-sizing: border-box; }
    .field-input:focus { outline: none; border-color: #0f2a5a; box-shadow: 0 0 0 3px rgba(15,42,90,0.08); background: #ffffff; }
    select.field-input { cursor: pointer; }
    .field-error { color: #dc2626; font-size: 12px; font-weight: 600; }
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 16px; border-top: 1px solid #e8ecf3; }
    .action-btn { padding: 8px 20px; border-radius: 10px; border: 1px solid #e5e7eb; background: #ffffff; color: #0f2a5a; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; }
    .action-btn:hover { background: #f3f4f6; border-color: #d1d5db; }
    .action-btn.primary { background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%); border-color: #0f2a5a; color: #ffffff; }
    .action-btn.primary:hover { background: linear-gradient(180deg, #0d2449 0%, #091a3d 100%); border-color: #0a1838; }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; gap: 16px; }
        .form-actions { flex-direction: column-reverse; }
        .action-btn { width: 100%; }
    }
</style>

<script>
    (function() {
        var roleSelect = document.getElementById('role_id');
        var profileSection = document.getElementById('profileSection');
        var roleFields = document.querySelectorAll('.role-field');
        var usernameLabel = document.getElementById('usernameLabel');
        var usernameInput = document.getElementById('username');

        function selectedRoleName() {
            var option = roleSelect.options[roleSelect.selectedIndex];
            return option ? option.getAttribute('data-role') || '' : '';
        }

        function updateProfileFields() {
            var role = selectedRoleName();
            var hasRole = role !== '';

            profileSection.style.display = hasRole ? 'grid' : 'none';
            usernameLabel.innerHTML = role === 'SINH_VIEN' ? 'MSSV <span class="required">*</span>' : 'Tên đăng nhập <span class="required">*</span>';
            usernameInput.placeholder = role === 'SINH_VIEN' ? 'Nhập MSSV' : 'Nhập tên đăng nhập';

            roleFields.forEach(function(field) {
                var roles = (field.getAttribute('data-roles') || '').split(/\s+/);
                var visible = roles.indexOf(role) !== -1;
                field.style.display = visible ? 'grid' : 'none';
            });
        }

        if (roleSelect) {
            roleSelect.addEventListener('change', updateProfileFields);
            updateProfileFields();
        }
    })();
</script>
