<?php
    $notifications = is_array($notifications ?? null) ? $notifications : [];
    $notificationFilters = is_array($notificationFilters ?? null) ? $notificationFilters : [];
    $notificationFilterOptions = is_array($notificationFilterOptions ?? null) ? $notificationFilterOptions : ['types' => [], 'senders' => []];
    $notificationError = (string) ($notificationError ?? '');

    $selectedReadStatus = (string) ($notificationFilters['read_status'] ?? '');
    $selectedType = (string) ($notificationFilters['type'] ?? '');
    $selectedSender = (string) ($notificationFilters['sender'] ?? '');
    $selectedKeyword = trim((string) ($notificationFilters['keyword'] ?? ''));
    $hasActiveFilters = $selectedReadStatus !== '' || $selectedType !== '' || $selectedSender !== '' || $selectedKeyword !== '';

    $escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    $formatDate = static function ($value): string {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        try {
            return (new DateTime($value))->format('d/m/Y H:i');
        } catch (Throwable) {
            return $value;
        }
    };
    $typeClass = static function (string $type): string {
        return str_contains(mb_strtolower($type, 'UTF-8'), 'hoạt') ? 'activity' : 'system';
    };
?>

<style>
    .notif-container {
        display: flex;
        flex-direction: column;
        gap: 0;
        animation: fadeIn 0.4s ease-out;
        overflow: visible;
    }

    .activity-page-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
        text-transform: none;
        letter-spacing: 0.6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .activity-panel {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: visible;
    }

    .activity-panel__header {
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        overflow: visible;
    }

    .activity-panel__body {
        padding: 14px;
    }

    .filter-btn {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: var(--primary);
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .filter-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .notif-filter-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-left: auto;
    }

    .notif-filter-toggle {
        width: 32px;
        height: 32px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #fff;
        color: var(--primary);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
    }

    .notif-filter-wrap.has-active .notif-filter-toggle,
    .notif-filter-toggle.active {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    .notif-filter-toggle:hover {
        background: #f8fafc;
        color: #0b1f45;
    }

    .notif-filter-toggle svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
        flex: 0 0 16px;
    }

    .filter-btn.primary {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .filter-btn.primary:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    .notif-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .notif-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e8ecf3;
        padding: 16px 20px;
        cursor: pointer;
        position: relative;
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: flex-start;
        gap: 16px;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }

    .notif-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 18px rgba(var(--primary-rgb), 0.06);
        transform: translateY(-2px);
    }

    .notif-card.unread {
        background: #f8faff;
        border-left: 4px solid var(--primary);
    }

    .notif-card.unread .notif-item-title {
        font-weight: 800;
        color: #0f172a;
    }

    .notif-unread-dot {
        width: 8px;
        height: 8px;
        background: var(--primary);
        border-radius: 50%;
        display: inline-block;
        margin-left: 8px;
        box-shadow: 0 0 8px rgba(var(--primary-rgb), 0.6);
    }

    .notif-icon-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: all 0.2s ease;
    }

    .notif-icon-circle.system {
        background: #fef3c7;
        color: #d97706;
    }

    .notif-icon-circle.activity {
        background: #e0f2fe;
        color: #0284c7;
    }

    .notif-item-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .notif-item-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        font-size: 12px;
        color: #94a3b8;
    }

    .notif-sender {
        font-weight: 700;
        color: #64748b;
    }

    .notif-dot-separator {
        width: 4px;
        height: 4px;
        background: #cbd5e1;
        border-radius: 50%;
    }

    .notif-time {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .notif-item-title {
        font-size: 15px;
        font-weight: 650;
        color: #334155;
        line-height: 1.4;
    }

    .notif-preview-text {
        font-size: 13.5px;
        color: #64748b;
        line-height: 1.5;
        margin-top: 4px;
        word-break: break-word;
    }

    .notif-badge {
        display: inline-flex;
        align-items: center;
        padding: 2px 10px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .notif-badge.system {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fef3c7;
    }

    .notif-badge.activity {
        background: #f0f9ff;
        color: #0369a1;
        border: 1px solid #e0f2fe;
    }

    .notif-content-drawer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        grid-column: 1 / -1;
    }

    .notif-card.expanded .notif-content-drawer {
        max-height: 360px;
    }

    .notif-card.expanded {
        border-color: var(--primary);
    }

    .notif-card.expanded .notif-angle i {
        transform: rotate(180deg);
    }

    .notif-expanded-body {
        padding-top: 14px;
        margin-top: 12px;
        border-top: 1px dashed #e2e8f0;
        font-size: 14px;
        color: #475569;
        line-height: 1.6;
    }

    .notif-angle {
        color: #94a3b8;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .notif-card:hover .notif-angle {
        background: #f1f5f9;
        color: #475569;
    }

    .notif-angle i {
        transition: transform 0.3s ease;
    }

    .notif-empty-state {
        background: #ffffff;
        border-radius: 16px;
        border: 1px dashed #cbd5e1;
        padding: 48px 24px;
        text-align: center;
        display: <?= empty($notifications) ? 'flex' : 'none' ?>;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 16px;
        animation: fadeIn 0.3s ease-out;
    }

    .notif-empty-icon {
        width: 72px;
        height: 72px;
        background: #f8fafc;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #cbd5e1;
        font-size: 32px;
        margin-bottom: 8px;
    }

    .notif-empty-state h3 {
        font-size: 16px;
        font-weight: 700;
        color: #475569;
        margin: 0;
    }

    .notif-empty-state p {
        font-size: 14px;
        color: #94a3b8;
        margin: 0;
        max-width: 360px;
    }

    .notif-filter-modal {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        z-index: 20;
        width: max-content;
        max-width: calc(100vw - 48px);
        padding: 14px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.16);
        display: none;
    }

    .notif-filter-modal.open {
        display: block;
    }

    .notif-filter-card {
        width: max-content;
        max-width: 100%;
        background: transparent;
        border: 0;
        box-shadow: none;
        overflow: visible;
    }

    .notif-filter-form {
        width: max-content;
        max-width: 100%;
    }

    .notif-filter-body {
        display: grid;
        grid-template-columns: repeat(3, max-content);
        gap: 12px;
    }

    .notif-filter-field {
        display: grid;
        gap: 6px;
        font-size: 12px;
        color: var(--primary);
        font-weight: 700;
        width: max-content;
    }

    .notif-filter-field label {
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
    }

    .notif-filter-field select {
        width: auto;
        min-width: 132px;
        min-height: 38px;
        padding: 0 34px 0 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background-color: #f9fafb;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231047a1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-position: right 10px center;
        background-repeat: no-repeat;
        background-size: 16px;
        color: #1f2937;
        font-size: 13px;
        font-weight: 600;
        outline: none;
        cursor: pointer;
        appearance: none;
    }

    .notif-filter-field select:focus {
        border-color: var(--primary-border-strong);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.12);
    }

    #readStatusFilterClean {
        min-width: 132px;
    }

    #typeFilterClean {
        min-width: 148px;
    }

    #senderFilterClean {
        min-width: 150px;
    }

    .notif-filter-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 14px;
    }

    .notif-filter-actions .filter-reset-btn,
    .notif-filter-actions .filter-apply-btn {
        min-height: 38px;
        padding: 8px 20px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.2;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .notif-filter-actions .filter-reset-btn {
        color: #dc2626 !important;
        background: #ffffff !important;
        border-color: #e5e7eb !important;
    }

    .notif-filter-actions .filter-reset-btn:hover {
        color: #dc2626 !important;
        background: #e5e7eb !important;
        border-color: #cbd5e1 !important;
    }

    .notif-filter-actions .filter-apply-btn {
        color: #ffffff !important;
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%) !important;
        border-color: #16a34a !important;
    }

    .notif-filter-actions .filter-apply-btn:hover {
        color: #ffffff !important;
        background: linear-gradient(180deg, #15803d 0%, #166534 100%) !important;
        border-color: #15803d !important;
    }

    .notif-error {
        padding: 12px 14px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #b91c1c;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .notif-filter-body {
            grid-template-columns: repeat(2, max-content);
        }

        .notif-card {
            grid-template-columns: auto 1fr;
            padding: 14px;
            gap: 12px;
        }

        .notif-angle {
            grid-column: 2;
            justify-self: end;
            margin-top: -8px;
        }
    }

    @media (max-width: 560px) {
        .notif-filter-body {
            grid-template-columns: 1fr;
        }

        .notif-filter-field,
        .notif-filter-field select {
            width: 100%;
        }

        .notif-filter-actions {
            flex-direction: column-reverse;
        }

        .notif-filter-actions .filter-btn {
            width: 100%;
        }
    }
