<?php
    // Sample accounts - replace with DB query
    $accounts = $accounts ?? [
        ['id'=>1,'full_name'=>'Nguyễn Văn A','username'=>'nguyenvana','gender'=>'male','email'=>'a@example.com','phone'=>'0912345678','role'=>'Admin','status'=>'active','status_label'=>'Hoạt động'],
        ['id'=>2,'full_name'=>'Trần Thị B','username'=>'tranthib','gender'=>'female','email'=>'b@example.com','phone'=>'0987654321','role'=>'Giảng viên','status'=>'inactive','status_label'=>'Không hoạt động'],
        ['id'=>3,'full_name'=>'Lê Văn C','username'=>'levanc','gender'=>'male','email'=>'c@example.com','phone'=>'0901122334','role'=>'Sinh viên','status'=>'active','status_label'=>'Hoạt động']
    ];

    $roles = $roles ?? ['Admin','Giảng viên','Sinh viên'];
    $genders = ['male'=>'Nam','female'=>'Nữ','other'=>'Khác'];
    $statuses = ['active'=>'Hoạt động','inactive'=>'Không hoạt động'];

    if (session_status() === PHP_SESSION_NONE) session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status']) && is_array($_POST['status'])) {
        foreach ($_POST['status'] as $id => $newStatus) {
            foreach ($accounts as &$acct) {
                if ($acct['id'] == $id) {
                    $acct['status'] = $newStatus;
                    $acct['status_label'] = $newStatus === 'active' ? 'Hoạt động' : 'Không hoạt động';
                }
            }
            unset($acct);
        }
        $_SESSION['message'] = 'Cập nhật trạng thái thành công';
        $_SESSION['message_type'] = 'success';
    }

    $current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    $total_items = count($accounts);
    $items_per_page = 10;
    $total_pages = ceil($total_items / $items_per_page);
?>

