<div class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li><a href="#dashboard" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 12h8V3H3v9Zm10 9h8v-7h-8v7ZM3 21h8v-7H3v7Zm10-9h8V3h-8v9Z" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Trang chủ</span></a></li>
        <li><a href="#notifications" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.7 21a2 2 0 0 1-3.4 0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Thông báo</span></a></li>
        <li class="has-submenu">
            <a href="#" onclick="return false;">
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
                <li><a href="#student-info" onclick="closeSidebar()">Thông tin sinh viên</a></li>
                <li><a href="#change-password" onclick="closeSidebar()">Đổi mật khẩu</a></li>
            </ul>
        </li>
        <li class="has-submenu">
            <a href="#" onclick="return false;">
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
                <li><a href="#activities-new" onclick="closeSidebar()">Đăng ký hoạt động</a></li>
                <li><a href="#activities-registered" onclick="closeSidebar()">Hoạt động đã đăng ký</a></li>
                <li><a href="#activities-joined" onclick="closeSidebar()">Hoạt động đã tham gia</a></li>
                <li><a href="#activities-calendar" onclick="closeSidebar()">Lịch hoạt động</a></li>
            </ul>
        </li>
        <li class="has-submenu">
            <a href="#" onclick="return false;">
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
                <li><a href="#evidence" onclick="closeSidebar()">Khai báo minh chứng</a></li>
                <li><a href="#evaluation" onclick="closeSidebar()">Phiếu đánh giá</a></li>
                <li><a href="#discipline-result" onclick="closeSidebar()">Kết quả rèn luyện</a></li>
            </ul>
        </li>
        <li><a href="#profile" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72 12.7 12.7 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.22a2 2 0 0 1 2.11-.45 12.7 12.7 0 0 0 2.81.7A2 2 0 0 1 22 16.92Z" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Liên hệ</span></a></li>
        <li><a href="#contact" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M16 17l5-5-5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 12H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Đăng xuất</span></a></li>
    </ul>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
