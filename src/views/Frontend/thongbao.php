<?php
    // Trang Thông báo - Khóa luận
?>

<style>
    /* VARIABLES & UTILITIES */
    .notif-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
        animation: fadeIn 0.4s ease-out;
    }

    .activity-page-title {
        font-size: 18px;
        font-weight: 800;
        color: #1d4ed8;
        text-transform: none;
        letter-spacing: 0.6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .activity-panel {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .activity-panel__header {
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .activity-panel__body {
        padding: 12px;
    }

    .filter-input {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 13px;
        color: #1f2937;
    }

    .filter-input input {
        border: none;
        background: transparent;
        outline: none;
        width: 100%;
        font-size: 13px;
        color: #1f2937;
    }

    .filter-btn {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #1d4ed8;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .filter-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .filter-btn.primary {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #fff;
    }

    .filter-btn.primary:hover {
        background: #1047a1;
        border-color: #1047a1;
    }

    .activity-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 16px;
        align-items: center;
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 12px;
    }

    .activity-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 999px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
    }

    .activity-tab:hover {
        background: #f1f5f9;
        color: #1d4ed8;
    }

    .activity-tab.active {
        background: #1d4ed8;
        color: #ffffff;
    }

    .notif-tab-badge {
        background: #f1f5f9;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 99px;
        transition: all 0.2s ease;
    }

    .activity-tab.active .notif-tab-badge {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    .notif-summary-badge {
        font-size: 13px;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .notif-summary-badge.has-unread {
        color: #e11d48;
    }

    .notif-summary-badge.all-read {
        color: #16a34a;
    }

    /* LIST CARDS */
    .notif-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .notif-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e8ecf3;
        padding: 16px 20px;
        cursor: pointer;
        position: relative;
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: flex-start;
        gap: 16px;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }

    .notif-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 18px rgba(29, 78, 216, 0.06);
        transform: translateY(-2px);
    }

    /* Unread Status Styles */
    .notif-card.unread {
        background: #f8faff;
        border-left: 4px solid var(--primary);
    }

    .notif-card.unread .notif-item-title {
        font-weight: 800;
        color: #0f172a;
    }

    /* Unread Dot */
    .notif-unread-dot {
        width: 8px;
        height: 8px;
        background: var(--primary);
        border-radius: 50%;
        display: inline-block;
        margin-left: 8px;
        box-shadow: 0 0 8px rgba(59, 130, 246, 0.6);
    }

    /* Icon Types */
    .notif-icon-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: all 0.2s ease;
    }

    .notif-icon-circle.system {
        background: #fef3c7;
        color: #d97706;
    }

    .notif-icon-circle.activity {
        background: #e0f2fe;
        color: #0284c7;
    }

    /* Info Details */
    .notif-item-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .notif-item-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        font-size: 12px;
        color: #94a3b8;
    }

    .notif-sender {
        font-weight: 700;
        color: #64748b;
    }

    .notif-dot-separator {
        width: 4px;
        height: 4px;
        background: #cbd5e1;
        border-radius: 50%;
    }

    .notif-time {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .notif-item-title {
        font-size: 15px;
        font-weight: 650;
        color: #334155;
        line-height: 1.4;
    }

    .notif-preview-text {
        font-size: 13.5px;
        color: #64748b;
        line-height: 1.5;
        margin-top: 4px;
        word-break: break-word;
    }

    /* Category Badges */
    .notif-badge {
        display: inline-flex;
        align-items: center;
        padding: 2px 10px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .notif-badge.system {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fef3c7;
    }

    .notif-badge.activity {
        background: #f0f9ff;
        color: #0369a1;
        border: 1px solid #e0f2fe;
    }

    /* ACCORDION EXPANSION */
    .notif-content-drawer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        grid-column: 1 / -1;
    }

    .notif-card.expanded .notif-content-drawer {
        max-height: 300px;
    }

    .notif-card.expanded {
        border-color: var(--primary);
    }

    .notif-card.expanded .notif-angle i {
        transform: rotate(180deg);
    }

    .notif-expanded-body {
        padding-top: 14px;
        margin-top: 12px;
        border-top: 1px dashed #e2e8f0;
        font-size: 14px;
        color: #475569;
        line-height: 1.6;
    }

    .notif-angle {
        color: #94a3b8;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .notif-card:hover .notif-angle {
        background: #f1f5f9;
        color: #475569;
    }

    .notif-angle i {
        transition: transform 0.3s ease;
    }

    /* EMPTY STATE */
    .notif-empty-state {
        background: #ffffff;
        border-radius: 16px;
        border: 1px dashed #cbd5e1;
        padding: 48px 24px;
        text-align: center;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 16px;
        animation: fadeIn 0.3s ease-out;
    }

    .notif-empty-icon {
        width: 72px;
        height: 72px;
        background: #f8fafc;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #cbd5e1;
        font-size: 32px;
        margin-bottom: 8px;
    }

    .notif-empty-state h3 {
        font-size: 16px;
        font-weight: 700;
        color: #475569;
        margin: 0;
    }

    .notif-empty-state p {
        font-size: 14px;
        color: #94a3b8;
        margin: 0;
        max-width: 320px;
    }

    /* ANIMATIONS */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .notif-card {
            grid-template-columns: auto 1fr;
            padding: 14px;
            gap: 12px;
        }

        .notif-angle {
            grid-column: 2;
            justify-self: end;
            margin-top: -8px;
        }
    }
