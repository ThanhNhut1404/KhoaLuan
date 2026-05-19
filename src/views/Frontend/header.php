<header class="student-header">
    <div class="header-left">
        <a class="logo" href="/KhoaLuan/public/student.php" aria-label="Trang sinh viên">
            <img class="header-logo" src="/KhoaLuan/public/images/logo1.png" alt="Logo" />
        </a>
    </div>
    <div class="header-right">
        <!-- THANH TIM KIEM -->
        <div class="search-box">
            <input type="text" placeholder="Tìm kiếm..." class="search-input">
            <button class="search-btn" aria-label="Tìm kiếm">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm10 2-4.35-4.35" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        
        <!-- ICON TRANG CHU VA THONG BAO -->
        <a href="/KhoaLuan/public/student.php" class="header-icon-link" title="Trang chủ">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5Z" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Trang chủ</span>
        </a>
        <a href="#" class="header-icon-link" title="Thông báo">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7Z" stroke-width="2" stroke-linecap="round"/>
                <path d="M10 21a2 2 0 0 0 4 0" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Thông báo</span>
        </a>

        <!-- DROPDOWN USER -->
        <div class="user-dropdown">
            <button class="user-btn" onclick="toggleUserMenu()" title="Thông tin cá nhân">
                <div class="user-avatar">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke-width="2" stroke-linecap="round"/>
                        <path d="M4 20c1.6-3 5-4 8-4s6.4 1 8 4" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <span class="user-name">Nguyễn Văn A</span>
                <svg class="user-caret" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            
            <div class="dropdown-menu" id="userMenu">
                <a href="/KhoaLuan/public/student.php?action=profile" onclick="closeUserMenu()">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 5h16v14H4z" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 9h8M8 13h5" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Thông tin cá nhân
                </a>
                <a href="#" onclick="closeUserMenu(); openPasswordModal(); return false;">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 15v4h4l9-9-4-4-9 9Z" stroke-width="2" stroke-linecap="round"/>
                        <path d="M14 6l4 4" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Đổi mật khẩu
                </a>
                <div class="dropdown-divider"></div>
                <a href="#logout" onclick="logout()">
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

    function logout() {
        if (confirm('Bạn có chắc muốn đăng xuất?')) {
            window.location.href = '/KhoaLuan/public/logout.php';
        }
    }
</script>
