<?php
    // Sample data - replace with actual database query
    $years = $years ?? [
        [
            'id' => 1,
            'name' => '2023 - 2024',
            'start_date' => '05/09/2023',
            'end_date' => '30/06/2024',
            'semesters' => 2,
            'status' => 'completed',
            'status_label' => 'Đã hoàn thành'
        ],
        [
            'id' => 2,
            'name' => '2024 - 2025',
            'start_date' => '05/09/2024',
            'end_date' => '30/06/2025',
            'semesters' => 2,
            'status' => 'active',
            'status_label' => 'Đang hoạt động'
        ],
        [
            'id' => 3,
            'name' => '2022 - 2023',
            'start_date' => '05/09/2022',
            'end_date' => '30/06/2023',
            'semesters' => 2,
            'status' => 'completed',
            'status_label' => 'Đã kết thúc'
        ]
    ];

    $current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    $total_items = count($years);
    $items_per_page = 3;
    $total_pages = ceil($total_items / $items_per_page);
?>

<div class="list-year-page">
    <div class="page-panel">
        <div class="panel-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH NIÊN KHÓA</h2>
            </div>
        </div>

        <div class="panel-body">
            <?php if (empty($years)): ?>
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 13h6M9 17h3M5 21h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <h3>Chưa có niên khóa nào</h3>
                    <p>Hãy tạo niên khóa đầu tiên để bắt đầu</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="col-stt">STT</th>
                                <th class="col-name">TÊN NIÊN KHÓA</th>
                                <th class="col-time">THỜI GIAN</th>
                                <th class="col-semester">SỐ HỌC KỲ</th>
                                <th class="col-status">TRẠNG THÁI</th>
                                <th class="col-action">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($years as $index => $year): ?>
                                <tr>
                                    <td class="col-stt">
                                        0<?= $index + 1 ?>
                                    </td>
                                    <td class="col-name">
                                        <?= htmlspecialchars($year['name']) ?>
                                    </td>
                                    <td class="col-time">
                                        <?= $year['start_date'] ?> - <?= $year['end_date'] ?>
                                    </td>
                                    <td class="col-semester">
                                        <?= $year['semesters'] ?>
                                    </td>
                                    <td class="col-status">
                                        <span class="status-badge status-<?= $year['status'] ?>">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            <?= $year['status_label'] ?>
                                        </span>
                                    </td>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <button class="action-btn edit" title="Chỉnh sửa" onclick="editYear(<?= $year['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.5 3.5a2.121 2.121 0 1 1 3 3L18 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                            <button class="action-btn delete" title="Xóa" onclick="deleteYear(<?= $year['id'] ?>)">
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

                <!-- Pagination -->
                <div class="pagination-container">
                    <div class="pagination-info">
                        Hiển thị 1 - <?= min($items_per_page, $total_items) ?> của <?= $total_items ?> niên khóa
                    </div>
                    <div class="pagination">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=list_year&page_num=<?= $current_page - 1 ?>" class="pagination-btn prev">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=list_year&page_num=<?= $i ?>" class="pagination-btn <?= $i === $current_page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=list_year&page_num=<?= $current_page + 1 ?>" class="pagination-btn next">
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
    .list-year-page {
        display: grid;
        gap: 0;
        padding: 24px;
    }

    .page-header {
        margin-bottom: 0;
    }

    .page-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f2a5a;
        text-transform: none;
        letter-spacing: 0.6px;
        margin: 0 0 4px 0;
    }

    .page-subtitle {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
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

    .btn-create {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%);
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-create:hover {
        background: linear-gradient(180deg, #0d2449 0%, #091a3d 100%);
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
        width: 45px;
        text-align: center;
    }

    .col-name {
        width: 20%;
        text-align: center;
    }

    .col-time {
        width: 24%;
        text-align: center;
        white-space: nowrap;
    }

    .col-semester {
        width: 12%;
        text-align: center;
    }

    .col-status {
        width: 18%;
        text-align: center;
    }

    .col-action {
        width: 10%;
        text-align: center;
    }

    .stt-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        color: #0f2a5a;
    }

    .year-info {
        font-weight: 600;
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
        .col-time {
            width: 20%;
        }

        .col-status {
            width: 18%;
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
            min-width: 800px;
        }

        .header-content {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }

        .btn-create {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    function editYear(id) {
        // Implement edit functionality
        alert('Chỉnh sửa niên khóa #' + id);
    }

    function deleteYear(id) {
        if (confirm('Bạn có chắc chắn muốn xóa niên khóa này?')) {
            // Implement delete functionality
            alert('Xóa niên khóa #' + id);
        }
    }
</script>
