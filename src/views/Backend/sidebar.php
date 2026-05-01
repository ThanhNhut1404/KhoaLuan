<style>
    .nav-item { position: relative; }
    .nav-sub {
        margin-left: 30px;
        display: none;
        gap: 6px;
    }
    .nav-item:hover .nav-sub { display: grid; }
    .nav-sub a {
        padding: 6px 8px;
        font-size: 13px;
        color: #cbd5f5;
        text-decoration: none;
        border-radius: 8px;
    }
    .nav-sub a:hover { background: rgba(255,255,255,0.08); }
    .sidebar.collapsed .nav-sub { display: none; }
</style>

<div class="sidebar">
    <div class="brand">
        <div class="brand-text">
            <div style="font-weight:700;">ADMIN</div>
            <div style="font-size:12px; color:#94a3b8;">Bảng điều khiển</div>
        </div>
    </div>

    <nav class="nav">
        <a href="?page=dashboard">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 12h8V3H3v9Zm10 9h8v-7h-8v7ZM3 21h8v-7H3v7Zm10-9h8V3h-8v9Z" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="nav-text">Tổng quan</span>
        </a>
        <a href="#">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
                <path d="M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="nav-text">Quản lý sinh viên</span>
        </a>
        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2v6l4 2" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="12" cy="13" r="9" stroke="#cbd5f5" stroke-width="2" />
                </svg>
                <span class="nav-text">Quản lý hoạt động</span>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Tạo hoạt động</span></a>
                <a href="#"><span class="nav-text">Danh sách hoạt động</span></a>
            </div>
        </div>
        <a href="#">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 4h16v16H4z" stroke="#cbd5f5" stroke-width="2" />
                <path d="M8 2v4M16 2v4M4 10h16" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="nav-text">Quản lý học kỳ</span>
        </a>
        <a href="#">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6h16M4 12h16M4 18h16" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
                <circle cx="8" cy="6" r="2" stroke="#cbd5f5" stroke-width="2" />
                <circle cx="16" cy="12" r="2" stroke="#cbd5f5" stroke-width="2" />
                <circle cx="10" cy="18" r="2" stroke="#cbd5f5" stroke-width="2" />
            </svg>
            <span class="nav-text">Quản lý tài khoản</span>
        </a>
        <a href="#">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 3l7 3v6c0 5-3.5 8-7 9-3.5-1-7-4-7-9V6l7-3Z" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
                <path d="M9.5 12.5 11 14l3.5-4" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="nav-text">Quản lý phân quyền</span>
        </a>
    </nav>
</div>
