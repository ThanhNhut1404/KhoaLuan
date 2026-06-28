<?php
    $formData = $formData ?? [];
    $errors = $errors ?? [];
    $isEdit = $isEdit ?? false;
?>

<div class="create-khoa-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title"><?= isset($isEdit) && $isEdit ? 'CHỈNH SỬA KHOA/BỘ MÔN' : 'TẠO KHOA/BỘ MÔN' ?></h2>
        </div>

        <div class="panel-body card-body">
            <form id="createKhoaForm" method="POST" action="<?= isset($isEdit) && $isEdit ? '?page=edit_khoa&ma=' . urlencode($formData['ma_khoa'] ?? '') : '?page=create_khoa' ?>">
                <div class="form-grid">
                    <div class="form-row row">
                        <div class="form-field col-12 col-md-6">
                            <label class="field-label form-label" for="ma_khoa">
                                Mã khoa/bộ môn <span class="required">*</span>
                            </label>
                            <input
                                id="ma_khoa"
                                name="ma_khoa"
                                class="field-input form-control"
                                placeholder="Ví dụ: K001"
                                value="<?= htmlspecialchars($formData['ma_khoa'] ?? '') ?>"
                                <?= isset($isEdit) && $isEdit ? 'readonly' : '' ?>
                                required
                            />
                            <?php if (isset($isEdit) && $isEdit): ?>
                                <input type="hidden" name="original_ma" value="<?= htmlspecialchars($formData['ma_khoa'] ?? '') ?>" />
                            <?php endif; ?>
                            <span class="field-error<?= isset($errors['ma_khoa']) ? '' : ' is-empty' ?>"><?= isset($errors['ma_khoa']) ? htmlspecialchars($errors['ma_khoa']) : '&nbsp;' ?></span>
                        </div>

                        <div class="form-field col-12 col-md-6">
                            <label class="field-label form-label" for="ten_khoa">
                                Tên khoa/bộ môn <span class="required">*</span>
                            </label>
                            <input
                                id="ten_khoa"
                                name="ten_khoa"
                                class="field-input form-control"
                                placeholder="Ví dụ: Khoa Công nghệ thông tin"
                                value="<?= htmlspecialchars($formData['ten_khoa'] ?? '') ?>"
                                required
                            />
                            <span class="field-error<?= isset($errors['ten_khoa']) ? '' : ' is-empty' ?>"><?= isset($errors['ten_khoa']) ? htmlspecialchars($errors['ten_khoa']) : '&nbsp;' ?></span>
                        </div>
                    </div>

                    <div class="form-row row">
                        <div class="form-field col-12 col-md-6">
                            <label class="field-label form-label" for="email_khoa">
                                Email
                            </label>
                            <input
                                id="email_khoa"
                                name="email_khoa"
                                type="email"
                                class="field-input form-control"
                                placeholder="contact@khoa.edu.vn"
                                value="<?= htmlspecialchars($formData['email_khoa'] ?? '') ?>"
                            />
                            <span class="field-error<?= isset($errors['email_khoa']) ? '' : ' is-empty' ?>"><?= isset($errors['email_khoa']) ? htmlspecialchars($errors['email_khoa']) : '&nbsp;' ?></span>
                        </div>

                        <div class="form-field col-12 col-md-6">
                            <label class="field-label form-label" for="so_dien_thoai_khoa">
                                Số điện thoại
                            </label>
                            <input
                                id="so_dien_thoai_khoa"
                                name="so_dien_thoai_khoa"
                                class="field-input form-control"
                                placeholder="Ví dụ: 0912345678"
                                value="<?= htmlspecialchars($formData['so_dien_thoai_khoa'] ?? '') ?>"
                            />
                            <span class="field-error<?= isset($errors['so_dien_thoai_khoa']) ? '' : ' is-empty' ?>"><?= isset($errors['so_dien_thoai_khoa']) ? htmlspecialchars($errors['so_dien_thoai_khoa']) : '&nbsp;' ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="?page=list_khoa" class="action-btn secondary cancel-btn btn btn-outline-secondary">
                        Hủy
                    </a>
                    <button type="submit" class="action-btn primary<?= isset($isEdit) && $isEdit ? ' save-change-btn' : '' ?> btn btn-primary">
                        <?= isset($isEdit) && $isEdit ? 'Lưu thay đổi' : 'Tạo khoa' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .create-khoa-page {
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

    .field-hint {
        font-size: 11px;
        color: #9ca3af;
        display: block;
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
        .create-khoa-page {
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
    document.getElementById('createKhoaForm')?.addEventListener('submit', function(e){
        var ma = document.getElementById('ma_khoa').value.trim();
        var ten = document.getElementById('ten_khoa').value.trim();
        var email = document.getElementById('email_khoa').value.trim();
        var phone = document.getElementById('so_dien_thoai_khoa').value.trim();

        if (!ma || !ten) {
            e.preventDefault();
            alert('Vui lòng nhập Mã khoa và Tên khoa.');
            return false;
        }

        if (email && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
            e.preventDefault();
            alert('Email không hợp lệ.');
            return false;
        }

        if (phone && !/^[0-9+\-\s]{6,20}$/.test(phone)) {
            e.preventDefault();
            alert('Số điện thoại không hợp lệ.');
            return false;
        }
    });
</script>
