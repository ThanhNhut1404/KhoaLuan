<?php
    $activities = $activities ?? [];
    $current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    $total_items = count($activities);
    $items_per_page = 10;
    $total_pages = max(1, (int) ceil($total_items / $items_per_page));
    $canEditActivity = is_callable($canAccessPermission ?? null) && $canAccessPermission('edit_activity');
    $canDeleteActivity = is_callable($canAccessPermission ?? null) && $canAccessPermission('delete_activity');
    $canChangeStatusActivity = is_callable($canAccessPermission ?? null) && $canAccessPermission('change_status_activity');
    $showActions = $canEditActivity || $canDeleteActivity;
?>
<div class="list-activity-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH HOẠT ĐỘNG</h2>
            </div>
        </div>

        <div class="panel-body card-body">
            <?php if (empty($activities)): ?>
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 13h6M9 17h3M5 21h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <h3>Chưa có hoạt động nào</h3>
                    <p>Hãy tạo một hoạt động để bắt đầu</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="col-stt">STT</th>
                                <th class="col-name">TÊN HOẠT ĐỘNG</th>
                                <th class="col-time">GIỜ</th>
                                <th class="col-period">CA HOẠT ĐỘNG</th>
                                <th class="col-start">NGÀY BẮT ĐẦU</th>
                                <th class="col-end">NGÀY KẾT THÚC</th>
                                <th class="col-type">LOẠI HOẠT ĐỘNG</th>
                                <th class="col-level">CẤP HOẠT ĐỘNG</th>
                                <th class="col-bonus">ĐIỂM CỘNG</th>
                                <th class="col-capacity">SỐ LƯỢNG</th>
                                <th class="col-status">TRẠNG THÁI</th>
                                <?php if ($showActions): ?>
                                    <th class="col-action">THAO TÁC</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($activities as $index => $activity): ?>
                                <tr data-id="<?= $activity['id'] ?>">
                                    <td class="col-stt">0<?= $index + 1 ?></td>
                                    <td class="col-name"><?= htmlspecialchars($activity['name']) ?></td>
                                    <td class="col-time"><?= htmlspecialchars($activity['time']) ?></td>
                                    <td class="col-period"><?= htmlspecialchars($activity['period']) ?></td>
                                    <td class="col-start"><?= htmlspecialchars($activity['start_date']) ?></td>
                                    <td class="col-end"><?= htmlspecialchars($activity['end_date']) ?></td>
                                    <td class="col-type"><?= htmlspecialchars($activity['activity_type'] ?? '') ?></td>
                                    <td class="col-level"><?= htmlspecialchars($activity['activity_level'] ?? '') ?></td>
                                    <td class="col-bonus"><?= htmlspecialchars($activity['bonus_points']) ?></td>
                                    <td class="col-capacity"><?= htmlspecialchars($activity['capacity']) ?></td>
                                    <td class="col-status">
                                        <?php if ($canChangeStatusActivity): ?>
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="_row_id" value="<?= $activity['id'] ?>" />
                                            <select name="status[<?= $activity['id'] ?>]" class="status-select form-select" onchange="updateStatusSelect(this)">
                                                <option value="active" <?= $activity['status'] === 'active' ? 'selected' : '' ?>>Đang diễn ra</option>
                                                <option value="upcoming" <?= $activity['status'] === 'upcoming' ? 'selected' : '' ?>>Sắp tới</option>
                                                <option value="completed" <?= $activity['status'] === 'completed' ? 'selected' : '' ?>>Đã hoàn thành</option>
                                            </select>
                                        </form>
                                        <?php else: ?>
                                            <?= htmlspecialchars($activity['status'] ?? '') ?>
                                        <?php endif; ?>
                                    </td>
                                    <?php if ($showActions): ?>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <?php if ($canEditActivity): ?>
                                            <button class="action-btn edit btn btn-outline-primary" title="Chỉnh sửa" onclick="editActivity(<?= $activity['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.5 3.5a2.121 2.121 0 1 1 3 3L18 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                            <?php endif; ?>
                                            <?php if ($canDeleteActivity): ?>
                                            <button class="action-btn delete btn btn-danger" title="Xóa" onclick="showDeleteConfirm(<?= $activity['id'] ?>, 'hoạt động', <?= htmlspecialchars(json_encode((string) ($activity['name'] ?? ''), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>)">
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
                        Hiển thị 1 - <?= min($items_per_page, $total_items) ?> của <?= $total_items ?> hoạt động
                    </div>
                    <div class="pagination mb-0">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=list_activity&page_num=<?= $current_page - 1 ?>" class="pagination-btn prev page-link page-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=list_activity&page_num=<?= $i ?>" class="pagination-btn page-link page-item <?= $i === $current_page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=list_activity&page_num=<?= $current_page + 1 ?>" class="pagination-btn next page-link page-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .list-activity-page {
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

    .col-name {
        width: 18%;
    }

    .col-time,
    .col-period,
    .col-start,
    .col-end,
    .col-bonus,
    .col-capacity,
    .col-status {
        text-align: center;
        white-space: nowrap;
    }

    .col-time {
        width: 10%;
    }

    .col-period {
        width: 12%;
    }

    .col-start,
    .col-end {
        width: 12%;
    }

    .col-bonus,
    .col-capacity {
        width: 10%;
    }

    .col-status {
        width: 13%;
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

    .status-completed {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-upcoming {
        background: #fce7f3;
        color: #831843;
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
        .col-time,
        .col-period,
        .col-start,
        .col-end,
        .col-bonus,
        .col-capacity,
        .col-status {
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
            min-width: 900px;
        }
    }
    /* Status select styling */
    .status-select { padding:6px 12px 6px 8px; border-radius:12px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; color:#0f2a5a; appearance:none; -webkit-appearance:none; font-weight:700; padding-right:36px; }
    .status-select.option { color:#0f2a5a; }
    .status-select.active { background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%23065546' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg, #bbf7d0, #34d399); background-size:12px, auto; color:#065f46; border-color:#34d399; font-weight:700; }
    .status-select.upcoming { background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%23613b52' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg, #fde68a, #f59e0b); background-size:12px, auto; color:#92400e; border-color:#f59e0b; font-weight:700; }
    .status-select.completed { background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%231e40af' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg, #dbeafe, #bfdbfe); background-size:12px, auto; color:#1e40af; border-color:#93c5fd; font-weight:700; }
</style>

<script>
    function editActivity(id) {
        window.location.href = '?page=edit_activity&id=' + id;
    }

    function deleteActivity(id) {
        console.log('Delete activity:', id);
    }
    function updateStatusSelect(el){
        var val = el.value;
        el.classList.remove('active','upcoming','completed');
        el.classList.add(val);
        el.form.submit();
    }

    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('.status-select').forEach(function(s){
            var val = s.value;
            s.classList.add(val);
        });
    });
</script>
<?php include __DIR__ . '/confirm/confirm_delete_modal.php'; ?>
