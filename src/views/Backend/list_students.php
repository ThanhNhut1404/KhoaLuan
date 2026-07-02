<?php
$students = $students ?? [];
$pagination = $pagination ?? [
    'current_page' => 1,
    'total_items' => count($students),
    'items_per_page' => 10,
    'total_pages' => 1,
    'from' => empty($students) ? 0 : 1,
    'to' => count($students),
];
$emptyMessage = $emptyMessage ?? 'Chưa có sinh viên nào.';
$permissionChecker = isset($canAccessPermission) && is_callable($canAccessPermission) ? $canAccessPermission : null;
$canEditStudent = $permissionChecker ? $permissionChecker('edit_student') : false;
$canDeleteStudent = $permissionChecker ? $permissionChecker('delete_student') : false;
$canChangeStatusStudent = $permissionChecker ? $permissionChecker('change_status_student') : false;
$showActions = $canEditStudent || $canDeleteStudent;
$current_page = (int) ($pagination['current_page'] ?? 1);
$total_items = (int) ($pagination['total_items'] ?? count($students));
$items_per_page = (int) ($pagination['items_per_page'] ?? 10);
$total_pages = (int) ($pagination['total_pages'] ?? 1);
$from = (int) ($pagination['from'] ?? (empty($students) ? 0 : 1));
$to = (int) ($pagination['to'] ?? count($students));
$filters = $filters ?? [];
$studentFilterOptions = $studentFilterOptions ?? ['classes' => [], 'academic_years' => []];

$statusOptions = [
    ['value' => 'Đang học', 'label' => 'Đang học'],
    ['value' => 'Bảo lưu', 'label' => 'Bảo lưu'],
    ['value' => 'Đã tốt nghiệp', 'label' => 'Đã tốt nghiệp'],
    ['value' => 'Đã thôi học', 'label' => 'Đã thôi học'],
];
$statusValues = array_column($statusOptions, 'value');

$statusClass = static function (string $status): string {
    return match ($status) {
        'Đang học' => 'active',
        'Bảo lưu' => 'paused',
        'Đã tốt nghiệp' => 'graduated',
        'Đã thôi học' => 'dropped',
        'Tạm ngừng', 'Kết thúc' => 'legacy',
        default => 'unknown',
    };
};

$statusLabel = static function (string $status) use ($statusOptions): string {
    foreach ($statusOptions as $option) {
        if ((string) ($option['value'] ?? '') === $status) {
            return (string) ($option['label'] ?? $status);
        }
    }

    return $status;
};

$currentKeyword = trim((string) ($filters['keyword'] ?? ($_GET['search'] ?? $_GET['keyword'] ?? $_GET['q'] ?? '')));
$currentClassId = trim((string) ($filters['class_id'] ?? ($_GET['class_id'] ?? $_GET['class'] ?? '')));
$currentAcademicYear = trim((string) ($filters['academic_year'] ?? ($_GET['academic_year'] ?? $_GET['year'] ?? '')));
$currentStatusFilter = trim((string) ($filters['status'] ?? ($_GET['status'] ?? '')));
$hasActiveFilters = $currentClassId !== '' || $currentAcademicYear !== '' || $currentStatusFilter !== '';

$paginationUrl = static function (int $pageNum): string {
    $params = $_GET;
    $params['page'] = 'list_students';
    $params['page_num'] = $pageNum;

    return '?' . http_build_query($params);
};
?>

<?php if ($canDeleteStudent): ?>
    <form id="studentDeleteForm" method="post" action="/KhoaLuan/public/admin.php?page=delete_student" style="display:none;">
        <input type="hidden" name="student_id" id="studentDeleteId" value="" />
    </form>
<?php endif; ?>

