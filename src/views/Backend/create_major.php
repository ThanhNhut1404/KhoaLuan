<?php
    $departments = $departments ?? [
        'Khoa CNTT',
        'Khoa Điện tử',
        'Khoa Cơ khí',
        'Khoa Hóa học'
    ];

    $formData = $formData ?? [];
    $errors = $errors ?? [];
?>

<div class="create-major-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">TẠO NGÀNH HỌC</h2>
        </div>

        <div class="panel-body card-body">
            <form id="createMajorForm" method="POST" action="?page=create_major">
                <div class="form-grid">
                    <!-- Mã ngành -->
                    <div class="form-field">
                        <label class="field-label form-label" for="major_code">
                            Mã ngành <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="major_code"
                            name="major_code"
                            class="field-input form-control"
                            placeholder="Ví dụ: CNTT01"
                            value="<?= isset($formData['major_code']) ? htmlspecialchars($formData['major_code']) : '' ?>"
                            required
                        />
                        <?php if(isset($errors['major_code'])): ?>
                            <span class="field-error"><?= $errors['major_code'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Tên ngành -->
                    <div class="form-field">
                        <label class="field-label form-label" for="major_name">
                            Tên ngành <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="major_name"
                            name="major_name"
                            class="field-input form-control"
                            placeholder="Nhập tên ngành học"
                            value="<?= isset($formData['major_name']) ? htmlspecialchars($formData['major_name']) : '' ?>"
                            required
                        />
                        <?php if(isset($errors['major_name'])): ?>
                            <span class="field-error"><?= $errors['major_name'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Số tín chỉ -->
                    <div class="form-field">
                        <label class="field-label form-label" for="total_credits">
                            Số tín chỉ <span class="required">*</span>
                        </label>
                        <input
                            type="number"
                            id="total_credits"
                            name="total_credits"
                            class="field-input form-control"
                            placeholder="Ví dụ: 120"
                            value="<?= isset($formData['total_credits']) ? htmlspecialchars($formData['total_credits']) : '' ?>"
                            min="1"
                            required
                        />
                        <?php if(isset($errors['total_credits'])): ?>
                            <span class="field-error"><?= $errors['total_credits'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Chọn Khoa trực thuộc -->
                    <div class="form-field">
                        <label class="field-label form-label" for="department">
                            Khoa trực thuộc <span class="required">*</span>
                        </label>
                        <select id="department" name="department" class="field-input form-select" required>
                            <option value="">-- Chọn khoa quản lý --</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= htmlspecialchars($dept) ?>" <?= (isset($formData['department']) && $formData['department'] === $dept) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dept) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['department'])): ?>
                            <span class="field-error"><?= $errors['department'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Mô tả -->
                    <div class="form-field" style="grid-column: 1 / -1;">
                        <label class="field-label form-label" for="description">
                            Mô tả
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            class="field-input textarea-input form-control"
                            placeholder="Nhập mô tả ngành học"
                            rows="4"
                        ><?= isset($formData['description']) ? htmlspecialchars($formData['description']) : '' ?></textarea>
                        <?php if(isset($errors['description'])): ?>
                            <span class="field-error"><?= $errors['description'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-field">
                        <label class="field-label form-label" for="status">
                            Trạng thái <span class="required">*</span>
                        </label>
                        <select id="status" name="status" class="field-input form-select" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="active" <?= (isset($formData['status']) && $formData['status'] === 'active') ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="inactive" <?= (isset($formData['status']) && $formData['status'] === 'inactive') ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                        <?php if(isset($errors['status'])): ?>
                            <span class="field-error"><?= $errors['status'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="?page=list_major" class="action-btn secondary btn btn-outline-secondary">
                        Hủy
                    </a>
                    <button type="submit" class="action-btn primary btn btn-primary">
                        Tạo ngành học
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
    .create-major-page {
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
        display: flex;
        align-items: center;
        padding: 8px 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 13px;
        color: #1f2937;
        font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .field-input:focus {
        outline: none;
        border-color: #0f2a5a;
        box-shadow: 0 0 0 3px rgba(15, 42, 90, 0.08);
        background: #ffffff;
    }

    textarea.field-input {
        resize: vertical;
        padding: 10px;
        display: block;
        font-family: inherit;
        line-height: 1.5;
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
    document.getElementById('createMajorForm')?.addEventListener('submit', function(e) {
        const majorCode = document.getElementById('major_code').value.trim();
        const majorName = document.getElementById('major_name').value.trim();
        const totalCredits = document.getElementById('total_credits').value;

        if (!majorCode || !majorName) {
            e.preventDefault();
            alert('Vui lòng điền tất cả các trường bắt buộc!');
            return false;
        }

        if (totalCredits < 1) {
            e.preventDefault();
            alert('Số tín chỉ phải lớn hơn 0!');
            return false;
        }
    });
</script>
