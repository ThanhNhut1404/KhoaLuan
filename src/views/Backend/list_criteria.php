<?php
$academicYears = $academicYears ?? [];
$semesters = $semesters ?? [];
$categories = $categories ?? [];
$selectedAcademicYearId = isset($selectedAcademicYearId) ? (int) $selectedAcademicYearId : 0;
$selectedSemesterId = isset($selectedSemesterId) ? (int) $selectedSemesterId : 0;
$formData = $formData ?? [];
$errors = $errors ?? [];

function oldValue(array $formData, string $key, string $default = ''): string
{
    return htmlspecialchars((string) ($formData[$key] ?? $default), ENT_QUOTES, 'UTF-8');
}

function errorHtml(array $errors, string $key): string
{
    return !empty($errors[$key]) ? '<div class="invalid-feedback d-block">' . htmlspecialchars($errors[$key], ENT_QUOTES, 'UTF-8') . '</div>' : '';
}
?>

<div class="container-fluid py-4">
    <div class="row align-items-end mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <label for="academicYear" class="form-label">Niên khóa</label>
            <select id="academicYear" name="MA_NIEN_KHOA" class="form-select" form="criteriaFilterForm">
                <option value="">-- Chọn niên khóa --</option>
                <?php foreach ($academicYears as $year): ?>
                    <?php $yearId = (int) ($year['id'] ?? $year['MA_NIEN_KHOA'] ?? 0); ?>
                    <option value="<?= htmlspecialchars((string) $yearId, ENT_QUOTES, 'UTF-8') ?>" <?= $yearId === $selectedAcademicYearId ? 'selected' : '' ?>>
                        <?= htmlspecialchars($year['name'] ?? $year['TEN_NIEN_KHOA'] ?? 'Không xác định', ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <label for="semester" class="form-label">Học kỳ</label>
            <select id="semester" name="MA_HOC_KY" class="form-select" form="criteriaFilterForm" <?= $selectedAcademicYearId ? '' : 'disabled' ?>>
                <option value="">-- Chọn học kỳ --</option>
                <?php foreach ($semesters as $semester): ?>
                    <?php $semesterId = (int) ($semester['MA_HOC_KY'] ?? $semester['id'] ?? 0); ?>
                    <option value="<?= htmlspecialchars((string) $semesterId, ENT_QUOTES, 'UTF-8') ?>" <?= $semesterId === $selectedSemesterId ? 'selected' : '' ?>>
                        <?= htmlspecialchars($semester['TEN_HOC_KY'] ?? $semester['name'] ?? 'Không xác định', ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4 text-md-end">
            <button type="button" class="btn btn-primary w-100 mt-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                + Thêm danh mục lớn
            </button>
        </div>
    </div>

    <form id="criteriaFilterForm" method="GET" action="/KhoaLuan/public/admin.php">
        <input type="hidden" name="page" value="list_criteria" />
    </form>

    <div class="accordion" id="categoryAccordion">
        <?php if (empty($categories)): ?>
            <div class="alert alert-info">Chưa có danh mục nào. Hãy thêm danh mục lớn trước khi tạo tiêu chí con.</div>
        <?php else: ?>
            <?php foreach ($categories as $index => $category): ?>
                <?php $categoryId = (int) ($category['id'] ?? $category['MA_DANH_MUC'] ?? 0); ?>
                <?php $categoryName = htmlspecialchars($category['name'] ?? $category['TEN_DANH_MUC'] ?? 'Danh mục chưa tên', ENT_QUOTES, 'UTF-8'); ?>
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="heading<?= $categoryId ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $categoryId ?>" aria-expanded="false" aria-controls="collapse<?= $categoryId ?>">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div>
                                    <div class="fw-bold"><?= $categoryName ?></div>
                                    <div class="text-muted small">Điểm tối đa: <?= htmlspecialchars((string) ($category['DIEM_TOI_DA_MUC'] ?? $category['max_points'] ?? '0'), ENT_QUOTES, 'UTF-8') ?></div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm addCriteriaBtn" data-category-id="<?= htmlspecialchars((string) $categoryId, ENT_QUOTES, 'UTF-8') ?>" data-category-name="<?= $categoryName ?>" data-bs-toggle="modal" data-bs-target="#addCriteriaModal">
                                    + Thêm tiêu chí con
                                </button>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse<?= $categoryId ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $categoryId ?>" data-bs-parent="#categoryAccordion">
                        <div class="accordion-body">
                            <?php $criteriaList = $category['criteria'] ?? []; ?>
                            <?php if (empty($criteriaList)): ?>
                                <div class="alert alert-secondary mb-0">Danh mục này chưa có tiêu chí con.</div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tiêu chí</th>
                                                <th>Loại tiêu chí</th>
                                                <th>Điểm cố định</th>
                                                <th>Lần tối đa</th>
                                                <th>Điểm tối đa</th>
                                                <th>Hoạt động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($criteriaList as $criteria): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($criteria['TEN_TIEU_CHI'] ?? $criteria['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td><?= htmlspecialchars($criteria['LOAI_TIEU_CHI'] ?? $criteria['type'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td><?= htmlspecialchars((string) ($criteria['DIEM_CO_DINH'] ?? '0'), ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td><?= htmlspecialchars((string) ($criteria['LAN_THUC_HIEN_TOI_DA'] ?? '0'), ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td><?= htmlspecialchars((string) ($criteria['DIEM_TOI_DA_TIEU_CHI'] ?? $criteria['DIEM_TOI_DA'] ?? '0'), ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td><?= !empty($criteria['use_for_activity']) && $criteria['use_for_activity'] !== '0' ? 'Có' : (!empty($criteria['IS_HOAT_DONG']) && $criteria['IS_HOAT_DONG'] !== '0' ? 'Có' : 'Không') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Thêm danh mục lớn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form method="POST" action="/KhoaLuan/public/admin.php?page=list_criteria">
                <input type="hidden" name="action" value="save_category" />
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="TEN_DANH_MUC" class="form-label">Tên danh mục</label>
                        <input id="TEN_DANH_MUC" name="TEN_DANH_MUC" type="text" class="form-control" value="<?= oldValue($formData, 'TEN_DANH_MUC') ?>" required />
                        <?= errorHtml($errors, 'TEN_DANH_MUC') ?>
                    </div>
                    <div class="mb-3">
                        <label for="DIEM_TOI_DA_MUC" class="form-label">Điểm tối đa danh mục</label>
                        <input id="DIEM_TOI_DA_MUC" name="DIEM_TOI_DA_MUC" type="number" step="0.01" min="0" class="form-control" value="<?= oldValue($formData, 'DIEM_TOI_DA_MUC') ?>" required />
                        <?= errorHtml($errors, 'DIEM_TOI_DA_MUC') ?>
                    </div>
                    <div class="mb-3">
                        <label for="THU_TU_HIEN_THI" class="form-label">Thứ tự hiển thị</label>
                        <input id="THU_TU_HIEN_THI" name="THU_TU_HIEN_THI" type="number" min="0" class="form-control" value="<?= oldValue($formData, 'THU_TU_HIEN_THI', '0') ?>" />
                        <?= errorHtml($errors, 'THU_TU_HIEN_THI') ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu danh mục</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addCriteriaModal" tabindex="-1" aria-labelledby="addCriteriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCriteriaModalLabel">Thêm tiêu chí con</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form method="POST" action="/KhoaLuan/public/admin.php?page=list_criteria">
                <input type="hidden" name="action" value="save_criteria" />
                <input type="hidden" id="MA_DANH_MUC" name="MA_DANH_MUC" value="<?= oldValue($formData, 'MA_DANH_MUC') ?>" />
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Danh mục cha</label>
                        <input type="text" id="categoryNameReadonly" class="form-control" value="<?= oldValue($formData, 'category_name') ?>" readonly />
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="TEN_TIEU_CHI" class="form-label">Tên tiêu chí</label>
                            <input id="TEN_TIEU_CHI" name="TEN_TIEU_CHI" type="text" class="form-control" value="<?= oldValue($formData, 'TEN_TIEU_CHI') ?>" required />
                            <?= errorHtml($errors, 'TEN_TIEU_CHI') ?>
                        </div>
                        <div class="col-md-6">
                            <label for="LOAI_TIEU_CHI" class="form-label">Loại tiêu chí</label>
                            <select id="LOAI_TIEU_CHI" name="LOAI_TIEU_CHI" class="form-select" required>
                                <?php $typeOptions = ['CONG_THEO_LAN' => 'Cộng theo lần', 'TRU_THEO_LAN' => 'Trừ theo lần', 'CO_DINH' => 'Cố định']; ?>
                                <?php foreach ($typeOptions as $value => $label): ?>
                                    <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" <?= oldValue($formData, 'LOAI_TIEU_CHI', 'CONG_THEO_LAN') === $value ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= errorHtml($errors, 'LOAI_TIEU_CHI') ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="MO_TA_TIEU_CHI" class="form-label">Mô tả tiêu chí</label>
                        <textarea id="MO_TA_TIEU_CHI" name="MO_TA_TIEU_CHI" rows="4" class="form-control"><?= oldValue($formData, 'MO_TA_TIEU_CHI') ?></textarea>
                        <?= errorHtml($errors, 'MO_TA_TIEU_CHI') ?>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="DIEM_CO_DINH" class="form-label">Điểm cố định</label>
                            <input id="DIEM_CO_DINH" name="DIEM_CO_DINH" type="number" step="0.01" min="0" class="form-control" value="<?= oldValue($formData, 'DIEM_CO_DINH') ?>" />
                            <?= errorHtml($errors, 'DIEM_CO_DINH') ?>
                        </div>
                        <div class="col-md-4">
                            <label for="LAN_THUC_HIEN_TOI_DA" class="form-label">Lần thực hiện tối đa</label>
                            <input id="LAN_THUC_HIEN_TOI_DA" name="LAN_THUC_HIEN_TOI_DA" type="number" min="0" class="form-control" value="<?= oldValue($formData, 'LAN_THUC_HIEN_TOI_DA') ?>" />
                            <?= errorHtml($errors, 'LAN_THUC_HIEN_TOI_DA') ?>
                        </div>
                        <div class="col-md-4">
                            <label for="DIEM_TOI_DA_TIEU_CHI" class="form-label">Điểm tối đa tiêu chí</label>
                            <input id="DIEM_TOI_DA_TIEU_CHI" name="DIEM_TOI_DA_TIEU_CHI" type="number" step="0.01" min="0" class="form-control" value="<?= oldValue($formData, 'DIEM_TOI_DA_TIEU_CHI') ?>" readonly />
                            <?= errorHtml($errors, 'DIEM_TOI_DA_TIEU_CHI') ?>
                        </div>
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" id="use_for_activity" name="use_for_activity" value="1" <?= !empty($formData['use_for_activity']) ? 'checked' : '' ?> />
                        <label class="form-check-label" for="use_for_activity">Sử dụng tiêu chí cho tạo hoạt động</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu tiêu chí</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    const addCriteriaButtons = document.querySelectorAll('.addCriteriaBtn');
    const categoryIdInput = document.getElementById('MA_DANH_MUC');
    const categoryNameReadonly = document.getElementById('categoryNameReadonly');
    const criteriaType = document.getElementById('LOAI_TIEU_CHI');
    const fixedPointInput = document.getElementById('DIEM_CO_DINH');
    const maxTimesInput = document.getElementById('LAN_THUC_HIEN_TOI_DA');
    const maxPointInput = document.getElementById('DIEM_TOI_DA_TIEU_CHI');

    function updateMaxPoint() {
        if (!criteriaType || !fixedPointInput || !maxTimesInput || !maxPointInput) {
            return;
        }

        if (criteriaType.value === 'CONG_THEO_LAN') {
            const fixedPoint = parseFloat(fixedPointInput.value) || 0;
            const times = parseInt(maxTimesInput.value, 10) || 0;
            maxPointInput.value = (fixedPoint * times).toFixed(2);
        }
    }

    addCriteriaButtons.forEach(button => {
        button.addEventListener('click', function () {
            const categoryId = this.dataset.categoryId || '';
            const categoryName = this.dataset.categoryName || '';
            if (categoryIdInput) {
                categoryIdInput.value = categoryId;
            }
            if (categoryNameReadonly) {
                categoryNameReadonly.value = categoryName;
            }
        });
    });

    if (criteriaType) {
        criteriaType.addEventListener('change', updateMaxPoint);
    }
    if (fixedPointInput) {
        fixedPointInput.addEventListener('input', updateMaxPoint);
    }
    if (maxTimesInput) {
        maxTimesInput.addEventListener('input', updateMaxPoint);
    }

    document.addEventListener('DOMContentLoaded', updateMaxPoint);
})();
</script>
