<style>
    /* Reduce spacing for main menu items; add extra top gap for first item */
    .sidebar .sidebar-menu { padding: 10px 0 6px 0; }
    .sidebar .sidebar-menu li:first-child a { padding-top: 18px; }
    .sidebar .sidebar-menu li { margin: 0; }
    .sidebar .sidebar-menu li a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
    }
    .sidebar .sidebar-menu li a svg { width: 18px; height: 18px; min-width: 18px; }
    .sidebar .sidebar-menu li a span { font-size: 14px; line-height: 1; }
    .sidebar .sidebar-menu .has-submenu > a { padding-right: 12px; }
    .sidebar .sidebar-menu .submenu li a { padding: 6px 28px; font-size: 13px; }
    /* Submenu slide/opacity/transform transition and caret rotation (slower, smoother) */
    .sidebar .sidebar-menu .submenu {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transform: translateY(-8px);
        transition: max-height 420ms cubic-bezier(.2,.8,.2,1), opacity 300ms ease, transform 360ms cubic-bezier(.2,.8,.2,1);
        pointer-events: none;
    }
    .sidebar .sidebar-menu li.has-submenu.open > .submenu {
        max-height: 560px;
        opacity: 1;
        transform: translateY(0);
        transition-delay: 80ms;
        pointer-events: auto;
    }
    .sidebar .sidebar-menu .submenu-caret { transition: transform 260ms cubic-bezier(.2,.8,.2,1); }
    .sidebar .sidebar-menu li.has-submenu.open > a .submenu-caret { transform: rotate(180deg); }
    .student-theme-switcher {
        padding: 6px 12px 0;
        border-top: 1px solid #eef1f6;
    }
    .student-theme-title {
        margin: 0 0 6px;
        font-size: 11px;
        font-weight: 800;
        color: var(--text-muted);
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }
    .student-theme-options {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .student-theme-dot {
        width: 24px;
        height: 24px;
        border: 2px solid rgba(255,255,255,0.95);
        border-radius: 50%;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        box-shadow: 0 0 0 1px #d8deea;
        transition: transform 0.16s ease, box-shadow 0.16s ease;
    }
    .student-theme-dot:hover {
        transform: scale(1.08);
    }
    .student-theme-dot.is-active {
        box-shadow: 0 0 0 2px var(--primary), 0 4px 10px rgba(var(--primary-rgb), 0.18);
    }
    .student-theme-dot i {
        font-size: 10px;
        opacity: 0;
        transform: scale(0.8);
        transition: opacity 0.16s ease, transform 0.16s ease;
    }
    .student-theme-dot.is-active i {
        opacity: 1;
        transform: scale(1);
    }
    .student-theme-dot[data-theme-value="blue"] { background: #1d4ed8; }
    .student-theme-dot[data-theme-value="red"] { background: #d90429; }
    .student-theme-dot[data-theme-value="green"] { background: #16a34a; }
    .student-theme-dot[data-theme-value="purple"] { background: #7c3aed; }
    .student-theme-dot[data-theme-value="cyan"] { background: #0891b2; }
    .student-theme-dot[data-theme-value="orange"] { background: #f97316; }
</style>

<div class="sidebar" id="sidebar">
    <ul class="sidebar-menu nav flex-column">
        <li><a class="nav-link" href="/KhoaLuan/public/student.php" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 12h8V3H3v9Zm10 9h8v-7h-8v7ZM3 21h8v-7H3v7Zm10-9h8V3h-8v9Z" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Trang chủ</span></a></li>
        <li><a class="nav-link" href="/KhoaLuan/public/student.php?action=thongbao" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.7 21a2 2 0 0 1-3.4 0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Thông báo</span></a></li>
        <li class="has-submenu">
            <a class="nav-link" href="#" onclick="return false;">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="9" stroke-width="2"/>
                    <path d="M12 8h.01" stroke-width="2" stroke-linecap="round"/>
                    <path d="M11 12h1v4h1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Thông tin chung</span>
                <svg class="submenu-caret" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <ul class="submenu">
                <li><a class="nav-link" href="/KhoaLuan/public/student.php?action=profile" onclick="closeSidebar()">Thông tin sinh viên</a></li>
                <li><a class="nav-link" href="#" onclick="closeSidebar(); openPasswordModal(); return false;">Đổi mật khẩu</a></li>
            </ul>
        </li>
        <li class="has-submenu">
            <a class="nav-link" href="#" onclick="return false;">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 7h8M8 12h8M8 17h8" stroke-width="2" stroke-linecap="round"/>
                    <rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/>
                </svg>
                <span>Hoạt động</span>
                <svg class="submenu-caret" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <ul class="submenu">
                <li><a class="nav-link" href="/KhoaLuan/public/student.php?action=dangkyhoatdong" onclick="closeSidebar()">Đăng ký hoạt động</a></li>
                <li><a class="nav-link" href="/KhoaLuan/public/student.php?action=hoatdongdangky" onclick="closeSidebar()">Hoạt động đã đăng ký</a></li>
                <li><a class="nav-link" href="/KhoaLuan/public/student.php?action=lichhoatdong" onclick="closeSidebar()">Lịch hoạt động</a></li>
                <li><a class="nav-link" href="/KhoaLuan/public/student.php?action=diemdanhhoatdong" onclick="closeSidebar()">Điểm danh hoạt động</a></li>
                <li><a class="nav-link" href="/KhoaLuan/public/student.php?action=hoatdongdathamgia" onclick="closeSidebar()">Hoạt động đã tham gia</a></li>
            </ul>
        </li>
        <li class="has-submenu">
            <a class="nav-link" href="#" onclick="return false;">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 12a9 9 0 1 0 3-6.7" stroke-width="2" stroke-linecap="round"/>
                    <path d="M3 4v5h5" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 7v5l3 2" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Điểm rèn luyện</span>
                <svg class="submenu-caret" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <ul class="submenu">
                <li><a class="nav-link" href="/KhoaLuan/public/student.php?action=phieudanhgia" onclick="closeSidebar()">Phiếu đánh giá</a></li>
                <li><a class="nav-link" href="/KhoaLuan/public/student.php?action=ketquarenluyen" onclick="closeSidebar()">Kết quả rèn luyện</a></li>
            </ul>
        </li>
        <li><a class="nav-link" href="/KhoaLuan/public/student.php?action=logout" onclick="openStudentLogoutConfirm(event, this);">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M16 17l5-5-5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 12H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Đăng xuất</span></a></li>
    </ul>
    <div class="student-theme-switcher" aria-label="Chọn màu giao diện">
        <p class="student-theme-title">Màu giao diện</p>
        <div class="student-theme-options">
            <?php
                $currentThemeColor = strtolower(trim((string) ($_SESSION['theme_color'] ?? 'blue')));
                $themeOptions = ['blue', 'red', 'green', 'purple', 'cyan', 'orange'];
                if (!in_array($currentThemeColor, $themeOptions, true)) {
                    $currentThemeColor = 'blue';
                }
            ?>
            <?php foreach ($themeOptions as $themeOption): ?>
                <button
                    type="button"
                    class="student-theme-dot<?= $themeOption === $currentThemeColor ? ' is-active' : '' ?>"
                    data-theme-value="<?= htmlspecialchars($themeOption, ENT_QUOTES, 'UTF-8') ?>"
                    aria-label="Chọn màu <?= htmlspecialchars($themeOption, ENT_QUOTES, 'UTF-8') ?>"
                    aria-pressed="<?= $themeOption === $currentThemeColor ? 'true' : 'false' ?>">
                    <i class="fas fa-check" aria-hidden="true"></i>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
