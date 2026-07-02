<style>
/* Small confirmation modal overrides (local to this page) */
#confirmDeleteModal { display:none; }
#confirmDeleteModal.active { display: grid; place-items: center; position: fixed; inset: 0; z-index: 1200; }
#confirmDeleteModal .modal-card {
    width: min(460px, calc(100% - 32px));
    min-width: 320px;
    max-width: calc(100% - 32px);
    padding: 12px;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(2,6,23,0.2);
}
#confirmDeleteModal .modal-header { padding: 2px 10px; }
#confirmDeleteModal .modal-title { font-size:14px; font-weight:800; color:#0f2a5a; }
#confirmDeleteModal .modal-close {
    width: 28px;
    height: 28px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    line-height: 1;
}
#confirmDeleteModal .modal-body { padding: 12px 10px 8px; }
#confirmDeleteModal .confirm-text { margin:0; text-align:center; }
#confirmDeleteModal .confirm-question { margin:0; font-size:15px; line-height:1.45; font-weight:700; color:#1f2937; overflow-wrap:anywhere; }
#confirmDeleteModal .confirm-warning { margin:8px 0 0; font-size:14px; line-height:1.4; font-weight:800; color:#b91c1c; }
#confirmDeleteModal .modal-actions { display:flex; gap:10px; justify-content:center; padding:24px 10px 10px; }
#confirmDeleteModal .action-btn {
        width: auto !important;
        height: auto !important;
        white-space: nowrap;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
    }
#confirmDeleteModal .action-btn.secondary { background:#f3f4f6; border-color:#d1d5db; color:#0f2a5a; }
#confirmDeleteModal .action-btn.primary { background: linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); border-color:#0f2a5a; color:#fff; }
@media (max-width: 420px) {
    #confirmDeleteModal .modal-card { min-width: 0; }
}
</style>

<script>
// Delete confirmation modal logic
let _deleteTargetId = null;
let _deleteTargetLabel = '';
let _deleteTargetName = '';

function showDeleteConfirm(id, label, targetName) {
    _deleteTargetId = id;
    _deleteTargetLabel = label || '';
    _deleteTargetName = (targetName || '').toString().trim();

    const modal = document.getElementById('confirmDeleteModal');
    const question = modal.querySelector('.confirm-question');
    const warning = modal.querySelector('.confirm-warning');
    const fallbackText = modal.querySelector('.confirm-text');
    const target = _deleteTargetName !== '' ? '"' + _deleteTargetName + '"' : '#' + id;
    const questionText = _deleteTargetLabel === 'student'
        ? 'Bạn có chắc chắn muốn xóa sinh viên này không?'
        : 'Bạn có chắc chắn muốn xóa ' + _deleteTargetLabel + ' ' + target + ' không?';
    const warningText = 'Thao tác này không thể hoàn tác.';

    if (question && warning) {
        question.textContent = questionText;
        warning.textContent = warningText;
    } else if (fallbackText) {
        fallbackText.textContent = questionText + '\n' + warningText;
    }

    modal.classList.add('active');
    modal.setAttribute('aria-hidden','false');
}

function hideDeleteConfirm() {
    const modal = document.getElementById('confirmDeleteModal');
    modal.classList.remove('active');
    modal.setAttribute('aria-hidden','true');
    _deleteTargetId = null;
    _deleteTargetLabel = '';
    _deleteTargetName = '';
}

function confirmDelete() {
    if (!_deleteTargetId) return hideDeleteConfirm();

    const majorDeleteForm = document.getElementById('majorDeleteForm');
    const majorDeleteId = document.getElementById('majorDeleteId');
    if (_deleteTargetLabel === 'ngành học' && majorDeleteForm && majorDeleteId) {
        majorDeleteId.value = _deleteTargetId;
        majorDeleteForm.submit();
        return;
    }

    const khoaDeleteForm = document.getElementById('khoaDeleteForm');
    const khoaDeleteId = document.getElementById('khoaDeleteId');
    if (_deleteTargetLabel === 'khoa/bộ môn' && khoaDeleteForm && khoaDeleteId) {
        khoaDeleteId.value = _deleteTargetId;
        khoaDeleteForm.submit();
        return;
    }

    const semesterDeleteForm = document.getElementById('semesterDeleteForm');
    const semesterDeleteId = document.getElementById('semesterDeleteId');
    if (_deleteTargetLabel === 'học kỳ' && semesterDeleteForm && semesterDeleteId) {
        semesterDeleteId.value = _deleteTargetId;
        semesterDeleteForm.submit();
        return;
    }

    const classDeleteForm = document.getElementById('classDeleteForm');
    const classDeleteId = document.getElementById('classDeleteId');
    if (_deleteTargetLabel === 'lớp học' && classDeleteForm && classDeleteId) {
        classDeleteId.value = _deleteTargetId;
        classDeleteForm.submit();
        return;
    }

    const studentDeleteForm = document.getElementById('studentDeleteForm');
    const studentDeleteId = document.getElementById('studentDeleteId');
    if (_deleteTargetLabel === 'student' && studentDeleteForm && studentDeleteId) {
        studentDeleteId.value = _deleteTargetId;
        studentDeleteForm.submit();
        return;
    }

    const row = document.querySelector('tr[data-id="' + _deleteTargetId + '"]');
    if (row) row.remove();
    hideDeleteConfirm();
}
</script>

<!-- Confirmation modal (uses global modal styles) -->
<div class="modal-overlay modal" id="confirmDeleteModal" aria-hidden="true">
    <div class="modal-card modal-content" role="dialog" aria-modal="true" aria-labelledby="confirmDeleteTitle">
        <div class="modal-header">
            <span class="modal-title" id="confirmDeleteTitle">Xác nhận xóa</span>
            <button class="modal-close btn btn-light" type="button" aria-label="Đóng" onclick="hideDeleteConfirm()">×</button>
        </div>
        <div class="modal-body">
            <div class="confirm-text">
                <p class="confirm-question">Bạn có chắc chắn muốn xóa mục này không?</p>
                <p class="confirm-warning">Thao tác này không thể hoàn tác.</p>
            </div>
        </div>
        <div class="modal-actions">
            <button class="action-btn secondary cancel-btn btn btn-outline-secondary" type="button" onclick="hideDeleteConfirm()">Hủy</button>
            <button class="action-btn primary btn btn-primary" type="button" onclick="confirmDelete()">Xác nhận</button>
        </div>
    </div>
</div>
