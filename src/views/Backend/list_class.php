<?php
    $classes = $classes ?? [];
    $filters = $filters ?? [];
    $academic_years = $academic_years ?? [];
    $statusOptions = $statusOptions ?? [
        ['value' => 'Hoạt động', 'label' => 'Hoạt động'],
        ['value' => 'Không hoạt động', 'label' => 'Không hoạt động'],
        ['value' => 'Ngừng tuyển sinh', 'label' => 'Ngừng tuyển sinh'],
    ];
    $pagination = $pagination ?? [];
    $current_page = (int) ($pagination['current_page'] ?? ($_GET['page_num'] ?? 1));
    $total_items = (int) ($pagination['total_items'] ?? count($classes));
    $items_per_page = (int) ($pagination['items_per_page'] ?? 10);
    $total_pages = max(1, (int) ($pagination['total_pages'] ?? ceil($total_items / max(1, $items_per_page))));
    $from = (int) ($pagination['from'] ?? ($total_items === 0 ? 0 : (($current_page - 1) * $items_per_page) + 1));
    $to = (int) ($pagination['to'] ?? min($total_items, $current_page * $items_per_page));
    $emptyMessage = $emptyMessage ?? 'Chưa có lớp học nào.';

    $currentKeyword = trim((string) ($filters['keyword'] ?? ($_GET['search'] ?? $_GET['keyword'] ?? $_GET['q'] ?? '')));
    $currentAcademicYear = trim((string) ($filters['academic_year'] ?? ($_GET['academic_year'] ?? '')));
    $currentStatus = trim((string) ($filters['status'] ?? ($_GET['status'] ?? '')));
    $hasActiveFilters = $currentKeyword !== '' || $currentAcademicYear !== '' || $currentStatus !== '';

    $paginationUrl = static function (int $pageNum): string {
        $params = $_GET;
        $params['page'] = 'list_class';
        $params['page_num'] = $pageNum;

        return '?' . http_build_query($params);
    };
