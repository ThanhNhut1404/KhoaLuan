<?php
    // Sample departments & majors - replace with DB calls in real app
    $departments = $departments ?? [
        'Khoa CNTT',
        'Khoa Điện tử',
        'Khoa Cơ khí',
        'Khoa Hóa học'
    ];

    $majors = $majors ?? [
        ['id'=>1, 'code'=>'CNTT01', 'name'=>'Công nghệ thông tin', 'credits'=>120, 'department'=>'Khoa CNTT', 'status'=>'active', 'description'=> ''],
        ['id'=>2, 'code'=>'DTVT02', 'name'=>'Điện tử truyền thông', 'credits'=>130, 'department'=>'Khoa Điện tử', 'status'=>'inactive', 'description'=> ''],
        ['id'=>3, 'code'=>'CK03', 'name'=>'Cơ khí', 'credits'=>140, 'department'=>'Khoa Cơ khí', 'status'=>'active', 'description'=> '']
    ];

    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $formData = [];
    $errors = [];

    // find major by id for prefilling
    if ($id) {
        foreach ($majors as $m) {
            if ($m['id'] == $id) {
                $formData = [
                    'major_code' => $m['code'],
                    'major_name' => $m['name'],
                    'total_credits' => $m['credits'],
                    'department' => $m['department'],
                    'description' => $m['description'] ?? '',
                    'status' => $m['status'] ?? 'active'
                ];
                break;
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // basic validation
        $code = trim($_POST['major_code'] ?? '');
        $name = trim($_POST['major_name'] ?? '');
        $credits = (int)($_POST['total_credits'] ?? 0);
        $dept = $_POST['department'] ?? '';
        $desc = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? 'inactive';

        if ($code === '') $errors['major_code'] = 'Mã ngành là bắt buộc';
        if ($name === '') $errors['major_name'] = 'Tên ngành là bắt buộc';
        if ($credits < 1) $errors['total_credits'] = 'Số tín chỉ phải lớn hơn 0';
        if ($dept === '') $errors['department'] = 'Vui lòng chọn khoa';

        if (empty($errors)) {
            // TODO: persist update to DB. For now simulate update and redirect
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['message'] = 'Cập nhật ngành học thành công';
            $_SESSION['message_type'] = 'success';
            header('Location: ?page=list_major');
            exit;
        } else {
            // repopulate form with submitted values
            $formData = [
                'major_code' => htmlspecialchars($code),
                'major_name' => htmlspecialchars($name),
                'total_credits' => $credits,
                'department' => $dept,
                'description' => htmlspecialchars($desc),
                'status' => $status
            ];
        }
    }
?>

<div class="edit-major-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">CHỈNH SỬA NGÀNH HỌC</h2>
        </div>

        <div class="panel-body card-body">
            <form id="editMajorForm" method="POST" action="?page=edit_major&id=<?= $id ?>">
                <div class="form-grid">
                    <div class="form-field">
                        <label class="field-label form-label" for="major_code">Mã ngành <span class="required">*</span></label>
                        <input type="text" id="major_code" name="major_code" class="field-input form-control" placeholder="Ví dụ: CNTT01" value="<?= $formData['major_code'] ?? '' ?>" required />
                        <?php if(isset($errors['major_code'])): ?><span class="field-error"><?= $errors['major_code'] ?></span><?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="major_name">Tên ngành <span class="required">*</span></label>
                        <input type="text" id="major_name" name="major_name" class="field-input form-control" placeholder="Nhập tên ngành học" value="<?= $formData['major_name'] ?? '' ?>" required />
                        <?php if(isset($errors['major_name'])): ?><span class="field-error"><?= $errors['major_name'] ?></span><?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="total_credits">Số tín chỉ <span class="required">*</span></label>
                        <input type="number" id="total_credits" name="total_credits" class="field-input form-control" placeholder="Ví dụ: 120" value="<?= $formData['total_credits'] ?? '' ?>" min="1" required />
                        <?php if(isset($errors['total_credits'])): ?><span class="field-error"><?= $errors['total_credits'] ?></span><?php endif; ?>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="department">Khoa trực thuộc <span class="required">*</span></label>
                        <select id="department" name="department" class="field-input form-select" required>
                            <option value="">-- Chọn khoa quản lý --</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= htmlspecialchars($dept) ?>" <?= (isset($formData['department']) && $formData['department'] === $dept) ? 'selected' : '' ?>><?= htmlspecialchars($dept) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['department'])): ?><span class="field-error"><?= $errors['department'] ?></span><?php endif; ?>
                    </div>

                    <div class="form-field" style="grid-column: 1 / -1;">
                        <label class="field-label form-label" for="description">Mô tả</label>
                        <textarea id="description" name="description" class="field-input textarea-input form-control" rows="4" placeholder="Nhập mô tả ngành học"><?= $formData['description'] ?? '' ?></textarea>
                    </div>

                    <div class="form-field">
                        <label class="field-label form-label" for="status">Trạng thái <span class="required">*</span></label>
                        <select id="status" name="status" class="field-input form-select" required>
                            <option value="active" <?= (isset($formData['status']) && $formData['status'] === 'active') ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="inactive" <?= (isset($formData['status']) && $formData['status'] === 'inactive') ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="?page=list_major" class="action-btn secondary btn btn-outline-secondary">Hủy</a>
                    <button type="submit" class="action-btn primary btn btn-primary">Cập nhật ngành học</button>
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
    /* reuse styles from create_major */
    .edit-major-page { display:grid; gap:0; padding:24px; }
    .page-panel { background:#fff; border:1px solid #e8ecf3; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden; }
    .panel-header { padding:12px 14px; border-bottom:1px solid #e5e7eb; background:#f9fafb; }
    .panel-title { font-size:14px; font-weight:700; color:#0f2a5a; margin:0; }
    .panel-body { padding:20px; }
    .form-grid { display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:20px; margin-bottom:24px; }
    .form-field { display:grid; gap:6px; }
    .field-label { font-size:12px; font-weight:700; color:#0f2a5a; }
    .field-input { padding:8px 10px; border-radius:10px; border:1px solid #e5e7eb; background:#f9fafb; font-size:13px; }
    .form-actions { display:flex; justify-content:flex-end; gap:12px; padding-top:16px; border-top:1px solid #e8ecf3; }
    .action-btn { padding:8px 20px; border-radius:10px; }
    .action-btn.primary { background:linear-gradient(180deg,#0f2a5a 0%,#0b1f45 100%); color:#fff; border-color:#0f2a5a; }
        .form-actions { display: flex; justify-content: flex-end; gap: 12px; padding-top: 16px; border-top: 1px solid #e8ecf3; }

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
    @media (max-width:768px) { .form-grid { grid-template-columns:1fr; } .action-btn { width:100%; justify-content:center; } }
</style>

<style>
    /* Ensure required asterisk is red like create_ views */
    .required { color: #dc2626; font-weight: 700; }
</style>

<script>
    document.getElementById('editMajorForm')?.addEventListener('submit', function(e){
        var code = document.getElementById('major_code').value.trim();
        var name = document.getElementById('major_name').value.trim();
        var credits = parseInt(document.getElementById('total_credits').value || '0', 10);
        if (!code || !name) { e.preventDefault(); alert('Vui lòng điền các trường bắt buộc'); return false; }
        if (credits < 1) { e.preventDefault(); alert('Số tín chỉ phải lớn hơn 0'); return false; }
    });
</script>
