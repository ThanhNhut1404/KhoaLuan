<?php
    $formData = $formData ?? [];
    $errors = $errors ?? [];
    $isEdit = $isEdit ?? true;
?>

<div class="edit-khoa-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">CHỈNH SỬA KHOA</h2>
        </div>

        <div class="panel-body card-body">
            <?php if (isset($adminToast) && $adminToast): ?>
                <div class="alert alert-<?= htmlspecialchars($adminToast['type'] ?? 'info') ?>" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;">
                    <?= htmlspecialchars($adminToast['message'] ?? '') ?>
                </div>
            <?php endif; ?>

            <form id="editKhoaForm" method="POST" action="?page=edit_khoa&ma=<?= urlencode($formData['ma_khoa'] ?? '') ?>">
                <div class="row g-3 g-md-4 year-form-row">
                    <div class="col-12 col-md-6 year-field">
                        <label class="field-label form-label" for="ma_khoa">
                            Mã khoa <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="ma_khoa"
                            name="ma_khoa"
                            class="field-input form-control"
                            placeholder="Ví dụ: K001"
                            value="<?= htmlspecialchars($formData['ma_khoa'] ?? '') ?>"
                            readonly
                        />
                        <input type="hidden" name="original_ma" value="<?= htmlspecialchars($formData['ma_khoa'] ?? '') ?>" />
                        <?php if(isset($errors['ma_khoa'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['ma_khoa']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 col-md-6 year-field">
                        <label class="field-label form-label" for="ten_khoa">
                            Tên khoa <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="ten_khoa"
                            name="ten_khoa"
                            class="field-input form-control<?= isset($errors['ten_khoa']) ? ' is-invalid' : '' ?>"
                            placeholder="Ví dụ: Khoa Công nghệ thông tin"
                            value="<?= htmlspecialchars($formData['ten_khoa'] ?? '') ?>"
                            required
                        />
                        <?php if(isset($errors['ten_khoa'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['ten_khoa']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 col-md-6 year-field">
                        <label class="field-label form-label" for="email_khoa">
                            Email khoa
                        </label>
                        <input
                            type="email"
                            id="email_khoa"
                            name="email_khoa"
                            class="field-input form-control<?= isset($errors['email_khoa']) ? ' is-invalid' : '' ?>"
                            placeholder="contact@khoa.edu.vn"
                            value="<?= htmlspecialchars($formData['email_khoa'] ?? '') ?>"
                        />
                        <?php if(isset($errors['email_khoa'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['email_khoa']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 col-md-6 year-field">
                        <label class="field-label form-label" for="so_dien_thoai_khoa">
                            Số điện thoại
                        </label>
                        <input
                            type="text"
                            id="so_dien_thoai_khoa"
                            name="so_dien_thoai_khoa"
                            class="field-input form-control<?= isset($errors['so_dien_thoai_khoa']) ? ' is-invalid' : '' ?>"
                            placeholder="Ví dụ: 0912345678"
                            value="<?= htmlspecialchars($formData['so_dien_thoai_khoa'] ?? '') ?>"
                        />
                        <?php if(isset($errors['so_dien_thoai_khoa'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['so_dien_thoai_khoa']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12" style="display:flex; justify-content:flex-end; gap:12px;">
                        <a href="?page=list_khoa" class="action-btn secondary cancel-btn btn btn-outline-secondary">
                            Hủy
                        </a>
                        <button type="submit" class="action-btn primary save-change-btn btn btn-primary">
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .edit-khoa-page {
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

    .invalid-feedback {
        font-size: 12px;
        margin-top: 6px;
        color: #dc2626;
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
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .action-btn:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .action-btn.secondary:hover {
        color: #dc2626;
        background: #e5e7eb;
        border-color: #cbd5e1;
    }

    .action-btn.secondary {
        color: #dc2626;
    }

    .action-btn.primary {
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%);
        border-color: #16a34a;
        color: #ffffff;
    }

    .action-btn.primary:hover {
        background: linear-gradient(180deg, #15803d 0%, #166534 100%);
        border-color: #15803d;
    }

    @media (max-width: 768px) {
        .edit-khoa-page {
            padding: 16px;
        }

        .page-panel {
            margin: 0;
        }

        .year-form-row {
            grid-template-columns: 1fr;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
