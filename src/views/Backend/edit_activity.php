<?php
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $activities = $activities ?? [];
    $organizing_units = $organizing_units ?? [];
    $time_slots = $time_slots ?? [];
    $activity_types = $activity_types ?? [];
    $activity_levels = $activity_levels ?? [];
    $formData = $formData ?? [];
    $errors = $errors ?? [];

    if ($id && empty($formData)) {
        foreach ($activities as $a) {
            if (($a['id'] ?? null) == $id) {
                $formData = $a;
                break;
            }
        }
    }
?>
<div class="edit-activity-page">
    <div class="page-panel card"><div class="panel-header card-header"><h2 class="panel-title">CHỈNH SỬA HOẠT ĐỘNG</h2></div>
    <div class="panel-body card-body">
<form id="editActivityForm" method="POST" action="?page=edit_activity&id=<?= $id ?>" enctype="multipart/form-data">
            <div class="form-grid">
                <!-- Tên hoạt động -->
                <div class="form-field">
                    <label class="field-label form-label" for="activity_name">Tên hoạt động <span class="required">*</span></label>
                    <input type="text" id="activity_name" name="activity_name" class="field-input form-control" placeholder="Nhập tên hoạt động" value="<?= htmlspecialchars($formData['name'] ?? ($formData['activity_name'] ?? '')) ?>" required />
                    <span class="field-error<?= isset($errors['activity_name']) ? '' : ' is-empty' ?>"><?= isset($errors['activity_name']) ? htmlspecialchars($errors['activity_name']) : '&nbsp;' ?></span>
                </div>

                <!-- Đơn vị tổ chức -->
                <div class="form-field">
                    <label class="field-label form-label" for="organizing_unit">Đơn vị tổ chức <span class="required">*</span></label>
                    <select id="organizing_unit" name="organizing_unit" class="field-input form-select" required>
                        <option value="">-- Chọn đơn vị tổ chức --</option>
                        <?php foreach ($organizing_units as $unit): ?>
                            <option value="<?= htmlspecialchars($unit) ?>" <?= (isset($formData['organizing_unit']) && $formData['organizing_unit'] === $unit) ? 'selected' : '' ?>><?= htmlspecialchars($unit) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="field-error<?= isset($errors['organizing_unit']) ? '' : ' is-empty' ?>"><?= isset($errors['organizing_unit']) ? htmlspecialchars($errors['organizing_unit']) : '&nbsp;' ?></span>
                </div>

                <!-- Ca hoạt động -->
                <div class="form-field">
                    <label class="field-label form-label" for="activity_period">Ca hoạt động <span class="required">*</span></label>
                    <select id="activity_period" name="activity_period" class="field-input form-select" required>
                        <option value="">-- Chọn ca hoạt động --</option>
                        <?php foreach ($time_slots as $slot): ?>
                            <option value="<?= htmlspecialchars($slot) ?>" <?= (isset($formData['activity_period']) && $formData['activity_period'] === $slot) ? 'selected' : '' ?>><?= htmlspecialchars($slot) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="field-error<?= isset($errors['activity_period']) ? '' : ' is-empty' ?>"><?= isset($errors['activity_period']) ? htmlspecialchars($errors['activity_period']) : '&nbsp;' ?></span>
                </div>

                <!-- Upload background -->
                <div class="form-field">
                    <label class="field-label form-label" for="background_image">Upload background</label>
                    <input type="file" id="background_image" name="background_image" class="field-input form-control" accept="image/*" />
                    <span class="field-error<?= isset($errors['background_image']) ? '' : ' is-empty' ?>"><?= isset($errors['background_image']) ? htmlspecialchars($errors['background_image']) : '&nbsp;' ?></span>
                </div>

                <!-- Giờ -->
                <div class="form-field">
                    <label class="field-label form-label" for="activity_time">Giờ <span class="required">*</span></label>
                    <input type="time" id="activity_time" name="activity_time" class="field-input form-control" value="<?= htmlspecialchars($formData['time'] ?? $formData['activity_time'] ?? '') ?>" required />
                    <span class="field-error<?= isset($errors['activity_time']) ? '' : ' is-empty' ?>"><?= isset($errors['activity_time']) ? htmlspecialchars($errors['activity_time']) : '&nbsp;' ?></span>
                </div>

                <!-- Địa điểm -->
                <div class="form-field">
                    <label class="field-label form-label" for="location">Địa điểm <span class="required">*</span></label>
                    <input type="text" id="location" name="location" class="field-input form-control" placeholder="Nhập địa điểm" value="<?= htmlspecialchars($formData['location'] ?? '') ?>" required />
                    <span class="field-error<?= isset($errors['location']) ? '' : ' is-empty' ?>"><?= isset($errors['location']) ? htmlspecialchars($errors['location']) : '&nbsp;' ?></span>
                </div>

                <!-- Ngày bắt đầu -->
                <div class="form-field">
                    <label class="field-label form-label" for="start_date">Ngày bắt đầu <span class="required">*</span></label>
                    <input type="date" id="start_date" name="start_date" class="field-input form-control" value="<?= htmlspecialchars($formData['start_date'] ?? '') ?>" required />
                    <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                    <span class="field-error<?= isset($errors['start_date']) ? '' : ' is-empty' ?>"><?= isset($errors['start_date']) ? htmlspecialchars($errors['start_date']) : '&nbsp;' ?></span>
                </div>

                <!-- Ngày kết thúc -->
                <div class="form-field">
                    <label class="field-label form-label" for="end_date">Ngày kết thúc <span class="required">*</span></label>
                    <input type="date" id="end_date" name="end_date" class="field-input form-control" value="<?= htmlspecialchars($formData['end_date'] ?? '') ?>" required />
                    <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                    <span class="field-error<?= isset($errors['end_date']) ? '' : ' is-empty' ?>"><?= isset($errors['end_date']) ? htmlspecialchars($errors['end_date']) : '&nbsp;' ?></span>
                </div>

                <!-- Loại hoạt động -->
                <div class="form-field">
                    <label class="field-label form-label" for="activity_type">Loại hoạt động <span class="required">*</span></label>
                    <select id="activity_type" name="activity_type" class="field-input form-select" required>
                        <option value="">-- Chọn loại hoạt động --</option>
                        <?php foreach ($activity_types as $type): ?>
                            <option value="<?= htmlspecialchars($type) ?>" <?= (isset($formData['activity_type']) && $formData['activity_type'] === $type) ? 'selected' : '' ?>><?= htmlspecialchars($type) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="field-error<?= isset($errors['activity_type']) ? '' : ' is-empty' ?>"><?= isset($errors['activity_type']) ? htmlspecialchars($errors['activity_type']) : '&nbsp;' ?></span>
                </div>

                <!-- Cấp hoạt động -->
                <div class="form-field">
                    <label class="field-label form-label" for="activity_level">Cấp hoạt động <span class="required">*</span></label>
                    <select id="activity_level" name="activity_level" class="field-input form-select" required>
                        <option value="">-- Chọn cấp hoạt động --</option>
                        <?php foreach ($activity_levels as $level): ?>
                            <option value="<?= htmlspecialchars($level) ?>" <?= (isset($formData['activity_level']) && $formData['activity_level'] === $level) ? 'selected' : '' ?>><?= htmlspecialchars($level) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="field-error<?= isset($errors['activity_level']) ? '' : ' is-empty' ?>"><?= isset($errors['activity_level']) ? htmlspecialchars($errors['activity_level']) : '&nbsp;' ?></span>
                </div>

                <!-- Đối tượng -->
                <div class="form-field">
                    <label class="field-label form-label" for="target_audience">Đối tượng <span class="required">*</span></label>
                    <input type="text" id="target_audience" name="target_audience" class="field-input form-control" placeholder="Nhập đối tượng" value="<?= htmlspecialchars($formData['target_audience'] ?? '') ?>" required />
                    <span class="field-error<?= isset($errors['target_audience']) ? '' : ' is-empty' ?>"><?= isset($errors['target_audience']) ? htmlspecialchars($errors['target_audience']) : '&nbsp;' ?></span>
                </div>

                <!-- Trang phục -->
                <div class="form-field">
                    <label class="field-label form-label" for="dress_code">Trang phục</label>
                    <input type="text" id="dress_code" name="dress_code" class="field-input form-control" placeholder="Nhập trang phục" value="<?= htmlspecialchars($formData['dress_code'] ?? '') ?>" />
                    <span class="field-error<?= isset($errors['dress_code']) ? '' : ' is-empty' ?>"><?= isset($errors['dress_code']) ? htmlspecialchars($errors['dress_code']) : '&nbsp;' ?></span>
                </div>

                <!-- Nội dung -->
                <div class="form-field">
                    <label class="field-label form-label" for="content">Nội dung</label>
                    <textarea id="content" name="content" class="field-input textarea-input form-control" placeholder="Nhập nội dung hoạt động"><?= isset($formData['content'])?htmlspecialchars($formData['content']):'' ?></textarea>
                    <span class="field-error<?= isset($errors['content']) ? '' : ' is-empty' ?>"><?= isset($errors['content']) ? htmlspecialchars($errors['content']) : '&nbsp;' ?></span>
                </div>

                <!-- Quyền lợi -->
                <div class="form-field">
                    <label class="field-label form-label" for="benefits">Quyền lợi</label>
                    <textarea id="benefits" name="benefits" class="field-input textarea-input form-control" placeholder="Nhập quyền lợi"><?= isset($formData['benefits'])?htmlspecialchars($formData['benefits']):'' ?></textarea>
                    <span class="field-error<?= isset($errors['benefits']) ? '' : ' is-empty' ?>"><?= isset($errors['benefits']) ? htmlspecialchars($errors['benefits']) : '&nbsp;' ?></span>
                </div>

                <!-- Điểm cộng -->
                <div class="form-field">
                    <label class="field-label form-label" for="bonus_points">Điểm cộng</label>
                    <input type="number" id="bonus_points" name="bonus_points" class="field-input form-control" placeholder="Nhập điểm cộng" value="<?= isset($formData['bonus_points'])?htmlspecialchars($formData['bonus_points']):'' ?>" min="0" />
                    <span class="field-error<?= isset($errors['bonus_points']) ? '' : ' is-empty' ?>"><?= isset($errors['bonus_points']) ? htmlspecialchars($errors['bonus_points']) : '&nbsp;' ?></span>
                </div>

                <!-- Số lượng -->
                <div class="form-field">
                    <label class="field-label form-label" for="capacity">Số lượng</label>
                    <input type="number" id="capacity" name="capacity" class="field-input form-control" placeholder="Nhập số lượng" value="<?= isset($formData['capacity'])?htmlspecialchars($formData['capacity']):'' ?>" min="0" />
                    <span class="field-error<?= isset($errors['capacity']) ? '' : ' is-empty' ?>"><?= isset($errors['capacity']) ? htmlspecialchars($errors['capacity']) : '&nbsp;' ?></span>
                </div>

                <!-- Người đại diện -->
                <div class="form-field">
                    <label class="field-label form-label" for="representative">Người đại diện</label>
                    <input type="text" id="representative" name="representative" class="field-input form-control" placeholder="Nhập người đại diện" value="<?= htmlspecialchars($formData['representative'] ?? '') ?>" />
                    <span class="field-error<?= isset($errors['representative']) ? '' : ' is-empty' ?>"><?= isset($errors['representative']) ? htmlspecialchars($errors['representative']) : '&nbsp;' ?></span>
                </div>

                <!-- Số điện thoại -->
                <div class="form-field">
                    <label class="field-label form-label" for="phone">Số điện thoại</label>
                    <input type="tel" id="phone" name="phone" class="field-input form-control" placeholder="Nhập số điện thoại" value="<?= htmlspecialchars($formData['phone'] ?? '') ?>" />
                    <span class="field-error<?= isset($errors['phone']) ? '' : ' is-empty' ?>"><?= isset($errors['phone']) ? htmlspecialchars($errors['phone']) : '&nbsp;' ?></span>
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
            </div>

            <div class="form-actions">
                <a href="?page=list_activity" class="action-btn secondary cancel-btn btn btn-outline-secondary">Hủy</a>
                <button type="submit" class="action-btn primary save-change-btn btn btn-primary">Cập nhật hoạt động</button>
            </div>
        </form>
    </div></div>
