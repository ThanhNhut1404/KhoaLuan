<?php
$student = $student ?? [];
$studentDisplayName = $_SESSION['student_name'] ?? trim((string) ($student['ho_ten'] ?? '')) ?: 'Sinh viên';

$studentHeaderAvatar = trim((string) ($student['avatar_url'] ?? ''));
if ($studentHeaderAvatar === '') {
    $sessionAvatar = trim((string) ($_SESSION['student_avatar'] ?? ''));
    $normalizeHeaderAvatar = static function (string $path): string {
        $path = trim(str_replace('\\', '/', $path));
        if ($path === '' || preg_match('#^https?://#i', $path)) {
            return '';
        }

        $path = parse_url($path, PHP_URL_PATH) ?: $path;
        $path = preg_replace('#^[A-Za-z]:/#', '/', $path) ?? $path;
        $path = preg_replace('#/+#', '/', $path) ?? $path;
        foreach (['/KhoaLuan/public/', 'KhoaLuan/public/', '/public/', 'public/'] as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $path = substr($path, strlen($prefix));
                break;
            }
        }

        $path = ltrim($path, '/');
        foreach (['uploads/avatars/', 'uploads/avatar/', 'upload/avatar/'] as $prefix) {
            $position = strpos($path, $prefix);
            if ($position !== false) {
                $filename = basename(substr($path, $position + strlen($prefix)));
                return $filename !== '' ? 'uploads/avatars/' . $filename : '';
            }
        }

        $filename = basename($path);
        return ($filename !== '' && $filename !== '.' && $filename !== '..') ? 'uploads/avatars/' . $filename : '';
    };

    $sessionAvatar = $normalizeHeaderAvatar($sessionAvatar);
    if ($sessionAvatar !== '') {
        $avatarPath = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $sessionAvatar);
        if (is_file($avatarPath)) {
            $studentHeaderAvatar = '/KhoaLuan/public/' . $sessionAvatar . '?v=' . filemtime($avatarPath);
        }
    }
}

$avatarInitial = $studentDisplayName !== ''
    ? (function_exists('mb_substr') ? mb_substr($studentDisplayName, 0, 1, 'UTF-8') : substr($studentDisplayName, 0, 1))
    : '?';
?>

<header class="student-header navbar navbar-expand-lg">
    <div class="header-left navbar-brand">
        <a class="logo" href="/KhoaLuan/public/student.php" aria-label="Trang sinh viên">
            <img class="header-logo" src="/KhoaLuan/public/images/logo1.png" alt="Logo" />
        </a>
    </div>
    <div class="header-right navbar-nav">
        <!-- THANH TIM KIEM -->
        <div class="search-box">
            <input type="text" placeholder="Tìm kiếm..." class="search-input form-control">
            <button class="search-btn btn btn-light" aria-label="Tìm kiếm">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm10 2-4.35-4.35" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        
        <!-- ICON TRANG CHU VA THONG BAO -->
        <a href="/KhoaLuan/public/student.php" class="header-icon-link btn btn-light nav-link" title="Trang chủ">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5Z" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Trang chủ</span>
        </a>
        <a href="/KhoaLuan/public/student.php?action=thongbao" class="header-icon-link btn btn-light nav-link" title="Thông báo">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7Z" stroke-width="2" stroke-linecap="round"/>
                <path d="M10 21a2 2 0 0 0 4 0" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Thông báo</span>
        </a>

        <!-- DROPDOWN USER -->
        <div class="user-dropdown dropdown">
            <button class="user-btn btn btn-light dropdown-toggle" onclick="toggleUserMenu()" title="Thông tin cá nhân">
                <div class="user-avatar">
                    <?php if ($studentHeaderAvatar !== ''): ?>
                        <img src="<?= htmlspecialchars($studentHeaderAvatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar">
                    <?php else: ?>
                        <span><?= htmlspecialchars($avatarInitial, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </div>
                <span class="user-name"><?= htmlspecialchars($studentDisplayName, ENT_QUOTES, 'UTF-8') ?></span>
                <svg class="user-caret" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            
            <div class="dropdown-menu" id="userMenu">
                <a class="dropdown-item" href="/KhoaLuan/public/student.php?action=profile" onclick="closeUserMenu()">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 5h16v14H4z" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 9h8M8 13h5" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Thông tin cá nhân
                </a>
                <a class="dropdown-item" href="#" onclick="closeUserMenu(); openPasswordModal(); return false;">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 15v4h4l9-9-4-4-9 9Z" stroke-width="2" stroke-linecap="round"/>
                        <path d="M14 6l4 4" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Đổi mật khẩu
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/KhoaLuan/public/student.php?action=logout" onclick="openStudentLogoutConfirm(event, this);">
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
</header>

<script>
    let _studentLogoutUrl = '/KhoaLuan/public/student.php?action=logout';

    function toggleUserMenu() {
        const menu = document.getElementById('userMenu');
        menu.classList.toggle('active');
    }

    function closeUserMenu() {
        const menu = document.getElementById('userMenu');
        menu.classList.remove('active');
    }

    // Đóng menu khi click ngoài
    document.addEventListener('click', function(event) {
        const userDropdown = document.querySelector('.user-dropdown');
        if (!userDropdown.contains(event.target)) {
            closeUserMenu();
        }
    });

    function openStudentLogoutConfirm(event, link) {
        if (event) {
            event.preventDefault();
        }

        if (link && link.href) {
            _studentLogoutUrl = link.href;
        }

        closeUserMenu();

        const modal = document.getElementById('confirmStudentLogoutModal');
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    function closeStudentLogoutConfirm() {
        const modal = document.getElementById('confirmStudentLogoutModal');
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    function confirmStudentLogout() {
        window.location.href = _studentLogoutUrl;
    }
</script>
