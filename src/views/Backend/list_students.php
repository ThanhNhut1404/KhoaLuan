<?php
$students = $students ?? [];
$pagination = $pagination ?? [
    'current_page' => 1,
    'total_items' => count($students),
    'items_per_page' => 10,
    'total_pages' => 1,
    'from' => empty($students) ? 0 : 1,
    'to' => count($students),
];
$emptyMessage = $emptyMessage ?? 'Chưa có sinh viên nào.';

$paginationUrl = static function (int $pageNum): string {
    $params = $_GET;
    $params['page'] = 'list_students';
    $params['page_num'] = $pageNum;

    return '?' . http_build_query($params);
};
?>

<div class="list-student-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH SINH VIÊN</h2>
                <a href="?page=create_student" class="action-btn btn btn-primary">Tạo sinh viên</a>
            </div>
        </div>

        <div class="panel-body card-body">
            <?php if (empty($students)): ?>
                <div class="empty-state">
                    <h3><?= htmlspecialchars($emptyMessage) ?></h3>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="col-stt">STT</th>
                                <th>MSSV</th>
                                <th>Tên đăng nhập</th>
                                <th>Lớp</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Khóa học</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $index => $student): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) (($pagination['current_page'] - 1) * $pagination['items_per_page'] + $index + 1)) ?></td>
                                    <td><?= htmlspecialchars($student['mssv'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['username'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['class_name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['email'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['phone'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['academic_year'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($student['status'] ?? '') ?></td>
                                    <td>
                                        <a href="?page=edit_student&id=<?= htmlspecialchars((string) ($student['id'] ?? '')) ?>" class="btn btn-outline-primary">Sửa</a>
                                        <form method="post" action="?page=delete_student" style="display:inline">
                                            <input type="hidden" name="student_id" value="<?= htmlspecialchars((string) ($student['id'] ?? '')) ?>" />
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này không?');">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    <div class="pagination-info">
                        Hiển thị <?= (int) $pagination['from'] ?> - <?= (int) $pagination['to'] ?> của <?= (int) $pagination['total_items'] ?> sinh viên
                    </div>
                    <nav class="pagination-nav">
                        <ul class="pagination mb-0">
                            <?php if ($pagination['current_page'] > 1): ?>
                                <li class="page-item"><a href="<?= htmlspecialchars($paginationUrl(1)) ?>" class="page-link">&lt;&lt;</a></li>
                                <li class="page-item"><a href="<?= htmlspecialchars($paginationUrl($pagination['current_page'] - 1)) ?>" class="page-link">&lt;</a></li>
                            <?php else: ?>
                                <li class="page-item disabled"><span class="page-link">&lt;&lt;</span></li>
                                <li class="page-item disabled"><span class="page-link">&lt;</span></li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                                    <a href="<?= htmlspecialchars($paginationUrl($i)) ?>" class="page-link"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                <li class="page-item"><a href="<?= htmlspecialchars($paginationUrl($pagination['current_page'] + 1)) ?>" class="page-link">&gt;</a></li>
                                <li class="page-item"><a href="<?= htmlspecialchars($paginationUrl($pagination['total_pages'])) ?>" class="page-link">&gt;&gt;</a></li>
                            <?php else: ?>
                                <li class="page-item disabled"><span class="page-link">&gt;</span></li>
                                <li class="page-item disabled"><span class="page-link">&gt;&gt;</span></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .list-student-page { display: grid; gap: 0; padding: 24px; }
    .page-panel { background: #ffffff; border: 1px solid #e8ecf3; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden; }
    .panel-header { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .header-content { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
    .panel-title { font-size: 14px; font-weight: 700; color: #0f2a5a; margin: 0; }
    .panel-body { padding: 20px; }
    .table-responsive { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th, .data-table td { padding: 12px 10px; }
    .data-table th { background: #f8fafc; font-weight: 700; }
    .btn { display: inline-flex; align-items: center; justify-content: center; padding: 8px 14px; border-radius: 8px; font-size: 13px; text-decoration: none; }
    .btn-outline-primary { border: 1px solid #0f2a5a; color: #0f2a5a; background: transparent; }
    .btn-danger { border: 1px solid #dc2626; color: #fff; background: #dc2626; }
    .pagination-container { margin-top: 16px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
    .pagination-nav .pagination { display: flex; gap: 6px; flex-wrap: wrap; padding-left: 0; list-style: none; margin: 0; }
    .page-link { display: inline-flex; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 8px; color: #0f2a5a; text-decoration: none; }
    .page-item.active .page-link { background: #0f2a5a; color: #ffffff; }
    .page-item.disabled .page-link { color: #94a3b8; cursor: not-allowed; }
    @media (max-width: 768px) { .header-content { flex-direction: column; align-items: stretch; } }
</style>

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

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .panel-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f2a5a;
        margin: 0;
    }

    .panel-body {
        padding: 20px;
    }

    .student-management-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .student-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(15, 42, 90, 0.06);
    }

    .student-card-body {
        padding: 18px;
        display: grid;
        gap: 10px;
    }

    .student-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f2a5a;
        margin: 0;
    }

    .student-card p {
        margin: 0;
        color: #475569;
        font-size: 13px;
        line-height: 1.6;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none;
    }

    .btn-outline-secondary {
        border: 1px solid #cbd5f5;
        background: #fff;
        color: #0f2a5a;
    }

    .btn-primary {
        background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%);
        border-color: #0f2a5a;
        color: #ffffff;
    }

    @media (max-width: 768px) {
        .student-management-grid { grid-template-columns: 1fr; }
    }
</style>
