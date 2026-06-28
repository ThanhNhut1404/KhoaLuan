<?php
    // Sample data - replace with actual database query
    $majors = $majors ?? [
        ['id'=>1, 'code'=>'CNTT01', 'name'=>'Công nghệ thông tin', 'credits'=>120, 'department'=>'Khoa CNTT', 'status'=>'active', 'status_label'=>'Hoạt động'],
        ['id'=>2, 'code'=>'DTVT02', 'name'=>'Điện tử truyền thông', 'credits'=>130, 'department'=>'Khoa Điện tử', 'status'=>'inactive', 'status_label'=>'Không hoạt động'],
        ['id'=>3, 'code'=>'CK03', 'name'=>'Cơ khí', 'credits'=>140, 'department'=>'Khoa Cơ khí', 'status'=>'active', 'status_label'=>'Hoạt động']
    ];

    // Handle status updates submitted from the table (auto-submit on change)
    if (session_status() === PHP_SESSION_NONE) session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status']) && is_array($_POST['status'])) {
        foreach ($_POST['status'] as $id => $newStatus) {
            // TODO: persist $newStatus for $id to database via model
            // For now update the local $majors array so UI reflects change immediately
            foreach ($majors as &$m) {
                if ($m['id'] == $id) {
                    $m['status'] = $newStatus;
                    $m['status_label'] = $newStatus === 'active' ? 'Hoạt động' : 'Không hoạt động';
                }
            }
            unset($m);
        }
        $_SESSION['message'] = 'Cập nhật trạng thái thành công';
        $_SESSION['message_type'] = 'success';
    }

    $current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    $total_items = count($majors);
    $items_per_page = 6;
    $total_pages = ceil($total_items / $items_per_page);
?>

