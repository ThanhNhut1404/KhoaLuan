<?php
$hoc_ky_list = $hoc_ky_list ?? $semesters ?? [];
$semesters = $semesters ?? [];
$academicYears = $academicYears ?? [];
$selectedAcademicYearId = $selectedAcademicYearId ?? 0;
$selectedSemesterId = $selectedSemesterId ?? 0;
$categories = $categories ?? [];
$formData = $formData ?? [];
$errors = $errors ?? [];
$statusOptions = $statusOptions ?? [];
$typeOptions = $typeOptions ?? [];
$isEdit = $isEdit ?? false;
$formType = $formType ?? 'criteria';

$selectedSemester = $formData['semester_id'] ?? $selectedSemesterId;
$selectedAcademicYear = $formData['MA_NIEN_KHOA'] ?? $selectedAcademicYearId;
$selectedCategory = $formData['category_id'] ?? 0;
$selectedType = $formData['type'] ?? 'CONG_THEO_LAN';
$selectedStatus = $formData['status'] ?? '1';
$categoryName = $formData['category_name'] ?? '';
$categoryMaxPoints = $formData['category_max_points'] ?? '';
$categoryDisplayOrder = $formData['category_display_order'] ?? 0;
?>

<div class="configure-criteria-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="panel-header-content">
                <div>
                    <h2 class="panel-title">Quản lý tiêu chí điểm rèn luyện</h2>
                    <p class="panel-description">Tạo danh mục và tiêu chí đánh giá cho học kỳ, với các loại điểm Cộng, Trừ và Cố định.</p>
                </div>
            </div>
        </div>
        <div class="panel-body card-body">
            <div class="criteria-grid">
                <div class="criteria-card">
                    <div class="card-heading">Tạo danh mục tiêu chí</div>
                    <form method="post" action="/KhoaLuan/public/admin.php?page=configure_criteria">
                        <input type="hidden" name="form_type" value="category" />

                        <div class="form-row">
                            <label class="field-label" for="category_year_id">Niên khóa</label>
                            <select id="category_year_id" name="MA_NIEN_KHOA" class="field-input form-select">
                                <option value="">-- Chọn niên khóa --</option>
                                <?php foreach ($academicYears as $year): ?>
                                    <option value="<?= htmlspecialchars($year['id'] ?? $year['MA_NIEN_KHOA'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= (int) $selectedAcademicYear === (int) ($year['id'] ?? $year['MA_NIEN_KHOA'] ?? 0) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($year['name'] ?? $year['TEN_NIEN_KHOA'] ?? 'Không xác định', ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-row">
                            <label class="field-label" for="category_semester_id">Học kỳ</label>
                            <select id="category_semester_id" name="semester_id" class="field-input form-select">
                                <option value="">-- Chọn học kỳ --</option>
                                <?php if (!empty($hoc_ky_list)): ?>
                                    <?php foreach ($hoc_ky_list as $hk): ?>
                                        <option value="<?= htmlspecialchars($hk['MA_HOC_KY'], ENT_QUOTES, 'UTF-8') ?>" <?= (int) $selectedSemester === (int) $hk['MA_HOC_KY'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($hk['TEN_HOC_KY'] ?? 'Không xác định', ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-row">
                            <label class="field-label" for="category_name">Tên danh mục</label>
                            <input id="category_name" name="category_name" type="text" class="field-input form-control" value="<?= htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8') ?>" />
                            <?php if (!empty($errors['category_name'])): ?><div class="field-error"><?= htmlspecialchars($errors['category_name'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <div class="form-row">
                            <label class="field-label" for="category_max_points">Điểm tối đa danh mục</label>
                            <input id="category_max_points" name="category_max_points" type="number" step="0.01" min="0" class="field-input form-control" value="<?= htmlspecialchars((string) $categoryMaxPoints, ENT_QUOTES, 'UTF-8') ?>" />
                            <?php if (!empty($errors['category_max_points'])): ?><div class="field-error"><?= htmlspecialchars($errors['category_max_points'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <div class="form-row">
                            <label class="field-label" for="category_display_order">Thứ tự hiển thị</label>
                            <input id="category_display_order" name="category_display_order" type="number" min="0" class="field-input form-control" value="<?= htmlspecialchars((string) $categoryDisplayOrder, ENT_QUOTES, 'UTF-8') ?>" />
                            <?php if (!empty($errors['category_display_order'])): ?><div class="field-error"><?= htmlspecialchars($errors['category_display_order'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <?php if (!empty($errors['category_general'])): ?><div class="field-error"><?= htmlspecialchars($errors['category_general'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

                        <div class="form-actions">
                            <button type="submit" class="action-btn primary btn btn-primary">Tạo danh mục</button>
                        </div>
                    </form>
                </div>

                <div class="criteria-card">
                    <div class="card-heading"><?= $isEdit ? 'Chỉnh sửa tiêu chí con' : 'Tạo tiêu chí con' ?></div>
                    <form method="post" action="/KhoaLuan/public/admin.php?page=configure_criteria<?= $isEdit && !empty($formData['id']) ? '&id=' . urlencode((string) $formData['id']) : '' ?>">
                        <input type="hidden" name="form_type" value="criteria" />
                        <?php if ($isEdit && !empty($formData['id'])): ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars((string) $formData['id'], ENT_QUOTES, 'UTF-8') ?>" />
                        <?php endif; ?>

                        <div class="form-row">
                            <label class="field-label" for="criteria_year_id">Niên khóa</label>
                            <select id="criteria_year_id" name="MA_NIEN_KHOA" class="field-input form-select">
                                <option value="">-- Chọn niên khóa --</option>
                                <?php foreach ($academicYears as $year): ?>
                                    <option value="<?= htmlspecialchars($year['id'] ?? $year['MA_NIEN_KHOA'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= (int) $selectedAcademicYear === (int) ($year['id'] ?? $year['MA_NIEN_KHOA'] ?? 0) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($year['name'] ?? $year['TEN_NIEN_KHOA'] ?? 'Không xác định', ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-row">
                            <label class="field-label" for="semester_id">Học kỳ</label>
                            <select id="semester_id" name="semester_id" class="field-input form-select">
                                <option value="">-- Chọn học kỳ --</option>
                                <?php if (!empty($hoc_ky_list)): ?>
                                    <?php foreach ($hoc_ky_list as $hk): ?>
                                        <option value="<?= htmlspecialchars($hk['MA_HOC_KY'], ENT_QUOTES, 'UTF-8') ?>" <?= (int) $selectedSemester === (int) $hk['MA_HOC_KY'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($hk['TEN_HOC_KY'] ?? 'Không xác định', ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php if (!empty($errors['semester_id'])): ?><div class="field-error"><?= htmlspecialchars($errors['semester_id'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <div class="form-row">
                            <label class="field-label" for="category_id">Danh mục cha</label>
                            <select id="category_id" name="category_id" class="field-input form-select">
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') ?>" <?= (int) $selectedCategory === (int) $category['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars((string) $category['max_points'], ENT_QUOTES, 'UTF-8') ?> điểm)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errors['category_id'])): ?><div class="field-error"><?= htmlspecialchars($errors['category_id'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <div class="form-row">
                            <label class="field-label" for="name">Tên tiêu chí</label>
                            <input id="name" name="name" type="text" class="field-input form-control" value="<?= htmlspecialchars($formData['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                            <?php if (!empty($errors['name'])): ?><div class="field-error"><?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <div class="form-row full-width">
                            <label class="field-label" for="description">Mô tả tiêu chí</label>
                            <textarea id="description" name="description" class="field-input form-control" rows="4"><?= htmlspecialchars($formData['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            <?php if (!empty($errors['description'])): ?><div class="field-error"><?= htmlspecialchars($errors['description'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <div class="form-row">
                            <label class="field-label" for="type">Loại tiêu chí</label>
                            <select id="type" name="type" class="field-input form-select">
                                <?php foreach ($typeOptions as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value'], ENT_QUOTES, 'UTF-8') ?>" <?= $selectedType === $option['value'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errors['type'])): ?><div class="field-error"><?= htmlspecialchars($errors['type'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <div class="form-row" id="fixed_point_row">
                            <label class="field-label" for="fixed_point">Điểm cố định mỗi lần</label>
                            <input id="fixed_point" name="fixed_point" type="number" step="0.01" min="0" class="field-input form-control" value="<?= htmlspecialchars((string) ($formData['fixed_point'] ?? '0.00'), ENT_QUOTES, 'UTF-8') ?>" />
                            <?php if (!empty($errors['fixed_point'])): ?><div class="field-error"><?= htmlspecialchars($errors['fixed_point'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <div class="form-row" id="deduct_point_row">
                            <label class="field-label" for="deduct_point">Điểm trừ mỗi lần</label>
                            <input id="deduct_point" name="deduct_point" type="number" step="0.01" min="0" class="field-input form-control" value="<?= htmlspecialchars((string) ($formData['deduct_point'] ?? '0.00'), ENT_QUOTES, 'UTF-8') ?>" />
                            <?php if (!empty($errors['deduct_point'])): ?><div class="field-error"><?= htmlspecialchars($errors['deduct_point'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <div class="form-row" id="max_times_row">
                            <label class="field-label" for="max_times">Lần thực hiện tối đa</label>
                            <input id="max_times" name="max_times" type="number" min="0" class="field-input form-control" value="<?= htmlspecialchars((string) ($formData['max_times'] ?? '0'), ENT_QUOTES, 'UTF-8') ?>" />
                            <?php if (!empty($errors['max_times'])): ?><div class="field-error"><?= htmlspecialchars($errors['max_times'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <div class="form-row" id="max_point_row">
                            <label class="field-label" for="max_point">Điểm tối đa tiêu chí</label>
                            <input id="max_point" name="max_point" type="number" step="0.01" min="0" class="field-input form-control" value="<?= htmlspecialchars((string) ($formData['max_point'] ?? '0.00'), ENT_QUOTES, 'UTF-8') ?>" />
                            <?php if (!empty($errors['max_point'])): ?><div class="field-error"><?= htmlspecialchars($errors['max_point'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <div class="form-row field-checkbox">
                            <label class="checkbox-label" for="use_for_activity">
                                    <input id="use_for_activity" name="use_for_activity" type="checkbox" value="1" <?= !empty($formData['use_for_activity']) ? 'checked' : '' ?> />
                                    Sử dụng tiêu chí cho tạo hoạt động (SU_DUNG_CHO_HOAT_DONG)
                                </label>
                        </div>

                        <div class="form-row">
                            <label class="field-label" for="display_order">Thứ tự hiển thị</label>
                            <input id="display_order" name="display_order" type="number" min="0" class="field-input form-control" value="<?= htmlspecialchars((string) ($formData['display_order'] ?? '0'), ENT_QUOTES, 'UTF-8') ?>" />
                            <?php if (!empty($errors['display_order'])): ?><div class="field-error"><?= htmlspecialchars($errors['display_order'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <div class="form-row">
                            <label class="field-label" for="status">Trạng thái</label>
                            <select id="status" name="status" class="field-input form-select">
                                <?php foreach ($statusOptions as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value'], ENT_QUOTES, 'UTF-8') ?>" <?= $selectedStatus === $option['value'] ? 'selected' : '' ?> >
                                        <?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errors['status'])): ?><div class="field-error"><?= htmlspecialchars($errors['status'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                        </div>

                        <div class="form-help">
                            <p><strong>Ghi chú</strong></p>
                            <ul>
                                <li><strong>Cộng theo lần:</strong> điểm tối đa tự động tính = điểm cố định × số lần tối đa.</li>
                                <li><strong>Trừ theo lần:</strong> bắt đầu với điểm tối đa, trừ theo số lần vi phạm.</li>
                                <li><strong>Cố định:</strong> điểm tối đa là điểm duy nhất khi thỏa điều kiện.</li>
                            </ul>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="action-btn primary btn btn-primary"><?= $isEdit ? 'Cập nhật tiêu chí' : 'Tạo tiêu chí' ?></button>
                            <a class="action-btn secondary btn btn-outline-secondary" href="?page=list_criteria<?= $selectedSemester ? '&semester_id=' . urlencode((string) $selectedSemester) : '' ?>">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.configure-criteria-page { padding: 24px; }
.page-panel { background:#fff; border:1px solid #e8ecf3; border-radius:12px; box-shadow:0 2px 18px rgba(15,23,42,0.08); }
.panel-header { padding:24px; border-bottom:1px solid #e5e7eb; background:#f8fafc; }
.panel-header-content { display:flex; flex-direction:column; gap:8px; }
.panel-title { margin:0; font-size:18px; font-weight:800; color:#0f172a; }
.panel-description { margin:0; color:#475569; font-size:14px; }
.panel-body { padding:24px; }
.criteria-grid { display:grid; grid-template-columns: 1fr 1fr; gap:20px; }
.criteria-card { background:#ffffff; border:1px solid #e5e7eb; border-radius:16px; padding:22px; box-shadow:0 6px 20px rgba(15,23,42,0.04); }
.card-heading { margin:0 0 16px; font-size:16px; font-weight:700; color:#0f172a; }
.form-row { display:flex; flex-direction:column; gap:8px; margin-bottom:16px; }
.full-width { grid-column: 1 / -1; }
.field-label { font-size:13px; font-weight:700; color:#0f172a; }
.field-input { width:100%; min-height:44px; padding:10px 12px; border:1px solid #d1d5db; border-radius:12px; background:#f8fafc; color:#0f172a; font-size:14px; }
.field-input:focus { outline:none; border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.12); }
.field-error { color:#dc2626; font-size:12px; }
.field-checkbox { flex-direction:row; align-items:center; }
.checkbox-label { display:flex; align-items:center; gap:10px; font-size:14px; font-weight:600; color:#0f172a; }
.checkbox-label input { width:16px; height:16px; }
.form-help { margin-bottom:16px; color:#475569; font-size:13px; }
.form-help ul { margin:8px 0 0 18px; padding:0; }
.form-help li { margin-bottom:6px; }
.form-actions { display:flex; gap:12px; flex-wrap:wrap; margin-top:8px; }
.action-btn { display:inline-flex; align-items:center; justify-content:center; min-height:44px; padding:0 18px; border-radius:12px; border:1px solid #e5e7eb; background:#fff; color:#0f172a; text-decoration:none; font-size:14px; font-weight:700; }
.action-btn.primary { background:#0f2a5a; color:#fff; border-color:#0f2a5a; }
.action-btn.secondary { background:#fff; color:#0f172a; }
@media (max-width: 1100px) { .criteria-grid { grid-template-columns: 1fr; } }
</style>

<script>
(function () {
    const typeSelect = document.getElementById('type');
    const fixedPointRow = document.getElementById('fixed_point_row');
    const deductPointRow = document.getElementById('deduct_point_row');
    const maxTimesRow = document.getElementById('max_times_row');
    const maxPointRow = document.getElementById('max_point_row');
    const fixedPointInput = document.getElementById('fixed_point');
    const maxTimesInput = document.getElementById('max_times');
    const maxPointInput = document.getElementById('max_point');

    function safeNumber(value) {
        const parsed = parseFloat(value);
        return Number.isFinite(parsed) ? parsed : 0;
    }

    function updateVisibility() {
        const type = typeSelect ? typeSelect.value : 'CONG_THEO_LAN';

        fixedPointRow.style.display = type === 'CO_DINH' ? 'none' : 'flex';
        deductPointRow.style.display = type === 'TRU_THEO_LAN' ? 'flex' : 'none';
        maxTimesRow.style.display = type !== 'CO_DINH' ? 'flex' : 'none';
        maxPointRow.style.display = 'flex';

        if (type === 'CONG_THEO_LAN') {
            maxPointInput.readOnly = true;
            computeMaxPoint();
        } else {
            maxPointInput.readOnly = false;
        }
    }

    function computeMaxPoint() {
        if (!fixedPointInput || !maxTimesInput || !maxPointInput) {
            return;
        }

        const fixedPoint = safeNumber(fixedPointInput.value);
        const maxTimes = parseInt(maxTimesInput.value, 10) || 0;
        maxPointInput.value = (fixedPoint * maxTimes).toFixed(2);
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', updateVisibility);
    }

    if (fixedPointInput) {
        fixedPointInput.addEventListener('input', function () {
            if (typeSelect && typeSelect.value === 'CONG_THEO_LAN') {
                computeMaxPoint();
            }
        });
    }

    const categoryYearSelect = document.getElementById('category_year_id');
    const categorySemesterSelect = document.getElementById('category_semester_id');
    const criteriaYearSelect = document.getElementById('criteria_year_id');
    const criteriaSemesterSelect = document.getElementById('semester_id');
    const criteriaCategorySelect = document.getElementById('category_id');

    function setSelectOptions(selectElement, options, defaultText) {
        if (!selectElement) {
            return;
        }

        selectElement.innerHTML = '';
        const emptyOption = document.createElement('option');
        emptyOption.value = '';
        emptyOption.textContent = defaultText;
        selectElement.appendChild(emptyOption);

        options.forEach(option => {
            const optionElement = document.createElement('option');
            optionElement.value = option.value;
            optionElement.textContent = option.label;
            selectElement.appendChild(optionElement);
        });
    }

    async function fetchSemestersByYear(yearId, targetSelect) {
        if (!targetSelect) {
            return;
        }

        setSelectOptions(targetSelect, [], '-- Chọn học kỳ --');

        if (!yearId) {
            return;
        }

        try {
            const response = await fetch('/KhoaLuan/public/admin.php?page=ajax_semesters_by_academic_year&MA_NIEN_KHOA=' + encodeURIComponent(yearId));
            if (!response.ok) {
                throw new Error('Lỗi khi tải học kỳ');
            }
            const semesters = await response.json();
            const semesterOptions = semesters.map(semester => ({
                value: semester.MA_HOC_KY || semester.id || '',
                label: semester.TEN_HOC_KY || semester.name || 'Không xác định',
            }));
            setSelectOptions(targetSelect, semesterOptions, '-- Chọn học kỳ --');
        } catch (error) {
            console.error(error);
        }
    }

    async function fetchCategoriesBySemester(semesterId) {
        if (!criteriaCategorySelect) {
            return;
        }

        setSelectOptions(criteriaCategorySelect, [], '-- Chọn danh mục --');

        if (!semesterId) {
            return;
        }

        try {
            const response = await fetch('/KhoaLuan/public/admin.php?page=ajax_categories_by_semester&MA_HOC_KY=' + encodeURIComponent(semesterId));
            if (!response.ok) {
                throw new Error('Lỗi khi tải danh mục');
            }
            const categories = await response.json();
            const categoryOptions = categories.map(category => ({
                value: category.id || category.MA_DANH_MUC || '',
                label: (category.name || category.TEN_DANH_MUC || 'Không xác định') + ' (' + (category.max_points || category.DIEM_TOI_DA_MUC || 0) + ' điểm)',
            }));
            setSelectOptions(criteriaCategorySelect, categoryOptions, '-- Chọn danh mục --');
        } catch (error) {
            console.error(error);
        }
    }

    if (categoryYearSelect && categorySemesterSelect) {
        categoryYearSelect.addEventListener('change', function () {
            fetchSemestersByYear(this.value, categorySemesterSelect);
        });
    }

    if (criteriaYearSelect && criteriaSemesterSelect && criteriaCategorySelect) {
        criteriaYearSelect.addEventListener('change', function () {
            fetchSemestersByYear(this.value, criteriaSemesterSelect);
            setSelectOptions(criteriaCategorySelect, [], '-- Chọn danh mục --');
        });

        criteriaSemesterSelect.addEventListener('change', function () {
            fetchCategoriesBySemester(this.value);
        });
    }

    if (maxTimesInput) {
        maxTimesInput.addEventListener('input', function () {
            if (typeSelect && typeSelect.value === 'CONG_THEO_LAN') {
                computeMaxPoint();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateVisibility();

        if (criteriaSemesterSelect && criteriaSemesterSelect.value) {
            fetchCategoriesBySemester(criteriaSemesterSelect.value);
        }
    });
})();
