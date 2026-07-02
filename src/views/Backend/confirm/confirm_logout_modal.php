<style>
#confirmLogoutModal { display:none; }
#confirmLogoutModal.active { display:grid; place-items:center; position:fixed; inset:0; z-index:1200; }
#confirmLogoutModal .modal-card {
    width:min(460px, calc(100% - 32px));
    min-width:320px;
    max-width:calc(100% - 32px);
    padding:12px;
    border-radius:10px;
    box-shadow:0 10px 30px rgba(2,6,23,0.2);
}
#confirmLogoutModal .modal-header { padding:2px 10px; }
#confirmLogoutModal .modal-title { font-size:14px; font-weight:800; color:#0f2a5a; }
#confirmLogoutModal .modal-close {
    width:28px;
    height:28px;
    padding:0;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    line-height:1;
}
#confirmLogoutModal .modal-body { padding:12px 10px 8px; }
#confirmLogoutModal .confirm-text { margin:0; text-align:center; }
#confirmLogoutModal .confirm-question { margin:0; font-size:15px; line-height:1.45; font-weight:700; color:#1f2937; overflow-wrap:anywhere; }
#confirmLogoutModal .modal-actions { display:flex; gap:10px; justify-content:center; padding:24px 10px 10px; }
#confirmLogoutModal .action-btn {
    width:auto !important;
    height:auto !important;
    white-space:nowrap;
    padding:8px 20px;
    border-radius:8px;
    font-size:14px;
    font-weight:600;
}
#confirmLogoutModal .action-btn.secondary { background:#f3f4f6; border-color:#d1d5db; color:#0f2a5a; }
#confirmLogoutModal .action-btn.primary { background:linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); border-color:#0f2a5a; color:#fff; }
@media (max-width:420px) {
    #confirmLogoutModal .modal-card { min-width:0; }
}
</style>

<script>
let _adminLogoutUrl = '/KhoaLuan/public/admin.php?page=logout';

function showAdminLogoutConfirm(event, link) {
    if (event) {
        event.preventDefault();
    }

    if (link && link.href) {
        _adminLogoutUrl = link.href;
    }

    const userMenu = document.getElementById('userMenu');
    const userBtn = document.getElementById('userMenuBtn');
    if (userMenu) {
        userMenu.classList.remove('open');
    }
    if (userBtn) {
        userBtn.setAttribute('aria-expanded', 'false');
    }

    const modal = document.getElementById('confirmLogoutModal');
    modal.classList.add('active');
    modal.setAttribute('aria-hidden', 'false');
}

function hideAdminLogoutConfirm() {
    const modal = document.getElementById('confirmLogoutModal');
    modal.classList.remove('active');
    modal.setAttribute('aria-hidden', 'true');
}

function confirmAdminLogout() {
    window.location.href = _adminLogoutUrl;
}
</script>

<div class="modal-overlay modal" id="confirmLogoutModal" aria-hidden="true">
    <div class="modal-card modal-content" role="dialog" aria-modal="true" aria-labelledby="confirmLogoutTitle">
        <div class="modal-header">
            <span class="modal-title" id="confirmLogoutTitle">Xác nhận đăng xuất</span>
            <button class="modal-close btn btn-light" type="button" aria-label="Đóng" onclick="hideAdminLogoutConfirm()">×</button>
        </div>
        <div class="modal-body">
            <div class="confirm-text">
                <p class="confirm-question">Bạn có chắc chắn muốn đăng xuất khỏi hệ thống không?</p>
            </div>
        </div>
        <div class="modal-actions">
            <button class="action-btn secondary cancel-btn btn btn-outline-secondary" type="button" onclick="hideAdminLogoutConfirm()">Hủy</button>
            <button class="action-btn primary btn btn-primary" type="button" onclick="confirmAdminLogout()">Đăng xuất</button>
        </div>
    </div>
</div>
