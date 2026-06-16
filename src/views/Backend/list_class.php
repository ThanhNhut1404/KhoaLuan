<?php
    $classes = $classes ?? [
        [
            'id' => 1,
            'code' => 'CNTT01',
            'name' => 'Công nghệ thông tin A',
            'department' => 'Khoa CNTT',
            'academic_year' => '2023 - 2024',
            'major' => 'Công nghệ Phần mềm',
            'advisor' => 'Thầy Nguyễn Văn A',
            'capacity' => 35,
            'status' => 'active',
            'status_label' => 'Đang diễn ra'
        ],
        [
            'id' => 2,
            'code' => 'DT01',
            'name' => 'Điện tử 1',
            'department' => 'Khoa Điện tử',
            'academic_year' => '2024 - 2025',
            'major' => 'Hệ thống nhúng',
            'advisor' => 'Cô Trần Thị B',
            'capacity' => 28,
            'status' => 'upcoming',
            'status_label' => 'Sắp tới'
        ],
        [
            'id' => 3,
            'code' => 'MX01',
            'name' => 'Mạng máy tính 1',
            'department' => 'Khoa CNTT',
            'academic_year' => '2023 - 2024',
            'major' => 'Mạng và Truyền thông',
            'advisor' => 'Thầy Phạm Văn C',
            'capacity' => 32,
            'status' => 'completed',
            'status_label' => 'Đã hoàn thành'
        ]
    ];

    $current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    $total_items = count($classes);
    $items_per_page = 10;
    $total_pages = ceil($total_items / $items_per_page);
?>

<div class="list-class-page">
    <div class="page-panel">
        <div class="panel-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH LỚP HỌC</h2>
            </div>
        </div>

        <div class="panel-body">
            <?php if (empty($classes)): ?>
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 13h6M9 17h3M5 21h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <h3>Chưa có lớp học nào</h3>
                    <p>Hãy tạo lớp học đầu tiên để bắt đầu</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="col-stt">STT</th>
                                <th class="col-code">MÃ LỚP</th>
                                <th class="col-name">TÊN LỚP</th>
                                <th class="col-department">KHOA</th>
                                <th class="col-year">NIÊN KHÓA</th>
                                <th class="col-major">CHUYÊN NGÀNH</th>
                                <th class="col-advisor">CỐ VẤN</th>
                                <th class="col-capacity">SỐ LƯỢNG</th>
                                <th class="col-status">TRẠNG THÁI</th>
                                <th class="col-action">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($classes as $index => $class): ?>
                                <tr>
                                    <td class="col-stt">0<?= $index + 1 ?></td>
                                    <td class="col-code"><?= htmlspecialchars($class['code']) ?></td>
                                    <td class="col-name"><?= htmlspecialchars($class['name']) ?></td>
                                    <td class="col-department"><?= htmlspecialchars($class['department']) ?></td>
                                    <td class="col-year"><?= htmlspecialchars($class['academic_year']) ?></td>
                                    <td class="col-major"><?= htmlspecialchars($class['major']) ?></td>
                                    <td class="col-advisor"><?= htmlspecialchars($class['advisor']) ?></td>
                                    <td class="col-capacity"><?= htmlspecialchars($class['capacity']) ?></td>
                                    <td class="col-status">
                                        <span class="status-badge status-<?= $class['status'] ?>">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            <?= htmlspecialchars($class['status_label']) ?>
                                        </span>
                                    </td>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <button class="action-btn edit" title="Chỉnh sửa" onclick="editClass(<?= $class['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.5 3.5a2.121 2.121 0 1 1 3 3L18 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                            <button class="action-btn delete" title="Xóa" onclick="deleteClass(<?= $class['id'] ?>)">
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
                        Hiển thị 1 - <?= min($items_per_page, $total_items) ?> của <?= $total_items ?> lớp học
                    </div>
                    <div class="pagination">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=list_class&page_num=<?= $current_page - 1 ?>" class="pagination-btn prev">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=list_class&page_num=<?= $i ?>" class="pagination-btn <?= $i === $current_page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=list_class&page_num=<?= $current_page + 1 ?>" class="pagination-btn next">
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
    .list-class-page {
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

    .col-code {
        width: 10%;
        white-space: nowrap;
    }

    .col-name {
        width: 18%;
    }

    .col-department,
    .col-year,
    .col-major,
    .col-advisor {
        width: 14%;
    }

    .col-capacity {
        width: 8%;
        white-space: nowrap;
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
        .col-department,
        .col-year,
        .col-major,
        .col-advisor {
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
</style>

<script>
    function editClass(id) {
        console.log('Edit class:', id);
    }

    function deleteClass(id) {
        console.log('Delete class:', id);
    }
</script>
