<style>
/* Small confirmation modal overrides (local to this page) */
#confirmDeleteModal { display:none; }
#confirmDeleteModal.active { display: grid; place-items: center; position: fixed; inset: 0; z-index: 1200; }
#confirmDeleteModal .modal-card { 
    width: fit-content; 
    min-width: 260px;
    max-width: calc(100% - 32px); 
    padding: 12px; 
    border-radius: 10px; 
    box-shadow: 0 10px 30px rgba(2,6,23,0.2); 
}
#confirmDeleteModal .modal-header { padding: 2px 10px; }
#confirmDeleteModal .modal-title { font-size:14px; font-weight:800; color:#0f2a5a; }
#confirmDeleteModal .modal-body { padding: 8px 10px; }
#confirmDeleteModal .confirm-text { font-size:15px; font-weight:800; color:#b91c1c; margin:0; text-align:center; }
#confirmDeleteModal .modal-actions { display:flex; gap:10px; justify-content:center; padding:28px 10px 10px; }
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
</style>

<script>
// Delete confirmation modal logic
let _deleteTargetId = null;
function showDeleteConfirm(id, label) {
    _deleteTargetId = id;
    const modal = document.getElementById('confirmDeleteModal');
    modal.querySelector('.confirm-text').textContent = 'Bạn có chắc chắn muốn xóa ' + label + ' #' + id + ' không?';
    modal.classList.add('active');
    modal.setAttribute('aria-hidden','false');
}

function hideDeleteConfirm() {
    const modal = document.getElementById('confirmDeleteModal');
    modal.classList.remove('active');
    modal.setAttribute('aria-hidden','true');
    _deleteTargetId = null;
}

function confirmDelete() {
    if (!_deleteTargetId) return hideDeleteConfirm();
    const row = document.querySelector('tr[data-id="' + _deleteTargetId + '"]');
    if (row) row.remove();
    hideDeleteConfirm();
}
</script>

<!-- Confirmation modal (uses global modal styles) -->
<div class="modal-overlay" id="confirmDeleteModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="confirmDeleteTitle">
        <div class="modal-header">
            <span class="modal-title" id="confirmDeleteTitle">Xác nhận xóa</span>
            <button class="modal-close" type="button" aria-label="Đóng" onclick="hideDeleteConfirm()">✕</button>
        </div>
        <div class="modal-body">
            <p class="confirm-text">Bạn có chắc chắn muốn xóa mục này không?</p>
        </div>
        <div class="modal-actions">
            <button class="action-btn secondary" type="button" onclick="hideDeleteConfirm()">Hủy</button>
            <button class="action-btn primary" type="button" onclick="confirmDelete()">Xác nhận</button>
        </div>
    </div>
</div>
