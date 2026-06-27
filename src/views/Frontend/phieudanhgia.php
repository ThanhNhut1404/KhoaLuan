<?php
    $student = $student ?? [];
    $sections = [
        [
            'title' => 'Dieu 1. Danh gia ve y thuc tham gia hoc tap (0 - 20 diem)',
            'max' => 20,
            'score' => 18,
            'items' => [
                [
                    'label' => 'Di hoc va thuc tap day du cac mon hoc theo lich hoc va lich thuc tap.',
                    'max' => 5,
                    'score' => 5
                ],
                [
                    'label' => 'Thuc hien dung quy che thi, kiem tra.',
                    'max' => 5,
                    'score' => 5
                ],
                [
                    'label' => 'Khong bi cam thi mon hoc nao.',
                    'max' => 3,
                    'score' => 3
                ],
                [
                    'label' => 'Vuot kho, phan dau trong hoc tap, xep loai hoc tap hoc ky gan nhat tu kha tro len.',
                    'max' => 3,
                    'score' => 3
                ],
                [
                    'label' => 'Tham gia mot trong cac hoat dong hoc thuat, nghien cuu khoa hoc, cac ky thi ve hoc thuat hoac dat mot trong cac ky nang chuan dau ra theo quy dinh cua truong.',
                    'max' => 4,
                    'score' => 2
                ]
            ]
        ],
        [
            'title' => 'Dieu 2. Danh gia ve y thuc chap hanh noi quy, quy dinh trong nha truong (0 - 25 diem)',
            'max' => 25,
            'score' => 0,
            'items' => []
        ],
        [
            'title' => 'Dieu 3. Danh gia ve y thuc tham gia cac hoat dong chinh tri, xa hoi (0 - 20 diem)',
            'max' => 20,
            'score' => 0,
            'items' => []
        ],
        [
            'title' => 'Dieu 4. Danh gia ve y thuc cong dan trong quan he cong dong (0 - 25 diem)',
            'max' => 25,
            'score' => 0,
            'items' => []
        ],
        [
            'title' => 'Dieu 5. Danh gia ve y thuc va ket qua tham gia cong tac can bo lop, doan the (0 - 10 diem)',
            'max' => 10,
            'score' => 0,
            'items' => []
        ]
    ];

    $totalScore = 18;
?>

