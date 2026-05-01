<div class="header">
    <div style="display:flex; align-items:center; gap:12px;">
        <button id="sidebarToggle" class="icon-btn" type="button" aria-label="Thu gọn menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
        <a href="#" aria-label="Tải lại trang quản trị" onclick="location.reload(); return false;">
            <img class="admin-logo" src="/KhoaLuan/public/images/logo1.png" alt="Logo" />
        </a>
    </div>

    <div class="header-search">
        <input type="text" placeholder="Tìm kiếm..." />
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm10 2-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
    </div>

    <div class="header-right">
        <a class="header-icon-link" href="#" aria-label="Thông báo">
            <span class="notif-badge">3</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M10 21a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Thông báo</span>
        </a>
        <div class="user-menu">
            <button id="userMenuBtn" class="admin-user-btn" type="button" aria-label="Tài khoản" aria-expanded="false">
                <span class="admin-user-avatar">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                        <path d="M4 20c1.6-3 5-4 8-4s6.4 1 8 4" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                <span>Nguyễn Văn A</span>
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
                <a href="#" role="menuitem">
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
