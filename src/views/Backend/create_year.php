<?php
    $formData = $formData ?? [];
    $errors = $errors ?? [];
    $statusOptions = $statusOptions ?? [];
    if (empty($statusOptions)) {
        $statusOptions = [
            ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
            ['value' => 'Đang diễn ra', 'label' => 'Đang diễn ra'],
            ['value' => 'Đã kết thúc', 'label' => 'Đã kết thúc'],
        ];
    }
    $formData['status'] = $formData['status'] ?? $statusOptions[0]['value'];
?>

<div class="create-year-page container-fluid">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">TẠO NIÊN KHÓA</h2>
        </div>

        <div class="panel-body card-body">
            <form id="createYearForm" method="POST" action="?page=create_year">
                <div class="row g-3 g-md-4 year-form-row">
                    <!-- Tên niên khóa -->
                    <div class="col-12 col-md-6 year-field">
                        <label class="field-label form-label" for="year_name">
                            Tên niên khóa <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="year_name" 
                            name="year_name" 
                            class="field-input form-control<?= isset($errors['year_name']) ? ' is-invalid' : '' ?>" 
                            placeholder="Ví dụ: 2024 - 2025"
                            value="<?= htmlspecialchars($formData['year_name'] ?? '') ?>"
                            required 
                        />
                        <small class="field-hint">&nbsp;</small>
                        <?php if(isset($errors['year_name'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['year_name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Ngày bắt đầu -->
                    <div class="col-12 col-md-6 year-field">
                        <label class="field-label form-label" for="start_date">
                            Ngày bắt đầu <span class="required">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="start_date" 
                            name="start_date" 
                            class="field-input form-control<?= isset($errors['start_date']) ? ' is-invalid' : '' ?>" 
                            value="<?= htmlspecialchars($formData['start_date'] ?? '') ?>"
                            required 
                        />
                        <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                        <?php if(isset($errors['start_date'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['start_date']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-12 col-md-6 year-field">
                        <label class="field-label form-label" for="status">
                            Trạng thái <span class="required">*</span>
                        </label>
                        <select id="status" name="status" class="field-input form-select<?= isset($errors['status']) ? ' is-invalid' : '' ?>" required>
                            <?php foreach ($statusOptions as $option): ?>
                                <option value="<?= htmlspecialchars($option['value']) ?>" <?= (($formData['status'] ?? '') === $option['value']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($option['label']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="field-hint">&nbsp;</small>
                        <?php if(isset($errors['status'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['status']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Ngày kết thúc -->
                    <div class="col-12 col-md-6 year-field">
                        <label class="field-label form-label" for="end_date">
                            Ngày kết thúc <span class="required">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="end_date" 
                            name="end_date" 
                            class="field-input form-control<?= isset($errors['end_date']) ? ' is-invalid' : '' ?>" 
                            value="<?= htmlspecialchars($formData['end_date'] ?? '') ?>"
                            required 
                        />
                        <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                        <?php if(isset($errors['end_date'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['end_date']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="?page=list_year" class="action-btn secondary btn btn-outline-secondary">
                        Hủy
                    </a>
                    <button type="submit" class="action-btn primary btn btn-primary">
                        Tạo niên khóa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .create-year-page {
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

    .year-form-row {
        margin-bottom: 24px;
        align-items: start;
    }

    .year-field {
        min-width: 0;
    }

    .field-label {
        font-size: 12px;
        font-weight: 700;
        color: #0f2a5a;
        text-transform: none;
        letter-spacing: 0.4px;
        display: block;
        margin-bottom: 6px;
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
        width: 100%;
    }

    .field-input:focus {
        outline: none;
        border-color: #0f2a5a;
        box-shadow: 0 0 0 3px rgba(15, 42, 90, 0.08);
        background: #ffffff;
    }

    .field-input.is-invalid {
        border-color: #dc2626;
        background-color: #fff7f7;
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

    .field-hint {
        font-size: 11px;
        color: #9ca3af;
        display: block;
        margin-top: 6px;
    }

    .invalid-feedback {
        font-size: 12px;
        margin-top: 6px;
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
        .create-year-page {
            padding: 16px;
        }

        .panel-body {
            padding: 16px;
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
