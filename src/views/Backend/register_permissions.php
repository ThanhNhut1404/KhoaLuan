<?php
$pendingFunctions = $pendingFunctions ?? [];
$registeredFunctions = $registeredFunctions ?? [];
$registryErrors = $registryErrors ?? [];

$statusLabel = static function (string $status): string {
    return $status === 'HOAT_DONG' ? 'Hoạt động' : $status;
};
?>

<div class="permission-registry-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="registry-header">
                <div>
                    <h2 class="panel-title">ĐĂNG KÝ CHỨC NĂNG</h2>
                </div>
                <?php if (!empty($pendingFunctions)): ?>
                    <form method="post" action="/KhoaLuan/public/admin.php?page=register_permissions">
                        <input type="hidden" name="action" value="register_all">
                        <button type="submit" class="action-btn primary btn btn-primary">Đăng ký tất cả</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="panel-body card-body">
            <?php if (!empty($registryErrors)): ?>
                <div class="registry-alert alert alert-warning">
                    <strong>Registry cần kiểm tra:</strong>
                    <ul>
                        <?php foreach ($registryErrors as $error): ?>
                            <li><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <section class="registry-section">
                <div class="section-heading">
                    <button type="button" class="registry-collapse-toggle" data-target="pendingRegistryContent" aria-expanded="true">
                        <h3>Chức năng chờ đăng ký</h3>
                        <svg class="collapse-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <span class="section-count"><?= count($pendingFunctions) ?></span>
                </div>

                <div class="registry-section-content" id="pendingRegistryContent">
                <?php if (empty($pendingFunctions)): ?>
                    <div class="empty-state">
                        <h3>Không có chức năng mới cần đăng ký.</h3>
                    </div>
                <?php else: ?>
                    <div class="table-wrapper">
                        <table class="data-table table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>CODE</th>
                                    <th>TÊN CHỨC NĂNG</th>
                                    <th>PAGE</th>
                                    <th>MODULE</th>
                                    <th>ICON</th>
                                    <th>THỨ TỰ</th>
                                    <th>TRẠNG THÁI</th>
                                    <th>THAO TÁC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendingFunctions as $function): ?>
                                    <tr>
                                        <td><code><?= htmlspecialchars((string) $function['MA_CHUC_NANG_CODE'], ENT_QUOTES, 'UTF-8') ?></code></td>
                                        <td><?= htmlspecialchars((string) $function['TEN_CHUC_NANG'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><code><?= htmlspecialchars((string) $function['PAGE'], ENT_QUOTES, 'UTF-8') ?></code></td>
                                        <td><?= htmlspecialchars((string) $function['MODULE'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) $function['ICON'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= (int) $function['THU_TU'] ?></td>
                                        <td><span class="status-badge active"><?= htmlspecialchars($statusLabel((string) $function['TRANG_THAI_CN']), ENT_QUOTES, 'UTF-8') ?></span></td>
                                        <td>
                                            <div class="action-group">
                                            <form method="post" action="/KhoaLuan/public/admin.php?page=register_permissions" style="display:inline">
                                                <input type="hidden" name="action" value="register">
                                                <input type="hidden" name="code" value="<?= htmlspecialchars((string) $function['MA_CHUC_NANG_CODE'], ENT_QUOTES, 'UTF-8') ?>">
                                                    <button type="submit" class="action-btn register btn btn-outline-primary" title="Đăng ký" aria-label="Đăng ký chức năng">
                                                        Đăng ký
                                                    </button>
                                            </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                </div>
            </section>

            <section class="registry-section registered-section">
                <div class="section-heading">
                    <button type="button" class="registry-collapse-toggle" data-target="registeredRegistryContent" aria-expanded="true">
                        <h3>Chức năng đã có trong database</h3>
                        <svg class="collapse-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <span class="section-count"><?= count($registeredFunctions) ?></span>
                </div>

                <div class="registry-section-content" id="registeredRegistryContent">
                <?php if (empty($registeredFunctions)): ?>
                    <div class="empty-state compact">
                        <h3>Chưa có chức năng nào từ registry trong database.</h3>
                    </div>
                <?php else: ?>
                    <div class="table-wrapper">
                        <table class="data-table table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>CODE</th>
                                    <th>TÊN CHỨC NĂNG</th>
                                    <th>PAGE</th>
                                    <th>MODULE</th>
                                    <th>ĐÃ GÁN ROLE</th>
                                    <th>THAO TÁC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registeredFunctions as $function): ?>
                                    <?php $assignmentCount = (int) ($function['ASSIGNMENT_COUNT'] ?? 0); ?>
                                    <tr>
                                        <td><code><?= htmlspecialchars((string) $function['MA_CHUC_NANG_CODE'], ENT_QUOTES, 'UTF-8') ?></code></td>
                                        <td><?= htmlspecialchars((string) $function['TEN_CHUC_NANG'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><code><?= htmlspecialchars((string) $function['PAGE'], ENT_QUOTES, 'UTF-8') ?></code></td>
                                        <td><?= htmlspecialchars((string) $function['MODULE'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= $assignmentCount ?></td>
                                        <td>
                                            <div class="action-group">
                                            <form method="post" action="/KhoaLuan/public/admin.php?page=register_permissions" style="display:inline">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="code" value="<?= htmlspecialchars((string) $function['MA_CHUC_NANG_CODE'], ENT_QUOTES, 'UTF-8') ?>">
                                                <button
                                                    type="submit"
                                                    class="action-btn delete btn btn-danger"
                                                    title="<?= $assignmentCount > 0 ? 'Không thể xóa chức năng đang được gán cho vai trò' : 'Xóa chức năng khỏi database' ?>"
                                                    aria-label="<?= $assignmentCount > 0 ? 'Không thể xóa chức năng đang được gán cho vai trò' : 'Xóa chức năng khỏi database' ?>"
                                                >
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M19 7l-1 12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2l-1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3M9 11v6M15 11v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
    .permission-registry-page { display: grid; gap: 0; padding: 24px; }
    .permission-registry-page .page-panel {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .permission-registry-page .panel-header {
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }
    .registry-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }
    .permission-registry-page .panel-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f2a5a;
        margin: 0;
    }
    .panel-subtitle {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
    }
    .permission-registry-page .panel-body {
        padding: 20px;
        display: grid;
        gap: 18px;
    }
    .registry-alert {
        margin: 0;
        font-size: 13px;
        border-radius: 8px;
    }
    .registry-alert ul {
        margin: 8px 0 0;
        padding-left: 18px;
    }
    .registry-section {
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
    }
    .section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 14px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }
    .registry-section.is-collapsed .section-heading {
        border-bottom: 0;
    }
    .registry-collapse-toggle {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
        padding: 0;
        border: 0;
        background: transparent;
        color: #0f2a5a;
        cursor: pointer;
        text-align: left;
    }
    .registry-collapse-toggle:hover,
    .registry-collapse-toggle:focus {
        color: #0f2a5a;
        outline: none;
    }
    .registry-collapse-toggle .collapse-arrow {
        flex: 0 0 auto;
        color: currentColor;
        transition: transform .18s ease;
    }
    .registry-section.is-collapsed .collapse-arrow {
        transform: rotate(-90deg);
    }
    .registry-section-content {
        display: block;
    }
    .registry-section-content.is-hidden {
        display: none;
    }
    .section-heading h3 {
        margin: 0;
        color: #0f2a5a;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
    }
    .registry-collapse-toggle:hover h3,
    .registry-collapse-toggle:focus h3 {
        color: #0f2a5a;
    }
    .section-count {
        min-width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: #eef2f7;
        color: #0f2a5a;
        font-size: 12px;
        font-weight: 800;
    }
    .table-wrapper { overflow-x: auto; }
    .data-table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
        font-size: 13px;
    }
    .data-table th {
        padding: 12px 14px;
        text-align: center;
        font-weight: 800;
        color: #0f2a5a;
        text-transform: uppercase;
        letter-spacing: .4px;
        font-size: 11px;
        border-right: 1px solid #d1d5db;
        white-space: nowrap;
    }
    .data-table td {
        padding: 12px 14px;
        color: #1f2937;
        text-align: center;
        border-right: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    .data-table code {
        color: #0f2a5a;
        background: #eef2f7;
        border-radius: 6px;
        padding: 3px 6px;
        font-size: 12px;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 26px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }
    .status-badge.active {
        background: #dcfce7;
        color: #166534;
    }
    .permission-registry-page .action-btn {
        width: auto !important;
        height: auto !important;
        padding: 8px 16px !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        font-weight: 800 !important;
        line-height: 1.2 !important;
        white-space: nowrap;
    }
    .permission-registry-page .action-btn.primary {
        background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%) !important;
        border-color: #0f2a5a !important;
        color: #ffffff !important;
    }
    .permission-registry-page .action-group {
        display: flex;
        gap: 8px;
        justify-content: center;
    }
    .permission-registry-page .action-btn.edit {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 6px !important;
        background: #ffffff !important;
        color: #1d4ed8 !important;
        cursor: pointer;
        transition: all .2s;
    }
    .permission-registry-page .action-btn.edit:hover {
        color: #1d4ed8 !important;
        background: #eff6ff !important;
        border-color: #d1d5db !important;
    }
    .permission-registry-page .action-btn.edit svg {
        width: 16px !important;
        height: 16px !important;
        stroke: currentColor !important;
    }
    .permission-registry-page .action-btn.register {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 96px !important;
        height: 42px !important;
        padding: 0 18px !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 10px !important;
        background: #ffffff !important;
        color: #1d4ed8 !important;
        font-size: 13px !important;
        font-weight: 800 !important;
        line-height: 1.2 !important;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
    }
    .permission-registry-page .action-btn.register:hover {
        color: #1d4ed8 !important;
        background: #eff6ff !important;
        border-color: #d1d5db !important;
    }
    .permission-registry-page .action-btn.delete {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 6px !important;
        background: #ffffff !important;
        color: #dc2626 !important;
        cursor: pointer;
        transition: all .2s;
    }
    .permission-registry-page .action-btn.delete:hover {
        color: #dc2626 !important;
        background: #fef2f2 !important;
        border-color: #d1d5db !important;
    }
    .permission-registry-page .action-btn.delete svg {
        width: 16px !important;
        height: 16px !important;
        stroke: currentColor !important;
    }
    .permission-registry-page .action-btn.delete:disabled {
        opacity: .55;
        cursor: not-allowed;
    }
    .empty-state {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 46px 20px;
        text-align: center;
        color: #6b7280;
    }
    .empty-state.compact { padding: 28px 20px; }
    .empty-state h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
    }
    @media (max-width: 768px) {
        .permission-registry-page { padding: 16px; }
        .registry-header { align-items: flex-start; flex-direction: column; }
        .data-table { min-width: 860px; }
    }
</style>

<script>
    (function() {
        document.querySelectorAll('.registry-collapse-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                var targetId = toggle.getAttribute('data-target');
                var content = targetId ? document.getElementById(targetId) : null;
                var section = toggle.closest('.registry-section');
                if (!content || !section) return;

                var isHidden = content.classList.toggle('is-hidden');
                section.classList.toggle('is-collapsed', isHidden);
                toggle.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
            });
        });
    })();
</script>
