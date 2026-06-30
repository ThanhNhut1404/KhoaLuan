<?php
    $majors = $majors ?? [];
    $statusOptions = $statusOptions ?? [
        ['value' => 'Hoạt động', 'label' => 'Hoạt động'],
        ['value' => 'Ngừng tuyển sinh', 'label' => 'Ngừng tuyển sinh'],
    ];
    $pagination = $pagination ?? [
        'current_page' => 1,
        'total_items' => count($majors),
        'items_per_page' => 10,
        'total_pages' => 1,
        'from' => empty($majors) ? 0 : 1,
        'to' => count($majors),
    ];
    $current_page = (int) ($pagination['current_page'] ?? 1);
    $total_items = (int) ($pagination['total_items'] ?? count($majors));
    $items_per_page = (int) ($pagination['items_per_page'] ?? 10);
    $total_pages = (int) ($pagination['total_pages'] ?? 1);
    $filters = $filters ?? [];
    $currentKeyword = trim((string) ($filters['keyword'] ?? ($_GET['search'] ?? $_GET['keyword'] ?? $_GET['q'] ?? '')));
    $currentStatusFilter = trim((string) ($filters['status'] ?? ($_GET['status'] ?? '')));
    $statusValues = array_column($statusOptions, 'value');
    if (!in_array($currentStatusFilter, $statusValues, true)) {
        $currentStatusFilter = '';
    }
    $hasActiveFilters = $currentStatusFilter !== '';
    $emptyMessage = $emptyMessage ?? 'Chưa có ngành học nào.';
    $paginationUrl = static function (int $pageNum): string {
        $params = $_GET;
        $params['page'] = 'list_major';
        $params['page_num'] = $pageNum;

        return '?' . http_build_query($params);
    };
?>

