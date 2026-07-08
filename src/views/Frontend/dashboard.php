<?php
    /**
     * Dữ liệu mẫu để render giao diện.
     * Khi nối DB, bạn chỉ cần truyền mảng $student từ Controller.
     */
    $student = $student ?? [];
?>

<style>
    .portal-grid {
        --dashboard-grid-gap: 16px;
        display: grid;
        grid-template-columns: 1fr;
        gap: var(--dashboard-grid-gap);
        align-items: start;
    }

    .portal-main {
        min-width: 0;
    }

    .portal-aside {
        min-width: 0;
        display: grid;
        grid-template-columns: 1fr;
        gap: var(--dashboard-grid-gap);
    }

    .portal-grid,
    .portal-main,
    .portal-aside,
    .portal-full,
    .news-card,
    .news-list,
    .news-item {
        min-width: 0;
        max-width: 100%;
    }

    .dashboard-top {
        display: grid;
        grid-template-columns: 1fr;
        gap: var(--dashboard-grid-gap);
        margin-bottom: 0;
    }

    .dashboard-top .student-profile {
        margin-bottom: 0;
    }

    .student-profile {
        max-width: none;
    }

    @media (min-width: 992px) {
        .portal-grid {
            grid-template-columns: minmax(0, 1.55fr) minmax(360px, 0.95fr);
            gap: var(--dashboard-grid-gap);
        }

        .dashboard-top {
            grid-template-columns: 1fr;
        }

        .portal-aside {
            grid-template-columns: 1fr;
        }
    }

    .portal-full {
        grid-column: 1 / -1;
    }

    .portal-full--stats {
        margin-top: 0;
    }

    .portal-grid .card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e8ecf3;
        overflow: hidden;
    }

    .news-card {
        display: flex;
        flex-direction: column;
    }

    .news-card .card-body {
        flex: 1;
        overflow: auto;
    }

    .portal-grid .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: #f8f9fb;
        border-bottom: 1px solid #e8ecf3;
    }

    .portal-grid .card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        color: var(--primary);
        font-size: 14px;
        letter-spacing: 0.2px;
        margin: 0;
    }

    .portal-grid .card-title svg {
        width: 16px;
        height: 16px;
        color: var(--secondary);
        stroke: currentColor;
    }

    .portal-grid .card-body {
        padding: 14px 16px;
    }

    .news-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        gap: 10px;
    }

    .news-item {
        display: grid;
        grid-template-columns: 54px 1fr;
        gap: 12px;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #f0f2f5;
        transition: 0.2s;
        background: #fff;
    }

    .news-item:hover {
        border-color: #dce1eb;
        box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.08);
        transform: translateY(-1px);
    }

    .student-profile .card-body {
        padding: 12px;
    }

    .student-profile-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        align-items: start;
    }

    @media (min-width: 768px) {
        .student-profile-grid {
            grid-template-columns: 126px 1fr;
            gap: 18px;
        }
    }

    .student-avatar {
        width: 104px;
        height: 139px;
        aspect-ratio: 3 / 4;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        overflow: hidden;
        margin: 0 auto;
    }

    .student-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .student-avatar i {
        font-size: 54px;
        opacity: 0.9;
    }

    .student-name {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
        margin: 2px 0 10px 0;
        line-height: 1.2;
    }

    .student-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 16px;
    }

    .student-field {
        display: flex;
        flex-wrap: nowrap;
        gap: 8px;
        align-items: center;
        padding: 0;
        border: none;
        border-radius: 0;
        background: transparent;
        min-width: 0;
    }

    .student-field .k {
        flex: 0 0 auto;
        min-width: auto;
        max-width: 180px;
        white-space: nowrap;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        text-transform: none;
        letter-spacing: 0;
    }

    .student-field .v {
        flex: 1 1 auto;
        min-width: 0;
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    @media (max-width: 480px) {
        .student-field {
            gap: 6px;
        }

        .student-field .k {
            max-width: 140px;
        }
    }

    .scores-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: var(--dashboard-grid-gap);
        margin-bottom: 0;
    }

    @media (min-width: 992px) {
        .scores-grid {
            grid-template-columns: minmax(0, 1fr) minmax(0, 0.85fr) minmax(0, 1.15fr);
            gap: var(--dashboard-grid-gap);
        }
    }

    @media (min-width: 992px) {
        .scores-section--registered {
            max-width: none;
            width: 100%;
        }
    }

    .scores-section--compact {
        margin-bottom: 0;
    }

    /* Kéo giãn khung placeholder để full chiều cao thẻ */
    .scores-section--fill {
        display: flex;
        flex-direction: column;
    }

    .scores-section--fill .chart-placeholder {
        flex: 1;
        margin-top: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }


    .scores-section--progress {
        max-width: 320px;
    }

    @media (min-width: 992px) {
        .scores-section--progress {
            max-width: none;
            width: 100%;
        }
    }

    /* Thu gọn thẻ "Chi Tiết Điểm Rèn Luyện": dài quá thì cuộn để xem */
    .scores-section--scroll .table-responsive {
        max-height: 320px;
        overflow: auto;
    }

    @media (min-width: 992px) {
        .scores-section--scroll .table-responsive {
            max-height: 360px;
        }
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(145px, 1fr));
        gap: var(--dashboard-grid-gap);
        margin-bottom: 0;
    }

    @media (min-width: 1200px) {
        .stats-container {
            grid-template-columns: repeat(6, minmax(0, 1fr));
        }
    }

    .stat-card {
        background: white;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: none;
        position: relative;
    }

    .stat-card--link {
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .stat-card:hover {
        transform: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .stat-icon {
        margin-bottom: 12px;
        color: var(--primary);
    }

    .stat-card--center {
        text-align: center;
    }

    .stat-card--center .stat-icon {
        display: flex;
        justify-content: center;
    }

    .stat-card--meta .stat-meta {
        position: absolute;
        right: 12px;
        top: 10px;
        font-size: 11px;
        color: var(--primary-muted);
        font-weight: 700;
    }

    .stat-icon svg {
        width: 32px;
        height: 32px;
        stroke: currentColor;
    }

    .stat-label {
        font-size: 13px;
        color: #6b7280;
        text-transform: none;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        transition: color 0.2s ease;
    }

    .stat-card:hover .stat-label {
        color: var(--primary);
    }

    .stat-value {
        font-size: 28px;
        font-weight: bold;
        color: var(--primary-muted);
    }

    .stat-card p {
        margin: 8px 0 0 0;
        font-size: 12px;
        color: #999;
    }

    .scores-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 30px;
        height: 100%;
    }

    .scores-grid .scores-section {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--secondary);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .portal-grid table {
        width: 100%;
        border-collapse: collapse;
    }

    .portal-grid thead {
        background: #f8f9fb;
        border-bottom: 2px solid #e0e6f0;
    }

    .portal-grid th {
        padding: 15px;
        text-align: left;
        color: var(--primary);
        font-weight: 600;
        font-size: 14px;
    }

    .portal-grid td {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f2f5;
        color: #555;
    }

    .portal-grid tbody tr:hover {
        background: #f8f9fb;
    }

    .score-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: bold;
        text-align: center;
        min-width: 60px;
    }

    .score-excellent {
        background: #d4edda;
        color: #155724;
    }

    .score-good {
        background: #d1ecf1;
        color: #0c5460;
    }

    .score-average {
        background: #fff3cd;
        color: #856404;
    }

    .score-poor {
        background: #f8d7da;
        color: #721c24;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-item {
        background: white;
        padding: 18px;
        border-radius: 8px;
        border: 1px solid #e8ecf3;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .info-label {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
        font-weight: 500;
    }

    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--primary);
    }

    .portal-grid .btn-group {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .portal-grid .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: 0.3s;
        font-weight: 600;
    }

    .portal-grid .btn-primary {
        background: var(--primary);
        color: white;
    }

    .portal-grid .btn-primary:hover {
        background: var(--primary-dark);
        box-shadow: 0 3px 8px rgba(var(--primary-rgb), 0.2);
    }

    .portal-grid .btn-secondary {
        background: #e8ecf3;
        color: var(--primary);
    }

    .portal-grid .btn-secondary:hover {
        background: #dce1eb;
    }

    .chart-placeholder {
        background: #f8f9fb;
        padding: 40px;
        border-radius: 8px;
        border: 1px solid #e8ecf3;
        text-align: center;
        color: #aaa;
        margin-top: 15px;
    }

    .chart-placeholder svg {
        width: 48px;
        height: 48px;
        opacity: 0.3;
        stroke: currentColor;
    }

    .portal-grid .btn svg {
        width: 16px;
        height: 16px;
        margin-right: 6px;
        stroke: currentColor;
        vertical-align: -3px;
    }

    .chart-placeholder p {
        margin-top: 15px;
    }

    .chart-placeholder small {
        color: #bbb;
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .stats-container {
            grid-template-columns: 1fr;
        }

        .portal-grid .card-header {
            flex-wrap: wrap;
            gap: 8px;
        }

        .news-item {
            grid-template-columns: 48px minmax(0, 1fr);
        }

        .news-item > div {
            min-width: 0;
        }

        .news-item,
        .news-item * {
            max-width: 100%;
        }

        .news-card .card-header a {
            white-space: normal;
        }

        .news-title,
        .news-meta {
            display: block;
            min-width: 0;
            max-width: 100%;
            white-space: normal;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .table-responsive {
            font-size: 13px;
        }

        th, td {
            padding: 10px 8px;
        }
    }
</style>

<div class="portal-grid">
    <div class="portal-main">
        <section id="dashboard">
            <div class="dashboard-top">
                <div class="card student-profile">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin sinh viên</h3>
                    </div>
                    <div class="card-body">
                        <div class="student-profile-grid">
                            <div>
                                <div class="student-avatar">
                                    <?php if (!empty($student['avatar_url'])): ?>
                                        <img src="<?= htmlspecialchars($student['avatar_url']) ?>" alt="Avatar">
                                    <?php else: ?>
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M4 20c1.6-3 5-4 8-4s6.4 1 8 4" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div>
                                <div class="student-name">
                                    <?= htmlspecialchars($student['ho_ten'] ?? '') ?>
                                </div>
                                <div class="student-fields">
                                    <div class="student-field"><div class="k">MSSV:</div><div class="v"><?= htmlspecialchars($student['mssv'] ?? '') ?></div></div>
                                    <div class="student-field"><div class="k">Họ tên:</div><div class="v"><?= htmlspecialchars($student['ho_ten'] ?? '') ?></div></div>
                                    <div class="student-field"><div class="k">Giới tính:</div><div class="v"><?= htmlspecialchars($student['gioi_tinh'] ?? '') ?></div></div>
                                    <div class="student-field"><div class="k">Ngày sinh:</div><div class="v"><?= htmlspecialchars($student['ngay_sinh'] ?? '') ?></div></div>
                                    <div class="student-field"><div class="k">Nơi sinh:</div><div class="v"><?= htmlspecialchars($student['noi_sinh'] ?? '') ?></div></div>
                                    <div class="student-field"><div class="k">Trạng thái:</div><div class="v"><?= htmlspecialchars($student['trang_thai'] ?? '') ?></div></div>
                                    <div class="student-field"><div class="k">Sinh viên năm thứ:</div><div class="v"><?= htmlspecialchars($student['nam_thu'] ?? '') ?></div></div>
                                    <div class="student-field"><div class="k">Lớp học:</div><div class="v"><?= htmlspecialchars($student['lop_hoc'] ?? '') ?></div></div>
                                    <div class="student-field"><div class="k">Khóa học:</div><div class="v"><?= htmlspecialchars($student['khoa_hoc'] ?? '') ?></div></div>
                                    <div class="student-field"><div class="k">Bậc đào tạo:</div><div class="v"><?= htmlspecialchars($student['bac_dao_tao'] ?? '') ?></div></div>
                                    <div class="student-field"><div class="k">Loại hình đào tạo:</div><div class="v"><?= htmlspecialchars($student['loai_hinh_dao_tao'] ?? '') ?></div></div>
                                    <div class="student-field"><div class="k">Ngành:</div><div class="v"><?= htmlspecialchars($student['nganh'] ?? '') ?></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <section id="history"></section>
        <section id="profile"></section>
        <section id="contact"></section>
    </div>

    <aside class="portal-aside">
        <div class="card news-card">
            <div class="card-header">
                <h3 class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; color: var(--primary); stroke: currentColor; margin-right: 4px;">
                        <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7Z" stroke-width="2" stroke-linecap="round"/>
                        <path d="M10 21a2 2 0 0 0 4 0" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Thông báo
                </h3>
                <a href="/KhoaLuan/public/student.php?action=thongbao" style="font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 4px;">Xem chi tiết <i class="fa-solid fa-angle-right"></i></a>
            </div>
            <div class="card-body">
                <ul class="news-list">
                    <li>
                        <a class="news-item list-group-item" href="/KhoaLuan/public/student.php?action=thongbao" style="text-decoration:none;">
                            <div class="news-date"><div class="m">Th4</div><div class="d">24</div></div>
                            <div>
                                <p class="news-title">Thông báo cập nhật điểm rèn luyện học kỳ I</p>
                                <p class="news-meta">Phòng CTSV • 08:30</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="news-item list-group-item" href="/KhoaLuan/public/student.php?action=thongbao" style="text-decoration:none;">
                            <div class="news-date"><div class="m">Th4</div><div class="d">22</div></div>
                            <div>
                                <p class="news-title">Hướng dẫn đăng ký hoạt động ngoại khóa</p>
                                <p class="news-meta">Đoàn - Hội • 14:10</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="news-item list-group-item" href="/KhoaLuan/public/student.php?action=thongbao" style="text-decoration:none;">
                            <div class="news-date"><div class="m">Th4</div><div class="d">18</div></div>
                            <div>
                                <p class="news-title">Lịch tiếp nhận minh chứng điểm rèn luyện</p>
                                <p class="news-meta">Khoa CNTT • 09:00</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="news-item list-group-item" href="/KhoaLuan/public/student.php?action=thongbao" style="text-decoration:none;">
                            <div class="news-date"><div class="m">Th4</div><div class="d">10</div></div>
                            <div>
                                <p class="news-title">Nhắc hạn hoàn tất khảo sát học kỳ</p>
                                <p class="news-meta">Hệ thống • 16:45</p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <div class="portal-full portal-full--stats">
        <!-- THỐNG KÊ -->
        <div class="stats-container">
            <a class="stat-card card stat-card--center stat-card--link" href="/KhoaLuan/public/student.php?action=ketquarenluyen">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 19h16" stroke-width="2" stroke-linecap="round"/>
                        <path d="M7 16V9" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 16V5" stroke-width="2" stroke-linecap="round"/>
                        <path d="M17 16v-7" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="stat-label">Kết quả rèn luyện</div>
            </a>

            <a class="stat-card card stat-card--center stat-card--meta stat-card--link" href="/KhoaLuan/public/student.php?action=lichhoatdong">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="5" width="18" height="16" rx="2" stroke-width="2" />
                        <path d="M16 3v4M8 3v4M3 11h18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8.5 15l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="stat-label">Lịch hoạt động</div>
            </a>

            <a class="stat-card card stat-card--center stat-card--link" href="/KhoaLuan/public/student.php?action=dangkyhoatdong">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="6" y="4" width="12" height="16" rx="2" stroke-width="2" />
                        <path d="M9 8h6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 12h4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="m9 16 2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="stat-label">Đăng ký hoạt động</div>
            </a>


            <a class="stat-card card stat-card--center stat-card--link" href="/KhoaLuan/public/student.php?action=phieudanhgia">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 3h7l5 5v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 3v5h5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 12h6M9 16h6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="stat-label">Phiếu đánh giá</div>
            </a>

            <a class="stat-card card stat-card--center stat-card--link" href="/KhoaLuan/public/student.php?action=hoatdongdangky">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 3h7l5 5v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 3v5h5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 12v6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 15h6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="stat-label">Hoạt động đã đăng ký</div>
            </a>

            <a class="stat-card card stat-card--center stat-card--link" href="/KhoaLuan/public/student.php?action=diemdanhhoatdong">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="7" r="3" stroke-width="2" />
                        <path d="M5 20c1.6-3 5-4 7-4s5.4 1 7 4" stroke-width="2" stroke-linecap="round"/>
                        <path d="m16 11 1.5 1.5 3-3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="stat-label">Điểm danh hoạt động</div>
            </a>
        </div>
    </div>

    <div class="portal-full">
        <div id="scores" class="scores-grid">
            <!-- BIỂU ĐỒ (THỐNG KÊ ĐIỂM) -->
            <section class="scores-section scores-section--compact scores-section--fill scores-section--registered card">
                <div class="section-title">
                    Hoạt động đã đăng ký
                </div>
                <div class="chart-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 19h16M7 16V9M12 16V5M17 16v-7" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <p style="margin-top: 15px;">Biểu đồ thống kê sẽ được hiển thị tại đây<br><small>(Cần thêm thư viện Chart.js)</small></p>
                </div>
            </section>

            <!-- TIẾN ĐỘ RÈN LUYỆN -->
            <section class="scores-section scores-section--compact scores-section--fill scores-section--progress card">
                <div class="section-title">
                    Tiến độ rèn luyện
                </div>
                <div class="chart-placeholder">
                    <p style="margin: 0;">Tiến độ sẽ hiển thị tại đây</p>
                    <small>(Sẽ nối dữ liệu sau)</small>
                </div>
            </section>

            <!-- BIỂU ĐỒ KẾT QUẢ RÈN LUYỆN -->
            <section class="scores-section scores-section--compact scores-section--fill card">
                <div class="section-title">
                    Kết quả rèn luyện
                </div>
                <div class="chart-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 19h16" stroke-width="2" stroke-linecap="round"/>
                        <path d="M7 16V9" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 16V6" stroke-width="2" stroke-linecap="round"/>
                        <path d="M17 16v-4" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <p style="margin-top: 15px;">Biểu đồ cột sẽ được hiển thị tại đây<br><small>(Cần thêm thư viện Chart.js)</small></p>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    (function() {
        function syncAsideCardHeights() {
            var profileCard = document.querySelector('.student-profile');
            var asideCards = document.querySelectorAll('.portal-aside .card');

            if (!profileCard || asideCards.length === 0) {
                return;
            }

            var height = profileCard.offsetHeight + 'px';
            asideCards.forEach(function(card) {
                card.style.height = height;
            });
        }

        window.addEventListener('load', syncAsideCardHeights);
        window.addEventListener('resize', syncAsideCardHeights);
    })();
</script>
