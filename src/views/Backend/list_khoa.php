<?php
    $khoas = $khoas ?? [];
    $pagination = $pagination ?? [
        'current_page' => 1,
        'total_items' => count($khoas),
        'items_per_page' => 10,
        'total_pages' => 1,
        'from' => empty($khoas) ? 0 : 1,
        'to' => count($khoas),
    ];
    $current_page = (int) ($pagination['current_page'] ?? 1);
    $total_items = (int) ($pagination['total_items'] ?? count($khoas));
    $items_per_page = (int) ($pagination['items_per_page'] ?? 10);
    $total_pages = (int) ($pagination['total_pages'] ?? 1);
    $emptyMessage = $emptyMessage ?? 'Chưa có khoa/bộ môn nào.';
    $canEditKhoa = is_callable($canAccessPermission ?? null) && $canAccessPermission('edit_khoa');
    $canDeleteKhoa = is_callable($canAccessPermission ?? null) && $canAccessPermission('delete_khoa');
    $showActions = $canEditKhoa || $canDeleteKhoa;
    $paginationUrl = static function (int $pageNum): string {
        $params = $_GET;
        $params['page'] = 'list_khoa';
        $params['page_num'] = $pageNum;

        return '?' . http_build_query($params);
    };
?>

<div class="list-khoa-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH KHOA/BỘ MÔN</h2>
            </div>
        </div>

        <div class="panel-body card-body">
            <?php if (empty($khoas)): ?>
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
                                <th class="col-code">MÃ KHOA/BỘ MÔN</th>
                                <th class="col-name">TÊN KHOA/BỘ MÔN</th>
                                <th class="col-email">EMAIL</th>
                                <th class="col-phone">SỐ ĐIỆN THOẠI</th>
                                <?php if ($showActions): ?>
                                    <th class="col-action">THAO TÁC</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($khoas as $index => $k): ?>
                                <?php $rowNumber = (($current_page - 1) * $items_per_page) + $index + 1; ?>
                                <tr data-id="<?= htmlspecialchars($k['ma'] ?? '') ?>">
                                    <td class="col-stt"><?= str_pad((string) $rowNumber, 2, '0', STR_PAD_LEFT) ?></td>
                                    <td class="col-code"><?= htmlspecialchars($k['ten_viet_tat'] ?? '') ?></td>
                                    <td class="col-name"><?= htmlspecialchars($k['ten'] ?? '') ?></td>
                                    <td class="col-email"><?= htmlspecialchars($k['email'] ?? '') ?></td>
                                    <td class="col-phone"><?= htmlspecialchars($k['phone'] ?? '') ?></td>
                                    <?php if ($showActions): ?>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <?php if ($canEditKhoa): ?>
                                            <a class="action-btn edit btn btn-outline-primary" title="Chỉnh sửa" href="?page=edit_khoa&ma=<?= urlencode($k['ma'] ?? '') ?>">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.5 3.5a2.121 2.121 0 1 1 3 3L18 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                            <?php endif; ?>
                                            <?php if ($canDeleteKhoa): ?>
                                            <button type="button" class="action-btn delete btn btn-danger" title="Xóa" onclick="showKhoaDeleteConfirm(<?= (int) ($k['ma'] ?? 0) ?>, <?= htmlspecialchars(json_encode((string) ($k['ten'] ?? ''), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>)">
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
                        Hiển thị <?= (int) ($pagination['from'] ?? 0) ?> - <?= (int) ($pagination['to'] ?? 0) ?> của <?= $total_items ?> khoa/bộ môn
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

<?php if ($canDeleteKhoa): ?>
    <form id="khoaDeleteForm" method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '?page=list_khoa') ?>" style="display:none;">
        <input type="hidden" name="action" value="delete" />
        <input type="hidden" name="ma" id="khoaDeleteId" value="" />
    </form>
<?php endif; ?>

<style>
    .list-khoa-page { padding: 24px; }
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
    .col-code { width:12%; }
    .col-name { width:39%; text-align:left; }
    .col-email { width:22%; text-align:left; }
    .col-phone { width:13%; }
    .col-action { width:9%; }
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
    function showKhoaDeleteConfirm(id, name) {
        showDeleteConfirm(id, 'khoa/bộ môn', name);
    }
</script>

<?php include __DIR__ . '/confirm/confirm_delete_modal.php'; ?>