<div class="list-major-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH NGÀNH HỌC</h2>
                <div class="filter-wrap <?= $hasActiveFilters ? 'has-active' : '' ?>" id="majorStatusFilter">
                    <button type="button" id="majorStatusFilterToggle" class="filter-btn btn btn-outline-secondary" title="Lọc trạng thái" aria-label="Lọc trạng thái" aria-expanded="false">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 5h16l-6 7v5l-4 2v-7L4 5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="filter-menu" id="majorStatusFilterMenu" aria-labelledby="majorStatusFilterToggle">
                        <form method="GET" action="/KhoaLuan/public/admin.php" class="filter-form">
                            <input type="hidden" name="page" value="list_major" />
                            <?php if ($currentKeyword !== ''): ?>
                                <input type="hidden" name="search" value="<?= htmlspecialchars($currentKeyword, ENT_QUOTES, 'UTF-8') ?>" />
                            <?php endif; ?>
                            <label class="filter-label" for="filter_major_status">Tr&#7841;ng th&aacute;i</label>
                            <select id="filter_major_status" name="status" class="filter-select form-select">
                                <option value="">T&#7845;t c&#7843;</option>
                                <?php foreach ($statusOptions as $option): ?>
                                    <option value="<?= htmlspecialchars((string) $option['value'], ENT_QUOTES, 'UTF-8') ?>" <?= $currentStatusFilter === $option['value'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="filter-actions">
                                <a href="?page=list_major<?= $currentKeyword !== '' ? '&search=' . urlencode($currentKeyword) : '' ?>" class="filter-clear btn btn-outline-secondary">&#272;&#7863;t l&#7841;i</a>
                                <button type="submit" class="filter-apply btn btn-primary">L&#7885;c</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body card-body">
            <?php if (empty($majors)): ?>
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 13h6M9 17h3M5 21h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <h3><?= htmlspecialchars($emptyMessage, ENT_QUOTES, 'UTF-8') ?></h3>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="col-stt">STT</th>
                                <th class="col-code">MÃ NGÀNH</th>
                                <th class="col-name">TÊN NGÀNH</th>
                                <th class="col-dept">MÃ KHOA/BỘ MÔN</th>
                                <th class="col-dept-name">TÊN KHOA/BỘ MÔN</th>
                                <th class="col-status">TRẠNG THÁI</th>
                                <th class="col-action">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($majors as $index => $major): ?>
                                <?php $rowNumber = (($current_page - 1) * $items_per_page) + $index + 1; ?>
                                <tr data-id="<?= (int) $major['id'] ?>">
                                    <td class="col-stt"><?= str_pad((string) $rowNumber, 2, '0', STR_PAD_LEFT) ?></td>
                                    <td class="col-code"><?= htmlspecialchars($major['code'] ?? '--', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-name"><?= htmlspecialchars($major['name'] ?? '--', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-dept"><?= htmlspecialchars($major['department'] ?? '--', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-dept-name"><?= htmlspecialchars($major['department_name'] ?? '--', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-status">
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="_row_id" value="<?= (int) $major['id'] ?>" />
                                            <select name="status[<?= (int) $major['id'] ?>]" class="status-select <?= htmlspecialchars($major['status_class'] ?? '', ENT_QUOTES, 'UTF-8') ?> form-select" data-previous-value="<?= htmlspecialchars($major['status'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-major-name="<?= htmlspecialchars($major['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" onchange="updateStatusSelect(this)">
                                                <?php foreach ($statusOptions as $option): ?>
                                                    <option value="<?= htmlspecialchars($option['value'], ENT_QUOTES, 'UTF-8') ?>" <?= (($major['status'] ?? '') === $option['value']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <button type="button" class="action-btn edit btn btn-outline-primary" title="Chỉnh sửa" onclick="editMajor(<?= (int) $major['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.5 3.5a2.121 2.121 0 1 1 3 3L18 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                            <button type="button" class="action-btn delete btn btn-danger" title="Xóa" onclick="showMajorDeleteConfirm(<?= (int) $major['id'] ?>, <?= htmlspecialchars(json_encode((string) ($major['name'] ?? ''), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>)">
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
                        Hiển thị <?= (int) ($pagination['from'] ?? 0) ?> - <?= (int) ($pagination['to'] ?? 0) ?> của <?= $total_items ?> ngành học
                    </div>
                    <div class="pagination mb-0">
                        <?php if ($current_page > 1): ?>
                            <a href="<?= htmlspecialchars($paginationUrl(1), ENT_QUOTES, 'UTF-8') ?>" class="pagination-btn first page-link page-item">&lt;&lt;</a>
                            <a href="<?= htmlspecialchars($paginationUrl($current_page - 1), ENT_QUOTES, 'UTF-8') ?>" class="pagination-btn prev page-link page-item">&lt;</a>
                        <?php else: ?>
                            <span class="pagination-btn first page-link page-item disabled">&lt;&lt;</span>
                            <span class="pagination-btn prev page-link page-item disabled">&lt;</span>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="<?= htmlspecialchars($paginationUrl($i), ENT_QUOTES, 'UTF-8') ?>" class="pagination-btn page-link page-item <?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="<?= htmlspecialchars($paginationUrl($current_page + 1), ENT_QUOTES, 'UTF-8') ?>" class="pagination-btn next page-link page-item">&gt;</a>
                            <a href="<?= htmlspecialchars($paginationUrl($total_pages), ENT_QUOTES, 'UTF-8') ?>" class="pagination-btn last page-link page-item">&gt;&gt;</a>
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

<form id="majorDeleteForm" method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '?page=list_major', ENT_QUOTES, 'UTF-8') ?>" style="display:none;">
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="id" id="majorDeleteId" value="" />
</form>

<style>
    .list-major-page { padding: 24px; }
    .page-panel { background: #fff; border: 1px solid #e8ecf3; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden; }
    .panel-header { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .header-content { display:flex; justify-content:space-between; align-items:center; }
    .panel-title { font-size:14px; font-weight:700; color:#0f2a5a; margin:0; }
    .filter-wrap { position:relative; display:inline-flex; align-items:center; }
    .filter-btn { width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; color:#0f2a5a; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:0; }
    .filter-wrap.has-active .filter-btn { background:#eff6ff; border-color:#bfdbfe; color:#1d4ed8; }
    .filter-btn:hover { background:#f8fafc; color:#0b1f45; }
    .filter-menu { position:absolute; top:calc(100% + 6px); right:0; z-index:30; display:none; width:240px; padding:12px; background:#fff; border:1px solid #e5e7eb; border-radius:8px; box-shadow:0 8px 20px rgba(15,42,90,0.12); }
    .filter-wrap.open .filter-menu { display:block; }
    .filter-form { display:grid; gap:8px; }
    .filter-label { font-size:12px; font-weight:700; color:#0f2a5a; margin:0; }
    .filter-select { min-height:36px; font-size:13px; border-radius:8px; border-color:#e5e7eb; }
    .filter-actions { display:flex; justify-content:flex-end; gap:8px; padding-top:6px; }
    .filter-clear,
    .filter-apply { font-size:13px; font-weight:700; border-radius:8px; padding:7px 12px; }
    .panel-body { padding:0; }
    .table-wrapper { overflow-x:auto; }
    .data-table { width:100%; border-collapse:collapse; font-size:13px; }
    .data-table thead { background:#f8f9fa; border-bottom:1px solid #e5e7eb; }
    .data-table th { padding:12px 14px; text-align:center; font-weight:700; color:#0f2a5a; text-transform:uppercase; font-size:11px; border-right:1px solid #d1d5db; }
    .data-table tbody tr { border-bottom:1px solid #f3f4f6; transition:background-color .2s; }
    .data-table tbody tr:nth-child(odd) { background:#f9fafb; }
    .data-table tbody tr:hover { background:#f0f1f3; }
    .data-table td { padding:12px 14px; color:#1f2937; text-align:center; border-right:1px solid #e5e7eb; }
    .col-stt { width:5%; }
    .col-code { width:9%; }
    .col-name { width:26%; }
    .col-dept { width:11%; }
    .col-dept-name { width:20%; }
    .col-status { width:16%; }
    .col-action { width:8%; }
    .action-group { display:flex; gap:8px; justify-content:center; }
    .action-btn { width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:0; }
    .action-btn.edit { color:#1d4ed8; }
    .action-btn.delete { color:#dc2626; }
    .pagination-container { padding:16px 14px; border-top:1px solid #e8ecf3; display:flex; justify-content:space-between; align-items:center; font-size:12px; color:#6b7280; }
    .pagination { display:flex; gap:6px; align-items:center; }
    .pagination-btn { display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; color:#6b7280; font-weight:600; text-decoration:none; }
    .pagination-btn.active { background:linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); color:#fff; border-color:#0f2a5a; }
    .pagination-btn.disabled { opacity:.45; cursor:not-allowed; pointer-events:none; background:#f9fafb; color:#9ca3af; }
    @media (max-width:768px) { .filter-menu { right:-4px; width:min(240px, calc(100vw - 48px)); } .data-table { min-width:900px; } }
    .data-table .status-select { padding:6px 12px 6px 8px; border-radius:12px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; color:#0f2a5a; appearance:none; -webkit-appearance:none; font-weight:700; padding-right:36px; background-position: right 10px center; background-repeat: no-repeat; }
    .data-table .status-select option { color:#0f2a5a; }
    .data-table .status-select.active { background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%23065546' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg, #bbf7d0, #34d399); background-size:12px, auto; color:#065f46; border-color:#34d399; font-weight:700; }
    .data-table .status-select.inactive { background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%237f1d1d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg, #fed7d7, #f87171); background-size:12px, auto; color:#7f1d1d; border-color:#f87171; font-weight:700; }
    .data-table .status-select:focus { outline: none; box-shadow: 0 0 0 3px rgba(52,211,153,0.12); }
</style>

<script>
    function editMajor(id) { window.location.href = '?page=edit_major&id=' + encodeURIComponent(id); }

    function isActiveStatus(value) {
        return value === 'Hoạt động';
    }

    function setStatusClass(el, value) {
        el.classList.remove('active','inactive');
        el.classList.add(isActiveStatus(value) ? 'active' : 'inactive');
    }

    function updateStatusSelect(el){
        var val = el.value;
        var previous = el.getAttribute('data-previous-value') || '';
        var majorName = el.getAttribute('data-major-name') || '';

        if (val === previous) {
            setStatusClass(el, previous);
            return;
        }

        el.value = previous;
        setStatusClass(el, previous);

        if (typeof showStatusConfirm !== 'function') {
            return;
        }

        showStatusConfirm({
            select: el,
            moduleLabel: 'ngành học',
            targetName: majorName,
            previousStatus: previous,
            nextStatus: val,
            warning: 'Thao tác này sẽ thay đổi trạng thái tuyển sinh của ngành học.'
        });
    }

    function showMajorDeleteConfirm(id, name) {
        showDeleteConfirm(id, 'ngành học', name);
    }

    (function(){
        var filterWrap = document.getElementById('majorStatusFilter');
        var filterToggle = document.getElementById('majorStatusFilterToggle');

        if (!filterWrap || !filterToggle) {
            return;
        }

        filterToggle.addEventListener('click', function(event){
            event.stopPropagation();
            var isOpen = filterWrap.classList.toggle('open');
            filterToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function(event){
            if (!filterWrap.contains(event.target)) {
                filterWrap.classList.remove('open');
                filterToggle.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', function(event){
            if (event.key === 'Escape') {
                filterWrap.classList.remove('open');
                filterToggle.setAttribute('aria-expanded', 'false');
            }
        });
    })();

    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('.status-select').forEach(function(s){
            s.setAttribute('data-previous-value', s.value);
            setStatusClass(s, s.value);
        });
    });
</script>

<?php include __DIR__ . '/confirm/confirm_status_modal.php'; ?>
<?php include __DIR__ . '/confirm/confirm_delete_modal.php'; ?>
