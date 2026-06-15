<?php $currentPage = $page ?? ($_GET['page'] ?? 'dashboard'); ?>
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
        <a href="?page=dashboard" class="<?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 13h6V4H4v9Zm10 7h6v-7h-6v7ZM4 21h6v-5H4v5Zm10-9h6V3h-6v9Z" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="nav-text">Tổng quan</span>
        </a>

        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="5" width="16" height="14" rx="2" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M4 10h16" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M8 14h4" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />
                </svg>
                <span class="nav-text">Quản lý khoa/bộ môn</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Danh sách khoa</span></a>
                <a href="#"><span class="nav-text">Danh sách bộ môn</span></a>
            </div>
        </div>

        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6h16v4H4z" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M4 12h16v6H4z" stroke="#cbd5f5" stroke-width="2" />
                </svg>
                <span class="nav-text">Quản lý ngành học</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Danh sách ngành học</span></a>
            </div>
        </div>

        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="5" y="4" width="14" height="16" rx="2" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M5 10h14" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M5 14h14" stroke="#cbd5f5" stroke-width="2" />
                </svg>
                <span class="nav-text">Quản lý lớp</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Danh sách lớp</span></a>
            </div>
        </div>

        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="4" width="16" height="16" rx="3" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M4 10h16" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M10 4v16" stroke="#cbd5f5" stroke-width="2" />
                </svg>
                <span class="nav-text">Quản lý niên khóa</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Danh sách niên khóa</span></a>
            </div>
        </div>

        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="7" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M12 8v4l3 2" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />
                </svg>
                <span class="nav-text">Quản lý học kỳ</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Danh sách học kỳ</span></a>
            </div>
        </div>

        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="9" cy="8" r="3" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M4 20v-2a7 7 0 0 1 10 0v2" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />
                </svg>
                <span class="nav-text">Quản lý sinh viên</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Danh sách sinh viên</span></a>
            </div>
        </div>

        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2l7 4v6c0 6-7 10-7 10S5 12 5 8V6l7-4Z" stroke="#cbd5f5" stroke-width="2" fill="none" />
                </svg>
                <span class="nav-text">Quản lý đoàn trường</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Danh sách đoàn trường</span></a>
            </div>
        </div>

        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6h16" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M4 12h16" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M4 18h16" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M9 12l2 2 4-4" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />
                </svg>
                <span class="nav-text">Quản lý tiêu chí điểm</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Danh sách tiêu chí điểm</span></a>
            </div>
        </div>

        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 3l7 4-5 4 2 6-5-4-5 4 2-6-5-4h6l2-6Z" stroke="#cbd5f5" stroke-width="2" fill="none" />
                </svg>
                <span class="nav-text">Quản lý hoạt động</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Danh sách hoạt động</span></a>
            </div>
        </div>

        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 8h16" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M4 14h10" stroke="#cbd5f5" stroke-width="2" />
                    <path d="M4 18h10" stroke="#cbd5f5" stroke-width="2" />
                </svg>
                <span class="nav-text">Quản lý đánh giá</span>
                <svg class="nav-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 9l6 6 6-6" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <div class="nav-sub">
                <a href="#"><span class="nav-text">Danh sách đánh giá</span></a>
            </div>
        </div>

        <div class="nav-item">
            <a href="#">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 17h4v-7H4zM10 21h4v-11h-4zM16 13h4v-5h-4z" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" fill="none" />
                </svg>
                <span class="nav-text">Quản lý thống kê</span>
            </a>
        </div>

        <a href="#">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="8" r="3" stroke="#cbd5f5" stroke-width="2" />
                <path d="M5 20v-2a7 7 0 0 1 14 0v2" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />
            </svg>
            <span class="nav-text">Quản lý tài khoản</span>
        </a>

        <a href="#">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2l7 4v6c0 6-7 10-7 10S5 12 5 8V6l7-4Z" stroke="#cbd5f5" stroke-width="2" fill="none" />
                <path d="M8 10h8" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />
                <path d="M12 14h.01" stroke="#cbd5f5" stroke-width="2" stroke-linecap="round" />
            </svg>
            <span class="nav-text">Quản lý phân quyền</span>
        </a>
    </nav>
</div>
