<?php
    $academic_years = $academic_years ?? [ ['id'=>1,'name'=>'2023 - 2024'], ['id'=>2,'name'=>'2024 - 2025'] ];
    $departments = $departments ?? [ ['id'=>1,'name'=>'Khoa CNTT'], ['id'=>2,'name'=>'Khoa Điện tử'] ];
    $majors = $majors ?? [ ['id'=>1,'name'=>'Chuyên ngành Công nghệ Phần mềm'], ['id'=>2,'name'=>'Chuyên ngành Mạng máy tính'] ];
    $advisors = $advisors ?? [ ['id'=>1,'name'=>'Thầy Nguyễn Văn A'], ['id'=>2,'name'=>'Cô Trần Thị B'] ];

    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $formData = [];
    $errors = [];

    $classes = $classes ?? [ ['id'=>1,'code'=>'L01','name'=>'Lớp K1','academic_year'=>1,'department'=>1,'major'=>1,'advisor'=>1,'capacity'=>30,'status'=>'active'] ];
    if ($id) foreach($classes as $c) if($c['id']==$id) { $formData = $c; break; }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $code = trim($_POST['class_code'] ?? '');
        $name = trim($_POST['class_name'] ?? '');
        $credits = (int)($_POST['capacity'] ?? 0);
        if ($code==='') $errors['class_code']='Mã lớp là bắt buộc';
        if ($name==='') $errors['class_name']='Tên lớp là bắt buộc';
        if (empty($errors)) { if(session_status()===PHP_SESSION_NONE) session_start(); $_SESSION['message']='Cập nhật lớp thành công'; $_SESSION['message_type']='success'; header('Location: ?page=list_class'); exit; }
        $formData = $_POST;
    }
?>

