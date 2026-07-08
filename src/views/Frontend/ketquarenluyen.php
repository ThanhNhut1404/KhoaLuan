<?php
    $selectedNienKhoa = (string) ($_GET['nien_khoa'] ?? '');
    $selectedHocKy = (string) ($_GET['hoc_ky'] ?? '');
    $selectedXepLoai = (string) ($_GET['xep_loai'] ?? '');
    $hasActiveResultFilters = $selectedNienKhoa !== '' || $selectedHocKy !== '' || $selectedXepLoai !== '';
?>

<style>
    .result-page {
        display: grid;
        gap: 16px;
    }


    .result-panel {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .result-panel__header {
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        overflow: visible;
    }

    .result-panel__body {
        padding: 14px;
    }

    .result-page-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
        margin: 0;
        letter-spacing: 0.6px;
        text-transform: none;
    }

    .result-subtitle {
        font-size: 12px;
        color: #6b7280;
        margin: 4px 0 0 0;
    }

    .result-page .btn-primary,
    .result-page .btn-light {
        border: none;
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s ease;
    }

    .result-page .btn-primary {
        background: #ffffff;
        color: #1f2937;
        border: 1px solid #e2e8f0;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
    }

    .result-page .btn-primary:hover {
        border-color: #cbd5f5;
        transform: translateY(-1px);
    }

    .result-table-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e8ecf3;
        overflow: hidden;
    }

    .result-filter-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-left: auto;
    }

    .result-filter-toggle {
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

    .result-filter-wrap.has-active .result-filter-toggle,
    .result-filter-toggle.active {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    .result-filter-toggle:hover {
        background: #f8fafc;
        color: #0b1f45;
    }

    .result-filter-toggle svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
        flex: 0 0 16px;
    }

    .result-filter-modal {
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

    .result-filter-modal.open {
        display: block;
    }

    .result-filter-card {
        width: max-content;
        max-width: 100%;
        background: transparent;
        border: 0;
        box-shadow: none;
        overflow: visible;
    }

    .result-filter-form {
        width: max-content;
        max-width: 100%;
    }

    .result-filter-body {
        display: grid;
        grid-template-columns: repeat(3, max-content);
        gap: 12px;
    }

    .result-filter-field {
        display: grid;
        gap: 6px;
        font-size: 12px;
        color: var(--primary);
        font-weight: 700;
        width: max-content;
    }

    .result-filter-field label {
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
    }

    .result-filter-field select {
        width: auto;
        min-width: 140px;
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

    .result-filter-field select:focus {
        border-color: var(--primary-border-strong);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.12);
    }

    #nienKhoaFilter {
        min-width: 148px;
    }

    #hocKyFilter {
        min-width: 128px;
    }

    #xepLoaiFilter {
        min-width: 138px;
    }

    .result-filter-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 14px;
    }

    .result-filter-actions .filter-reset-btn,
    .result-filter-actions .filter-apply-btn {
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

    .result-filter-actions .filter-reset-btn {
        color: #dc2626 !important;
        background: #ffffff !important;
        border-color: #e5e7eb !important;
    }

    .result-filter-actions .filter-reset-btn:hover {
        color: #dc2626 !important;
        background: #e5e7eb !important;
        border-color: #cbd5e1 !important;
    }

    .result-filter-actions .filter-apply-btn {
        color: #ffffff !important;
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%) !important;
        border-color: #16a34a !important;
    }

    .result-filter-actions .filter-apply-btn:hover {
        color: #ffffff !important;
        background: linear-gradient(180deg, #15803d 0%, #166534 100%) !important;
        border-color: #15803d !important;
    }

    .year-block {
        border-top: 1px solid #eef2f7;
    }

    .year-block:first-child {
        border-top: 0;
    }

    .year-header {
        padding: 12px 16px;
        font-weight: 700;
        color: var(--primary);
        background: #f8fafc;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .result-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        border: 1px solid #cbd5e1;
    }

    .result-table th,
    .result-table td {
        padding: 10px 12px;
        text-align: left;
        border-bottom: 1px solid #d7dee8;
        border-right: 1px solid #d7dee8;
    }

    .result-table th:last-child,
    .result-table td:last-child {
        border-right: 1px solid #d7dee8;
    }

    .result-table th:first-child,
    .result-table td:first-child {
        width: 20%;
    }

    .result-table td:first-child {
        font-weight: 600;
        color: #111827;
    }

    .result-table th:nth-child(2),
    .result-table td:nth-child(2) {
        width: 120px;
    }

    .result-table th:nth-child(3),
    .result-table td:nth-child(3) {
        width: 150px;
    }

    .result-table th:nth-child(2),
    .result-table th:nth-child(3),
    .result-table th:nth-child(4),
    .result-table td:nth-child(2),
    .result-table td:nth-child(3),
    .result-table td:nth-child(4) {
        text-align: center;
    }

    .result-table td:nth-child(4) {
        color: #ef4444;
    }

    .result-table th {
        background: #edf2f7;
        color: #475569;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .result-table td {
        color: #1f2937;
    }

    .result-table tr:last-child td {
        border-bottom: none;
    }

    .score-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 60px;
        padding: 4px 10px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 12px;
        background: #f1f5f9;
        color: #1f2937;
    }

    .pill-excellent {
        background: #e7f6ed;
        color: #047857;
    }

    .pill-good {
        background: #e0f2fe;
        color: var(--primary);
    }

    .pill-fair {
        background: #fef3c7;
        color: #b45309;
    }

    .score-text {
        font-weight: 700;
        color: #111827;
    }

    .result-table td.note-muted {
        color: #94a3b8;
        font-size: 12px;
        font-style: italic;
    }

    .summary-row {
        background: #edf2f7;
    }

    .result-table tr.summary-row td {
        font-weight: 700;
        color: #111827;
        background: #edf2f7;
        border-top: 2px solid #cbd5e1;
    }

    .result-table tr.summary-row td:first-child {
        font-weight: 700;
        color: #475569;
        font-size: 12px;
        letter-spacing: 0.3px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 900px) {
        .result-filter-body {
            grid-template-columns: repeat(2, max-content);
        }

        .result-table th,
        .result-table td {
            padding: 10px 8px;
        }
    }

    @media (max-width: 560px) {
        .result-filter-body {
            grid-template-columns: 1fr;
        }

        .result-filter-field,
        .result-filter-field select {
            width: 100%;
        }

        .result-filter-actions {
            flex-direction: column-reverse;
        }

        .result-filter-actions .filter-reset-btn,
        .result-filter-actions .filter-apply-btn {
            width: 100%;
        }
    }
