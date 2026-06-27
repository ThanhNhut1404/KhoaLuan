<?php
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $roles = $roles ?? [ ['id'=>1,'name'=>'Admin'], ['id'=>2,'name'=>'Giảng viên'], ['id'=>3,'name'=>'Sinh viên'] ];
    $users = $users ?? [ ['id'=>1,'full_name'=>'Nguyen A','username'=>'nguyena','email'=>'a@example.com','role'=>1,'status'=>'active'] ];
    $formData = [];
    if ($id) foreach($users as $u) if($u['id']==$id){ $formData=$u; break; }
    $errors = [];
    if ($_SERVER['REQUEST_METHOD']==='POST'){
        $name = trim($_POST['full_name'] ?? '');
        if ($name==='') $errors['full_name']='Tên là bắt buộc';
        if (empty($errors)){ if(session_status()===PHP_SESSION_NONE) session_start(); $_SESSION['message']='Cập nhật tài khoản thành công'; $_SESSION['message_type']='success'; header('Location: ?page=list_accounts'); exit; }
        $formData = $_POST;
    }
?>

<div class="edit-account-page">
    <div class="page-panel card"><div class="panel-header card-header"><h2 class="panel-title">CHỈNH SỬA TÀI KHOẢN</h2></div>
    <div class="panel-body card-body">
        <form id="editAccountForm" method="POST" action="?page=edit_account&id=<?= $id ?>">
            <div class="form-grid">
                <!-- Tên người dùng -->
                <div class="form-field">
                    <label class="field-label form-label" for="full_name">Tên người dùng <span class="required">*</span></label>
                    <input type="text" id="full_name" name="full_name" class="field-input form-control" placeholder="Nhập tên người dùng" value="<?= htmlspecialchars($formData['full_name'] ?? '') ?>" required />
                    <?php if(isset($errors['full_name'])): ?><span class="field-error"><?= $errors['full_name'] ?></span><?php endif; ?>
                </div>

                <!-- Tên tài khoản -->
                <div class="form-field">
                    <label class="field-label form-label" for="username">Tên tài khoản <span class="required">*</span></label>
                    <input type="text" id="username" name="username" class="field-input form-control" placeholder="Nhập tên tài khoản" value="<?= htmlspecialchars($formData['username'] ?? '') ?>" required />
                    <?php if(isset($errors['username'])): ?><span class="field-error"><?= $errors['username'] ?></span><?php endif; ?>
                </div>

                <!-- Giới tính -->
                <div class="form-field">
                    <label class="field-label form-label" for="gender">Giới tính <span class="required">*</span></label>
                    <select id="gender" name="gender" class="field-input form-select" required>
                        <option value="">-- Chọn giới tính --</option>
                        <option value="male" <?= (isset($formData['gender']) && $formData['gender'] === 'male') ? 'selected' : '' ?>>Nam</option>
                        <option value="female" <?= (isset($formData['gender']) && $formData['gender'] === 'female') ? 'selected' : '' ?>>Nữ</option>
                        <option value="other" <?= (isset($formData['gender']) && $formData['gender'] === 'other') ? 'selected' : '' ?>>Khác</option>
                    </select>
                    <?php if(isset($errors['gender'])): ?><span class="field-error"><?= $errors['gender'] ?></span><?php endif; ?>
                </div>

                <!-- Ngày sinh -->
                <div class="form-field">
                    <label class="field-label form-label" for="birthday">Ngày sinh <span class="required">*</span></label>
                    <input type="date" id="birthday" name="birthday" class="field-input form-control" value="<?= htmlspecialchars($formData['birthday'] ?? '') ?>" required />
                    <?php if(isset($errors['birthday'])): ?><span class="field-error"><?= $errors['birthday'] ?></span><?php endif; ?>
                </div>

                <!-- Địa chỉ -->
                <div class="form-field">
                    <label class="field-label form-label" for="address">Địa chỉ</label>
                    <input type="text" id="address" name="address" class="field-input form-control" placeholder="Nhập địa chỉ" value="<?= htmlspecialchars($formData['address'] ?? '') ?>" />
                    <?php if(isset($errors['address'])): ?><span class="field-error"><?= $errors['address'] ?></span><?php endif; ?>
                </div>

                <!-- Email -->
                <div class="form-field">
                    <label class="field-label form-label" for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="field-input form-control" placeholder="Nhập email" value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required />
                    <?php if(isset($errors['email'])): ?><span class="field-error"><?= $errors['email'] ?></span><?php endif; ?>
                </div>

                <!-- Số điện thoại -->
                <div class="form-field">
                    <label class="field-label form-label" for="phone">Số điện thoại</label>
                    <input type="tel" id="phone" name="phone" class="field-input form-control" placeholder="Nhập số điện thoại" value="<?= htmlspecialchars($formData['phone'] ?? '') ?>" />
                    <?php if(isset($errors['phone'])): ?><span class="field-error"><?= $errors['phone'] ?></span><?php endif; ?>
                </div>

                <!-- Mật khẩu -->
                <div class="form-field">
                    <label class="field-label form-label" for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" class="field-input form-control" placeholder="Nhập mật khẩu" />
                    <?php if(isset($errors['password'])): ?><span class="field-error"><?= $errors['password'] ?></span><?php endif; ?>
                </div>

                <!-- Xác nhận mật khẩu -->
                <div class="form-field">
                    <label class="field-label form-label" for="confirm_password">Xác nhận mật khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="field-input form-control" placeholder="Nhập lại mật khẩu" />
                    <?php if(isset($errors['confirm_password'])): ?><span class="field-error"><?= $errors['confirm_password'] ?></span><?php endif; ?>
                </div>

                <!-- Vai trò -->
                <div class="form-field">
                    <label class="field-label form-label" for="role">Chọn vai trò <span class="required">*</span></label>
                    <select id="role" name="role" class="field-input form-select" required>
                        <option value="">-- Chọn vai trò --</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= (isset($formData['role']) && $formData['role'] == $role['id']) ? 'selected' : '' ?>><?= htmlspecialchars($role['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if(isset($errors['role'])): ?><span class="field-error"><?= $errors['role'] ?></span><?php endif; ?>
                </div>

                <!-- Trạng thái -->
                <div class="form-field">
                    <label class="field-label form-label" for="status">Trạng thái <span class="required">*</span></label>
                    <select id="status" name="status" class="field-input form-select" required>
                        <option value="">-- Chọn trạng thái --</option>
                        <option value="active" <?= (isset($formData['status']) && $formData['status'] === 'active') ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="inactive" <?= (isset($formData['status']) && $formData['status'] === 'inactive') ? 'selected' : '' ?>>Không hoạt động</option>
                    </select>
                    <?php if(isset($errors['status'])): ?><span class="field-error"><?= $errors['status'] ?></span><?php endif; ?>
                </div>
            </div>
            <div class="form-actions">
                <a href="?page=list_accounts" class="action-btn secondary btn btn-outline-secondary">Hủy</a>
                <button type="submit" class="action-btn primary btn btn-primary">Cập nhật tài khoản</button>
            </div>
        </form>
    </div></div>
</div>

<style>
    .create-account-page, .edit-account-page { display:grid; gap:0; padding:24px }
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
