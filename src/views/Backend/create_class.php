<?php
    $academic_years = $academic_years ?? [];
    $departments = $departments ?? [];
    $majors = $majors ?? [];
    $advisors = $advisors ?? [];
    $formData = $formData ?? [];
    $errors = $errors ?? [];
?>

<div class="create-class-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">TẠO LỚP HỌC</h2>
        </div>

        <div class="panel-body card-body">
            <form id="createClassForm" method="POST" action="?page=create_class">
                <div class="form-grid">
                    <!-- Mã lớp học -->
                    <div class="form-field">
                        <label class="field-label form-label" for="class_code">
                            Mã lớp học <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="class_code"
                            name="class_code"
                            class="field-input form-control"
                            placeholder="Nhập mã lớp học"
                            value="<?= isset($formData['class_code']) ? htmlspecialchars($formData['class_code']) : '' ?>"
                            required
                        />
                        <?php if(isset($errors['class_code'])): ?>
                            <span class="field-error"><?= $errors['class_code'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Tên lớp -->
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
                            value="<?= isset($formData['class_name']) ? htmlspecialchars($formData['class_name']) : '' ?>"
                            required
                        />
                        <?php if(isset($errors['class_name'])): ?>
                            <span class="field-error"><?= $errors['class_name'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Niên khóa -->
                    <div class="form-field">
                        <label class="field-label form-label" for="academic_year">
                            Niên khóa <span class="required">*</span>
                        </label>
                        <select id="academic_year" name="academic_year" class="field-input form-select" required>
                            <option value="">-- Chọn niên khóa --</option>
                            <?php foreach ($academic_years as $year): ?>
                                <option value="<?= $year['id'] ?>" <?= (isset($formData['academic_year']) && $formData['academic_year'] == $year['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($year['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['academic_year'])): ?>
                            <span class="field-error"><?= $errors['academic_year'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Khoa -->
                    <div class="form-field">
                        <label class="field-label form-label" for="department">
                            Khoa <span class="required">*</span>
                        </label>
                        <select id="department" name="department" class="field-input form-select" required>
                            <option value="">-- Chọn khoa --</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?= $department['id'] ?>" <?= (isset($formData['department']) && $formData['department'] == $department['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($department['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['department'])): ?>
                            <span class="field-error"><?= $errors['department'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Chuyên ngành -->
                    <div class="form-field">
                        <label class="field-label form-label" for="major">
                            Chuyên ngành <span class="required">*</span>
                        </label>
                        <select id="major" name="major" class="field-input form-select" required>
                            <option value="">-- Chọn chuyên ngành --</option>
                            <?php foreach ($majors as $major): ?>
                                <option value="<?= $major['id'] ?>" <?= (isset($formData['major']) && $formData['major'] == $major['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($major['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['major'])): ?>
                            <span class="field-error"><?= $errors['major'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Cố vấn -->
                    <div class="form-field">
                        <label class="field-label form-label" for="advisor">
                            Cố vấn <span class="required">*</span>
                        </label>
                        <select id="advisor" name="advisor" class="field-input form-select" required>
                            <option value="">-- Chọn cố vấn --</option>
                            <?php foreach ($advisors as $advisor): ?>
                                <option value="<?= $advisor['id'] ?>" <?= (isset($formData['advisor']) && $formData['advisor'] == $advisor['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($advisor['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['advisor'])): ?>
                            <span class="field-error"><?= $errors['advisor'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Số lượng -->
                    <div class="form-field">
                        <label class="field-label form-label" for="capacity">
                            Số lượng
                        </label>
                        <input
                            type="number"
                            id="capacity"
                            name="capacity"
                            class="field-input form-control"
                            placeholder="Nhập số lượng"
                            value="<?= isset($formData['capacity']) ? htmlspecialchars($formData['capacity']) : '' ?>"
                            min="0"
                        />
                        <?php if(isset($errors['capacity'])): ?>
                            <span class="field-error"><?= $errors['capacity'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-field">
                        <label class="field-label form-label" for="status">
                            Trạng thái <span class="required">*</span>
                        </label>
                        <select id="status" name="status" class="field-input form-select" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="upcoming" <?= (isset($formData['status']) && $formData['status'] === 'upcoming') ? 'selected' : '' ?>>Sắp tới</option>
                            <option value="active" <?= (isset($formData['status']) && $formData['status'] === 'active') ? 'selected' : '' ?>>Đang diễn ra</option>
                            <option value="completed" <?= (isset($formData['status']) && $formData['status'] === 'completed') ? 'selected' : '' ?>>Đã hoàn thành</option>
                        </select>
                        <?php if(isset($errors['status'])): ?>
                            <span class="field-error"><?= $errors['status'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Ghi chú -->
                    <div class="form-field">
                        <label class="field-label form-label" for="notes">
                            Ghi chú
                        </label>
                        <textarea
                            id="notes"
                            name="notes"
                            class="field-input textarea-input form-control"
                            placeholder="Nhập ghi chú"
                        ><?= isset($formData['notes']) ? htmlspecialchars($formData['notes']) : '' ?></textarea>
                        <?php if(isset($errors['notes'])): ?>
                            <span class="field-error"><?= $errors['notes'] ?></span>
                        <?php endif; ?>
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
