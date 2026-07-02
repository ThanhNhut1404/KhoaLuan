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
$selectedDepartmentId = (string) ($formData['department_id'] ?? '');
$selectedMajorId = (string) ($formData['major_id'] ?? '');
$selectedClassId = (string) ($formData['class_id'] ?? '');
$studentDependencyData = [
    'majors' => array_map(static fn(array $nganh): array => [
        'id' => (string) ($nganh['MA_NGANH'] ?? ''),
        'department_id' => (string) ($nganh['MA_KHOA'] ?? ''),
        'name' => (string) ($nganh['TEN_NGANH'] ?? ''),
    ], $listNganh),
    'classes' => array_map(static fn(array $lop): array => [
        'id' => (string) ($lop['MA_LOP'] ?? ''),
        'department_id' => (string) ($lop['MA_KHOA'] ?? ''),
        'major_id' => (string) ($lop['MA_NGANH'] ?? ''),
        'year_id' => (string) ($lop['MA_NIEN_KHOA'] ?? ''),
        'name' => (string) ($lop['TEN_LOP'] ?? ''),
    ], $listLop),
];
?>

<div class="student-form-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">CẤP TÀI KHOẢN SINH VIÊN</h2>
        </div>

        <div class="panel-body card-body">
            <form id="createStudentForm" method="post" action="/KhoaLuan/public/admin.php?page=create_student" novalidate>
                <div class="form-grid">
                    <div class="form-field">
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
                        <select id="major_id" name="major_id" class="field-input form-select" data-selected-value="<?= htmlspecialchars($selectedMajorId, ENT_QUOTES, 'UTF-8') ?>" <?= $selectedDepartmentId === '' ? 'disabled' : '' ?>>
                            <option value=""><?= $selectedDepartmentId === '' ? 'Vui lòng chọn khoa/bộ môn trước' : '-- Chọn ngành học --' ?></option>
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
                        <select id="class_id" name="class_id" class="field-input form-select" data-selected-value="<?= htmlspecialchars($selectedClassId, ENT_QUOTES, 'UTF-8') ?>" <?= $selectedMajorId === '' ? 'disabled' : '' ?>>
                            <option value=""><?= $selectedMajorId === '' ? 'Vui lòng chọn ngành học trước' : '-- Chọn lớp học --' ?></option>
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

                    <div class="form-field">
                        <label class="field-label form-label" for="address_line1">Số nhà <span class="required">*</span></label>
                        <input id="address_line1" name="address_line1" class="field-input form-control" type="text" value="<?= $value('address_line1') ?>" placeholder="Số nhà" />
                        <?= $error('address_line1') ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="address_province">Tỉnh / Thành phố <span class="required">*</span></label>
                        <input id="address_province" name="address_province" class="field-input form-control" type="text" value="<?= $value('address_province') ?>" placeholder="Tỉnh / Thành phố" />
                        <?= $error('address_province') ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="address_line2">Đường / Ấp / Khóm <span class="required">*</span></label>
                        <input id="address_line2" name="address_line2" class="field-input form-control" type="text" value="<?= $value('address_line2') ?>" placeholder="Đường / Ấp / Khóm" />
                        <?= $error('address_line2') ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="address_line3">Xã / Phường <span class="required">*</span></label>
                        <input id="address_line3" name="address_line3" class="field-input form-control" type="text" value="<?= $value('address_line3') ?>" placeholder="Xã / Phường" />
                        <?= $error('address_line3') ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="status">Trạng thái học tập</label>
                        <select id="status" name="status" class="field-input form-select">
                            <?php foreach ($statusOptions as $option): ?>
                                <option value="<?= htmlspecialchars($option['value']) ?>" <?= $selected('status', $option['value']) ?>><?= htmlspecialchars($option['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-error is-empty">&nbsp;</span>
                    </div>
                </div>

                <div class="form-footer">
                    <p class="hint">MSSV được tự sinh theo Mã ngành + Mã niên khóa + STT; mật khẩu mặc định là <strong>#Tdu1234</strong>.</p>

                    <div class="form-actions">
                        <a href="/KhoaLuan/public/admin.php?page=list_students" class="action-btn secondary cancel-btn btn btn-outline-secondary">Hủy</a>
                        <button type="submit" class="action-btn primary btn btn-primary">Tạo sinh viên</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .student-form-page { display: grid; gap: 0; padding: 24px; }
    .page-panel { background: #ffffff; border: 1px solid #e8ecf3; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06); overflow: hidden; }
    .panel-header { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .panel-title { font-size: 14px; font-weight: 700; color: #0f2a5a; margin: 0; display: flex; align-items: center; gap: 8px; }
    .panel-body { padding: 20px; }
    .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 14px; margin-bottom: 8px; }
    .form-field { display: grid; gap: 3px; min-width: 0; }
    .full-width { grid-column: 1 / -1; }
    .field-label { font-size: 12px; font-weight: 700; color: #0f2a5a; text-transform: none; letter-spacing: 0.4px; display: block; }
    .required { color: #dc2626; font-weight: 700; }
    .field-input { padding: 10px; border: 1px solid #e5e7eb; border-radius: 10px; background: #f9fafb; font-size: 13px; color: #1f2937; font-family: inherit; height: 40px; box-sizing: border-box; width: 100%; transition: border-color 0.2s, box-shadow 0.2s; }
    .field-input:focus { outline: none; border-color: #0f2a5a; box-shadow: 0 0 0 3px rgba(15, 42, 90, 0.08); background: #ffffff; }
    .field-input:disabled { cursor: not-allowed; color: #64748b; background-color: #eef2f7; }
    select.field-input { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231f2937' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 10px center; background-size: 16px; padding-right: 32px; }
    .field-error { color: #dc2626; display: block; font-size: 12px; font-weight: 600; line-height: 1.2; min-height: 18px; overflow-wrap: anywhere; }
    .field-error.is-empty { visibility: hidden; }
    .form-footer { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding-top: 16px; border-top: 1px solid #e8ecf3; }
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; flex-shrink: 0; }
    .action-btn { padding: 8px 20px; border-radius: 10px; border: 1px solid #e5e7eb; background: #ffffff; color: #0f2a5a; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.2s; white-space: nowrap; }
    .action-btn:hover { background: #f3f4f6; border-color: #d1d5db; }
    .action-btn.primary { background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%); border-color: #0f2a5a; color: #ffffff; font-weight: 700; }
    .action-btn.primary:hover { background: linear-gradient(180deg, #0d2449 0%, #091a3d 100%); border-color: #0a1838; }
    .hint { margin: 0; color: #475569; font-size: 13px; }
    @media (max-width: 768px) {
        .student-form-page { padding: 16px; }
        .panel-body { padding: 16px; }
        .form-grid { grid-template-columns: 1fr; gap: 0; }
        .form-footer { flex-direction: column; align-items: stretch; }
        .form-actions { flex-direction: column-reverse; }
        .action-btn { width: 100%; justify-content: center; }
    }
</style>

<script type="application/json" id="studentDependencyData">
<?= json_encode($studentDependencyData, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>
</script>

<script>
    (function() {
        const departmentSelect = document.getElementById('department_id');
        const majorSelect = document.getElementById('major_id');
        const classSelect = document.getElementById('class_id');
        const dataNode = document.getElementById('studentDependencyData');

        if (!departmentSelect || !majorSelect || !classSelect || !dataNode) {
            return;
        }

        let dependencyData = { majors: [], classes: [] };
        try {
            dependencyData = JSON.parse(dataNode.textContent || '{}');
        } catch (error) {
            dependencyData = { majors: [], classes: [] };
        }

        const majors = Array.isArray(dependencyData.majors) ? dependencyData.majors : [];
        const classes = Array.isArray(dependencyData.classes) ? dependencyData.classes : [];
        const initialMajorId = majorSelect.dataset.selectedValue || '';
        const initialClassId = classSelect.dataset.selectedValue || '';

        function replaceOptions(select, placeholder, options, selectedValue) {
            select.innerHTML = '';

            const placeholderOption = document.createElement('option');
            placeholderOption.value = '';
            placeholderOption.textContent = placeholder;
            select.appendChild(placeholderOption);

            options.forEach(function(optionData) {
                const option = document.createElement('option');
                option.value = String(optionData.id || '');
                option.textContent = String(optionData.name || '');
                if (option.value === selectedValue) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        }

        function populateMajors(selectedValue) {
            const departmentId = departmentSelect.value;

            if (departmentId === '') {
                replaceOptions(majorSelect, 'Vui lòng chọn khoa/bộ môn trước', [], '');
                majorSelect.disabled = true;
                populateClasses('');
                return;
            }

            const filteredMajors = majors.filter(function(major) {
                return String(major.department_id || '') === departmentId;
            });

            replaceOptions(majorSelect, '-- Chọn ngành học --', filteredMajors, selectedValue);
            majorSelect.disabled = false;

            if (!filteredMajors.some(function(major) { return String(major.id || '') === majorSelect.value; })) {
                majorSelect.value = '';
            }

            populateClasses(majorSelect.value === selectedValue ? initialClassId : '');
        }

        function populateClasses(selectedValue) {
            const departmentId = departmentSelect.value;
            const majorId = majorSelect.value;

            if (majorId === '') {
                replaceOptions(classSelect, 'Vui lòng chọn ngành học trước', [], '');
                classSelect.disabled = true;
                return;
            }

            const filteredClasses = classes.filter(function(classItem) {
                return String(classItem.department_id || '') === departmentId
                    && String(classItem.major_id || '') === majorId;
            });

            replaceOptions(classSelect, '-- Chọn lớp học --', filteredClasses, selectedValue);
            classSelect.disabled = false;

            if (!filteredClasses.some(function(classItem) { return String(classItem.id || '') === classSelect.value; })) {
                classSelect.value = '';
            }
        }

        departmentSelect.addEventListener('change', function() {
            majorSelect.dataset.selectedValue = '';
            classSelect.dataset.selectedValue = '';
            populateMajors('');
        });

        majorSelect.addEventListener('change', function() {
            classSelect.dataset.selectedValue = '';
            populateClasses('');
        });

        populateMajors(initialMajorId);
    })();
</script>
