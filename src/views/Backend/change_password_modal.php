<?php
$changePasswordErrors = $changePasswordErrors ?? [];
$openChangePasswordModal = $openChangePasswordModal ?? false;
?>

<div class="modal-overlay modal<?= $openChangePasswordModal ? ' active' : '' ?>" id="adminPasswordModal" aria-hidden="<?= $openChangePasswordModal ? 'false' : 'true' ?>">
    <div class="modal-card modal-content" role="dialog" aria-modal="true" aria-labelledby="adminPasswordModalTitle">
        <div class="modal-header">
            <span class="modal-title" id="adminPasswordModalTitle">Đổi mật khẩu</span>
            <button class="modal-close btn btn-light" type="button" aria-label="Đóng" onclick="closeAdminPasswordModal()">✕</button>
        </div>
        <form method="post" action="/KhoaLuan/public/admin.php?page=change_password">
            <div class="modal-body">
            <div class="modal-field">
                <label for="adminCurrentPassword">Mật khẩu cũ<span class="req">*</span></label>
                <div class="modal-input-wrap">
                    <input id="adminCurrentPassword" name="current_password" type="password" placeholder="Nhập mật khẩu cũ" />
                    <button class="modal-toggle" type="button" aria-label="Hiện mật khẩu" data-target="adminCurrentPassword">
                        <svg class="eye-on" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="3" stroke-width="2" />
                        </svg>
                        <svg class="eye-off" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 3l18 18" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10.5 6.5A9.9 9.9 0 0 1 12 6c6.5 0 10 6 10 6a18.2 18.2 0 0 1-3.4 4.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.6 6.6A18 18 0 0 0 2 12s3.5 6 10 6c1.8 0 3.4-.4 4.8-1.1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                <?php if (!empty($changePasswordErrors['current_password'])): ?>
                    <div class="modal-error"><?= htmlspecialchars($changePasswordErrors['current_password']) ?></div>
                <?php endif; ?>
            </div>
            <div class="modal-field">
                <label for="adminNewPassword">Mật khẩu mới<span class="req">*</span></label>
                <div class="modal-input-wrap">
                    <input id="adminNewPassword" name="new_password" type="password" placeholder="Nhập mật khẩu mới" />
                    <button class="modal-toggle" type="button" aria-label="Hiện mật khẩu" data-target="adminNewPassword">
                        <svg class="eye-on" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="3" stroke-width="2" />
                        </svg>
                        <svg class="eye-off" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 3l18 18" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10.5 6.5A9.9 9.9 0 0 1 12 6c6.5 0 10 6 10 6a18.2 18.2 0 0 1-3.4 4.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.6 6.6A18 18 0 0 0 2 12s3.5 6 10 6c1.8 0 3.4-.4 4.8-1.1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                <?php if (!empty($changePasswordErrors['new_password'])): ?>
                    <div class="modal-error"><?= htmlspecialchars($changePasswordErrors['new_password']) ?></div>
                <?php endif; ?>
            </div>
            <div class="modal-field">
                <label for="adminConfirmPassword">Xác nhận mật khẩu<span class="req">*</span></label>
                <div class="modal-input-wrap">
                    <input id="adminConfirmPassword" name="confirm_password" type="password" placeholder="Nhập lại mật khẩu mới" />
                    <button class="modal-toggle" type="button" aria-label="Hiện mật khẩu" data-target="adminConfirmPassword">
                        <svg class="eye-on" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="3" stroke-width="2" />
                        </svg>
                        <svg class="eye-off" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 3l18 18" stroke-width="2" stroke-linecap="round"/>
                            <path d="M10.5 6.5A9.9 9.9 0 0 1 12 6c6.5 0 10 6 10 6a18.2 18.2 0 0 1-3.4 4.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.6 6.6A18 18 0 0 0 2 12s3.5 6 10 6c1.8 0 3.4-.4 4.8-1.1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                <?php if (!empty($changePasswordErrors['confirm_password'])): ?>
                    <div class="modal-error"><?= htmlspecialchars($changePasswordErrors['confirm_password']) ?></div>
                <?php endif; ?>
            </div>
            </div>
            <div class="modal-actions">
                <button class="modal-save btn btn-primary" type="submit">Lưu</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAdminPasswordModal() {
        var modal = document.getElementById('adminPasswordModal');
        if (!modal) return;
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeAdminPasswordModal() {
        var modal = document.getElementById('adminPasswordModal');
        if (!modal) return;
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }

    document.querySelectorAll('.modal-toggle').forEach(function(button){
        button.addEventListener('click', function(){
            var targetId = button.getAttribute('data-target');
            var input = document.getElementById(targetId);
            if (!input) return;
            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            button.classList.toggle('is-visible', isHidden);
            button.setAttribute('aria-label', isHidden ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
        });
    });
</script>
