<style>
    .frontend-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 56px;
        background: #2c387e;
        color: #fff;
        display: flex;
        align-items: center;
        padding: 0 12px;
        z-index: 1000;
        box-sizing: border-box;
    }
    .frontend-header .menu-btn {
        font-size: 24px;
        cursor: pointer;
        margin-right: 12px;
        user-select: none;
    }
    /* slide-in menu for pages that use #menu */
    .menu, #sidebar {
        position: fixed;
        top: 56px;
        left: -250px;
        width: 250px;
        height: calc(100% - 56px);
        background: #2c387e;
        color: #fff;
        padding: 12px;
        box-sizing: border-box;
        transition: left .25s ease;
        z-index: 999;
    }
    .menu.active, #sidebar.active {
        left: 0;
    }
    /* ensure page content is not hidden under fixed header */
    .content { padding-top: 70px; }
</style>

<div class="frontend-header">
    <div class="menu-btn" onclick="toggleMenu()" aria-label="Mở menu">☰</div>
    <div>Hệ thống điểm danh sinh viên</div>
</div>

<!-- JS moved to public/js/menu.js -->
