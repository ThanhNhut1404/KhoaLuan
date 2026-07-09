<?php
// Page: Thiết lập tiêu chí
$title = 'THIẾT LẬP BỘ TIÊU CHÍ';
$academicYears = $academicYears ?? [];
$semesters = $semesters ?? [];
$categories = $categories ?? [];
$criteriaByCategory = $criteriaByCategory ?? [];
$selectedAcademicYearId = isset($selectedAcademicYearId) ? (int) $selectedAcademicYearId : 0;
$selectedSemesterId = isset($selectedSemesterId) ? (int) $selectedSemesterId : 0;
$formData = $formData ?? [];
$errors = $errors ?? [];
$masterTemplates = $masterTemplates ?? [];
$selectedTemplateId = isset($selectedTemplateId) ? (int) $selectedTemplateId : 0;

function oldValue(array $formData, string $key, string $default = ''): string
{
    return htmlspecialchars((string) ($formData[$key] ?? $default), ENT_QUOTES, 'UTF-8');
}

function errorHtml(array $errors, string $key): string
{
    return !empty($errors[$key]) ? '<div class="text-danger" style="margin-top:4px;font-size:12px;">' . htmlspecialchars($errors[$key], ENT_QUOTES, 'UTF-8') . '</div>' : '';
}
?>