<div class="list-accounts-page">
    <div class="page-panel">
        <div class="panel-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH TÀI KHOẢN</h2>

                <div class="actions-right">
                    <button id="filterToggle" class="btn-create filter-reset">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 5h18M6 12h12M10 19h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Bộ lọc
                    </button>
                </div>
            </div>

            <div id="filterBar" class="filter-bar" style="display:none;">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Vai trò</label>
                        <select id="filterRole" class="field-input">
                            <option value="">Tất cả</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= htmlspecialchars($r) ?>"><?= htmlspecialchars($r) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Trạng thái</label>
                        <select id="filterStatus" class="field-input">
                            <option value="">Tất cả</option>
                            <?php foreach ($statuses as $k=>$v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Giới tính</label>
                        <select id="filterGender" class="field-input">
                            <option value="">Tất cả</option>
                            <?php foreach ($genders as $k=>$v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group filter-actions">
                        <button id="resetFilters" class="btn-create filter-reset">Đặt lại</button>
                        <button id="applyFilters" class="btn-create filter-apply">Áp dụng</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <?php if (empty($accounts)): ?>
                <div class="empty-state">
                    <h3>Chưa có tài khoản nào</h3>
                    <p>Hãy tạo tài khoản để bắt đầu</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table" id="accountsTable">
                        <thead>
                            <tr>
                                <th class="col-stt">STT</th>
                                <th class="col-fullname">TÊN NGƯỜI DÙNG</th>
                                <th class="col-username">TÊN TÀI KHOẢN</th>
                                <th class="col-gender">GIỚI TÍNH</th>
                                <th class="col-email">EMAIL</th>
                                <th class="col-phone">SỐ ĐIỆN THOẠI</th>
                                <th class="col-role">VAI TRÒ</th>
                                <th class="col-status">TRẠNG THÁI</th>
                                <th class="col-action">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($accounts as $index => $a): ?>
                                <tr data-role="<?= htmlspecialchars($a['role']) ?>" data-status="<?= htmlspecialchars($a['status']) ?>" data-gender="<?= htmlspecialchars($a['gender']) ?>">
                                    <td class="col-stt">0<?= $index + 1 ?></td>
                                    <td class="col-fullname"><?= htmlspecialchars($a['full_name']) ?></td>
                                    <td class="col-username"><?= htmlspecialchars($a['username']) ?></td>
                                    <td class="col-gender"><?= htmlspecialchars($genders[$a['gender']] ?? $a['gender']) ?></td>
                                    <td class="col-email"><?= htmlspecialchars($a['email']) ?></td>
                                    <td class="col-phone"><?= htmlspecialchars($a['phone']) ?></td>
                                    <td class="col-role"><?= htmlspecialchars($a['role']) ?></td>
                                    <td class="col-status">
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="_row_id" value="<?= $a['id'] ?>" />
                                            <select name="status[<?= $a['id'] ?>]" class="status-select" onchange="updateStatusSelect(this)">
                                                <option value="active" <?= $a['status'] === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                                <option value="inactive" <?= $a['status'] === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <button class="action-btn edit" title="Chỉnh sửa" onclick="editAccount(<?= $a['id'] ?>)">✎</button>
                                            <button class="action-btn delete" title="Xóa" onclick="deleteAccount(<?= $a['id'] ?>)">🗑</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    <div class="pagination-info">
                        Hiển thị 1 - <?= min($items_per_page, $total_items) ?> của <?= $total_items ?> tài khoản
                    </div>
                    <div class="pagination">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=list_accounts&page_num=<?= $current_page - 1 ?>" class="pagination-btn prev">‹</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=list_accounts&page_num=<?= $i ?>" class="pagination-btn <?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=list_accounts&page_num=<?= $current_page + 1 ?>" class="pagination-btn next">›</a>
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
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
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
        background: #f3f4f6;
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

    .data-table th:last-child {
        border-right: none;
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

    .data-table td:last-child {
        border-right: none;
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

    .col-gender,
    .col-email,
    .col-phone,
    .col-role {
        width: 12%;
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
        .table-wrapper {
            overflow-x: auto;
        }

        .data-table {
            min-width: 1000px;
        }
    }

    /* Filter bar styles aligned with list_class look */
    .filter-bar {
        padding: 12px 16px;
        border-top: 1px solid #e8ecf3;
        background: #ffffff;
    }

    .filter-row {
        display: flex;
        gap: 12px;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex-shrink: 0;
    }

    .filter-label {
        font-weight: 700;
        font-size: 12px;
        color: #0f2a5a;
    }

    .filter-bar .field-input {
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 13px;
        height: 40px;
        box-sizing: border-box;
    }

    .filter-actions {
        display: flex;
        flex-direction: row;
        gap: 12px;
        align-items: center;
        flex-wrap: nowrap;
        justify-content: flex-start;
        flex-shrink: 0;
    }

    .btn-create.filter-apply,
    .btn-create.filter-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        min-width: 80px;
        padding: 0 10px;
        border-radius: 6px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        font-size: 13px;
    }

    .btn-create.filter-apply {
        background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%);
        color: #ffffff;
        border: 1px solid #0f2a5a;
    }

    .btn-create.filter-reset {
        background: #ffffff;
        color: #0f2a5a;
        border: 1px solid #e5e7eb;
    }

    /* Reduce select/input heights to match buttons */
    .filter-bar .field-input {
        padding: 8px 10px;
        height: 34px;
        border-radius: 8px;
        font-size: 13px;
    }

    /* Status select styling (same as list_major) */
    .status-select { padding:6px 12px 6px 8px; border-radius:12px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; color:#0f2a5a; appearance:none; -webkit-appearance:none; font-weight:700; padding-right:36px; }
    .status-select option { color:#0f2a5a; }
    .status-select.active { background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%23065546' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg, #bbf7d0, #34d399); background-size:12px, auto; color:#065f46; border-color:#34d399; font-weight:700; }
    .status-select.inactive { background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%237f1d1d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg, #fed7d7, #f87171); background-size:12px, auto; color:#7f1d1d; border-color:#f87171; font-weight:700; }
</style>

<script>
    document.getElementById('filterToggle').addEventListener('click', function(){
        var f = document.getElementById('filterBar');
        f.style.display = (f.style.display === 'none' || f.style.display === '') ? 'block' : 'none';
    });

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

    function editAccount(id){ window.location.href = '?page=edit_account&id=' + id; }
    function deleteAccount(id){ if(confirm('Bạn có chắc chắn muốn xóa tài khoản này?')) alert('Xóa tài khoản #' + id); }
</script>
