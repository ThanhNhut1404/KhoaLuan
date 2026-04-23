<style>
    .dashboard-header {
        margin-bottom: 30px;
    }

    .dashboard-header h1 {
        color: #1d4ed8;
        font-size: 28px;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .welcome-message {
        background: linear-gradient(135deg, #1d4ed8 0%, #1047a1 100%);
        color: white;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(29, 78, 216, 0.15);
    }

    .welcome-message h2 {
        margin: 0 0 10px 0;
        font-size: 22px;
    }

    .welcome-message p {
        margin: 0;
        font-size: 14px;
        opacity: 0.95;
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
        border-left: 4px solid #00a8e8;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(29, 78, 216, 0.12);
    }

    .stat-icon {
        font-size: 32px;
        margin-bottom: 12px;
        color: #00a8e8;
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

    .chart-placeholder i {
        font-size: 48px;
        opacity: 0.3;
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

        .dashboard-header h1 {
            font-size: 22px;
        }

        .table-responsive {
            font-size: 13px;
        }

        th, td {
            padding: 10px 8px;
        }
    }
</style>

<div class="dashboard-header">
    <h1><i class="fas fa-graduation-cap"></i> Bảng Điểm Rèn Luyện</h1>
</div>

<div class="welcome-message">
    <h2 style="margin: 0 0 10px 0; font-size: 22px;">
        <i class="fas fa-hand-spock"></i> Xin chào, Nguyễn Văn A!
    </h2>
    <p style="margin: 0; font-size: 14px; opacity: 0.95;">
        Đây là thông tin chi tiết về điểm rèn luyện của bạn trong học kỳ này.
    </p>
</div>

<!-- THÔNG TIN CÁ NHÂN -->
<div class="info-grid">
    <div class="info-item">
        <div class="info-label"><i class="fas fa-id-card"></i> Mã Sinh Viên</div>
        <div class="info-value">20210001</div>
    </div>
    <div class="info-item">
        <div class="info-label"><i class="fas fa-book"></i> Lớp</div>
        <div class="info-value">CT101</div>
    </div>
    <div class="info-item">
        <div class="info-label"><i class="fas fa-calendar"></i> Học Kỳ</div>
        <div class="info-value">I - 2024-2025</div>
    </div>
    <div class="info-item">
        <div class="info-label"><i class="fas fa-building"></i> Khoa</div>
        <div class="info-value">Công Nghệ Thông Tin</div>
    </div>
</div>

<!-- THỐNG KÊ -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-star"></i></div>
        <div class="stat-label">Điểm Tổng Hợp</div>
        <div class="stat-value">85.5</div>
        <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">Trên 100</p>
    </div>

    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-label">Số Hoạt Động</div>
        <div class="stat-value">12</div>
        <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">Hoạt động</p>
    </div>

    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-trophy"></i></div>
        <div class="stat-label">Xếp Hạng</div>
        <div class="stat-value">Giỏi</div>
        <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">Loại điểm</p>
    </div>

    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div class="stat-label">Cập Nhật Lần Cuối</div>
        <div class="stat-value">Hôm nay</div>
        <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">21:30</p>
    </div>
</div>

<!-- BẢNG ĐIỂM CHI TIẾT -->
<div class="scores-section">
    <div class="section-title">
        <i class="fas fa-list-check"></i> Chi Tiết Điểm Rèn Luyện
    </div>

    <div class="btn-group">
        <button class="btn btn-primary"><i class="fas fa-download"></i> Tải Xuống</button>
        <button class="btn btn-secondary"><i class="fas fa-print"></i> In</button>
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
</div>

<!-- BIỂU ĐỒ -->
<div class="scores-section">
    <div class="section-title">
        <i class="fas fa-chart-pie"></i> Thống Kê Điểm
    </div>
    <div class="chart-placeholder">
        <i class="fas fa-chart-bar" style="font-size: 48px; opacity: 0.3;"></i>
        <p style="margin-top: 15px;">Biểu đồ thống kê sẽ được hiển thị tại đây<br><small>(Cần thêm thư viện Chart.js)</small></p>
    </div>
</div>
