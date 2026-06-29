<?php
    $academic_years = $academic_years ?? [];
    $departments = $departments ?? [];
    $majors = $majors ?? [];
    $advisors = $advisors ?? [];
    $classes = $classes ?? [];

    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $formData = $formData ?? [];
    $errors = $errors ?? [];

    if ($id && empty($formData)) {
        foreach ($classes as $c) {
            if (($c['id'] ?? null) == $id) {
                $formData = $c;
                break;
            }
        }
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
                        <span class="field-error<?= isset($errors['class_code']) ? '' : ' is-empty' ?>"><?= isset($errors['class_code']) ? htmlspecialchars($errors['class_code']) : '&nbsp;' ?></span>
                    </div>

                    <!-- Tên lớp -->
                    <div class="form-field">
                        <label class="field-label form-label" for="class_name">Tên lớp <span class="required">*</span></label>
                        <input type="text" id="class_name" name="class_name" class="field-input form-control" placeholder="Nhập tên lớp" value="<?= isset($formData['name'])?htmlspecialchars($formData['name']):(isset($formData['class_name'])?htmlspecialchars($formData['class_name']):'') ?>" required />
                        <span class="field-error<?= isset($errors['class_name']) ? '' : ' is-empty' ?>"><?= isset($errors['class_name']) ? htmlspecialchars($errors['class_name']) : '&nbsp;' ?></span>
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
                        <span class="field-error<?= isset($errors['academic_year']) ? '' : ' is-empty' ?>"><?= isset($errors['academic_year']) ? htmlspecialchars($errors['academic_year']) : '&nbsp;' ?></span>
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
                        <span class="field-error<?= isset($errors['department']) ? '' : ' is-empty' ?>"><?= isset($errors['department']) ? htmlspecialchars($errors['department']) : '&nbsp;' ?></span>
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
                        <span class="field-error<?= isset($errors['major']) ? '' : ' is-empty' ?>"><?= isset($errors['major']) ? htmlspecialchars($errors['major']) : '&nbsp;' ?></span>
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
                        <span class="field-error<?= isset($errors['advisor']) ? '' : ' is-empty' ?>"><?= isset($errors['advisor']) ? htmlspecialchars($errors['advisor']) : '&nbsp;' ?></span>
                    </div>

                    <!-- Số lượng -->
                    <div class="form-field">
                        <label class="field-label form-label" for="capacity">Số lượng</label>
                        <input type="number" id="capacity" name="capacity" class="field-input form-control" placeholder="Nhập số lượng" value="<?= isset($formData['capacity'])?htmlspecialchars($formData['capacity']):'' ?>" min="0" />
                        <span class="field-error<?= isset($errors['capacity']) ? '' : ' is-empty' ?>"><?= isset($errors['capacity']) ? htmlspecialchars($errors['capacity']) : '&nbsp;' ?></span>
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
                        <span class="field-error<?= isset($errors['status']) ? '' : ' is-empty' ?>"><?= isset($errors['status']) ? htmlspecialchars($errors['status']) : '&nbsp;' ?></span>
                    </div>

                    <!-- Ghi chú -->
                    <div class="form-field">
                        <label class="field-label form-label" for="notes">Ghi chú</label>
                        <textarea id="notes" name="notes" class="field-input textarea-input form-control" placeholder="Nhập ghi chú"><?= isset($formData['notes'])?htmlspecialchars($formData['notes']):'' ?></textarea>
                        <span class="field-error<?= isset($errors['notes']) ? '' : ' is-empty' ?>"><?= isset($errors['notes']) ? htmlspecialchars($errors['notes']) : '&nbsp;' ?></span>
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
    .form-grid { display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:0 14px; margin-bottom:8px }
    .form-field { display:grid; gap:3px }
    .field-label { font-size:12px; font-weight:700; color:#0f2a5a }
    .field-input { padding:10px; border-radius:10px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; color:#1f2937; height:40px; box-sizing:border-box }
    .field-input:focus { outline:none; border-color:#0f2a5a; box-shadow:0 0 0 3px rgba(15,42,90,0.08); background:#fff }
    .field-error { font-size:12px; color:#dc2626; display:block; line-height:1.2; min-height:18px; overflow-wrap:anywhere }
    .field-error.is-empty { visibility:hidden }
    .form-actions { display:flex; justify-content:flex-end; gap:12px; padding-top:16px; border-top:1px solid #e8ecf3 }
    .action-btn { padding:8px 20px; border-radius:10px; border:1px solid #e5e7eb; background:#fff; color:#0f2a5a; font-size:13px; font-weight:700; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:all 0.2s; white-space:nowrap }
    .action-btn:hover { background:#f3f4f6; border-color:#d1d5db }
    .action-btn.primary { background: linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); border-color:#0f2a5a; color:#fff }
    .action-btn.primary:hover { background: linear-gradient(180deg,#0d2449 0%,#091a3d 100%); border-color:#0a1838 }
    @media (max-width:768px) { .form-grid{grid-template-columns:1fr; gap:0} .action-btn{width:100%; justify-content:center} }
</style>

<style>
    /* Ensure required asterisk is red like create_ views */
    .required { color: #dc2626; font-weight: 700; }
</style>