<div class="list-major-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
                <div class="header-content">
                <h2 class="panel-title">DANH SÁCH NGÀNH HỌC</h2>
            </div>
        </div>

        <div class="panel-body card-body">
            <?php if (empty($majors)): ?>
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 13h6M9 17h3M5 21h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <h3>Chưa có ngành học nào</h3>
                    <p>Hãy tạo ngành học đầu tiên để bắt đầu</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="col-stt">STT</th>
                                <th class="col-code">MÃ NGÀNH</th>
                                <th class="col-name">TÊN NGÀNH</th>
                                <th class="col-credits">SỐ TÍN CHỈ</th>
                                <th class="col-dept">KHOA TRỰC THUỘC</th>
                                <th class="col-status">TRẠNG THÁI</th>
                                <th class="col-action">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($majors as $index => $major): ?>
                                <tr data-id="<?= $major['id'] ?>">
                                    <td class="col-stt">0<?= $index + 1 ?></td>
                                    <td class="col-code"><?= htmlspecialchars($major['code']) ?></td>
                                    <td class="col-name"><?= htmlspecialchars($major['name']) ?></td>
                                    <td class="col-credits"><?= htmlspecialchars($major['credits']) ?></td>
                                    <td class="col-dept"><?= htmlspecialchars($major['department']) ?></td>
                                    <td class="col-status">
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="_row_id" value="<?= $major['id'] ?>" />
                                            <select name="status[<?= $major['id'] ?>]" class="status-select form-select" onchange="updateStatusSelect(this)">
                                                <option value="active" <?= $major['status'] === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                                <option value="inactive" <?= $major['status'] === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <button class="action-btn edit btn btn-outline-primary" title="Chỉnh sửa" onclick="editMajor(<?= $major['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.5 3.5a2.121 2.121 0 1 1 3 3L18 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                            <button class="action-btn delete btn btn-danger" title="Xóa" onclick="showDeleteConfirm(<?= $major['id'] ?>, 'ngành')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M19 7l-1 12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2l-1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3M9 11v6M15 11v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    <div class="pagination-info">
                        Hiển thị 1 - <?= min($items_per_page, $total_items) ?> của <?= $total_items ?> ngành học
                    </div>
                    <div class="pagination mb-0">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=list_major&page_num=<?= $current_page - 1 ?>" class="pagination-btn prev page-link page-item">«</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=list_major&page_num=<?= $i ?>" class="pagination-btn page-link page-item <?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=list_major&page_num=<?= $current_page + 1 ?>" class="pagination-btn next page-link page-item">»</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Reuse styles from list_year with adjusted columns */
    .list-major-page { padding: 24px; }
    .page-panel { background: #fff; border: 1px solid #e8ecf3; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden; }
    .panel-header { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .header-content { display:flex; justify-content:space-between; align-items:center; }
    .panel-title { font-size:14px; font-weight:700; color:#0f2a5a; margin:0; }
    .btn-create { padding:8px 14px; background:linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); color:#fff; border-radius:6px; text-decoration:none; font-weight:700 }
    .panel-body { padding:0; }
    .table-wrapper { overflow-x:auto; }
    .data-table { width:100%; border-collapse:collapse; font-size:13px; }
    .data-table thead { background:#f8f9fa; border-bottom:1px solid #e5e7eb; }
    .data-table th { padding:12px 14px; text-align:center; font-weight:700; color:#0f2a5a; text-transform:uppercase; font-size:11px; border-right:1px solid #d1d5db; }
    .data-table tbody tr { border-bottom:1px solid #f3f4f6; transition:background-color .2s; }
    .data-table tbody tr:nth-child(odd) { background:#f9fafb; }
    .data-table tbody tr:hover { background:#f0f1f3; }
    .data-table td { padding:12px 14px; color:#1f2937; text-align:center; border-right:1px solid #e5e7eb; }
    .col-stt { width:50px; }
    .col-code { width:12%; }
    .col-name { width:26%; }
    .col-credits { width:12%; }
    .col-dept { width:18%; }
    .col-status { width:16%; }
    .col-action { width:8%; }
    .status-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:999px; font-size:12px; font-weight:600; }
    .status-active { background:#d1fae5; color:#065f46; }
    .status-inactive { background:#fee2e2; color:#991b1b; }
    .action-group { display:flex; gap:8px; justify-content:center; }
    .action-btn { width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:0; }
    .action-btn.edit { color:#1d4ed8; }
    .action-btn.delete { color:#dc2626; }
    .pagination-container { padding:16px 14px; border-top:1px solid #e8ecf3; display:flex; justify-content:space-between; align-items:center; font-size:12px; color:#6b7280; }
    .pagination { display:flex; gap:6px; align-items:center; }
    .pagination-btn { display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; color:#6b7280; font-weight:600; text-decoration:none; }
    .pagination-btn.active { background:linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); color:#fff; border-color:#0f2a5a; }
    @media (max-width:768px) { .data-table { min-width:900px; } }

    /* Status select styling (matched to list_activity) */
    .data-table .status-select { padding:6px 12px 6px 8px; border-radius:12px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; color:#0f2a5a; appearance:none; -webkit-appearance:none; font-weight:700; padding-right:36px; background-position: right 10px center; background-repeat: no-repeat; }
    .data-table .status-select option { color:#0f2a5a; }
    .data-table .status-select.active { background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%23065546' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg, #bbf7d0, #34d399); background-size:12px, auto; color:#065f46; border-color:#34d399; font-weight:700; }
    .data-table .status-select.inactive { background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%237f1d1d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg, #fed7d7, #f87171); background-size:12px, auto; color:#7f1d1d; border-color:#f87171; font-weight:700; }
    .data-table .status-select:focus { outline: none; box-shadow: 0 0 0 3px rgba(52,211,153,0.12); }

    /* Removed status pill — select now shows status color directly */

    
</style>

<script>
    function editMajor(id) { window.location.href = '?page=edit_major&id=' + id; }

    

    function updateStatusSelect(el){
        var val = el.value;
        el.classList.remove('active','inactive');
        el.classList.add(val === 'active' ? 'active' : 'inactive');

        // submit the form after updating style
        el.form.submit();
    }

    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('.status-select').forEach(function(s){
            var val = s.value;
            s.classList.add(val === 'active' ? 'active' : 'inactive');
        });
    });
</script>

<?php include __DIR__ . '/confirm/confirm_delete_modal.php'; ?>
