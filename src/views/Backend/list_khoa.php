<?php
    $khoas = $khoas ?? [];
    $pagination = $pagination ?? ['current_page'=>1,'total_items'=>count($khoas),'items_per_page'=>10,'total_pages'=>1];
    $current_page = $pagination['current_page'];
    $total_items = $pagination['total_items'];
    $items_per_page = $pagination['items_per_page'];
    $total_pages = $pagination['total_pages'];
?>

<div class="list-khoa-page">
    <div class="page-panel card">
        <div class="panel-header card-header">
            <div class="header-content">
                <h2 class="panel-title">DANH SÁCH KHOA</h2>
                <div>
                    <a href="?page=create_khoa" class="btn-create">Tạo khoa</a>
                </div>
            </div>
        </div>

        <div class="panel-body card-body">
            <?php if (isset($adminToast) && $adminToast): ?>
                <div class="alert alert-<?= htmlspecialchars($adminToast['type'] ?? 'info') ?>" style="margin-bottom:12px;padding:10px;border-radius:8px;">
                    <?= htmlspecialchars($adminToast['message'] ?? '') ?>
                </div>
            <?php endif; ?>
            <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
            <?php if (!empty($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?>" style="margin-bottom:12px;padding:10px;border-radius:8px;">
                    <?= htmlspecialchars($_SESSION['message']) ?>
                </div>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            <?php endif; ?>
            <?php if (empty($khoas)): ?>
                <div class="empty-state">
                    <h3>Chưa có khoa nào</h3>
                    <p>Hãy tạo khoa để bắt đầu</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="col-stt">STT</th>
                                <th class="col-code">MÃ KHOA</th>
                                <th class="col-name">TÊN KHOA</th>
                                <th class="col-email">EMAIL</th>
                                <th class="col-phone">SỐ ĐIỆN THOẠI</th>
                                <th class="col-action">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($khoas as $index => $k): ?>
                                <tr data-id="<?= htmlspecialchars($k['ma'] ?? '') ?>">
                                    <td class="col-stt">0<?= $index + 1 ?></td>
                                    <td class="col-code"><?= htmlspecialchars($k['ma'] ?? '') ?></td>
                                    <td class="col-name"><?= htmlspecialchars($k['ten'] ?? '') ?></td>
                                    <td class="col-email"><?= htmlspecialchars($k['email'] ?? '') ?></td>
                                    <td class="col-phone"><?= htmlspecialchars($k['phone'] ?? '') ?></td>
                                    <td class="col-action">
                                        <div class="action-group">
                                            <a class="action-btn edit btn btn-outline-primary" title="Chỉnh sửa" href="?page=edit_khoa&ma=<?= urlencode($k['ma'] ?? '') ?>">✎</a>
                                            <form method="POST" style="display:inline-block;" onsubmit="return confirm('Xóa khoa này?');">
                                                <input type="hidden" name="action" value="delete" />
                                                <input type="hidden" name="ma" value="<?= htmlspecialchars($k['ma'] ?? '') ?>" />
                                                <button type="submit" class="action-btn delete btn btn-danger" title="Xóa">🗑</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    <div class="pagination-info">
                        Hiển thị 1 - <?= min($items_per_page, $total_items) ?> của <?= $total_items ?> khoa
                    </div>
                    <div class="pagination">
                        <?php if ($current_page > 1): ?>
                            <a href="?page=list_khoa&page_num=<?= $current_page - 1 ?>" class="pagination-btn prev">‹</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=list_khoa&page_num=<?= $i ?>" class="pagination-btn <?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=list_khoa&page_num=<?= $current_page + 1 ?>" class="pagination-btn next">›</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