</style>

<div class="result-page">
    <div class="result-panel card">
        <div class="result-panel__header card-header">
            <h1 class="result-page-title">Kết quả rèn luyện</h1>
            <div class="result-filter-wrap <?= $hasActiveResultFilters ? 'has-active' : '' ?>">
                <button class="result-filter-toggle btn btn-outline-secondary" id="resultFilterToggle" type="button" onclick="openResultFilter()" title="Bộ lọc" aria-label="Bộ lọc" aria-expanded="false">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M4 5h16l-6 7v5l-4 2v-7L4 5Z" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <div class="result-filter-modal" id="resultFilterModal" aria-hidden="true">
                    <div class="result-filter-card">
                        <form class="result-filter-form" method="get" action="/KhoaLuan/public/student.php">
                            <input type="hidden" name="action" value="ketquarenluyen">
                            <div class="result-filter-body">
                                <div class="result-filter-field">
                                    <label for="nienKhoaFilter">Niên khóa</label>
                                    <select id="nienKhoaFilter" name="nien_khoa">
                                        <option value="">Tất cả</option>
                                        <option value="2023-2024" <?= $selectedNienKhoa === '2023-2024' ? 'selected' : '' ?>>2023 - 2024</option>
                                        <option value="2022-2023" <?= $selectedNienKhoa === '2022-2023' ? 'selected' : '' ?>>2022 - 2023</option>
                                    </select>
                                </div>
                                <div class="result-filter-field">
                                    <label for="hocKyFilter">Học kỳ</label>
                                    <select id="hocKyFilter" name="hoc_ky">
                                        <option value="">Tất cả</option>
                                        <option value="1" <?= $selectedHocKy === '1' ? 'selected' : '' ?>>Học kỳ 1</option>
                                        <option value="2" <?= $selectedHocKy === '2' ? 'selected' : '' ?>>Học kỳ 2</option>
                                        <option value="3" <?= $selectedHocKy === '3' ? 'selected' : '' ?>>Học kỳ 3</option>
                                    </select>
                                </div>
                                <div class="result-filter-field">
                                    <label for="xepLoaiFilter">Xếp loại</label>
                                    <select id="xepLoaiFilter" name="xep_loai">
                                        <option value="">Tất cả</option>
                                        <option value="xuat-sac" <?= $selectedXepLoai === 'xuat-sac' ? 'selected' : '' ?>>Xuất sắc</option>
                                        <option value="tot" <?= $selectedXepLoai === 'tot' ? 'selected' : '' ?>>Tốt</option>
                                        <option value="kha" <?= $selectedXepLoai === 'kha' ? 'selected' : '' ?>>Khá</option>
                                        <option value="trung-binh" <?= $selectedXepLoai === 'trung-binh' ? 'selected' : '' ?>>Trung bình</option>
                                        <option value="yeu" <?= $selectedXepLoai === 'yeu' ? 'selected' : '' ?>>Yếu</option>
                                        <option value="kem" <?= $selectedXepLoai === 'kem' ? 'selected' : '' ?>>Kém</option>
                                    </select>
                                </div>
                            </div>
                            <div class="result-filter-actions">
                                <a class="filter-btn filter-reset-btn btn btn-outline-secondary" href="/KhoaLuan/public/student.php?action=ketquarenluyen">Đặt lại</a>
                                <button class="filter-btn filter-apply-btn btn btn-primary" type="submit">Áp dụng</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="result-panel__body card-body">
            <div class="result-table-card">
        <div class="year-block" data-nien-khoa="2023-2024">
            <div class="year-header">Năm học 2023 - 2024</div>
            <div class="table-responsive"><table class="result-table table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Học kỳ</th>
                        <th>Điểm</th>
                        <th>Xếp loại</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-result-row data-hoc-ky="1" data-xep-loai="tot">
                        <td>Học kỳ 1 (2023-2024)</td>
                        <td class="score-text">82,00</td>
                        <td><span class="score-pill badge rounded-pill pill-good">Tốt</span></td>
                        <td class="note-muted">Không có ghi chú</td>
                    </tr>
                    <tr data-result-row data-hoc-ky="2" data-xep-loai="tot">
                        <td>Học kỳ 2 (2023-2024)</td>
                        <td class="score-text">87,00</td>
                        <td><span class="score-pill badge rounded-pill pill-good">Tốt</span></td>
                        <td class="note-muted">Không có ghi chú</td>
                    </tr>
                    <tr data-result-row data-hoc-ky="3" data-xep-loai="xuat-sac">
                        <td>Học kỳ 3 (2023-2024)</td>
                        <td class="score-text">97,00</td>
                        <td><span class="score-pill badge rounded-pill pill-excellent">Xuất sắc</span></td>
                        <td>Khen thưởng HK</td>
                    </tr>
                    <tr class="summary-row" data-summary-row>
                        <td>ĐIỂM TRUNG BÌNH:</td>
                        <td class="score-text">88,67</td>
                        <td><span class="score-pill badge rounded-pill pill-good">Tốt</span></td>
                        <td></td>
                    </tr>
                </tbody>
            </table></div>
        </div>

        <div class="year-block" data-nien-khoa="2022-2023">
            <div class="year-header">Năm học 2022 - 2023</div>
            <div class="table-responsive"><table class="result-table table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Học kỳ</th>
                        <th>Điểm</th>
                        <th>Xếp loại</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-result-row data-hoc-ky="1" data-xep-loai="kha">
                        <td>Học kỳ 1 (2022-2023)</td>
                        <td class="score-text">76,00</td>
                        <td><span class="score-pill badge rounded-pill pill-fair">Khá</span></td>
                        <td class="note-muted">Không có ghi chú</td>
                    </tr>
                    <tr data-result-row data-hoc-ky="2" data-xep-loai="kha">
                        <td>Học kỳ 2 (2022-2023)</td>
                        <td class="score-text">78,00</td>
                        <td><span class="score-pill badge rounded-pill pill-fair">Khá</span></td>
                        <td class="note-muted">Không có ghi chú</td>
                    </tr>
                    <tr data-result-row data-hoc-ky="3" data-xep-loai="tot">
                        <td>Học kỳ 3 (2022-2023)</td>
                        <td class="score-text">83,00</td>
                        <td><span class="score-pill badge rounded-pill pill-good">Tốt</span></td>
                        <td class="note-muted">Không có ghi chú</td>
                    </tr>
                    <tr class="summary-row" data-summary-row>
                        <td>ĐIỂM TRUNG BÌNH:</td>
                        <td class="score-text">79,00</td>
                        <td><span class="score-pill badge rounded-pill pill-fair">Khá</span></td>
                        <td></td>
                    </tr>
                </tbody>
            </table></div>
        </div>
    </div>
        </div>
    </div>
