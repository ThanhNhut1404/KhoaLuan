<?php
    $semesters = $semesters ?? [];
    $pagination = $pagination ?? [
        'current_page' => 1,
        'total_items' => count($semesters),
        'items_per_page' => 10,
        'total_pages' => 1,
        'from' => empty($semesters) ? 0 : 1,
        'to' => count($semesters),
    ];
    $current_page = (int) ($pagination['current_page'] ?? 1);
    $total_items = (int) ($pagination['total_items'] ?? count($semesters));
    $items_per_page = (int) ($pagination['items_per_page'] ?? 10);
    $total_pages = (int) ($pagination['total_pages'] ?? 1);
    $emptyMessage = $emptyMessage ?? 'Chưa có học kỳ nào.';
    $paginationUrl = static function (int $pageNum): string {
        $params = $_GET;
        $params['page'] = 'list_semester';
        $params['page_num'] = $pageNum;
        return '?' . http_build_query($params);
    };
?>

<div class="list-semester-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH HỌC KỲ</h2>
            </div>
        </div>

        <div class="panel-body card-body">
            <?php if (empty($semesters)): ?>
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 13h6M9 17h3M5 21h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <h3><?= htmlspecialchars($emptyMessage) ?></h3>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="col-stt">STT</th>
                                <th class="col-name">TÊN HỌC KỲ</th>
                                <th class="col-time">THỜI GIAN</th>
                                <th class="col-year">NIÊN KHÓA</th>
                                <th class="col-status">TRẠNG THÁI</th>
                                <th class="col-action">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($semesters as $index => $semester): ?>
                                <?php $rowNumber = (($current_page - 1) * $items_per_page) + $index + 1; ?>
                                <tr data-id="<?= htmlspecialchars($semester['id'] ?? '') ?>">
                                    <td class="col-stt"><?= str_pad((string) $rowNumber, 2, '0', STR_PAD_LEFT) ?></td>
                                    <td class="col-name"><?= htmlspecialchars($semester['name'] ?? '') ?></td>
                                    <td class="col-time">
                                        <?= htmlspecialchars($semester['start_date'] ?? '') ?> - <?= htmlspecialchars($semester['end_date'] ?? '') ?>
                                    </td>
                                    <td class="col-year"><?= htmlspecialchars($semester['academic_year'] ?? '') ?></td>
                                    <td class="col-status">
                                        <span class="status-badge status-<?= htmlspecialchars(strtolower(str_replace(' ', '-', $semester['status'] ?? ''))) ?>">
                                            <?= htmlspecialchars($semester['status'] ?? '') ?>
                                        </span>
                                    </td>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <a class="action-btn edit btn btn-outline-primary" title="Chỉnh sửa" href="?page=edit_semester&id=<?= (int) ($semester['id'] ?? 0) ?>">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.5 3.5a2.121 2.121 0 1 1 3 3L18 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                            <button type="button" class="action-btn delete btn btn-danger" title="Xóa" onclick="showSemesterDeleteConfirm(<?= (int) ($semester['id'] ?? 0) ?>, <?= htmlspecialchars(json_encode((string) ($semester['name'] ?? ''), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>)">
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
                        Hiển thị <?= (int) ($pagination['from'] ?? 0) ?> - <?= (int) ($pagination['to'] ?? 0) ?> của <?= $total_items ?> học kỳ
                    </div>
                    <div class="pagination mb-0">
                        <?php if ($current_page > 1): ?>
                            <a href="<?= htmlspecialchars($paginationUrl(1)) ?>" class="pagination-btn first page-link page-item">&lt;&lt;</a>
                            <a href="<?= htmlspecialchars($paginationUrl($current_page - 1)) ?>" class="pagination-btn prev page-link page-item">&lt;</a>
                        <?php else: ?>
                            <span class="pagination-btn first page-link page-item disabled">&lt;&lt;</span>
                            <span class="pagination-btn prev page-link page-item disabled">&lt;</span>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="<?= htmlspecialchars($paginationUrl($i)) ?>" class="pagination-btn page-link page-item <?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="<?= htmlspecialchars($paginationUrl($current_page + 1)) ?>" class="pagination-btn next page-link page-item">&gt;</a>
                            <a href="<?= htmlspecialchars($paginationUrl($total_pages)) ?>" class="pagination-btn last page-link page-item">&gt;&gt;</a>
                        <?php else: ?>
                            <span class="pagination-btn next page-link page-item disabled">&gt;</span>
                            <span class="pagination-btn last page-link page-item disabled">&gt;&gt;</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<form id="semesterDeleteForm" method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '?page=list_semester') ?>" style="display:none;">
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="id" id="semesterDeleteId" value="" />
</form>

