<?php
$title = 'ÁP DỤNG BỘ TIÊU CHÍ CHO HỌC KỲ';
$academicYears = $academicYears ?? [];
$selectedAcademicYearId = isset($selectedAcademicYearId) ? (int) $selectedAcademicYearId : 0;
$semesterRows = $semesterRows ?? [];
$masterTemplates = $masterTemplates ?? [];
$formData = $formData ?? [];
$errors = $errors ?? [];

function applyCriteriaOldValue(array $formData, string $key, string $default = ''): string
{
    return htmlspecialchars((string) ($formData[$key] ?? $default), ENT_QUOTES, 'UTF-8');
}

function applyCriteriaErrorHtml(array $errors, string $key): string
{
    return !empty($errors[$key]) ? '<div class="text-danger" style="margin-top:4px;font-size:12px;">' . htmlspecialchars($errors[$key], ENT_QUOTES, 'UTF-8') . '</div>' : '';
}
?>

<div class="list-apply-criteria-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="header-content">
                <h2 class="panel-title">ÁP DỤNG BỘ TIÊU CHÍ CHO HỌC KỲ</h2>
                <div class="filter-wrap <?= $selectedAcademicYearId > 0 ? 'has-active' : '' ?>" id="applyCriteriaFilter">
                    <button type="button" id="applyCriteriaFilterToggle" class="filter-btn btn btn-outline-secondary" title="Bộ lọc" aria-label="Bộ lọc" aria-expanded="false">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 5h16l-6 7v5l-4 2v-7L4 5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="filter-menu" id="applyCriteriaFilterMenu" role="menu" aria-labelledby="applyCriteriaFilterToggle">
                        <form method="GET" action="/KhoaLuan/public/admin.php" class="filter-form">
                            <input type="hidden" name="page" value="apply_criteria" />
                            <label class="filter-label" for="academicYear">Chọn niên khóa</label>
                            <select id="academicYear" name="MA_NIEN_KHOA" class="filter-select form-select" onchange="this.form.submit()">
                                <option value="">-- Chọn niên khóa --</option>
                                <?php foreach ($academicYears as $year): ?>
                                    <?php $yearId = (int) ($year['id'] ?? $year['MA_NIEN_KHOA'] ?? 0); ?>
                                    <option value="<?= htmlspecialchars((string) $yearId, ENT_QUOTES, 'UTF-8') ?>" <?= $yearId === $selectedAcademicYearId ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($year['name'] ?? $year['TEN_NIEN_KHOA'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body card-body">
            <?php if ($selectedAcademicYearId <= 0): ?>
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 13h6M9 17h3M5 21h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <h3>Vui lòng chọn niên khóa để xem danh sách học kỳ.</h3>
                </div>
            <?php elseif (empty($semesterRows)): ?>
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 13h6M9 17h3M5 21h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <h3>Không có học kỳ nào trong niên khóa này.</h3>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="col-stt">STT</th>
                                <th>Học kỳ</th>
                                <th>Thời gian bắt đầu</th>
                                <th>Thời gian kết thúc</th>
                                <th>Trạng thái</th>
                                <th>Bộ tiêu chí đang áp dụng</th>
                                <th>Chọn bộ tiêu chí áp dụng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($semesterRows as $index => $row): ?>
                                <?php $rowSemesterId = (int) ($row['id'] ?? 0); ?>
                                <?php $rowTemplate = $row['appliedTemplate'] ?? null; ?>
                                <?php $rowStatus = htmlspecialchars((string) ($row['status'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars((string) ($row['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) ($row['start_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?: '<span class="text-muted">Chưa cập nhật</span>' ?></td>
                                    <td><?= htmlspecialchars((string) ($row['end_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?: '<span class="text-muted">Chưa cập nhật</span>' ?></td>
                                    <td><?= $rowStatus !== '' ? $rowStatus : '<span class="text-muted">Chưa cập nhật</span>' ?></td>
                                    <td>
                                        <?= $rowTemplate ? htmlspecialchars((string) ($rowTemplate['name'] ?? $rowTemplate['TEN_BO_MAU'] ?? ''), ENT_QUOTES, 'UTF-8') : '<span class="text-muted">Chưa áp dụng</span>' ?>
                                    </td>
                                    <td>
                                        <form method="POST" action="/KhoaLuan/public/admin.php?page=apply_criteria" class="apply-form">
                                            <input type="hidden" name="form_type" value="apply_template">
                                            <input type="hidden" name="semester_id" value="<?= htmlspecialchars((string) $rowSemesterId, ENT_QUOTES, 'UTF-8') ?>">
                                            <input type="hidden" name="MA_NIEN_KHOA" value="<?= htmlspecialchars((string) $selectedAcademicYearId, ENT_QUOTES, 'UTF-8') ?>">

                                            <div class="apply-form-controls">
                                                <select name="apply_template_id" class="apply-template-select form-select form-select-sm" required>
                                                    <option value="">-- Chọn bộ tiêu chí --</option>
                                                    <?php $currentTemplateId = (int) ($rowTemplate['id'] ?? $rowTemplate['MA_BO_MAU'] ?? 0); ?>
                                                    <?php foreach ($masterTemplates as $template): ?>
                                                        <?php $templateOptionId = (int) ($template['id'] ?? $template['MA_BO_MAU'] ?? 0); ?>
                                                        <option value="<?= htmlspecialchars((string) $templateOptionId, ENT_QUOTES, 'UTF-8') ?>" <?= $templateOptionId === $currentTemplateId ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($template['name'] ?? $template['TEN_BO_MAU'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" class="btn btn-primary btn-sm apply-template-button" disabled>
                                                    Áp dụng
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .list-apply-criteria-page { padding: 24px; }
    .page-panel { background: #ffffff; border: 1px solid #e8ecf3; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: visible; }
    .panel-header { position: relative; z-index: 40; padding: 12px 14px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; border-radius: 8px 8px 0 0; }
    .header-content { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
    .panel-title { font-size: 14px; font-weight: 700; color: #0f2a5a; margin: 0; }
    .filter-wrap { position: relative; display: inline-flex; align-items: center; }
    .filter-btn { width: 32px; height: 32px; border: 1px solid #e5e7eb; border-radius: 6px; background: #fff; color: #0f2a5a; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
    .filter-wrap.has-active .filter-btn { background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8; }
    .filter-btn:hover { background: #f8fafc; color: #0b1f45; }
    .filter-menu { position: absolute; top: calc(100% + 6px); right: 0; z-index: 1000; display: none; width: 280px; padding: 12px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 8px 20px rgba(15,42,90,0.12); }
    .filter-wrap.open .filter-menu { display: block; }
    .filter-form { display: grid; gap: 8px; }
    .filter-label { font-size: 12px; font-weight: 700; color: #0f2a5a; margin: 0; }
    .filter-select { min-height: 36px; font-size: 13px; border-radius: 8px; border-color: #e5e7eb; }
    .panel-body { padding: 0; }
    .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px 20px; text-align: center; color: #9ca3af; }
    .empty-state svg { color: #d1d5db; margin-bottom: 16px; }
    .empty-state h3 { font-size: 16px; font-weight: 600; color: #6b7280; margin: 0; }
    .table-wrapper { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .data-table thead, .data-table thead.table-light { background: #eef2f7; border-bottom: 1px solid #e5e7eb; }
    .data-table th { padding: 12px 14px; text-align: center; font-weight: 800; color: #0f2a5a; text-transform: uppercase; letter-spacing: .4px; font-size: 11px; border-right: 1px solid #d1d5db; white-space: nowrap; }
    .data-table tbody tr { border-bottom: 1px solid #f3f4f6; transition: background-color .2s; }
    .data-table tbody tr:nth-child(odd) { background: #f9fafb; }
    .data-table tbody tr:hover { background: #f0f1f3; }
    .data-table td { padding: 12px 14px; color: #1f2937; text-align: center; border-right: 1px solid #e5e7eb; vertical-align: middle; }
    .col-stt { width: 35px; }
    .apply-form { display: inline-flex; align-items: center; justify-content: center; width: 100%; }
    .apply-form-controls { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; }
    .apply-template-select { min-width: 220px; padding: 6px 10px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; font-size: 13px; }
    .apply-template-button { min-width: 90px; border-radius: 8px; font-weight: 700; }
    .action-badge { display: inline-flex; align-items: center; justify-content: center; min-width: 100px; padding: 6px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; background: #e0f2fe; color: #0369a1; }
    @media (max-width: 768px) {
        .filter-menu { right: -4px; width: min(280px, calc(100vw - 48px)); }
        .data-table { min-width: 980px; }
        .apply-form-controls { flex-wrap: wrap; justify-content: flex-start; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.apply-template-select').forEach(function (select) {
            const form = select.closest('form');
            const button = form ? form.querySelector('.apply-template-button') : null;

            if (button) {
                button.disabled = !select.value;
            }

            select.addEventListener('change', function () {
                if (button) {
                    button.disabled = !this.value;
                }
            });
        });
    });

    (function () {
        const filterWrap = document.getElementById('applyCriteriaFilter');
        const filterToggle = document.getElementById('applyCriteriaFilterToggle');

        if (!filterWrap || !filterToggle) {
            return;
        }

        filterToggle.addEventListener('click', function (event) {
            event.stopPropagation();
            const isOpen = filterWrap.classList.toggle('open');
            filterToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function (event) {
            if (!filterWrap.contains(event.target)) {
                filterWrap.classList.remove('open');
                filterToggle.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                filterWrap.classList.remove('open');
                filterToggle.setAttribute('aria-expanded', 'false');
            }
        });
    })();
</script>