</div>

<script>
    function openResultFilter() {
        var modal = document.querySelector('.result-filter-wrap #resultFilterModal');
        if (!modal) return;
        var isOpen = modal.classList.contains('open');
        modal.classList.toggle('open', !isOpen);
        modal.setAttribute('aria-hidden', isOpen ? 'true' : 'false');
        var toggle = document.getElementById('resultFilterToggle');
        if (toggle) {
            toggle.classList.toggle('active', !isOpen);
            toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        }
    }

    function closeResultFilter() {
        var modal = document.querySelector('.result-filter-wrap #resultFilterModal');
        if (!modal) return;
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        var toggle = document.getElementById('resultFilterToggle');
        if (toggle) {
            toggle.classList.remove('active');
            toggle.setAttribute('aria-expanded', 'false');
        }
    }

    document.addEventListener('click', function(event) {
        var filterWrap = document.querySelector('.result-filter-wrap');
        if (filterWrap && !filterWrap.contains(event.target)) {
            closeResultFilter();
        }
    });

    (function applyResultFilters() {
        var params = new URLSearchParams(window.location.search);
        var nienKhoa = params.get('nien_khoa') || '';
        var hocKy = params.get('hoc_ky') || '';
        var xepLoai = params.get('xep_loai') || '';

        if (!nienKhoa && !hocKy && !xepLoai) return;

        document.querySelectorAll('.year-block').forEach(function(block) {
            var blockYear = block.getAttribute('data-nien-khoa') || '';
            var yearMatches = !nienKhoa || blockYear === nienKhoa;
            var visibleRows = 0;

            block.querySelectorAll('[data-result-row]').forEach(function(row) {
                var rowMatches = yearMatches &&
                    (!hocKy || row.getAttribute('data-hoc-ky') === hocKy) &&
                    (!xepLoai || row.getAttribute('data-xep-loai') === xepLoai);

                row.style.display = rowMatches ? '' : 'none';
                if (rowMatches) visibleRows += 1;
            });

            block.querySelectorAll('[data-summary-row]').forEach(function(row) {
                row.style.display = yearMatches && visibleRows > 0 && !hocKy && !xepLoai ? '' : 'none';
            });

            block.style.display = yearMatches && visibleRows > 0 ? '' : 'none';
        });
    })();
</script>
