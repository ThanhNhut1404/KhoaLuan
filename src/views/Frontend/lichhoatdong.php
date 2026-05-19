<?php
    $student = $student ?? [];
?>

<style>
    .calendar-page {
        display: grid;
        gap: 18px;
    }

    .calendar-title {
        font-size: 18px;
        font-weight: 700;
        color: #1d4ed8;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        text-align: center;
    }

    .calendar-toolbar {
        display: grid;
        gap: 12px;
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 12px;
        padding: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        align-items: end;
    }

    .filter-field {
        display: grid;
        gap: 6px;
        font-size: 12px;
        color: #4b5563;
        font-weight: 600;
    }

    .filter-control {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 13px;
        color: #1f2937;
        font-weight: 600;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        align-items: center;
    }

    .filter-btn {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #1f2937;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .filter-btn.primary {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #fff;
    }

    .calendar-card {
        background: #fff;
        border: 1px solid #e8ecf3;
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        display: grid;
        gap: 12px;
    }

    .calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .calendar-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #1f2937;
    }

    .nav-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #fff;
        font-weight: 700;
        color: #1f2937;
        cursor: pointer;
    }

    .today-btn {
        padding: 6px 10px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #fff;
        font-size: 12px;
        font-weight: 700;
        color: #1f2937;
    }

    .month-label {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .view-toggle {
        display: inline-flex;
        align-items: center;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        background: #f9fafb;
    }

    .view-toggle button {
        border: none;
        background: transparent;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 700;
        color: #4b5563;
        cursor: pointer;
    }

    .view-toggle button.active {
        background: #1d4ed8;
        color: #fff;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        border: 1px solid #e8ecf3;
        border-radius: 12px;
        overflow: hidden;
    }

    .calendar-cell {
        min-height: 92px;
        border-right: 1px solid #e8ecf3;
        border-bottom: 1px solid #e8ecf3;
        padding: 8px;
        display: grid;
        gap: 6px;
        font-size: 12px;
    }

    .calendar-cell:nth-child(7n) {
        border-right: none;
    }

    .calendar-cell.header {
        min-height: auto;
        background: #f8faff;
        font-weight: 700;
        color: #4b5563;
        text-align: center;
        padding: 8px 6px;
    }

    .calendar-date {
        font-weight: 700;
        color: #111827;
    }

    .calendar-date.muted {
        color: #cbd5f5;
    }

    .calendar-date.sunday {
        color: #ef4444;
    }

    .calendar-event {
        display: grid;
        gap: 2px;
        padding: 6px;
        border-radius: 8px;
        font-size: 11px;
        background: #eef2ff;
        color: #1f2937;
        border: 1px solid transparent;
    }

    .calendar-event .time {
        font-weight: 700;
        font-size: 10px;
    }

    .event--volunteer {
        background: #e8f1ff;
        border-color: #c7dbff;
        color: #1d4ed8;
    }

    .event--environment {
        background: #e8f8f0;
        border-color: #bdebd3;
        color: #047857;
    }

    .event--skill {
        background: #fff7e6;
        border-color: #fde4b5;
        color: #c2410c;
    }

    .event--academic {
        background: #efeaff;
        border-color: #d9ccff;
        color: #6d28d9;
    }

    .event--event {
        background: #ffe9ed;
        border-color: #ffc4d1;
        color: #be123c;
    }

    .calendar-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        font-size: 12px;
        color: #4b5563;
        align-items: center;
    }

    .legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #1d4ed8;
    }

    .legend-dot.volunteer { background: #1d4ed8; }
    .legend-dot.environment { background: #047857; }
    .legend-dot.skill { background: #f59e0b; }
    .legend-dot.academic { background: #7c3aed; }
    .legend-dot.event { background: #e11d48; }

    @media (max-width: 900px) {
        .calendar-cell {
            min-height: 82px;
        }
    }

    @media (max-width: 700px) {
        .calendar-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .calendar-grid {
            font-size: 11px;
        }

        .calendar-cell {
            min-height: 70px;
        }
    }
</style>

<div class="calendar-page">
    <h2 class="calendar-title">Lịch hoạt động
    </h2>

    <div class="calendar-toolbar">
        <div class="filter-row">
            <div class="filter-field">
                <span>Năm học</span>
                <div class="filter-control">2024 - 2025 <span>v</span></div>
            </div>
            <div class="filter-field">
                <span>Học kỳ</span>
                <div class="filter-control">Học kỳ 2 <span>v</span></div>
            </div>
            <div class="filter-field">
                <span>Loại hoạt động</span>
                <div class="filter-control">Tất cả <span>v</span></div>
            </div>
            <div class="filter-field">
                <span>Trạng thái</span>
                <div class="filter-control">Tất cả <span>v</span></div>
            </div>
            <div class="filter-actions">
                <button class="filter-btn" type="button">Đặt lại</button>
                <button class="filter-btn primary" type="button">Lọc</button>
            </div>
        </div>
    </div>

    <div class="calendar-card">
        <div class="calendar-header">
            <div class="calendar-nav">
                <button class="nav-btn" type="button">&#x2039;</button>
                <button class="nav-btn" type="button">&#x203A;</button>
                <button class="today-btn" type="button">Hôm nay</button>
            </div>
            <div class="month-label">Tháng 5, 2025 <span>v</span></div>
            <div class="view-toggle">
                <button class="active" type="button">Tháng</button>
                <button type="button">Tuần</button>
                <button type="button">Ngày</button>
            </div>
        </div>

        <div class="calendar-grid">
            <div class="calendar-cell header">T2</div>
            <div class="calendar-cell header">T3</div>
            <div class="calendar-cell header">T4</div>
            <div class="calendar-cell header">T5</div>
            <div class="calendar-cell header">T6</div>
            <div class="calendar-cell header">T7</div>
            <div class="calendar-cell header">CN</div>

            <div class="calendar-cell"><div class="calendar-date muted">28</div></div>
            <div class="calendar-cell"><div class="calendar-date muted">29</div></div>
            <div class="calendar-cell"><div class="calendar-date muted">30</div></div>
            <div class="calendar-cell"><div class="calendar-date">1</div></div>
            <div class="calendar-cell"><div class="calendar-date">2</div></div>
            <div class="calendar-cell"><div class="calendar-date">3</div></div>
            <div class="calendar-cell"><div class="calendar-date sunday">4</div></div>

            <div class="calendar-cell"><div class="calendar-date">5</div></div>
            <div class="calendar-cell">
                <div class="calendar-date">6</div>
                <div class="calendar-event event--volunteer">
                    <span class="time">08:00</span>
                    <span>Hiến máu tình nguyện
                    </span>
                </div>
            </div>
            <div class="calendar-cell"><div class="calendar-date">7</div></div>
            <div class="calendar-cell"><div class="calendar-date">8</div></div>
            <div class="calendar-cell">
                <div class="calendar-date">9</div>
                <div class="calendar-event event--environment">
                    <span class="time">14:00</span>
                    <span>Trồng cây xanh</span>
                </div>
            </div>
            <div class="calendar-cell"><div class="calendar-date">10</div></div>
            <div class="calendar-cell"><div class="calendar-date sunday">11</div></div>

            <div class="calendar-cell"><div class="calendar-date">12</div></div>
            <div class="calendar-cell">
                <div class="calendar-date">13</div>
                <div class="calendar-event event--skill">
                    <span class="time">09:00</span>
                    <span>Tap huan ky nang mem</span>
                </div>
            </div>
            <div class="calendar-cell"><div class="calendar-date">14</div></div>
            <div class="calendar-cell"><div class="calendar-date">15</div></div>
            <div class="calendar-cell"><div class="calendar-date">16</div></div>
            <div class="calendar-cell">
                <div class="calendar-date">17</div>
                <div class="calendar-event event--academic">
                    <span class="time">07:30</span>
                    <span>Ho tro ky thi THPT</span>
                </div>
            </div>
            <div class="calendar-cell"><div class="calendar-date sunday">18</div></div>

            <div class="calendar-cell"><div class="calendar-date">19</div></div>
            <div class="calendar-cell"><div class="calendar-date">20</div></div>
            <div class="calendar-cell">
                <div class="calendar-date">21</div>
                <div class="calendar-event event--academic">
                    <span class="time">13:30</span>
                    <span>Hoi thao sinh vien</span>
                </div>
            </div>
            <div class="calendar-cell"><div class="calendar-date">22</div></div>
            <div class="calendar-cell"><div class="calendar-date">23</div></div>
            <div class="calendar-cell"><div class="calendar-date">24</div></div>
            <div class="calendar-cell"><div class="calendar-date sunday">25</div></div>

            <div class="calendar-cell"><div class="calendar-date">26</div></div>
            <div class="calendar-cell"><div class="calendar-date">27</div></div>
            <div class="calendar-cell">
                <div class="calendar-date">28</div>
                <div class="calendar-event event--event">
                    <span class="time">08:00</span>
                    <span>Ngay hoi sinh vien</span>
                </div>
            </div>
            <div class="calendar-cell"><div class="calendar-date">29</div></div>
            <div class="calendar-cell"><div class="calendar-date">30</div></div>
            <div class="calendar-cell"><div class="calendar-date">31</div></div>
            <div class="calendar-cell"><div class="calendar-date muted">1</div></div>
        </div>

        <div class="calendar-legend">
            <div class="legend-item"><span class="legend-dot volunteer"></span> Học tập</div>
            <div class="legend-item"><span class="legend-dot environment"></span> Đạo đức</div>
            <div class="legend-item"><span class="legend-dot skill"></span> Tình nguyện</div>
            <div class="legend-item"><span class="legend-dot academic"></span> Thể lực</div>
            <div class="legend-item"><span class="legend-dot event"></span> Hội nhập</div>
        </div>
    </div>
</div>
