<?php
    $attendanceActivities = $attendanceActivities ?? [];

    if (empty($attendanceActivities)) {
        $attendanceActivities = [
            [
                'name' => 'Ngày hội tư vấn hướng nghiệp',
                'type' => 'Học tập',
                'time' => '08:00 - 25/05/2026',
                'location' => 'Hội trường A',
                'level' => 'Khoa',
                'unit' => 'Khoa Công nghệ thông tin',
                'point' => 5,
                'activity_status' => 'Đã đăng ký',
                'attendance_status' => 'Chưa điểm danh',
                'checkin_time' => '',
                'can_checkin' => true,
                'term' => 'Học kỳ 2 (2024 - 2025)',
            ],
            [
                'name' => 'Tập huấn kỹ năng làm việc nhóm',
                'type' => 'Hội nhập',
                'time' => '13:30 - 02/06/2026',
                'location' => 'Phòng B203',
                'level' => 'Khoa',
                'unit' => 'Đoàn khoa Công nghệ thông tin',
                'point' => 3,
                'activity_status' => 'Đã duyệt',
                'attendance_status' => 'Đã điểm danh',
                'checkin_time' => '13:45 - 02/06/2026',
                'can_checkin' => false,
                'term' => 'Học kỳ 2 (2024 - 2025)',
            ],
            [
                'name' => 'Hiến máu tình nguyện',
                'type' => 'Đạo đức',
                'time' => '07:00 - 05/06/2026',
                'location' => 'Sảnh nhà A',
                'level' => 'Trường',
                'unit' => 'Đoàn trường',
                'point' => 5,
                'activity_status' => 'Đã đăng ký',
                'attendance_status' => 'Chưa mở',
                'checkin_time' => '',
                'can_checkin' => false,
                'term' => 'Học kỳ 2 (2024 - 2025)',
            ],
            [
                'name' => 'Sinh hoạt chuyên đề an toàn thông tin',
                'type' => 'Học tập',
                'time' => '09:00 - 12/06/2026',
                'location' => 'Hội trường B',
                'level' => 'Khoa',
                'unit' => 'Bộ môn An toàn thông tin',
                'point' => 4,
                'activity_status' => 'Đã duyệt',
                'attendance_status' => 'Hết thời gian',
                'checkin_time' => '',
                'can_checkin' => false,
                'term' => 'Học kỳ 1 (2024 - 2025)',
            ],
            [
                'name' => 'Ngày chủ nhật xanh',
                'type' => 'Tình nguyện',
                'time' => '07:30 - 15/06/2026',
                'location' => 'Khuôn viên trường',
                'level' => 'Lớp',
                'unit' => 'Liên chi đoàn khoa',
                'point' => 3,
                'activity_status' => 'Đã đăng ký',
                'attendance_status' => 'Chưa điểm danh',
                'checkin_time' => '',
                'can_checkin' => true,
                'term' => 'Học kỳ hè (2024 - 2025)',
            ],
            [
                'name' => 'Workshop định hướng nghề nghiệp',
                'type' => 'Hội nhập',
                'time' => '14:00 - 18/06/2026',
                'location' => 'Phòng seminar C101',
                'level' => 'Câu lạc bộ',
                'unit' => 'CLB Kỹ năng sinh viên',
                'point' => 2,
                'activity_status' => 'Chờ duyệt',
                'attendance_status' => 'Không khả dụng',
                'checkin_time' => '',
                'can_checkin' => false,
                'term' => 'Học kỳ hè (2024 - 2025)',
            ],
            [
                'name' => 'Cuộc thi ý tưởng khởi nghiệp',
                'type' => 'Học tập',
                'time' => '08:30 - 22/06/2026',
                'location' => 'Trung tâm sáng tạo',
                'level' => 'Trường',
                'unit' => 'Trung tâm sáng tạo sinh viên',
                'point' => 5,
                'activity_status' => 'Đã duyệt',
                'attendance_status' => 'Chưa điểm danh',
                'checkin_time' => '',
                'can_checkin' => true,
                'term' => 'Học kỳ 2 (2024 - 2025)',
            ],
        ];
    }
