<?php
    $departments = $departments ?? [];
    $formData = $formData ?? [];
    $errors = $errors ?? [];
    $statusOptions = $statusOptions ?? [
        ['value' => 'Hoạt động', 'label' => 'Hoạt động'],
        ['value' => 'Ngừng tuyển sinh', 'label' => 'Ngừng tuyển sinh'],
    ];
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
?>

<div class="edit-major-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">CHỈNH SỬA NGÀNH HỌC</h2>
        </div>

        <div class="panel-body card-body">
            <form id="editMajorForm" method="POST" action="?page=edit_major&id=<?= (int) $id ?>">
                <div class="form-grid">
                    <div class="form-row row">
                        <div class="form-field col-12 col-md-6">
                            <label class="field-label form-label" for="major_code">
                                Tên viết tắt
                            </label>
                            <input
                                type="text"
                                id="major_code"
                                name="major_code"
                                class="field-input form-control"
                                placeholder="Ví dụ: CNTT"
                                value="<?= htmlspecialchars($formData['major_code'] ?? '') ?>"
                            />
                            <span class="field-error<?= isset($errors['major_code']) ? '' : ' is-empty' ?>"><?= isset($errors['major_code']) ? htmlspecialchars($errors['major_code']) : '&nbsp;' ?></span>
                        </div>

                        <div class="form-field col-12 col-md-6">
                            <label class="field-label form-label" for="major_name">
                                Tên ngành <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="major_name"
                                name="major_name"
                                class="field-input form-control"
                                placeholder="Nhập tên ngành học"
                                value="<?= htmlspecialchars($formData['major_name'] ?? '') ?>"
                                required
                            />
                            <span class="field-error<?= isset($errors['major_name']) ? '' : ' is-empty' ?>"><?= isset($errors['major_name']) ? htmlspecialchars($errors['major_name']) : '&nbsp;' ?></span>
                        </div>
                    </div>

                    <div class="form-row row">
                        <div class="form-field col-12 col-md-6">
                            <label class="field-label form-label" for="department">
                                Khoa trực thuộc <span class="required">*</span>
                            </label>
                            <select id="department" name="department" class="field-input form-select" required>
                                <option value="">-- Chọn khoa quản lý --</option>
                                <?php foreach ($departments as $dept): ?>
                                    <?php
                                        $departmentValue = is_array($dept) ? (string) ($dept['MA_KHOA'] ?? $dept['ma'] ?? '') : (string) $dept;
                                        $departmentLabel = is_array($dept) ? (string) ($dept['TEN_KHOA'] ?? $dept['ten'] ?? '') : (string) $dept;
                                    ?>
                                    <option value="<?= htmlspecialchars($departmentValue) ?>" <?= (isset($formData['department']) && (string) $formData['department'] === $departmentValue) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($departmentLabel) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-error<?= isset($errors['department']) ? '' : ' is-empty' ?>"><?= isset($errors['department']) ? htmlspecialchars($errors['department']) : '&nbsp;' ?></span>
                        </div>

                        <div class="form-field col-12 col-md-6">
                            <label class="field-label form-label" for="status">
                                Trạng thái <span class="required">*</span>
                            </label>
                            <select id="status" name="status" class="field-input form-select" required>
                                <option value="">-- Chọn trạng thái --</option>
                                <?php foreach ($statusOptions as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']) ?>" <?= (isset($formData['status']) && $formData['status'] === $option['value']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($option['label']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-error<?= isset($errors['status']) ? '' : ' is-empty' ?>"><?= isset($errors['status']) ? htmlspecialchars($errors['status']) : '&nbsp;' ?></span>
                        </div>
                    </div>

                    <div class="form-row row">
                        <div class="form-field col-12 col-md-6">
                            <label class="field-label form-label" for="description">
                                Mô tả
                            </label>
                            <textarea
                                id="description"
                                name="description"
                                class="field-input textarea-input form-control"
                                placeholder="Nhập mô tả ngành học"
                                rows="4"
                            ><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                            <span class="field-error<?= isset($errors['description']) ? '' : ' is-empty' ?>"><?= isset($errors['description']) ? htmlspecialchars($errors['description']) : '&nbsp;' ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="?page=list_major" class="action-btn secondary cancel-btn btn btn-outline-secondary">
                        Hủy
                    </a>
                    <button type="submit" class="action-btn primary save-change-btn btn btn-primary">
                        Cập nhật ngành học
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .edit-major-page {
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
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 18px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        column-gap: 20px;
        row-gap: 12px;
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

    .field-error {
        font-size: 12px;
        color: #dc2626;
        display: block;
        line-height: 1.25;
        min-height: 14px;
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

    @media (max-width: 768px) {
        .edit-major-page {
            padding: 16px;
        }

        .form-grid {
            gap: 12px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 12px;
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
    document.getElementById('editMajorForm')?.addEventListener('submit', function(e) {
        const majorName = document.getElementById('major_name').value.trim();
        const department = document.getElementById('department').value.trim();
        const status = document.getElementById('status').value.trim();

        if (!majorName || !department || !status) {
            e.preventDefault();
            alert('Vui lòng điền tất cả các trường bắt buộc!');
            return false;
        }
    });
</script>