</style>

<div class="notif-container">
    <div class="activity-panel card">
        <div class="activity-panel__header card-header">
            <div class="activity-page-title">
                <i class="fa-solid fa-bell"></i>
                Thông báo
            </div>
            <div class="notif-filter-wrap <?= $hasActiveFilters ? 'has-active' : '' ?>">
                <button class="notif-filter-toggle btn btn-outline-secondary" id="notifFilterToggle" type="button" onclick="openNotifFilter()" title="Bộ lọc" aria-label="Bộ lọc" aria-expanded="false">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M4 5h16l-6 7v5l-4 2v-7L4 5Z" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <div class="notif-filter-modal" id="notifFilterModal" aria-hidden="true">
                    <div class="notif-filter-card">
                        <form class="notif-filter-form" method="get" action="/KhoaLuan/public/student.php">
                            <input type="hidden" name="action" value="thongbao">
                            <div class="notif-filter-body">
                                <div class="notif-filter-field">
                                    <label for="readStatusFilterClean">Trạng thái đọc</label>
                                    <select id="readStatusFilterClean" name="read_status">
                                        <option value="">Tất cả</option>
                                        <option value="unread" <?= $selectedReadStatus === 'unread' ? 'selected' : '' ?>>Chưa đọc</option>
                                        <option value="read" <?= $selectedReadStatus === 'read' ? 'selected' : '' ?>>Đã đọc</option>
                                    </select>
                                </div>

                                <div class="notif-filter-field">
                                    <label for="typeFilterClean">Loại thông báo</label>
                                    <select id="typeFilterClean" name="type">
                                        <option value="">Tất cả</option>
                                        <?php foreach (($notificationFilterOptions['types'] ?? []) as $option): ?>
                                            <?php $value = (string) ($option['value'] ?? ''); ?>
                                            <option value="<?= $escape($value) ?>" <?= $selectedType === $value ? 'selected' : '' ?>><?= $escape($option['label'] ?? $value) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="notif-filter-field">
                                    <label for="senderFilterClean">Đơn vị gửi</label>
                                    <select id="senderFilterClean" name="sender">
                                        <option value="">Tất cả</option>
                                        <?php foreach (($notificationFilterOptions['senders'] ?? []) as $option): ?>
                                            <?php $value = (string) ($option['value'] ?? ''); ?>
                                            <option value="<?= $escape($value) ?>" <?= $selectedSender === $value ? 'selected' : '' ?>><?= $escape($option['label'] ?? $value) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="notif-filter-actions">
                                <a class="filter-btn filter-reset-btn btn btn-light" href="/KhoaLuan/public/student.php?action=thongbao">Đặt lại</a>
                                <button class="filter-btn filter-apply-btn primary btn btn-primary" type="submit">Áp dụng</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="activity-panel__body card-body">

    <?php if ($notificationError !== ''): ?>
        <div class="notif-error"><?= $escape($notificationError) ?></div>
    <?php endif; ?>

    <div class="notif-list" id="notifList">
        <?php foreach ($notifications as $notification): ?>
            <?php
                $id = (int) ($notification['id'] ?? 0);
                $isRead = (int) ($notification['is_read'] ?? 0) === 1;
                $typeName = trim((string) ($notification['type_name'] ?? 'Hệ thống'));
                $badgeClass = $typeClass($typeName);
                $icon = $badgeClass === 'activity' ? 'fa-person-running' : 'fa-gear';
                $titleText = trim((string) ($notification['title'] ?? 'Thông báo'));
                $bodyText = trim((string) ($notification['body'] ?? ''));
                $preview = mb_strlen($bodyText, 'UTF-8') > 170 ? mb_substr($bodyText, 0, 170, 'UTF-8') . '...' : $bodyText;
                $sender = trim((string) ($notification['sender'] ?? 'Hệ thống'));
                $createdAt = $formatDate($notification['created_at'] ?? '');
            ?>
            <div class="notif-card <?= $isRead ? '' : 'unread' ?> card" id="notif-<?= $id ?>" data-status="<?= $isRead ? 'read' : 'unread' ?>" onclick="toggleExpand('notif-<?= $id ?>')">
                <div class="notif-icon-circle <?= $badgeClass ?>" aria-hidden="true">
                    <i class="fa-solid <?= $icon ?>"></i>
                </div>
                <div class="notif-item-details">
                    <div class="notif-item-meta">
                        <span class="notif-sender"><?= $escape($sender) ?></span>
                        <?php if ($createdAt !== ''): ?>
                            <span class="notif-dot-separator" aria-hidden="true"></span>
                            <span class="notif-time" title="<?= $escape($notification['created_at'] ?? '') ?>">
                                <i class="fa-regular fa-clock"></i> <?= $escape($createdAt) ?>
                            </span>
                        <?php endif; ?>
                        <span class="notif-dot-separator" aria-hidden="true"></span>
                        <span class="notif-badge <?= $badgeClass ?> badge rounded-pill"><?= $escape($typeName) ?></span>
                    </div>
                    <div class="notif-item-title">
                        <?= $escape($titleText) ?>
                        <?php if (!$isRead): ?>
                            <span class="notif-unread-dot" id="dot-notif-<?= $id ?>"></span>
                        <?php endif; ?>
                    </div>
                    <div class="notif-preview-text"><?= $escape($preview) ?></div>
                </div>
                <div class="notif-angle" aria-label="Mở rộng chi tiết">
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="notif-content-drawer">
                    <div class="notif-expanded-body"><?= nl2br($escape($bodyText)) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="notif-empty-state" id="notifEmptyState">
        <div class="notif-empty-icon" aria-hidden="true">
            <i class="fa-regular fa-bell-slash"></i>
        </div>
        <h3>Không có thông báo nào</h3>
        <p>Hộp thư thông báo của bạn đang trống hoặc không có thông báo nào phù hợp với bộ lọc.</p>
    </div>
        </div>
    </div>