?>

<style>
    .attendance-page {
        display: grid;
        gap: 16px;
    }

    .attendance-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
        text-align: left;
        letter-spacing: 0.6px;
        margin: 0;
    }

    .attendance-card {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        padding: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .attendance-panel__header {
        position: relative;
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .attendance-panel__body {
        padding: 16px;
    }

    .attendance-toolbar {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-left: auto;
    }

    .attendance-filter-toggle {
        width: 32px;
        height: 32px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #fff;
        color: var(--primary);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
    }

    .attendance-filter-toggle.active {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    .attendance-filter-toggle:hover {
        background: #f8fafc;
        color: #0b1f45;
    }

    .attendance-filter-toggle svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
        flex: 0 0 16px;
    }

    .attendance-filter-panel {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        z-index: 20;
        width: max-content;
        max-width: calc(100vw - 48px);
        padding: 14px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.16);
        display: none;
    }

    .attendance-filter-panel.open {
        display: block;
    }

    .attendance-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, max-content);
        gap: 12px;
    }

    .attendance-filter-field {
        display: grid;
        gap: 6px;
        font-size: 12px;
        color: var(--primary);
        font-weight: 700;
        width: max-content;
    }

    .attendance-filter-select {
        width: auto;
        min-width: 132px;
        min-height: 38px;
        padding: 0 34px 0 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background-color: #f9fafb;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231047a1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-position: right 10px center;
        background-repeat: no-repeat;
        background-size: 16px;
        color: #1f2937;
        font-size: 13px;
        font-weight: 600;
        outline: none;
        cursor: pointer;
        appearance: none;
    }

    .attendance-filter-select:focus {
        border-color: var(--primary-border-strong);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.12);
    }

    #attendanceTermFilter {
        min-width: 188px;
    }

    #attendanceTypeFilter {
        min-width: 132px;
    }

    #attendanceActivityStatusFilter {
        min-width: 158px;
    }

    #attendanceStatusFilter {
        min-width: 164px;
    }

    .attendance-filter-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 14px;
    }

    .attendance-filter-actions .attendance-filter-reset,
    .attendance-filter-actions .attendance-filter-apply {
        min-height: 38px;
        padding: 8px 20px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.2;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .attendance-filter-actions .attendance-filter-reset {
        color: #dc2626 !important;
        background: #ffffff !important;
        border-color: #e5e7eb !important;
    }

    .attendance-filter-actions .attendance-filter-reset:hover {
        color: #dc2626 !important;
        background: #e5e7eb !important;
        border-color: #cbd5e1 !important;
    }

    .attendance-filter-actions .attendance-filter-apply {
        color: #ffffff !important;
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%) !important;
        border-color: #16a34a !important;
    }

    .attendance-filter-actions .attendance-filter-apply:hover {
        color: #ffffff !important;
        background: linear-gradient(180deg, #15803d 0%, #166534 100%) !important;
        border-color: #15803d !important;
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .attendance-table thead th {
        background: var(--primary-soft);
        color: #1f2937;
        text-align: center;
        padding: 10px 12px;
        border-bottom: 1px solid #e8ecf3;
        border-right: 1px solid #e2e8f0;
        font-weight: 700;
        white-space: nowrap;
    }

    .attendance-table tbody td {
        padding: 10px 12px;
        border-bottom: 1px solid #eef2f7;
        border-right: 1px solid #eef2f7;
        color: #334155;
        vertical-align: top;
    }

    .attendance-table tbody td:not(:nth-child(2)) {
        text-align: center;
        vertical-align: middle;
    }

    .attendance-table tbody tr {
        border-top: 1px solid #eef2f7;
    }

    .attendance-table tbody tr:first-child {
        border-top: none;
    }

    .attendance-table tbody tr:nth-child(even) td {
        background: #f3f7fc;
    }

    .attendance-table tbody tr:nth-child(odd) td {
        background: #ffffff;
    }

    .attendance-table tbody tr:hover td {
        background: #eaf3ff;
    }

    .cell-center { text-align: center; }

    .attendance-col-stt {
        width: 54px;
        min-width: 54px;
        padding-left: 8px !important;
        padding-right: 8px !important;
    }

    .attendance-col-type,
    .attendance-col-activity-status {
        width: 112px;
        min-width: 112px;
        padding-left: 8px !important;
        padding-right: 8px !important;
        white-space: nowrap;
    }

    .attendance-col-location {
        width: 18%;
        min-width: 180px;
    }

    .attendance-col-status {
        width: 132px;
        min-width: 132px;
        padding-left: 8px !important;
        padding-right: 8px !important;
        white-space: nowrap;
    }

    .attendance-col-actions {
        width: 88px;
        min-width: 88px;
        padding-left: 8px !important;
        padding-right: 8px !important;
        white-space: nowrap;
    }

    .status-badge,
    .attendance-status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 78px;
        padding: 4px 8px;
        border-radius: 999px;
        background: #eff6ff;
        color: var(--primary);
        font-size: 12px;
        font-weight: 700;
        line-height: 1.2;
        white-space: nowrap;
    }

    .attendance-status-badge {
        min-width: 104px;
        border: 1px solid transparent;
    }

    .attendance-status-badge.pending {
        background: #fffbeb;
        border-color: #fde68a;
        color: #d97706;
    }

    .attendance-status-badge.done {
        background: #ecfdf5;
        border-color: #bbf7d0;
        color: #15803d;
    }

    .attendance-status-badge.not-open {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
    }

    .attendance-status-badge.expired,
    .attendance-status-badge.unavailable {
        background: #fef2f2;
        border-color: #fecaca;
        color: #b91c1c;
    }

    .attendance-action-list {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .attendance-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s;
        padding: 0;
    }

    .attendance-action-btn:hover {
        border-color: #d1d5db;
        background: #f9fafb;
    }

    .attendance-action-btn.view {
        color: #1d4ed8;
    }

    .attendance-action-btn.view:hover {
        background: #eff6ff;
    }

    .attendance-action-btn.checkin {
        color: var(--primary);
    }

    .attendance-action-btn.checkin:hover {
        background: #eff6ff;
    }

    .attendance-action-btn.disabled {
        background: #f8fafc;
        cursor: not-allowed;
        pointer-events: auto;
    }

    .attendance-action-btn.disabled:hover {
        border-color: #e5e7eb;
        background: #f8fafc;
        transform: none;
    }

    .attendance-action-btn.disabled.not-open {
        color: #f59e0b;
        background: #fffbeb;
    }

    .attendance-action-btn.disabled.not-open:hover {
        background: #fffbeb;
    }

    .attendance-action-btn.disabled.unapproved,
    .attendance-action-btn.disabled.unavailable {
        color: #6b7280;
        background: #f8fafc;
    }

    .attendance-action-btn.disabled.expired {
        color: #dc2626;
        background: #fef2f2;
    }

    .attendance-action-btn.disabled.expired:hover {
        background: #fef2f2;
    }

    .attendance-action-btn.disabled.done {
        color: #16a34a;
        background: #f0fdf4;
    }

    .attendance-action-btn.disabled.done:hover {
        background: #f0fdf4;
    }

    .attendance-action-btn i {
        font-size: 14px;
        line-height: 1;
    }

    .attendance-empty-state {
        text-align: center;
        color: #6b7280;
        padding: 16px 0;
        font-size: 13px;
    }

    .attendance-pagination-container {
        padding: 14px 14px 4px;
        border-top: 1px solid #e8ecf3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #6b7280;
    }

    .attendance-pagination {
        display: flex;
        gap: 6px;
        align-items: center;
        margin: 0;
    }

    .attendance-pagination-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #fff;
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s;
    }

    .attendance-pagination-btn:hover {
        border-color: #d1d5db;
        background: #f9fafb;
        color: #4b5563;
    }

    .attendance-pagination-btn.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .attendance-pagination-btn.disabled {
        opacity: 0.45;
        cursor: not-allowed;
        pointer-events: none;
        background: #f9fafb;
        color: #9ca3af;
    }

    .attendance-pagination-btn.prev,
    .attendance-pagination-btn.next,
    .attendance-pagination-btn.first,
    .attendance-pagination-btn.last {
        min-width: auto;
        padding: 0 8px;
    }

    @media (max-width: 900px) {
        .attendance-panel__body { padding: 12px; }
        .attendance-table { font-size: 12px; }
        .attendance-filter-grid { grid-template-columns: repeat(2, max-content); }
    }

    @media (max-width: 560px) {
        .attendance-filter-grid { grid-template-columns: 1fr; }
        .attendance-filter-field,
        .attendance-filter-select {
            width: 100%;
        }
        .attendance-filter-actions { flex-direction: column-reverse; }
        .attendance-filter-actions button { width: 100%; }
        .attendance-pagination-container {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="attendance-page">
    <div class="attendance-card card">
        <div class="attendance-panel__header card-header">
            <h2 class="attendance-title">Điểm danh hoạt động</h2>
            <div class="attendance-toolbar">
                <button class="attendance-filter-toggle btn btn-outline-secondary" id="attendanceFilterToggle" type="button" title="Bộ lọc" aria-label="Bộ lọc" aria-expanded="false">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M4 5h16l-6 7v5l-4 2v-7L4 5Z" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <div class="attendance-filter-panel" id="attendanceFilterPanel" aria-hidden="true">
                    <div class="attendance-filter-grid">
                        <label class="attendance-filter-field">
                            <span>Học kỳ</span>
                            <select class="attendance-filter-select form-select" id="attendanceTermFilter" aria-label="Lọc theo học kỳ">
                                <option value="">Tất cả</option>
                                <option value="Học kỳ 2 (2024 - 2025)">Học kỳ 2 (2024 - 2025)</option>
                                <option value="Học kỳ 1 (2024 - 2025)">Học kỳ 1 (2024 - 2025)</option>
                                <option value="Học kỳ hè (2024 - 2025)">Học kỳ hè (2024 - 2025)</option>
                            </select>
                        </label>

                        <label class="attendance-filter-field">
                            <span>Loại hoạt động</span>
                            <select class="attendance-filter-select form-select" id="attendanceTypeFilter" aria-label="Lọc theo loại hoạt động">
                                <option value="">Tất cả</option>
                                <option value="Học tập">Học tập</option>
                                <option value="Đạo đức">Đạo đức</option>
                                <option value="Tình nguyện">Tình nguyện</option>
                                <option value="Hội nhập">Hội nhập</option>
                            </select>
                        </label>

                        <label class="attendance-filter-field">
                            <span>Trạng thái hoạt động</span>
                            <select class="attendance-filter-select form-select" id="attendanceActivityStatusFilter" aria-label="Lọc theo trạng thái hoạt động">
                                <option value="">Tất cả</option>
                                <option value="Đã đăng ký">Đã đăng ký</option>
                                <option value="Đã duyệt">Đã duyệt</option>
                                <option value="Chờ duyệt">Chờ duyệt</option>
                            </select>
                        </label>

                        <label class="attendance-filter-field">
                            <span>Trạng thái điểm danh</span>
                            <select class="attendance-filter-select form-select" id="attendanceStatusFilter" aria-label="Lọc theo trạng thái điểm danh">
                                <option value="">Tất cả</option>
                                <option value="Chưa điểm danh">Chưa điểm danh</option>
                                <option value="Đã điểm danh">Đã điểm danh</option>
                                <option value="Chưa mở">Chưa mở</option>
                                <option value="Hết thời gian">Hết thời gian</option>
                                <option value="Không khả dụng">Không khả dụng</option>
                            </select>
                        </label>
                    </div>

                    <div class="attendance-filter-actions">
                        <button class="attendance-filter-reset btn btn-outline-secondary" id="attendanceFilterReset" type="button">Đặt lại</button>
                        <button class="attendance-filter-apply btn btn-primary" id="attendanceFilterApply" type="button">Áp dụng</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="attendance-panel__body card-body">
            <div class="table-responsive"><table class="attendance-table table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th class="cell-center attendance-col-stt">STT</th>
                        <th>Tên hoạt động</th>
                        <th class="attendance-col-type">Loại hoạt động</th>
                        <th>Thời gian</th>
                        <th class="attendance-col-location">Địa điểm</th>
                        <th class="attendance-col-activity-status">Trạng thái hoạt động</th>
                        <th class="attendance-col-status">Trạng thái điểm danh</th>
                        <th class="cell-center attendance-col-actions">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($attendanceActivities)): ?>
                        <?php foreach ($attendanceActivities as $index => $activity): ?>
                            <?php
                                $attendanceStatus = (string) ($activity['attendance_status'] ?? 'Chưa mở');
                                $activityStatus = (string) ($activity['activity_status'] ?? 'Đã đăng ký');
                                $canCheckin = (bool) ($activity['can_checkin'] ?? false);
                                $attendanceClass = match ($attendanceStatus) {
                                    'Chưa điểm danh' => 'pending',
                                    'Đã điểm danh' => 'done',
                                    'Hết thời gian' => 'expired',
                                    'Không khả dụng' => 'unavailable',
                                    default => 'not-open',
                                };
                                $attendanceIcon = 'fa-lock';
                                $attendanceTitle = 'Chưa đến thời gian điểm danh.';
                                $disabledReasonClass = 'not-open';

                                if ($canCheckin) {
                                    $attendanceIcon = 'fa-camera';
                                    $attendanceTitle = 'Điểm danh hoạt động';
                                } elseif ($attendanceStatus === 'Đã điểm danh') {
                                    $attendanceIcon = 'fa-circle-check';
                                    $attendanceTitle = 'Bạn đã điểm danh thành công.';
                                    $disabledReasonClass = 'done';
                                } elseif ($attendanceStatus === 'Hết thời gian') {
                                    $attendanceIcon = 'fa-ban';
                                    $attendanceTitle = 'Hoạt động đã hết thời gian điểm danh.';
                                    $disabledReasonClass = 'expired';
                                } elseif ($activityStatus === 'Chờ duyệt') {
                                    $attendanceIcon = 'fa-user-clock';
                                    $attendanceTitle = 'Đăng ký của bạn chưa được duyệt.';
                                    $disabledReasonClass = 'unapproved';
                                } elseif ($attendanceStatus === 'Không khả dụng') {
                                    $attendanceIcon = 'fa-user-clock';
                                    $attendanceTitle = 'Đăng ký của bạn chưa được duyệt.';
                                    $disabledReasonClass = 'unavailable';
                                }
                            ?>
                            <tr
                                data-term="<?= htmlspecialchars($activity['term'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                data-type="<?= htmlspecialchars($activity['type'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                data-activity-status="<?= htmlspecialchars($activityStatus, ENT_QUOTES, 'UTF-8') ?>"
                                data-attendance-status="<?= htmlspecialchars($attendanceStatus, ENT_QUOTES, 'UTF-8') ?>"
                                data-name="<?= htmlspecialchars($activity['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                data-time="<?= htmlspecialchars($activity['time'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                data-location="<?= htmlspecialchars($activity['location'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                data-checkin-time="<?= htmlspecialchars($activity['checkin_time'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                data-can-checkin="<?= $canCheckin ? '1' : '0' ?>"
                            >
                                <td class="cell-center attendance-col-stt"><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($activity['name'] ?? '') ?></td>
                                <td class="attendance-col-type"><?= htmlspecialchars($activity['type'] ?? '') ?></td>
                                <td><?= htmlspecialchars($activity['time'] ?? '') ?></td>
                                <td class="attendance-col-location"><?= htmlspecialchars($activity['location'] ?? '') ?></td>
                                <td class="cell-center attendance-col-activity-status"><span class="status-badge"><?= htmlspecialchars($activityStatus) ?></span></td>
                                <td class="cell-center attendance-col-status">
                                    <span class="attendance-status-badge <?= $attendanceClass ?>"><?= htmlspecialchars($attendanceStatus) ?></span>
                                </td>
                                <td class="cell-center attendance-col-actions">
                                    <div class="attendance-action-list">
                                        <button class="attendance-action-btn view btn" type="button" title="Xem" aria-label="Xem">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <?php if ($canCheckin): ?>
                                            <button
                                                class="attendance-action-btn checkin btn"
                                                type="button"
                                                title="<?= htmlspecialchars($attendanceTitle, ENT_QUOTES, 'UTF-8') ?>"
                                                aria-label="<?= htmlspecialchars($attendanceTitle, ENT_QUOTES, 'UTF-8') ?>"
                                                data-name="<?= htmlspecialchars($activity['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-type="<?= htmlspecialchars($activity['type'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-time="<?= htmlspecialchars($activity['time'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-location="<?= htmlspecialchars($activity['location'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-level="<?= htmlspecialchars($activity['level'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-unit="<?= htmlspecialchars($activity['unit'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-point="<?= htmlspecialchars((string) ($activity['point'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                                data-status="<?= htmlspecialchars($activityStatus, ENT_QUOTES, 'UTF-8') ?>"
                                                data-attendance-status="<?= htmlspecialchars($attendanceStatus, ENT_QUOTES, 'UTF-8') ?>"
                                            >
                                                <i class="fa-solid <?= $attendanceIcon ?>"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="attendance-action-btn disabled <?= htmlspecialchars($disabledReasonClass, ENT_QUOTES, 'UTF-8') ?> btn" type="button" title="<?= htmlspecialchars($attendanceTitle, ENT_QUOTES, 'UTF-8') ?>" aria-label="<?= htmlspecialchars($attendanceTitle, ENT_QUOTES, 'UTF-8') ?>" disabled>
                                                <i class="fa-solid <?= $attendanceIcon ?>"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="attendance-empty-state">Chưa có hoạt động nào cần điểm danh.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table></div>

            <div class="attendance-pagination-container" id="attendancePaginationContainer">
                <div class="attendance-pagination-info" id="attendancePaginationInfo"></div>
                <div class="attendance-pagination mb-0" id="attendancePagination"></div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const toggle = document.getElementById('attendanceFilterToggle');
        const panel = document.getElementById('attendanceFilterPanel');
        const applyBtn = document.getElementById('attendanceFilterApply');
        const resetBtn = document.getElementById('attendanceFilterReset');
        const termFilter = document.getElementById('attendanceTermFilter');
        const typeFilter = document.getElementById('attendanceTypeFilter');
        const activityStatusFilter = document.getElementById('attendanceActivityStatusFilter');
        const attendanceStatusFilter = document.getElementById('attendanceStatusFilter');
        const tbody = document.querySelector('.attendance-table tbody');
        const rows = Array.from(document.querySelectorAll('.attendance-table tbody tr[data-term]'));
        const pagination = document.getElementById('attendancePagination');
        const paginationInfo = document.getElementById('attendancePaginationInfo');
        const itemsPerPage = 10;
        let currentPage = 1;
        let emptyRow = document.getElementById('attendanceFilterEmptyRow');

        if (!toggle || !panel || !applyBtn || !resetBtn || !tbody) return;

        if (!emptyRow) {
            emptyRow = document.createElement('tr');
            emptyRow.id = 'attendanceFilterEmptyRow';
            emptyRow.style.display = 'none';
            emptyRow.innerHTML = '<td colspan="8" class="attendance-empty-state">Không có hoạt động phù hợp với bộ lọc.</td>';
            tbody.appendChild(emptyRow);
        }

        function setPanelOpen(isOpen) {
            panel.classList.toggle('open', isOpen);
            toggle.classList.toggle('active', isOpen);
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            panel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        }

        function matches(row, field, value) {
            return !value || row.dataset[field] === value;
        }

        function getFilteredRows() {
            return rows.filter(function(row) {
                return matches(row, 'term', termFilter.value) &&
                    matches(row, 'type', typeFilter.value) &&
                    matches(row, 'activityStatus', activityStatusFilter.value) &&
                    matches(row, 'attendanceStatus', attendanceStatusFilter.value);
            });
        }

        function createPageButton(label, page, classes, disabled) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'attendance-pagination-btn page-link page-item' + (classes ? ' ' + classes : '');
            button.textContent = label;

            if (disabled) {
                button.classList.add('disabled');
                button.disabled = true;
            } else {
                button.addEventListener('click', function() {
                    renderPage(page);
                });
            }

            return button;
        }

        function renderPage(page) {
            const filteredRows = getFilteredRows();
            const totalItems = filteredRows.length;
            const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
            currentPage = Math.min(Math.max(1, page), totalPages);
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, totalItems);

            rows.forEach(function(row) {
                row.style.display = 'none';
            });

            filteredRows.slice(startIndex, endIndex).forEach(function(row) {
                row.style.display = '';
            });

            emptyRow.style.display = totalItems === 0 ? '' : 'none';

            if (paginationInfo) {
                const from = totalItems === 0 ? 0 : startIndex + 1;
                paginationInfo.textContent = 'Hiển thị ' + from + ' - ' + endIndex + ' của ' + totalItems + ' hoạt động';
            }

            if (!pagination) return;
            pagination.innerHTML = '';
            pagination.appendChild(createPageButton('<<', 1, 'first', currentPage === 1));
            pagination.appendChild(createPageButton('<', currentPage - 1, 'prev', currentPage === 1));

            for (let i = 1; i <= totalPages; i += 1) {
                pagination.appendChild(createPageButton(String(i), i, i === currentPage ? 'active' : '', false));
            }

            pagination.appendChild(createPageButton('>', currentPage + 1, 'next', currentPage === totalPages));
            pagination.appendChild(createPageButton('>>', totalPages, 'last', currentPage === totalPages));
        }

        function applyFilters() {
            currentPage = 1;
            renderPage(currentPage);
            setPanelOpen(false);
        }

        function resetFilters() {
            [termFilter, typeFilter, activityStatusFilter, attendanceStatusFilter].forEach(function(select) {
                select.value = '';
            });

            currentPage = 1;
            renderPage(currentPage);
            setPanelOpen(false);
        }

        toggle.addEventListener('click', function(event) {
            event.stopPropagation();
            setPanelOpen(!panel.classList.contains('open'));
        });

        panel.addEventListener('click', function(event) {
            event.stopPropagation();
        });

        applyBtn.addEventListener('click', applyFilters);
        resetBtn.addEventListener('click', resetFilters);

        tbody.addEventListener('click', function(event) {
            const attendanceButton = event.target.closest('.attendance-action-btn.checkin');
            if (!attendanceButton) return;
            event.stopPropagation();
            if (typeof window.openAttendanceModal === 'function') {
                window.openAttendanceModal(attendanceButton);
            }
        });

        document.addEventListener('click', function() {
            setPanelOpen(false);
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                setPanelOpen(false);
            }
        });

        renderPage(currentPage);
    })();
</script>

<?php require __DIR__ . '/attendance_modal.php'; ?>