<style>
    .list-semester-page { padding: 24px; }
    .page-panel { background: #fff; border: 1px solid #e8ecf3; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden; }
    .panel-header { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .header-content { display:flex; justify-content:space-between; align-items:center; gap:12px; }
    .panel-title { font-size:14px; font-weight:700; color:#0f2a5a; margin:0; }
    .btn-create { padding:8px 14px; background:linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); color:#fff; border-radius:6px; text-decoration:none; font-weight:700; }
    .panel-body { padding:0; }
    .table-wrapper { overflow-x:auto; }
    .data-table { width:100%; border-collapse:collapse; table-layout:fixed; font-size:13px; }
    .data-table thead { background:#f8f9fa; border-bottom:1px solid #e5e7eb; }
    .data-table th { padding:12px 14px; text-align:center; font-weight:700; color:#0f2a5a; text-transform:uppercase; font-size:11px; border-right:1px solid #d1d5db; }
    .data-table tbody tr { border-bottom:1px solid #f3f4f6; transition:background-color .2s; }
    .data-table tbody tr:nth-child(odd),
    .data-table tbody tr:nth-child(odd) td { background:#ffffff; }
    .data-table tbody tr:nth-child(even),
    .data-table tbody tr:nth-child(even) td { background:#f1f5f9; }
    .data-table tbody tr:hover,
    .data-table tbody tr:hover td { background:#e9edf3; }
    .data-table tbody tr:hover { background:#f0f1f3; }
    .data-table td { padding:12px 14px; color:#1f2937; text-align:center; border-right:1px solid #e5e7eb; }
    .col-stt { width:5%; }
    .col-name { width:30%; text-align:left; }
    .col-time { width:25%; }
    .col-year { width:15%; }
    .col-status { width:10%; }
    .col-action { width:10%; }
    
    .status-badge { display:inline-block; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:600; white-space:nowrap; }
    .status-sắp-tới { background:#e0f2fe; color:#0369a1; }
    .status-đang-diễn-ra { background:#dcfce7; color:#15803d; }
    .status-đã-hoàn-thành { background:#f3f4f6; color:#4b5563; }
    
    .action-group { display:flex; gap:8px; justify-content:center; }
    .action-btn { width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:0; }
    .action-btn.edit { color:#1d4ed8; }
    .action-btn.delete { color:#dc2626; }
    .pagination-container { padding:16px 14px; border-top:1px solid #e8ecf3; display:flex; justify-content:space-between; align-items:center; font-size:12px; color:#6b7280; }
    .pagination { display:flex; gap:6px; align-items:center; }
    .pagination-btn { display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; color:#6b7280; font-weight:600; text-decoration:none; }
    .pagination-btn.active { background:linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); color:#fff; border-color:#0f2a5a; }
    .pagination-btn.disabled { opacity:.45; cursor:not-allowed; pointer-events:none; background:#f9fafb; color:#9ca3af; }
    @media (max-width:768px) { .data-table { min-width:900px; } }
    .empty-state { padding:42px 0; text-align:center; color:#334155; }
    .empty-state svg { margin-bottom:16px; color:#0f2a5a; }
    .empty-state h3 { margin-bottom:8px; font-size:18px; font-weight:700; }
    .empty-state p { margin:0; color:#64748b; }
</style>

<script>
    function showSemesterDeleteConfirm(id, name) {
        showDeleteConfirm(id, 'học kỳ', name);
    }
</script>

<?php include __DIR__ . '/confirm/confirm_delete_modal.php'; ?>
