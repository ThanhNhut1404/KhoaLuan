<?php
    $rolePermissionRows = $rolePermissionRows ?? [];
    $rolePermissionDetails = $rolePermissionDetails ?? [];
    $roles = $roles ?? [];
    $filters = $filters ?? [];
    $pagination = $pagination ?? [];
    $current_page = (int) ($pagination['current_page'] ?? ($_GET['page_num'] ?? 1));
    $total_items = (int) ($pagination['total_items'] ?? count($rolePermissionRows));
    $items_per_page = (int) ($pagination['items_per_page'] ?? 10);
    $total_pages = max(1, (int) ($pagination['total_pages'] ?? ceil($total_items / max(1, $items_per_page))));
    $from = (int) ($pagination['from'] ?? ($total_items === 0 ? 0 : (($current_page - 1) * $items_per_page) + 1));
    $to = (int) ($pagination['to'] ?? min($total_items, $current_page * $items_per_page));
    $emptyMessage = $emptyMessage ?? 'Chưa có vai trò nào.';

    $currentKeyword = trim((string) ($filters['keyword'] ?? ($_GET['search'] ?? $_GET['keyword'] ?? $_GET['q'] ?? '')));
    $currentRoleId = trim((string) ($filters['role_id'] ?? ($_GET['role_id'] ?? '')));
    $hasActiveFilters = $currentKeyword !== '' || $currentRoleId !== '';

    $paginationUrl = static function (int $pageNum): string {
        $params = $_GET;
        $params['page'] = 'list_role_permissions';
        $params['page_num'] = $pageNum;

        return '?' . http_build_query($params);
    };

    $roleLabel = static function (string $roleName): string {
        $labels = [
            'ADMIN' => 'Admin',
            'DOAN_TRUONG' => 'Đoàn trường',
            'KHOA' => 'Khoa',
            'BO_MON' => 'Bộ môn',
            'DOAN_KHOA' => 'Đoàn khoa',
            'LIEN_CHI' => 'Liên chi / CLB',
            'CO_VAN_HOC_TAP' => 'Cố vấn học tập',
            'CAN_BO_LOP' => 'Cán bộ lớp',
            'SINH_VIEN' => 'Sinh viên',
            'GIANG_VIEN' => 'Giảng viên',
        ];

        return $labels[$roleName] ?? str_replace('_', ' ', $roleName);
    };

    $modalDetails = [];
    foreach ($rolePermissionDetails as $roleId => $detail) {
        $groups = [];
        foreach (($detail['groups'] ?? []) as $module => $functions) {
            $items = [];
            foreach ($functions as $function) {
                $items[] = [
                    'name' => (string) ($function['TEN_CHUC_NANG'] ?? ''),
                    'page' => (string) ($function['PAGE'] ?? ''),
                ];
            }

            $groups[] = [
                'module' => (string) $module,
                'items' => $items,
            ];
        }

        $modalDetails[(string) $roleId] = [
            'roleName' => $roleLabel((string) ($detail['role_name'] ?? '')),
            'roleCode' => (string) ($detail['role_name'] ?? ''),
            'menuCount' => (int) ($detail['menu_count'] ?? 0),
            'functionCount' => (int) ($detail['function_count'] ?? 0),
            'groups' => $groups,
        ];
    }
?>