<div class="edit-class-page">
    <div class="page-panel card">
        <div class="panel-header card-header"><h2 class="panel-title">CHỈNH SỬA LỚP HỌC</h2></div>
        <div class="panel-body card-body">
            <form id="editClassForm" method="POST" action="?page=edit_class&id=<?= $id ?>">
                <div class="form-grid">
                    <!-- Mã lớp học -->
                    <div class="form-field">
                        <label class="field-label form-label" for="class_code">Mã lớp học <span class="required">*</span></label>
                        <input type="text" id="class_code" name="class_code" class="field-input form-control" placeholder="Nhập mã lớp học" value="<?= isset($formData['code'])?htmlspecialchars($formData['code']):(isset($formData['class_code'])?htmlspecialchars($formData['class_code']):'') ?>" required />
                        <?php if(isset($errors['class_code'])): ?><span class="field-error"><?= $errors['class_code'] ?></span><?php endif; ?>
                    </div>

                    <!-- Tên lớp -->
                    <div class="form-field">
                        <label class="field-label form-label" for="class_name">Tên lớp <span class="required">*</span></label>
                        <input type="text" id="class_name" name="class_name" class="field-input form-control" placeholder="Nhập tên lớp" value="<?= isset($formData['name'])?htmlspecialchars($formData['name']):(isset($formData['class_name'])?htmlspecialchars($formData['class_name']):'') ?>" required />
                        <?php if(isset($errors['class_name'])): ?><span class="field-error"><?= $errors['class_name'] ?></span><?php endif; ?>
                    </div>

                    <!-- Niên khóa -->
                    <div class="form-field">
                        <label class="field-label form-label" for="academic_year">Niên khóa <span class="required">*</span></label>
                        <select id="academic_year" name="academic_year" class="field-input form-select" required>
                            <option value="">-- Chọn niên khóa --</option>
                            <?php foreach ($academic_years as $y): ?>
                                <option value="<?= $y['id'] ?>" <?= (isset($formData['academic_year']) && $formData['academic_year'] == $y['id']) ? 'selected' : '' ?>><?= htmlspecialchars($y['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['academic_year'])): ?><span class="field-error"><?= $errors['academic_year'] ?></span><?php endif; ?>
                    </div>

                    <!-- Khoa -->
                    <div class="form-field">
                        <label class="field-label form-label" for="department">Khoa <span class="required">*</span></label>
                        <select id="department" name="department" class="field-input form-select" required>
                            <option value="">-- Chọn khoa --</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?= $department['id'] ?>" <?= (isset($formData['department']) && $formData['department'] == $department['id']) ? 'selected' : '' ?>><?= htmlspecialchars($department['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['department'])): ?><span class="field-error"><?= $errors['department'] ?></span><?php endif; ?>
                    </div>

                    <!-- Chuyên ngành -->
                    <div class="form-field">
                        <label class="field-label form-label" for="major">Chuyên ngành <span class="required">*</span></label>
                        <select id="major" name="major" class="field-input form-select" required>
                            <option value="">-- Chọn chuyên ngành --</option>
                            <?php foreach ($majors as $major): ?>
                                <option value="<?= $major['id'] ?>" <?= (isset($formData['major']) && $formData['major'] == $major['id']) ? 'selected' : '' ?>><?= htmlspecialchars($major['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['major'])): ?><span class="field-error"><?= $errors['major'] ?></span><?php endif; ?>
                    </div>

                    <!-- Cố vấn -->
                    <div class="form-field">
                        <label class="field-label form-label" for="advisor">Cố vấn <span class="required">*</span></label>
                        <select id="advisor" name="advisor" class="field-input form-select" required>
                            <option value="">-- Chọn cố vấn --</option>
                            <?php foreach ($advisors as $advisor): ?>
                                <option value="<?= $advisor['id'] ?>" <?= (isset($formData['advisor']) && $formData['advisor'] == $advisor['id']) ? 'selected' : '' ?>><?= htmlspecialchars($advisor['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['advisor'])): ?><span class="field-error"><?= $errors['advisor'] ?></span><?php endif; ?>
                    </div>

                    <!-- Số lượng -->
                    <div class="form-field">
                        <label class="field-label form-label" for="capacity">Số lượng</label>
                        <input type="number" id="capacity" name="capacity" class="field-input form-control" placeholder="Nhập số lượng" value="<?= isset($formData['capacity'])?htmlspecialchars($formData['capacity']):'' ?>" min="0" />
                        <?php if(isset($errors['capacity'])): ?><span class="field-error"><?= $errors['capacity'] ?></span><?php endif; ?>
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-field">
                        <label class="field-label form-label" for="status">Trạng thái <span class="required">*</span></label>
                        <select id="status" name="status" class="field-input form-select" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="upcoming" <?= (isset($formData['status']) && $formData['status'] === 'upcoming') ? 'selected' : '' ?>>Sắp tới</option>
                            <option value="active" <?= (isset($formData['status']) && $formData['status'] === 'active') ? 'selected' : '' ?>>Đang diễn ra</option>
                            <option value="completed" <?= (isset($formData['status']) && $formData['status'] === 'completed') ? 'selected' : '' ?>>Đã hoàn thành</option>
                        </select>
                        <?php if(isset($errors['status'])): ?><span class="field-error"><?= $errors['status'] ?></span><?php endif; ?>
                    </div>

                    <!-- Ghi chú -->
                    <div class="form-field">
                        <label class="field-label form-label" for="notes">Ghi chú</label>
                        <textarea id="notes" name="notes" class="field-input textarea-input form-control" placeholder="Nhập ghi chú"><?= isset($formData['notes'])?htmlspecialchars($formData['notes']):'' ?></textarea>
                        <?php if(isset($errors['notes'])): ?><span class="field-error"><?= $errors['notes'] ?></span><?php endif; ?>
                    </div>
                </div>
                <div class="form-actions">
                    <a href="?page=list_class" class="action-btn secondary cancel-btn btn btn-outline-secondary">Hủy</a>
                    <button type="submit" class="action-btn primary save-change-btn btn btn-primary">Cập nhật lớp học</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .create-class-page, .edit-class-page {
        display: grid; gap: 0; padding: 24px;
    }
    .page-panel { background:#fff; border:1px solid #e8ecf3; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden }
    .panel-header { padding:12px 14px; border-bottom:1px solid #e5e7eb; background:#f9fafb }
    .panel-title { font-size:14px; font-weight:700; color:#0f2a5a; margin:0; display:flex; align-items:center; gap:8px }
    .panel-body { padding:20px }
    .form-grid { display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:20px; margin-bottom:24px }
    .form-field { display:grid; gap:6px }
    .field-label { font-size:12px; font-weight:700; color:#0f2a5a }
    .field-input { padding:10px; border-radius:10px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; color:#1f2937; height:40px; box-sizing:border-box }
    .field-input:focus { outline:none; border-color:#0f2a5a; box-shadow:0 0 0 3px rgba(15,42,90,0.08); background:#fff }
    .form-actions { display:flex; justify-content:flex-end; gap:12px; padding-top:16px; border-top:1px solid #e8ecf3 }
    .action-btn { padding:8px 20px; border-radius:10px; border:1px solid #e5e7eb; background:#fff; color:#0f2a5a; font-size:13px; font-weight:700; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:all 0.2s; white-space:nowrap }
    .action-btn:hover { background:#f3f4f6; border-color:#d1d5db }
    .action-btn.primary { background: linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); border-color:#0f2a5a; color:#fff }
    .action-btn.primary:hover { background: linear-gradient(180deg,#0d2449 0%,#091a3d 100%); border-color:#0a1838 }
    @media (max-width:768px) { .form-grid{grid-template-columns:1fr} .action-btn{width:100%; justify-content:center} }
</style>

<style>
    /* Ensure required asterisk is red like create_ views */
    .required { color: #dc2626; font-weight: 700; }
</style>
