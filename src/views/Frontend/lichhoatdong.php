<?php
    $student = $student ?? [];
?>

<style>
    .calendar-page {
        display: grid;
        gap: 16px;
    }

    .calendar-panel__header {
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
    }

    .calendar-panel__body {
        padding: 14px;
        display: grid;
        gap: 12px;
    }

    .calendar-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
        text-transform: none;
        letter-spacing: 0.6px;
        text-align: left;
        margin: 0;
    }

    .calendar-card {
        background: #fff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        padding: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        display: grid;
        gap: 0;
        overflow: hidden;
    }

    .calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .calendar-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-left: auto;
    }

    .date-picker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 32px;
        padding: 0 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        font-size: 12px;
        font-weight: 800;
        color: #1f2937;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.05);
    }

    .date-picker input {
        border: none;
        outline: none;
        font-size: 12px;
        font-weight: 700;
        color: #1f2937;
        background: transparent;
        width: 138px;
        height: 100%;
        padding: 0;
    }

    .date-picker input::-webkit-calendar-picker-indicator {
        cursor: pointer;
    }

    .date-now-btn {
        min-height: 32px;
        padding: 6px 10px;
        border-radius: 8px;
        border: 1px solid var(--primary);
        background: var(--primary);
        color: #ffffff;
        font-size: 12px;
        font-weight: 900 !important;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
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
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        padding: 0;
    }

    .nav-btn:hover {
        background: #f8faff;
        border-color: rgba(var(--primary-rgb), 0.35);
        color: var(--primary);
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
        background: var(--primary);
        color: #fff;
    }

    .schedule-table {
        display: grid;
        grid-template-columns: max-content repeat(7, minmax(0, 1fr));
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
    }

    .schedule-cell {
        border-right: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        padding: 10px 8px;
        font-size: 13px;
    }

    .schedule-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary) 100%);
        color: #ffffff;
        font-weight: 700;
        text-align: center;
        padding: 12px 6px;
    }

    .schedule-header .day {
        display: block;
        font-size: 14px;
        font-weight: 700;
    }

    .schedule-header .date {
        display: block;
        font-size: 12px;
        opacity: 0.9;
        margin-top: 2px;
    }

    .schedule-slot {
        background: #eff6ff;
        font-weight: 700;
        text-align: center;
        color: #1e3a8a;
        white-space: nowrap;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .schedule-label {
        white-space: nowrap;
        padding: 12px 14px;
    }

    .schedule-body {
        min-height: 190px;
        background-image:
            repeating-linear-gradient(0deg, rgba(203, 213, 225, 0.3), rgba(203, 213, 225, 0.3) 1px, transparent 1px, transparent 20px),
            repeating-linear-gradient(90deg, rgba(203, 213, 225, 0.3), rgba(203, 213, 225, 0.3) 1px, transparent 1px, transparent 20px);
        background-color: #ffffff;
        transition: background-color 0.2s ease;
    }

    .schedule-body:hover {
        background-color: #f8fafc;
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
        background: var(--primary);
    }

    .legend-dot.study { background: var(--primary); }
    .legend-dot.ethics { background: #16a34a; }
    .legend-dot.volunteer { background: #f59e0b; }
    .legend-dot.fitness { background: #7c3aed; }
    .legend-dot.integration { background: #14b8a6; }
    .legend-dot.other { background: #6b7280; }
    .legend-dot.paused { background: #dc2626; }

    @media (max-width: 900px) {
        .schedule-body {
            min-height: 150px;
        }
    }

    @media (max-width: 700px) {
        .calendar-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .schedule-table {
            font-size: 11px;
        }

        .schedule-body {
            min-height: 125px;
        }
    }
</style>

<div class="calendar-page">
    <div class="calendar-card card">
        <div class="calendar-panel__header card-header">
            <h2 class="calendar-title">Lịch hoạt động</h2>
        </div>

        <div class="calendar-panel__body card-body">
            <div class="calendar-header">
                <div class="month-label">Tháng 5, 2025 <span>v</span></div>
                <div class="calendar-controls">
                    <label class="date-picker">
                        <input class="form-control" type="date" value="2026-05-28" aria-label="Chọn ngày" />
                    </label>
                    <button class="date-now-btn btn btn-primary" type="button">Hiện tại</button>
                    <div class="calendar-nav">
                        <button class="nav-btn btn btn-outline-secondary" type="button">&#x2039;</button>
                        <button class="nav-btn btn btn-outline-secondary" type="button">&#x203A;</button>
                    </div>
                </div>
            </div>

            <div class="schedule-table table-responsive">
            <div class="schedule-cell schedule-header schedule-label">Ca hoạt động</div>
            <div class="schedule-cell schedule-header schedule-day"><span class="day">Thứ 2</span><span class="date">25/05/2026</span></div>
            <div class="schedule-cell schedule-header schedule-day"><span class="day">Thứ 3</span><span class="date">26/05/2026</span></div>
            <div class="schedule-cell schedule-header schedule-day"><span class="day">Thứ 4</span><span class="date">27/05/2026</span></div>
            <div class="schedule-cell schedule-header schedule-day"><span class="day">Thứ 5</span><span class="date">28/05/2026</span></div>
            <div class="schedule-cell schedule-header schedule-day"><span class="day">Thứ 6</span><span class="date">29/05/2026</span></div>
            <div class="schedule-cell schedule-header schedule-day"><span class="day">Thứ 7</span><span class="date">30/05/2026</span></div>
            <div class="schedule-cell schedule-header schedule-day"><span class="day">Chủ nhật</span><span class="date">31/05/2026</span></div>
            <div class="schedule-cell schedule-slot">SÁNG</div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>

            <div class="schedule-cell schedule-slot">CHIỀU</div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>

            <div class="schedule-cell schedule-slot">TỐI</div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            <div class="schedule-cell schedule-body"></div>
            </div>

            <div class="calendar-legend">
                <div class="legend-item"><span class="legend-dot ethics"></span> Đạo đức</div>
                <div class="legend-item"><span class="legend-dot fitness"></span> Thể lực</div>
                <div class="legend-item"><span class="legend-dot study"></span> Học tập</div>
                <div class="legend-item"><span class="legend-dot volunteer"></span> Tình nguyện</div>
                <div class="legend-item"><span class="legend-dot integration"></span> Hội nhập</div>
                <div class="legend-item"><span class="legend-dot other"></span> Khác</div>
                <div class="legend-item"><span class="legend-dot paused"></span> Tạm ngưng</div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        var dateInput = document.querySelector('.date-picker input');
        var monthLabel = document.querySelector('.month-label');
        var dayHeaders = Array.prototype.slice.call(document.querySelectorAll('.schedule-day'));
        var nowBtn = document.querySelector('.date-now-btn');
        var prevBtn = document.querySelector('.calendar-nav .nav-btn:first-child');
        var nextBtn = document.querySelector('.calendar-nav .nav-btn:last-child');

        if (!dateInput || !monthLabel || dayHeaders.length !== 7) {
            return;
        }

        function pad(value) {
            return value < 10 ? '0' + value : '' + value;
        }

        function toDateOnly(date) {
            return new Date(date.getFullYear(), date.getMonth(), date.getDate());
        }

        function formatDateVN(date) {
            return pad(date.getDate()) + '/' + pad(date.getMonth() + 1) + '/' + date.getFullYear();
        }

        function formatMonthLabel(date) {
            return 'Tháng ' + (date.getMonth() + 1) + ', ' + date.getFullYear();
        }

        function startOfWeekMonday(date) {
            var day = date.getDay();
            var diff = (day === 0 ? -6 : 1) - day;
            var start = new Date(date);
            start.setDate(date.getDate() + diff);
            return toDateOnly(start);
        }

        function updateWeek(date) {
            var weekStart = startOfWeekMonday(date);
            for (var i = 0; i < 7; i += 1) {
                var cellDate = new Date(weekStart);
                cellDate.setDate(weekStart.getDate() + i);
                var dayText = i === 6 ? 'Chủ nhật' : 'Thứ ' + (i + 2);
                var dayEl = dayHeaders[i].querySelector('.day');
                var dateEl = dayHeaders[i].querySelector('.date');
                if (dayEl) dayEl.textContent = dayText;
                if (dateEl) dateEl.textContent = formatDateVN(cellDate);
            }
            monthLabel.textContent = formatMonthLabel(date);
        }

        function setDateFromInput(value) {
            if (!value) return;
            var parts = value.split('-');
            var date = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
            updateWeek(date);
        }

        dateInput.addEventListener('change', function() {
            setDateFromInput(dateInput.value);
        });

        function setToday() {
            var today = new Date();
            dateInput.value = today.getFullYear() + '-' + pad(today.getMonth() + 1) + '-' + pad(today.getDate());
            updateWeek(today);
        }

        if (nowBtn) nowBtn.addEventListener('click', setToday);

        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                var current = new Date(dateInput.value || new Date());
                current.setDate(current.getDate() - 7);
                dateInput.value = current.getFullYear() + '-' + pad(current.getMonth() + 1) + '-' + pad(current.getDate());
                updateWeek(current);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                var current = new Date(dateInput.value || new Date());
                current.setDate(current.getDate() + 7);
                dateInput.value = current.getFullYear() + '-' + pad(current.getMonth() + 1) + '-' + pad(current.getDate());
                updateWeek(current);
            });
        }

        setDateFromInput(dateInput.value);
    })();
</script>
