<?php
    $activities = $activities ?? [
        [
            'id' => 1,
            'name' => 'Ngày hội khởi nghiệp',
            'time' => '09:00',
            'period' => 'Sáng',
            'start_date' => '15/07/2025',
            'end_date' => '15/07/2025',
            'activity_type' => 'Hội thảo',
            'activity_level' => 'Trường',
            'bonus_points' => 10,
            'capacity' => 120,
            'status' => 'active',
            'status_label' => 'Đang diễn ra'
        ],
        [
            'id' => 2,
            'name' => 'Tập huấn kỹ năng mềm',
            'time' => '13:30',
            'period' => 'Chiều',
            'start_date' => '22/07/2025',
            'end_date' => '22/07/2025',
            'activity_type' => 'Văn hóa',
            'activity_level' => 'Khoa',
            'bonus_points' => 8,
            'capacity' => 80,
            'status' => 'upcoming',
            'status_label' => 'Sắp tới'
        ],
        [
            'id' => 3,
            'name' => 'Chiến dịch tình nguyện',
            'time' => '18:00',
            'period' => 'Tối',
            'start_date' => '10/08/2025',
            'end_date' => '12/08/2025',
            'activity_type' => 'Tình nguyện',
            'activity_level' => 'Lớp',
            'bonus_points' => 15,
            'capacity' => 150,
            'status' => 'completed',
            'status_label' => 'Đã hoàn thành'
        ]
    ];

    if (session_status() === PHP_SESSION_NONE) session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status']) && is_array($_POST['status'])) {
        foreach ($_POST['status'] as $id => $newStatus) {
            foreach ($activities as &$act) {
                if ($act['id'] == $id) {
                    $act['status'] = $newStatus;
                    $act['status_label'] = $newStatus === 'active' ? 'Đang diễn ra' : ($newStatus === 'upcoming' ? 'Sắp tới' : 'Đã hoàn thành');
                }
            }
            unset($act);
        }
        $_SESSION['message'] = 'Cập nhật trạng thái thành công';
        $_SESSION['message_type'] = 'success';
    }

    $current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    $total_items = count($activities);
    $items_per_page = 10;
    $total_pages = ceil($total_items / $items_per_page);
?>

<div class="list-activity-page">
    <div class="page-panel">
        <div class="panel-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH HOẠT ĐỘNG</h2>
            </div>
        </div>

        <div class="panel-body">
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
                    <table class="data-table">
                        <thead>
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
                                <th class="col-action">THAO TÁC</th>
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
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="_row_id" value="<?= $activity['id'] ?>" />
                                            <select name="status[<?= $activity['id'] ?>]" class="status-select" onchange="updateStatusSelect(this)">
                                                <option value="active" <?= $activity['status'] === 'active' ? 'selected' : '' ?>>Đang diễn ra</option>
                                                <option value="upcoming" <?= $activity['status'] === 'upcoming' ? 'selected' : '' ?>>Sắp tới</option>
                                                <option value="completed" <?= $activity['status'] === 'completed' ? 'selected' : '' ?>>Đã hoàn thành</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <button class="action-btn edit" title="Chỉnh sửa" onclick="editActivity(<?= $activity['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.5 3.5a2.121 2.121 0 1 1 3 3L18 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                            <button class="action-btn delete" title="Xóa" onclick="showDeleteConfirm(<?= $activity['id'] ?>, 'hoạt động')">
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
                        Hiển thị 1 - <?= min($items_per_page, $total_items) ?> của <?= $total_items ?> hoạt động
                    </div>
                    <div class="pagination">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=list_activity&page_num=<?= $current_page - 1 ?>" class="pagination-btn prev">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=list_activity&page_num=<?= $i ?>" class="pagination-btn <?= $i === $current_page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=list_activity&page_num=<?= $current_page + 1 ?>" class="pagination-btn next">
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
