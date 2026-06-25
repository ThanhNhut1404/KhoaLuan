<?php
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $semesters = $semesters ?? [ ['id'=>1,'name'=>'Học kỳ 1','start_date'=>'2023-09-05','end_date'=>'2023-12-15','status'=>'completed'] ];
    $formData = [];
    if ($id) foreach($semesters as $s) if($s['id']==$id){ $formData=$s; break; }
    $errors = [];
    if ($_SERVER['REQUEST_METHOD']==='POST'){
        $name = trim($_POST['name'] ?? '');
        if ($name==='') $errors['name']='Tên học kỳ là bắt buộc';
        if (empty($errors)){ if(session_status()===PHP_SESSION_NONE) session_start(); $_SESSION['message']='Cập nhật học kỳ thành công'; $_SESSION['message_type']='success'; header('Location: ?page=list_semester'); exit; }
        $formData = $_POST;
    }
?>

<div class="edit-semester-page">
    <div class="page-panel"><div class="panel-header"><h2 class="panel-title">CHỈNH SỬA HỌC KỲ</h2></div>
    <div class="panel-body">
        <?php
            $academic_years = $academic_years ?? [
                ['id' => 1, 'name' => '2023 - 2024'],
                ['id' => 2, 'name' => '2024 - 2025'],
                ['id' => 3, 'name' => '2022 - 2023']
            ];
        ?>
        <form id="editSemesterForm" method="POST" action="?page=edit_semester&id=<?= $id ?>">
            <div class="form-grid">
                <!-- Niên khóa -->
                <div class="form-field">
                    <label class="field-label" for="academic_year">Niên khóa <span class="required">*</span></label>
                    <select id="academic_year" name="academic_year" class="field-input" required>
                        <option value="">-- Chọn niên khóa --</option>
                        <?php foreach ($academic_years as $year): ?>
                            <option value="<?= $year['id'] ?>" <?= (isset($formData['academic_year']) && $formData['academic_year'] == $year['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($year['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="field-hint">&nbsp;</small>
                    <?php if(isset($errors['academic_year'])): ?><span class="field-error"><?= $errors['academic_year'] ?></span><?php endif; ?>
                </div>

                <!-- Tên học kỳ -->
                <div class="form-field">
                    <label class="field-label" for="semester_name">Tên học kỳ <span class="required">*</span></label>
                    <input type="text" id="semester_name" name="semester_name" class="field-input" placeholder="Nhập tên học kỳ" value="<?= htmlspecialchars($formData['name'] ?? $formData['semester_name'] ?? '') ?>" required />
                    <small class="field-hint">Ví dụ: Học kỳ 1</small>
                    <?php if(isset($errors['semester_name'])): ?><span class="field-error"><?= $errors['semester_name'] ?></span><?php endif; ?>
                </div>

                <!-- Ngày bắt đầu -->
                <div class="form-field">
                    <label class="field-label" for="start_date">Ngày bắt đầu <span class="required">*</span></label>
                    <input type="date" id="start_date" name="start_date" class="field-input" value="<?= htmlspecialchars($formData['start_date'] ?? '') ?>" required />
                    <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                    <?php if(isset($errors['start_date'])): ?><span class="field-error"><?= $errors['start_date'] ?></span><?php endif; ?>
                </div>

                <!-- Ngày kết thúc -->
                <div class="form-field">
                    <label class="field-label" for="end_date">Ngày kết thúc <span class="required">*</span></label>
                    <input type="date" id="end_date" name="end_date" class="field-input" value="<?= htmlspecialchars($formData['end_date'] ?? '') ?>" required />
                    <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                    <?php if(isset($errors['end_date'])): ?><span class="field-error"><?= $errors['end_date'] ?></span><?php endif; ?>
                </div>

                

                <!-- Trạng thái -->
                <div class="form-field">
                    <label class="field-label" for="status">Trạng thái <span class="required">*</span></label>
                    <select id="status" name="status" class="field-input" required>
                        <option value="">-- Chọn trạng thái --</option>
                        <option value="upcoming" <?= (isset($formData['status']) && $formData['status'] === 'upcoming') ? 'selected' : '' ?>>Sắp tới</option>
                        <option value="active" <?= (isset($formData['status']) && $formData['status'] === 'active') ? 'selected' : '' ?>>Đang diễn ra</option>
                        <option value="completed" <?= (isset($formData['status']) && $formData['status'] === 'completed') ? 'selected' : '' ?>>Đã hoàn thành</option>
                    </select>
                    <small class="field-hint">&nbsp;</small>
                    <?php if(isset($errors['status'])): ?><span class="field-error"><?= $errors['status'] ?></span><?php endif; ?>
                </div>
            </div>

            <div class="form-actions">
                <a href="?page=list_semester" class="action-btn secondary">Hủy</a>
                <button type="submit" class="action-btn primary">Cập nhật học kỳ</button>
            </div>
        </form>
    </div></div>
</div>

<style>
    .create-semester-page, .edit-semester-page {
        display: grid;
        gap: 0;
        padding: 24px;
    }

    .page-panel {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .panel-header {
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .panel-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f2a5a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .panel-body {
        padding: 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 24px;
        align-items: start;
    }

    .form-field { display: grid; gap: 6px; }

    .field-label { font-size: 12px; font-weight: 700; color: #0f2a5a; }

    .field-input {
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 13px;
        color: #1f2937;
        font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
        height: 40px;
        box-sizing: border-box;
    }

    .field-input:focus { outline: none; border-color: #0f2a5a; box-shadow: 0 0 0 3px rgba(15,42,90,0.08); background:#fff }

    .field-hint { font-size: 11px; color: #9ca3af; display: block; }
    .field-error { font-size: 12px; color: #dc2626; display: block; }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 16px;
        border-top: 1px solid #e8ecf3;
    }

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

    .action-btn.primary {
        background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%);
        border-color: #0f2a5a;
        color: #ffffff;
        font-weight: 700;
    }

    .action-btn.primary:hover { background: linear-gradient(180deg,#0d2449 0%,#091a3d 100%); border-color:#0a1838 }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; gap: 16px; }
        .form-actions { flex-direction: column-reverse; }
        .action-btn { width: 100%; justify-content: center; }
    }
    /* Ensure required asterisk matches create_ views */
    .required { color: #dc2626; font-weight: 700; }
</style>

<script>
    document.getElementById('editSemesterForm')?.addEventListener('submit', function(e) {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(document.getElementById('end_date').value);

        if (startDate >= endDate) {
            e.preventDefault();
            alert('Ngày bắt đầu phải trước ngày kết thúc!');
            return false;
        }
    });
</script>
