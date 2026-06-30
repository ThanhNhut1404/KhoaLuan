<style>
#confirmStatusModal { display:none; }
#confirmStatusModal.active { display:grid; place-items:center; position:fixed; inset:0; z-index:1200; }
#confirmStatusModal .modal-card {
    width:min(480px, calc(100% - 32px));
    min-width:320px;
    max-width:calc(100% - 32px);
    padding:12px;
    border-radius:10px;
    box-shadow:0 10px 30px rgba(2,6,23,0.2);
}
#confirmStatusModal .modal-header { padding:2px 10px; }
#confirmStatusModal .modal-title { font-size:14px; font-weight:800; color:#0f2a5a; }
#confirmStatusModal .modal-close {
    width:28px;
    height:28px;
    padding:0;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    line-height:1;
}
#confirmStatusModal .modal-body { padding:12px 10px 8px; }
#confirmStatusModal .confirm-text { margin:0; text-align:center; }
#confirmStatusModal .confirm-question { margin:0; font-size:15px; line-height:1.45; font-weight:700; color:#1f2937; overflow-wrap:anywhere; }
#confirmStatusModal .confirm-warning { margin:8px 0 0; font-size:14px; line-height:1.4; font-weight:800; color:#b91c1c; }
#confirmStatusModal .modal-actions { display:flex; gap:10px; justify-content:center; padding:24px 10px 10px; }
#confirmStatusModal .action-btn {
    width:auto !important;
    height:auto !important;
    white-space:nowrap;
    padding:8px 20px;
    border-radius:8px;
    font-size:14px;
    font-weight:600;
}
#confirmStatusModal .action-btn.secondary { background:#f3f4f6; border-color:#d1d5db; color:#0f2a5a; }
#confirmStatusModal .action-btn.primary { background:linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); border-color:#0f2a5a; color:#fff; }
@media (max-width:420px) {
    #confirmStatusModal .modal-card { min-width:0; }
}
</style>

<script>
let _statusConfirmSelect = null;
let _statusConfirmPrevious = '';
let _statusConfirmNext = '';

function showStatusConfirm(options) {
    options = options || {};
    _statusConfirmSelect = options.select || null;
    _statusConfirmPrevious = (options.previousStatus || '').toString();
    _statusConfirmNext = (options.nextStatus || '').toString();

    const modal = document.getElementById('confirmStatusModal');
    const question = modal.querySelector('.confirm-question');
    const warning = modal.querySelector('.confirm-warning');
    const targetName = (options.targetName || '').toString().trim();
    const moduleLabel = (options.moduleLabel || 'mục').toString();
    const displayName = targetName !== '' ? '"' + targetName + '"' : 'này';

    question.textContent = options.question || ('Bạn có chắc chắn muốn chuyển ' + moduleLabel + ' ' + displayName + ' từ "' + _statusConfirmPrevious + '" sang "' + _statusConfirmNext + '" không?');
    warning.textContent = options.warning || 'Thao tác này sẽ thay đổi trạng thái của dữ liệu.';

    modal.classList.add('active');
    modal.setAttribute('aria-hidden', 'false');
}

function hideStatusConfirm() {
    const modal = document.getElementById('confirmStatusModal');
    modal.classList.remove('active');
    modal.setAttribute('aria-hidden', 'true');

    if (_statusConfirmSelect) {
        _statusConfirmSelect.value = _statusConfirmPrevious;
        if (typeof setStatusClass === 'function') {
            setStatusClass(_statusConfirmSelect, _statusConfirmPrevious);
        }
    }

    _statusConfirmSelect = null;
    _statusConfirmPrevious = '';
    _statusConfirmNext = '';
}

function confirmStatusChange() {
    if (!_statusConfirmSelect || !_statusConfirmSelect.form) {
        hideStatusConfirm();
        return;
    }

    _statusConfirmSelect.value = _statusConfirmNext;
    if (typeof setStatusClass === 'function') {
        setStatusClass(_statusConfirmSelect, _statusConfirmNext);
    }
    _statusConfirmSelect.form.submit();
}
</script>

<div class="modal-overlay modal" id="confirmStatusModal" aria-hidden="true">
    <div class="modal-card modal-content" role="dialog" aria-modal="true" aria-labelledby="confirmStatusTitle">
        <div class="modal-header">
            <span class="modal-title" id="confirmStatusTitle">Xác nhận thay đổi trạng thái</span>
            <button class="modal-close btn btn-light" type="button" aria-label="Đóng" onclick="hideStatusConfirm()">×</button>
        </div>
        <div class="modal-body">
            <div class="confirm-text">
                <p class="confirm-question">Bạn có chắc chắn muốn thay đổi trạng thái không?</p>
                <p class="confirm-warning">Thao tác này sẽ thay đổi trạng thái của dữ liệu.</p>
            </div>
        </div>
        <div class="modal-actions">
            <button class="action-btn secondary cancel-btn btn btn-outline-secondary" type="button" onclick="hideStatusConfirm()">Hủy</button>
            <button class="action-btn primary btn btn-primary" type="button" onclick="confirmStatusChange()">Xác nhận</button>
        </div>
    </div>
</div>
