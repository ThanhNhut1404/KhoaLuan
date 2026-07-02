<?php
$adminSession = $_SESSION['admin'] ?? [];
$adminName = $adminSession['TEN_DANG_NHAP'] ?? 'Admin';
$adminRole = $adminSession['TEN_VAI_TRO'] ?? '';
?>

<?php
    $currentPage = $_GET['page'] ?? 'dashboard';
    $searchablePages = ['list_khoa', 'list_major', 'list_class', 'list_semester', 'list_year', 'list_students', 'list_role_permissions'];
    $isHeaderSearchEnabled = in_array($currentPage, $searchablePages, true);
    $headerSearchValue = $isHeaderSearchEnabled
        ? trim((string) ($_GET['search'] ?? $_GET['keyword'] ?? $_GET['q'] ?? ''))
        : '';
?>

<div class="header navbar">
    <div class="d-flex align-items-center gap-3">
        <button id="sidebarToggle" class="icon-btn btn btn-outline-secondary" type="button" aria-label="Thu gọn menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        <!-- header logo removed per design: logo now shown in sidebar -->
    </div>

    <form id="headerSearchForm" class="header-search" method="GET" action="/KhoaLuan/public/admin.php" data-search-enabled="<?= $isHeaderSearchEnabled ? '1' : '0' ?>" data-list-page="<?= htmlspecialchars($currentPage) ?>">
        <input type="hidden" name="page" value="<?= htmlspecialchars($currentPage) ?>" />
        <?php if ($isHeaderSearchEnabled): ?>
            <?php foreach ($_GET as $key => $value): ?>
                <?php
                    if (in_array((string) $key, ['page', 'search', 'keyword', 'q', 'page_num', 'department', 'major'], true) || is_array($value)) {
                        continue;
                    }
                ?>
                <input type="hidden" name="<?= htmlspecialchars((string) $key, ENT_QUOTES, 'UTF-8') ?>" value="<?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') ?>" />
            <?php endforeach; ?>
        <?php endif; ?>
        <input id="headerSearchInput" type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($headerSearchValue) ?>" />
        <button type="submit" aria-label="Tìm kiếm">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm10 2-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        </button>
    </form>

    <div class="header-right">
        <a class="header-icon-link btn btn-light" href="#" aria-label="Thông báo">
            <span class="notif-badge">3</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M10 21a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Thông báo</span>
        </a>
        <div class="user-menu">
            <button id="userMenuBtn" class="admin-user-btn btn btn-light dropdown-toggle" type="button" aria-label="Tài khoản" aria-expanded="false">
                <span class="admin-user-avatar">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                        <path d="M4 20c1.6-3 5-4 8-4s6.4 1 8 4" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                <span><?= htmlspecialchars($adminName) ?><?= $adminRole !== '' ? ' - ' . htmlspecialchars($adminRole) : '' ?></span>
                <svg class="admin-user-caret" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div id="userMenu" class="user-dropdown" role="menu">
                <a href="#" role="menuitem">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 5h16v14H4z" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 9h8M8 13h5" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Thông tin cá nhân
                </a>
                <a href="#" role="menuitem" onclick="openAdminPasswordModal(); return false;">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 15v4h4l9-9-4-4-9 9Z" stroke-width="2" stroke-linecap="round"/>
                        <path d="M14 6l4 4" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Đổi mật khẩu
                </a>
                <div class="menu-divider" role="separator" aria-hidden="true"></div>
                <a href="/KhoaLuan/public/admin.php?page=logout" role="menuitem" onclick="showAdminLogoutConfirm(event, this)">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 17l-1 1a2 2 0 0 1-2.83 0L4 15.83a2 2 0 0 1 0-2.83L6.17 11a2 2 0 0 1 2.83 0l1 1" stroke-width="2" stroke-linecap="round"/>
                        <path d="M7 14h10" stroke-width="2" stroke-linecap="round"/>
                        <path d="M15 10l2 4-2 4" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Đăng xuất
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        var form = document.getElementById('headerSearchForm');
        var input = document.getElementById('headerSearchInput');
        if (!form || !input) return;

        var listPage = form.dataset.listPage || 'dashboard';
        var listUrl = '/KhoaLuan/public/admin.php?page=' + encodeURIComponent(listPage);
        var params = new URLSearchParams(window.location.search);
        var hasSearchQuery = params.has('search') || params.has('keyword') || params.has('q');

        function resetSearchList() {
            window.location.href = listUrl;
        }

        form.addEventListener('submit', function(event) {
            if (form.dataset.searchEnabled !== '1') {
                event.preventDefault();
                return;
            }

            if (input.value.trim() === '') {
                event.preventDefault();
                resetSearchList();
            }
        });

        input.addEventListener('input', function() {
            if (form.dataset.searchEnabled === '1' && hasSearchQuery && input.value.trim() === '') {
                resetSearchList();
            }
        });
    })();
</script>
