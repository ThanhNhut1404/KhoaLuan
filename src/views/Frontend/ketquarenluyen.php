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
        font-weight: 700;
        color: #1d4ed8;
        margin: 0;
    }

    .filter-card,
    .table-card,
    .summary-card,
    .note-card {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #e8ecf3;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.06);
    }

    .filter-card {
        padding: 16px;
        display: grid;
        gap: 12px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
    }

    .filter-field label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        display: block;
        margin-bottom: 6px;
    }

    .filter-field select {
        width: 100%;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #d7deea;
        background: #f8fafc;
        font-size: 13px;
        color: #1f2937;
        outline: none;
    }

    .filter-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
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
        background: #1d4ed8;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(29, 78, 216, 0.2);
    }

    .btn-primary:hover {
        background: #1746c6;
    }

    .btn-light {
        background: #f8fafc;
        color: #1f2937;
        border: 1px solid #d7deea;
    }

    .table-card {
        overflow: hidden;
    }

    .table-card-header {
        padding: 12px 16px;
        border-bottom: 1px solid #e8ecf3;
        background: #f8fafc;
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
    }

    .result-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .result-table th,
    .result-table td {
        padding: 10px 12px;
        text-align: center;
        border-bottom: 1px solid #eef2f7;
    }

    .result-table th {
        background: #f4f6fb;
        color: #475569;
        font-weight: 600;
        font-size: 12px;
    }

    .result-table td {
        color: #1f2937;
    }

    .result-table tr:last-child td {
        border-bottom: none;
    }

    .score-good {
        color: #10b981;
        font-weight: 700;
    }

    .score-warn {
        color: #f59e0b;
        font-weight: 700;
    }

    .score-best {
        color: #2563eb;
        font-weight: 700;
    }

    .score-bad {
        color: #ef4444;
        font-weight: 700;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }

    .summary-card {
        padding: 14px;
        display: grid;
        gap: 6px;
        text-align: center;
    }

    .summary-title {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
    }

    .summary-value {
        font-size: 22px;
        font-weight: 800;
        color: #1d4ed8;
    }

    .summary-value.success {
        color: #10b981;
    }

    .summary-value.danger {
        color: #ef4444;
    }

    .note-card {
        padding: 12px 16px;
        display: grid;
        gap: 6px;
        background: #f8fafc;
    }

    .note-title {
        font-weight: 700;
        color: #1d4ed8;
        font-size: 13px;
    }

    .note-text {
        font-size: 12px;
        color: #475569;
        line-height: 1.5;
    }

    .note-text ul {
        padding-left: 18px;
        margin: 0;
        display: grid;
        gap: 4px;
    }

    @media (max-width: 768px) {
        .filter-actions {
            justify-content: stretch;
        }

        .btn-primary,
        .btn-light {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="result-page">
    <div class="result-header">
        <h1 class="result-title">Kết quả rèn luyện</h1>
    </div>

    <div class="filter-card">
        <div class="filter-grid">
            <div class="filter-field">
                <label for="namHoc">Năm học</label>
                <select id="namHoc" name="namHoc">
                    <option selected><?= $filters['nam_hoc']; ?></option>
                    <option>2022 - 2023</option>
                    <option>2021 - 2022</option>
                </select>
            </div>
            <div class="filter-field">
                <label for="hocKy">Học kỳ</label>
                <select id="hocKy" name="hocKy">
                    <option selected><?= $filters['hoc_ky']; ?></option>
                    <option>Học kỳ 1</option>
                    <option>Học kỳ 2</option>
                </select>
            </div>
            <div class="filter-field">
                <label for="lop">Lớp</label>
                <select id="lop" name="lop">
                    <option selected><?= $filters['lop']; ?></option>
                    <option>DHCNTT17A</option>
                    <option>DHCNTT17B</option>
                </select>
            </div>
        </div>
        <div class="filter-actions">
            <button class="btn-primary" type="button">
                <i class="fa-solid fa-magnifying-glass"></i>
                Lọc kết quả
            </button>
            <button class="btn-light" type="button">
                <i class="fa-solid fa-file-export"></i>
                Xuất Excel
            </button>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card-header">Bảng kết quả rèn luyện</div>
        <div class="table-card-body">
            <table class="result-table">
                <thead>
                    <tr>
                        <th>Học kỳ</th>
                        <th>Năm học</th>
                        <th>Điểm rèn luyện (/100)</th>
                        <th>Xếp loại</th>
                        <th>Ghi chú / Điểm trừ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Học kỳ 1</td>
                        <td>2021 - 2022</td>
                        <td class="score-warn">78</td>
                        <td class="score-warn">Khá</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Học kỳ 2</td>
                        <td>2021 - 2022</td>
                        <td class="score-warn">82</td>
                        <td class="score-warn">Khá</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Học kỳ 1</td>
                        <td>2022 - 2023</td>
                        <td class="score-good">88</td>
                        <td class="score-good">Tốt</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Học kỳ 2</td>
                        <td>2022 - 2023</td>
                        <td class="score-good">85</td>
                        <td class="score-good">Tốt</td>
                        <td class="score-bad">Điểm trừ: -5 (Đi muộn 2 lần)</td>
                    </tr>
                    <tr>
                        <td>Học kỳ 1</td>
                        <td>2023 - 2024</td>
                        <td class="score-best">90</td>
                        <td class="score-best">Xuất sắc</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Học kỳ 2</td>
                        <td>2023 - 2024</td>
                        <td class="score-good">85</td>
                        <td class="score-good">Tốt</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-title">Điểm rèn luyện trung bình</div>
            <div class="summary-value">84.7</div>
            <div class="summary-title">/100</div>
        </div>
        <div class="summary-card">
            <div class="summary-title">Xếp loại trung bình</div>
            <div class="summary-value success">Tốt</div>
        </div>
        <div class="summary-card">
            <div class="summary-title">Tổng điểm trừ</div>
            <div class="summary-value danger">-5</div>
        </div>
        <div class="note-card">
            <div class="note-title">
                <i class="fa-solid fa-circle-info"></i>
                Điểm rèn luyện được tính dựa trên các hoạt động đã tham gia và quy chế rèn luyện hiện hành.
            </div>
        </div>
    </div>

    <div class="note-card">
        <div class="note-title">Ghi chú:</div>
        <div class="note-text">
            <ul>
                <li>Thang điểm rèn luyện: 0 - 100 điểm.</li>
                <li>Xếp loại: Xuất sắc (≥90), Tốt (80 - 89), Khá (65 - 79), Trung bình (50 - 64), Yếu (<50).</li>
                <li>Điểm trừ được áp dụng theo quy định của nhà trường.</li>
            </ul>
        </div>
    </div>
</div>