<div class="create-year-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h3 class="panel-title">THIẾT LẬP BỘ TIÊU CHÍ</h3>
        </div>
        <div class="panel-body card-body">
            <div class="toolbar-wrapper mb-3">
                    <form method="GET" action="/KhoaLuan/public/admin.php" class="setup-criteria-form">
                        <input type="hidden" name="page" value="setup_criteria">
                        <div class="toolbar-grid">
                            <div class="toolbar-field">
                                <label class="field-label form-label" for="template_id">Bộ tiêu chí mẫu</label>
                                <select id="template_id" name="template_id" class="field-input form-select" onchange="this.form.submit()">
                                    <option value="">-- Chọn bộ tiêu chí mẫu --</option>
                                    <?php foreach ($masterTemplates as $template): ?>
                                        <?php $templateOptionId = (int) ($template['id'] ?? $template['MA_BO_MAU'] ?? 0); ?>
                                        <option value="<?= htmlspecialchars((string) $templateOptionId, ENT_QUOTES, 'UTF-8') ?>" <?= $templateOptionId === $selectedTemplateId ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($template['name'] ?? $template['TEN_BO_MAU'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="toolbar-actions">
                                <button type="button" class="action-btn primary btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTemplateModal">Tạo bộ tiêu chí</button>
                                <?php if ($selectedTemplateId > 0): ?>
                                    <button type="button" class="action-btn secondary btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Thêm danh mục lớn</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
            </div>

            <div class="template-actions-row mb-3">
                <div>
                    <?php if ($selectedTemplateId > 0): ?>
                        <span class="text-muted">Quản lý bộ tiêu chí mẫu, danh mục và tiêu chí con.</span>
                    <?php endif; ?>
                </div>
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <?php if ($selectedTemplateId > 0): ?>
                        <button id="editTemplateTrigger" type="button" class="btn btn-sm btn-outline-secondary" data-template-id="<?= htmlspecialchars((string) $selectedTemplateId, ENT_QUOTES, 'UTF-8') ?>" data-template-name="<?= htmlspecialchars((string) ($selectedTemplate['name'] ?? $selectedTemplate['TEN_BO_MAU'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-template-description="<?= htmlspecialchars((string) ($selectedTemplate['description'] ?? $selectedTemplate['MO_TA'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-template-status="<?= htmlspecialchars((string) ($selectedTemplate['status'] ?? $selectedTemplate['TRANG_THAI'] ?? 1), ENT_QUOTES, 'UTF-8') ?>" data-bs-toggle="modal" data-bs-target="#editTemplateModal">Sửa bộ mẫu</button>
                        <form method="POST" action="/KhoaLuan/public/admin.php?page=setup_criteria" class="d-inline">
                            <input type="hidden" name="form_type" value="template_master_lock">
                            <input type="hidden" name="template_id" value="<?= htmlspecialchars((string) $selectedTemplateId, ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" class="btn btn-sm btn-warning text-dark">Khóa bộ mẫu</button>
                        </form>
                        <form method="POST" action="/KhoaLuan/public/admin.php?page=setup_criteria" class="d-inline">
                            <input type="hidden" name="form_type" value="template_master_delete">
                            <input type="hidden" name="template_id" value="<?= htmlspecialchars((string) $selectedTemplateId, ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Xóa bộ mẫu</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($selectedTemplateId > 0): ?>
                <?php
                    $templateName = '';
                    foreach ($masterTemplates as $t) {
                        $tid = (int)($t['id'] ?? $t['MA_BO_MAU'] ?? 0);
                        if ($tid === $selectedTemplateId) { $templateName = $t['name'] ?? $t['TEN_BO_MAU'] ?? ''; break; }
                    }

                    $totalScore = 0;
                    foreach ($categories as $c) {
                        $totalScore += (float) ($c['max_points'] ?? $c['DIEM_TOI_DA_MUC'] ?? 0);
                    }
                ?>
                <div class="template-summary-card card mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                            <div>
                                <div class="summary-title">Bộ tiêu chí: <?= htmlspecialchars($templateName, ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="summary-meta">
                                    <?php $templateStatusValue = (int) ($selectedTemplate['status'] ?? $selectedTemplate['TRANG_THAI'] ?? 1); ?>
                                    <span class="badge rounded-pill <?= $templateStatusValue === 1 ? 'bg-success' : 'bg-warning text-dark' ?>"><?= $templateStatusValue === 1 ? 'Hoạt động' : 'Khóa' ?></span>
                                    <span class="text-muted ms-2">Tổng điểm: <strong><?= number_format($totalScore, 2) ?> / 100</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (empty($categories)): ?>
                    <div class="alert alert-info">Bộ tiêu chí này chưa có danh mục lớn. Hãy thêm danh mục lớn đầu tiên.</div>
                <?php else: ?>
                    <?php
                        function toRoman(int $num): string {
                            $map = [1000=>'M',900=>'CM',500=>'D',400=>'CD',100=>'C',90=>'XC',50=>'L',40=>'XL',10=>'X',9=>'IX',5=>'V',4=>'IV',1=>'I'];
                            $ret = '';
                            foreach ($map as $n => $r) { while ($num >= $n) { $ret .= $r; $num -= $n; } }
                            return $ret;
                        }
                    ?>
                    <div class="row">
                        <?php foreach ($categories as $index => $category): ?>
                            <?php $categoryId = (int) ($category['id'] ?? $category['MA_DANH_MUC'] ?? 0); ?>
                            <?php $categoryName = htmlspecialchars($category['name'] ?? $category['TEN_DANH_MUC'] ?? 'Danh mục chưa tên', ENT_QUOTES, 'UTF-8'); ?>
                            <?php $items = $criteriaByCategory[$categoryId] ?? []; ?>
                            <div class="col-12 mb-3">
                                <div class="card category-card shadow-sm">
                                    <div class="card-header category-card-header">
                                        <div class="category-card-heading">
                                            <div class="category-badge"><?= toRoman($index + 1) ?></div>
                                            <div>
                                                <div class="category-title"><?= $categoryName ?></div>
                                                <div class="category-meta">
                                                    <span class="text-muted">Tối đa: <?= htmlspecialchars((string) ($category['max_points'] ?? $category['DIEM_TOI_DA_MUC'] ?? '0'), ENT_QUOTES, 'UTF-8') ?> điểm</span>
                                                    <span class="dot-divider"></span>
                                                    <span class="text-muted"><?= count($items) ?> tiêu chí con</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="category-actions">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editCategoryModal_<?= htmlspecialchars((string) $categoryId, ENT_QUOTES, 'UTF-8') ?>">Sửa danh mục</button>
                                            <form method="POST" action="/KhoaLuan/public/admin.php?page=setup_criteria" class="d-inline">
                                                <input type="hidden" name="form_type" value="template_category_delete">
                                                <input type="hidden" name="template_id" value="<?= htmlspecialchars((string) $selectedTemplateId, ENT_QUOTES, 'UTF-8') ?>">
                                                <input type="hidden" name="category_id" value="<?= htmlspecialchars((string) $categoryId, ENT_QUOTES, 'UTF-8') ?>">
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Xóa danh mục</button>
                                            </form>
                                            <button class="btn btn-sm btn-primary addSubCriteriaButton" type="button" data-category-id="<?= htmlspecialchars((string) $categoryId, ENT_QUOTES, 'UTF-8') ?>" data-category-name="<?= $categoryName ?>" data-bs-toggle="modal" data-bs-target="#addCriteriaModal">Thêm tiêu chí con</button>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <?php if (empty($items)): ?>
                                            <div class="alert alert-secondary mb-0 rounded-0 border-0">Danh mục này chưa có tiêu chí con.</div>
                                        <?php else: ?>
                                            <div class="table-responsive">
                                                <table class="table data-table table-hover align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="width:60px;">STT</th>
                                                            <th>Tên tiêu chí</th>
                                                            <th>Loại tính điểm</th>
                                                            <th>Điểm cộng</th>
                                                            <th>Điểm trừ</th>
                                                            <th>Điểm tối đa</th>
                                                            <th>Số lần tối đa</th>
                                                            <th>Dùng tạo hoạt động</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($items as $itemIndex => $item): ?>
                                                            <?php $useForActivity = isset($item['use_for_activity']) ? (int)$item['use_for_activity'] : 0; ?>
                                                            <tr>
                                                                <td><?= $itemIndex + 1 ?></td>
                                                                <td><?= htmlspecialchars($item['TEN_TIEU_CHI'] ?? $item['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                                                <td><?= htmlspecialchars($item['LOAI_TIEU_CHI'] ?? $item['type'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                                                <td><?= htmlspecialchars((string) ($item['DIEM_CONG'] ?? $item['add_point'] ?? '0'), ENT_QUOTES, 'UTF-8') ?></td>
                                                                <td><?= htmlspecialchars((string) ($item['DIEM_TRU'] ?? $item['deduct_point'] ?? '0'), ENT_QUOTES, 'UTF-8') ?></td>
                                                                <td><?= htmlspecialchars((string) ($item['DIEM_TOI_DA_TC'] ?? $item['max_point'] ?? '0'), ENT_QUOTES, 'UTF-8') ?> điểm</td>
                                                                <td><?= htmlspecialchars((string) ($item['LAN_THUC_HIEN_TOI_DA'] ?? $item['max_times'] ?? '0'), ENT_QUOTES, 'UTF-8') ?></td>
                                                                <td>
                                                                    <div class="criteria-actions">
                                                                        <span class="badge rounded-pill <?= $useForActivity === 1 ? 'bg-success' : 'bg-secondary' ?>"><?= $useForActivity === 1 ? 'Có' : 'Không' ?></span>
                                                                        <button class="btn btn-xs btn-outline-secondary editCriteriaButton" type="button" data-bs-target="#editCriteriaModal" data-criteria-id="<?= htmlspecialchars((string) ($item['id'] ?? $item['MA_TIEU_CHI'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" data-template-id="<?= htmlspecialchars((string) $selectedTemplateId, ENT_QUOTES, 'UTF-8') ?>" data-category-id="<?= htmlspecialchars((string) ($item['category_id'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" data-category-name="<?= htmlspecialchars((string) ($category['name'] ?? $category['TEN_DANH_MUC'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-name="<?= htmlspecialchars((string) ($item['TEN_TIEU_CHI'] ?? $item['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-description="<?= htmlspecialchars((string) ($item['MO_TA_TIEU_CHI'] ?? $item['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" data-type="<?= htmlspecialchars((string) ($item['LOAI_TIEU_CHI'] ?? $item['type'] ?? 'CONG_THEO_LAN'), ENT_QUOTES, 'UTF-8') ?>" data-add-point="<?= htmlspecialchars((string) ($item['DIEM_CONG'] ?? $item['add_point'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" data-deduct-point="<?= htmlspecialchars((string) ($item['DIEM_TRU'] ?? $item['deduct_point'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" data-max-point="<?= htmlspecialchars((string) ($item['DIEM_TOI_DA_TC'] ?? $item['max_point'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" data-max-times="<?= htmlspecialchars((string) ($item['LAN_THUC_HIEN_TOI_DA'] ?? $item['max_times'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" data-use-for-activity="<?= htmlspecialchars((string) ($item['SU_DUNG_CHO_HOAT_DONG'] ?? $item['use_for_activity'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" data-bs-toggle="modal">Sửa</button>
                                                                        <form method="POST" action="/KhoaLuan/public/admin.php?page=setup_criteria" class="d-inline">
                                                                            <input type="hidden" name="form_type" value="template_criteria_delete">
                                                                            <input type="hidden" name="template_id" value="<?= htmlspecialchars((string) $selectedTemplateId, ENT_QUOTES, 'UTF-8') ?>">
                                                                            <input type="hidden" name="criteria_id" value="<?= htmlspecialchars((string) ($item['id'] ?? $item['MA_TIEU_CHI'] ?? 0), ENT_QUOTES, 'UTF-8') ?>">
                                                                            <button class="btn btn-xs btn-outline-danger" type="submit">Xóa</button>
                                                                        </form>
                                                                    </div>
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
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="addTemplateModal" tabindex="-1" aria-labelledby="addTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTemplateModalLabel">Tạo bộ tiêu chí mẫu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form method="POST" action="/KhoaLuan/public/admin.php?page=setup_criteria">
                <input type="hidden" name="form_type" value="template_master" />
                <input type="hidden" name="semester_id" value="<?= htmlspecialchars((string) $selectedSemesterId, ENT_QUOTES, 'UTF-8') ?>" />
                <input type="hidden" name="MA_NIEN_KHOA" value="<?= htmlspecialchars((string) $selectedAcademicYearId, ENT_QUOTES, 'UTF-8') ?>" />
                <input type="hidden" name="template_id" value="<?= htmlspecialchars((string) $selectedTemplateId, ENT_QUOTES, 'UTF-8') ?>" />
                <div class="modal-body" style="padding:20px;">
                    <div class="mb-3">
                        <label for="template_name" class="form-label">Tên bộ tiêu chí</label>
                        <input id="template_name" name="template_name" type="text" class="form-control" value="<?= oldValue($formData, 'template_name') ?>" required />
                        <?= errorHtml($errors, 'template_name') ?>
                    </div>
                    <div class="mb-3">
                        <label for="template_description" class="form-label">Mô tả</label>
                        <textarea id="template_description" name="template_description" rows="4" class="form-control"><?= oldValue($formData, 'template_description') ?></textarea>
                        <?= errorHtml($errors, 'template_description') ?>
                    </div>
                    <div class="mb-3">
                        <label for="template_status" class="form-label">Trạng thái</label>
                        <select id="template_status" name="template_status" class="form-select">
                            <option value="1" <?= oldValue($formData, 'template_status', '1') === '1' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= oldValue($formData, 'template_status', '1') === '0' ? 'selected' : '' ?>>Khóa</option>
                        </select>
                        <?= errorHtml($errors, 'template_status') ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu bộ tiêu chí</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Thêm danh mục lớn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form method="POST" action="/KhoaLuan/public/admin.php?page=setup_criteria">
                <input type="hidden" name="form_type" value="template_category" />
                <input type="hidden" name="template_id" value="<?= htmlspecialchars((string) $selectedTemplateId, ENT_QUOTES, 'UTF-8') ?>" />
                <input type="hidden" name="semester_id" value="<?= htmlspecialchars((string) $selectedSemesterId, ENT_QUOTES, 'UTF-8') ?>" />
                <input type="hidden" name="MA_NIEN_KHOA" value="<?= htmlspecialchars((string) $selectedAcademicYearId, ENT_QUOTES, 'UTF-8') ?>" />
                <div class="modal-body" style="padding:20px;">
                    <div class="mb-3">
                        <label for="TEN_DANH_MUC" class="form-label">Tên danh mục</label>
                        <input id="TEN_DANH_MUC" name="category_name" type="text" class="form-control" value="<?= oldValue($formData, 'category_name') ?>" required />
                        <?= errorHtml($errors, 'category_name') ?>
                    </div>
                    <div class="mb-3">
                        <label for="DIEM_TOI_DA_MUC" class="form-label">Điểm tối đa danh mục</label>
                        <input id="DIEM_TOI_DA_MUC" name="category_max_points" type="number" step="0.01" min="0" class="form-control" value="<?= oldValue($formData, 'category_max_points') ?>" required />
                        <?= errorHtml($errors, 'category_max_points') ?>
                    </div>
                    <div class="mb-3">
                        <label for="THU_TU_HIEN_THI" class="form-label">Thứ tự hiển thị</label>
                        <input id="THU_TU_HIEN_THI" name="category_display_order" type="number" min="0" class="form-control" value="<?= oldValue($formData, 'category_display_order', '0') ?>" />
                        <?= errorHtml($errors, 'category_display_order') ?>
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
            <form id="addCriteriaForm" method="POST" action="/KhoaLuan/public/admin.php?page=setup_criteria">
                <input type="hidden" name="form_type" value="template_criteria" />
                <input type="hidden" name="template_id" value="<?= htmlspecialchars((string) $selectedTemplateId, ENT_QUOTES, 'UTF-8') ?>" />
                <input type="hidden" id="criteriaCategoryId" name="category_id" value="<?= oldValue($formData, 'category_id') ?>" />
                <input type="hidden" id="criteriaDisplayOrder" name="display_order" value="<?= oldValue($formData, 'display_order', '0') ?>" />
                <input type="hidden" id="criteriaStatus" name="status" value="<?= oldValue($formData, 'status', '1') ?>" />
                <div class="modal-body" style="padding:20px;">
                    <div class="mb-3">
                        <label class="form-label">Danh mục cha</label>
                        <input type="text" id="criteriaCategoryNameReadonly" class="form-control" value="<?= oldValue($formData, 'category_name') ?>" readonly />
                    </div>
                    <div class="mb-3">
                        <label for="TEN_TIEU_CHI" class="form-label">Tên tiêu chí</label>
                        <input id="TEN_TIEU_CHI" name="name" type="text" class="form-control" value="<?= oldValue($formData, 'name') ?>" required />
                        <?= errorHtml($errors, 'name') ?>
                    </div>
                    <div class="mb-3">
                        <label for="MO_TA_TIEU_CHI" class="form-label">Mô tả tiêu chí</label>
                        <textarea id="MO_TA_TIEU_CHI" name="description" rows="3" class="form-control"><?= oldValue($formData, 'description') ?></textarea>
                        <?= errorHtml($errors, 'description') ?>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="DIEM_CO_DINH" class="form-label">Điểm cố định</label>
                            <input id="DIEM_CO_DINH" name="fixed_point" type="number" step="0.01" min="0" class="form-control" value="<?= oldValue($formData, 'fixed_point') ?>" />
                            <?= errorHtml($errors, 'fixed_point') ?>
                        </div>
                        <div class="col-md-4">
                            <label for="add_point" class="form-label">Điểm cộng mỗi lần</label>
                            <input id="add_point" name="add_point" type="number" step="0.01" min="0" class="form-control" value="<?= oldValue($formData, 'add_point') ?>" />
                            <?= errorHtml($errors, 'add_point') ?>
                        </div>
                        <div class="col-md-4">
                            <label for="deduct_point" class="form-label">Điểm trừ mỗi lần</label>
                            <input id="deduct_point" name="deduct_point" type="number" step="0.01" min="0" class="form-control" value="<?= oldValue($formData, 'deduct_point') ?>" />
                            <?= errorHtml($errors, 'deduct_point') ?>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="LAN_THUC_HIEN_TOI_DA" class="form-label">Lần thực hiện tối đa</label>
                            <input id="LAN_THUC_HIEN_TOI_DA" name="max_times" type="number" min="0" class="form-control" value="<?= oldValue($formData, 'max_times') ?>" />
                            <?= errorHtml($errors, 'max_times') ?>
                        </div>
                        <div class="col-md-4">
                            <label for="DIEM_TOI_DA_TIEU_CHI" class="form-label">Điểm tối đa tiêu chí</label>
                            <input id="DIEM_TOI_DA_TIEU_CHI" name="max_point" type="number" step="0.01" min="0" class="form-control" value="<?= oldValue($formData, 'max_point') ?>" readonly />
                            <?= errorHtml($errors, 'max_point') ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="LOAI_TIEU_CHI" class="form-label">Loại tiêu chí</label>
                        <select id="LOAI_TIEU_CHI" name="type" class="form-select">
                            <?php $typeOptions = ['CONG_THEO_LAN' => 'Cộng theo lần', 'TRU_THEO_LAN' => 'Trừ theo lần', 'CO_DINH' => 'Cố định']; ?>
                            <?php foreach ($typeOptions as $value => $label): ?>
                                <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" <?= oldValue($formData, 'type', 'CONG_THEO_LAN') === $value ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= errorHtml($errors, 'type') ?>
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

<?php if ($selectedTemplateId > 0): ?>
<div class="modal fade" id="editTemplateModal" tabindex="-1" aria-labelledby="editTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTemplateModalLabel">Sửa bộ tiêu chí mẫu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form method="POST" action="/KhoaLuan/public/admin.php?page=setup_criteria">
                <input type="hidden" name="form_type" value="template_master_update">
                <input type="hidden" name="template_id" value="<?= htmlspecialchars((string) $selectedTemplateId, ENT_QUOTES, 'UTF-8') ?>">
                <div class="modal-body" style="padding:20px;">
                    <div class="mb-3">
                        <label class="form-label">Tên bộ tiêu chí</label>
                        <input name="template_name" type="text" class="form-control" value="<?= htmlspecialchars((string) ($selectedTemplate['name'] ?? $selectedTemplate['TEN_BO_MAU'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="template_description" rows="4" class="form-control"><?= htmlspecialchars((string) ($selectedTemplate['description'] ?? $selectedTemplate['MO_TA'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="template_status" class="form-select">
                            <option value="1" <?= ((int) ($selectedTemplate['status'] ?? $selectedTemplate['TRANG_THAI'] ?? 1)) === 1 ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= ((int) ($selectedTemplate['status'] ?? $selectedTemplate['TRANG_THAI'] ?? 1)) === 0 ? 'selected' : '' ?>>Khóa</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php foreach ($categories as $category): ?>
<?php $categoryId = (int) ($category['id'] ?? $category['MA_DANH_MUC'] ?? 0); ?>
<div class="modal fade" id="editCategoryModal_<?= htmlspecialchars((string) $categoryId, ENT_QUOTES, 'UTF-8') ?>" tabindex="-1" aria-labelledby="editCategoryModalLabel_<?= htmlspecialchars((string) $categoryId, ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel_<?= htmlspecialchars((string) $categoryId, ENT_QUOTES, 'UTF-8') ?>">Sửa danh mục lớn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form method="POST" action="/KhoaLuan/public/admin.php?page=setup_criteria">
                <input type="hidden" name="form_type" value="template_category_update">
                <input type="hidden" name="template_id" value="<?= htmlspecialchars((string) $selectedTemplateId, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="category_id" value="<?= htmlspecialchars((string) $categoryId, ENT_QUOTES, 'UTF-8') ?>">
                <div class="modal-body" style="padding:20px;">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục</label>
                        <input name="category_name" type="text" class="form-control" value="<?= htmlspecialchars((string) ($category['name'] ?? $category['TEN_DANH_MUC'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Điểm tối đa danh mục</label>
                        <input name="category_max_points" type="number" step="0.01" min="0" class="form-control" value="<?= htmlspecialchars((string) ($category['max_points'] ?? $category['DIEM_TOI_DA_MUC'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thứ tự hiển thị</label>
                        <input name="category_display_order" type="number" min="0" class="form-control" value="<?= htmlspecialchars((string) ($category['display_order'] ?? $category['THU_TU_HIEN_THI'] ?? 0), ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<div class="modal fade" id="editCriteriaModal" tabindex="-1" aria-labelledby="editCriteriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCriteriaModalLabel">Sửa tiêu chí con</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form id="editCriteriaForm" method="POST" action="/KhoaLuan/public/admin.php?page=setup_criteria">
                <input type="hidden" name="form_type" value="template_criteria_update">
                <input type="hidden" id="editCriteriaId" name="criteria_id" value="">
                <input type="hidden" id="editCriteriaTemplateId" name="template_id" value="">
                <input type="hidden" id="editCriteriaCategoryId" name="category_id" value="">
                <div class="modal-body" style="padding:20px;">
                    <div class="mb-3">
                        <label class="form-label">Danh mục cha</label>
                        <input id="editCriteriaCategoryName" type="text" class="form-control" value="" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên tiêu chí</label>
                        <input id="editCriteriaName" name="name" type="text" class="form-control" value="" required>
                        <?= errorHtml($errors, 'name') ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả tiêu chí</label>
                        <textarea id="editCriteriaDescription" name="description" rows="3" class="form-control"></textarea>
                        <?= errorHtml($errors, 'description') ?>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Điểm cố định</label>
                            <input id="editCriteriaFixedPoint" name="fixed_point" type="number" step="0.01" min="0" class="form-control" value="">
                            <?= errorHtml($errors, 'fixed_point') ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Điểm cộng mỗi lần</label>
                            <input id="editCriteriaAddPoint" name="add_point" type="number" step="0.01" min="0" class="form-control" value="">
                            <?= errorHtml($errors, 'add_point') ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Điểm trừ mỗi lần</label>
                            <input id="editCriteriaDeductPoint" name="deduct_point" type="number" step="0.01" min="0" class="form-control" value="">
                            <?= errorHtml($errors, 'deduct_point') ?>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Lần thực hiện tối đa</label>
                            <input id="editCriteriaMaxTimes" name="max_times" type="number" min="0" class="form-control" value="">
                            <?= errorHtml($errors, 'max_times') ?>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Điểm tối đa tiêu chí</label>
                            <input id="editCriteriaMaxPoint" name="max_point" type="number" step="0.01" min="0" class="form-control" value="" readonly>
                            <?= errorHtml($errors, 'max_point') ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loại tiêu chí</label>
                        <select id="editCriteriaType" name="type" class="form-select">
                            <?php $typeOptions = ['CONG_THEO_LAN' => 'Cộng theo lần', 'TRU_THEO_LAN' => 'Trừ theo lần', 'CO_DINH' => 'Cố định']; ?>
                            <?php foreach ($typeOptions as $value => $label): ?>
                                <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= errorHtml($errors, 'type') ?>
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input id="editCriteriaUseForActivity" class="form-check-input" type="checkbox" name="use_for_activity" value="1">
                        <label class="form-check-label">Sử dụng tiêu chí cho tạo hoạt động</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .create-year-page { display: grid; gap: 0; padding: 24px; }
    .page-panel { background: #ffffff; border: 1px solid #e8ecf3; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden; }
    .panel-header { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .panel-title { font-size: 14px; font-weight: 700; color: #0f2a5a; margin: 0; display: flex; align-items: center; gap: 8px; }
    .panel-body { padding: 20px; }
    .toolbar-card { border: 1px solid #e8ecf3; border-radius: 10px; box-shadow: 0 1px 3px rgba(15,42,90,0.06); }
    .toolbar-grid { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 12px; align-items: center; }
    .toolbar-field { min-width: 0; max-width: 420px; }
    .toolbar-actions { display: flex; align-items: center; gap: 8px; justify-content: flex-start; align-self: center; margin-top: 18px; }
    .template-actions-row { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
    .template-summary-card { border: 1px solid #e8ecf3; border-radius: 10px; box-shadow: 0 1px 3px rgba(15,42,90,0.06); }
    .summary-title { font-size: 15px; font-weight: 700; color: #0f2a5a; }
    .summary-meta { margin-top: 6px; display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
    .field-label {
        font-size: 12px;
        font-weight: 700;
        color: #0f2a5a;
        text-transform: none;
        letter-spacing: 0.4px;
        display: block;
        margin-bottom: 6px;
    }
    .field-input {
        display: flex;
        align-items: center;
        padding: 8px 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 13px;
        color: #1f2937;
        height: 40px;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .field-input:focus { outline: none; border-color: #0f2a5a; box-shadow: 0 0 0 3px rgba(15,42,90,0.08); background: #fff; }
    .category-card { border: 1px solid #e8ecf3; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 3px rgba(15,42,90,0.06); }
    .category-card-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 14px 16px; background: #f8fafc; border-bottom: 1px solid #e8ecf3; }
    .category-card-heading { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .category-badge { min-width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; background: linear-gradient(135deg, #0f2a5a 0%, #1d4ed8 100%); color: #fff; font-weight: 700; font-size: 15px; }
    .category-title { font-weight: 700; color: #0f2a5a; line-height: 1.3; }
    .category-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; font-size: 12px; color: #64748b; margin-top: 2px; }
    .dot-divider { width: 4px; height: 4px; border-radius: 50%; background: #cbd5e1; display: inline-block; }
    .category-actions { display: flex; flex-wrap: wrap; gap: 8px; justify-content: flex-end; }
    .table { width:100%; border-collapse: collapse; }
    .table th, .table td { padding: 12px 14px; border-bottom: 1px solid #e8ecf3; text-align:left; color:#334155; font-size:13px; vertical-align: middle; }
    .table thead th { color:#64748b; font-weight:700; background:#f8fafc; }
    .data-table tbody tr:nth-child(odd) { background: #fcfdff; }
    .data-table tbody tr:nth-child(even) { background: #f8fafc; }
    .data-table tbody tr:hover { background: #eef2f7; }
    .criteria-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .btn-xs { padding: 0.2rem 0.45rem; font-size: 0.78rem; line-height: 1.4; border-radius: 0.35rem; }
    .action-btn {
        padding: 8px 20px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #0f2a5a;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .action-btn:hover { background: #f3f4f6; border-color: #d1d5db; }

    .action-btn.secondary { color: #0f2a5a; }
    .action-btn.secondary:hover { background: #dbeafe; border-color: #0f2a5a; }

    .action-btn.primary {
        background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%);
        border-color: #0f2a5a;
        color: #ffffff;
        font-weight: 700;
    }

    .action-btn.primary:hover { background: linear-gradient(180deg, #0d2449 0%, #091a3d 100%); border-color: #0a1838; }
    @media (max-width: 768px) {
        .create-year-page { padding: 16px; }
        .toolbar-grid { grid-template-columns: 1fr; }
        .toolbar-actions { width: 100%; }
        .category-card-header { flex-direction: column; align-items: flex-start; }
        .category-actions { width: 100%; justify-content: flex-start; }
        .table-responsive { overflow-x: auto; }
        .table { min-width: 860px; }
    }
</style>

<script>
const addSubCriteriaButtons = document.querySelectorAll('.addSubCriteriaButton');
const addCriteriaForm = document.getElementById('addCriteriaForm');
const categoryIdInput = document.getElementById('criteriaCategoryId');
const categoryNameReadonly = document.getElementById('criteriaCategoryNameReadonly');
const criteriaSemesterIdInput = document.getElementById('criteriaSemesterId');
const criteriaAcademicYearIdInput = document.getElementById('criteriaAcademicYearId');
const criteriaTypeSelect = document.getElementById('LOAI_TIEU_CHI');
const fixedPointInput = document.getElementById('DIEM_CO_DINH');
const addPointInput = document.getElementById('add_point');
const deductPointInput = document.getElementById('deduct_point');
const maxTimesInput = document.getElementById('LAN_THUC_HIEN_TOI_DA');
const maxPointInput = document.getElementById('DIEM_TOI_DA_TIEU_CHI');
const fixedPointRow = fixedPointInput ? fixedPointInput.closest('.col-md-4') : null;
const addPointRow = addPointInput ? addPointInput.closest('.col-md-4') : null;
const deductPointRow = deductPointInput ? deductPointInput.closest('.col-md-4') : null;
const maxTimesRow = maxTimesInput ? maxTimesInput.closest('.col-md-4') : null;
const editCriteriaTypeSelect = document.getElementById('editCriteriaType');
const editCriteriaFixedPointInput = document.getElementById('editCriteriaFixedPoint');
const editCriteriaAddPointInput = document.getElementById('editCriteriaAddPoint');
const editCriteriaDeductPointInput = document.getElementById('editCriteriaDeductPoint');
const editCriteriaMaxTimesInput = document.getElementById('editCriteriaMaxTimes');
const editCriteriaMaxPointInput = document.getElementById('editCriteriaMaxPoint');
const editCriteriaUseForActivityInput = document.getElementById('editCriteriaUseForActivity');
const editCriteriaIdInput = document.getElementById('editCriteriaId');
const editCriteriaTemplateIdInput = document.getElementById('editCriteriaTemplateId');
const editCriteriaCategoryIdInput = document.getElementById('editCriteriaCategoryId');
const editCriteriaCategoryNameInput = document.getElementById('editCriteriaCategoryName');
const editCriteriaNameInput = document.getElementById('editCriteriaName');
const editCriteriaDescriptionInput = document.getElementById('editCriteriaDescription');

function setFieldVisibility(row, input, visible) {
    if (row) {
        row.style.display = visible ? '' : 'none';
    }
    if (input) {
        input.disabled = !visible;
    }
}

function updateCriteriaFieldsForType(typeSelect, fixedInput, addInput, deductInput, maxTimesInputField, maxPointInputField) {
    const fixedPointRow = fixedInput ? fixedInput.closest('.col-md-4') : null;
    const addPointRow = addInput ? addInput.closest('.col-md-4') : null;
    const deductPointRow = deductInput ? deductInput.closest('.col-md-4') : null;
    const maxTimesRow = maxTimesInputField ? maxTimesInputField.closest('.col-md-4') : null;

    if (!typeSelect) {
        return;
    }

    const type = typeSelect.value;
    if (type === 'CONG_THEO_LAN') {
        setFieldVisibility(fixedPointRow, fixedInput, false);
        setFieldVisibility(addPointRow, addInput, true);
        setFieldVisibility(deductPointRow, deductInput, false);
        setFieldVisibility(maxTimesRow, maxTimesInputField, true);
    } else if (type === 'CO_DINH') {
        setFieldVisibility(fixedPointRow, fixedInput, true);
        setFieldVisibility(addPointRow, addInput, false);
        setFieldVisibility(deductPointRow, deductInput, false);
        setFieldVisibility(maxTimesRow, maxTimesInputField, false);
    } else if (type === 'TRU_THEO_LAN') {
        setFieldVisibility(fixedPointRow, fixedInput, false);
        setFieldVisibility(addPointRow, addInput, false);
        setFieldVisibility(deductPointRow, deductInput, true);
        setFieldVisibility(maxTimesRow, maxTimesInputField, true);
    } else {
        setFieldVisibility(fixedPointRow, fixedInput, true);
        setFieldVisibility(addPointRow, addInput, true);
        setFieldVisibility(deductPointRow, deductInput, true);
        setFieldVisibility(maxTimesRow, maxTimesInputField, true);
    }

    if (maxPointInputField) {
        updateCriteriaMaxPoint(typeSelect, fixedInput, addInput, deductInput, maxTimesInputField, maxPointInputField);
    }
}

function updateCriteriaMaxPoint(typeSelect, fixedInput, addInput, deductInput, maxTimesInputField, maxPointInputField) {
    if (!typeSelect || !maxPointInputField) {
        return;
    }

    const type = typeSelect.value;
    const fixedPoint = fixedInput ? parseFloat(fixedInput.value) || 0 : 0;
    const addPoint = addInput ? parseFloat(addInput.value) || 0 : 0;
    const deductPoint = deductInput ? parseFloat(deductInput.value) || 0 : 0;
    const maxTimes = maxTimesInputField ? parseInt(maxTimesInputField.value, 10) || 0 : 0;

    if (type === 'CONG_THEO_LAN') {
        maxPointInputField.value = (addPoint * maxTimes).toFixed(2);
    } else if (type === 'CO_DINH') {
        maxPointInputField.value = fixedPoint.toFixed(2);
    } else if (type === 'TRU_THEO_LAN') {
        maxPointInputField.value = (deductPoint * maxTimes).toFixed(2);
    } else {
        maxPointInputField.value = '';
    }
}

function resetCriteriaModalFields() {
    const nameInput = document.getElementById('TEN_TIEU_CHI');
    const descriptionInput = document.getElementById('MO_TA_TIEU_CHI');
    const useForActivityInput = document.getElementById('use_for_activity');

    if (nameInput) nameInput.value = '';
    if (descriptionInput) descriptionInput.value = '';
    if (fixedPointInput) fixedPointInput.value = '';
    if (addPointInput) addPointInput.value = '';
    if (deductPointInput) deductPointInput.value = '';
    if (maxTimesInput) maxTimesInput.value = '';
    if (maxPointInput) maxPointInput.value = '';
    if (criteriaTypeSelect) criteriaTypeSelect.value = 'CONG_THEO_LAN';
    if (useForActivityInput) useForActivityInput.checked = false;
    updateCriteriaFieldsForType(criteriaTypeSelect, fixedPointInput, addPointInput, deductPointInput, maxTimesInput, maxPointInput);
}

addSubCriteriaButtons.forEach(function(button){
    button.addEventListener('click', function(event){
        event.stopPropagation();
        const selectedCategoryId = this.dataset.categoryId || '';
        const selectedCategoryName = this.dataset.categoryName || '';

        if (categoryIdInput) {
            categoryIdInput.value = selectedCategoryId;
        }
        if (categoryNameReadonly) {
            categoryNameReadonly.value = selectedCategoryName;
        }
        if (criteriaSemesterIdInput) {
            criteriaSemesterIdInput.value = '<?= htmlspecialchars((string) $selectedSemesterId, ENT_QUOTES, 'UTF-8') ?>';
        }
        if (criteriaAcademicYearIdInput) {
            criteriaAcademicYearIdInput.value = '<?= htmlspecialchars((string) $selectedAcademicYearId, ENT_QUOTES, 'UTF-8') ?>';
        }
        resetCriteriaModalFields();
    });
});

if (addCriteriaForm) {
    addCriteriaForm.addEventListener('submit', function(event){
        const selectedCategoryId = categoryIdInput ? categoryIdInput.value : '';
        if (!selectedCategoryId) {
            alert('Không xác định được danh mục cha.');
            event.preventDefault();
            return false;
        }
    });
}

if (criteriaTypeSelect) {
    criteriaTypeSelect.addEventListener('change', function(){
        updateCriteriaFieldsForType(criteriaTypeSelect, fixedPointInput, addPointInput, deductPointInput, maxTimesInput, maxPointInput);
    });
}
if (fixedPointInput) {
    fixedPointInput.addEventListener('input', function(){ updateCriteriaMaxPoint(criteriaTypeSelect, fixedPointInput, addPointInput, deductPointInput, maxTimesInput, maxPointInput); });
}
if (addPointInput) {
    addPointInput.addEventListener('input', function(){ updateCriteriaMaxPoint(criteriaTypeSelect, fixedPointInput, addPointInput, deductPointInput, maxTimesInput, maxPointInput); });
}
if (deductPointInput) {
    deductPointInput.addEventListener('input', function(){ updateCriteriaMaxPoint(criteriaTypeSelect, fixedPointInput, addPointInput, deductPointInput, maxTimesInput, maxPointInput); });
}
if (maxTimesInput) {
    maxTimesInput.addEventListener('input', function(){ updateCriteriaMaxPoint(criteriaTypeSelect, fixedPointInput, addPointInput, deductPointInput, maxTimesInput, maxPointInput); });
}

if (editCriteriaTypeSelect) {
    editCriteriaTypeSelect.addEventListener('change', function(){
        updateCriteriaFieldsForType(editCriteriaTypeSelect, editCriteriaFixedPointInput, editCriteriaAddPointInput, editCriteriaDeductPointInput, editCriteriaMaxTimesInput, editCriteriaMaxPointInput);
    });
}
if (editCriteriaFixedPointInput) {
    editCriteriaFixedPointInput.addEventListener('input', function(){ updateCriteriaMaxPoint(editCriteriaTypeSelect, editCriteriaFixedPointInput, editCriteriaAddPointInput, editCriteriaDeductPointInput, editCriteriaMaxTimesInput, editCriteriaMaxPointInput); });
}
if (editCriteriaAddPointInput) {
    editCriteriaAddPointInput.addEventListener('input', function(){ updateCriteriaMaxPoint(editCriteriaTypeSelect, editCriteriaFixedPointInput, editCriteriaAddPointInput, editCriteriaDeductPointInput, editCriteriaMaxTimesInput, editCriteriaMaxPointInput); });
}
if (editCriteriaDeductPointInput) {
    editCriteriaDeductPointInput.addEventListener('input', function(){ updateCriteriaMaxPoint(editCriteriaTypeSelect, editCriteriaFixedPointInput, editCriteriaAddPointInput, editCriteriaDeductPointInput, editCriteriaMaxTimesInput, editCriteriaMaxPointInput); });
}
if (editCriteriaMaxTimesInput) {
    editCriteriaMaxTimesInput.addEventListener('input', function(){ updateCriteriaMaxPoint(editCriteriaTypeSelect, editCriteriaFixedPointInput, editCriteriaAddPointInput, editCriteriaDeductPointInput, editCriteriaMaxTimesInput, editCriteriaMaxPointInput); });
}

document.querySelectorAll('.editCriteriaButton').forEach(function(button){
    button.addEventListener('click', function(){
        if (!editCriteriaIdInput || !editCriteriaTemplateIdInput || !editCriteriaCategoryIdInput || !editCriteriaCategoryNameInput || !editCriteriaNameInput || !editCriteriaDescriptionInput || !editCriteriaTypeSelect || !editCriteriaFixedPointInput || !editCriteriaAddPointInput || !editCriteriaDeductPointInput || !editCriteriaMaxTimesInput || !editCriteriaMaxPointInput || !editCriteriaUseForActivityInput) {
            return;
        }

        editCriteriaIdInput.value = this.dataset.criteriaId || '';
        editCriteriaTemplateIdInput.value = this.dataset.templateId || '';
        editCriteriaCategoryIdInput.value = this.dataset.categoryId || '';
        editCriteriaCategoryNameInput.value = this.dataset.categoryName || '';
        editCriteriaNameInput.value = this.dataset.name || '';
        editCriteriaDescriptionInput.value = this.dataset.description || '';
        editCriteriaTypeSelect.value = this.dataset.type || 'CONG_THEO_LAN';
        editCriteriaFixedPointInput.value = this.dataset.fixedPoint || '';
        editCriteriaAddPointInput.value = this.dataset.addPoint || '';
        editCriteriaDeductPointInput.value = this.dataset.deductPoint || '';
        editCriteriaMaxTimesInput.value = this.dataset.maxTimes || '';
        editCriteriaMaxPointInput.value = this.dataset.maxPoint || '';
        editCriteriaUseForActivityInput.checked = (this.dataset.useForActivity || '0') === '1';

        updateCriteriaFieldsForType(editCriteriaTypeSelect, editCriteriaFixedPointInput, editCriteriaAddPointInput, editCriteriaDeductPointInput, editCriteriaMaxTimesInput, editCriteriaMaxPointInput);
    });
});

async function onAcademicYearChange(yearId) {
    const semesterSelect = document.getElementById('semester');
    if (!semesterSelect) {
        return;
    }

    semesterSelect.innerHTML = '';
    const emptyOption = document.createElement('option');
    emptyOption.value = '';
    emptyOption.textContent = '-- Chọn học kỳ --';
    semesterSelect.appendChild(emptyOption);
    semesterSelect.disabled = true;

    if (!yearId) {
        return;
    }

    try {
        const response = await fetch('/KhoaLuan/public/admin.php?page=ajax_semesters_by_academic_year&MA_NIEN_KHOA=' + encodeURIComponent(yearId));
        if (!response.ok) {
            throw new Error('Lỗi khi tải học kỳ');
        }

        const semesters = await response.json();
        semesters.forEach(semester => {
            const option = document.createElement('option');
            option.value = semester.MA_HOC_KY || semester.id || '';
            option.textContent = semester.TEN_HOC_KY || semester.name || 'Không xác định';
            semesterSelect.appendChild(option);
        });

        semesterSelect.disabled = false;
    } catch (error) {
        console.error(error);
    }
}

updateCriteriaFieldsForType(criteriaTypeSelect, fixedPointInput, addPointInput, deductPointInput, maxTimesInput, maxPointInput);
</script>
