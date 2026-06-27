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

    .action-btn {
        padding: 6px 10px;
        border-radius: 8px;
        border: 1px solid #fecaca;
        background: #fee2e2;
        color: #b91c1c;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn:hover { filter: brightness(0.96); }

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
    <h2 class="joined-title">Hoạt động đã đăng ký</h2>

    <div class="joined-card card">
        <div class="joined-toolbar">
            <label class="term-select">
                <select class="form-select" name="term" aria-label="Chon hoc ky">
                    <option value="2024-2025-1">Hoc ky 1 (2024-2025)</option>
                    <option value="2024-2025-2" selected>Hoc ky 2 (2024-2025)</option>
                    <option value="2025-2026-1">Hoc ky 1 (2025-2026)</option>
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
                    <th class="cell-center">Tương tác</th>
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
                            <td><?= htmlspecialchars($activity['level'] ?? '') ?></td>
                            <td class="cell-center">
                                <button class="action-btn btn btn-danger" type="button">Huy</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-state">Chưa có hoạt động nào đã đăng ký.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table></div>
    </div>
</div>
