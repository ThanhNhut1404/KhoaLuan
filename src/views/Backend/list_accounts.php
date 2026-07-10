<?php
    $accounts = $accounts ?? [];
    $roles = $roles ?? [];
    $genders = ['male'=>'Nam','female'=>'Nữ','other'=>'Khác'];
    $statuses = ['active'=>'Hoạt động','inactive'=>'Không hoạt động'];

    $current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    $total_items = count($accounts);
    $items_per_page = 10;
    $total_pages = max(1, (int) ceil($total_items / $items_per_page));
    $canEditAccount = is_callable($canAccessPermission ?? null) && $canAccessPermission('edit_account');
    $canDeleteAccount = is_callable($canAccessPermission ?? null) && $canAccessPermission('delete_account');
    $canChangeStatusAccount = is_callable($canAccessPermission ?? null) && $canAccessPermission('change_status_account');
    $showActions = $canEditAccount || $canDeleteAccount;
?>
<div class="list-accounts-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH TÀI KHOẢN</h2>

                <div class="filter-wrap" id="accountFilter">
                    <button type="button" id="filterToggle" class="filter-btn btn btn-outline-secondary" title="Bộ lọc" aria-label="Bộ lọc" aria-expanded="false">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 5h16l-6 7v5l-4 2v-7L4 5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="filter-menu" id="filterBar" role="menu" aria-labelledby="filterToggle">
                        <div class="filter-form">
                            <label class="filter-label" for="filterRole">Vai trò</label>
                            <select id="filterRole" class="filter-select form-select">
                            <option value="">Tất cả</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= htmlspecialchars($r) ?>"><?= htmlspecialchars($r) ?></option>
                            <?php endforeach; ?>
                            </select>

                            <label class="filter-label" for="filterStatus">Trạng thái</label>
                            <select id="filterStatus" class="filter-select form-select">
                            <option value="">Tất cả</option>
                            <?php foreach ($statuses as $k=>$v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                            </select>

                            <label class="filter-label" for="filterGender">Giới tính</label>
                            <select id="filterGender" class="filter-select form-select">
                            <option value="">Tất cả</option>
                            <?php foreach ($genders as $k=>$v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                            </select>

                            <div class="filter-actions">
                                <button type="button" id="resetFilters" class="filter-clear btn btn-outline-secondary">Đặt lại</button>
                                <button type="button" id="applyFilters" class="filter-apply btn btn-primary">Lọc</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body card-body">
            <?php if (empty($accounts)): ?>
                <div class="empty-state">
                    <h3>Chưa có tài khoản nào</h3>
                    <p>Hãy tạo tài khoản để bắt đầu</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table table table-hover table-bordered align-middle" id="accountsTable">
                        <thead class="table-light">
                            <tr>
                                <th class="col-stt">STT</th>
                                <th class="col-fullname">TÊN NGƯỜI DÙNG</th>
                                <th class="col-username">TÊN TÀI KHOẢN</th>
                                <th class="col-gender">GIỚI TÍNH</th>
                                <th class="col-email">EMAIL TÀI KHOẢN</th>
                                <th class="col-phone">SỐ ĐIỆN THOẠI</th>
                                <th class="col-role">VAI TRÒ</th>
                                <th class="col-status">TRẠNG THÁI</th>
                                <?php if ($showActions): ?>
                                    <th class="col-action">THAO TÁC</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($accounts as $index => $a): ?>
                                <tr data-id="<?= htmlspecialchars((string) ($a['id'] ?? $a['username'] ?? '')) ?>" data-role="<?= htmlspecialchars($a['role'] ?? '') ?>" data-status="<?= htmlspecialchars($a['status'] ?? '') ?>" data-gender="<?= htmlspecialchars($a['gender'] ?? '') ?>">
                                    <td class="col-stt">0<?= $index + 1 ?></td>
                                    <td class="col-fullname"><?= htmlspecialchars($a['full_name']) ?></td>
                                    <td class="col-username"><?= htmlspecialchars($a['username']) ?></td>
                                    <td class="col-gender"><?= htmlspecialchars($genders[$a['gender']] ?? $a['gender']) ?></td>
                                    <td class="col-email"><?= htmlspecialchars(trim((string) ($a['email'] ?? '')) !== '' ? (string) $a['email'] : '-') ?></td>
                                    <td class="col-phone"><?= htmlspecialchars($a['phone']) ?></td>
                                    <td class="col-role"><?= htmlspecialchars($a['role']) ?></td>
                                    <td class="col-status">
                                        <?php if ($canChangeStatusAccount): ?>
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="_row_id" value="<?= htmlspecialchars((string) ($a['id'] ?? $a['username'] ?? '')) ?>" />
                                            <select name="status[<?= htmlspecialchars((string) ($a['id'] ?? $a['username'] ?? '')) ?>]" class="status-select form-select" onchange="updateStatusSelect(this)">
                                                <option value="active" <?= $a['status'] === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                                <option value="inactive" <?= $a['status'] === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                                            </select>
                                        </form>
                                        <?php else: ?>
                                            <?= htmlspecialchars($statuses[$a['status']] ?? $a['status']) ?>
                                        <?php endif; ?>
                                    </td>
                                    <?php if ($showActions): ?>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <?php if ($canEditAccount): ?>
                                            <button type="button" class="action-btn edit btn btn-outline-primary" title="Chỉnh sửa" aria-label="Chỉnh sửa tài khoản" onclick="editAccount(<?= htmlspecialchars(json_encode((string) ($a['id'] ?? $a['username'] ?? ''), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.5 3.5a2.121 2.121 0 1 1 3 3L18 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                            <?php endif; ?>
                                            <?php if ($canDeleteAccount): ?>
                                            <button type="button" class="action-btn delete btn btn-danger" title="Xóa" aria-label="Xóa tài khoản" onclick="showDeleteConfirm(<?= htmlspecialchars(json_encode((string) ($a['id'] ?? $a['username'] ?? ''), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>, 'tài khoản', <?= htmlspecialchars(json_encode((string) ($a['full_name'] ?? ''), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M19 7l-1 12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2l-1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3M9 11v6M15 11v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    <div class="pagination-info">
                        Hiển thị 1 - <?= min($items_per_page, $total_items) ?> của <?= $total_items ?> tài khoản
                    </div>
                    <div class="pagination mb-0">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=list_accounts&page_num=<?= $current_page - 1 ?>" class="pagination-btn prev page-link page-item">‹</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=list_accounts&page_num=<?= $i ?>" class="pagination-btn page-link page-item <?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=list_accounts&page_num=<?= $current_page + 1 ?>" class="pagination-btn next page-link page-item">›</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .list-accounts-page {
        display: grid;
        gap: 0;
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
        position: relative;
        z-index: 40;
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
        border-radius: 8px 8px 0 0;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .filter-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .filter-btn {
        width: 32px;
        height: 32px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #ffffff;
        color: #0f2a5a;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
    }

    .filter-btn:hover {
        background: #f8fafc;
        color: #0b1f45;
    }

    .filter-btn svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
    }

    .filter-menu {
        position: absolute;
        top: calc(100% + 6px);
        right: 0;
        z-index: 1000;
        display: none;
        width: 300px;
        padding: 12px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 8px 20px rgba(15, 42, 90, 0.12);
    }

    .filter-wrap.open .filter-menu {
        display: block;
    }

    .filter-form {
        display: grid;
        gap: 8px;
    }

    .panel-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f2a5a;
        margin: 0;
    }

    .panel-body {
        padding: 0;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
        color: #9ca3af;
    }

    .empty-state svg {
        color: #d1d5db;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 16px;
        font-weight: 600;
        color: #6b7280;
        margin: 0 0 8px 0;
    }

    .empty-state p {
        font-size: 13px;
        color: #9ca3af;
        margin: 0;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .data-table thead {
        background: #f8f9fa;
        border-bottom: 1px solid #e5e7eb;
    }

    .data-table th {
        padding: 12px 14px;
        text-align: center;
        font-weight: 700;
        color: #0f2a5a;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        font-size: 11px;
        border-right: 1px solid #d1d5db;
    }

    .data-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.2s;
    }

    .data-table tbody tr:nth-child(odd) {
        background-color: #f9fafb;
    }

    .data-table tbody tr:hover {
        background-color: #f0f1f3;
    }

    .data-table td {
        padding: 12px 14px;
        color: #1f2937;
        text-align: center;
        border-right: 1px solid #e5e7eb;
    }

    .col-stt {
        width: 35px;
    }

    .col-fullname {
        width: 18%;
    }

    .col-username {
        width: 12%;
    }

    .col-gender {
        width: 7%;
        white-space: nowrap;
    }

    .col-phone {
        width: 10%;
        white-space: nowrap;
    }

    .col-role {
        width: 9%;
        white-space: nowrap;
    }

    .col-email {
        width: 16%;
    }

    .col-status {
        width: 12%;
        white-space: nowrap;
    }

    .col-action {
        width: 10%;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge svg {
        width: 10px;
        height: 10px;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s;
        padding: 0;
    }

    .action-btn svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
    }

    .action-btn:hover {
        border-color: #d1d5db;
        background: #f9fafb;
    }

    .action-btn.edit {
        color: #1d4ed8;
    }

    .action-btn.edit:hover {
        background: #eff6ff;
    }

    .action-btn.delete {
        color: #dc2626;
    }

    .action-btn.delete:hover {
        background: #fef2f2;
    }

    .pagination-container {
        padding: 16px 14px;
        border-top: 1px solid #e8ecf3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #6b7280;
    }

    .pagination {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .pagination-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #ffffff;
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .pagination-btn:hover {
        border-color: #d1d5db;
        background: #f9fafb;
        color: #4b5563;
    }

    .pagination-btn.active {
        background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%);
        border-color: #0f2a5a;
        color: #ffffff;
    }

    .pagination-btn.prev,
    .pagination-btn.next {
        min-width: auto;
        padding: 0 8px;
    }

    @media (max-width: 1024px) {
        .col-email,
        .col-phone,
        .col-role {
            width: auto;
        }

        .data-table {
            font-size: 12px;
        }

        .data-table th,
        .data-table td {
            padding: 10px 12px;
        }
    }

    @media (max-width: 768px) {
        .filter-menu {
            right: -4px;
            width: min(300px, calc(100vw - 48px));
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .data-table {
            min-width: 1000px;
        }
    }

    .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #0f2a5a;
        margin: 0;
    }

    .filter-select {
        min-height: 36px;
        font-size: 13px;
        border-radius: 8px;
        border-color: #e5e7eb;
    }

    .filter-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        padding-top: 6px;
    }

    .filter-clear,
    .filter-apply {
        font-size: 13px;
        font-weight: 700;
        border-radius: 8px;
        padding: 7px 12px;
    }

    /* Status select styling (same as list_major) */
    .status-select { padding:6px 12px 6px 8px; border-radius:12px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; color:#0f2a5a; appearance:none; -webkit-appearance:none; font-weight:700; padding-right:36px; }
    .status-select option { color:#0f2a5a; }
    .status-select.active { background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%23065546' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg, #bbf7d0, #34d399); background-size:12px, auto; color:#065f46; border-color:#34d399; font-weight:700; }
    .status-select.inactive { background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%237f1d1d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg, #fed7d7, #f87171); background-size:12px, auto; color:#7f1d1d; border-color:#f87171; font-weight:700; }
</style>

<script>
    (function() {
        var filterWrap = document.getElementById('accountFilter');
        var filterToggle = document.getElementById('filterToggle');

        if (!filterWrap || !filterToggle) {
            return;
        }

        filterToggle.addEventListener('click', function(event) {
            event.stopPropagation();
            var isOpen = filterWrap.classList.toggle('open');
            filterToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function(event) {
            if (!filterWrap.contains(event.target)) {
                filterWrap.classList.remove('open');
                filterToggle.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                filterWrap.classList.remove('open');
                filterToggle.setAttribute('aria-expanded', 'false');
            }
        });
    })();

    function filterAccounts() {
        var role = document.getElementById('filterRole').value;
        var status = document.getElementById('filterStatus').value;
        var gender = document.getElementById('filterGender').value;

        var rows = document.querySelectorAll('#accountsTable tbody tr');
        rows.forEach(function(r){
            var rRole = r.getAttribute('data-role');
            var rStatus = r.getAttribute('data-status');
            var rGender = r.getAttribute('data-gender');

            var show = true;
            if (role && rRole !== role) show = false;
            if (status && rStatus !== status) show = false;
            if (gender && rGender !== gender) show = false;

            r.style.display = show ? '' : 'none';
        });
    }

    function updateStatusSelect(el){
        var val = el.value;
        el.classList.remove('active','inactive');
        el.classList.add(val === 'active' ? 'active' : 'inactive');
        el.form.submit();
    }

    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('.status-select').forEach(function(s){
            var val = s.value;
            s.classList.add(val === 'active' ? 'active' : 'inactive');
        });
    });

    document.getElementById('applyFilters').addEventListener('click', function(e){ e.preventDefault(); filterAccounts(); });
    document.getElementById('resetFilters').addEventListener('click', function(e){ e.preventDefault(); document.getElementById('filterRole').value=''; document.getElementById('filterStatus').value=''; document.getElementById('filterGender').value=''; filterAccounts(); });

    function editAccount(username){ window.location.href = '?page=edit_account&username=' + encodeURIComponent(username); }

    
</script>

<?php include __DIR__ . '/confirm/confirm_delete_modal.php'; ?>
