<?php
    $semesters = $semesters ?? [];
    $academic_years = $academic_years ?? [];
    $status_options = $status_options ?? [
        ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
        ['value' => 'Đang diễn ra', 'label' => 'Đang diễn ra'],
        ['value' => 'Đã hoàn thành', 'label' => 'Đã hoàn thành'],
        ['value' => 'Tạm khóa', 'label' => 'Tạm khóa'],
    ];
    $statusValues = array_column($status_options, 'value');
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
    $filters = $filters ?? [];
    $currentKeyword = trim((string) ($filters['keyword'] ?? ($_GET['search'] ?? $_GET['keyword'] ?? $_GET['q'] ?? '')));
    $currentStatusFilter = trim((string) ($filters['status'] ?? ($_GET['status'] ?? '')));
    $currentAcademicYearFilter = trim((string) ($filters['academic_year_id'] ?? ($_GET['academic_year_id'] ?? $_GET['academic_year'] ?? '')));
    $extractAcademicYearId = static function (array $year): string {
        foreach (['id', 'MA_NIEN_KHOA', 'academic_year_id', 'year_id'] as $key) {
            if (array_key_exists($key, $year) && trim((string) $year[$key]) !== '') {
                return trim((string) $year[$key]);
            }
        }

        return '';
    };
    $extractAcademicYearName = static function (array $year): string {
        foreach (['name', 'TEN_NIEN_KHOA', 'academic_year_name', 'year_name'] as $key) {
            if (array_key_exists($key, $year) && trim((string) $year[$key]) !== '') {
                return trim((string) $year[$key]);
            }
        }

        return '';
    };

    $academicYearOptions = [];
    foreach ($academic_years as $year) {
        if (!is_array($year)) {
            continue;
        }

        $yearId = $extractAcademicYearId($year);
        if ($yearId === '') {
            continue;
        }

        $academicYearOptions[] = [
            'id' => $yearId,
            'name' => $extractAcademicYearName($year),
        ];
    }

    if (!in_array($currentStatusFilter, $statusValues, true)) {
        $currentStatusFilter = '';
    }
    $academicYearValues = array_values(array_map(
        static fn (array $option): string => $option['id'],
        $academicYearOptions
    ));
    if ($currentAcademicYearFilter !== '' && !empty($academicYearValues) && !in_array($currentAcademicYearFilter, $academicYearValues, true)) {
        $currentAcademicYearFilter = '';
    }
    $hasActiveFilters = $currentStatusFilter !== '' || $currentAcademicYearFilter !== '';
    $emptyMessage = $emptyMessage ?? 'Chưa có học kỳ nào.';
    $paginationUrl = static function (int $pageNum): string {
        $params = $_GET;
        $params['page'] = 'list_semester';
        $params['page_num'] = $pageNum;
        return '?' . http_build_query($params);
    };
    $permissionChecker = isset($canAccessPermission) && is_callable($canAccessPermission) ? $canAccessPermission : null;
    $canEditSemester = $permissionChecker ? $permissionChecker('edit_semester') : false;
    $canDeleteSemester = $permissionChecker ? $permissionChecker('delete_semester') : false;
    $canChangeStatusSemester = $permissionChecker ? $permissionChecker('change_status_semester') : false;
    $showActions = $canEditSemester || $canDeleteSemester;

    $formatDate = static function (?string $date): string {
        $date = trim((string) $date);
        if ($date === '') {
            return '';
        }

        $timestamp = strtotime($date);
        return $timestamp ? date('d/m/Y', $timestamp) : $date;
    };
    $statusClass = static function (?string $status): string {
        return match (trim((string) $status)) {
            'Sắp diễn ra' => 'upcoming',
            'Đang diễn ra' => 'active',
            'Đã hoàn thành' => 'completed',
            'Tạm khóa' => 'locked',
            default => 'unknown',
        };
    };
?>

