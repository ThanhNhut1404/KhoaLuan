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
    $totalMaxScore = array_sum(array_column($sections, 'max'));

    $getCurrentRank = static function (int $score): string {
        if ($score >= 90) {
            return 'Xu&#7845;t s&#7855;c';
        }

        if ($score >= 80) {
            return 'T&#7889;t';
        }

        if ($score >= 65) {
            return 'Kh&#225;';
        }

        if ($score >= 50) {
            return 'Trung b&#236;nh';
        }

        if ($score >= 35) {
            return 'Y&#7871;u';
        }

        return 'K&#233;m';
    };

    $currentRank = $getCurrentRank((int) $totalScore);
    $evaluationStatus = $student['trang_thai_phieu_cham']
        ?? ($student['trang_thai_danh_gia'] ?? 'Chưa nộp');

    $studentInfoValue = static function ($value, string $fallback = 'Chưa cập nhật'): string {
        $value = trim((string) $value);
        return htmlspecialchars($value !== '' ? $value : $fallback, ENT_QUOTES, 'UTF-8');
    };

    $formatStudentDate = static function ($value): string {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        $timestamp = strtotime($value);
        return $timestamp !== false ? date('d/m/Y', $timestamp) : $value;
    };

    $evaluationStart = $formatStudentDate($student['thoi_gian_bat_dau_danh_gia'] ?? '');
    $evaluationEnd = $formatStudentDate($student['thoi_gian_ket_thuc_danh_gia'] ?? '');
    $evaluationTime = trim((string) ($student['thoi_gian_danh_gia'] ?? ''));
    if ($evaluationTime === '') {
        $evaluationTime = $evaluationStart !== '' && $evaluationEnd !== ''
            ? $evaluationStart . ' - ' . $evaluationEnd
            : ($evaluationStart !== '' ? $evaluationStart : $evaluationEnd);
    }
?>

