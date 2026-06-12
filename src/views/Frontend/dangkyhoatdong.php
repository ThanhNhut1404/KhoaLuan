<?php
    $student = $student ?? [];
?>

<style>
    .activity-page {
        display: grid;
        gap: 16px;
    }

    .activity-page-title {
        font-size: 18px;
        font-weight: 800;
        color: #1d4ed8;
        text-transform: none;
        letter-spacing: 0.6px;
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
    }

    .activity-panel__body {
        padding: 12px;
    }

    .activity-toolbar {
        display: grid;
        gap: 12px;
    }

    .activity-filters {
        display: grid;
        grid-template-columns: minmax(220px, 1.4fr) repeat(5, minmax(140px, 1fr)) auto;
        gap: 12px;
        align-items: end;
        background: transparent;
        border: none;
        border-radius: 0;
        padding: 0 8px 0 0;
        box-shadow: none;
    }

    .filter-field {
        display: grid;
        gap: 6px;
        font-size: 12px;
        color: #1d4ed8;
        font-weight: 600;
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

    .filter-select {
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
        align-items: center;
        justify-self: end;
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
    }

    .filter-btn.primary {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #fff;
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
    }

    .meta-label {
        color: #6b7280;
        font-weight: 800;
    }

    /* activity-detail styles moved to activity_detail_modal.php */

    .activity-detail-close:hover { background: #eef2ff; }

    .activity-detail-body {
        padding: 16px;
        overflow-y: auto;
        display: grid;
        gap: 16px;
    }

    .activity-detail-top {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 16px;
        align-items: start;
    }

    .activity-detail-image {
        width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e8ecf3;
        background: #f8fafc;
    }

    .activity-detail-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .activity-detail-summary {
        display: grid;
        gap: 12px;
        align-content: start;
    }

    .activity-detail-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .activity-detail-summary-item {
        border: 1px solid #e8ecf3;
        background: #f8fafc;
        border-radius: 12px;
        padding: 10px 12px;
        display: grid;
        gap: 4px;
        min-height: 58px;
    }

    .activity-detail-summary-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 800;
        color: #1d4ed8;
    }

    .activity-detail-summary-value {
        font-size: 13px;
        color: #0f172a;
        font-weight: 700;
        line-height: 1.35;
    }

    .activity-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .detail-box {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 14px;
        padding: 12px 13px;
        display: grid;
        gap: 6px;
        box-shadow: 0 1px 0 rgba(15, 23, 42, 0.02);
    }

    .detail-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 800;
        color: #1d4ed8;
    }

    .detail-label::before {
        content: "";
        display: inline-block;
        width: 14px;
        height: 14px;
        margin-right: 8px;
        vertical-align: middle;
        background-image: url("data:image/svg+xml;utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Ccircle cx='12' cy='12' r='6' fill='%231d4ed8'/%3E%3C/svg%3E");
        background-size: 100% 100%;
        background-repeat: no-repeat;
        opacity: 0.95;
    }

    .stat-box {
        background: linear-gradient(90deg, #fff7ed 0%, #fffaf0 100%);
        border-color: #fcd34d;
        box-shadow: 0 1px 0 rgba(245, 158, 11, 0.03);
    }

    .stat-box .detail-label {
        color: #b45309;
    }

    .stat-box .detail-value {
        color: #92400e;
        font-weight: 800;
    }

    .detail-value {
        font-size: 13px;
        color: #1f2937;
        font-weight: 600;
        line-height: 1.55;
        white-space: pre-line;
    }

    .detail-box.full-width {
        grid-column: 1 / -1;
    }

    .detail-contact {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .detail-contact-item {
        padding: 10px 12px;
        background: #f8fafc;
        border: 1px solid #e8ecf3;
        border-radius: 12px;
        display: grid;
        gap: 4px;
    }

    .detail-contact-item .detail-label {
        margin-bottom: 0;
    }

    .activity-detail-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 0 18px 18px;
    }

    .detail-action {
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #0f172a;
        padding: 10px 14px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .detail-action.primary {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
    }

    .activity-pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 6px;
    }

    .page-btn {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #fff;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .page-btn.active {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #fff;
    }

    @media (max-width: 1100px) {
        .activity-filters {
            grid-template-columns: 1fr 1fr;
        }

        .filter-actions {
            justify-content: flex-end;
        }
    }

    @media (max-width: 640px) {
        .activity-filters {
            grid-template-columns: 1fr;
        }

        .activity-sort {
            width: 100%;
            justify-content: flex-start;
        }
    }

    /* Activity detail modal styles (kept here to preserve original page layout) */
    .activity-detail-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1500;
        padding: 20px;
    }

    .activity-detail-overlay.active {
        display: flex;
    }

    .activity-detail-card {
        width: min(780px, 100%);
        max-height: 80vh;
        overflow: hidden;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);
        border: 1px solid #e8ecf3;
        display: grid;
        grid-template-rows: auto 1fr auto;
    }

    .activity-detail-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px;
        border-bottom: 1px solid #eef2ff;
        background: #f8faff;
    }

    .activity-detail-header .modal-title {
        font-size: 16px;
        font-weight: 700;
        color: #1d4ed8;
    }

    .activity-detail-close {
        position: static;
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 8px;
        background: transparent;
        color: #1f2937;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
    }

    .activity-detail-close:hover { background: #eef2ff; }

    .activity-detail-body {
        padding: 16px;
        overflow-y: auto;
        display: grid;
        gap: 16px;
    }

    .activity-detail-top {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 16px;
        align-items: start;
    }

    .activity-detail-image {
        width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e8ecf3;
        background: #f8fafc;
    }

    .activity-detail-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .activity-detail-summary {
        display: grid;
        gap: 12px;
        align-content: start;
    }

    .activity-detail-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .activity-detail-summary-item {
        border: 1px solid #e8ecf3;
        background: #f8fafc;
        border-radius: 12px;
        padding: 10px 12px;
        display: grid;
        gap: 4px;
        min-height: 58px;
    }

    .activity-detail-summary-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 800;
        color: #1d4ed8;
    }

    .activity-detail-summary-value {
        font-size: 13px;
        color: #0f172a;
        font-weight: 700;
        line-height: 1.35;
    }

    .activity-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .detail-box {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 14px;
        padding: 12px 13px;
        display: grid;
        gap: 6px;
        box-shadow: 0 1px 0 rgba(15, 23, 42, 0.02);
    }

    .detail-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 800;
        color: #1d4ed8;
    }

    .detail-label::before {
        content: "🔹";
        display: inline-block;
        margin-right: 8px;
        font-size: 12px;
        vertical-align: middle;
        opacity: 0.95;
    }

    .detail-value {
        font-size: 13px;
        color: #1f2937;
        font-weight: 600;
        line-height: 1.55;
        white-space: pre-line;
    }

    .detail-box.full-width {
        grid-column: 1 / -1;
    }

    .detail-contact {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .detail-contact-item {
        padding: 10px 12px;
        background: #f8fafc;
        border: 1px solid #e8ecf3;
        border-radius: 12px;
        display: grid;
        gap: 4px;
    }

    .detail-contact-item .detail-label {
        margin-bottom: 0;
    }

    .activity-detail-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 0 18px 18px;
    }

    .detail-action {
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #0f172a;
        padding: 10px 14px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .detail-action.primary {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
    }

    /* icon uses SVG data URI */

    .stat-box {
        background: linear-gradient(90deg, #fff7ed 0%, #fffaf0 100%);
        border-color: #fcd34d;
        box-shadow: 0 1px 0 rgba(245, 158, 11, 0.03);
    }

    .stat-box .detail-label {
        color: #b45309;
    }

    .stat-box .detail-value {
        color: #92400e;
        font-weight: 800;
    }

    /* Card visual improvements */
    .activity-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 18px;
    }

    .activity-card {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e8ecf3;
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
        display: flex;
        flex-direction: column;
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .activity-card:hover { transform: translateY(-6px); box-shadow: 0 14px 32px rgba(15,23,42,0.08); }

    .activity-cover { height: 140px; background: #f3f4f6; position: relative; }
    .activity-cover img { width:100%; height:100%; object-fit:cover; display:block; }

    .activity-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 2;
        padding: 5px 10px;
        border-radius: 999px;
        background: rgba(255,255,255,0.92);
        border: 1px solid #34d399;
        color: #059669;
        font-size: 11px;
        font-weight: 800;
        backdrop-filter: blur(6px);
    }

    .activity-body { padding: 14px; display:flex; flex-direction:column; gap:10px; }
    .activity-title { font-size:16px; font-weight:800; color:#0f172a; }
    .activity-meta { font-size:13px; color:#475569; display:flex; flex-direction:column; gap:6px; }
    .activity-meta span { display:flex; gap:6px; align-items:center; flex-wrap:wrap; }

    .activity-score { display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:8px; }
    .activity-score > div {
        background:#f8fafc;
        border:1px solid #e8ecf3;
        border-radius:12px;
        padding:8px 10px;
        font-size:13px;
        font-weight:800;
        color:#0f172a;
    }

    .activity-footer { margin-top:auto; display:flex; justify-content:space-between; align-items:center; gap:8px; }
    .activity-btn { padding:8px 12px; border-radius:10px; background:#16a34a; color:#fff; border:none; cursor:pointer; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:8px; }

    .activity-tag {
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:6px 10px;
        border-radius:999px;
        border:1px solid currentColor;
        background:#fff;
        font-size:12px;
        font-weight:800;
    }

    .activity-tag--tinhnguyen { color:#16a34a; border-color:#86efac; }
    .activity-tag--hocthuat { color:#2563eb; border-color:#93c5fd; }
    .activity-tag--thethao { color:#f97316; border-color:#fdba74; }
    .activity-tag--vanhoa { color:#db2777; border-color:#f9a8d4; }
    .activity-tag--kynang { color:#7c3aed; border-color:#c4b5fd; }
    .activity-tag--khac { color:#0f766e; border-color:#5eead4; }

</style>

<div class="activity-page">
    <div class="activity-toolbar">
        <div class="activity-panel">
            <div class="activity-panel__header">
                <div class="activity-page-title">Đăng ký hoạt động</div>
            </div>
            <div class="activity-panel__body">
                <div class="activity-filters">
                    <div class="filter-field">
                        <span>Tìm kiếm hoạt động</span>
                        <div class="filter-input">
                            <input type="text" placeholder="Tìm kiếm hoạt động..." />
                        </div>
                    </div>
                    <div class="filter-field">
                        <span>Đơn vị tổ chức</span>
                        <div class="filter-input">
                            <input type="text" placeholder="Nhập đơn vị tổ chức..." />
                        </div>
                    </div>
                    <div class="filter-field">
                        <span>Loại hoạt động</span>
                        <div class="filter-select">Tất cả <span>v</span></div>
                    </div>
                    <div class="filter-field">
                        <span>Học kỳ</span>
                        <div class="filter-select">Học kỳ 2 (2024 - 2025) <span>v</span></div>
                    </div>
                    <div class="filter-field">
                        <span>Trạng thái</span>
                        <div class="filter-select">Đang mở <span>v</span></div>
                    </div>
                    <div class="filter-actions">
                        <button class="filter-btn" type="button">Đặt lại</button>
                        <button class="filter-btn primary" type="button">Lọc & Tìm kiếm</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="activity-tabs">
            <div class="activity-tab active">Tất cả</div>
            <div class="activity-tab">Học tập</div>
            <div class="activity-tab">Đạo đức</div>
            <div class="activity-tab">Thể lực</div>
            <div class="activity-tab">Tình nguyện</div>
            <div class="activity-tab">Hội nhập</div>
            <div class="activity-tab">Khác</div>
            <div class="activity-sort">
                <div class="sort-select">Mới nhất</div>
            </div>
        </div>
    </div>

    <div class="activity-grid">
        <article class="activity-card" tabindex="0"
            data-title="Chiến dịch Mùa hè xanh 2024"
            data-unit="Đoàn trường Đại học ABC"
            data-time="20/06/2024 - 25/06/2024"
            data-location="TP. Hồ Chí Minh"
            data-point="10 điểm"
            data-remaining="45 / 100"
            data-tag="Tình nguyện"
            data-benefits="Được cộng 10 điểm rèn luyện; được ghi nhận tham gia chiến dịch tình nguyện cấp trường."
            data-clothing="Áo xanh thanh niên, quần dài, giày thể thao."
            data-audience="Sinh viên toàn trường"
            data-content="Tham gia các hoạt động hỗ trợ cộng đồng, dọn dẹp cảnh quan, tổ chức hoạt động thiếu nhi và tuyên truyền bảo vệ môi trường."
            data-contact-name="Nguyễn Văn A"
            data-contact-phone="0901 234 567"
            data-image="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Chiến dịch Mùa hè xanh 2024</div>
                <div class="activity-meta">
                    <span><span class="meta-label">Đơn vị:</span> Đoàn trường Đại học ABC</span>
                    <span><span class="meta-label">Thời gian:</span> 20/06/2024 - 25/06/2024</span>
                    <span><span class="meta-label">Địa điểm:</span> TP. Hồ Chí Minh</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label">Điểm cộng:</span><br><strong>10 điểm</strong></div>
                    <div><span class="meta-label">Còn lại:</span><br><strong>45 / 100</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag activity-tag--tinhnguyen">Tình nguyện</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card" tabindex="0"
            data-title="Hội thảo: AI và tương lai nghề nghiệp"
            data-unit="Khoa Công nghệ thông tin"
            data-time="15/05/2024 - 15/05/2024"
            data-location="Hội trường B, Cơ sở 1"
            data-point="8 điểm"
            data-remaining="120 / 150"
            data-tag="Học thuật"
            data-benefits="Cập nhật kiến thức về trí tuệ nhân tạo, nhận điểm rèn luyện và giao lưu với doanh nghiệp."
            data-clothing="Lịch sự, áo sơ mi hoặc đồng phục sinh viên."
            data-audience="Sinh viên năm 2 trở lên, ưu tiên ngành CNTT"
            data-content="Chuyên gia chia sẻ xu hướng AI, kỹ năng chuẩn bị hồ sơ nghề nghiệp và định hướng việc làm."
            data-contact-name="Trần Thị B"
            data-contact-phone="0902 111 222"
            data-image="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Hội thảo: AI và tương lai nghề nghiệp</div>
                <div class="activity-meta">
                    <span><span class="meta-label">Đơn vị:</span> Khoa Công nghệ thông tin</span>
                    <span><span class="meta-label">Thời gian:</span> 15/05/2024 - 15/05/2024</span>
                    <span><span class="meta-label">Địa điểm:</span> Hội trường B, Cơ sở 1</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label">Điểm cộng:</span><br><strong>8 điểm</strong></div>
                    <div><span class="meta-label">Còn lại:</span><br><strong>120 / 150</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag activity-tag--hocthuat">Học thuật</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card" tabindex="0"
            data-title="Giải bóng đá sinh viên mở rộng 2024"
            data-unit="Hội Sinh viên"
            data-time="10/05/2024 - 30/05/2024"
            data-location="Sân bóng đá trường ABC"
            data-point="6 điểm"
            data-remaining="8 / 16 đội"
            data-tag="Thể thao"
            data-benefits="Rèn luyện thể lực, tinh thần đồng đội, giao lưu giữa các khoa và được cộng điểm rèn luyện."
            data-clothing="Áo thể thao, quần short, giày đế mềm."
            data-audience="Sinh viên toàn trường có sức khỏe tốt"
            data-content="Thi đấu theo thể thức vòng bảng và loại trực tiếp; các đội đăng ký thi đấu theo lớp/khoa/câu lạc bộ."
            data-contact-name="Lê Văn C"
            data-contact-phone="0913 456 789"
            data-image="https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Giải bóng đá sinh viên mở rộng 2024</div>
                <div class="activity-meta">
                    <span><span class="meta-label">Đơn vị:</span> Hội Sinh viên</span>
                    <span><span class="meta-label">Thời gian:</span> 10/05/2024 - 30/05/2024</span>
                    <span><span class="meta-label">Địa điểm:</span> Sân bóng đá trường ABC</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label">Điểm cộng:</span><br><strong>6 điểm</strong></div>
                    <div><span class="meta-label">Còn lại:</span><br><strong>8 / 16 đội</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag activity-tag--thethao">Thể thao</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card" tabindex="0"
            data-title="Hiến máu nhân đạo đợt 1/2024"
            data-unit="Đoàn trường Đại học ABC"
            data-time="08/05/2024 - 08/05/2024"
            data-location="Giảng đường A, Cơ sở 1"
            data-point="7 điểm"
            data-remaining="60 / 100"
            data-tag="Tình nguyện"
            data-benefits="Được kiểm tra sức khỏe cơ bản, nhận giấy chứng nhận tham gia và cộng điểm rèn luyện."
            data-clothing="Trang phục gọn gàng, thoải mái, ưu tiên áo tay ngắn."
            data-audience="Sinh viên đủ điều kiện hiến máu theo quy định y tế"
            data-content="Đăng ký, khám sàng lọc, hiến máu và theo dõi sức khỏe sau hiến máu."
            data-contact-name="Phạm Thị D"
            data-contact-phone="0904 888 999"
            data-image="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Hiến máu nhân đạo đợt 1/2024</div>
                <div class="activity-meta">
                    <span><span class="meta-label">Đơn vị:</span> Đoàn trường Đại học ABC</span>
                    <span><span class="meta-label">Thời gian:</span> 08/05/2024 - 08/05/2024</span>
                    <span><span class="meta-label">Địa điểm:</span> Giảng đường A, Cơ sở 1</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label">Điểm cộng:</span><br><strong>7 điểm</strong></div>
                    <div><span class="meta-label">Còn lại:</span><br><strong>60 / 100</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag activity-tag--tinhnguyen">Tình nguyện</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card" tabindex="0"
            data-title="Đêm văn nghệ chào tân sinh viên K15"
            data-unit="Hội Sinh viên"
            data-time="25/05/2024 - 25/05/2024"
            data-location="Hội trường lớn"
            data-point="5 điểm"
            data-remaining="30 / 80"
            data-tag="Văn hóa - Văn nghệ"
            data-benefits="Giao lưu văn nghệ, phát triển kỹ năng biểu diễn và cộng điểm rèn luyện."
            data-clothing="Trang phục tự chọn phù hợp tiết mục; lịch sự, gọn gàng khi vào khán đài."
            data-audience="Sinh viên toàn trường"
            data-content="Các tiết mục hát, múa, kịch và trình diễn của các khoa, câu lạc bộ chào đón tân sinh viên."
            data-contact-name="Nguyễn Thị E"
            data-contact-phone="0905 123 456"
            data-image="https://images.unsplash.com/photo-1483412033650-1015ddeb83d1?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1483412033650-1015ddeb83d1?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Đêm văn nghệ chào tân sinh viên K15</div>
                <div class="activity-meta">
                    <span><span class="meta-label">Đơn vị:</span> Hội Sinh viên</span>
                    <span><span class="meta-label">Thời gian:</span> 25/05/2024 - 25/05/2024</span>
                    <span><span class="meta-label">Địa điểm:</span> Hội trường lớn</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label">Điểm cộng:</span><br><strong>5 điểm</strong></div>
                    <div><span class="meta-label">Còn lại:</span><br><strong>30 / 80</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag activity-tag--vanhoa">Văn hóa - Văn nghệ</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card" tabindex="0"
            data-title="Workshop: Kỹ năng thuyết trình hiệu quả"
            data-unit="Trung tâm Kỹ năng mềm"
            data-time="18/05/2024 - 18/05/2024"
            data-location="Phòng B.302"
            data-point="6 điểm"
            data-remaining="25 / 40"
            data-tag="Kỹ năng"
            data-benefits="Nâng cao kỹ năng thuyết trình, tự tin trước đám đông và được nhận tài liệu hướng dẫn."
            data-clothing="Trang phục lịch sự, thoải mái để tham gia thực hành."
            data-audience="Sinh viên có nhu cầu phát triển kỹ năng mềm"
            data-content="Học cách xây dựng slide, luyện giọng nói, ngôn ngữ cơ thể và thực hành thuyết trình."
            data-contact-name="Võ Văn F"
            data-contact-phone="0906 222 333"
            data-image="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Workshop: Kỹ năng thuyết trình hiệu quả</div>
                <div class="activity-meta">
                    <span><span class="meta-label">Đơn vị:</span> Trung tâm Kỹ năng mềm</span>
                    <span><span class="meta-label">Thời gian:</span> 18/05/2024 - 18/05/2024</span>
                    <span><span class="meta-label">Địa điểm:</span> Phòng B.302</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label">Điểm cộng:</span><br><strong>6 điểm</strong></div>
                    <div><span class="meta-label">Còn lại:</span><br><strong>25 / 40</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag activity-tag--kynang">Kỹ năng</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card" tabindex="0"
            data-title="Cuộc thi Nhiếp ảnh: Khoảnh khắc sinh viên"
            data-unit="Câu lạc bộ Nhiếp ảnh"
            data-time="01/05/2024 - 20/05/2024"
            data-location="Online"
            data-point="6 điểm"
            data-remaining="30 / 120"
            data-tag="Khác"
            data-benefits="Sân chơi sáng tạo, cơ hội trưng bày tác phẩm và nhận giải thưởng."
            data-clothing="Tự do, phù hợp khi tham gia chụp ảnh thực tế."
            data-audience="Sinh viên toàn trường yêu thích nhiếp ảnh"
            data-content="Gửi ảnh dự thi theo chủ đề, bình chọn online và triển lãm ảnh đẹp của sinh viên."
            data-contact-name="Hoàng Thị G"
            data-contact-phone="0907 444 555"
            data-image="https://images.unsplash.com/photo-1453928582365-b6ad33cbcf64?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1453928582365-b6ad33cbcf64?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Cuộc thi Nhiếp ảnh: Khoảnh khắc sinh viên</div>
                <div class="activity-meta">
                    <span><span class="meta-label">Đơn vị:</span> Câu lạc bộ Nhiếp ảnh</span>
                    <span><span class="meta-label">Thời gian:</span> 01/05/2024 - 20/05/2024</span>
                    <span><span class="meta-label">Địa điểm:</span> Online</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label">Điểm cộng:</span><br><strong>6 điểm</strong></div>
                    <div><span class="meta-label">Còn lại:</span><br><strong>30 / 120</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag activity-tag--khac">Khác</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card" tabindex="0"
            data-title="Ngày hội \"Vì môi trường xanh\""
            data-unit="CLB Môi trường xanh"
            data-time="28/05/2024 - 28/05/2024"
            data-location="Công viên 23/9, Quận 1"
            data-point="8 điểm"
            data-remaining="90 / 120"
            data-tag="Tình nguyện"
            data-benefits="Góp phần bảo vệ môi trường, tăng tinh thần cộng đồng và được cộng điểm rèn luyện."
            data-clothing="Áo xanh/đồng phục sinh viên, giày thể thao, mang theo bình nước cá nhân."
            data-audience="Sinh viên quan tâm hoạt động môi trường"
            data-content="Thu gom rác, trồng cây, phân loại rác và tuyên truyền lối sống xanh."
            data-contact-name="Đặng Văn H"
            data-contact-phone="0908 777 888"
            data-image="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge">Sắp diễn ra</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Ngày hội "Vì môi trường xanh"</div>
                <div class="activity-meta">
                    <span><span class="meta-label">Đơn vị:</span> CLB Môi trường xanh</span>
                    <span><span class="meta-label">Thời gian:</span> 28/05/2024 - 28/05/2024</span>
                    <span><span class="meta-label">Địa điểm:</span> Công viên 23/9, Quận 1</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label">Điểm cộng:</span><br><strong>8 điểm</strong></div>
                    <div><span class="meta-label">Còn lại:</span><br><strong>90 / 120</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag activity-tag--tinhnguyen">Tình nguyện</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>
    </div>

    <div class="activity-pagination">
        <span class="page-btn">&#x2039;</span>
        <span class="page-btn active">1</span>
        <span class="page-btn">2</span>
        <span class="page-btn">3</span>
        <span class="page-btn">&#x203A;</span>
    </div>
</div>

<script>
    // Wire up Đăng ký buttons to the registration page with the activity title
    (function() {
        const base = 'http://localhost/KhoaLuan/public/student.php?action=dangkyhoatdong';
        document.querySelectorAll('.activity-card').forEach(function(card) {
            const btn = card.querySelector('.activity-btn');
            if (!btn) return;
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const title = card.dataset.title || '';
                const url = base + '&title=' + encodeURIComponent(title);
                window.location.href = url;
            });
        });
    })();
</script>

<?php include __DIR__ . '/activity_detail_modal.php'; ?>
