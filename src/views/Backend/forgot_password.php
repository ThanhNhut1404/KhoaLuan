<?php
// Modal quên mật khẩu cho quản trị viên
?>

<div class="modal-overlay modal" id="forgotModal" aria-hidden="true">
    <div class="modal-card modal-content" role="dialog" aria-modal="true" aria-labelledby="forgotTitle">
        <div class="modal-header">
            <div class="modal-title" id="forgotTitle">Quên mật khẩu</div>
            <button class="modal-close btn btn-light" type="button" aria-label="Đóng" onclick="closeForgotModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-note">Nhập email của bạn để nhận mã OTP đặt lại mật khẩu.</div>
            <div class="form-row">
                <label class="form-label" for="forgotEmail">Địa chỉ Email</label>
                <input id="forgotEmail" class="form-control" type="email" placeholder="Nhập email" />
            </div>
        </div>
        <div class="modal-actions">
            <button class="modal-btn" type="button">Gửi mã OTP</button>
        </div>
    </div>
</div>