<style>
    .evaluation-page {
        display: grid;
        gap: 16px;
    }

    .evaluation-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        text-align: center;
        margin: 0;
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
        border-radius: 8px;
        padding: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .evaluation-panel__header {
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
    }

    .evaluation-panel__body {
        padding: 14px 16px;
    }

    .student-info-grid {
        display: grid;
        grid-template-columns:
            minmax(165px, 0.72fr)
            max-content
            minmax(180px, 0.9fr)
            minmax(220px, 1fr)
            max-content
            max-content
            minmax(220px, 1fr);
        gap: 0;
    }

    .student-info-item {
        display: grid;
        gap: 4px;
        padding: 0 18px;
        border-left: 1px solid #e5e7eb;
        justify-items: center;
        text-align: center;
    }

    .student-info-item:first-child {
        border-left: none;
        padding-left: 0;
    }

    .student-info-item:last-child {
        padding-right: 0;
    }

    .student-info-item--compact {
        min-width: max-content;
        white-space: nowrap;
    }

    .student-info-item--name {
        justify-items: center;
        text-align: center;
    }

    .student-info-item--name span:last-child {
        justify-self: start;
        text-align: left;
    }

    .student-info-item--class {
        min-width: 180px;
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

    .score-overview-card {
        background: #fff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .score-overview-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(150px, 1fr));
        gap: 0;
    }

    .score-overview-item {
        display: grid;
        gap: 6px;
        padding: 0 18px;
        border-left: 1px solid #e5e7eb;
        text-align: center;
        justify-items: center;
    }

    .score-overview-item:first-child {
        border-left: none;
    }

    .score-overview-item span:first-child {
        font-size: 12px;
        color: #6b7280;
        font-weight: 700;
    }

    .score-overview-item span:last-child {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
    }

    .score-overview-student-score {
        color: var(--primary) !important;
    }

    .score-overview-student-score strong {
        color: #16a34a !important;
        font: inherit;
    }

    .score-overview-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 28px;
        padding: 5px 12px;
        border-radius: 999px;
        background: #fff7ed;
        color: #c2410c !important;
        font-size: 13px !important;
        width: max-content;
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
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
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
        grid-template-columns: minmax(260px, 1fr) 180px 170px 90px;
        align-items: center;
        gap: 0;
        padding: 0;
        border-bottom: 1px solid #e5e7eb;
        font-size: 13px;
        color: #1f2937;
    }

    .section-row.header {
        font-size: 12px;
        font-weight: 800;
        color: #4b5563;
        border-bottom: 1px solid #e5e7eb;
        background: #cfd1d5;
    }

    .section-row:last-child {
        border-bottom: none;
    }

    .section-row > div {
        min-height: 52px;
        padding: 10px 12px;
        border-left: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .section-row > div:first-child {
        border-left: none;
        justify-content: flex-start;
        text-align: left;
    }

    .section-row.header > div:first-child {
        justify-content: center;
        text-align: center;
    }

    .section-row.header > div {
        min-height: 36px;
        padding-top: 6px;
        padding-bottom: 6px;
        background: #f3f4f6;
    }

    .score-select {
        width: 72px;
        max-width: 100%;
        padding: 6px 8px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        font-size: 13px;
        background: #f9fafb;
        text-align: center;
    }

    .evidence-btn {
        min-height: 28px;
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #1d4ed8;
        font-size: 13px;
        font-weight: 500 !important;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap;
    }

    .evidence-btn:hover {
        color: #1d4ed8 !important;
        border-color: #d1d5db !important;
        background: #eff6ff !important;
    }

    .evidence-btn svg {
        width: 12px;
        height: 12px;
        stroke: currentColor;
    }

    .note-icon {
        min-height: 34px;
        min-width: 34px;
        padding: 6px 9px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #16a34a;
        background: #fff;
        cursor: pointer;
    }

    .note-icon:hover {
        color: #15803d !important;
        border-color: #d1d5db !important;
        background: #f0fdf4 !important;
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
        color: var(--primary);
        border-top: 1px solid #e5e7eb;
        padding: 12px 0 0;
    }

    .evaluation-summary {
        background: #f8fbff;
        border-top: 1px solid #dbe7ff;
        padding: 10px 16px;
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 10px 16px;
        align-items: center;
    }

    .summary-export-btn {
        min-height: 34px;
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #f97316;
        font-size: 13px;
        font-weight: 900 !important;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap;
    }

    .summary-export-btn:hover {
        color: #f97316 !important;
        background: #fff7ed !important;
        border-color: #d1d5db !important;
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
        color: var(--primary);
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
        flex-wrap: nowrap;
    }

    .summary-btn {
        min-height: 34px;
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        font-size: 13px;
        font-weight: 900 !important;
        cursor: pointer;
        background: #fff;
        color: #1d4ed8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .summary-btn:hover {
        color: #1d4ed8 !important;
        border-color: #d1d5db !important;
        background: #eff6ff !important;
    }

    .summary-btn.primary {
        color: #ffffff !important;
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%) !important;
        border-color: #16a34a !important;
    }

    .summary-btn.primary:hover {
        color: #ffffff !important;
        background: linear-gradient(180deg, #15803d 0%, #166534 100%) !important;
        border-color: #15803d !important;
    }

    @media (max-width: 900px) {
        .section-row {
            grid-template-columns: 1fr;
            gap: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-row.header {
            display: none;
        }

        .section-row > div {
            min-height: 0;
            padding: 8px 0;
            border-left: none;
            justify-content: flex-start;
            text-align: left;
        }

        .summary-actions {
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .evaluation-summary {
            grid-template-columns: 1fr;
        }

        .student-info-grid {
            grid-template-columns: 1fr;
            gap: 10px 0;
        }

        .student-info-item {
            padding: 0;
            border-left: none;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        .student-info-item:first-child {
            border-top: none;
            padding-top: 0;
        }

        .score-overview-grid {
            grid-template-columns: 1fr;
            gap: 10px 0;
        }

        .score-overview-item {
            padding: 0;
            border-left: none;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        .score-overview-item:first-child {
            border-top: none;
            padding-top: 0;
        }
    }
</style>

<div class="evaluation-page">
    <div class="student-info-card card">
        <div class="evaluation-panel__header card-header">
            <h2 class="evaluation-title">Phiếu đánh giá kết quả rèn luyện của sinh viên</h2>
        </div>

        <div class="evaluation-panel__body card-body">
            <div class="student-info-grid">
                <div class="student-info-item student-info-item--name">
                    <span>Họ tên sinh viên</span>
                    <span><?= $studentInfoValue($student['ho_ten'] ?? '') ?></span>
                </div>
                <div class="student-info-item student-info-item--compact">
                    <span>Mã số sinh viên</span>
                    <span><?= $studentInfoValue($student['mssv'] ?? '') ?></span>
                </div>
                <div class="student-info-item student-info-item--class">
                    <span>Lớp</span>
                    <span><?= $studentInfoValue($student['lop_hoc'] ?? '') ?></span>
                </div>
                <div class="student-info-item">
                    <span>Khoa/ Bộ môn</span>
                    <span><?= $studentInfoValue($student['khoa'] ?? '') ?></span>
                </div>
                <div class="student-info-item student-info-item--compact">
                    <span>Học kỳ</span>
                    <span><?= $studentInfoValue($student['hoc_ky'] ?? '') ?></span>
                </div>
                <div class="student-info-item student-info-item--compact">
                    <span>Năm học</span>
                    <span><?= $studentInfoValue($student['nien_khoa'] ?? ($student['khoa_hoc'] ?? '')) ?></span>
                </div>
                <div class="student-info-item">
                    <span>Thời gian thực hiện đánh giá</span>
                    <span><?= $studentInfoValue($evaluationTime) ?></span>
                </div>
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
                        <div>Nội dung đánh giá</div>
                        <div>Sinh viên tự chấm</div>
                        <div>Minh chứng (nếu có)</div>
                        <div>Ghi chú</div>
                    </div>
                    <?php if (!empty($section['items'])): ?>
                        <?php foreach ($section['items'] as $item): ?>
                            <div class="section-row">
                                <div><?= $item['label'] ?></div>
                                <div>
                                    <input
                                        class="score-select form-control"
                                        type="number"
                                        min="0"
                                        max="<?= $item['max'] ?>"
                                        value="<?= $item['score'] ?>"
                                    >
                                </div>
                                <div>
                                    <button
                                        class="evidence-btn btn btn-outline-secondary"
                                        type="button"
                                        data-section-title="<?= htmlspecialchars((string) $section['title'], ENT_QUOTES, 'UTF-8') ?>"
                                        data-criteria-title="<?= htmlspecialchars((string) $item['label'], ENT_QUOTES, 'UTF-8') ?>"
                                        data-criteria-max="<?= htmlspecialchars((string) $item['max'], ENT_QUOTES, 'UTF-8') ?>"
                                        data-criteria-score="<?= htmlspecialchars((string) $item['score'], ENT_QUOTES, 'UTF-8') ?>"
                                    >
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
                            <div>
                                <input
                                    class="score-select form-control"
                                    type="number"
                                    min="0"
                                    max="<?= $section['max'] ?>"
                                    value="<?= $section['score'] ?>"
                                >
                            </div>
                            <div>
                                <button
                                    class="evidence-btn btn btn-outline-secondary"
                                    type="button"
                                    data-section-title="<?= htmlspecialchars((string) $section['title'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-criteria-title="Chưa có nội dung chi tiết."
                                    data-criteria-max="<?= htmlspecialchars((string) $section['max'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-criteria-score="<?= htmlspecialchars((string) $section['score'], ENT_QUOTES, 'UTF-8') ?>"
                                >
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

    <div class="score-overview-card card">
        <div class="evaluation-panel__body card-body">
            <div class="score-overview-grid">
                <div class="score-overview-item">
                    <span>T&#7893;ng &#273;i&#7875;m t&#7889;i &#273;a</span>
                    <span><?= $totalMaxScore ?></span>
                </div>
                <div class="score-overview-item">
                    <span>T&#7893;ng &#273;i&#7875;m sinh vi&#234;n t&#7921; ch&#7845;m</span>
                    <span class="score-overview-student-score"><strong><?= $totalScore ?></strong>/100</span>
                </div>
                <div class="score-overview-item">
                    <span>X&#7871;p lo&#7841;i hi&#7879;n t&#7841;i</span>
                    <span><?= $currentRank ?></span>
                </div>
                <div class="score-overview-item">
                    <span>Tr&#7841;ng th&#225;i phi&#7871;u ch&#7845;m</span>
                    <span class="score-overview-status"><?= $studentInfoValue($evaluationStatus) ?></span>
                </div>
            </div>
        </div>

        <div class="evaluation-summary">
            <button class="summary-export-btn btn btn-outline-danger" type="button">
                <i class="fa-solid fa-file-pdf" aria-hidden="true"></i>
                Xuất PDF
            </button>
            <div class="summary-actions">
                <button class="summary-btn btn btn-outline-secondary" type="button">
                    <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                    Lưu nháp
                </button>
                <button class="summary-btn primary btn btn-primary" type="button">
                    <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                    Nộp phiếu đánh giá
                </button>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/evidence_modal.php'; ?>
