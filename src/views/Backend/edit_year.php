<?php
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $years = $years ?? [ ['id'=>1,'name'=>'2023 - 2024','start_date'=>'2023-09-05','end_date'=>'2024-06-30','status'=>'completed'] ];
    $formData = [];
    if ($id) foreach($years as $y) if($y['id']==$id) { $formData=$y; break; }
    $errors = [];
    if ($_SERVER['REQUEST_METHOD']==='POST'){
        $name = trim($_POST['year_name'] ?? '');
        if ($name==='') $errors['year_name']='Tên niên khóa là bắt buộc';
        if (empty($errors)){ if(session_status()===PHP_SESSION_NONE) session_start(); $_SESSION['message']='Cập nhật niên khóa thành công'; $_SESSION['message_type']='success'; header('Location: ?page=list_year'); exit; }
        $formData = $_POST;
    }
?>

<div class="edit-year-page">
    <div class="page-panel"><div class="panel-header"><h2 class="panel-title">CHỈNH SỬA NIÊN KHÓA</h2></div>
    <div class="panel-body">
        <form id="editYearForm" method="POST" action="?page=edit_year&id=<?= $id ?>">
            <div class="form-grid">
                <div class="form-field">
                    <label class="field-label" for="year_name">Tên niên khóa <span class="required">*</span></label>
                    <input type="text" id="year_name" name="year_name" class="field-input" placeholder="Ví dụ: 2024 - 2025" value="<?= htmlspecialchars($formData['name'] ?? ($formData['year_name'] ?? '')) ?>" required />
                    <?php if(isset($errors['year_name'])): ?><span class="field-error"><?= $errors['year_name'] ?></span><?php endif; ?>
                </div>

                <div class="form-field">
                    <label class="field-label" for="start_date">Ngày bắt đầu <span class="required">*</span></label>
                    <input type="date" id="start_date" name="start_date" class="field-input" value="<?= htmlspecialchars($formData['start_date'] ?? '') ?>" required />
                    <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                    <?php if(isset($errors['start_date'])): ?><span class="field-error"><?= $errors['start_date'] ?></span><?php endif; ?>
                </div>

                <div class="form-field">
                    <label class="field-label" for="status">Trạng thái <span class="required">*</span></label>
                    <select id="status" name="status" class="field-input" required>
                        <option value="">-- Chọn trạng thái --</option>
                        <option value="upcoming" <?= (isset($formData['status']) && $formData['status'] === 'upcoming') ? 'selected' : '' ?>>Sắp tới</option>
                        <option value="active" <?= (isset($formData['status']) && $formData['status'] === 'active') ? 'selected' : '' ?>>Đang diễn ra</option>
                        <option value="completed" <?= (isset($formData['status']) && $formData['status'] === 'completed') ? 'selected' : '' ?>>Đã hoàn thành</option>
                    </select>
                    <?php if(isset($errors['status'])): ?><span class="field-error"><?= $errors['status'] ?></span><?php endif; ?>
                </div>

                <div class="form-field">
                    <label class="field-label" for="end_date">Ngày kết thúc <span class="required">*</span></label>
                    <input type="date" id="end_date" name="end_date" class="field-input" value="<?= htmlspecialchars($formData['end_date'] ?? '') ?>" required />
                    <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                    <?php if(isset($errors['end_date'])): ?><span class="field-error"><?= $errors['end_date'] ?></span><?php endif; ?>
                </div>
            </div>
            <div class="form-actions">
                <a href="?page=list_year" class="action-btn secondary">Hủy</a>
                <button type="submit" class="action-btn primary">Cập nhật niên khóa</button>
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
    .form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:20px; margin-bottom:24px }
    .form-field { display:grid; gap:6px }
    .field-label { font-size:12px; font-weight:700; color:#0f2a5a }
    .field-input { padding:10px; border-radius:10px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; color:#1f2937 }
    .field-input:focus { outline:none; border-color:#0f2a5a; box-shadow:0 0 0 3px rgba(15,42,90,0.08); background:#fff }
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
