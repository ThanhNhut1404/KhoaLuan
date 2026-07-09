<?php
$changePasswordErrors = $changePasswordErrors ?? [];
$openChangePasswordModal = $openChangePasswordModal ?? false;
?>

<style>
    #passwordModal .modal-card {
        width: min(380px, 100%);
    }

    #passwordModal .modal-header {
        min-height: 48px;
        padding: 10px 16px;
    }

    #passwordModal .modal-title {
        font-size: 15px;
        font-weight: 600;
    }

    #passwordModal .modal-close {
        width: 34px;
        height: 34px;
        padding: 0;
        font-size: 31px;
        line-height: 1;
        color: var(--primary-dark);
        background: transparent;
    }

    #passwordModal .modal-close:hover {
        color: var(--primary-dark);
        background: #eef4ff;
    }

    #passwordModal .modal-body {
        gap: 10px;
        padding: 14px 18px;
    }

    #passwordModal .modal-field label {
        margin-bottom: 5px;
    }

    #passwordModal .modal-field input {
        height: 42px;
        padding: 9px 42px 9px 12px;
        border-radius: 11px;
    }

    #passwordModal .modal-actions {
        padding: 0 18px 18px;
    }

    #passwordModal .modal-save {
        min-height: 42px;
        padding: 9px 14px;
        border-radius: 11px;
        font-weight: 800;
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%);
        box-shadow: 0 10px 20px rgba(22, 163, 74, 0.22);
    }

    #passwordModal .modal-save:hover {
        background: linear-gradient(180deg, #15803d 0%, #166534 100%);
    }
</style>

<div class="modal-overlay modal<?= $openChangePasswordModal ? ' active' : '' ?>" id="passwordModal" aria-hidden="<?= $openChangePasswordModal ? 'false' : 'true' ?>">
    <div class="modal-card modal-content" role="dialog" aria-modal="true" aria-labelledby="passwordModalTitle">
        <div class="modal-header">
            <span class="modal-title" id="passwordModalTitle">Đổi mật khẩu</span>
            <button class="modal-close btn btn-light" type="button" aria-label="Đóng" onclick="closePasswordModal()">×</button>
        </div>
        <form method="post" action="/KhoaLuan/public/student.php?action=change_password">
            <div class="modal-body">
                <div class="modal-field pwd-wrap">
                    <label class="form-label" for="currentPassword">Mật khẩu cũ<span class="req">*</span></label>
                    <div class="modal-input-wrap">
                        <input id="currentPassword" name="current_password" type="password" class="form-control" placeholder="Nhập mật khẩu cũ" />
                        <button class="modal-toggle" type="button" aria-label="Hiện mật khẩu" data-target="currentPassword">
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
                <div class="modal-field pwd-wrap">
                    <label class="form-label" for="newPassword">Mật khẩu mới<span class="req">*</span></label>
                    <div class="modal-input-wrap">
                        <input id="newPassword" name="new_password" type="password" class="form-control" placeholder="Nhập mật khẩu mới" data-password-strength />
                        <button class="modal-toggle" type="button" aria-label="Hiện mật khẩu" data-target="newPassword">
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
                <div class="modal-field pwd-wrap">
                    <label class="form-label" for="confirmPassword">Xác nhận mật khẩu<span class="req">*</span></label>
                    <div class="modal-input-wrap">
                        <input id="confirmPassword" name="confirm_password" type="password" class="form-control" placeholder="Nhập lại mật khẩu mới" data-password-match="#newPassword" />
                        <button class="modal-toggle" type="button" aria-label="Hiện mật khẩu" data-target="confirmPassword">
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
    function openPasswordModal() {
        const modal = document.getElementById('passwordModal');
        if (!modal) return;
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closePasswordModal() {
        const modal = document.getElementById('passwordModal');
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