</style>

<div class="notif-container">
    <!-- TIÊU ĐỀ & TÌM KIẾM -->
    <div class="activity-panel card">
        <div class="activity-panel__header card-header">
            <div class="activity-page-title">
                <i class="fa-solid fa-bell"></i>
                Thông báo
            </div>
            <div class="notif-summary-badge has-unread badge rounded-pill" id="notifUnreadSummary">Bạn có 4 thông báo chưa đọc</div>
        </div>
        <div class="activity-panel__body card-body" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; padding: 12px 14px;">
            <!-- SEARCH INPUT -->
            <div class="filter-input" style="max-width: 320px; width: 100%;">
                <i class="fa-solid fa-magnifying-glass" style="color: #94a3b8; font-size: 14px;"></i>
                <input class="form-control" type="text" id="notifSearch" placeholder="Tìm kiếm thông báo..." onkeyup="filterNotifications()">
            </div>
            <!-- MARK ALL ACTION -->
            <button class="filter-btn primary btn btn-primary" id="btnMarkAll" onclick="markAllAsRead()">
                <i class="fa-solid fa-check-double"></i> Đánh dấu tất cả đã đọc
            </button>
        </div>
    </div>

    <!-- TABS -->
    <div class="activity-tabs nav nav-pills" role="tablist">
        <button class="activity-tab active nav-link" id="tab-all" onclick="switchTab('all')" role="tab" aria-selected="true">
            Tất cả <span class="notif-tab-badge badge rounded-pill" id="badge-all">6</span>
        </button>
        <button class="activity-tab nav-link" id="tab-unread" onclick="switchTab('unread')" role="tab" aria-selected="false">
            Chưa đọc <span class="notif-tab-badge badge rounded-pill" id="badge-unread">4</span>
        </button>
        <button class="activity-tab nav-link" id="tab-system" onclick="switchTab('system')" role="tab" aria-selected="false">
            Hệ thống <span class="notif-tab-badge badge rounded-pill" id="badge-system">3</span>
        </button>
        <button class="activity-tab nav-link" id="tab-activity" onclick="switchTab('activity')" role="tab" aria-selected="false">
            Hoạt động <span class="notif-tab-badge badge rounded-pill" id="badge-activity">3</span>
        </button>
    </div>

    <!-- DANH SÁCH THÔNG BÁO -->
    <div class="notif-list" id="notifList">
        <!-- Item 1 -->
        <div class="notif-card unread card" id="notif-1" data-category="activity" data-status="unread" onclick="toggleExpand('notif-1')">
            <div class="notif-icon-circle activity" aria-hidden="true">
                <i class="fa-solid fa-person-running"></i>
            </div>
            <div class="notif-item-details">
                <div class="notif-item-meta">
                    <span class="notif-sender">Phòng CTSV</span>
                    <span class="notif-dot-separator" aria-hidden="true"></span>
                    <span class="notif-time" title="2026-04-24 08:30:00">
                        <i class="fa-regular fa-clock"></i> 24/04/2026 08:30
                    </span>
                    <span class="notif-dot-separator" aria-hidden="true"></span>
                    <span class="notif-badge activity badge rounded-pill">Hoạt động</span>
                </div>
                <div class="notif-item-title">
                    Thông báo cập nhật điểm rèn luyện học kỳ I <span class="notif-unread-dot" id="dot-notif-1"></span>
                </div>
                <div class="notif-preview-text">
                    Kính gửi các bạn sinh viên, Phòng CTSV đã cập nhật điểm rèn luyện tạm tính học kỳ I năm học 2025-2026. Sinh viên vui lòng kiểm tra...
                </div>
            </div>
            <div class="notif-angle" aria-label="Mở rộng chi tiết">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="notif-content-drawer">
                <div class="notif-expanded-body">
                    Kính gửi các bạn sinh viên, Phòng CTSV đã cập nhật điểm rèn luyện tạm tính học kỳ I năm học 2025-2026. <br><br>
                    Sinh viên vui lòng kiểm tra và gửi phản hồi/minh chứng bổ sung nếu có sai sót trực tiếp cho cố vấn học tập hoặc qua cổng hỗ trợ trực tuyến của Trường trước ngày 30/04/2026. Sau thời hạn trên, dữ liệu sẽ được khóa để lập danh sách khen thưởng chính thức.
                </div>
            </div>
        </div>

        <!-- Item 2 -->
        <div class="notif-card card" id="notif-2" data-category="activity" data-status="read" onclick="toggleExpand('notif-2')">
            <div class="notif-icon-circle activity" aria-hidden="true">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div class="notif-item-details">
                <div class="notif-item-meta">
                    <span class="notif-sender">Đoàn - Hội</span>
                    <span class="notif-dot-separator" aria-hidden="true"></span>
                    <span class="notif-time" title="2026-04-22 14:10:00">
                        <i class="fa-regular fa-clock"></i> 22/04/2026 14:10
                    </span>
                    <span class="notif-dot-separator" aria-hidden="true"></span>
                    <span class="notif-badge activity badge rounded-pill">Hoạt động</span>
                </div>
                <div class="notif-item-title">
                    Hướng dẫn đăng ký hoạt động ngoại khóa
                </div>
                <div class="notif-preview-text">
                    Đoàn trường hướng dẫn các bước đăng ký tham gia các hoạt động ngoại khóa năm học mới. Các bạn sinh viên có thể đăng ký trực tuyến...
                </div>
            </div>
            <div class="notif-angle" aria-label="Mở rộng chi tiết">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="notif-content-drawer">
                <div class="notif-expanded-body">
                    Đoàn trường hướng dẫn các bước đăng ký tham gia các hoạt động ngoại khóa năm học mới. <br><br>
                    Các bạn sinh viên có thể đăng ký trực tuyến thông qua ứng dụng Cổng thông tin sinh viên bằng cách truy cập mục <strong>Đăng ký hoạt động</strong> trên thanh menu. Mỗi hoạt động đăng ký thành công và được xác nhận tham gia sẽ được cộng điểm rèn luyện tương ứng theo quy chế đánh giá điểm rèn luyện hiện hành.
                </div>
            </div>
        </div>

        <!-- Item 3 -->
        <div class="notif-card unread card" id="notif-3" data-category="system" data-status="unread" onclick="toggleExpand('notif-3')">
            <div class="notif-icon-circle system" aria-hidden="true">
                <i class="fa-solid fa-gear"></i>
            </div>
            <div class="notif-item-details">
                <div class="notif-item-meta">
                    <span class="notif-sender">Khoa CNTT</span>
                    <span class="notif-dot-separator" aria-hidden="true"></span>
                    <span class="notif-time" title="2026-04-18 09:00:00">
                        <i class="fa-regular fa-clock"></i> 18/04/2026 09:00
                    </span>
                    <span class="notif-dot-separator" aria-hidden="true"></span>
                    <span class="notif-badge system badge rounded-pill">Hệ thống</span>
                </div>
                <div class="notif-item-title">
                    Lịch tiếp nhận minh chứng điểm rèn luyện <span class="notif-unread-dot" id="dot-notif-3"></span>
                </div>
                <div class="notif-preview-text">
                    Khoa Công nghệ thông tin thông báo lịch tiếp nhận hồ sơ minh chứng điểm rèn luyện trực tiếp tại văn phòng Khoa (Phòng A201). Thời gian...
                </div>
            </div>
            <div class="notif-angle" aria-label="Mở rộng chi tiết">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="notif-content-drawer">
                <div class="notif-expanded-body">
                    Khoa Công nghệ thông tin thông báo lịch tiếp nhận hồ sơ minh chứng điểm rèn luyện trực tiếp tại văn phòng Khoa (Phòng A201). <br><br>
                    Thời gian tiếp nhận từ ngày 20/04 đến hết ngày 25/04/2026 (sáng từ 8h00 - 11h00, chiều từ 13h30 - 16h30). Các bạn sinh viên chuẩn bị đầy đủ bản in minh chứng hoạt động cùng biểu mẫu đánh giá tự chấm đã ký tên cố vấn học tập trước khi nộp.
                </div>
            </div>
        </div>

        <!-- Item 4 -->
        <div class="notif-card unread card" id="notif-4" data-category="system" data-status="unread" onclick="toggleExpand('notif-4')">
            <div class="notif-icon-circle system" aria-hidden="true">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="notif-item-details">
                <div class="notif-item-meta">
                    <span class="notif-sender">Hệ thống</span>
                    <span class="notif-dot-separator" aria-hidden="true"></span>
                    <span class="notif-time" title="2026-04-10 16:45:00">
                        <i class="fa-regular fa-clock"></i> 10/04/2026 16:45
                    </span>
                    <span class="notif-dot-separator" aria-hidden="true"></span>
                    <span class="notif-badge system badge rounded-pill">Hệ thống</span>
                </div>
                <div class="notif-item-title">
                    Nhắc hạn hoàn tất khảo sát học kỳ <span class="notif-unread-dot" id="dot-notif-4"></span>
                </div>
                <div class="notif-preview-text">
                    Nhắc nhở: Bạn còn khảo sát đánh giá giảng viên chưa hoàn tất. Vui lòng truy cập Cổng khảo sát để thực hiện trước hạn chót...
                </div>
            </div>
            <div class="notif-angle" aria-label="Mở rộng chi tiết">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="notif-content-drawer">
                <div class="notif-expanded-body">
                    Nhắc nhở: Bạn còn khảo sát đánh giá giảng viên chưa hoàn tất. <br><br>
                    Vui lòng truy cập Cổng khảo sát để thực hiện trước hạn chót ngày 15/04/2026. Việc không hoàn tất khảo sát đúng hạn có thể ảnh hưởng tiêu cực tới kết quả điểm rèn luyện (Mục đánh giá ý thức học tập) và hạn chế một số chức năng tra cứu đăng ký môn học của học kỳ tiếp theo.
                </div>
            </div>
        </div>

        <!-- Item 5 -->
        <div class="notif-card card" id="notif-5" data-category="activity" data-status="read" onclick="toggleExpand('notif-5')">
            <div class="notif-icon-circle activity" aria-hidden="true">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
            <div class="notif-item-details">
                <div class="notif-item-meta">
                    <span class="notif-sender">Hệ thống</span>
                    <span class="notif-dot-separator" aria-hidden="true"></span>
                    <span class="notif-time" title="2026-04-05 10:20:00">
                        <i class="fa-regular fa-clock"></i> 05/04/2026 10:20
                    </span>
                    <span class="notif-dot-separator" aria-hidden="true"></span>
                    <span class="notif-badge activity badge rounded-pill">Hoạt động</span>
                </div>
                <div class="notif-item-title">
                    Xác nhận đăng ký hoạt động thành công
                </div>
                <div class="notif-preview-text">
                    Hệ thống xác nhận bạn đã đăng ký thành công hoạt động 'Chiến dịch Mùa hè xanh 2026'. Vui lòng có mặt đúng giờ và mặc đúng trang phục...
                </div>
            </div>
            <div class="notif-angle" aria-label="Mở rộng chi tiết">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="notif-content-drawer">
                <div class="notif-expanded-body">
                    Hệ thống xác nhận bạn đã đăng ký thành công hoạt động <strong>"Chiến dịch Mùa hè xanh 2026"</strong>. <br><br>
                    Vui lòng có mặt đúng giờ (7h00 ngày 10/05/2026) tại sảnh A và mặc đúng trang phục áo xanh Thanh niên Việt Nam để làm lễ xuất quân. Hoạt động này được cộng 10 điểm rèn luyện vào điều kiện hoạt động cộng đồng xã hội.
                </div>
            </div>
        </div>

        <!-- Item 6 -->
        <div class="notif-card unread card" id="notif-6" data-category="system" data-status="unread" onclick="toggleExpand('notif-6')">
            <div class="notif-icon-circle system" aria-hidden="true">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="notif-item-details">
                <div class="notif-item-meta">
                    <span class="notif-sender">Hệ thống</span>
                    <span class="notif-dot-separator" aria-hidden="true"></span>
                    <span class="notif-time" title="2026-04-01 21:15:00">
                        <i class="fa-regular fa-clock"></i> 01/04/2026 21:15
                    </span>
                    <span class="notif-dot-separator" aria-hidden="true"></span>
                    <span class="notif-badge system badge rounded-pill">Hệ thống</span>
                </div>
                <div class="notif-item-title">
                    Cảnh báo tài khoản đăng nhập lạ <span class="notif-unread-dot" id="dot-notif-6"></span>
                </div>
                <div class="notif-preview-text">
                    Phát hiện đăng nhập vào tài khoản của bạn từ thiết bị hoặc địa điểm mới. Nếu đây không phải là bạn, vui lòng tiến hành đổi mật khẩu...
                </div>
            </div>
            <div class="notif-angle" aria-label="Mở rộng chi tiết">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="notif-content-drawer">
                <div class="notif-expanded-body">
                    Phát hiện đăng nhập vào tài khoản của bạn từ thiết bị Chrome trên Windows ở địa chỉ IP 171.244.xxx.xxx (Cần Thơ, Việt Nam). <br><br>
                    Nếu đây không phải là bạn, vui lòng tiến hành đổi mật khẩu ngay lập tức tại phần <strong>Thông tin chung -> Đổi mật khẩu</strong> để bảo vệ tài khoản điểm rèn luyện cá nhân khỏi sự cố rò rỉ dữ liệu.
                </div>
            </div>
        </div>
    </div>

    <!-- EMPTY STATE -->
    <div class="notif-empty-state" id="notifEmptyState">
        <div class="notif-empty-icon" aria-hidden="true">
            <i class="fa-regular fa-bell-slash"></i>
        </div>
        <h3>Không tìm thấy thông báo nào</h3>
        <p>Hộp thư thông báo của bạn đang trống hoặc không có thông báo nào khớp với bộ lọc tìm kiếm hiện tại.</p>
    </div>