<div class="list-semester-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH HỌC KỲ</h2>
                <div class="filter-wrap <?= $hasActiveFilters ? 'has-active' : '' ?>" id="semesterStatusFilter">
                    <button type="button" id="semesterStatusFilterToggle" class="filter-btn btn btn-outline-secondary" title="Lọc trạng thái" aria-label="Lọc trạng thái" aria-expanded="false">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 5h16l-6 7v5l-4 2v-7L4 5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="filter-menu" id="semesterStatusFilterMenu" aria-labelledby="semesterStatusFilterToggle">
                        <form method="GET" action="/KhoaLuan/public/admin.php" class="filter-form">
                            <input type="hidden" name="page" value="list_semester" />
                            <?php if ($currentKeyword !== ''): ?>
                                <input type="hidden" name="search" value="<?= htmlspecialchars($currentKeyword, ENT_QUOTES, 'UTF-8') ?>" />
                            <?php endif; ?>
                            <label class="filter-label" for="filter_semester_year">Niên khóa</label>
                            <select id="filter_semester_year" name="academic_year_id" class="filter-select form-select">
                                <option value="">Tất cả</option>
                                <?php foreach ($academicYearOptions as $year): ?>
                                    <?php
                                        $yearId = (string) ($year['id'] ?? '');
                                        $yearName = (string) ($year['name'] ?? '');
                                    ?>
                                    <option value="<?= htmlspecialchars($yearId, ENT_QUOTES, 'UTF-8') ?>" <?= $currentAcademicYearFilter === $yearId ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($yearName, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label class="filter-label" for="filter_semester_status">Trạng thái</label>
                            <select id="filter_semester_status" name="status" class="filter-select form-select">
                                <option value="">Tất cả</option>
                                <?php foreach ($status_options as $option): ?>
                                    <option value="<?= htmlspecialchars((string) $option['value'], ENT_QUOTES, 'UTF-8') ?>" <?= $currentStatusFilter === $option['value'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="filter-actions">
                                <a href="?page=list_semester<?= $currentKeyword !== '' ? '&search=' . urlencode($currentKeyword) : '' ?>" class="filter-clear btn btn-outline-secondary">Đặt lại</a>
                                <button type="submit" class="filter-apply btn btn-primary">Lọc</button>
                            </div>
                        </form>
                    </div>
                </div>
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
                                <th class="col-start">THỜI GIAN BẮT ĐẦU</th>
                                <th class="col-end">THỜI GIAN KẾT THÚC</th>
                                <th class="col-year">NIÊN KHÓA</th>
                                <th class="col-status">TRẠNG THÁI</th>
                                <?php if ($showActions): ?>
                                    <th class="col-action">THAO TÁC</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($semesters as $index => $semester): ?>
                                <?php $rowNumber = (($current_page - 1) * $items_per_page) + $index + 1; ?>
                                <tr data-id="<?= htmlspecialchars($semester['id'] ?? '') ?>">
                                    <td class="col-stt"><?= str_pad((string) $rowNumber, 2, '0', STR_PAD_LEFT) ?></td>
                                    <td class="col-name"><?= htmlspecialchars($semester['name'] ?? '') ?></td>
                                    <td class="col-start"><?= htmlspecialchars($formatDate($semester['start_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-end"><?= htmlspecialchars($formatDate($semester['end_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-year"><?= htmlspecialchars($semester['academic_year'] ?? '') ?></td>
                                    <td class="col-status">
                                        <?php $currentStatus = (string) ($semester['status'] ?? ''); ?>
                                        <?php if ($canChangeStatusSemester): ?>
                                        <form method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '?page=list_semester', ENT_QUOTES, 'UTF-8') ?>" style="display:inline-block;">
                                            <input type="hidden" name="action" value="status" />
                                            <input type="hidden" name="_row_id" value="<?= (int) ($semester['id'] ?? 0) ?>" />
                                            <select name="status[<?= (int) ($semester['id'] ?? 0) ?>]" class="status-select <?= htmlspecialchars($statusClass($currentStatus), ENT_QUOTES, 'UTF-8') ?> form-select" data-previous-value="<?= htmlspecialchars($currentStatus, ENT_QUOTES, 'UTF-8') ?>" data-semester-name="<?= htmlspecialchars($semester['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" onchange="updateStatusSelect(this)">
                                                <?php if ($currentStatus !== '' && !in_array($currentStatus, $statusValues, true)): ?>
                                                    <option value="<?= htmlspecialchars($currentStatus, ENT_QUOTES, 'UTF-8') ?>" selected><?= htmlspecialchars($currentStatus, ENT_QUOTES, 'UTF-8') ?></option>
                                                <?php endif; ?>
                                                <?php foreach ($status_options as $option): ?>
                                                    <option value="<?= htmlspecialchars($option['value'], ENT_QUOTES, 'UTF-8') ?>" <?= $currentStatus === $option['value'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </form>
                                        <?php else: ?>
                                            <?= htmlspecialchars($currentStatus, ENT_QUOTES, 'UTF-8') ?>
                                        <?php endif; ?>
                                    </td>
                                    <?php if ($showActions): ?>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <?php if ($canEditSemester): ?>
                                            <a class="action-btn edit btn btn-outline-primary" title="Chỉnh sửa" href="?page=edit_semester&id=<?= (int) ($semester['id'] ?? 0) ?>&return=<?= urlencode($paginationUrl($current_page)) ?>">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M15.5 3.5a2.121 2.121 0 1 1 3 3L18 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                            <?php endif; ?>
                                            <?php if ($canDeleteSemester): ?>
                                            <button type="button" class="action-btn delete btn btn-danger" title="Xóa" onclick="showSemesterDeleteConfirm(<?= (int) ($semester['id'] ?? 0) ?>, <?= htmlspecialchars(json_encode((string) ($semester['name'] ?? ''), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8') ?>)">
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

<?php if ($canDeleteSemester): ?>
    <form id="semesterDeleteForm" method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '?page=list_semester') ?>" style="display:none;">
        <input type="hidden" name="action" value="delete" />
        <input type="hidden" name="id" id="semesterDeleteId" value="" />
    </form>
<?php endif; ?>

<style>
    .list-semester-page { padding: 24px; }
    .page-panel { background: #fff; border: 1px solid #e8ecf3; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden; }
    .panel-header { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .header-content { display:flex; justify-content:space-between; align-items:center; gap:12px; }
    .panel-title { font-size:14px; font-weight:700; color:#0f2a5a; margin:0; }
    .filter-wrap { position:relative; display:inline-flex; align-items:center; }
    .filter-btn { width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; color:#0f2a5a; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:0; }
    .filter-wrap.has-active .filter-btn { background:#eff6ff; border-color:#bfdbfe; color:#1d4ed8; }
    .filter-btn:hover { background:#f8fafc; color:#0b1f45; }
    .filter-menu { position:absolute; top:calc(100% + 6px); right:0; z-index:30; display:none; width:240px; padding:12px; background:#fff; border:1px solid #e5e7eb; border-radius:8px; box-shadow:0 8px 20px rgba(15,42,90,0.12); }
    .filter-wrap.open .filter-menu { display:block; }
    .filter-form { display:grid; gap:8px; }
    .filter-label { font-size:12px; font-weight:700; color:#0f2a5a; margin:0; }
    .filter-select { min-height:36px; font-size:13px; border-radius:8px; border-color:#e5e7eb; position:relative; z-index:1; }
    .filter-actions { display:flex; justify-content:flex-end; gap:8px; padding-top:6px; }
    .filter-clear,
    .filter-apply { font-size:13px; font-weight:700; border-radius:8px; padding:7px 12px; }
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
    .col-name { width:22%; text-align:left; }
    .col-start { width:15%; }
    .col-end { width:15%; }
    .col-year { width:15%; }
    .col-status { width:16%; }
    .col-action { width:10%; }
    
    .status-badge { display:inline-block; padding:4px 10px; border-radius:12px; font-size:11px; font-weight:600; white-space:nowrap; }
    .status-sắp-tới { background:#e0f2fe; color:#0369a1; }
    .status-đang-diễn-ra { background:#dcfce7; color:#15803d; }
    .status-đã-hoàn-thành { background:#f3f4f6; color:#4b5563; }
    .data-table .status-select { min-width:150px; padding:6px 34px 6px 10px; border-radius:12px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; appearance:none; -webkit-appearance:none; font-weight:700; background-position:right 10px center; background-repeat:no-repeat; }
    .data-table .status-select option { color:#0f2a5a; background:#fff; }
    .data-table .status-select.upcoming { background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%230369a1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg,#e0f2fe,#7dd3fc); background-size:12px, auto; color:#0369a1; border-color:#7dd3fc; }
    .data-table .status-select.active { background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%23065f46' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg,#d1fae5,#6ee7b7); background-size:12px, auto; color:#065f46; border-color:#34d399; }
    .data-table .status-select.completed { background:#f3f4f6; color:#4b5563; border-color:#d1d5db; }
    .data-table .status-select.locked { background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%237f1d1d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg,#fed7d7,#f87171); background-size:12px, auto; color:#7f1d1d; border-color:#f87171; }
    .data-table .status-select.unknown { background:#e5e7eb; color:#374151; border-color:#d1d5db; }
    .data-table .status-select:focus { outline:none; box-shadow:0 0 0 3px rgba(15,42,90,0.12); }
    
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
    .empty-state { padding:42px 0; text-align:center; color:#334155; }
    .empty-state svg { margin-bottom:16px; color:#0f2a5a; }
    .empty-state h3 { margin-bottom:8px; font-size:18px; font-weight:700; }
    .empty-state p { margin:0; color:#64748b; }
</style>

<script>
    function showSemesterDeleteConfirm(id, name) {
        showDeleteConfirm(id, 'học kỳ', name);
    }

    function statusClassName(value) {
        if (value === 'Sắp diễn ra') {
            return 'upcoming';
        }
        if (value === 'Đang diễn ra') {
            return 'active';
        }
        if (value === 'Đã hoàn thành') {
            return 'completed';
        }
        if (value === 'Tạm khóa') {
            return 'locked';
        }
        return 'unknown';
    }

    function setStatusClass(el, value) {
        el.classList.remove('upcoming', 'active', 'completed', 'locked', 'unknown');
        el.classList.add(statusClassName(value));
    }

    function updateStatusSelect(el) {
        var nextStatus = el.value;
        var previousStatus = el.getAttribute('data-previous-value') || '';
        var semesterName = el.getAttribute('data-semester-name') || '';

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
            moduleLabel: 'học kỳ',
            targetName: semesterName,
            previousStatus: previousStatus,
            nextStatus: nextStatus,
            question: 'Bạn có chắc chắn muốn thay đổi trạng thái học kỳ này không?',
            warning: 'Trạng thái học kỳ sẽ được cập nhật sau khi xác nhận.'
        });
    }

    (function(){
        var filterWrap = document.getElementById('semesterStatusFilter');
        var filterToggle = document.getElementById('semesterStatusFilterToggle');

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

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.status-select').forEach(function(select) {
            select.setAttribute('data-previous-value', select.value);
            setStatusClass(select, select.value);
        });
    });
</script>

<?php include __DIR__ . '/confirm/confirm_status_modal.php'; ?>
<?php include __DIR__ . '/confirm/confirm_delete_modal.php'; ?>
