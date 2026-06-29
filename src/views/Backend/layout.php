<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap');

        :root {
            --ink: #1f2937;
            --muted: #6b7280;
            --brand: #1d4ed8;
            --brand-2: #1047a1;
            --accent: #00a8e8;
            --bg: #f0f2f5;
            --card: #ffffff;
            --line: #e8ecf3;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Manrope', system-ui, sans-serif;
            background: var(--bg);
            color: var(--ink);
        }

        .container {
            display: flex;
            min-height: 100vh;
            max-width: none;
            padding-left: 0;
            padding-right: 0;
        }

        .sidebar {
            width: 247px;
            background: linear-gradient(180deg, #0f2a5a 0%, #0b1f45 100%);
            color: #e2e8f0;
            padding: 22px 16px;
            transition: width 0.2s ease;
        }

        .sidebar.collapsed { width: 86px; }

        .main { flex: 1; min-width: 0; }

        .header {
            background: var(--card);
            padding: 10px 20px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .content { padding: 4px 14px 22px; }

        .card.page-panel,
        .page-panel.card {
            display: block;
            background: #ffffff;
            border: 1px solid #e8ecf3;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .card-header.panel-header,
        .panel-header.card-header {
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .card-body.panel-body,
        .panel-body.card-body {
            padding: 20px;
        }

        .form-control.field-input,
        .form-select.field-input {
            display: block;
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border-color: #e5e7eb;
            background-color: #f9fafb;
            font-size: 13px;
            color: #1f2937;
            min-height: 40px;
        }

        .form-control.field-input:focus,
        .form-select.field-input:focus {
            border-color: #0f2a5a;
            box-shadow: 0 0 0 3px rgba(15, 42, 90, 0.08);
            background-color: #ffffff;
        }

        .form-label.field-label {
            margin-bottom: 0;
        }

        .btn.action-btn {
            font-size: 13px;
            font-weight: 700;
        }

        .action-btn.cancel-btn,
        .btn.action-btn.cancel-btn {
            color: #dc2626 !important;
            background: #ffffff !important;
            border-color: #e5e7eb !important;
        }

        .action-btn.cancel-btn:hover,
        .btn.action-btn.cancel-btn:hover {
            color: #dc2626 !important;
            background: #e5e7eb !important;
            border-color: #cbd5e1 !important;
        }

        .action-btn.save-change-btn,
        .btn.action-btn.save-change-btn {
            color: #ffffff !important;
            background: linear-gradient(180deg, #16a34a 0%, #15803d 100%) !important;
            border-color: #16a34a !important;
        }

        .action-btn.save-change-btn:hover,
        .btn.action-btn.save-change-btn:hover {
            color: #ffffff !important;
            background: linear-gradient(180deg, #15803d 0%, #166534 100%) !important;
            border-color: #15803d !important;
        }

        .table.data-table {
            margin-bottom: 0;
            font-size: 13px;
        }

        .data-table thead,
        .data-table thead.table-light,
        .data-table thead tr,
        .data-table thead th {
            background: #eef2f7 !important;
        }

        .data-table tbody tr:nth-child(odd),
        .data-table tbody tr:nth-child(odd) td {
            background: #ffffff !important;
        }

        .data-table tbody tr:nth-child(even),
        .data-table tbody tr:nth-child(even) td {
            background: #f1f5f9 !important;
        }

        .data-table tbody tr:hover,
        .data-table tbody tr:hover td {
            background: #e9edf3 !important;
        }

        .data-table .action-group {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
        }

        .data-table .action-btn.edit,
        .data-table .action-btn.delete {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 32px !important;
            height: 32px !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 6px !important;
            background: #ffffff !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
            padding: 0 !important;
            text-decoration: none !important;
            line-height: 1 !important;
        }

        .data-table .action-btn.edit {
            color: #1d4ed8 !important;
        }

        .data-table .action-btn.edit:hover {
            color: #1d4ed8 !important;
            background: #eff6ff !important;
            border-color: #d1d5db !important;
        }

        .data-table .action-btn.delete {
            color: #dc2626 !important;
        }

        .data-table .action-btn.delete:hover {
            color: #dc2626 !important;
            background: #fef2f2 !important;
            border-color: #d1d5db !important;
        }

        .data-table .action-btn.edit svg,
        .data-table .action-btn.delete svg {
            width: 16px !important;
            height: 16px !important;
            stroke: currentColor !important;
        }

        .form-grid.row {
            display: grid;
            margin-left: 0;
            margin-right: 0;
            --bs-gutter-x: 0;
            --bs-gutter-y: 0;
        }

        .form-grid.row > .form-field {
            width: auto;
            max-width: none;
            padding-left: 0;
            padding-right: 0;
            margin-top: 0;
            flex: initial;
        }

        .pagination .page-link.pagination-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .icon-btn {
            border: 1px solid var(--line);
            background: #ffffff;
            color: #0f2a5a;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .admin-logo {
            height: 42px;
            width: auto;
            display: block;
        }

        .notif-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ef4444;
            color: #ffffff;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 999px;
            border: 2px solid #ffffff;
        }

        .header-search {
            flex: 1;
            max-width: 420px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8f9fb;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 8px 12px;
            color: #0f2a5a;
        }

        .header-search input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 14px;
            color: #0f2a5a;
            padding: 0;
            min-height: auto;
            box-shadow: none;
        }

        .header-search input:focus {
            box-shadow: none;
            border: none;
        }

        .header-search input::placeholder { color: #94a3b8; }

        .header-search svg { color: #0f2a5a; }

        .header-search button {
            border: none;
            background: transparent;
            color: #0f2a5a;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .header-right { display: flex; align-items: center; gap: 10px; }
        .header-title { font-weight: 700; letter-spacing: 0.3px; color: var(--brand); }

        .header-icon-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 10px;
            text-decoration: none;
            color: #0f2a5a;
            font-size: 13px;
            font-weight: 600;
            position: relative;
            background: #ffffff;
            border: none;
        }

        .header-icon-link svg { color: #0f2a5a; }

        .header-icon-link:hover { background: #f1f5f9; }

        .admin-user-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: none;
            padding: 6px 10px;
            border-radius: 999px;
            cursor: pointer;
            color: #0f2a5a;
            font-size: 13px;
            font-weight: 600;
        }

        .admin-user-btn:hover { background: #f1f5f9; }

        .admin-user-btn.dropdown-toggle::after {
            display: none;
        }

        .admin-user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand), var(--accent));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }

        .admin-user-caret {
            width: 14px;
            height: 14px;
            stroke: currentColor;
        }

        .user-menu { position: relative; }
        .user-dropdown {
            position: absolute;
            right: 0;
            top: 44px;
            min-width: 190px;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
            padding: 6px;
            display: none;
            z-index: 20;
        }

        .user-dropdown.open { display: block; }
        .user-dropdown a {
            display: block;
            padding: 10px 12px;
            text-decoration: none;
            color: #0f2a5a;
            border-radius: 8px;
            font-size: 14px;
        }

        .user-dropdown a svg {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            vertical-align: -3px;
            color: #0f2a5a;
            stroke: currentColor;
        }

        .user-dropdown a:hover { background: #f1f5f9; }

        .menu-divider {
            height: 1px;
            background: var(--line);
            margin: 6px 4px;
        }

        /* CHANGE PASSWORD MODAL */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.35);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1200;
            padding: 20px;
        }

        .modal-overlay.active { display: flex; }

        .modal-card {
            width: min(520px, 100%);
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);
            border: 1px solid var(--line);
            overflow: hidden;
            animation: modalIn 180ms ease-out;
        }

        .modal-card.modal-content {
            display: block;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
            border-bottom: 1px solid #eef2ff;
            background: #f8faff;
        }

        .modal-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f2a5a;
        }

        .modal-close {
            border: none;
            background: transparent;
            color: #0f2a5a;
            font-size: 18px;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover { background: #f1f5f9; }

        .modal-body {
            padding: 18px;
            display: grid;
            gap: 12px;
        }

        .modal-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #0f2a5a;
            margin-bottom: 6px;
        }

        .modal-field .req { color: #ef4444; margin-left: 4px; }

        .modal-error {
            color: #dc2626;
            font-size: 12px;
            font-weight: 600;
            margin-top: 6px;
        }

        .modal-input-wrap { position: relative; }

        .modal-field input {
            width: 100%;
            padding: 12px 44px 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: #fbfdff;
            font-size: 14px;
            outline: none;
        }

        .modal-field input:focus {
            border-color: #c7d6ff;
            box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.12);
        }

        .modal-toggle {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-45%);
            border: none;
            background: transparent;
            color: #0f2a5a;
            cursor: pointer;
            padding: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .modal-toggle svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
        }

        .modal-toggle .eye-off { display: none; }
        .modal-toggle.is-visible .eye-on { display: none; }
        .modal-toggle.is-visible .eye-off { display: block; }

        .modal-actions { padding: 0 18px 18px; }

        .modal-save {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, #0f2a5a, #0b1f45);
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(15, 42, 90, 0.25);
        }

        .modal-save:hover { filter: brightness(0.96); }

        .admin-toast {
            position: fixed;
            top: 18px;
            right: 18px;
            z-index: 1300;
            min-width: 260px;
            max-width: min(360px, calc(100vw - 36px));
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid transparent;
            background: #ffffff;
            font-size: 13px;
            font-weight: 800;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.18);
            animation: toastIn 180ms ease-out;
            transition: opacity 180ms ease, transform 180ms ease;
        }

        .admin-toast.is-hiding {
            opacity: 0;
            transform: translateY(-8px);
        }

        .admin-toast.success {
            color: #166534;
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .admin-toast.error {
            color: #991b1b;
            background: #fef2f2;
            border-color: #fecaca;
        }

        @keyframes toastIn {
            from { transform: translateY(-8px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes modalIn {
            from { transform: translateY(8px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 8px;
            border-radius: 12px;
            background: rgba(255,255,255,0.08);
            margin-bottom: 8px;
        }

        .brand-image {
            height: 50px;
            width: auto;
            display: inline-block;
            border-radius: 6px;
        }

        /* Reset any extra spacing that might push the logo down */
        .brand-text, .brand-title {
            padding: 0;
            margin: 0;
            height: auto;
            min-height: 0;
            line-height: 1;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .brand-badge {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
        }

        .nav { display: grid; gap: 1px; }
        .nav a {
            color: #dbeafe;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 10px;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .nav-sub {
            margin-left: 8px;
            gap: 1px;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-8px);
            transition: max-height 420ms cubic-bezier(.2,.8,.2,1), opacity 300ms ease, transform 360ms cubic-bezier(.2,.8,.2,1);
            pointer-events: none;
            display: block; /* keep layout but hidden via max-height/opacity */
        }

        .nav-item.open .nav-sub {
            max-height: 560px;
            opacity: 1;
            transform: translateY(0);
            transition-delay: 80ms;
            pointer-events: auto;
            display: block;
        }
        .nav-sub a {
            display: block;
            padding: 4px 10px;
            margin-left: 16px;
            font-size: 13px;
            color: #cbd5f5;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .nav-sub a:hover { background: rgba(255,255,255,0.08); color: #ffffff; }
        .nav a:hover { background: rgba(255,255,255,0.12); color: #ffffff; }
        .nav-section {
            margin: 10px 0 4px;
            padding: 0 12px;
            font-size: 11px;
            font-weight: 700;
            color: rgba(203, 213, 245, 0.72);
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }
        .nav a.active,
        .nav-sub a.active { background: rgba(255,255,255,0.16); color: #ffffff; }
        .nav-item { position: relative; }
        .nav-item > a { display: flex; align-items: center; gap: 10px; }
        .nav-caret { margin-left: auto; opacity: 0.8; transition: transform 0.2s ease; }
        .nav-item.open .nav-caret { transform: rotate(180deg); }
        .sidebar.collapsed .nav-sub { display: none; }
        .nav .nav-text { white-space: nowrap; }
        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .brand-text { display: none; }

        .sidebar.collapsed .brand { justify-content: center; }
    </style>
</head>
<body>
<?php $activeToast = $adminToast ?? $changePasswordToast ?? null; ?>
<?php if (!empty($activeToast)): ?>
    <div class="admin-toast <?= htmlspecialchars($activeToast['type']) ?>">
        <?= htmlspecialchars($activeToast['message']) ?>
    </div>
<?php endif; ?>

<div class="container admin-container container-fluid">
    <?php include 'sidebar.php'; ?>

    <div class="main">
        <?php include 'header.php'; ?>
        <div class="content">
            <?php include $content; ?>
        </div>
    </div>
</div>

<style>
    .content {
        --select-arrow-default: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231f2937' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        --select-arrow-green: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23065f46' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        --select-arrow-orange: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2392400e' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        --select-arrow-blue: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231e40af' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        --select-arrow-red: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%237f1d1d' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        --select-arrow-gray: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    }

    .content select.field-input,
    .content select.form-select:not(.status-select) {
        appearance: none !important;
        -webkit-appearance: none !important;
        background-image: var(--select-arrow-default) !important;
        background-position: right 12px center !important;
        background-repeat: no-repeat !important;
        background-size: 16px !important;
        padding-right: 42px !important;
    }

    .content select.status-select {
        appearance: none !important;
        -webkit-appearance: none !important;
        background-image: var(--select-arrow-default) !important;
        background-position: right 10px center !important;
        background-repeat: no-repeat !important;
        background-size: 12px !important;
        padding-right: 36px !important;
    }

    .content select.status-select.active {
        background-image: var(--select-arrow-green), linear-gradient(90deg, #bbf7d0, #34d399) !important;
        background-position: right 10px center, center !important;
        background-repeat: no-repeat, repeat !important;
        background-size: 12px, auto !important;
    }

    .content select.status-select.upcoming {
        background-image: var(--select-arrow-orange), linear-gradient(90deg, #fde68a, #f59e0b) !important;
        background-position: right 10px center, center !important;
        background-repeat: no-repeat, repeat !important;
        background-size: 12px, auto !important;
    }

    .content select.status-select.completed {
        background-image: var(--select-arrow-blue), linear-gradient(90deg, #dbeafe, #bfdbfe) !important;
        background-position: right 10px center, center !important;
        background-repeat: no-repeat, repeat !important;
        background-size: 12px, auto !important;
    }

    .content select.status-select.inactive {
        background-image: var(--select-arrow-red), linear-gradient(90deg, #fed7d7, #f87171) !important;
        background-position: right 10px center, center !important;
        background-repeat: no-repeat, repeat !important;
        background-size: 12px, auto !important;
    }

    .content select.status-select.unknown {
        background-image: var(--select-arrow-gray) !important;
        background-color: #f8f9fa !important;
        background-position: right 10px center !important;
        background-repeat: no-repeat !important;
        background-size: 12px !important;
    }
</style>

<?php include __DIR__ . '/change_password_modal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function(){
        var toggle = document.getElementById('sidebarToggle');
        var sidebar = document.querySelector('.sidebar');
        var userBtn = document.getElementById('userMenuBtn');
        var userMenu = document.getElementById('userMenu');
        if (!toggle || !sidebar) return;

        function closeSidebarSubmenus() {
            document.querySelectorAll('.sidebar .nav-item.open').forEach(function(item) {
                item.classList.remove('open');
            });
        }

        toggle.addEventListener('click', function(){
            var isCollapsed = sidebar.classList.toggle('collapsed');
            if (isCollapsed) {
                closeSidebarSubmenus();
            }
        });

        // Accordion: only one nav-item open at a time
        (function(){
            var nav = document.querySelector('.sidebar .nav');
            if(!nav) return;

            var toggles = nav.querySelectorAll('.nav-item > a');
            toggles.forEach(function(trigger){
                var item = trigger.closest('.nav-item');
                var submenu = item && item.querySelector('.nav-sub');
                if(!item || !submenu) return;

                trigger.addEventListener('click', function(e){
                    e.preventDefault();
                    var wasOpen = item.classList.contains('open');

                    // close others
                    nav.querySelectorAll('.nav-item.open').forEach(function(other){
                        if(other !== item) other.classList.remove('open');
                    });

                    // toggle this one
                    item.classList.toggle('open', !wasOpen);
                });
            });

            // open any parent that contains an active link
            nav.querySelectorAll('.nav-item').forEach(function(it){
                if(it.querySelector('a.active')) it.classList.add('open');
            });
        })();

        if (userBtn && userMenu) {
            userBtn.addEventListener('click', function(e){
                e.stopPropagation();
                var isOpen = userMenu.classList.toggle('open');
                userBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            document.addEventListener('click', function(){
                userMenu.classList.remove('open');
                userBtn.setAttribute('aria-expanded', 'false');
            });

            document.addEventListener('keydown', function(e){
                if (e.key === 'Escape') {
                    userMenu.classList.remove('open');
                    userBtn.setAttribute('aria-expanded', 'false');
                    closeAdminPasswordModal();
                }
            });
        }
    })();

    (function(){
        var toast = document.querySelector('.admin-toast');
        if (!toast) return;

        window.setTimeout(function() {
            toast.classList.add('is-hiding');
            window.setTimeout(function() {
                if (toast && toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 220);
        }, 3000);
    })();

    <?php if (!empty($redirectToAdminLogin)): ?>
    window.setTimeout(function() {
        window.location.href = '/KhoaLuan/public/admin.php?page=login';
    }, 2000);
    <?php endif; ?>
</script>

</body>
</html>