<div class="list-student-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH SINH VIÊN</h2>
                <div class="filter-wrap <?= $hasActiveFilters ? 'has-active' : '' ?>" id="studentFilter">
                    <button type="button" id="studentFilterToggle" class="filter-btn btn btn-outline-secondary" title="Bộ lọc" aria-label="Bộ lọc" aria-expanded="false">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 5h16l-6 7v5l-4 2v-7L4 5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="filter-menu" id="studentFilterMenu" role="menu" aria-labelledby="studentFilterToggle">
                        <form method="GET" action="/KhoaLuan/public/admin.php" class="filter-form">
                            <input type="hidden" name="page" value="list_students" />
                            <?php if ($currentKeyword !== ''): ?>
                                <input type="hidden" name="search" value="<?= htmlspecialchars($currentKeyword, ENT_QUOTES, 'UTF-8') ?>" />
                            <?php endif; ?>

                                <label class="filter-label" for="filter_student_class">Lớp học</label>
                                <select id="filter_student_class" name="class_id" class="filter-select form-select">
                                    <option value="">Tất cả</option>
                                    <?php foreach (($studentFilterOptions['classes'] ?? []) as $classOption): ?>
                                        <?php
                                            $classId = (string) ($classOption['id'] ?? '');
                                            $className = (string) ($classOption['name'] ?? '');
                                        ?>
                                        <option value="<?= htmlspecialchars($classId, ENT_QUOTES, 'UTF-8') ?>" <?= $currentClassId === $classId ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($className, ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <label class="filter-label" for="filter_student_year">Niên khóa</label>
                                <select id="filter_student_year" name="academic_year" class="filter-select form-select">
                                    <option value="">Tất cả</option>
                                    <?php foreach (($studentFilterOptions['academic_years'] ?? []) as $yearOption): ?>
                                        <?php $yearName = (string) ($yearOption['name'] ?? ''); ?>
                                        <option value="<?= htmlspecialchars($yearName, ENT_QUOTES, 'UTF-8') ?>" <?= $currentAcademicYear === $yearName ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($yearName, ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <label class="filter-label" for="filter_student_status">Trạng thái</label>
                                <select id="filter_student_status" name="status" class="filter-select form-select">
                                    <option value="">Tất cả</option>
                                    <?php foreach ($statusOptions as $option): ?>
                                        <option value="<?= htmlspecialchars((string) $option['value'], ENT_QUOTES, 'UTF-8') ?>" <?= $currentStatusFilter === $option['value'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars((string) $option['label'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <div class="filter-actions">
                                    <a href="?page=list_students" class="filter-clear btn btn-outline-secondary">Đặt lại</a>
                                    <button type="submit" class="filter-apply btn btn-primary">Lọc</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body card-body">
            <?php if (empty($students)): ?>
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
                                <th>MSSV</th>
                                <th>Họ và tên</th>
                                <th>Lớp</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Khóa học</th>
                                <th>Trạng thái</th>
                                <?php if ($showActions): ?>
                                    <th>Thao tác</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $index => $student): ?>
                                <?php
                                    $studentId = (int) ($student['id'] ?? 0);
                                    $currentStatus = trim((string) ($student['status'] ?? ''));
                                    $studentName = trim((string) ($student['full_name'] ?? ''));
                                    if ($studentName === '') {
                                        $studentName = trim((string) ($student['mssv'] ?? ''));
                                    }
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) (($current_page - 1) * $items_per_page + $index + 1)) ?></td>
                                    <td><?= htmlspecialchars($student['mssv'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($studentName) ?></td>
                                    <td><?= htmlspecialchars($student['class_name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['email'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['phone'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['academic_year'] ?? '') ?></td>
                                    <td class="col-status">
                                        <?php if ($canChangeStatusStudent): ?>
                                            <?php $isLegacyStatus = $currentStatus !== '' && !in_array($currentStatus, $statusValues, true); ?>
                                            <form method="post" action="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '?page=list_students', ENT_QUOTES, 'UTF-8') ?>" class="status-form">
                                                <input type="hidden" name="action" value="status" />
                                                <input type="hidden" name="_row_id" value="<?= $studentId ?>" />
                                                <?php if ($isLegacyStatus): ?>
                                                    <span class="status-badge legacy"><?= htmlspecialchars($statusLabel($currentStatus), ENT_QUOTES, 'UTF-8') ?></span>
                                                <?php endif; ?>
                                                <select name="status[<?= $studentId ?>]" class="status-select <?= htmlspecialchars($statusClass($currentStatus), ENT_QUOTES, 'UTF-8') ?> form-select" data-previous-value="<?= htmlspecialchars($currentStatus, ENT_QUOTES, 'UTF-8') ?>" data-student-name="<?= htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8') ?>" onchange="updateStatusSelect(this)">
                                                    <?php if ($isLegacyStatus): ?>
                                                        <option value="" selected disabled>Chọn trạng thái</option>
                                                    <?php endif; ?>
                                                    <?php foreach ($statusOptions as $option): ?>
                                                        <option value="<?= htmlspecialchars((string) $option['value'], ENT_QUOTES, 'UTF-8') ?>" <?= $currentStatus === $option['value'] ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars((string) $option['label'], ENT_QUOTES, 'UTF-8') ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </form>
                                        <?php else: ?>
                                            <span class="status-badge <?= htmlspecialchars($statusClass($currentStatus), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($statusLabel($currentStatus), ENT_QUOTES, 'UTF-8') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if ($showActions): ?>
                                        <td>
                                            <div class="action-group">
                                                <?php if ($canEditStudent): ?>
                                                    <a href="?page=edit_student&id=<?= htmlspecialchars((string) ($student['id'] ?? '')) ?>" class="action-btn edit btn btn-outline-primary" title="Chỉnh sửa" aria-label="Chỉnh sửa sinh viên">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M15.5 3.5a2.121 2.121 0 1 1 3 3L18 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($canDeleteStudent): ?>
                                                    <form method="post" action="/KhoaLuan/public/admin.php?page=delete_student" style="display:inline">
                                                        <input type="hidden" name="student_id" value="<?= htmlspecialchars((string) ($student['id'] ?? '')) ?>" />
                                                        <button type="button" class="action-btn delete btn btn-danger" title="Xóa" aria-label="Xóa sinh viên" onclick="showStudentDeleteConfirm(<?= $studentId ?>)">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M19 7l-1 12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2l-1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3M9 11v6M15 11v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </button>
                                                    </form>
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
                        Hiển thị <?= $from ?> - <?= $to ?> của <?= $total_items ?> sinh viên
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

<style>
    .list-student-page { padding: 24px; }
    .page-panel { background: #ffffff; border: 1px solid #e8ecf3; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: visible; }
    .panel-header { position:relative; z-index:40; padding: 12px 14px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; border-radius:8px 8px 0 0; }
    .header-content { display:flex; justify-content:space-between; align-items:center; gap:12px; }
    .panel-title { font-size:14px; font-weight:700; color:#0f2a5a; margin:0; }
    .filter-wrap { position:relative; display:inline-flex; align-items:center; }
    .filter-btn { width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; color:#0f2a5a; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:0; }
    .filter-wrap.has-active .filter-btn { background:#eff6ff; border-color:#bfdbfe; color:#1d4ed8; }
    .filter-btn:hover { background:#f8fafc; color:#0b1f45; }
    .filter-menu { position:absolute; top:calc(100% + 6px); right:0; z-index:1000; display:none; width:300px; padding:12px; background:#fff; border:1px solid #e5e7eb; border-radius:8px; box-shadow:0 8px 20px rgba(15,42,90,0.12); }
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
    .col-action { width:10%; }
    .col-status { width:12%; }
    .action-group { display:flex; gap:8px; justify-content:center; }
    .action-btn { width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:0; text-decoration:none; }
    .action-btn.edit { color:#1d4ed8; }
    .action-btn.delete { color:#dc2626; }
    .status-form { display:inline-flex; align-items:center; justify-content:center; gap:8px; flex-wrap:wrap; }
    .status-badge { display:inline-flex; align-items:center; justify-content:center; min-width:92px; padding:6px 12px; border-radius:999px; font-size:12px; font-weight:700; white-space:nowrap; }
    .status-badge.active { background:#d1fae5; color:#065f46; }
    .status-badge.paused { background:#fef3c7; color:#92400e; }
    .status-badge.graduated { background:#e0e7ff; color:#3730a3; }
    .status-badge.dropped { background:#fee2e2; color:#991b1b; }
    .status-badge.legacy { background:#f3f4f6; color:#4b5563; border:1px solid #d1d5db; }
    .status-badge.unknown { background:#e5e7eb; color:#374151; }
    .data-table .status-select { min-width:150px; padding:6px 34px 6px 10px; border-radius:12px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; appearance:none; -webkit-appearance:none; font-weight:700; background-position:right 10px center; background-repeat:no-repeat; }
    .data-table .status-select option { color:#0f2a5a; background:#fff; }
    .data-table .status-select.active { background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%23065f46' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg,#d1fae5,#6ee7b7); background-size:12px, auto; color:#065f46; border-color:#34d399; }
    .data-table .status-select.paused { background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%2392400e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg,#fef3c7,#fbbf24); background-size:12px, auto; color:#92400e; border-color:#f59e0b; }
    .data-table .status-select.graduated { background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%233730a3' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg,#e0e7ff,#a5b4fc); background-size:12px, auto; color:#3730a3; border-color:#818cf8; }
    .data-table .status-select.dropped { background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%23991b1b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") no-repeat right 10px center, linear-gradient(90deg,#fee2e2,#fca5a5); background-size:12px, auto; color:#991b1b; border-color:#f87171; }
    .data-table .status-select.legacy { background:#f3f4f6; color:#4b5563; border-color:#d1d5db; }
    .data-table .status-select.unknown { background:#e5e7eb; color:#374151; border-color:#d1d5db; }
    .data-table .status-select:focus { outline:none; box-shadow:0 0 0 3px rgba(15,42,90,0.12); }
    .pagination-container { padding:16px 14px; border-top:1px solid #e8ecf3; display:flex; justify-content:space-between; align-items:center; font-size:12px; color:#6b7280; }
    .pagination { display:flex; gap:6px; align-items:center; }
    .pagination-btn { display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; color:#6b7280; font-weight:600; text-decoration:none; }
    .pagination-btn.active { background:linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); color:#fff; border-color:#0f2a5a; }
    .pagination-btn.disabled { opacity:.45; cursor:not-allowed; pointer-events:none; background:#f9fafb; color:#9ca3af; }
    @media (max-width:768px) {
        .filter-menu { right:-4px; width:min(300px, calc(100vw - 48px)); }
        .data-table { min-width:900px; }
    }
</style>

<script>
    function setStatusClass(el, value) {
        el.classList.remove('active', 'paused', 'graduated', 'dropped', 'legacy', 'unknown');

        switch (value) {
            case 'Đang học':
                el.classList.add('active');
                break;
            case 'Bảo lưu':
                el.classList.add('paused');
                break;
            case 'Đã tốt nghiệp':
                el.classList.add('graduated');
                break;
            case 'Đã thôi học':
                el.classList.add('dropped');
                break;
            case 'Tạm ngừng':
            case 'Kết thúc':
                el.classList.add('legacy');
                break;
            default:
                el.classList.add('unknown');
                break;
        }
    }

    function updateStatusSelect(el) {
        const nextStatus = el.value;
        const previousStatus = el.getAttribute('data-previous-value') || '';
        const studentName = el.getAttribute('data-student-name') || '';

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
            moduleLabel: 'sinh viên',
            targetName: studentName,
            previousStatus: previousStatus,
            nextStatus: nextStatus,
            question: 'Bạn có chắc chắn muốn thay đổi trạng thái sinh viên này không?',
            warning: 'Trạng thái sinh viên sẽ được cập nhật sau khi xác nhận.'
        });
    }

    function showStudentDeleteConfirm(id) {
        showDeleteConfirm(id, 'student', '');
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.status-select').forEach(function (select) {
            const previousStatus = select.getAttribute('data-previous-value') || select.value;
            select.setAttribute('data-previous-value', previousStatus);
            setStatusClass(select, previousStatus || select.value);
        });
    });

    (function() {
        const filterWrap = document.getElementById('studentFilter');
        const filterToggle = document.getElementById('studentFilterToggle');

        if (!filterWrap || !filterToggle) {
            return;
        }

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
    })();
</script>

<?php include __DIR__ . '/confirm/confirm_status_modal.php'; ?>
<?php include __DIR__ . '/confirm/confirm_delete_modal.php'; ?>
