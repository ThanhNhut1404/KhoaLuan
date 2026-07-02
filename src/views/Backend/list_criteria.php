<?php
$semesters = $semesters ?? [];
$selectedSemesterId = $selectedSemesterId ?? 0;
$criteria = $criteria ?? [];
$filters = $filters ?? ['keyword' => ''];
$currentKeyword = trim((string) ($filters['keyword'] ?? ''));
$hasCriteria = !empty($criteria);
?>

<div class="list-criteria-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH TIÊU CHÍ</h2>
                <div class="criteria-actions">
                    <a class="action-btn primary btn btn-primary" href="?page=configure_criteria">Thêm tiêu chí mới</a>
                </div>
            </div>
            <form method="GET" action="/KhoaLuan/public/admin.php" class="filter-form">
                <input type="hidden" name="page" value="list_criteria" />
                <div class="filter-row">
                    <div class="filter-item">
                        <label class="field-label" for="semesterFilter">Học kỳ</label>
                        <select id="semesterFilter" name="semester_id" class="field-input form-select" onchange="this.form.submit()">
                            <?php foreach ($semesters as $semester): ?>
                                <option value="<?= htmlspecialchars($semester['id'], ENT_QUOTES, 'UTF-8') ?>" <?= (int) $selectedSemesterId === (int) $semester['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($semester['name'] ?? 'Không xác định', ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label class="field-label" for="keyword">Tìm kiếm</label>
                        <input id="keyword" name="keyword" type="text" value="<?= htmlspecialchars($currentKeyword, ENT_QUOTES, 'UTF-8') ?>" class="field-input form-control" placeholder="Tên tiêu chí hoặc mô tả" />
                    </div>
                    <div class="filter-item filter-actions">
                        <button type="submit" class="action-btn primary btn btn-primary">Tìm</button>
                        <a href="?page=list_criteria<?= $selectedSemesterId ? '&semester_id=' . urlencode($selectedSemesterId) : '' ?>" class="action-btn secondary btn btn-outline-secondary">Đặt lại</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="panel-body card-body">
            <?php if (!$hasCriteria): ?>
                <div class="empty-state">
                    <h3>Không tìm thấy tiêu chí nào.</h3>
                    <p>Hãy chọn học kỳ khác hoặc thêm tiêu chí mới.</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Tên tiêu chí</th>
                                <th>Mô tả</th>
                                <th>Điểm cộng</th>
                                <th>Điểm trừ</th>
                                <th>Lần thực hiện</th>
                                <th>Thứ tự</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($criteria as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($item['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($item['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) ($item['credit'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) ($item['deduction'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) ($item['execution_round'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) ($item['display_order'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($item['status'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <a class="action-btn secondary btn btn-outline-secondary" href="?page=configure_criteria&id=<?= urlencode((string) ($item['id'] ?? '')) ?>">Sửa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.list-criteria-page { padding: 24px; }
.page-panel { background:#fff; border:1px solid #e8ecf3; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden; }
.panel-header { padding:12px 14px; border-bottom:1px solid #e5e7eb; background:#f9fafb; }
.header-content { display:flex; justify-content:space-between; align-items:center; gap:12px; }
.panel-title { margin:0; font-size:14px; font-weight:700; color:#0f2a5a; }
.criteria-actions { display:flex; gap:10px; flex-wrap:wrap; }
.panel-body { padding:20px; }
.filter-form .filter-row { display:grid; grid-template-columns: minmax(220px, 1fr) minmax(240px, 1fr) auto; gap:12px; align-items:end; }
.filter-item { display:flex; flex-direction:column; gap:6px; }
.filter-actions { display:flex; gap:10px; align-items:center; }
.filter-actions .action-btn { white-space:nowrap; }
.data-table { width:100%; border-collapse:collapse; font-size:13px; margin-top:12px; }
.data-table th, .data-table td { padding:12px 14px; border:1px solid #e5e7eb; text-align:left; vertical-align:middle; }
.data-table th { background:#f8f9fa; color:#0f2a5a; font-weight:700; }
.action-btn { display:inline-flex; align-items:center; justify-content:center; padding:8px 14px; border-radius:8px; border:1px solid #e5e7eb; background:#fff; color:#0f2a5a; text-decoration:none; font-size:13px; }
.action-btn.primary { background:#0f2a5a; color:#fff; border-color:#0f2a5a; }
.action-btn.secondary { background:#fff; color:#0f2a5a; }
.empty-state { padding:24px; text-align:center; color:#475569; }
@media (max-width: 900px) { .filter-form .filter-row { grid-template-columns: 1fr; } .header-content { flex-direction:column; align-items:flex-start; } }
</style>
