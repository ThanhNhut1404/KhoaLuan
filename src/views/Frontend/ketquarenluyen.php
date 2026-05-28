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

    .result-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .result-title {
        font-size: 20px;
        font-weight: 800;
        color: #1d4ed8;
        margin: 0;
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
    .result-note,
    .result-table-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e8ecf3;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
    }

    .result-card {
        padding: 14px 16px;
        display: grid;
        gap: 6px;
        min-height: 96px;
    }

    .result-card .label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .result-card .value {
        font-size: 22px;
        font-weight: 800;
        color: #1d4ed8;
    }

    .result-card .sub {
        font-size: 12px;
        color: #94a3b8;
    }

    .result-card .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        background: #ecfdf3;
        border-radius: 999px;
        padding: 4px 10px;
    }

    .result-note {
        padding: 12px 16px;
        display: grid;
        gap: 6px;
        background: #f8fafc;
    }

    .result-note .label {
        font-weight: 700;
        color: #1d4ed8;
        font-size: 12px;
    }

    .result-note input {
        border: 1px solid #e2e8f0;
        padding: 8px 10px;
        border-radius: 8px;
        font-size: 13px;
        background: #ffffff;
        outline: none;
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
        color: #1f2937;
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

    .result-table th {
        background: #f4f6fb;
        color: #475569;
        font-weight: 600;
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

    .note-muted {
        color: #94a3b8;
        font-size: 12px;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .result-header {
            align-items: flex-start;
        }

        .result-table th,
        .result-table td {
            padding: 10px 8px;
        }
    }
</style>

<div class="result-page">
    <div class="result-header">
        <div>
            <h1 class="result-title">Kết quả rèn luyện</h1>
            <div class="result-subtitle">Tổng hợp điểm rèn luyện theo từng học kỳ</div>
        </div>
        <button class="btn-primary" type="button">
            <i class="fa-solid fa-file-pdf"></i>
            In bảng điểm
        </button>
    </div>

    <div class="result-cards">
        <div class="result-card">
            <div class="label">Điểm học kỳ này</div>
            <div class="value">84.50</div>
            <div class="sub">/100</div>
        </div>
        <div class="result-card">
            <div class="label">Xếp loại hiện tại</div>
            <div class="badge">Tốt</div>
        </div>
        <div class="result-note">
            <div class="label">Ghi chú</div>
            <input type="text" placeholder="Nhập ghi chú" />
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
                </tbody>
            </table>
        </div>
    </div>
</div>
