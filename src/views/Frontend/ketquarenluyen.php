<?php
    $filters = [
        'nam_hoc' => '2023 - 2024',
        'hoc_ky' => 'Tất cả',
        'lop' => 'Tất cả'
    ];
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
    }

    .result-panel__body {
        padding: 12px 14px;
    }


    .result-page-title {
        font-size: 18px;
        font-weight: 800;
        color: #1d4ed8;
        margin: 0;
        letter-spacing: 0.6px;
        text-transform: none;
    }

    .result-subtitle {
        font-size: 12px;
        color: #6b7280;
        margin: 4px 0 0 0;
    }

    .btn-primary,
    .btn-light {
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

    .btn-primary {
        background: #ffffff;
        color: #1f2937;
        border: 1px solid #e2e8f0;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
    }

    .btn-primary:hover {
        border-color: #cbd5f5;
        transform: translateY(-1px);
    }

    .result-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
    }

    .result-card,
    .result-table-card,
    .result-filter {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e8ecf3;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
    }

    .result-card {
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 8px;
        min-height: 96px;
    }

    .result-card .score-row {
        display: flex;
        align-items: baseline;
        gap: 6px;
        flex: 1;
        align-items: center;
    }

    .result-card .label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: none;
        letter-spacing: 0.3px;
    }

    .result-card .value {
        font-size: 40px;
        font-weight: 900;
        color: #1d4ed8;
        line-height: 1;
        letter-spacing: -1px;
    }

    .result-card .sub {
        font-size: 16px;
        font-weight: 600;
        color: #94a3b8;
        align-self: flex-end;
        padding-bottom: 4px;
    }

    .result-card .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 22px;
        font-weight: 900;
        color: #047857;
        background: #ecfdf3;
        border: 2px solid #6ee7b7;
        border-radius: 12px;
        padding: 10px 18px;
        width: fit-content;
        letter-spacing: 0.5px;
    }

    .result-filter {
        padding: 14px 16px;
        display: grid;
        gap: 10px;
        min-height: 96px;
    }

    .result-filter .label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: none;
        letter-spacing: 0.3px;
    }

    .result-filter select {
        border: 1px solid #e2e8f0;
        padding: 8px 10px;
        border-radius: 8px;
        font-size: 13px;
        background: #ffffff;
        outline: none;
        color: #1f2937;
        max-width: 250px;
        width: 100%;
    }

    .result-filter-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .result-filter-controls {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .result-filter-btn {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        background: #ffffff;
        color: #1d4ed8;
    }

    .result-filter-btn.primary {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #ffffff;
    }

    .result-table-card {
        overflow: hidden;
    }

    .year-block {
        border-top: 1px solid #eef2f7;
    }

    .year-header {
        padding: 12px 16px;
        font-weight: 700;
        color: #1d4ed8;
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
        border: 1px solid #e8ecf3;
    }

    .result-table th,
    .result-table td {
        padding: 10px 12px;
        text-align: left;
        border-bottom: 1px solid #eef2f7;
        border-right: 1px solid #eef2f7;
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

    .result-table th:last-child,
    .result-table td:last-child {
        border-right: none;
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
        color: #1d4ed8;
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

    @media (max-width: 768px) {
        .result-table th,
        .result-table td {
            padding: 10px 8px;
        }
    }
</style>

<div class="result-page">
    <div class="result-panel">
        <div class="result-panel__header">
            <h1 class="result-page-title">Kết quả rèn luyện</h1>
        </div>
        <div class="result-panel__body">
            <div class="result-cards">
                <div class="result-card">
                    <div class="label">Điểm học kỳ này</div>
                    <div class="score-row">
                        <div class="value">84.50</div>
                        <div class="sub">/100</div>
                    </div>
                </div>
                <div class="result-card">
                    <div class="label">Xếp loại hiện tại</div>
                    <div class="badge">Tốt</div>
                </div>
                <div class="result-filter">
                    <div class="label">Học kỳ</div>
                    <div class="result-filter-controls">
                        <select>
                            <option>Tất cả</option>
                            <option>Học kỳ 1</option>
                            <option>Học kỳ 2</option>
                            <option>Học kỳ 3</option>
                        </select>
                        <div class="result-filter-actions">
                            <button class="result-filter-btn" type="button">Đặt lại</button>
                            <button class="result-filter-btn primary" type="button">Lọc</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="result-table-card">
        <div class="year-block">
            <div class="year-header">Năm học 2023 - 2024</div>
            <table class="result-table">
                <thead>
                    <tr>
                        <th>Học kỳ</th>
                        <th>Điểm</th>
                        <th>Xếp loại</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Học kỳ 3 (2023-2024)</td>
                        <td class="score-text">97,00</td>
                        <td><span class="score-pill pill-excellent">Xuất sắc</span></td>
                        <td>Khen thưởng HK</td>
                    </tr>
                    <tr>
                        <td>Học kỳ 2 (2023-2024)</td>
                        <td class="score-text">87,00</td>
                        <td><span class="score-pill pill-good">Tốt</span></td>
                        <td class="note-muted">Không có ghi chú</td>
                    </tr>
                    <tr>
                        <td>Học kỳ 1 (2023-2024)</td>
                        <td class="score-text">82,00</td>
                        <td><span class="score-pill pill-good">Tốt</span></td>
                        <td class="note-muted">Không có ghi chú</td>
                    </tr>
                    <tr class="summary-row">
                        <td>ĐIỂM TRUNG BÌNH:</td>
                        <td class="score-text">88,67</td>
                        <td><span class="score-pill pill-good">Tốt</span></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="year-block">
            <div class="year-header">Năm học 2022 - 2023</div>
            <table class="result-table">
                <thead>
                    <tr>
                        <th>Học kỳ</th>
                        <th>Điểm</th>
                        <th>Xếp loại</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Học kỳ 3 (2022-2023)</td>
                        <td class="score-text">83,00</td>
                        <td><span class="score-pill pill-good">Tốt</span></td>
                        <td class="note-muted">Không có ghi chú</td>
                    </tr>
                    <tr>
                        <td>Học kỳ 2 (2022-2023)</td>
                        <td class="score-text">78,00</td>
                        <td><span class="score-pill pill-fair">Khá</span></td>
                        <td class="note-muted">Không có ghi chú</td>
                    </tr>
                    <tr>
                        <td>Học kỳ 1 (2022-2023)</td>
                        <td class="score-text">76,00</td>
                        <td><span class="score-pill pill-fair">Khá</span></td>
                        <td class="note-muted">Không có ghi chú</td>
                    </tr>
                    <tr class="summary-row">
                        <td>ĐIỂM TRUNG BÌNH:</td>
                        <td class="score-text">79,00</td>
                        <td><span class="score-pill pill-fair">Khá</span></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
