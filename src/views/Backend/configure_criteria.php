<?php
$semesters = $semesters ?? [];
$selectedSemesterId = $selectedSemesterId ?? 0;
$formData = $formData ?? [];
$errors = $errors ?? [];
$statusOptions = $statusOptions ?? [
    ['value' => 'Hoạt động', 'label' => 'Hoạt động'],
    ['value' => 'Tạm khóa', 'label' => 'Tạm khóa'],
];
$isEdit = $isEdit ?? false;
$title = $isEdit ? 'Chỉnh sửa tiêu chí' : 'Tạo tiêu chí';
?>

<div class="configure-criteria-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h2>
        </div>
        <div class="panel-body card-body">
            <form method="post" action="/KhoaLuan/public/admin.php?page=configure_criteria<?= $isEdit && !empty($formData['id']) ? '&id=' . urlencode((string) $formData['id']) : '' ?>">
                <div class="form-grid">
                    <div class="form-field">
                        <label class="field-label" for="semester_id">Học kỳ</label>
                        <select id="semester_id" name="semester_id" class="field-input form-select">
                            <option value="">-- Chọn học kỳ --</option>
                            <?php foreach ($semesters as $semester): ?>
                                <option value="<?= htmlspecialchars($semester['id'], ENT_QUOTES, 'UTF-8') ?>" <?= (int) $selectedSemesterId === (int) $semester['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($semester['name'] ?? 'Không xác định', ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($errors['semester_id'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['semester_id'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="name">Tên tiêu chí</label>
                        <input id="name" name="name" type="text" class="field-input form-control" value="<?= htmlspecialchars($formData['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                        <?php if (!empty($errors['name'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-field full-width">
                        <label class="field-label" for="description">Mô tả tiêu chí</label>
                        <textarea id="description" name="description" class="field-input form-control" rows="4"><?= htmlspecialchars($formData['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        <?php if (!empty($errors['description'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['description'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="credit">Điểm cộng</label>
                        <input id="credit" name="credit" type="number" min="0" class="field-input form-control" value="<?= htmlspecialchars((string) ($formData['credit'] ?? '0'), ENT_QUOTES, 'UTF-8') ?>" />
                        <?php if (!empty($errors['credit'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['credit'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="deduction">Điểm trừ</label>
                        <input id="deduction" name="deduction" type="number" min="0" class="field-input form-control" value="<?= htmlspecialchars((string) ($formData['deduction'] ?? '0'), ENT_QUOTES, 'UTF-8') ?>" />
                        <?php if (!empty($errors['deduction'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['deduction'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="execution_round">Lần thực hiện</label>
                        <input id="execution_round" name="execution_round" type="number" min="0" class="field-input form-control" value="<?= htmlspecialchars((string) ($formData['execution_round'] ?? '0'), ENT_QUOTES, 'UTF-8') ?>" />
                        <?php if (!empty($errors['execution_round'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['execution_round'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="display_order">Thứ tự hiển thị</label>
                        <input id="display_order" name="display_order" type="number" min="0" class="field-input form-control" value="<?= htmlspecialchars((string) ($formData['display_order'] ?? '0'), ENT_QUOTES, 'UTF-8') ?>" />
                        <?php if (!empty($errors['display_order'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['display_order'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="status">Trạng thái</label>
                        <select id="status" name="status" class="field-input form-select">
                            <?php foreach ($statusOptions as $option): ?>
                                <option value="<?= htmlspecialchars($option['value'], ENT_QUOTES, 'UTF-8') ?>" <?= ($formData['status'] ?? '') === $option['value'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($errors['status'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['status'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="action-btn primary btn btn-primary"><?= $isEdit ? 'Cập nhật' : 'Tạo mới' ?></button>
                    <a class="action-btn secondary btn btn-outline-secondary" href="?page=list_criteria<?= $selectedSemesterId ? '&semester_id=' . urlencode($selectedSemesterId) : '' ?>">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.configure-criteria-page { padding: 24px; }
.page-panel { background:#fff; border:1px solid #e8ecf3; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden; }
.panel-header { padding:12px 14px; border-bottom:1px solid #e5e7eb; background:#f9fafb; }
.panel-title { margin:0; font-size:14px; font-weight:700; color:#0f2a5a; }
.panel-body { padding:20px; }
.form-grid { display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:16px; }
.form-field { display:flex; flex-direction:column; gap:8px; }
.form-field.full-width { grid-column: 1 / -1; }
.field-label { font-size:13px; font-weight:700; color:#0f2a5a; }
.field-input { width:100%; padding:10px 12px; border-radius:10px; border:1px solid #e5e7eb; background:#f8fafc; color:#0f172a; font-size:13px; }
.field-error { color:#dc2626; font-size:12px; margin-top:4px; }
.form-actions { display:flex; gap:12px; margin-top:20px; justify-content:flex-start; }
.action-btn { display:inline-flex; align-items:center; justify-content:center; padding:10px 18px; border-radius:10px; border:1px solid #e5e7eb; background:#fff; color:#0f2a5a; text-decoration:none; font-size:13px; }
.action-btn.primary { background:#0f2a5a; color:#fff; border-color:#0f2a5a; }
.action-btn.secondary { background:#fff; color:#0f2a5a; }
@media (max-width: 900px) { .form-grid { grid-template-columns: 1fr; } }
</style>
