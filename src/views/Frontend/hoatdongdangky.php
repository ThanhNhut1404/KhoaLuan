<?php
    $student = $student ?? [];
    $activities = $activities ?? [];

    if (empty($activities)) {
        $activities = [
            [
                'name' => 'Ngày hội tư vấn hướng nghiệp',
                'type' => 'Học tập',
                'time' => '08:00 - 25/05/2026',
                'location' => 'Hội trường A',
                'level' => 'Khoa',
                'status' => 'Đã đăng ký',
                'term' => 'Học kỳ 2 (2024 - 2025)',
            ],
            [
                'name' => 'Chiến dịch tình nguyện hè',
                'type' => 'Tình nguyện',
                'time' => '07:30 - 30/05/2026',
                'location' => 'Phường Linh Trung',
                'level' => 'Trường',
                'status' => 'Chờ duyệt',
                'term' => 'Học kỳ 2 (2024 - 2025)',
            ],
            [
                'name' => 'Tập huấn kỹ năng làm việc nhóm',
                'type' => 'Hội nhập',
                'time' => '13:30 - 02/06/2026',
                'location' => 'Phòng B203',
                'level' => 'Khoa',
                'status' => 'Đã duyệt',
                'term' => 'Học kỳ 2 (2024 - 2025)',
            ],
            [
                'name' => 'Hiến máu tình nguyện',
                'type' => 'Đạo đức',
                'time' => '07:00 - 05/06/2026',
                'location' => 'Sảnh nhà A',
                'level' => 'Trường',
                'status' => 'Đã đăng ký',
                'term' => 'Học kỳ 2 (2024 - 2025)',
            ],
            [
                'name' => 'Giải chạy sinh viên khỏe',
                'type' => 'Thể lực',
                'time' => '06:00 - 08/06/2026',
                'location' => 'Sân vận động trường',
                'level' => 'Trường',
                'status' => 'Chờ duyệt',
                'term' => 'Học kỳ 1 (2024 - 2025)',
            ],
            [
                'name' => 'Sinh hoạt chuyên đề an toàn thông tin',
                'type' => 'Học tập',
                'time' => '09:00 - 12/06/2026',
                'location' => 'Hội trường B',
                'level' => 'Khoa',
                'status' => 'Đã duyệt',
                'term' => 'Học kỳ 1 (2024 - 2025)',
            ],
            [
                'name' => 'Ngày chủ nhật xanh',
                'type' => 'Tình nguyện',
                'time' => '07:30 - 15/06/2026',
                'location' => 'Khuôn viên trường',
                'level' => 'Lớp',
                'status' => 'Đã đăng ký',
                'term' => 'Học kỳ hè (2024 - 2025)',
            ],
            [
                'name' => 'Workshop định hướng nghề nghiệp',
                'type' => 'Hội nhập',
                'time' => '14:00 - 18/06/2026',
                'location' => 'Phòng seminar C101',
                'level' => 'Câu lạc bộ',
                'status' => 'Chờ duyệt',
                'term' => 'Học kỳ hè (2024 - 2025)',
            ],
            [
                'name' => 'Cuộc thi ý tưởng khởi nghiệp',
                'type' => 'Học tập',
                'time' => '08:30 - 22/06/2026',
                'location' => 'Trung tâm sáng tạo',
                'level' => 'Trường',
                'status' => 'Đã duyệt',
                'term' => 'Học kỳ 2 (2024 - 2025)',
            ],
            [
                'name' => 'Giao lưu câu lạc bộ tiếng Anh',
                'type' => 'Khác',
                'time' => '18:00 - 25/06/2026',
                'location' => 'Phòng sinh hoạt CLB',
                'level' => 'Câu lạc bộ',
                'status' => 'Đã hủy',
                'term' => 'Học kỳ 1 (2024 - 2025)',
            ],
        ];
    }

?>

