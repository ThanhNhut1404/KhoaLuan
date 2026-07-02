<?php
$currentPage = $page ?? ($_GET['page'] ?? 'dashboard');
$adminSession = $_SESSION['admin'] ?? [];
$sessionRoles = is_array($adminSession['roles'] ?? null) ? $adminSession['roles'] : [];
$roleIds = array_values(array_filter(array_map(
    static fn(array $role): mixed => $role['role_id'] ?? $role['MA_VAI_TRO'] ?? null,
    $sessionRoles
)));

if (empty($roleIds) && !empty($adminSession['MA_VAI_TRO'])) {
    $roleIds[] = $adminSession['MA_VAI_TRO'];
}

$permissionService = $permissionService ?? new \KhoaLuan\QLDRL\Services\PermissionService();
$sidebarMenu = $permissionService->buildSidebarMenu($roleIds);

if (!function_exists('renderBackendSidebarIcon')) {
    function renderBackendSidebarIcon(string $icon): string
    {
        $icons = [
            'dashboard' => '<path d="M4 13h6V4H4v9Zm10 7h6v-7h-6v7ZM4 21h6v-5H4v5Zm10-9h6V3h-6v9Z" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
            'department' => '<rect x="4" y="5" width="16" height="14" rx="2" stroke="#cbd5f5" stroke-width="2" /><path d="M4 10h16" stroke="#cbd5f5" stroke-width="2" /><path d="M8 14h4" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />',
            'major' => '<path d="M4 6h16v4H4z" stroke="#cbd5f5" stroke-width="2" /><path d="M4 12h16v6H4z" stroke="#cbd5f5" stroke-width="2" />',
            'class' => '<rect x="5" y="4" width="14" height="16" rx="2" stroke="#cbd5f5" stroke-width="2" /><path d="M5 10h14" stroke="#cbd5f5" stroke-width="2" /><path d="M5 14h14" stroke="#cbd5f5" stroke-width="2" />',
            'year' => '<rect x="4" y="4" width="16" height="16" rx="3" stroke="#cbd5f5" stroke-width="2" /><path d="M4 10h16" stroke="#cbd5f5" stroke-width="2" /><path d="M10 4v16" stroke="#cbd5f5" stroke-width="2" />',
            'semester' => '<circle cx="12" cy="12" r="7" stroke="#cbd5f5" stroke-width="2" /><path d="M12 8v4l3 2" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />',
            'student' => '<circle cx="9" cy="8" r="3" stroke="#cbd5f5" stroke-width="2" /><path d="M4 20v-2a7 7 0 0 1 10 0v2" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />',
            'account' => '<circle cx="12" cy="8" r="3" stroke="#cbd5f5" stroke-width="2" /><path d="M5 20v-2a7 7 0 0 1 14 0v2" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />',
            'criteria' => '<path d="M4 6h16" stroke="#cbd5f5" stroke-width="2" /><path d="M4 12h16" stroke="#cbd5f5" stroke-width="2" /><path d="M4 18h16" stroke="#cbd5f5" stroke-width="2" /><path d="M9 12l2 2 4-4" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />',
            'activity' => '<path d="M12 3l7 4-5 4 2 6-5-4-5 4 2-6-5-4h6l2-6Z" stroke="#cbd5f5" stroke-width="2" fill="none" />',
            'evaluation' => '<path d="M4 8h16" stroke="#cbd5f5" stroke-width="2" /><path d="M4 14h10" stroke="#cbd5f5" stroke-width="2" /><path d="M4 18h10" stroke="#cbd5f5" stroke-width="2" />',
            'statistics' => '<path d="M4 17h4v-7H4zM10 21h4v-11h-4zM16 13h4v-5h-4z" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" fill="none" />',
            'permission' => '<path d="M12 2l7 4v6c0 6-7 10-7 10S5 12 5 8V6l7-4Z" stroke="#cbd5f5" stroke-width="2" fill="none" /><path d="M8 10h8" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" /><path d="M12 14h.01" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />',
        ];

        return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' . ($icons[$icon] ?? $icons['dashboard']) . '</svg>';
    }
}
?>
<div class="sidebar">
    <div class="brand">
        <div class="brand-text">
            <div class="brand-title" style="font-weight:700;">
                <img src="/KhoaLuan/public/images/logo2.png" class="brand-image" alt="Logo" />
                ADMIN
            </div>
        </div>
    </div>
    <hr class="sidebar-divider">
    <nav class="nav flex-column">
        <?php foreach ($sidebarMenu as $parent): ?>
            <?php
                $children = $parent['children'] ?? [];
                $isSingleDashboard = count($children) === 1 && ($children[0]['page'] ?? '') === 'dashboard';
                $isOpen = array_reduce($children, static fn(bool $carry, array $child): bool => $carry || (($child['page'] ?? '') === $currentPage), false);
            ?>

            <?php if ($isSingleDashboard): ?>
                <a href="<?= htmlspecialchars($children[0]['url']) ?>" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <?= renderBackendSidebarIcon($parent['icon'] ?? 'dashboard') ?>
                    <span class="nav-text"><?= htmlspecialchars($children[0]['label']) ?></span>
                </a>
            <?php else: ?>
                <div class="nav-item<?= $isOpen ? ' open' : '' ?>">
                    <a href="#">
                        <?= renderBackendSidebarIcon($parent['icon'] ?? 'dashboard') ?>
                        <span class="nav-text"><?= htmlspecialchars($parent['label'] ?? '') ?></span>
                        <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <div class="nav-sub">
                        <?php foreach ($children as $child): ?>
                            <a href="<?= htmlspecialchars($child['url']) ?>" class="<?= ($child['page'] ?? '') === $currentPage ? 'active' : '' ?>">
                                <span class="nav-text"><?= htmlspecialchars($child['label']) ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>
</div>
