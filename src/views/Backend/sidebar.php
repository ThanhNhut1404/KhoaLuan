<style>
    .nav-item { position: relative; }
    .nav-item > a {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .nav-caret {
        margin-left: auto;
        opacity: 0.8;
    }
    .nav-sub {
        margin-left: 30px;
        display: none;
        gap: 6px;
    }
    .nav-item.open .nav-sub { display: grid; }
    .nav-item.open .nav-caret { transform: rotate(180deg); }
    .nav-caret { transition: transform 0.2s ease; }
    .nav-sub a {
        padding: 6px 8px;
        font-size: 13px;
        color: #cbd5f5;
        text-decoration: none;
        border-radius: 8px;
    }
    .nav-sub a:hover { background: rgba(255,255,255,0.08); }
    .sidebar.collapsed .nav-sub { display: none; }
    .sidebar-divider {
    border: none;
    height: 1px;
    background: rgba(255,255,255,0.15);
    margin: 12px 10px;
}
    .brand-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .brand-title svg {
        width: 18px;
        height: 18px;
        stroke: #cbd5f5;
    }
</style>

<div class="sidebar">
    <div class="brand">
        <div class="brand-text">
            <div class="brand-title" style="font-weight:700;">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="3" stroke-width="2" />
                    <path d="M19.4 15a1 1 0 0 0 .2 1.1l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1 1 0 0 0-1.1-.2 1 1 0 0 0-.6.9V20a2 2 0 1 1-4 0v-.1a1 1 0 0 0-.6-.9 1 1 0 0 0-1.1.2l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1 1 0 0 0 .2-1.1 1 1 0 0 0-.9-.6H4a2 2 0 1 1 0-4h.1a1 1 0 0 0 .9-.6 1 1 0 0 0-.2-1.1l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1 1 0 0 0 1.1.2 1 1 0 0 0 .6-.9V4a2 2 0 1 1 4 0v.1a1 1 0 0 0 .6.9 1 1 0 0 0 1.1-.2l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1 1 0 0 0-.2 1.1 1 1 0 0 0 .9.6H20a2 2 0 1 1 0 4h-.1a1 1 0 0 0-.5.6Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                ADMIN
            </div>
        </div>
    </div>
<hr class="sidebar-divider">
    <nav class="nav">
        <a href="?page=dashboard">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 12h8V3H3v9Zm10 9h8v-7h-8v7ZM3 21h8v-7H3v7Zm10-9h8V3h-8v9Z" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span class="nav-text">Tổng quan</span>
        </a>
        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="3" width="18" height="18" rx="2" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M8 7h2M8 11h2M8 15h2M14 7h2M14 11h2M14 15h2" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />
                </svg>
                <span class="nav-text">Quản lý khoa/bộ môn</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Tạo tài khoản</span></a>
                <a href="#"><span class="nav-text">Danh sách khoa</span></a>
                <a href="#"><span class="nav-text">Danh sách bộ môn</span></a>
            </div>
        </div>
        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="4" width="18" height="12" rx="2" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M7 8h10M7 12h6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />
                    <path d="M7 20h10" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />
                </svg>
                <span class="nav-text">Quản lý lớp</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Tạo lớp</span></a>
                <a href="#"><span class="nav-text">Danh sách lớp</span></a>
            </div>
        </div>
        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="9" cy="7" r="3" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M22 21v-2a3 3 0 0 0-2-2.82" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
                    <path d="M17 3a3 3 0 0 1 0 6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="nav-text">Quản lý sinh viên</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Tạo tài khoản</span></a>
                <a href="#"><span class="nav-text">Danh sách tài khoản</span></a>
            </div>
        </div>
        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 4h16v16H4z" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M8 2v4M16 2v4M4 10h16" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="nav-text">Quản lý học kỳ</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Tạo học kỳ</span></a>
                <a href="#"><span class="nav-text">Danh sách học kỳ</span></a>
            </div>
        </div>
        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 12h3l2-4 4 8 2-4h5" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="nav-text">Quản lý hoạt động</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Tạo hoạt động</span></a>
                <a href="#"><span class="nav-text">Danh sách hoạt động</span></a>
            </div>
        </div>
        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="5" y="4" width="14" height="16" rx="2" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M9 3h6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />
                    <path d="M9 9h6M9 13h6M9 17h4" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />
                </svg>
                <span class="nav-text">Quản lý đánh giá</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Tạo phiếu đánh giá</span></a>
                <a href="#"><span class="nav-text">Danh sách đánh giá</span></a>
            </div>
        </div>
        <a href="#">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="3" y="5" width="18" height="14" rx="2" stroke="#cbd5f5" stroke-width="2" />
                <circle cx="9" cy="12" r="2" stroke="#cbd5f5" stroke-width="2" />
                <path d="M14 10h4M14 14h4" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />
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

<script>
    (function() {
        var navItems = document.querySelectorAll('.sidebar .nav-item');

        navItems.forEach(function(item) {
            var trigger = item.querySelector('a');
            var submenu = item.querySelector('.nav-sub');

            if (!trigger || !submenu) {
                return;
            }

            trigger.addEventListener('click', function(event) {
                event.preventDefault();
                item.classList.toggle('open');
            });
        });
    })();
</script>
