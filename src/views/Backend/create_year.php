<div class="create-year-page">
    <div class="page-panel">
        <div class="panel-header">
            <h2 class="panel-title">TẠO NIÊN KHÓA</h2>
        </div>

        <div class="panel-body">
            <form id="createYearForm" method="POST" action="?page=create_year">
                <div class="form-grid">
                    <!-- Tên niên khóa -->
                    <div class="form-field">
                        <label class="field-label" for="year_name">
                            Tên niên khóa <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="year_name" 
                            name="year_name" 
                            class="field-input" 
                            placeholder="Ví dụ: 2024 - 2025"
                            value="<?= isset($formData['year_name']) ? htmlspecialchars($formData['year_name']) : '' ?>"
                            required 
                        />
                        <small class="field-hint">&nbsp;</small>
                        <?php if(isset($errors['year_name'])): ?>
                            <span class="field-error"><?= $errors['year_name'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Ngày bắt đầu -->
                    <div class="form-field">
                        <label class="field-label" for="start_date">
                            Ngày bắt đầu <span class="required">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="start_date" 
                            name="start_date" 
                            class="field-input" 
                            value="<?= isset($formData['start_date']) ? htmlspecialchars($formData['start_date']) : '' ?>"
                            required 
                        />
                        <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                        <?php if(isset($errors['start_date'])): ?>
                            <span class="field-error"><?= $errors['start_date'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-field">
                        <label class="field-label" for="status">
                            Trạng thái <span class="required">*</span>
                        </label>
                        <select id="status" name="status" class="field-input" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="upcoming" <?= (isset($formData['status']) && $formData['status'] === 'upcoming') ? 'selected' : '' ?>>Sắp tới</option>
                            <option value="active" <?= (isset($formData['status']) && $formData['status'] === 'active') ? 'selected' : '' ?>>Đang diễn ra</option>
                            <option value="completed" <?= (isset($formData['status']) && $formData['status'] === 'completed') ? 'selected' : '' ?>>Đã hoàn thành</option>
                        </select>
                        <?php if(isset($errors['status'])): ?>
                            <span class="field-error"><?= $errors['status'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Ngày kết thúc -->
                    <div class="form-field">
                        <label class="field-label" for="end_date">
                            Ngày kết thúc <span class="required">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="end_date" 
                            name="end_date" 
                            class="field-input" 
                            value="<?= isset($formData['end_date']) ? htmlspecialchars($formData['end_date']) : '' ?>"
                            required 
                        />
                        <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                        <?php if(isset($errors['end_date'])): ?>
                            <span class="field-error"><?= $errors['end_date'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="?page=list_year" class="action-btn secondary">
                        Hủy
                    </a>
                    <button type="submit" class="action-btn primary">
                        Tạo niên khóa
                    </button>
                </div>

                <?php if(isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?>">
                        <?= $_SESSION['message'] ?>
                    </div>
                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<style>
    .create-year-page {
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
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 24px;
        align-items: start;
    }

    .form-field {
        display: grid;
        gap: 6px;
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
    }

    .field-error {
        font-size: 12px;
        color: #dc2626;
        display: block;
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

    .alert {
        margin-top: 16px;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
        border: 1px solid;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border-color: #6ee7b7;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
    }

    .alert-info {
        background: #dbeafe;
        color: #1e3a8a;
        border-color: #93c5fd;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
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
    document.getElementById('createYearForm')?.addEventListener('submit', function(e) {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(document.getElementById('end_date').value);

        if (startDate >= endDate) {
            e.preventDefault();
            alert('Ngày bắt đầu phải trước ngày kết thúc!');
            return false;
        }
    });
</script>