</div>

<style>
    .create-activity-page, .edit-activity-page { display: grid; gap: 0; padding:24px }
    .page-panel { background:#fff; border:1px solid #e8ecf3; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden }
    .panel-header { padding:12px 14px; border-bottom:1px solid #e5e7eb; background:#f9fafb }
    .panel-title { font-size:14px; font-weight:700; color:#0f2a5a; margin:0 }
    .panel-body { padding:20px }
    .form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:0 14px; margin-bottom:8px }
    .form-field { display:grid; gap:3px }
    .field-label { font-size:12px; font-weight:700; color:#0f2a5a }
    .field-input { padding:10px; border-radius:10px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; color:#1f2937 }
    .field-input:focus { outline:none; border-color:#0f2a5a; box-shadow:0 0 0 3px rgba(15,42,90,0.08); background:#fff }
    .field-hint { font-size: 11px; color: #9ca3af; display: block; }
    .field-error { color:#dc2626; display:block; font-size:12px; line-height:1.2; min-height:18px; overflow-wrap:anywhere }
    .field-error.is-empty { visibility:hidden }
    .form-actions { display:flex; justify-content:flex-end; gap:12px; padding-top:16px; border-top:1px solid #e8ecf3 }
    .action-btn { padding:8px 20px; border-radius:10px; border:1px solid #e5e7eb; background:#fff; color:#0f2a5a; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px }
    .action-btn:hover { background:#f3f4f6; border-color:#d1d5db }
    .action-btn.primary { background: linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); border-color:#0f2a5a; color:#fff }
    .action-btn.primary:hover { background: linear-gradient(180deg,#0d2449 0%,#091a3d 100%); border-color:#0a1838 }
    @media (max-width:768px) { .form-grid{grid-template-columns:1fr; gap:0} .action-btn{width:100%; justify-content:center} }
</style>

<style>
    /* Ensure required asterisk is red like create_ views */
    .required { color: #dc2626; font-weight: 700; }
</style>

<script>
    document.getElementById('editActivityForm')?.addEventListener('submit', function(event) {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        if (startDate && endDate && startDate > endDate) {
            event.preventDefault();
            alert('Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc.');
        }
    });
</script>