</div>

<script>
    function toggleExpand(id) {
        var card = document.getElementById(id);
        if (!card) return;
        var wasExpanded = card.classList.contains('expanded');

        document.querySelectorAll('.notif-card').forEach(function(item) {
            item.classList.remove('expanded');
        });

        if (!wasExpanded) {
            card.classList.add('expanded');
            if (card.classList.contains('unread')) {
                markAsRead(id);
            }
        }
    }

    function markAsRead(id) {
        var card = document.getElementById(id);
        if (!card || !card.classList.contains('unread')) return;
        card.classList.remove('unread');
        card.setAttribute('data-status', 'read');

        var dot = document.getElementById('dot-' + id);
        if (dot) {
            dot.remove();
        }
    }

    function openNotifFilter() {
        var modal = document.querySelector('.notif-filter-wrap #notifFilterModal');
        if (!modal) return;
        var isOpen = modal.classList.contains('open');
        modal.classList.toggle('open', !isOpen);
        modal.setAttribute('aria-hidden', isOpen ? 'true' : 'false');
        var toggle = document.getElementById('notifFilterToggle');
        if (toggle) {
            toggle.classList.toggle('active', !isOpen);
            toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        }
    }

    function closeNotifFilter() {
        var modal = document.querySelector('.notif-filter-wrap #notifFilterModal');
        if (!modal) return;
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        var toggle = document.getElementById('notifFilterToggle');
        if (toggle) {
            toggle.classList.remove('active');
            toggle.setAttribute('aria-expanded', 'false');
        }
    }

    document.addEventListener('click', function(event) {
        var filterWrap = document.querySelector('.notif-filter-wrap');
        if (filterWrap && !filterWrap.contains(event.target)) {
            closeNotifFilter();
        }
    });
</script>
