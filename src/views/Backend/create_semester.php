<?php
    $formData = $formData ?? [];
    $errors = $errors ?? [];
    $academic_years = $academic_years ?? [];
    $status_options = $status_options ?? [];
?>

<div class="create-semester-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">TẠO HỌC KỲ</h2>
        </div>

        <div class="panel-body card-body">
            <form id="createSemesterForm" method="POST" action="?page=create_semester" novalidate>
                <div class="form-grid">
                    <div class="form-row row">
                        <div class="form-field col-12 col-md-6">
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

                        <div class="form-field col-12 col-md-6">
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

                    <div class="form-row row">
                        <div class="form-field col-12 col-md-6">
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

                        <div class="form-field col-12 col-md-6">
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

                    <div class="form-row row">
                        <div class="form-field col-12 col-md-6">
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
                </div>

                <div class="form-actions">
                    <a href="?page=list_semester" class="action-btn secondary cancel-btn btn btn-outline-secondary">
                        Hủy
                    </a>
                    <button type="submit" class="action-btn primary btn btn-primary">
                        Tạo học kỳ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .create-semester-page {
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
    }

    .panel-body {
        padding: 20px;
    }

    .form-grid {
        display: grid;
        gap: 0;
        margin-bottom: 8px;
    }

    .form-row {
        display: grid;
        gap: 14px;
    }

    .form-field {
        display: grid;
        gap: 3px;
    }

    .field-label {
        font-size: 12px;
        font-weight: 700;
        color: #0f2a5a;
        display: block;
    }

    .required {
        color: #dc2626;
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

    select.field-input {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231f2937' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 16px;
        padding-right: 32px;
    }

    .field-error {
        font-size: 12px;
        color: #dc2626;
        display: block;
        line-height: 1.2;
        min-height: 18px;
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
    }

    .action-btn.primary:hover {
        background: linear-gradient(180deg, #1a3a6b 0%, #152d52 100%);
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
