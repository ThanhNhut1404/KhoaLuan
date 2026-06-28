<?php
    $formData = $formData ?? [];
    $errors = $errors ?? [];
    $isEdit = $isEdit ?? false;
?>

<div class="create-khoa-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">TẠO KHOA</h2>
        </div>

        <div class="panel-body card-body">
            <div class="create-khoa-page">
                <div class="page-panel card" style="max-width:900px;margin:24px auto;">
                    <div class="panel-header card-header">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
                            <h2 class="panel-title">TẠO KHOA</h2>
                            <a href="?page=list_khoa" class="btn-create" style="padding:8px 12px;font-size:13px;">← Quay về danh sách</a>
                        </div>
                    </div>

                    <div class="panel-body card-body" style="padding:22px;">
                        <?php if(isset($adminToast) && $adminToast): ?>
                            <div class="alert alert-<?= $adminToast['type'] ?? 'info' ?>" style="margin-bottom:16px;padding:12px 14px;border-radius:8px;">
                                <?= htmlspecialchars($adminToast['message'] ?? '') ?>
                            </div>
                        <?php endif; ?>

                        <form id="createKhoaForm" method="POST" action="<?= isset($isEdit) && $isEdit ? '?page=edit_khoa&ma=' . urlencode($formData['ma_khoa'] ?? '') : '?page=create_khoa' ?>">
                            <div class="form-grid" style="grid-template-columns:repeat(2,1fr);gap:18px;">
                                <div class="form-field">
                                    <label class="field-label form-label" for="ma_khoa">Mã khoa <span class="required">*</span></label>
                                    <input id="ma_khoa" name="ma_khoa" class="field-input form-control" required
                                           placeholder="Ví dụ: K001"
                                           value="<?= htmlspecialchars($formData['ma_khoa'] ?? '') ?>" <?= isset($isEdit) && $isEdit ? 'readonly' : '' ?> />
                                    <?php if (isset($isEdit) && $isEdit): ?>
                                        <input type="hidden" name="original_ma" value="<?= htmlspecialchars($formData['ma_khoa'] ?? '') ?>" />
                                    <?php endif; ?>
                                    <?php if(isset($errors['ma_khoa'])): ?><span class="field-error"><?= $errors['ma_khoa'] ?></span><?php endif; ?>
                                </div>

                                <div class="form-field">
                                    <label class="field-label form-label" for="ten_khoa">Tên khoa <span class="required">*</span></label>
                                    <input id="ten_khoa" name="ten_khoa" class="field-input form-control" required
                                           placeholder="Ví dụ: Khoa Công nghệ thông tin"
                                           value="<?= htmlspecialchars($formData['ten_khoa'] ?? '') ?>" />
                                    <?php if(isset($errors['ten_khoa'])): ?><span class="field-error"><?= $errors['ten_khoa'] ?></span><?php endif; ?>
                                </div>

                                <div class="form-field">
                                    <label class="field-label form-label" for="email_khoa">Email khoa</label>
                                    <input id="email_khoa" name="email_khoa" type="email" class="field-input form-control"
                                           placeholder="contact@khoa.edu.vn"
                                           value="<?= htmlspecialchars($formData['email_khoa'] ?? '') ?>" />
                                    <?php if(isset($errors['email_khoa'])): ?><span class="field-error"><?= $errors['email_khoa'] ?></span><?php endif; ?>
                                </div>

                                <div class="form-field">
                                    <label class="field-label form-label" for="so_dien_thoai_khoa">Số điện thoại</label>
                                    <input id="so_dien_thoai_khoa" name="so_dien_thoai_khoa" class="field-input form-control"
                                           placeholder="Ví dụ: 0912345678"
                                           value="<?= htmlspecialchars($formData['so_dien_thoai_khoa'] ?? '') ?>" />
                                    <?php if(isset($errors['so_dien_thoai_khoa'])): ?><span class="field-error"><?= $errors['so_dien_thoai_khoa'] ?></span><?php endif; ?>
                                </div>

                                <div style="grid-column:1/ -1; display:flex; gap:12px; justify-content:flex-end;">
                                    <a href="?page=list_khoa" class="action-btn secondary" style="padding:10px 18px;">Hủy</a>
                                    <button type="submit" class="action-btn primary" style="padding:10px 18px;"><?= isset($isEdit) && $isEdit ? 'Lưu thay đổi' : 'Tạo khoa' ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <style>
                /* Minimal polished styling, reusing project style tokens */
                .create-khoa-page { padding: 12px; }
                .panel-title { font-size: 16px; font-weight: 800; color: #0f2a5a; }
                .field-label { font-size: 13px; font-weight: 700; color: #0f2a5a; margin-bottom:6px; display:block; }
                .required { color: #dc2626; }
                .field-input { width:100%; padding:10px 12px; border-radius:10px; border:1px solid #e5e7eb; background:#fff; font-size:14px; }
                .field-input:focus{ outline:none; border-color:#0f2a5a; box-shadow:0 0 0 4px rgba(29,78,216,0.08); }
                .form-grid { display:grid; }
                .form-field { display:grid; gap:6px; }
                .action-btn { border-radius:10px; border:1px solid #e5e7eb; background:#fff; color:#0f2a5a; font-weight:700; text-decoration:none; cursor:pointer; }
                .action-btn.primary { background: linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); color:#fff; border-color:#0f2a5a; }
                .action-btn.secondary { background:#fff; }
                .field-error { color:#dc2626; font-size:13px; }
                .btn-create { background: transparent; color:#0f2a5a; text-decoration:none; border:1px solid transparent; }
                @media (max-width:768px){ .page-panel{ margin:12px; } .form-grid{ grid-template-columns:1fr; } }
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
