<?php
    $roles = $roles ?? [
        ['id' => 1, 'name' => 'Admin'],
        ['id' => 2, 'name' => 'Giảng viên'],
        ['id' => 3, 'name' => 'Sinh viên']
    ];

    $formData = $formData ?? [];
    $errors = $errors ?? [];
?>

<div class="create-account-page">
    <div class="page-panel">
        <div class="panel-header">
            <h2 class="panel-title">CẤP TÀI KHOẢN</h2>
        </div>

        <div class="panel-body">
            <form id="createAccountForm" method="POST" action="?page=create_account">
                <div class="form-grid">
                    <!-- Tên người dùng -->
                    <div class="form-field">
                        <label class="field-label" for="full_name">
                            Tên người dùng <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            class="field-input"
                            placeholder="Nhập tên người dùng"
                            value="<?= isset($formData['full_name']) ? htmlspecialchars($formData['full_name']) : '' ?>"
                            required
                        />
                        <?php if(isset($errors['full_name'])): ?>
                            <span class="field-error"><?= $errors['full_name'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Tên tài khoản -->
                    <div class="form-field">
                        <label class="field-label" for="username">
                            Tên tài khoản <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="field-input"
                            placeholder="Nhập tên tài khoản"
                            value="<?= isset($formData['username']) ? htmlspecialchars($formData['username']) : '' ?>"
                            required
                        />
                        <?php if(isset($errors['username'])): ?>
                            <span class="field-error"><?= $errors['username'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Giới tính -->
                    <div class="form-field">
                        <label class="field-label" for="gender">
                            Giới tính <span class="required">*</span>
                        </label>
                        <select id="gender" name="gender" class="field-input" required>
                            <option value="">-- Chọn giới tính --</option>
                            <option value="male" <?= (isset($formData['gender']) && $formData['gender'] === 'male') ? 'selected' : '' ?>>Nam</option>
                            <option value="female" <?= (isset($formData['gender']) && $formData['gender'] === 'female') ? 'selected' : '' ?>>Nữ</option>
                            <option value="other" <?= (isset($formData['gender']) && $formData['gender'] === 'other') ? 'selected' : '' ?>>Khác</option>
                        </select>
                        <?php if(isset($errors['gender'])): ?>
                            <span class="field-error"><?= $errors['gender'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Ngày sinh -->
                    <div class="form-field">
                        <label class="field-label" for="birthday">
                            Ngày sinh <span class="required">*</span>
                        </label>
                        <input
                            type="date"
                            id="birthday"
                            name="birthday"
                            class="field-input"
                            value="<?= isset($formData['birthday']) ? htmlspecialchars($formData['birthday']) : '' ?>"
                            required
                        />
                        <?php if(isset($errors['birthday'])): ?>
                            <span class="field-error"><?= $errors['birthday'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Địa chỉ -->
                    <div class="form-field">
                        <label class="field-label" for="address">
                            Địa chỉ
                        </label>
                        <input
                            type="text"
                            id="address"
                            name="address"
                            class="field-input"
                            placeholder="Nhập địa chỉ"
                            value="<?= isset($formData['address']) ? htmlspecialchars($formData['address']) : '' ?>"
                        />
                        <?php if(isset($errors['address'])): ?>
                            <span class="field-error"><?= $errors['address'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="form-field">
                        <label class="field-label" for="email">
                            Email <span class="required">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="field-input"
                            placeholder="Nhập email"
                            value="<?= isset($formData['email']) ? htmlspecialchars($formData['email']) : '' ?>"
                            required
                        />
                        <?php if(isset($errors['email'])): ?>
                            <span class="field-error"><?= $errors['email'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Số điện thoại -->
                    <div class="form-field">
                        <label class="field-label" for="phone">
                            Số điện thoại
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="field-input"
                            placeholder="Nhập số điện thoại"
                            value="<?= isset($formData['phone']) ? htmlspecialchars($formData['phone']) : '' ?>"
                        />
                        <?php if(isset($errors['phone'])): ?>
                            <span class="field-error"><?= $errors['phone'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Mật khẩu -->
                    <div class="form-field">
                        <label class="field-label" for="password">
                            Mật khẩu <span class="required">*</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="field-input"
                            placeholder="Nhập mật khẩu"
                            required
                        />
                        <?php if(isset($errors['password'])): ?>
                            <span class="field-error"><?= $errors['password'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Xác nhận mật khẩu -->
                    <div class="form-field">
                        <label class="field-label" for="confirm_password">
                            Xác nhận mật khẩu <span class="required">*</span>
                        </label>
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            class="field-input"
                            placeholder="Nhập lại mật khẩu"
                            required
                        />
                        <?php if(isset($errors['confirm_password'])): ?>
                            <span class="field-error"><?= $errors['confirm_password'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Vai trò -->
                    <div class="form-field">
                        <label class="field-label" for="role">
                            Chọn vai trò <span class="required">*</span>
                        </label>
                        <select id="role" name="role" class="field-input" required>
                            <option value="">-- Chọn vai trò --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>" <?= (isset($formData['role']) && $formData['role'] == $role['id']) ? 'selected' : '' ?> >
                                    <?= htmlspecialchars($role['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($errors['role'])): ?>
                            <span class="field-error"><?= $errors['role'] ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-field">
                        <label class="field-label" for="status">
                            Trạng thái <span class="required">*</span>
                        </label>
                        <select id="status" name="status" class="field-input" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="active" <?= (isset($formData['status']) && $formData['status'] === 'active') ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="inactive" <?= (isset($formData['status']) && $formData['status'] === 'inactive') ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                        <?php if(isset($errors['status'])): ?>
                            <span class="field-error"><?= $errors['status'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="?page=accounts" class="action-btn secondary">
                        Hủy
                    </a>
                    <button type="submit" class="action-btn primary">
                        Cấp tài khoản
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
    .create-account-page {
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
