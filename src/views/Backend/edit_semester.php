<?php
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $formData = $formData ?? [];
    $errors = $errors ?? [];
    $academic_years = $academic_years ?? [];
    $status_options = $status_options ?? [];
    $isEdit = $isEdit ?? true;
?>

<div class="edit-semester-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">CHỈNH SỬA HỌC KỲ</h2>
        </div>

        <div class="panel-body card-body">
            <form id="editSemesterForm" method="POST" action="?page=edit_semester&id=<?= $id ?>" novalidate>
                <div class="form-grid">
                    <div class="form-row">
                        <div class="form-field">
                            <label class="field-label form-label" for="academic_year">
                                Niên khóa <span class="required">*</span>
                            </label>
                            <select
                                id="academic_year"
                                name="academic_year"
                                class="field-input form-select"
                                required
                            >
                                <option value="">-- Chọn niên khóa --</option>
                                <?php foreach ($academic_years as $year): ?>
                                    <option
                                        value="<?= htmlspecialchars($year['id']) ?>"
                                        <?= (isset($formData['academic_year']) && $formData['academic_year'] == $year['id']) ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($year['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-error<?= isset($errors['academic_year']) ? '' : ' is-empty' ?>"><?= isset($errors['academic_year']) ? htmlspecialchars($errors['academic_year']) : '&nbsp;' ?></span>
                        </div>

                        <div class="form-field">
                            <label class="field-label form-label" for="semester_name">
                                Tên học kỳ <span class="required">*</span>
                            </label>
                            <input
                                id="semester_name"
                                name="semester_name"
                                class="field-input form-control"
                                placeholder="Ví dụ: Học kỳ 1"
                                value="<?= htmlspecialchars($formData['semester_name'] ?? '') ?>"
                                required
                            />
                            <span class="field-error<?= isset($errors['semester_name']) ? '' : ' is-empty' ?>"><?= isset($errors['semester_name']) ? htmlspecialchars($errors['semester_name']) : '&nbsp;' ?></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label class="field-label form-label" for="start_date">
                                Ngày bắt đầu <span class="required">*</span>
                            </label>
                            <input
                                id="start_date"
                                name="start_date"
                                type="date"
                                class="field-input form-control"
                                value="<?= htmlspecialchars($formData['start_date'] ?? '') ?>"
                                required
                            />
                            <span class="field-error<?= isset($errors['start_date']) ? '' : ' is-empty' ?>"><?= isset($errors['start_date']) ? htmlspecialchars($errors['start_date']) : '&nbsp;' ?></span>
                        </div>

                        <div class="form-field">
                            <label class="field-label form-label" for="end_date">
                                Ngày kết thúc <span class="required">*</span>
                            </label>
                            <input
                                id="end_date"
                                name="end_date"
                                type="date"
                                class="field-input form-control"
                                value="<?= htmlspecialchars($formData['end_date'] ?? '') ?>"
                                required
                            />
                            <span class="field-error<?= isset($errors['end_date']) ? '' : ' is-empty' ?>"><?= isset($errors['end_date']) ? htmlspecialchars($errors['end_date']) : '&nbsp;' ?></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label class="field-label form-label" for="status">
                                Trạng thái <span class="required">*</span>
                            </label>
                            <select
                                id="status"
                                name="status"
                                class="field-input form-select"
                                required
                            >
                                <option value="">-- Chọn trạng thái --</option>
                                <?php foreach ($status_options as $option): ?>
                                    <option
                                        value="<?= htmlspecialchars($option['value']) ?>"
                                        <?= (isset($formData['status']) && $formData['status'] === $option['value']) ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($option['label']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-error<?= isset($errors['status']) ? '' : ' is-empty' ?>"><?= isset($errors['status']) ? htmlspecialchars($errors['status']) : '&nbsp;' ?></span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="?page=list_semester" class="action-btn">
                            Hủy
                        </a>
                        <button type="submit" class="action-btn primary">
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .edit-semester-page {
        display: grid;
        gap: 0;
        padding: 24px;
    }

    .page-header {
        margin-bottom: 0;
    }

    .page-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f2a5a;
        text-transform: none;
        letter-spacing: 0.6px;
        margin: 0 0 4px 0;
    }

    .page-subtitle {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
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

    .panel-title svg {
        width: 18px;
        height: 18px;
        color: #0f2a5a;
    }

    .panel-body {
        padding: 20px;
    }

    .form-grid {
        display: flex;
        flex-direction: column;
        gap: 0;
        margin-bottom: 8px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        column-gap: 14px;
        row-gap: 0;
        margin: 0;
    }

    .form-row > .form-field {
        width: auto;
        max-width: none;
        padding: 0;
    }

    .form-field {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
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
        display: flex;
        align-items: center;
        padding: 8px 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 13px;
        color: #1f2937;
        font-family: inherit;
        height: 40px;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .field-input:focus {
        outline: none;
        border-color: #0f2a5a;
        box-shadow: 0 0 0 3px rgba(15, 42, 90, 0.08);
        background: #ffffff;
    }

    .field-hint {
        font-size: 11px;
        color: #9ca3af;
        display: block;
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
        .edit-semester-page {
            padding: 16px;
        }

        .form-grid {
            gap: 0;
        }

        .form-row {
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
    document.getElementById('editSemesterForm')?.addEventListener('submit', function(e){
        var form = this;
        var submitButton = form.querySelector('button[type="submit"]');
        var fields = {
            academic_year: document.getElementById('academic_year'),
            semester_name: document.getElementById('semester_name'),
            start_date: document.getElementById('start_date'),
            end_date: document.getElementById('end_date'),
            status: document.getElementById('status')
        };
        var errors = {};

        function setFieldError(name, message) {
            var input = fields[name];
            var error = input?.closest('.form-field')?.querySelector('.field-error');
            if (!error) return;

            error.textContent = message || '\u00a0';
            error.classList.toggle('is-empty', !message);
        }

        Object.keys(fields).forEach(function(name) {
            setFieldError(name, '');
        });

        if (!fields.academic_year.value.trim()) {
            errors.academic_year = 'Vui lòng chọn niên khóa.';
        }

        if (!fields.semester_name.value.trim()) {
            errors.semester_name = 'Vui lòng nhập tên học kỳ.';
        }

        if (!fields.start_date.value.trim()) {
            errors.start_date = 'Vui lòng chọn ngày bắt đầu.';
        }

        if (!fields.end_date.value.trim()) {
            errors.end_date = 'Vui lòng chọn ngày kết thúc.';
        }

        if (fields.start_date.value && fields.end_date.value && fields.start_date.value >= fields.end_date.value) {
            errors.end_date = 'Ngày kết thúc phải sau ngày bắt đầu.';
        }

        if (!fields.status.value.trim()) {
            errors.status = 'Vui lòng chọn trạng thái.';
        }

        Object.keys(errors).forEach(function(name) {
            setFieldError(name, errors[name]);
        });

        if (Object.keys(errors).length > 0) {
            e.preventDefault();
            return false;
        }

        if (submitButton) {
            submitButton.disabled = true;
        }
    });
</script>