</div>

<script>
    let currentTab = 'all';

    // Toggle accordion drawer for specific notification
    function toggleExpand(id) {
        const card = document.getElementById(id);
        const wasExpanded = card.classList.contains('expanded');

        // Close all expanded first for clean accordion behavior
        document.querySelectorAll('.notif-card').forEach(c => {
            c.classList.remove('expanded');
        });

        if (!wasExpanded) {
            card.classList.add('expanded');
            // If it was unread, mark as read on expand
            if (card.classList.contains('unread')) {
                markAsRead(id);
            }
        }
    }

    // Mark single notification as read
    function markAsRead(id) {
        const card = document.getElementById(id);
        if (card.classList.contains('unread')) {
            card.classList.remove('unread');
            card.setAttribute('data-status', 'read');
            
            // Remove unread dot
            const dot = document.getElementById('dot-' + id);
            if (dot) {
                dot.remove();
            }

            // Recalculate badges
            updateBadges();
        }
    }

    // Mark all notifications as read
    function markAllAsRead() {
        document.querySelectorAll('.notif-card.unread').forEach(card => {
            const id = card.getAttribute('id');
            card.classList.remove('unread');
            card.setAttribute('data-status', 'read');
            
            const dot = document.getElementById('dot-' + id);
            if (dot) {
                dot.remove();
            }
        });

        // Add subtle animation check to the button
        const btn = document.getElementById('btnMarkAll');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Đã hoàn thành';
        btn.style.background = '#10b981';
        btn.style.borderColor = '#10b981';
        btn.style.color = '#ffffff';
        
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.style.background = '';
            btn.style.borderColor = '';
            btn.style.color = '';
        }, 1500);

        updateBadges();
        
        // If we are currently on the 'unread' tab, refresh view
        if (currentTab === 'unread') {
            switchTab('unread');
        }
    }

    // Switch between tabs
    function switchTab(tabId) {
        currentTab = tabId;
        
        // Remove active class from all tabs
        document.querySelectorAll('.activity-tab').forEach(t => {
            t.classList.remove('active');
            t.setAttribute('aria-selected', 'false');
        });

        // Add active to selected tab
        document.getElementById('tab-' + tabId).classList.add('active');
        document.getElementById('tab-' + tabId).setAttribute('aria-selected', 'true');

        filterNotifications();
    }

    // Filter notifications based on tab and search query
    function filterNotifications() {
        const query = document.getElementById('notifSearch').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.notif-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const category = card.getAttribute('data-category');
            const status = card.getAttribute('data-status');
            const title = card.querySelector('.notif-item-title').textContent.toLowerCase();
            const preview = card.querySelector('.notif-preview-text').textContent.toLowerCase();
            const sender = card.querySelector('.notif-sender').textContent.toLowerCase();
            const body = card.querySelector('.notif-expanded-body').textContent.toLowerCase();

            let matchesTab = false;
            if (currentTab === 'all') {
                matchesTab = true;
            } else if (currentTab === 'unread') {
                matchesTab = (status === 'unread');
            } else if (currentTab === 'system') {
                matchesTab = (category === 'system');
            } else if (currentTab === 'activity') {
                matchesTab = (category === 'activity');
            }

            let matchesSearch = true;
            if (query !== '') {
                matchesSearch = title.includes(query) || 
                                preview.includes(query) || 
                                sender.includes(query) ||
                                body.includes(query);
            }

            if (matchesTab && matchesSearch) {
                card.style.display = 'grid';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Display empty state if no notifications match
        const emptyState = document.getElementById('notifEmptyState');
        const listContainer = document.getElementById('notifList');
        
        if (visibleCount === 0) {
            emptyState.style.display = 'flex';
        } else {
            emptyState.style.display = 'none';
        }
    }

    // Dynamically calculate and update badge counts on the tab headers
    function updateBadges() {
        const cards = document.querySelectorAll('.notif-card');
        
        let total = cards.length;
        let unread = 0;
        let system = 0;
        let activity = 0;

        cards.forEach(card => {
            const category = card.getAttribute('data-category');
            const status = card.getAttribute('data-status');

            if (status === 'unread') unread++;
            if (category === 'system') system++;
            if (category === 'activity') activity++;
        });

        // Update badge DOM
        document.getElementById('badge-all').textContent = total;
        document.getElementById('badge-unread').textContent = unread;
        document.getElementById('badge-system').textContent = system;
        document.getElementById('badge-activity').textContent = activity;

        // Update summary text
        const summary = document.getElementById('notifUnreadSummary');
        if (unread > 0) {
            summary.textContent = `Bạn có ${unread} thông báo chưa đọc`;
            summary.className = 'notif-summary-badge has-unread';
        } else {
            summary.textContent = 'Bạn đã đọc tất cả thông báo';
            summary.className = 'notif-summary-badge all-read';
        }
    }

    // Initialize counts on page load
    document.addEventListener('DOMContentLoaded', () => {
        updateBadges();
    });
</script>