<div class="list-role-permissions-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH PHÂN QUYỀN</h2>
                <div class="filter-wrap <?= $hasActiveFilters ? 'has-active' : '' ?>" id="rolePermissionFilter">
                    <button type="button" id="rolePermissionFilterToggle" class="filter-btn btn btn-outline-secondary" title="Bộ lọc" aria-label="Bộ lọc" aria-expanded="false">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 5h16l-6 7v5l-4 2v-7L4 5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="filter-menu" id="rolePermissionFilterMenu" role="menu" aria-labelledby="rolePermissionFilterToggle">
                        <form method="GET" action="/KhoaLuan/public/admin.php" class="filter-form">
                            <input type="hidden" name="page" value="list_role_permissions" />
                            <?php if ($currentKeyword !== ''): ?>
                                <input type="hidden" name="search" value="<?= htmlspecialchars($currentKeyword, ENT_QUOTES, 'UTF-8') ?>" />
                            <?php endif; ?>

                            <label class="filter-label" for="filter_role_id">Vai trò</label>
                            <select id="filter_role_id" name="role_id" class="filter-select form-select">
                                <option value="">Tất cả</option>
                                <?php foreach ($roles as $role): ?>
                                    <?php
                                        $roleId = (string) ($role['MA_VAI_TRO'] ?? '');
                                        $roleName = (string) ($role['TEN_VAI_TRO'] ?? '');
                                    ?>
                                    <option value="<?= htmlspecialchars($roleId, ENT_QUOTES, 'UTF-8') ?>" <?= $currentRoleId === $roleId ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($roleLabel($roleName), ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <div class="filter-actions">
                                <a href="?page=list_role_permissions<?= $currentKeyword !== '' ? '&search=' . urlencode($currentKeyword) : '' ?>" class="filter-clear btn btn-outline-secondary">Đặt lại</a>
                                <button type="submit" class="filter-apply btn btn-primary">Lọc</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body card-body">
            <?php if (empty($rolePermissionRows)): ?>
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2l7 4v6c0 6-7 10-7 10S5 12 5 8V6l7-4Z" stroke="currentColor" stroke-width="2" fill="none" />
                        <path d="M8 10h8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M12 14h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <h3><?= htmlspecialchars($emptyMessage, ENT_QUOTES, 'UTF-8') ?></h3>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="col-stt">STT</th>
                                <th class="col-role">VAI TRÒ</th>
                                <th class="col-menu-count">SỐ MENU</th>
                                <th class="col-function-count">SỐ CHỨC NĂNG</th>
                                <th class="col-action">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rolePermissionRows as $index => $row): ?>
                                <?php
                                    $roleId = (int) ($row['MA_VAI_TRO'] ?? 0);
                                    $roleName = (string) ($row['TEN_VAI_TRO'] ?? '');
                                    $stt = (($current_page - 1) * $items_per_page) + $index + 1;
                                ?>
                                <tr data-id="<?= $roleId ?>">
                                    <td class="col-stt"><?= $stt ?></td>
                                    <td class="col-role"><?= htmlspecialchars($roleLabel($roleName), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="col-menu-count"><?= (int) ($row['menu_count'] ?? 0) ?></td>
                                    <td class="col-function-count"><?= (int) ($row['function_count'] ?? 0) ?></td>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <button type="button" class="action-btn view btn btn-outline-primary" title="Xem" aria-label="Xem" onclick="openRolePermissionDetail(<?= $roleId ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6" />
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
                        Hiển thị <?= $from ?> - <?= $to ?> của <?= $total_items ?> vai trò
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

<div class="permission-detail-modal" id="permissionDetailModal" aria-hidden="true">
    <div class="permission-detail-backdrop" data-close-role-permission-detail></div>
    <div class="permission-detail-card modal-content" role="dialog" aria-modal="true" aria-labelledby="permissionDetailTitle">
        <div class="permission-detail-header modal-header">
            <div>
                <div class="permission-detail-eyebrow">Chi tiết phân quyền</div>
                <h3 class="permission-detail-title modal-title" id="permissionDetailTitle">Vai trò</h3>
            </div>
            <button type="button" class="permission-detail-close modal-close" aria-label="Đóng" data-close-role-permission-detail>&times;</button>
        </div>
        <div class="permission-detail-body modal-body">
            <div class="permission-summary">
                <div class="summary-item">
                    <span>Tên vai trò</span>
                    <strong id="detailRoleName">--</strong>
                </div>
                <div class="summary-item">
                    <span>Mã vai trò</span>
                    <strong id="detailRoleCode">--</strong>
                </div>
                <div class="summary-item">
                    <span>Tổng số menu</span>
                    <strong id="detailMenuCount">0</strong>
                </div>
                <div class="summary-item">
                    <span>Tổng số chức năng</span>
                    <strong id="detailFunctionCount">0</strong>
                </div>
            </div>
            <div class="permission-detail-groups" id="detailPermissionGroups"></div>
        </div>
    </div>
</div>

<script type="application/json" id="rolePermissionDetailData"><?= json_encode($modalDetails, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?></script>

<style>
    .list-role-permissions-page { display:grid; gap:0; padding:24px; }
    .page-panel { background:#fff; border:1px solid #e8ecf3; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden; }
    .panel-header { padding:12px 14px; border-bottom:1px solid #e5e7eb; background:#f9fafb; }
    .header-content { display:flex; justify-content:space-between; align-items:center; gap:12px; }
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
    .col-stt { width:6%; }
    .col-role { width:42%; }
    .col-menu-count,
    .col-function-count { width:18%; white-space:nowrap; }
    .col-action { width:16%; }
    .action-group { display:flex; align-items:center; justify-content:center; gap:8px; }
    .action-btn { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border:1px solid #e5e7eb; border-radius:6px; background:#fff; cursor:pointer; transition:all .2s; padding:0; }
    .action-btn:hover { border-color:#d1d5db; background:#f9fafb; }
    .action-btn.view { color:#1d4ed8; }
    .action-btn.view:hover { background:#eff6ff; }
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
    .permission-detail-modal { position:fixed; inset:0; z-index:1200; display:none; align-items:center; justify-content:center; padding:20px; }
    .permission-detail-modal.open { display:flex; }
    .permission-detail-backdrop { position:absolute; inset:0; background:rgba(15,23,42,0.38); }
    .permission-detail-card { position:relative; width:min(820px,100%); max-height:min(86vh,760px); overflow:hidden; background:#fff; border:1px solid #e8ecf3; border-radius:12px; box-shadow:0 24px 60px rgba(15,23,42,0.22); display:flex; flex-direction:column; }
    .permission-detail-header { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:16px 18px; border-bottom:1px solid #eef2ff; background:#f8faff; }
    .permission-detail-eyebrow { font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:.08em; }
    .permission-detail-title { margin:3px 0 0; font-size:16px; font-weight:800; color:#0f2a5a; }
    .permission-detail-close { border:none; background:transparent; color:#0f2a5a; font-size:22px; cursor:pointer; width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; }
    .permission-detail-close:hover { background:#f1f5f9; }
    .permission-detail-body { padding:18px; overflow:auto; display:grid; gap:16px; }
    .permission-summary { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:10px; }
    .summary-item { border:1px solid #e8ecf3; border-radius:8px; padding:10px 12px; background:#fff; min-width:0; }
    .summary-item span { display:block; font-size:12px; font-weight:700; color:#64748b; }
    .summary-item strong { display:block; margin-top:4px; color:#0f2a5a; font-size:15px; font-weight:800; overflow-wrap:anywhere; }
    .permission-detail-groups { display:grid; gap:12px; }
    .detail-group { border:1px solid #e8ecf3; border-radius:8px; overflow:hidden; background:#fff; }
    .detail-group-title { display:flex; justify-content:space-between; align-items:center; gap:8px; padding:12px 14px; background:#f9fafb; border-bottom:1px solid #e5e7eb; color:#0f2a5a; font-size:13px; font-weight:800; text-transform:uppercase; }
    .detail-count { min-width:24px; height:24px; border-radius:999px; background:#eef2f7; display:inline-flex; align-items:center; justify-content:center; font-size:12px; }
    .detail-list { display:grid; }
    .detail-item { display:flex; align-items:center; gap:10px; padding:10px 14px; border-top:1px solid #eef2f7; color:#1f2937; font-size:13px; font-weight:700; }
    .detail-item:first-child { border-top:0; }
    .detail-check { color:#16a34a; font-weight:900; }
    .detail-empty { margin:0; color:#64748b; font-size:13px; font-weight:700; padding:14px; border:1px dashed #cbd5e1; border-radius:8px; background:#f8fafc; }
    @media (max-width:1024px) { .data-table { font-size:12px; } .data-table th,.data-table td { padding:10px 12px; } .permission-summary { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media (max-width:768px) { .pagination-container { align-items:flex-start; flex-direction:column; gap:10px; } .filter-menu { right:-4px; width:min(280px, calc(100vw - 48px)); } .data-table { min-width:760px; } .permission-summary { grid-template-columns:1fr; } .permission-detail-card { max-height:88vh; } }
</style>

<script>
    (function() {
        const filterWrap = document.getElementById('rolePermissionFilter');
        const filterToggle = document.getElementById('rolePermissionFilterToggle');
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
        }

        const modal = document.getElementById('permissionDetailModal');
        const groupsWrap = document.getElementById('detailPermissionGroups');
        const detailDataNode = document.getElementById('rolePermissionDetailData');
        const detailData = detailDataNode ? JSON.parse(detailDataNode.textContent || '{}') : {};

        function setText(id, value) {
            const node = document.getElementById(id);
            if (node) {
                node.textContent = value;
            }
        }

        function appendText(parent, tag, className, value) {
            const node = document.createElement(tag);
            if (className) {
                node.className = className;
            }
            node.textContent = value;
            parent.appendChild(node);
            return node;
        }

        window.openRolePermissionDetail = function(roleId) {
            if (!modal || !groupsWrap) return;
            const detail = detailData[String(roleId)];
            if (!detail) return;

            setText('permissionDetailTitle', detail.roleName || 'Vai trò');
            setText('detailRoleName', detail.roleName || '--');
            setText('detailRoleCode', detail.roleCode || '--');
            setText('detailMenuCount', String(detail.menuCount || 0));
            setText('detailFunctionCount', String(detail.functionCount || 0));
            groupsWrap.innerHTML = '';

            if (!detail.groups || detail.groups.length === 0) {
                appendText(groupsWrap, 'p', 'detail-empty', 'Vai trò này chưa được cấp chức năng nào.');
            } else {
                detail.groups.forEach(function(group) {
                    const section = document.createElement('section');
                    section.className = 'detail-group';

                    const title = document.createElement('div');
                    title.className = 'detail-group-title';
                    appendText(title, 'span', '', group.module || 'Khác');
                    appendText(title, 'span', 'detail-count', String((group.items || []).length));
                    section.appendChild(title);

                    const list = document.createElement('div');
                    list.className = 'detail-list';
                    (group.items || []).forEach(function(item) {
                        const row = document.createElement('div');
                        row.className = 'detail-item';
                        appendText(row, 'span', 'detail-check', '✓');
                        appendText(row, 'span', '', item.name || item.page || '--');
                        list.appendChild(row);
                    });
                    section.appendChild(list);
                    groupsWrap.appendChild(section);
                });
            }

            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        };

        function closeModal() {
            if (!modal) return;
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('[data-close-role-permission-detail]').forEach(function(node) {
            node.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (filterWrap && filterToggle) {
                    filterWrap.classList.remove('open');
                    filterToggle.setAttribute('aria-expanded', 'false');
                }
                closeModal();
            }
        });
    })();
</script>
