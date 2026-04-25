<?php
    /**
     * Dữ liệu mẫu để render giao diện.
     * Khi nối DB, bạn chỉ cần truyền mảng $student từ Controller.
     */
    $student = $student ?? [];
?>

<style>
    .portal-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        align-items: start;
    }

    .portal-main {
        min-width: 0;
    }

    .portal-aside {
        min-width: 0;
    }

    @media (min-width: 992px) {
        .portal-grid {
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 24px;
        }
    }

    .portal-full {
        grid-column: 1 / -1;
    }

    .card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e8ecf3;
        overflow: hidden;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: #f8f9fb;
        border-bottom: 1px solid #e8ecf3;
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        color: #1d4ed8;
        font-size: 14px;
        letter-spacing: 0.2px;
        margin: 0;
    }

    .card-title svg {
        width: 16px;
        height: 16px;
        color: #00a8e8;
        stroke: currentColor;
    }

    .card-body {
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
        border-radius: 10px;
        border: 1px solid #f0f2f5;
        transition: 0.2s;
        background: #fff;
    }

    .news-item:hover {
        border-color: #dce1eb;
        box-shadow: 0 4px 12px rgba(29, 78, 216, 0.08);
        transform: translateY(-1px);
    }

    .news-date {
        border-radius: 10px;
        background: linear-gradient(135deg, #1d4ed8 0%, #1047a1 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        height: 54px;
        min-width: 54px;
        line-height: 1;
    }

    .news-date .m {
        font-size: 11px;
        opacity: 0.9;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .news-date .d {
        font-size: 18px;
        font-weight: 800;
        margin-top: 2px;
    }

    .news-title {
        font-size: 13px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 4px 0;
        line-height: 1.35;
    }

    .news-meta {
        font-size: 12px;
        color: #6b7280;
        margin: 0;
    }

    .aside-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .quick-link {
        display: flex;
        gap: 10px;
        align-items: center;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #e8ecf3;
        text-decoration: none;
        color: #2c3e50;
        transition: 0.2s;
        background: #fff;
    }

    .quick-link:hover {
        border-color: #dce1eb;
        box-shadow: 0 4px 12px rgba(29, 78, 216, 0.08);
        transform: translateY(-1px);
    }

    .quick-link svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
    }

    .quick-link .quick-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef4ff;
        color: #1d4ed8;
    }

    .quick-link span {
        font-size: 13px;
        font-weight: 700;
    }

    .student-profile {
        margin-bottom: 30px;
    }

    .student-profile.card {
        border: none;
    }

    .student-profile .card-header {
        background: transparent;
        border-bottom: none;
        padding-bottom: 6px;
    }

    .student-profile .card-body {
        padding: 16px;
    }

    .student-profile-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        align-items: start;
    }

    @media (min-width: 768px) {
        .student-profile-grid {
            grid-template-columns: 140px 1fr;
            gap: 18px;
        }
    }

    .student-avatar {
        width: 140px;
        height: 140px;
        border-radius: 14px;
        background: linear-gradient(135deg, #1d4ed8 0%, #1047a1 100%);
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
        color: #1d4ed8;
        margin: 2px 0 10px 0;
        line-height: 1.2;
    }

    .student-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 16px;
    }

    .student-field {
        display: grid;
        grid-template-columns: 160px 1fr;
        gap: 10px;
        padding: 0;
        border: none;
        border-radius: 0;
        background: transparent;
    }

    @media (max-width: 480px) {
        .student-field {
            grid-template-columns: 1fr;
        }
    }

    .student-field .k {
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        text-transform: none;
        letter-spacing: 0;
    }

    .student-field .v {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        word-break: break-word;
    }

    .scores-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    @media (min-width: 992px) {
        .scores-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
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
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 22px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: none;
    }

    .stat-card:hover {
        transform: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .stat-icon {
        margin-bottom: 12px;
        color: #00a8e8;
    }

    .stat-icon svg {
        width: 32px;
        height: 32px;
        stroke: currentColor;
    }

    .stat-label {
        font-size: 13px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: bold;
        color: #2c387e;
    }

    .stat-card p {
        margin: 8px 0 0 0;
        font-size: 12px;
        color: #999;
    }

    .scores-section {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 30px;
        height: 100%;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: #1d4ed8;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #00a8e8;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f8f9fb;
        border-bottom: 2px solid #e0e6f0;
    }

    th {
        padding: 15px;
        text-align: left;
        color: #1d4ed8;
        font-weight: 600;
        font-size: 14px;
    }

    td {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f2f5;
        color: #555;
    }

    tbody tr:hover {
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
        border-radius: 10px;
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
        color: #1d4ed8;
    }

    .btn-group {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: 0.3s;
        font-weight: 600;
    }

    .btn-primary {
        background: #1d4ed8;
        color: white;
    }

    .btn-primary:hover {
        background: #1047a1;
        box-shadow: 0 3px 8px rgba(29, 78, 216, 0.2);
    }

    .btn-secondary {
        background: #e8ecf3;
        color: #1d4ed8;
    }

    .btn-secondary:hover {
        background: #dce1eb;
    }

    .chart-placeholder {
        background: #f8f9fb;
        padding: 40px;
        border-radius: 10px;
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

    .btn svg {
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

            <!-- THỐNG KÊ -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="m12 2 3 6 6 1-4 4 1 6-6-3-6 3 1-6-4-4 6-1 3-6Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="stat-label">Điểm Tổng Hợp</div>
                    <div class="stat-value">85.5</div>
                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">Trên 100</p>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="9" stroke-width="2" />
                            <path d="m8 12 3 3 5-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="stat-label">Số Hoạt Động</div>
                    <div class="stat-value">12</div>
                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">Hoạt động</p>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 4h8v4a4 4 0 1 1-8 0V4Z" stroke-width="2" />
                            <path d="M6 4H4v2a4 4 0 0 0 4 4" stroke-width="2" />
                            <path d="M18 4h2v2a4 4 0 0 1-4 4" stroke-width="2" />
                            <path d="M12 12v4" stroke-width="2" stroke-linecap="round" />
                            <path d="M8 20h8" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <div class="stat-label">Xếp Hạng</div>
                    <div class="stat-value">Giỏi</div>
                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">Loại điểm</p>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="9" stroke-width="2" />
                            <path d="M12 7v5l3 2" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <div class="stat-label">Cập Nhật Lần Cuối</div>
                    <div class="stat-value">Hôm nay</div>
                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">21:30</p>
                </div>
            </div>
        </section>

        <section id="history"></section>
        <section id="profile"></section>
        <section id="contact"></section>
    </div>

    <aside class="portal-aside">
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3 class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Thao tác nhanh
                </h3>
            </div>
            <div class="card-body">
                <div class="aside-actions">
                    <a class="quick-link" href="#scores">
                        <span class="quick-icon">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 19h16M7 16V9M12 16V5M17 16v-7" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <span>Xem điểm</span>
                    </a>
                    <a class="quick-link" href="#profile">
                        <span class="quick-icon">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 5h16v14H4z" stroke-width="2" stroke-linecap="round"/>
                                <path d="M8 9h8M8 13h5" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <span>Hồ sơ</span>
                    </a>
                    <a class="quick-link" href="#contact">
                        <span class="quick-icon">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 12a8 8 0 0 1 16 0" stroke-width="2" stroke-linecap="round"/>
                                <path d="M4 12v3a2 2 0 0 0 2 2h2v-6H6a2 2 0 0 0-2 2Z" stroke-width="2" stroke-linecap="round"/>
                                <path d="M20 12v3a2 2 0 0 1-2 2h-2v-6h2a2 2 0 0 1 2 2Z" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <span>Hỗ trợ</span>
                    </a>
                    <a class="quick-link" href="#history">
                        <span class="quick-icon">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 12a9 9 0 1 0 3-6.7" stroke-width="2" stroke-linecap="round"/>
                                <path d="M3 4v5h5" stroke-width="2" stroke-linecap="round"/>
                                <path d="M12 7v5l3 2" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <span>Lịch sử</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 7h18s-3 0-3-7Z" stroke-width="2" stroke-linecap="round"/>
                        <path d="M10 21a2 2 0 0 0 4 0" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Thông báo
                </h3>
                <div style="font-size: 12px; color: #6b7280;">Mới nhất</div>
            </div>
            <div class="card-body">
                <ul class="news-list">
                    <li>
                        <a class="news-item" href="#" style="text-decoration:none;">
                            <div class="news-date"><div class="m">Th4</div><div class="d">24</div></div>
                            <div>
                                <p class="news-title">Thông báo cập nhật điểm rèn luyện học kỳ I</p>
                                <p class="news-meta">Phòng CTSV • 08:30</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="news-item" href="#" style="text-decoration:none;">
                            <div class="news-date"><div class="m">Th4</div><div class="d">22</div></div>
                            <div>
                                <p class="news-title">Hướng dẫn đăng ký hoạt động ngoại khóa</p>
                                <p class="news-meta">Đoàn - Hội • 14:10</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="news-item" href="#" style="text-decoration:none;">
                            <div class="news-date"><div class="m">Th4</div><div class="d">18</div></div>
                            <div>
                                <p class="news-title">Lịch tiếp nhận minh chứng điểm rèn luyện</p>
                                <p class="news-meta">Khoa CNTT • 09:00</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="news-item" href="#" style="text-decoration:none;">
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

    <div class="portal-full">
        <div id="scores" class="scores-grid">
            <!-- BIỂU ĐỒ (THỐNG KÊ ĐIỂM) -->
            <section class="scores-section scores-section--compact scores-section--fill">
                <div class="section-title">
                    Thống kê điểm
                </div>
                <div class="chart-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 19h16M7 16V9M12 16V5M17 16v-7" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <p style="margin-top: 15px;">Biểu đồ thống kê sẽ được hiển thị tại đây<br><small>(Cần thêm thư viện Chart.js)</small></p>
                </div>
            </section>

            <!-- BẢNG CHI TIẾT -->
            <section class="scores-section scores-section--compact scores-section--scroll">
                <div class="section-title">
                    Kết quả rèn luyện
                </div>

                <div class="btn-group">
                    <button class="btn btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 3v12" stroke-width="2" stroke-linecap="round"/>
                            <path d="m7 10 5 5 5-5" stroke-width="2" stroke-linecap="round"/>
                            <path d="M5 21h14" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Tải Xuống
                    </button>
                    <button class="btn btn-secondary">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9V4h12v5" stroke-width="2" stroke-linecap="round"/>
                            <rect x="6" y="13" width="12" height="7" stroke-width="2" />
                            <path d="M6 12h12" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        In
                    </button>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tiêu Chí Đánh Giá</th>
                                <th>Mô Tả</th>
                                <th>Điểm</th>
                                <th>Ghi Chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><strong>Học Tập</strong></td>
                                <td>Thái độ, kết quả học tập</td>
                                <td><span class="score-badge score-excellent">9.0</span></td>
                                <td>Xuất sắc</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><strong>Kỷ Luật</strong></td>
                                <td>Tuân thủ nội quy, luật lệ</td>
                                <td><span class="score-badge score-excellent">8.5</span></td>
                                <td>Tốt</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><strong>Hoạt Động Tập Thể</strong></td>
                                <td>Tham gia các hoạt động</td>
                                <td><span class="score-badge score-good">8.0</span></td>
                                <td>Tốt</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td><strong>Công Tác Xã Hội</strong></td>
                                <td>Đóng góp cho cộng đồng</td>
                                <td><span class="score-badge score-good">7.5</span></td>
                                <td>Khá</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td><strong>Đạo Đức</strong></td>
                                <td>Hành vi, thái độ đạo đức</td>
                                <td><span class="score-badge score-excellent">9.5</span></td>
                                <td>Xuất sắc</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td><strong>Sức Khỏe</strong></td>
                                <td>Rèn luyện thể chất</td>
                                <td><span class="score-badge score-average">7.0</span></td>
                                <td>Khá</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- TIẾN ĐỘ RÈN LUYỆN -->
            <section class="scores-section scores-section--compact scores-section--fill">
                <div class="section-title">
                    Tiến độ rèn luyện
                </div>
                <div class="chart-placeholder">
                    <p style="margin: 0;">Tiến độ sẽ hiển thị tại đây</p>
                    <small>(Sẽ nối dữ liệu sau)</small>
                </div>
            </section>
        </div>
    </div>
</div>
