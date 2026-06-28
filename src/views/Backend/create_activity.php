<?php
    $organizing_units = $organizing_units ?? [
        'Đoàn trường',
        'Khoa CNTT',
        'Phòng Công tác Sinh viên',
        'Liên chi đoàn'
    ];

    $time_slots = $time_slots ?? [
        'Sáng',
        'Chiều',
        'Tối'
    ];

    $activity_types = $activity_types ?? [
        'Văn hóa',
        'Thể thao',
        'Tình nguyện',
        'Hội thảo'
    ];

    $activity_levels = $activity_levels ?? [
        'Trường',
        'Khoa',
        'Lớp',
        'Liên trường'
    ];

    $formData = $formData ?? [];
    $errors = $errors ?? [];
?>

<div class="create-activity-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">TẠO HOẠT ĐỘNG</h2>
        </div>

        <div class="panel-body card-body">
            <form id="createActivityForm" method="POST" action="?page=create_activity" enctype="multipart/form-data">
                <div class="form-grid">
                    <!-- Tên hoạt động -->
                    <div class="form-field">
                        <label class="field-label form-label" for="activity_name">
                            Tên hoạt động <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="activity_name"
                            name="activity_name"
                            class="field-input form-control"
                            placeholder="Nhập tên hoạt động"
                            value="<?= isset($formData['activity_name']) ? htmlspecialchars($formData['activity_name']) : '' ?>"
                            required
                        />
                        <?php if(isset($errors['activity_name'])): ?>
                            <span class="field-error"><?= $errors['activity_name'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Đơn vị tổ chức -->
                    <div class="form-field">
                        <label class="field-label form-label" for="organizing_unit">
                            Đơn vị tổ chức <span class="required">*</span>
                        </label>
                        <select id="organizing_unit" name="organizing_unit" class="field-input form-select" required>
                            <option value="">-- Chọn đơn vị tổ chức --</option>
                            <?php foreach ($organizing_units as $unit): ?>
                                <option value="<?= htmlspecialchars($unit) ?>" <?= (isset($formData['organizing_unit']) && $formData['organizing_unit'] === $unit) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($unit) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['organizing_unit'])): ?>
                            <span class="field-error"><?= $errors['organizing_unit'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Thời gian hoạt động -->
                    <div class="form-field">
                        <label class="field-label form-label" for="activity_period">
                            Ca hoạt động <span class="required">*</span>
                        </label>
                        <select id="activity_period" name="activity_period" class="field-input form-select" required>
                            <option value="">-- Chọn ca hoạt động --</option>
                            <?php foreach ($time_slots as $slot): ?>
                                <option value="<?= htmlspecialchars($slot) ?>" <?= (isset($formData['activity_period']) && $formData['activity_period'] === $slot) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($slot) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['activity_period'])): ?>
                            <span class="field-error"><?= $errors['activity_period'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Upload background -->
                    <div class="form-field">
                        <label class="field-label form-label" for="background_image">
                            Upload background
                        </label>
                        <input
                            type="file"
                            id="background_image"
                            name="background_image"
                            class="field-input form-control"
                            accept="image/*"
                        />
                        <?php if(isset($errors['background_image'])): ?>
                            <span class="field-error"><?= $errors['background_image'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Giờ -->
                    <div class="form-field">
                        <label class="field-label form-label" for="activity_time">
                            Giờ <span class="required">*</span>
                        </label>
                        <input
                            type="time"
                            id="activity_time"
                            name="activity_time"
                            class="field-input form-control"
                            value="<?= isset($formData['activity_time']) ? htmlspecialchars($formData['activity_time']) : '' ?>"
                            required
                        />
                        <?php if(isset($errors['activity_time'])): ?>
                            <span class="field-error"><?= $errors['activity_time'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Địa điểm -->
                    <div class="form-field">
                        <label class="field-label form-label" for="location">
                            Địa điểm <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="location"
                            name="location"
                            class="field-input form-control"
                            placeholder="Nhập địa điểm"
                            value="<?= isset($formData['location']) ? htmlspecialchars($formData['location']) : '' ?>"
                            required
                        />
                        <?php if(isset($errors['location'])): ?>
                            <span class="field-error"><?= $errors['location'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Ngày bắt đầu -->
                    <div class="form-field">
                        <label class="field-label form-label" for="start_date">
                            Ngày bắt đầu <span class="required">*</span>
                        </label>
                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            class="field-input form-control"
                            value="<?= isset($formData['start_date']) ? htmlspecialchars($formData['start_date']) : '' ?>"
                            required
                        />
                        <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                        <?php if(isset($errors['start_date'])): ?>
                            <span class="field-error"><?= $errors['start_date'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Ngày kết thúc -->
                    <div class="form-field">
                        <label class="field-label form-label" for="end_date">
                            Ngày kết thúc <span class="required">*</span>
                        </label>
                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            class="field-input form-control"
                            value="<?= isset($formData['end_date']) ? htmlspecialchars($formData['end_date']) : '' ?>"
                            required
                        />
                        <small class="field-hint">Định dạng: dd/mm/yyyy</small>
                        <?php if(isset($errors['end_date'])): ?>
                            <span class="field-error"><?= $errors['end_date'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Loại hoạt động -->
                    <div class="form-field">
                        <label class="field-label form-label" for="activity_type">
                            Loại hoạt động <span class="required">*</span>
                        </label>
                        <select id="activity_type" name="activity_type" class="field-input form-select" required>
                            <option value="">-- Chọn loại hoạt động --</option>
                            <?php foreach ($activity_types as $type): ?>
                                <option value="<?= htmlspecialchars($type) ?>" <?= (isset($formData['activity_type']) && $formData['activity_type'] === $type) ? 'selected' : '' ?> >
                                    <?= htmlspecialchars($type) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['activity_type'])): ?>
                            <span class="field-error"><?= $errors['activity_type'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Cấp hoạt động -->
                    <div class="form-field">
                        <label class="field-label form-label" for="activity_level">
                            Cấp hoạt động <span class="required">*</span>
                        </label>
                        <select id="activity_level" name="activity_level" class="field-input form-select" required>
                            <option value="">-- Chọn cấp hoạt động --</option>
                            <?php foreach ($activity_levels as $level): ?>
                                <option value="<?= htmlspecialchars($level) ?>" <?= (isset($formData['activity_level']) && $formData['activity_level'] === $level) ? 'selected' : '' ?> >
                                    <?= htmlspecialchars($level) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['activity_level'])): ?>
                            <span class="field-error"><?= $errors['activity_level'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Đối tượng -->
                    <div class="form-field">
                        <label class="field-label form-label" for="target_audience">
                            Đối tượng <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="target_audience"
                            name="target_audience"
                            class="field-input form-control"
                            placeholder="Nhập đối tượng"
                            value="<?= isset($formData['target_audience']) ? htmlspecialchars($formData['target_audience']) : '' ?>"
                            required
                        />
                        <?php if(isset($errors['target_audience'])): ?>
                            <span class="field-error"><?= $errors['target_audience'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Trang phục -->
                    <div class="form-field">
                        <label class="field-label form-label" for="dress_code">
                            Trang phục
                        </label>
                        <input
                            type="text"
                            id="dress_code"
                            name="dress_code"
                            class="field-input form-control"
                            placeholder="Nhập trang phục"
                            value="<?= isset($formData['dress_code']) ? htmlspecialchars($formData['dress_code']) : '' ?>"
                        />
                        <?php if(isset($errors['dress_code'])): ?>
                            <span class="field-error"><?= $errors['dress_code'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Nội dung -->
                    <div class="form-field">
                        <label class="field-label form-label" for="content">
                            Nội dung
                        </label>
                        <textarea
                            id="content"
                            name="content"
                            class="field-input textarea-input form-control"
                            placeholder="Nhập nội dung hoạt động"
                        ><?= isset($formData['content']) ? htmlspecialchars($formData['content']) : '' ?></textarea>
                        <?php if(isset($errors['content'])): ?>
                            <span class="field-error"><?= $errors['content'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Quyền lợi -->
                    <div class="form-field">
                        <label class="field-label form-label" for="benefits">
                            Quyền lợi
                        </label>
                        <textarea
                            id="benefits"
                            name="benefits"
                            class="field-input textarea-input form-control"
                            placeholder="Nhập quyền lợi"
                        ><?= isset($formData['benefits']) ? htmlspecialchars($formData['benefits']) : '' ?></textarea>
                        <?php if(isset($errors['benefits'])): ?>
                            <span class="field-error"><?= $errors['benefits'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Điểm cộng -->
                    <div class="form-field">
                        <label class="field-label form-label" for="bonus_points">
                            Điểm cộng
                        </label>
                        <input
                            type="number"
                            id="bonus_points"
                            name="bonus_points"
                            class="field-input form-control"
                            placeholder="Nhập điểm cộng"
                            value="<?= isset($formData['bonus_points']) ? htmlspecialchars($formData['bonus_points']) : '' ?>"
                            min="0"
                        />
                        <?php if(isset($errors['bonus_points'])): ?>
                            <span class="field-error"><?= $errors['bonus_points'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Số lượng -->
                    <div class="form-field">
                        <label class="field-label form-label" for="capacity">
                            Số lượng
                        </label>
                        <input
                            type="number"
                            id="capacity"
                            name="capacity"
                            class="field-input form-control"
                            placeholder="Nhập số lượng"
                            value="<?= isset($formData['capacity']) ? htmlspecialchars($formData['capacity']) : '' ?>"
                            min="0"
                        />
                        <?php if(isset($errors['capacity'])): ?>
                            <span class="field-error"><?= $errors['capacity'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Người đại diện -->
                    <div class="form-field">
                        <label class="field-label form-label" for="representative">
                            Người đại diện
                        </label>
                        <input
                            type="text"
                            id="representative"
                            name="representative"
                            class="field-input form-control"
                            placeholder="Nhập người đại diện"
                            value="<?= isset($formData['representative']) ? htmlspecialchars($formData['representative']) : '' ?>"
                        />
                        <?php if(isset($errors['representative'])): ?>
                            <span class="field-error"><?= $errors['representative'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Số điện thoại -->
                    <div class="form-field">
                        <label class="field-label form-label" for="phone">
                            Số điện thoại
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="field-input form-control"
                            placeholder="Nhập số điện thoại"
                            value="<?= isset($formData['phone']) ? htmlspecialchars($formData['phone']) : '' ?>"
                        />
                        <?php if(isset($errors['phone'])): ?>
                            <span class="field-error"><?= $errors['phone'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-field">
                        <label class="field-label form-label" for="status">
                            Trạng thái <span class="required">*</span>
                        </label>
                        <select id="status" name="status" class="field-input form-select" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="upcoming" <?= (isset($formData['status']) && $formData['status'] === 'upcoming') ? 'selected' : '' ?>>Sắp tới</option>
                            <option value="active" <?= (isset($formData['status']) && $formData['status'] === 'active') ? 'selected' : '' ?>>Đang diễn ra</option>
                            <option value="completed" <?= (isset($formData['status']) && $formData['status'] === 'completed') ? 'selected' : '' ?>>Đã hoàn thành</option>
                        </select>
                        <?php if(isset($errors['status'])): ?>
                            <span class="field-error"><?= $errors['status'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="?page=list_activity" class="action-btn secondary cancel-btn btn btn-outline-secondary">
                        Hủy
                    </a>
                    <button type="submit" class="action-btn primary btn btn-primary">
                        Tạo hoạt động
                    </button>
                </div>

                <?php if(isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?>">
                        <?= $_SESSION['message'] ?>
                    </div>
                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<style>
    .create-activity-page {
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
    }

    .form-field {
        display: grid;
        gap: 6px;
    }

    .field-label {
        font-size: 12px;
        font-weight: 700;
        color: #0f2a5a;
        text-transform: none;
        letter-spacing: 0.4px;
        display: block;
    }

    .required {
        color: #dc2626;
        font-weight: 700;
    }

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

    .field-input:focus {
        outline: none;
        border-color: #0f2a5a;
        box-shadow: 0 0 0 3px rgba(15, 42, 90, 0.08);
        background: #ffffff;
    }

    select.field-input {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231f2937' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 16px;
        padding-right: 32px;
    }

    .textarea-input {
        min-height: 100px;
        resize: vertical;
        padding: 12px;
        height: auto;
    }

    .field-hint {
        font-size: 11px;
        color: #9ca3af;
        display: block;
    }

    .field-error {
        font-size: 12px;
        color: #dc2626;
        display: block;
    }

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

    .action-btn:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .action-btn.primary {
        background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%);
        border-color: #0f2a5a;
        color: #ffffff;
        font-weight: 700;
    }

    .action-btn.primary:hover {
        background: linear-gradient(180deg, #0d2449 0%, #091a3d 100%);
        border-color: #0a1838;
    }

    .alert {
        margin-top: 16px;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
        border: 1px solid;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border-color: #6ee7b7;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
    }

    .alert-info {
        background: #dbeafe;
        color: #1e3a8a;
        border-color: #93c5fd;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    document.getElementById('createActivityForm').addEventListener('submit', function(event) {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        if (startDate && endDate && startDate > endDate) {
            event.preventDefault();
            alert('Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc.');
        }
    });
</script>