<style>
    .evaluation-page {
        display: grid;
        gap: 18px;
    }

    .evaluation-title {
        font-size: 18px;
        font-weight: 700;
        color: #1d4ed8;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        text-align: center;
    }

    .evaluation-note {
        background: #eef4ff;
        border: 1px solid #dbe7ff;
        color: #1e3a8a;
        font-size: 13px;
        padding: 10px 14px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .evaluation-note svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
    }

    .student-info-card {
        background: #fff;
        border: 1px solid #e8ecf3;
        border-radius: 10px;
        padding: 14px 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .student-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 10px 18px;
    }

    .student-info-item {
        display: grid;
        gap: 4px;
    }

    .student-info-item span:first-child {
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
    }

    .student-info-item span:last-child {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
    }

    .evaluation-section {
        border: 1px solid #e8ecf3;
        border-radius: 10px;
        background: #fff;
        overflow: hidden;
    }

    .evaluation-section summary {
        list-style: none;
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        cursor: pointer;
        background: linear-gradient(135deg, #1d4ed8 0%, #2c50c8 100%);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
    }

    .evaluation-section summary::-webkit-details-marker { display: none; }

    .section-meta {
        display: grid;
        gap: 4px;
        text-align: right;
        font-size: 12px;
        font-weight: 600;
    }

    .section-body {
        padding: 12px 14px 16px;
        display: grid;
        gap: 10px;
    }

    .section-table {
        display: grid;
        gap: 8px;
    }

    .section-row {
        display: grid;
        grid-template-columns: minmax(220px, 1fr) 90px 160px 140px 90px;
        gap: 10px;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px dashed #e5e7eb;
        font-size: 13px;
        color: #1f2937;
    }

    .section-row.header {
        font-size: 12px;
        font-weight: 700;
        color: #4b5563;
        text-transform: uppercase;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 10px;
    }

    .section-row:last-child {
        border-bottom: none;
    }

    .score-select {
        width: 100%;
        padding: 6px 8px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        font-size: 13px;
        background: #f9fafb;
    }

    .evidence-btn {
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #dbe7ff;
        background: #f3f6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .evidence-btn svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
    }

    .note-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        background: #f9fafb;
    }

    .note-icon svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
    }

    .section-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 700;
        color: #1d4ed8;
        padding: 6px 0 0;
    }

    .evaluation-summary {
        background: #f8fbff;
        border: 1px solid #dbe7ff;
        border-radius: 10px;
        padding: 14px 16px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 10px 16px;
        align-items: center;
    }

    .summary-item {
        display: grid;
        gap: 4px;
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
    }

    .summary-item strong {
        font-size: 18px;
        color: #1d4ed8;
    }

    .summary-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        background: #ffe8cc;
        color: #c2410c;
        width: max-content;
    }

    .summary-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .summary-btn {
        padding: 8px 14px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        background: #fff;
        color: #1f2937;
    }

    .summary-btn.primary {
        background: #1d4ed8;
        color: #fff;
        border-color: #1d4ed8;
    }

    @media (max-width: 900px) {
        .section-row {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .section-row.header {
            display: none;
        }

        .summary-actions {
            justify-content: flex-start;
        }
    }
</style>

<div class="evaluation-page">
    <h2 class="evaluation-title">Phiếu đánh giá kết quả rèn luyện của sinh viên</h2>

    <div class="evaluation-note alert alert-warning">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="9" stroke-width="2" />
            <path d="M12 8h.01" stroke-width="2" stroke-linecap="round" />
            <path d="M11 12h1v5h1" stroke-width="2" stroke-linecap="round" />
        </svg>
        <span>Sinh viên tự đánh giá trung thực theo đúng quy định. Điểm tự chấm sẽ được các đơn vị liên quan xem xét và đánh giá.</span>
    </div>

    <div class="student-info-card card">
        <div class="student-info-grid">
            <div class="student-info-item">
                <span>Họ tên sinh viên</span>
                <span><?= $student['ho_ten'] ?? 'Nguyen Van A' ?></span>
            </div>
            <div class="student-info-item">
                <span>Mã số sinh viên</span>
                <span><?= $student['mssv'] ?? '2213405678' ?></span>
            </div>
            <div class="student-info-item">
                <span>Lớp</span>
                <span><?= $student['lop'] ?? 'D19CNTT01' ?></span>
            </div>
            <div class="student-info-item">
                <span>Khoa/ Bộ môn</span>
                <span><?= $student['khoa'] ?? 'Khoa Công nghệ thông tin' ?></span>
            </div>
            <div class="student-info-item">
                <span>Học kỳ</span>
                <span><?= $student['hoc_ky'] ?? '2' ?></span>
            </div>
            <div class="student-info-item">
                <span>Năm học</span>
                <span><?= $student['nam_hoc'] ?? '2024 - 2025' ?></span>
            </div>
        </div>
    </div>

    <?php foreach ($sections as $index => $section): ?>
        <details class="evaluation-section" <?= $index === 0 ? 'open' : '' ?>>
            <summary>
                <span><?= $section['title'] ?></span>
                <span class="section-meta">
                    <span>Điểm tối đa: <?= $section['max'] ?></span>
                    <span>Sinh viên tự chấm: <?= $section['score'] ?></span>
                </span>
            </summary>
            <div class="section-body card-body">
                <div class="section-table table-responsive">
                    <div class="section-row header">
                        <div>ội dung đánh giá</div>
                        <div>Điểm tối đa</div>
                        <div>Sinh viên tự chấm</div>
                        <div>Minh chứng (nếu có)</div>
                        <div>Ghi chú</div>
                    </div>
                    <?php if (!empty($section['items'])): ?>
                        <?php foreach ($section['items'] as $item): ?>
                            <div class="section-row">
                                <div><?= $item['label'] ?></div>
                                <div><?= $item['max'] ?></div>
                                <div>
                                    <select class="score-select form-select">
                                        <?php for ($i = 0; $i <= $item['max']; $i++): ?>
                                            <option value="<?= $i ?>" <?= $i === $item['score'] ? 'selected' : '' ?>><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div>
                                    <button class="evidence-btn btn btn-outline-secondary" type="button">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5v14" stroke-width="2" stroke-linecap="round" />
                                            <path d="M5 12h14" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                        Tải minh chứng
                                    </button>
                                </div>
                                <div>
                                    <span class="note-icon" title="Ghi chú">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 5h16v12H7l-3 3V5Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="section-row">
                            <div>Chưa có nội dung chi tiết.</div>
                            <div><?= $section['max'] ?></div>
                            <div>
                                <select class="score-select form-select">
                                    <?php for ($i = 0; $i <= $section['max']; $i++): ?>
                                        <option value="<?= $i ?>" <?= $i === $section['score'] ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div>
                                <button class="evidence-btn btn btn-outline-secondary" type="button">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 5v14" stroke-width="2" stroke-linecap="round" />
                                        <path d="M5 12h14" stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                    Tải minh chứng
                                </button>
                            </div>
                            <div>
                                <span class="note-icon" title="Ghi chú">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 5h16v12H7l-3 3V5Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="section-total">
                    <span>Tổng điểm điều <?= $index + 1 ?></span>
                    <span><?= $section['score'] ?></span>
                </div>
            </div>
        </details>
    <?php endforeach; ?>

    <div class="evaluation-summary">
        <div class="summary-item">
            <span>Tổng điểm tự chấm</span>
            <strong><?= $totalScore ?> / 100</strong>
        </div>
        <div class="summary-item">
            <span>Xếp loại dự kiến</span>
            <strong>Chưa xếp loại</strong>
        </div>
        <div class="summary-item">
            <span>Trạng thái</span>
            <span class="summary-status badge rounded-pill">Chưa gửi</span>
        </div>
        <div class="summary-actions">
            <button class="summary-btn btn btn-outline-secondary" type="button">Lưu nháp</button>
            <button class="summary-btn primary btn btn-primary" type="button">Gửi phiếu tự chấm</button>
        </div>
    </div>
</div>
