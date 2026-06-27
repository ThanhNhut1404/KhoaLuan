<?php
    $student = $student ?? [];
    $activities = $activities ?? [];
?>

<style>
    .joined-page {
        display: grid;
        gap: 16px;
    }

    .joined-title {
        font-size: 18px;
        font-weight: 700;
        color: #1d4ed8;
        text-align: center;
        letter-spacing: 0.4px;
    }

    .joined-card {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .joined-toolbar {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-bottom: 12px;
    }

    .term-select {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        font-size: 12px;
        font-weight: 700;
        color: #1f2937;
    }

    .term-select select {
        border: none;
        background: transparent;
        font-size: 12px;
        font-weight: 700;
        color: #1f2937;
        outline: none;
        cursor: pointer;
    }

    .joined-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .joined-table thead th {
        background: #eef2ff;
        color: #1f2937;
        text-align: center;
        padding: 10px 12px;
        border-bottom: 1px solid #e8ecf3;
        border-right: 1px solid #e2e8f0;
        font-weight: 700;
        white-space: nowrap;
    }

    .joined-table thead th:last-child {
        border-right: none;
    }

    .joined-table tbody td {
        padding: 10px 12px;
        border-bottom: 1px solid #eef2f7;
        border-right: 1px solid #eef2f7;
        color: #334155;
        vertical-align: top;
    }

    .joined-table tbody tr {
        border-top: 1px solid #eef2f7;
    }

    .joined-table tbody tr:first-child {
        border-top: none;
    }

    .joined-table tbody td:last-child {
        border-right: none;
    }

    .joined-table tbody tr:hover {
        background: #f8fafc;
    }

    .cell-center { text-align: center; }
    .cell-right { text-align: right; }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .badge.level { background: #f0fdf4; color: #15803d; }
    .badge.score { background: #fff7ed; color: #c2410c; }

    .empty-state {
        text-align: center;
        color: #6b7280;
        padding: 16px 0;
        font-size: 13px;
    }

    @media (max-width: 900px) {
        .joined-card { padding: 12px; }
        .joined-table { font-size: 12px; }
    }
</style>

<div class="joined-page">
    <h2 class="joined-title">Hoạt động đã tham gia</h2>

    <div class="joined-card card">
        <div class="joined-toolbar">
            <label class="term-select">
                <select class="form-select" name="term" aria-label="Chọn học kỳ">
                    <option value="2024-2025-1">Học kỳ 1 (2024-2025)</option>
                    <option value="2024-2025-2" selected>Học kỳ 2 (2024-2025)</option>
                    <option value="2025-2026-1">Học kỳ 1 (2025-2026)</option>
                </select>
            </label>
        </div>
        <div class="table-responsive"><table class="joined-table table table-hover table-bordered align-middle">
            <thead>
                <tr>
                    <th class="cell-center">STT</th>
                    <th>Tên hoạt động</th>
                    <th>Loại hoạt động</th>
                    <th>Thời gian</th>
                    <th>Địa điểm</th>
                    <th>Cấp hoạt động</th>
                    <th class="cell-center">Điểm</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($activities)): ?>
                    <?php foreach ($activities as $index => $activity): ?>
                        <tr>
                            <td class="cell-center"><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($activity['name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($activity['type'] ?? '') ?></td>
                            <td><?= htmlspecialchars($activity['time'] ?? '') ?></td>
                            <td><?= htmlspecialchars($activity['location'] ?? '') ?></td>
                            <td><span class="badge level rounded-pill"><?= htmlspecialchars($activity['level'] ?? '') ?></span></td>
                            <td class="cell-center"><span class="badge score rounded-pill"><?= htmlspecialchars($activity['score'] ?? '') ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-state">Chưa có hoạt động nào đã tham gia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table></div>
    </div>
</div>
