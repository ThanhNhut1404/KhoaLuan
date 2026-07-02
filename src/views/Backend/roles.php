<?php
$roles = $roles ?? [];
$selectedRoleId = $selectedRoleId ?? null;
$selectedRole = $selectedRole ?? null;
$functionsByModule = $functionsByModule ?? [];
$assignedPermissionIds = $assignedPermissionIds ?? [];
$errors = $errors ?? [];
$assignedLookup = array_fill_keys(array_map('intval', $assignedPermissionIds), true);

$roleLabel = static function (string $roleName): string {
    $labels = [
        'ADMIN' => 'Admin',
        'DOAN_TRUONG' => 'Đoàn trường',
        'KHOA' => 'Khoa',
        'BO_MON' => 'Bộ môn',
        'DOAN_KHOA' => 'Đoàn khoa',
        'LIEN_CHI' => 'Liên chi / CLB',
        'CO_VAN_HOC_TAP' => 'Cố vấn học tập',
        'CAN_BO_LOP' => 'Cán bộ lớp',
        'SINH_VIEN' => 'Sinh viên',
        'GIANG_VIEN' => 'Giảng viên',
    ];

    return $labels[$roleName] ?? str_replace('_', ' ', $roleName);
};
?>

<div class="role-permission-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <h2 class="panel-title">CẤP QUYỀN TRUY CẬP</h2>
        </div>

        <div class="panel-body card-body">
            <form class="role-select-form" method="get" action="/KhoaLuan/public/admin.php">
                <input type="hidden" name="page" value="roles">
                <div class="role-select-row">
                    <label class="field-label form-label" for="role_id_select">Vai trò</label>
                    <select id="role_id_select" name="role_id" class="field-input form-select">
                        <?php foreach ($roles as $role): ?>
                            <?php $roleId = (int) ($role['MA_VAI_TRO'] ?? 0); ?>
                            <option value="<?= $roleId ?>" <?= (int) $selectedRoleId === $roleId ? 'selected' : '' ?>>
                                <?= htmlspecialchars($roleLabel((string) ($role['TEN_VAI_TRO'] ?? '')), ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if (!empty($errors['role_id'])): ?>
                    <div class="permission-error"><?= htmlspecialchars($errors['role_id'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
            </form>

            <form method="post" action="/KhoaLuan/public/admin.php?page=roles" class="permission-form">
                <input type="hidden" name="role_id" value="<?= htmlspecialchars((string) ($selectedRoleId ?? ''), ENT_QUOTES, 'UTF-8') ?>">

                <div class="permission-toolbar">
                    <div class="selected-role">
                        <?= htmlspecialchars($selectedRole ? $roleLabel((string) $selectedRole['TEN_VAI_TRO']) : 'Chưa chọn vai trò', ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <div class="permission-actions">
                        <button class="action-btn secondary btn btn-outline-secondary" type="button" id="checkAllPermissions">Chọn tất cả</button>
                        <button class="action-btn secondary btn btn-outline-secondary" type="button" id="clearAllPermissions">Bỏ chọn</button>
                        <button class="action-btn primary btn btn-primary" type="submit" <?= $selectedRole ? '' : 'disabled' ?>>Lưu</button>
                    </div>
                </div>

                <?php if (!empty($errors['permission_ids'])): ?>
                    <div class="permission-error"><?= htmlspecialchars($errors['permission_ids'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <div class="permission-groups">
                    <?php foreach ($functionsByModule as $module => $functions): ?>
                        <section class="permission-group">
                            <div class="permission-group-title">
                                <span><?= htmlspecialchars((string) $module, ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="permission-count"><?= count($functions) ?></span>
                            </div>

                            <div class="permission-list">
                                <?php foreach ($functions as $function): ?>
                                    <?php
                                        $functionId = (int) ($function['MA_CHUC_NANG'] ?? 0);
                                        $checked = isset($assignedLookup[$functionId]);
                                    ?>
                                    <label class="permission-item">
                                        <input
                                            type="checkbox"
                                            name="permission_ids[]"
                                            value="<?= $functionId ?>"
                                            <?= $checked ? 'checked' : '' ?>
                                        >
                                        <span>
                                            <strong><?= htmlspecialchars((string) ($function['TEN_CHUC_NANG'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
                                            <small><?= htmlspecialchars((string) ($function['PAGE'] ?? ''), ENT_QUOTES, 'UTF-8') ?></small>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .role-permission-page { display: grid; gap: 0; padding: 24px; }
    .role-select-form { margin-bottom: 12px; }
    .role-select-row {
        display: grid;
        grid-template-columns: minmax(180px, 320px);
        gap: 3px;
    }
    .role-permission-page .page-panel {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .role-permission-page .panel-header {
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }
    .role-permission-page .panel-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f2a5a;
        margin: 0;
    }
    .role-permission-page .panel-body {
        padding: 20px;
    }
    .role-permission-page .field-label {
        font-size: 12px;
        font-weight: 700;
        color: #0f2a5a;
        display: block;
        margin-bottom: 0;
    }
    .role-permission-page .field-input {
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 13px;
        color: #1f2937;
        font-family: inherit;
        height: 40px;
        box-sizing: border-box;
    }
    .role-permission-page .field-input:focus {
        outline: none;
        border-color: #0f2a5a;
        box-shadow: 0 0 0 3px rgba(15,42,90,0.08);
        background: #ffffff;
    }
    .permission-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 0 16px;
        border-bottom: 1px solid #e8ecf3;
        margin-bottom: 16px;
    }
    .selected-role {
        color: #0f2a5a;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .permission-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    .permission-actions .action-btn,
    .role-permission-page .action-btn {
        width: auto !important;
        height: auto !important;
        padding: 8px 20px !important;
        border-radius: 10px !important;
        border: 1px solid #e5e7eb !important;
        background: #ffffff !important;
        color: #0f2a5a !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        line-height: 1.3 !important;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }
    .role-permission-page .action-btn:hover {
        background: #f3f4f6 !important;
        border-color: #d1d5db !important;
    }
    .role-permission-page .action-btn.primary {
        background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%) !important;
        border-color: #0f2a5a !important;
        color: #ffffff !important;
    }
    .role-permission-page .action-btn.primary:hover {
        background: linear-gradient(180deg, #0d2449 0%, #091a3d 100%) !important;
        border-color: #0a1838 !important;
    }
    .role-permission-page .action-btn:disabled {
        opacity: .55;
        cursor: not-allowed;
    }
    .permission-groups {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }
    .permission-group {
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        background: #ffffff;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(15, 42, 90, 0.04);
    }
    .permission-group-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 12px 14px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        color: #0f2a5a;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .permission-count {
        min-width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: #eef2f7;
        color: #0f2a5a;
        font-size: 12px;
        letter-spacing: 0;
    }
    .permission-list { display: grid; }
    .permission-item {
        display: grid;
        grid-template-columns: 18px minmax(0, 1fr);
        gap: 10px;
        align-items: flex-start;
        padding: 10px 14px;
        margin: 0;
        border-top: 1px solid #eef2f7;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    .permission-item:first-child {
        border-top: 0;
    }
    .permission-item:hover {
        background: #f8fafc;
    }
    .permission-item input {
        margin-top: 2px;
        width: 16px;
        height: 16px;
        accent-color: #0f2a5a;
    }
    .permission-item strong {
        display: block;
        color: #1f2937;
        font-size: 13px;
        font-weight: 700;
    }
    .permission-item small {
        display: block;
        color: #6b7280;
        font-size: 12px;
        margin-top: 2px;
    }
    .permission-error {
        color: #dc2626;
        font-size: 12px;
        font-weight: 700;
        margin: 8px 0 12px;
    }

    @media (max-width: 900px) {
        .permission-groups { grid-template-columns: 1fr; }
        .permission-toolbar { align-items: flex-start; flex-direction: column; }
        .permission-actions { justify-content: flex-start; }
    }
    @media (max-width: 768px) {
        .role-select-row { grid-template-columns: 1fr; }
        .permission-actions {
            width: 100%;
            flex-direction: column-reverse;
            align-items: stretch;
        }
        .role-permission-page .action-btn { width: 100% !important; }
    }
</style>

<script>
    (function() {
        var roleSelect = document.getElementById('role_id_select');
        var checkAll = document.getElementById('checkAllPermissions');
        var clearAll = document.getElementById('clearAllPermissions');
        var permissionForm = document.querySelector('.permission-form');

        if (roleSelect) {
            roleSelect.addEventListener('change', function() {
                roleSelect.form.submit();
            });
        }

        function setAllPermissions(checked) {
            if (!permissionForm) return;
            permissionForm.querySelectorAll('input[type="checkbox"][name="permission_ids[]"]').forEach(function(input) {
                input.checked = checked;
            });
        }

        if (checkAll) {
            checkAll.addEventListener('click', function() {
                setAllPermissions(true);
            });
        }

        if (clearAll) {
            clearAll.addEventListener('click', function() {
                setAllPermissions(false);
            });
        }
    })();
</script>