<style>
    .joined-page {
        display: grid;
        gap: 16px;
    }

    .joined-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
        text-align: left;
        letter-spacing: 0.6px;
        margin: 0;
    }

    .joined-card {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        padding: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .joined-panel__header {
        position: relative;
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .joined-panel__body {
        padding: 16px;
    }

    .joined-toolbar {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-left: auto;
    }

    .filter-toggle {
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

    .filter-toggle.active {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    .filter-toggle:hover {
        background: #f8fafc;
        color: #0b1f45;
    }

    .filter-toggle svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
        flex: 0 0 16px;
    }

    .filter-panel {
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

    .filter-panel.open {
        display: block;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, max-content);
        gap: 12px;
    }

    .filter-field {
        display: grid;
        gap: 6px;
        font-size: 12px;
        color: var(--primary);
        font-weight: 700;
        width: max-content;
    }

    .filter-select {
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

    .filter-select:focus {
        border-color: var(--primary-border-strong);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.12);
    }

    #registeredTermFilter {
        min-width: 188px;
    }

    #registeredTypeFilter,
    #registeredLevelFilter,
    #registeredStatusFilter {
        min-width: 132px;
    }

    .filter-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 14px;
    }

    .filter-actions .filter-reset-btn,
    .filter-actions .filter-apply-btn {
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

    .filter-actions .filter-reset-btn {
        color: #dc2626 !important;
        background: #ffffff !important;
        border-color: #e5e7eb !important;
    }

    .filter-actions .filter-reset-btn:hover {
        color: #dc2626 !important;
        background: #e5e7eb !important;
        border-color: #cbd5e1 !important;
    }

    .filter-actions .filter-apply-btn {
        color: #ffffff !important;
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%) !important;
        border-color: #16a34a !important;
    }

    .filter-actions .filter-apply-btn:hover {
        color: #ffffff !important;
        background: linear-gradient(180deg, #15803d 0%, #166534 100%) !important;
        border-color: #15803d !important;
    }

    .joined-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .joined-table thead th {
        background: var(--primary-soft);
        color: #1f2937;
        text-align: center;
        padding: 10px 12px;
        border-bottom: 1px solid #e8ecf3;
        border-right: 1px solid #e2e8f0;
        font-weight: 700;
        white-space: nowrap;
    }

    .joined-table tbody td {
        padding: 10px 12px;
        border-bottom: 1px solid #eef2f7;
        border-right: 1px solid #eef2f7;
        color: #334155;
        vertical-align: top;
    }

    .joined-table tbody td:not(:nth-child(2)) {
        text-align: center;
        vertical-align: middle;
    }

    .joined-table tbody tr {
        border-top: 1px solid #eef2f7;
    }

    .joined-table tbody tr:first-child {
        border-top: none;
    }

    .joined-table tbody tr:nth-child(even) td {
        background: #f3f7fc;
    }

    .joined-table tbody tr:nth-child(odd) td {
        background: #ffffff;
    }

    .joined-table tbody tr:hover td {
        background: #eaf3ff;
    }

    .cell-center { text-align: center; }

    .col-stt {
        width: 54px;
        min-width: 54px;
        padding-left: 8px !important;
        padding-right: 8px !important;
    }

    .col-actions {
        width: 84px;
        min-width: 84px;
        padding-left: 8px !important;
        padding-right: 8px !important;
        white-space: nowrap;
    }

    .col-type {
        width: 108px;
        min-width: 108px;
        padding-left: 8px !important;
        padding-right: 8px !important;
        white-space: nowrap;
    }

    .col-location {
        width: 22%;
        min-width: 190px;
    }

    .col-status {
        width: 104px;
        min-width: 104px;
        padding-left: 8px !important;
        padding-right: 8px !important;
        white-space: nowrap;
    }

    .col-level {
        width: 104px;
        min-width: 104px;
        padding-left: 8px !important;
        padding-right: 8px !important;
        white-space: nowrap;
    }

    .status-badge {
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

    .action-list {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .action-btn {
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

    .action-btn:hover {
        border-color: #d1d5db;
        background: #f9fafb;
    }

    .action-btn.view {
        color: #1d4ed8;
    }

    .action-btn.view:hover {
        background: #eff6ff;
    }

    .action-btn.cancel {
        color: #b91c1c;
    }

    .action-btn.cancel:hover {
        background: #fef2f2;
    }

    .action-btn i {
        font-size: 14px;
        line-height: 1;
    }

    .empty-state {
        text-align: center;
        color: #6b7280;
        padding: 16px 0;
        font-size: 13px;
    }

    .pagination-container {
        padding: 14px 14px 4px;
        border-top: 1px solid #e8ecf3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #6b7280;
    }

    .pagination {
        display: flex;
        gap: 6px;
        align-items: center;
        margin: 0;
    }

    .pagination-btn {
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

    .pagination-btn:hover {
        border-color: #d1d5db;
        background: #f9fafb;
        color: #4b5563;
    }

    .pagination-btn.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .pagination-btn.disabled {
        opacity: 0.45;
        cursor: not-allowed;
        pointer-events: none;
        background: #f9fafb;
        color: #9ca3af;
    }

    .pagination-btn.prev,
    .pagination-btn.next,
    .pagination-btn.first,
    .pagination-btn.last {
        min-width: auto;
        padding: 0 8px;
    }

    @media (max-width: 900px) {
        .joined-panel__body { padding: 12px; }
        .joined-table { font-size: 12px; }
        .filter-grid { grid-template-columns: repeat(2, max-content); }
    }

    @media (max-width: 560px) {
        .filter-grid { grid-template-columns: 1fr; }
        .filter-field,
        .filter-select {
            width: 100%;
        }
        .filter-actions { flex-direction: column-reverse; }
        .filter-btn { width: 100%; }
        .pagination-container {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="joined-page">
    <div class="joined-card card">
        <div class="joined-panel__header card-header">
            <h2 class="joined-title">Hoạt động đã đăng ký</h2>
            <div class="joined-toolbar">
                <button class="filter-toggle btn btn-outline-secondary" id="registeredFilterToggle" type="button" title="Bộ lọc" aria-label="Bộ lọc" aria-expanded="false">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M4 5h16l-6 7v5l-4 2v-7L4 5Z" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <div class="filter-panel" id="registeredFilterPanel" aria-hidden="true">
                    <div class="filter-grid">
                        <label class="filter-field">
                            <span>Học kỳ</span>
                            <select class="filter-select form-select" id="registeredTermFilter" aria-label="Lọc theo học kỳ">
                                <option value="">Tất cả</option>
                                <option value="Học kỳ 2 (2024 - 2025)">Học kỳ 2 (2024 - 2025)</option>
                                <option value="Học kỳ 1 (2024 - 2025)">Học kỳ 1 (2024 - 2025)</option>
                                <option value="Học kỳ hè (2024 - 2025)">Học kỳ hè (2024 - 2025)</option>
                            </select>
                        </label>

                        <label class="filter-field">
                            <span>Loại hoạt động</span>
                            <select class="filter-select form-select" id="registeredTypeFilter" aria-label="Lọc theo loại hoạt động">
                                <option value="">Tất cả</option>
                                <option value="Học tập">Học tập</option>
                                <option value="Đạo đức">Đạo đức</option>
                                <option value="Thể lực">Thể lực</option>
                                <option value="Tình nguyện">Tình nguyện</option>
                                <option value="Hội nhập">Hội nhập</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </label>

                        <label class="filter-field">
                            <span>Cấp hoạt động</span>
                            <select class="filter-select form-select" id="registeredLevelFilter" aria-label="Lọc theo cấp hoạt động">
                                <option value="">Tất cả</option>
                                <option value="Trường">Trường</option>
                                <option value="Khoa">Khoa</option>
                                <option value="Lớp">Lớp</option>
                                <option value="Câu lạc bộ">Câu lạc bộ</option>
                            </select>
                        </label>

                        <label class="filter-field">
                            <span>Trạng thái</span>
                            <select class="filter-select form-select" id="registeredStatusFilter" aria-label="Lọc theo trạng thái">
                                <option value="">Tất cả</option>
                                <option value="Đã đăng ký">Đã đăng ký</option>
                                <option value="Chờ duyệt">Chờ duyệt</option>
                                <option value="Đã duyệt">Đã duyệt</option>
                                <option value="Đã hủy">Đã hủy</option>
                            </select>
                        </label>
                    </div>

                    <div class="filter-actions">
                        <button class="filter-btn filter-reset-btn btn btn-outline-secondary" id="registeredFilterReset" type="button">Đặt lại</button>
                        <button class="filter-btn filter-apply-btn btn btn-primary" id="registeredFilterApply" type="button">Áp dụng</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="joined-panel__body card-body">
            <div class="table-responsive"><table class="joined-table table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th class="cell-center col-stt">STT</th>
                        <th>Tên hoạt động</th>
                        <th class="col-type">Loại hoạt động</th>
                        <th>Thời gian</th>
                        <th class="col-location">Địa điểm</th>
                        <th class="col-level">Cấp hoạt động</th>
                        <th class="col-status">Trạng thái</th>
                        <th class="cell-center col-actions">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($activities)): ?>
                        <?php foreach ($activities as $index => $activity): ?>
                            <tr
                                data-term="<?= htmlspecialchars($activity['term'] ?? 'Học kỳ 2 (2024 - 2025)', ENT_QUOTES, 'UTF-8') ?>"
                                data-type="<?= htmlspecialchars($activity['type'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                data-level="<?= htmlspecialchars($activity['level'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                data-status="<?= htmlspecialchars($activity['status'] ?? 'Đã đăng ký', ENT_QUOTES, 'UTF-8') ?>"
                            >
                                <td class="cell-center col-stt"><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($activity['name'] ?? '') ?></td>
                                <td class="col-type"><?= htmlspecialchars($activity['type'] ?? '') ?></td>
                                <td><?= htmlspecialchars($activity['time'] ?? '') ?></td>
                                <td class="col-location"><?= htmlspecialchars($activity['location'] ?? '') ?></td>
                                <td class="col-level"><?= htmlspecialchars($activity['level'] ?? '') ?></td>
                                <td class="cell-center col-status"><span class="status-badge"><?= htmlspecialchars($activity['status'] ?? 'Đã đăng ký') ?></span></td>
                                <td class="cell-center col-actions">
                                    <div class="action-list">
                                        <button class="action-btn view btn" type="button" title="Xem" aria-label="Xem">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="action-btn cancel btn" type="button" title="Hủy" aria-label="Hủy">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="empty-state">Chưa có hoạt động nào đã đăng ký.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table></div>

            <div class="pagination-container" id="registeredPaginationContainer">
                <div class="pagination-info" id="registeredPaginationInfo"></div>
                <div class="pagination mb-0" id="registeredPagination"></div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const toggle = document.getElementById('registeredFilterToggle');
        const panel = document.getElementById('registeredFilterPanel');
        const applyBtn = document.getElementById('registeredFilterApply');
        const resetBtn = document.getElementById('registeredFilterReset');
        const termFilter = document.getElementById('registeredTermFilter');
        const typeFilter = document.getElementById('registeredTypeFilter');
        const levelFilter = document.getElementById('registeredLevelFilter');
        const statusFilter = document.getElementById('registeredStatusFilter');
        const tbody = document.querySelector('.joined-table tbody');
        const rows = Array.from(document.querySelectorAll('.joined-table tbody tr[data-term]'));
        const pagination = document.getElementById('registeredPagination');
        const paginationInfo = document.getElementById('registeredPaginationInfo');
        const itemsPerPage = 10;
        let currentPage = 1;
        let emptyRow = document.getElementById('registeredFilterEmptyRow');

        if (!toggle || !panel || !applyBtn || !resetBtn || !tbody) return;

        if (!emptyRow) {
            emptyRow = document.createElement('tr');
            emptyRow.id = 'registeredFilterEmptyRow';
            emptyRow.style.display = 'none';
            emptyRow.innerHTML = '<td colspan="8" class="empty-state">Không có hoạt động phù hợp với bộ lọc.</td>';
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
                    matches(row, 'level', levelFilter.value) &&
                    matches(row, 'status', statusFilter.value);
            });
        }

        function createPageButton(label, page, classes, disabled) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'pagination-btn page-link page-item' + (classes ? ' ' + classes : '');
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
            [termFilter, typeFilter, levelFilter, statusFilter].forEach(function(select) {
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
