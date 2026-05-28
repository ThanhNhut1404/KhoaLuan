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

    .activity-tab.active {
        color: #1d4ed8;
        background: #eef2ff;
        border-color: #c7d2fe;
    }

    .activity-sort {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #64748b;
    }

    .sort-select {
        padding: 6px 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #fff;
        font-size: 12px;
        font-weight: 600;
        color: #1d4ed8;
    }

    .activity-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }

    .activity-card {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e8ecf3;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        overflow: hidden;
        display: grid;
        grid-template-rows: 140px 1fr;
    }

    .activity-cover {
        position: relative;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
    }

    .activity-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .activity-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 4px 8px;
        border-radius: 999px;
        background: #e6f9ef;
        color: #047857;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid #bdebd3;
    }


    .activity-body {
        padding: 12px 14px 14px;
        display: grid;
        gap: 10px;
    }

    .activity-title {
        font-size: 13px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.3;
        min-height: 34px;
    }

    .activity-meta {
        display: grid;
        gap: 4px;
        font-size: 11px;
        color: #64748b;
    }

    .activity-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .meta-label {
        color: #9ca3af;
        font-weight: 700;
    }

    .activity-score {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 6px;
        font-size: 11px;
        color: #64748b;
        margin-top: 4px;
    }

    .activity-score strong {
        color: #1f2937;
        font-weight: 700;
    }

    .activity-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .activity-tag {
        padding: 4px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        background: #eef2ff;
        color: #1d4ed8;
    }

    .activity-btn {
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid #16a34a;
        background: #16a34a;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
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
        <article class="activity-card">
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
                    <span class="activity-tag">Tình nguyện</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card">
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
                    <span class="activity-tag">Học thuật</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card">
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
                    <span class="activity-tag">Thể thao</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card">
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
                    <span class="activity-tag">Tình nguyện</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card">
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
                    <span class="activity-tag">Văn hóa - Văn nghệ</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card">
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
                    <span class="activity-tag">Kỹ năng</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card">
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
                    <span class="activity-tag">Khác</span>
                    <button class="activity-btn" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card">
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
                    <span class="activity-tag">Tình nguyện</span>
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
