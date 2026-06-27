<?php
    $student = $student ?? [];
    // normalize date value for input[type=date] (YYYY-MM-DD)
    $ngay_sinh_val = '';
    if (!empty($student['ngay_sinh'])) {
        $ds = $student['ngay_sinh'];
        $d = DateTime::createFromFormat('d/m/Y', $ds);
        if ($d instanceof DateTime) {
            $ngay_sinh_val = $d->format('Y-m-d');
        } else {
            $d2 = DateTime::createFromFormat('Y-m-d', $ds);
            if ($d2 instanceof DateTime) $ngay_sinh_val = $d2->format('Y-m-d');
        }
    }
    $avatar = $student['avatar'] ?? '';
    $hasAvatar = is_string($avatar) && trim($avatar) !== '';
?>

<style>
    /* Grid layout for edit form while keeping modal styles */
    /* make modal a bit wider than default and limit height */
    #editProfileModal .modal-card { width: min(780px, 100%); max-width: 920px; }
    #editProfileModal .modal-card { max-height: calc(80vh); display: flex; flex-direction: column; }
    #editProfileModal .modal-body .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px;
        align-items: start;
    }
    #editProfileModal .modal-body .form-grid .full { grid-column: 1 / -1; }
    #editProfileModal .modal-body .modal-field input,
    #editProfileModal .modal-body .modal-field select,
    #editProfileModal .modal-body .modal-field textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 8px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
    }
    #editProfileModal .modal-body .modal-field textarea { min-height: 84px; resize: vertical; }
    /* reduce textarea height slightly */
    #editProfileModal .modal-body .modal-field textarea { min-height: 60px; }

    /* limit modal body height and enable scrolling when content is tall */
    #editProfileModal .modal-body { max-height: calc(80vh - 120px); overflow-y: auto; padding: 14px; }

    /* layout actions horizontally for this modal and style cancel button */
    #editProfileModal .modal-actions { display:flex; gap:12px; justify-content:flex-end; padding: 12px 18px; }
    #editProfileModal .btn-cancel {
        border: 1px solid #e3e9ff;
        background: #fbfdff;
        color: var(--primary-dark);
        padding: 10px 14px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 700;
    }
    #editProfileModal .btn-cancel:hover { filter: brightness(0.98); }

    /* make primary save button narrower (not full width) inside this modal */
    #editProfileModal .modal-save { width: auto; min-width: 120px; padding: 10px 16px; }
    #editProfileModal .avatar-section {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 14px;
        padding: 6px 2px 2px;
    }
    #editProfileModal .avatar-preview {
        width: 92px;
        height: 92px;
        border-radius: 22px;
        overflow: hidden;
        flex: 0 0 auto;
        border: 2px solid #e8ecf3;
        background: linear-gradient(135deg, #e2e8f0 0%, #dbeafe 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.10);
    }
    #editProfileModal .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    #editProfileModal .avatar-preview span {
        font-size: 30px;
        font-weight: 800;
        color: var(--primary);
    }
    #editProfileModal .avatar-meta {
        display: grid;
        gap: 8px;
    }
    #editProfileModal .avatar-upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: fit-content;
        border: 1px solid #e3e9ff;
        background: #fbfdff;
        color: var(--primary-dark);
        padding: 10px 14px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 700;
        transition: 0.2s ease;
    }
    #editProfileModal .avatar-upload-btn:hover { background: #eef2ff; }
    #editProfileModal .avatar-hint {
        font-size: 12px;
        color: #64748b;
    }
    /* calendar icon button inside input wrap */
    #editProfileModal .modal-input-wrap { position: relative; }
    #editProfileModal .modal-icon {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: var(--primary-dark);
        cursor: pointer;
        padding: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
    #editProfileModal .modal-icon:hover { background: #eef2ff; }
</style>

