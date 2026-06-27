<?php
    $years = $years ?? [];
    $statusOptions = $statusOptions ?? [
        ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
        ['value' => 'Đang hoạt động', 'label' => 'Đang hoạt động'],
        ['value' => 'Đã hoàn thành', 'label' => 'Đã hoàn thành'],
    ];
    $pagination = $pagination ?? [
        'current_page' => 1,
        'total_items' => count($years),
        'items_per_page' => 10,
        'total_pages' => 1,
        'from' => empty($years) ? 0 : 1,
        'to' => count($years),
    ];

    $statusClass = function (string $label): string {
        return match ($label) {
            'Sắp diễn ra' => 'upcoming',
            'Đang hoạt động' => 'active',
            'Đã hoàn thành' => 'completed',
            default => 'unknown',
        };
    };
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
                    <h3>Chưa có niên khóa nào.</h3>
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
                                <?php
                                    $rowNumber = (($pagination['current_page'] - 1) * $pagination['items_per_page']) + $index + 1;
                                    $currentStatus = (string) ($year['status'] ?? '');
                                    $currentStatusLabel = $year['status_label'] ?? '--';
                                    $currentStatusClass = $statusClass($currentStatusLabel);
                                ?>
                                <tr data-id="<?= (int) $year['id'] ?>">
                                    <td class="col-stt"><?= str_pad((string) $rowNumber, 2, '0', STR_PAD_LEFT) ?></td>
                                    <td class="col-name"><?= htmlspecialchars($year['name'] ?? '--') ?></td>
                                    <td class="col-time"><?= htmlspecialchars($year['time'] ?? '--') ?></td>
                                    <td class="col-semester"><?= ($year['semesters'] === null || $year['semesters'] === '') ? '--' : htmlspecialchars((string) $year['semesters']) ?></td>
                                    <td class="col-status">
                                        <form method="POST" class="inline-form">
                                            <input type="hidden" name="action" value="update_status" />
                                            <input type="hidden" name="year_id" value="<?= (int) $year['id'] ?>" />
                                            <select name="status" class="status-select <?= htmlspecialchars($currentStatusClass) ?>" onchange="this.form.submit()">
                                                <?php if ($currentStatus === ''): ?>
                                                    <option value="" selected disabled>--</option>
                                                <?php endif; ?>
                                                <?php foreach ($statusOptions as $option): ?>
                                                    <option value="<?= htmlspecialchars($option['value']) ?>" <?= $currentStatus === $option['value'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($option['label']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <form method="POST" class="inline-form">
                                                <input type="hidden" name="action" value="edit" />
                                                <input type="hidden" name="year_id" value="<?= (int) $year['id'] ?>" />
                                                <button class="action-btn edit" type="submit" title="Chỉnh sửa" aria-label="Chỉnh sửa">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M15.5 3.5a2.121 2.121 0 1 1 3 3L18 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <button
                                                class="action-btn delete"
                                                type="button"
                                                title="Xóa"
                                                aria-label="Xóa"
                                                onclick="showYearDeleteConfirm(<?= (int) $year['id'] ?>)"
                                            >
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
                        Hiển thị <?= (int) $pagination['from'] ?> - <?= (int) $pagination['to'] ?> của <?= (int) $pagination['total_items'] ?> niên khóa
                    </div>
                    <?php if ($pagination['total_pages'] > 1): ?>
                        <div class="pagination">
                            <?php if ($pagination['current_page'] > 1): ?>
                                <a href="?page=list_year&page_num=<?= $pagination['current_page'] - 1 ?>" class="pagination-btn prev" aria-label="Trang trước">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <a href="?page=list_year&page_num=<?= $i ?>" class="pagination-btn <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                <a href="?page=list_year&page_num=<?= $pagination['current_page'] + 1 ?>" class="pagination-btn next" aria-label="Trang sau">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal-overlay" id="yearDeleteModal" aria-hidden="true">
    <div class="modal-card year-delete-card" role="dialog" aria-modal="true" aria-labelledby="yearDeleteTitle">
        <div class="modal-header">
            <span class="modal-title" id="yearDeleteTitle">Xác nhận xóa</span>
            <button class="modal-close" type="button" aria-label="Đóng" onclick="hideYearDeleteConfirm()">✕</button>
        </div>
        <div class="modal-body">
            <p class="confirm-text">Bạn có chắc chắn muốn xóa niên khóa này không?</p>
        </div>
        <form id="yearDeleteForm" method="POST" class="modal-actions">
            <input type="hidden" name="action" value="delete" />
            <input type="hidden" name="year_id" id="deleteYearId" value="" />
            <button class="action-btn secondary" type="button" onclick="hideYearDeleteConfirm()">Hủy</button>
            <button class="action-btn primary" type="submit">Đồng ý</button>
        </form>
    </div>
</div>

<style>
    .list-year-page {
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

    .data-table th:last-child,
    .data-table td:last-child {
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

    .col-stt { width: 45px; }
    .col-name { width: 20%; }
    .col-time { width: 24%; white-space: nowrap; }
    .col-semester { width: 12%; }
    .col-status { width: 18%; }
    .col-action { width: 10%; }

    .inline-form {
        display: inline-flex;
        margin: 0;
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
        text-decoration: none;
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

    .data-table .status-select {
        min-width: 148px;
        padding: 6px 36px 6px 10px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 13px;
        color: #0f2a5a;
        appearance: none;
        -webkit-appearance: none;
        font-weight: 700;
        background-position: right 10px center;
        background-repeat: no-repeat;
    }

    .data-table .status-select.upcoming {
        background: linear-gradient(90deg, #fef3c7, #fde68a);
        color: #92400e;
        border-color: #fbbf24;
    }

    .data-table .status-select.active {
        background: linear-gradient(90deg, #bbf7d0, #34d399);
        color: #065f46;
        border-color: #34d399;
    }

    .data-table .status-select.completed {
        background: linear-gradient(90deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        border-color: #93c5fd;
    }

    .data-table .status-select.unknown {
        background: #f3f4f6;
        color: #6b7280;
    }

    .data-table .status-select:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.12);
    }

    #yearDeleteModal {
        display: none;
    }

    #yearDeleteModal.active {
        display: grid;
        place-items: center;
        position: fixed;
        inset: 0;
        z-index: 1200;
        background: rgba(15, 23, 42, 0.35);
    }

    .year-delete-card {
        width: min(520px, calc(100% - 32px));
    }

    #yearDeleteModal .modal-body {
        padding: 18px 24px;
    }

    #yearDeleteModal .confirm-text {
        font-size: 15px;
        line-height: 1.55;
        font-weight: 800;
        color: #b91c1c;
        margin: 0;
        text-align: center;
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: normal;
    }

    #yearDeleteModal .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        padding: 0 18px 18px;
    }

    #yearDeleteModal .action-btn {
        width: auto;
        height: auto;
        white-space: nowrap;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
    }

    #yearDeleteModal .action-btn.secondary {
        background: #f3f4f6;
        border-color: #d1d5db;
        color: #0f2a5a;
    }

    #yearDeleteModal .action-btn.primary {
        background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%);
        border-color: #0f2a5a;
        color: #ffffff;
    }

    @media (max-width: 768px) {
        .data-table {
            min-width: 800px;
        }

        .pagination-container {
            align-items: flex-start;
            flex-direction: column;
            gap: 12px;
        }
    }
</style>

<script>
    function showYearDeleteConfirm(id) {
        var modal = document.getElementById('yearDeleteModal');
        var input = document.getElementById('deleteYearId');
        if (!modal || !input) return;

        input.value = id;
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
    }

    function hideYearDeleteConfirm() {
        var modal = document.getElementById('yearDeleteModal');
        var input = document.getElementById('deleteYearId');
        if (!modal || !input) return;

        input.value = '';
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }
</script>
