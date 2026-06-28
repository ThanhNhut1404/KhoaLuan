<?php
    $formData = $formData ?? [];
    $errors = $errors ?? [];
    $statusOptions = $statusOptions ?? [];
    if (empty($statusOptions)) {
        $statusOptions = [
            ['value' => 'Sắp diễn ra', 'label' => 'Sắp diễn ra'],
            ['value' => 'Đang diễn ra', 'label' => 'Đang diễn ra'],
            ['value' => 'Đã hoàn thành', 'label' => 'Đã hoàn thành'],
        ];
    }
?>

<div class="edit-year-page">
    <div class="page-panel card"><div class="panel-header card-header"><h2 class="panel-title">CHỈNH SỬA NIÊN KHÓA</h2></div>
    <div class="panel-body card-body">
        <form id="editYearForm" method="POST" action="?page=edit_year&id=<?= $id ?>">
            <div class="form-grid">
                <div class="form-field">
                    <label class="field-label form-label" for="year_name">Tên niên khóa <span class="required">*</span></label>
                    <input type="text" id="year_name" name="year_name" class="field-input form-control" placeholder="Ví dụ: 2024 - 2025" value="<?= htmlspecialchars($formData['name'] ?? ($formData['year_name'] ?? '')) ?>" required />
                    <small class="field-hint">&nbsp;</small>
                    <?php if(isset($errors['year_name'])): ?><span class="field-error"><?= $errors['year_name'] ?></span><?php endif; ?>
                </div>

                <div class="form-field">
                    <label class="field-label form-label" for="start_date">Ngày bắt đầu <span class="required">*</span></label>
                    <input type="date" id="start_date" name="start_date" class="field-input form-control" value="<?= htmlspecialchars($formData['start_date'] ?? '') ?>" required />
                    <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                    <?php if(isset($errors['start_date'])): ?><span class="field-error"><?= $errors['start_date'] ?></span><?php endif; ?>
                </div>

                <div class="form-field">
                    <label class="field-label form-label" for="status">Trạng thái <span class="required">*</span></label>
                    <select id="status" name="status" class="field-input form-select" required>
                        <option value="">-- Chọn trạng thái --</option>
                        <?php foreach ($statusOptions as $option): ?>
                            <option value="<?= htmlspecialchars($option['value']) ?>" <?= (($formData['status'] ?? '') === $option['value']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($option['label']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="field-hint">&nbsp;</small>
                    <?php if(isset($errors['status'])): ?><span class="field-error"><?= htmlspecialchars($errors['status']) ?></span><?php endif; ?>
                </div>

                <div class="form-field">
                    <label class="field-label form-label" for="end_date">Ngày kết thúc <span class="required">*</span></label>
                    <input type="date" id="end_date" name="end_date" class="field-input form-control" value="<?= htmlspecialchars($formData['end_date'] ?? '') ?>" required />
                    <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                    <?php if(isset($errors['end_date'])): ?><span class="field-error"><?= $errors['end_date'] ?></span><?php endif; ?>
                </div>
            </div>
            <div class="form-actions">
                <a href="?page=list_year" class="action-btn secondary btn btn-outline-secondary">Hủy</a>
                <button type="submit" class="action-btn primary btn btn-primary">Cập nhật niên khóa</button>
            </div>
        </form>
    </div></div>
</div>

<style>
    .create-year-page, .edit-year-page { display: grid; gap:0; padding:24px }
    .page-panel { background:#fff; border:1px solid #e8ecf3; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden }
    .panel-header { padding:12px 14px; border-bottom:1px solid #e5e7eb; background:#f9fafb }
    .panel-title { font-size:14px; font-weight:700; color:#0f2a5a; margin:0 }
    .panel-body { padding:20px }
    .form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:20px; margin-bottom:24px; align-items: start; }
    .form-field { display:grid; gap:6px }
    .field-label { font-size:12px; font-weight:700; color:#0f2a5a }
    .field-input { padding:10px; border-radius:10px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; color:#1f2937; height:40px; box-sizing:border-box; }
    .field-input:focus { outline:none; border-color:#0f2a5a; box-shadow:0 0 0 3px rgba(15,42,90,0.08); background:#fff }
    .field-hint { font-size: 11px; color: #9ca3af; display: block; }
    .form-actions { display:flex; justify-content:flex-end; gap:12px; padding-top:16px; border-top:1px solid #e8ecf3 }
    .action-btn { padding:8px 20px; border-radius:10px; border:1px solid #e5e7eb; background:#fff; color:#0f2a5a; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px }
    .action-btn:hover { background:#f3f4f6; border-color:#d1d5db }
    .action-btn.primary { background: linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); border-color:#0f2a5a; color:#fff }
    .action-btn.primary:hover { background: linear-gradient(180deg,#0d2449 0%,#091a3d 100%); border-color:#0a1838 }
    @media (max-width:768px) { .form-grid{grid-template-columns:1fr} .action-btn{width:100%; justify-content:center} }
</style>

<style>
    /* Ensure required asterisk is red like create_ views */
    .required { color: #dc2626; font-weight: 700; }
</style>

<script>
    document.getElementById('editYearForm')?.addEventListener('submit', function(e) {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(document.getElementById('end_date').value);

        if (startDate >= endDate) {
            e.preventDefault();
            alert('Ngày bắt đầu phải trước ngày kết thúc!');
            return false;
        }
    });
</script>