<div class="modal-overlay modal" id="editProfileModal" aria-hidden="true">
    <div class="modal-card modal-content" role="dialog" aria-modal="true" aria-labelledby="editProfileTitle">
        <div class="modal-header">
            <span class="modal-title" id="editProfileTitle">Chỉnh sửa thông tin sinh viên</span>
            <button class="modal-close btn btn-light" type="button" aria-label="Đóng" onclick="closeEditProfileModal()">✕</button>
        </div>

        <form id="editProfileForm" class="modal-body" method="post" action="" enctype="multipart/form-data">
            <div class="avatar-section">
                <div class="avatar-preview" id="avatarPreview">
                    <?php if ($hasAvatar): ?>
                        <img id="avatarPreviewImg" src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar" />
                    <?php else: ?>
                        <span id="avatarPreviewText"><?= htmlspecialchars(substr($student['ho_ten'] ?? 'S', 0, 1), ENT_QUOTES, 'UTF-8') ?></span>
                        <img id="avatarPreviewImg" src="" alt="Avatar" style="display:none" />
                    <?php endif; ?>
                </div>
                <div class="avatar-meta">
                    <button type="button" class="avatar-upload-btn btn btn-outline-secondary" onclick="document.getElementById('avatarInput').click()">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M12 16V4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M8 8l4-4 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4 20h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Tải ảnh lên
                    </button>
                    <div class="avatar-hint">Chọn ảnh từ máy để thay đổi avatar. JPG, PNG hoặc WEBP.</div>
                    <input class="form-control" id="avatarInput" name="avatar" type="file" accept="image/*" style="display:none" />
                </div>
            </div>
            <div class="form-grid">
                <div class="modal-field">
                    <label class="form-label">Họ và tên</label>
                    <input class="form-control" name="ho_ten" value="<?= htmlspecialchars($student['ho_ten'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                </div>
                <div class="modal-field">
                    <label class="form-label">MSSV</label>
                    <input class="form-control" name="mssv" value="<?= htmlspecialchars($student['mssv'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                </div>
                <div class="modal-field">
                    <label class="form-label">Lớp</label>
                    <input class="form-control" name="lop_hoc" value="<?= htmlspecialchars($student['lop_hoc'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                </div>
                <div class="modal-field">
                    <label class="form-label">Ngành</label>
                    <input class="form-control" name="nganh" value="<?= htmlspecialchars($student['nganh'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                </div>
                <div class="modal-field">
                    <label class="form-label">Ngày sinh</label>
                    <div class="modal-input-wrap">
                        <input class="form-control" id="ngaySinh" name="ngay_sinh" type="date" value="<?= htmlspecialchars($ngay_sinh_val ?: ($student['ngay_sinh'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
                        <button type="button" class="modal-icon btn btn-light" aria-label="Chọn ngày" onclick="openDatePicker('ngaySinh')">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
                                <rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2" stroke="currentColor"/>
                                <path d="M16 2v4M8 2v4" stroke-width="2" stroke="currentColor" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="modal-field">
                    <label class="form-label">Giới tính</label>
                    <select class="form-select" name="gioi_tinh">
                        <option <?= (isset($student['gioi_tinh']) && $student['gioi_tinh'] === 'Nam') ? 'selected' : '' ?>>Nam</option>
                        <option <?= (isset($student['gioi_tinh']) && $student['gioi_tinh'] === 'Nữ') ? 'selected' : '' ?>>Nữ</option>
                        <option <?= (isset($student['gioi_tinh']) && $student['gioi_tinh'] === 'Khác') ? 'selected' : '' ?>>Khác</option>
                    </select>
                </div>
                <div class="modal-field">
                    <label class="form-label">Nơi sinh</label>
                    <input class="form-control" name="noi_sinh" value="<?= htmlspecialchars($student['noi_sinh'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                </div>
                <div class="modal-field">
                    <label class="form-label">Dân tộc</label>
                    <input class="form-control" name="dan_toc" value="<?= htmlspecialchars($student['dan_toc'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                </div>
                <div class="modal-field">
                    <label class="form-label">Tôn giáo</label>
                    <input class="form-control" name="ton_giao" value="<?= htmlspecialchars($student['ton_giao'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                </div>
                <div class="modal-field">
                    <label class="form-label">Email</label>
                    <input class="form-control" name="email" value="<?= htmlspecialchars($student['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                </div>
                <div class="modal-field">
                    <label class="form-label">Số điện thoại</label>
                    <input class="form-control" name="so_dien_thoai" value="<?= htmlspecialchars($student['so_dien_thoai'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                </div>
                <div class="modal-field full">
                    <label class="form-label">Địa chỉ thường trú</label>
                    <textarea class="form-control" name="dia_chi_thuong_tru"><?= htmlspecialchars($student['dia_chi_thuong_tru'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            </div>
        </form>

        <div class="modal-actions">
            <button class="btn-cancel btn btn-outline-secondary" type="button" onclick="closeEditProfileModal()">Hủy</button>
            <button class="modal-save btn btn-primary" type="submit" form="editProfileForm">Lưu</button>
        </div>
    </div>
</div>

<script>
    function openEditProfileModal() {
        var modal = document.getElementById('editProfileModal');
        if (!modal) return;
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
    }
    function closeEditProfileModal() {
        var modal = document.getElementById('editProfileModal');
        if (!modal) return;
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeEditProfileModal();
        }
    });

    function openDatePicker(id) {
        var input = document.getElementById(id);
        if (!input) return;
        // modern browsers support showPicker()
        if (typeof input.showPicker === 'function') {
            try { input.showPicker(); return; } catch(e) {}
        }
        // fallback: focus and click
        try { input.focus(); input.click(); } catch(e) { input.focus(); }
    }

    (function() {
        var avatarInput = document.getElementById('avatarInput');
        var avatarImg = document.getElementById('avatarPreviewImg');
        var avatarText = document.getElementById('avatarPreviewText');
        if (!avatarInput) return;

        avatarInput.addEventListener('change', function() {
            var file = avatarInput.files && avatarInput.files[0];
            if (!file) return;

            var reader = new FileReader();
            reader.onload = function(e) {
                if (!avatarImg) return;
                avatarImg.src = e.target.result;
                avatarImg.style.display = 'block';
                if (avatarText) avatarText.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    })();
</script>
 