?>
<div class="list-class-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH LỚP HỌC</h2>
                <div class="filter-wrap <?= $hasActiveFilters ? 'has-active' : '' ?>" id="classFilter">
                    <button type="button" id="classFilterToggle" class="filter-btn btn btn-outline-secondary" title="Bộ lọc" aria-label="Bộ lọc" aria-expanded="false">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 5h16l-6 7v5l-4 2v-7L4 5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="filter-menu" id="classFilterMenu" role="menu" aria-labelledby="classFilterToggle">
                        <form method="GET" action="/KhoaLuan/public/admin.php" class="filter-form">
                            <input type="hidden" name="page" value="list_class" />
                            <?php if ($currentKeyword !== ''): ?>
                                <input type="hidden" name="search" value="<?= htmlspecialchars($currentKeyword, ENT_QUOTES, 'UTF-8') ?>" />
                            <?php endif; ?>

                            <label class="filter-label" for="filter_academic_year">Niên khóa</label>
                            <select id="filter_academic_year" name="academic_year" class="filter-select form-select">
                                <option value="">Tất cả</option>
                                <?php foreach ($academic_years as $year): ?>
                                    <?php
                                        $yearId = (string) ($year['id'] ?? '');
                                        $yearName = (string) ($year['name'] ?? '');
                                    ?>
                                    <option value="<?= htmlspecialchars($yearId, ENT_QUOTES, 'UTF-8') ?>" <?= $currentAcademicYear === $yearId ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($yearName, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label class="filter-label" for="filter_status">Trạng thái</label>
                            <select id="filter_status" name="status" class="filter-select form-select">
                                <option value="">Tất cả</option>
                                <?php foreach ($statusOptions as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value'], ENT_QUOTES, 'UTF-8') ?>" <?= $currentStatus === $option['value'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <div class="filter-actions">
                                <a href="?page=list_class<?= $currentKeyword !== '' ? '&search=' . urlencode($currentKeyword) : '' ?>" class="filter-clear btn btn-outline-secondary">Đặt lại</a>
                                <button type="submit" class="filter-apply btn btn-primary">Lọc</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body card-body">
            <?php if (empty($classes)): ?>
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
                                <th class="col-code">MÃ LỚP</th>
                                <th class="col-name">TÊN LỚP</th>
                                <th class="col-department">KHOA</th>
                                <th class="col-major">CHUYÊN NGÀNH</th>
                                <th class="col-year">NIÊN KHÓA</th>
                                <th class="col-capacity">SĨ SỐ</th>
                                <th class="col-status">TRẠNG THÁI</th>
                                <th class="col-action">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($classes as $index => $class): ?>
                                <?php
                                    $id = (int) ($class['id'] ?? 0);
                                    $stt = (($current_page - 1) * $items_per_page) + $index + 1;
                                ?>
                                <tr data-id="<?= $id ?>">
                                    <td class="col-stt"><?= $stt ?></td>
                                    <td class="col-code"><?= htmlspecialchars($class['code'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-name"><?= htmlspecialchars($class['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-department"><?= htmlspecialchars($class['department'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-major"><?= htmlspecialchars($class['major'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-year"><?= htmlspecialchars($class['academic_year'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-capacity"><?= htmlspecialchars($class['capacity'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-status">
                                        <form method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '?page=list_class', ENT_QUOTES, 'UTF-8') ?>" style="display:inline-block;">
                                            <input type="hidden" name="action" value="status" />
                                            <input type="hidden" name="_row_id" value="<?= $id ?>" />
                                            <select name="status[<?= $id ?>]" class="status-select <?= htmlspecialchars($class['status_class'] ?? 'unknown', ENT_QUOTES, 'UTF-8') ?> form-select" data-previous-value="<?= htmlspecialchars($class['status'] ?? '', ENT_QUOTES, 'UTF-8') ?>" data-class-name="<?= htmlspecialchars($class['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" onchange="updateStatusSelect(this)">
                                                <?php foreach ($statusOptions as $option): ?>
                                                    <option value="<?= htmlspecialchars($option['value'], ENT_QUOTES, 'UTF-8') ?>" <?= (($class['status'] ?? '') === $option['value']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <button type="button" class="action-btn edit btn btn-outline-primary" title="Chỉnh sửa" onclick="editClass(<?= $id ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.5 3.5a2.121 2.121 0 1 1 3 3L18 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                            <button type="button" class="action-btn delete btn btn-danger" title="Xóa" onclick="showDeleteConfirm(<?= $id ?>, 'lớp học', <?= htmlspecialchars(json_encode((string) ($class['name'] ?? ''), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>)">
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
                        Hiển thị <?= $from ?> - <?= $to ?> của <?= $total_items ?> lớp học
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

<form id="classDeleteForm" method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '?page=list_class', ENT_QUOTES, 'UTF-8') ?>" style="display:none;">
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="id" id="classDeleteId" value="" />
</form>

<style>
    .list-class-page { display:grid; gap:0; padding:24px; }
    .page-panel { background:#fff; border:1px solid #e8ecf3; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden; }
    .panel-header { padding:12px 14px; border-bottom:1px solid #e5e7eb; background:#f9fafb; }
    .header-content { display:flex; justify-content:space-between; align-items:center; }
    .panel-title { font-size:14px; font-weight:700; color:#0f2a5a; margin:0; }
    .filter-wrap { position:relative; display:inline-flex; align-items:center; }
    .filter-btn { width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; color:#0f2a5a; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:0; }
    .filter-wrap.has-active .filter-btn { background:#eff6ff; border-color:#bfdbfe; color:#1d4ed8; }
    .filter-btn:hover { background:#f8fafc; color:#0b1f45; }
    .filter-menu { position:absolute; top:calc(100% + 6px); right:0; z-index:30; display:none; width:280px; padding:12px; background:#fff; border:1px solid #e5e7eb; border-radius:8px; box-shadow:0 8px 20px rgba(15,42,90,0.12); }
    .filter-wrap.open .filter-menu { display:block; }
    .filter-form { display:grid; gap:8px; }
    .filter-label { font-size:12px; font-weight:700; color:#0f2a5a; margin:0; }
    .filter-select { min-height:36px; font-size:13px; border-radius:8px; border-color:#e5e7eb; }
    .filter-actions { display:flex; justify-content:flex-end; gap:8px; padding-top:6px; }
    .filter-clear,
    .filter-apply { font-size:13px; font-weight:700; border-radius:8px; padding:7px 12px; }
    .panel-body { padding:0; }
    .empty-state { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:60px 20px; text-align:center; color:#9ca3af; }
    .empty-state svg { color:#d1d5db; margin-bottom:16px; }
    .empty-state h3 { font-size:16px; font-weight:600; color:#6b7280; margin:0; }
    .table-wrapper { overflow-x:auto; }
    .data-table { width:100%; border-collapse:collapse; font-size:13px; }
    .data-table thead,
    .data-table thead.table-light { background:#eef2f7; border-bottom:1px solid #e5e7eb; }
    .data-table th { padding:12px 14px; text-align:center; font-weight:800; color:#0f2a5a; text-transform:uppercase; letter-spacing:.4px; font-size:11px; border-right:1px solid #d1d5db; white-space:nowrap; }
    .data-table tbody tr { border-bottom:1px solid #f3f4f6; transition:background-color .2s; }
    .data-table tbody tr:nth-child(odd) { background:#f9fafb; }
    .data-table tbody tr:hover { background:#f0f1f3; }
    .data-table td { padding:12px 14px; color:#1f2937; text-align:center; border-right:1px solid #e5e7eb; vertical-align:middle; }
    .col-stt { width:35px; }
    .col-code { width:8%; white-space:nowrap; }
    .col-name { width:16%; }
    .col-department { width:18%; }
    .col-major { width:14%; }
    .col-year { width:9%; }
    .col-capacity { width:6%; white-space:nowrap; }
    .col-status { width:12%; white-space:nowrap; }
    .col-action { width:10%; }
    .status-badge { display:inline-flex; align-items:center; justify-content:center; gap:6px; min-width:92px; padding:6px 12px; border-radius:999px; font-size:12px; font-weight:700; white-space:nowrap; }
    .status-active { background:#d1fae5; color:#065f46; }
    .status-inactive { background:#fee2e2; color:#991b1b; }
    .status-stopped { background:#fed7d7; color:#7f1d1d; }
    .status-unknown { background:#e5e7eb; color:#374151; }
    .data-table .status-select { min-width:150px; padding:6px 34px 6px 10px; border-radius:12px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; appearance:none; -webkit-appearance:none; font-weight:700; background-position:right 10px center; background-repeat:no-repeat; }
    .data-table .status-select option { color:#0f2a5a; background:#fff; }
    .data-table .status-select.active { background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%23065f46' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg,#d1fae5,#6ee7b7); background-size:12px, auto; color:#065f46; border-color:#34d399; }
    .data-table .status-select.inactive { background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%23991b1b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg,#fee2e2,#fca5a5); background-size:12px, auto; color:#991b1b; border-color:#f87171; }
    .data-table .status-select.stopped { background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%237f1d1d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg, #fed7d7, #f87171); background-size:12px, auto; color:#7f1d1d; border-color:#f87171; font-weight:700; }
    .data-table .status-select.unknown { background:#e5e7eb; color:#374151; border-color:#d1d5db; }
    .data-table .status-select:focus { outline:none; box-shadow:0 0 0 3px rgba(15,42,90,0.12); }
    .action-group { display:flex; align-items:center; justify-content:center; gap:8px; }
    .action-btn { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; cursor:pointer; transition:all .2s; padding:0; }
    .action-btn:hover { border-color:#d1d5db; background:#f9fafb; }
    .action-btn.edit { color:#1d4ed8; }
    .action-btn.edit:hover { background:#eff6ff; }
    .action-btn.delete { color:#dc2626; }
    .action-btn.delete:hover { background:#fef2f2; }
    .pagination-container { padding:16px 14px; border-top:1px solid #e8ecf3; display:flex; justify-content:space-between; align-items:center; font-size:12px; color:#6b7280; }
    .pagination { display:flex; gap:6px; align-items:center; }
    .pagination-btn { display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; color:#6b7280; font-size:12px; font-weight:600; cursor:pointer; text-decoration:none; transition:all .2s; }
    .pagination-btn:hover { border-color:#d1d5db; background:#f9fafb; color:#4b5563; }
    .pagination-btn.active { background:linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); border-color:#0f2a5a; color:#fff; }
    .pagination-btn.disabled { opacity:.45; cursor:not-allowed; pointer-events:none; background:#f9fafb; color:#9ca3af; }
    .pagination-btn.prev,
    .pagination-btn.next,
    .pagination-btn.first,
    .pagination-btn.last { min-width:auto; padding:0 8px; }
    @media (max-width:1024px) { .col-department,.col-major,.col-year { width:auto; } .data-table { font-size:12px; } .data-table th,.data-table td { padding:10px 12px; } }
    @media (max-width:768px) { .pagination-container { align-items:flex-start; flex-direction:column; gap:10px; } .filter-menu { right:-4px; width:min(280px, calc(100vw - 48px)); } .data-table { min-width:980px; } }
</style>

<script>
    function editClass(id) {
        window.location.href = '?page=edit_class&id=' + encodeURIComponent(id);
    }

    function statusClassName(value) {
        if (value === 'Hoạt động') {
            return 'active';
        }
        if (value === 'Không hoạt động') {
            return 'inactive';
        }
        if (value === 'Ngừng tuyển sinh') {
            return 'stopped';
        }
        return 'unknown';
    }

    function setStatusClass(el, value) {
        el.classList.remove('active', 'inactive', 'stopped', 'unknown');
        el.classList.add(statusClassName(value));
    }

    function updateStatusSelect(el) {
        const nextStatus = el.value;
        const previousStatus = el.getAttribute('data-previous-value') || '';
        const className = el.getAttribute('data-class-name') || '';

        if (nextStatus === previousStatus) {
            setStatusClass(el, previousStatus);
            return;
        }

        el.value = previousStatus;
        setStatusClass(el, previousStatus);

        if (typeof showStatusConfirm !== 'function') {
            return;
        }

        showStatusConfirm({
            select: el,
            moduleLabel: 'lớp học',
            targetName: className,
            previousStatus: previousStatus,
            nextStatus: nextStatus,
            question: 'Bạn có chắc chắn muốn thay đổi trạng thái lớp học này không?',
            warning: 'Trạng thái lớp học sẽ được cập nhật sau khi xác nhận.'
        });
    }

    (function() {
        const filterWrap = document.getElementById('classFilter');
        const filterToggle = document.getElementById('classFilterToggle');
        if (filterWrap && filterToggle) {
            filterToggle.addEventListener('click', function(event) {
                event.stopPropagation();
                const isOpen = filterWrap.classList.toggle('open');
                filterToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            document.addEventListener('click', function(event) {
                if (!filterWrap.contains(event.target)) {
                    filterWrap.classList.remove('open');
                    filterToggle.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    filterWrap.classList.remove('open');
                    filterToggle.setAttribute('aria-expanded', 'false');
                }
            });
        }

    })();

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.status-select').forEach(function(select) {
            select.setAttribute('data-previous-value', select.value);
            setStatusClass(select, select.value);
        });
    });
</script>
<?php include __DIR__ . '/confirm/confirm_status_modal.php'; ?>
<?php include __DIR__ . '/confirm/confirm_delete_modal.php'; ?>
