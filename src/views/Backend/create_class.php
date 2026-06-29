<?php
    $academic_years = $academic_years ?? [];
    $departments = $departments ?? [];
    $majors = $majors ?? [];
    $formData = $formData ?? [];
    $errors = $errors ?? [];
    $statusOptions = $statusOptions ?? [
        ['value' => 'Hoạt động', 'label' => 'Hoạt động'],
        ['value' => 'Không hoạt động', 'label' => 'Không hoạt động'],
        ['value' => 'Ngừng tuyển sinh', 'label' => 'Ngừng tuyển sinh'],
    ];

    $selectedDepartment = (string) ($formData['department'] ?? '');
    $selectedMajor = (string) ($formData['major'] ?? '');
    $majorPayload = array_map(static function (array $major): array {
        return [
            'id' => (string) ($major['id'] ?? ''),
            'department_id' => (string) ($major['department_id'] ?? ''),
            'name' => (string) ($major['name'] ?? ''),
        ];
    }, $majors);
?>

<div class="create-class-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">TẠO LỚP HỌC</h2>
        </div>

        <div class="panel-body card-body">
            <form id="createClassForm" method="POST" action="?page=create_class" novalidate>
                <div class="form-grid">
                    <div class="form-field">
                        <label class="field-label form-label" for="class_code">
                            Mã lớp học <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="class_code"
                            name="class_code"
                            class="field-input form-control"
                            placeholder="Ví dụ: CNTT47A"
                            maxlength="50"
                            value="<?= htmlspecialchars($formData['class_code'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            aria-invalid="<?= isset($errors['class_code']) ? 'true' : 'false' ?>"
                        />
                        <span class="field-error<?= isset($errors['class_code']) ? '' : ' is-empty' ?>"><?= isset($errors['class_code']) ? htmlspecialchars($errors['class_code'], ENT_QUOTES, 'UTF-8') : '&nbsp;' ?></span>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="class_name">
                            Tên lớp <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="class_name"
                            name="class_name"
                            class="field-input form-control"
                            placeholder="Nhập tên lớp"
                            maxlength="100"
                            value="<?= htmlspecialchars($formData['class_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            aria-invalid="<?= isset($errors['class_name']) ? 'true' : 'false' ?>"
                        />
                        <span class="field-error<?= isset($errors['class_name']) ? '' : ' is-empty' ?>"><?= isset($errors['class_name']) ? htmlspecialchars($errors['class_name'], ENT_QUOTES, 'UTF-8') : '&nbsp;' ?></span>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="academic_year">
                            Niên khóa <span class="required">*</span>
                        </label>
                        <select id="academic_year" name="academic_year" class="field-input form-select" aria-invalid="<?= isset($errors['academic_year']) ? 'true' : 'false' ?>">
                            <option value="">-- Chọn niên khóa --</option>
                            <?php foreach ($academic_years as $year): ?>
                                <?php
                                    $yearId = (string) ($year['id'] ?? '');
                                    $yearName = (string) ($year['name'] ?? '');
                                ?>
                                <option value="<?= htmlspecialchars($yearId, ENT_QUOTES, 'UTF-8') ?>" <?= (isset($formData['academic_year']) && (string) $formData['academic_year'] === $yearId) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($yearName, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-error<?= isset($errors['academic_year']) ? '' : ' is-empty' ?>"><?= isset($errors['academic_year']) ? htmlspecialchars($errors['academic_year'], ENT_QUOTES, 'UTF-8') : '&nbsp;' ?></span>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="department">
                            Khoa <span class="required">*</span>
                        </label>
                        <select id="department" name="department" class="field-input form-select" aria-invalid="<?= isset($errors['department']) ? 'true' : 'false' ?>">
                            <option value="">-- Chọn khoa --</option>
                            <?php foreach ($departments as $department): ?>
                                <?php
                                    $departmentId = (string) ($department['id'] ?? '');
                                    $departmentName = (string) ($department['name'] ?? '');
                                ?>
                                <option value="<?= htmlspecialchars($departmentId, ENT_QUOTES, 'UTF-8') ?>" <?= $selectedDepartment === $departmentId ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($departmentName, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-error<?= isset($errors['department']) ? '' : ' is-empty' ?>"><?= isset($errors['department']) ? htmlspecialchars($errors['department'], ENT_QUOTES, 'UTF-8') : '&nbsp;' ?></span>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="major">
                            Chuyên ngành <span class="required">*</span>
                        </label>
                        <select id="major" name="major" class="field-input form-select" aria-invalid="<?= isset($errors['major']) ? 'true' : 'false' ?>" <?= $selectedDepartment === '' ? 'disabled' : '' ?>>
                            <option value="">-- Chọn chuyên ngành --</option>
                            <?php foreach ($majors as $major): ?>
                                <?php
                                    $majorId = (string) ($major['id'] ?? '');
                                    $majorDepartmentId = (string) ($major['department_id'] ?? '');
                                    $majorName = (string) ($major['name'] ?? '');
                                    if ($selectedDepartment === '' || $majorDepartmentId !== $selectedDepartment) {
                                        continue;
                                    }
                                ?>
                                <option value="<?= htmlspecialchars($majorId, ENT_QUOTES, 'UTF-8') ?>" <?= $selectedMajor === $majorId ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($majorName, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-error<?= isset($errors['major']) ? '' : ' is-empty' ?>"><?= isset($errors['major']) ? htmlspecialchars($errors['major'], ENT_QUOTES, 'UTF-8') : '&nbsp;' ?></span>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="capacity">
                            Sĩ số <span class="required">*</span>
                        </label>
                        <input
                            type="number"
                            id="capacity"
                            name="capacity"
                            class="field-input form-control"
                            placeholder="Nhập sĩ số tối đa"
                            min="1"
                            max="200"
                            step="1"
                            value="<?= htmlspecialchars($formData['capacity'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            aria-invalid="<?= isset($errors['capacity']) ? 'true' : 'false' ?>"
                        />
                        <span class="field-error<?= isset($errors['capacity']) ? '' : ' is-empty' ?>"><?= isset($errors['capacity']) ? htmlspecialchars($errors['capacity'], ENT_QUOTES, 'UTF-8') : '&nbsp;' ?></span>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="status">
                            Trạng thái <span class="required">*</span>
                        </label>
                        <select id="status" name="status" class="field-input form-select" aria-invalid="<?= isset($errors['status']) ? 'true' : 'false' ?>">
                            <option value="">-- Chọn trạng thái --</option>
                            <?php foreach ($statusOptions as $option): ?>
                                <option value="<?= htmlspecialchars($option['value'], ENT_QUOTES, 'UTF-8') ?>" <?= (isset($formData['status']) && $formData['status'] === $option['value']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-error<?= isset($errors['status']) ? '' : ' is-empty' ?>"><?= isset($errors['status']) ? htmlspecialchars($errors['status'], ENT_QUOTES, 'UTF-8') : '&nbsp;' ?></span>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="notes">
                            Ghi chú
                        </label>
                        <textarea
                            id="notes"
                            name="notes"
                            class="field-input textarea-input form-control"
                            placeholder="Nhập ghi chú"
                        ><?= htmlspecialchars($formData['notes'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        <span class="field-error<?= isset($errors['notes']) ? '' : ' is-empty' ?>"><?= isset($errors['notes']) ? htmlspecialchars($errors['notes'], ENT_QUOTES, 'UTF-8') : '&nbsp;' ?></span>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="?page=list_class" class="action-btn secondary cancel-btn btn btn-outline-secondary">
                        Hủy
                    </a>
                    <button type="submit" class="action-btn primary btn btn-primary">
                        Tạo lớp học
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .create-class-page {
        display: grid;
        gap: 0;
        padding: 24px;
    }

    .page-panel {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .panel-header {
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .panel-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f2a5a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .panel-body {
        padding: 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0 14px;
        margin-bottom: 8px;
    }

    .form-field {
        display: grid;
        gap: 3px;
    }

    .field-label {
        font-size: 12px;
        font-weight: 700;
        color: #0f2a5a;
        text-transform: none;
        letter-spacing: 0.4px;
        display: block;
    }

    .required {
        color: #dc2626;
        font-weight: 700;
    }

    .field-input {
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 13px;
        color: #1f2937;
        font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
        height: 40px;
        box-sizing: border-box;
    }

    .field-input:focus {
        outline: none;
        border-color: #0f2a5a;
        box-shadow: 0 0 0 3px rgba(15, 42, 90, 0.08);
        background: #ffffff;
    }

    .field-input:disabled {
        cursor: not-allowed;
        color: #9ca3af;
        background-color: #eef2f7;
    }

    select.field-input {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231f2937' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 16px;
        padding-right: 32px;
    }

    .textarea-input {
        min-height: 100px;
        resize: vertical;
        padding: 12px;
        height: auto;
    }

    .field-error {
        font-size: 12px;
        color: #dc2626;
        display: block;
        line-height: 1.2;
        min-height: 18px;
        overflow-wrap: anywhere;
    }

    .field-error.is-empty {
        visibility: hidden;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 16px;
        border-top: 1px solid #e8ecf3;
    }

    .action-btn {
        padding: 8px 20px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #0f2a5a;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .action-btn:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .action-btn.primary {
        background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%);
        border-color: #0f2a5a;
        color: #ffffff;
        font-weight: 700;
    }

    .action-btn.primary:hover {
        background: linear-gradient(180deg, #0d2449 0%, #091a3d 100%);
        border-color: #0a1838;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    (function() {
        const majors = <?= json_encode($majorPayload, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        const departmentSelect = document.getElementById('department');
        const majorSelect = document.getElementById('major');
        const selectedMajor = <?= json_encode($selectedMajor, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

        if (!departmentSelect || !majorSelect) {
            return;
        }

        function resetMajorOptions(nextSelectedMajor) {
            const departmentId = departmentSelect.value;
            majorSelect.innerHTML = '';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = '-- Chọn chuyên ngành --';
            majorSelect.appendChild(placeholder);

            if (!departmentId) {
                majorSelect.value = '';
                majorSelect.disabled = true;
                return;
            }

            majors
                .filter(function(major) {
                    return major.department_id === departmentId;
                })
                .forEach(function(major) {
                    const option = document.createElement('option');
                    option.value = major.id;
                    option.textContent = major.name;
                    majorSelect.appendChild(option);
                });

            majorSelect.disabled = false;
            majorSelect.value = nextSelectedMajor || '';
        }

        departmentSelect.addEventListener('change', function() {
            resetMajorOptions('');
        });

        resetMajorOptions(selectedMajor);
    })();
</script>
