<header class="student-header">
    <div class="header-left">
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
            <span>Điểm Rèn Luyện</span>
        </div>
    </div>
    
    <div class="header-center">
        <!-- THANH TIM KIEM -->
        <div class="search-box">
            <input type="text" placeholder="Tìm kiếm..." class="search-input">
            <button class="search-btn"><i class="fas fa-search"></i></button>
        </div>
    </div>
    
    <div class="header-right">
        <!-- ICON TRANG CHU VA THONG BAO -->
        <a href="#" class="header-icon-link" title="Trang chủ">
            <i class="fas fa-home"></i> <span>Trang chủ</span>
        </a>
        <a href="#" class="header-icon-link" title="Thông báo">
            <i class="fas fa-bell"></i> <span>Thông báo</span>
        </a>

        <!-- DROPDOWN USER -->
        <div class="user-dropdown">
            <button class="user-btn" onclick="toggleUserMenu()" title="Thông tin cá nhân">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <span class="user-name">Nguyễn Văn A</span>
            </button>
            
            <div class="dropdown-menu" id="userMenu">
                <a href="#profile" onclick="closeUserMenu()">
                    <i class="fas fa-id-card"></i> Thông tin cá nhân
                </a>
                <a href="#change-password" onclick="closeUserMenu()">
                    <i class="fas fa-key"></i> Đổi mật khẩu
                </a>
                <div class="dropdown-divider"></div>
                <a href="#logout" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
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
