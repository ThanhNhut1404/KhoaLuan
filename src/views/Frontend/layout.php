<?php
$studentThemeColor = strtolower(trim((string) ($_SESSION['theme_color'] ?? 'blue')));
if (!in_array($studentThemeColor, ['blue', 'red', 'green', 'purple', 'cyan', 'orange'], true)) {
    $studentThemeColor = 'blue';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Hệ thống quản lý điểm rèn luyện' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #1d4ed8;
            --primary-dark: #1047a1;
            --secondary: #00a8e8;
            --primary-rgb: 29, 78, 216;
            --primary-dark-rgb: 16, 71, 161;
            --secondary-rgb: 0, 168, 232;
            --primary-soft: #eef2ff;
            --primary-soft-strong: #edf2ff;
            --primary-surface: #f5f7ff;
            --primary-border: #e3e9ff;
            --primary-border-strong: #c7d6ff;
            --primary-muted: #2c387e;
            --footer-start: #101f72;
            --footer-mid: #142b9f;
            --footer-end: #07164f;
            --success: #06a77d;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --text-muted: #6b7280;
            --sidebar-width: 210px;
        }

        body[data-theme="blue"] {
            --primary: #1d4ed8;
            --primary-dark: #1047a1;
            --secondary: #00a8e8;
            --primary-rgb: 29, 78, 216;
            --primary-dark-rgb: 16, 71, 161;
            --secondary-rgb: 0, 168, 232;
            --primary-soft: #eef2ff;
            --primary-soft-strong: #edf2ff;
            --primary-surface: #f5f7ff;
            --primary-border: #e3e9ff;
            --primary-border-strong: #c7d6ff;
            --primary-muted: #2c387e;
            --footer-start: #101f72;
            --footer-mid: #142b9f;
            --footer-end: #07164f;
        }

        body[data-theme="red"] {
            --primary: #d90429;
            --primary-dark: #9f1239;
            --secondary: #fb7185;
            --primary-rgb: 217, 4, 41;
            --primary-dark-rgb: 159, 18, 57;
            --secondary-rgb: 251, 113, 133;
            --primary-soft: #fff1f2;
            --primary-soft-strong: #ffe4e6;
            --primary-surface: #fff5f6;
            --primary-border: #fecdd3;
            --primary-border-strong: #fda4af;
            --primary-muted: #881337;
            --footer-start: #7f1d1d;
            --footer-mid: #be123c;
            --footer-end: #4c0519;
        }

        body[data-theme="green"] {
            --primary: #16a34a;
            --primary-dark: #15803d;
            --secondary: #22c55e;
            --primary-rgb: 22, 163, 74;
            --primary-dark-rgb: 21, 128, 61;
            --secondary-rgb: 34, 197, 94;
            --primary-soft: #f0fdf4;
            --primary-soft-strong: #dcfce7;
            --primary-surface: #f3fbf6;
            --primary-border: #bbf7d0;
            --primary-border-strong: #86efac;
            --primary-muted: #14532d;
            --footer-start: #064e3b;
            --footer-mid: #15803d;
            --footer-end: #052e16;
        }

        body[data-theme="purple"] {
            --primary: #7c3aed;
            --primary-dark: #5b21b6;
            --secondary: #a855f7;
            --primary-rgb: 124, 58, 237;
            --primary-dark-rgb: 91, 33, 182;
            --secondary-rgb: 168, 85, 247;
            --primary-soft: #f5f3ff;
            --primary-soft-strong: #ede9fe;
            --primary-surface: #faf7ff;
            --primary-border: #ddd6fe;
            --primary-border-strong: #c4b5fd;
            --primary-muted: #4c1d95;
            --footer-start: #3b0764;
            --footer-mid: #6d28d9;
            --footer-end: #2e1065;
        }

        body[data-theme="cyan"] {
            --primary: #0891b2;
            --primary-dark: #0e7490;
            --secondary: #06b6d4;
            --primary-rgb: 8, 145, 178;
            --primary-dark-rgb: 14, 116, 144;
            --secondary-rgb: 6, 182, 212;
            --primary-soft: #ecfeff;
            --primary-soft-strong: #cffafe;
            --primary-surface: #f0fdff;
            --primary-border: #a5f3fc;
            --primary-border-strong: #67e8f9;
            --primary-muted: #164e63;
            --footer-start: #164e63;
            --footer-mid: #0891b2;
            --footer-end: #083344;
        }

        body[data-theme="orange"] {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --secondary: #fb923c;
            --primary-rgb: 249, 115, 22;
            --primary-dark-rgb: 234, 88, 12;
            --secondary-rgb: 251, 146, 60;
            --primary-soft: #fff7ed;
            --primary-soft-strong: #ffedd5;
            --primary-surface: #fffaf4;
            --primary-border: #fed7aa;
            --primary-border-strong: #fdba74;
            --primary-muted: #9a3412;
            --footer-start: #7c2d12;
            --footer-mid: #ea580c;
            --footer-end: #431407;
        }

        body {
            font-family: 'Manrope', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            max-width: 100%;
            overflow-x: hidden;
        }

        html {
            max-width: 100%;
            overflow-x: hidden;
        }

        .student-app .container,
        .student-app .container-fluid {
            max-width: none;
        }

        .student-app .card {
            border: 1px solid #e8ecf3;
        }

        .student-app .card,
        .student-app .card-header,
        .student-app .card-body {
            color: inherit;
        }

        .student-app .form-control,
        .student-app .form-select {
            font-family: inherit;
        }

        .student-app .btn {
            font-family: inherit;
            font-weight: inherit;
        }

        .student-app a {
            --bs-link-color-rgb: var(--primary-rgb);
            --bs-link-hover-color-rgb: var(--primary-dark-rgb);
        }

        .student-app .btn-primary {
            --bs-btn-bg: var(--primary);
            --bs-btn-border-color: var(--primary);
            --bs-btn-hover-bg: var(--primary-dark);
            --bs-btn-hover-border-color: var(--primary-dark);
            --bs-btn-active-bg: var(--primary-dark);
            --bs-btn-active-border-color: var(--primary-dark);
            --bs-btn-disabled-bg: var(--primary);
            --bs-btn-disabled-border-color: var(--primary);
        }

        .student-app .btn-outline-primary {
            --bs-btn-color: var(--primary);
            --bs-btn-border-color: var(--primary);
            --bs-btn-hover-bg: var(--primary);
            --bs-btn-hover-border-color: var(--primary);
            --bs-btn-active-bg: var(--primary-dark);
            --bs-btn-active-border-color: var(--primary-dark);
            --bs-btn-disabled-color: var(--primary);
            --bs-btn-disabled-border-color: var(--primary);
        }

        .student-app .form-control:focus,
        .student-app .form-select:focus {
            border-color: var(--primary-border-strong);
            box-shadow: 0 0 0 0.25rem rgba(var(--primary-rgb), 0.16);
        }

        .student-app .table {
            margin-bottom: 0;
            color: inherit;
        }

        .student-app .badge {
            font-family: inherit;
        }

        .student-app .modal-card.modal-content,
        .student-app .activity-detail-card.modal-content {
            display: block;
            width: min(520px, 100%);
            max-width: calc(100vw - 32px);
        }

        .student-app .activity-detail-card.modal-content {
            display: grid;
            width: min(980px, calc(100vw - 32px));
        }

        .student-app .modal-header,
        .student-app .modal-body {
            flex-shrink: 0;
        }

        .user-dropdown .dropdown-menu {
            display: block;
        }

        .user-dropdown .dropdown-menu:not(.active) {
            pointer-events: none;
        }

        .user-btn.dropdown-toggle::after {
            display: none;
        }

        .student-header.navbar {
            padding: 0;
            flex-wrap: nowrap;
        }

        .header-left.navbar-brand {
            margin: 0;
            padding: 0 0 0 8px;
        }

        .header-right.navbar-nav {
            flex-direction: row;
            flex-wrap: nowrap;
        }

        .header-icon-link.nav-link {
            padding: 6px 10px;
        }

        .search-input.form-control {
            min-height: auto;
            box-shadow: none;
        }

        .search-input.form-control:focus {
            box-shadow: none;
        }

        .sidebar-menu.nav {
            display: block;
        }

        .sidebar-menu .nav-link {
            color: inherit;
        }

        .student-app .activity-tabs.nav-pills .activity-tab.nav-link {
            color: #64748b;
            background: transparent;
            border: 1px solid transparent;
        }

        .student-app .activity-tabs.nav-pills .activity-tab.nav-link.active {
            background: var(--primary);
            color: #ffffff;
        }

        .student-app .activity-tabs.nav-pills .activity-tab.nav-link:hover {
            background: #f1f5f9;
            color: var(--primary);
        }

        .student-app .activity-tabs.nav-pills .activity-tab.nav-link.active:hover {
            background: var(--primary);
            color: #ffffff;
        }

        .footer-content.row {
            margin-left: 0;
            margin-right: 0;
        }

        .footer-section[class*="col-"] {
            padding-left: 0;
            padding-right: 0;
        }

        .table-responsive {
            border-radius: inherit;
        }

        @media (max-width: 576px) {
            .student-app .modal-card.modal-content,
            .student-app .activity-detail-card.modal-content {
                width: calc(100vw - 40px);
                max-width: calc(100vw - 40px);
                box-sizing: border-box;
            }

            .student-app .modal-overlay {
                padding: 16px;
            }

            .student-app .modal-body {
                padding: 16px;
            }

            .student-app .modal-actions {
                padding: 0 16px 16px;
            }
        }

        /* HEADER */
        .student-header {
            background: #ffffff;
            color: var(--primary);
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 62px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-left: 8px;
        }

        .logo {
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header-logo {
            height: 52px;
            width: auto;
            display: block;
        }

        /* HEADER CENTER - THANH TIM KIEM */
        .header-center {
            flex: 1;
            display: flex;
            justify-content: center;
            padding: 0 20px;
        }

        /* HEADER CENTER - THANH TIM KIEM */
        .header-center {
            flex: 1;
            display: flex;
                opacity: 0.9;
            padding: 0 20px;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: var(--primary-surface);
            border-radius: 12px;
            padding: 0 12px;
            width: 100%;
            min-width: 0;
            max-width: 400px;
            transition: 0.3s;
            border: 1px solid var(--primary-border);
        }

        .search-box:focus-within {
            background: var(--primary-soft-strong);
            border-color: var(--primary-border-strong);
        }

        .search-input {
            flex: 1;
            min-width: 0;
            border: none;
            background: transparent;
            color: var(--text-muted);
            padding: 8px 12px;
            font-size: 14px;
            outline: none;
        }

        .search-input::placeholder {
            color: rgba(107, 114, 128, 0.7);
        }

        .search-btn {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            flex: 0 0 auto;
            font-size: 16px;
            padding: 4px 8px;
            transition: 0.3s;
        }

        .search-btn:hover {
            color: var(--primary-dark);
            opacity: 1;
        }

        /* HEADER RIGHT - ICONS VA USER MENU */

        /* NUT 3 GACH NAM DUOI HEADER */
        .menu-toggle-btn {
            position: fixed;
            top: 75px;
            left: 20px;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            cursor: pointer;
            background: #ffffff;
            border: 1px solid #e8ecf3;
            color: var(--primary);
            transition: 0.3s;
            padding: 0;
            z-index: 1001;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        body.sidebar-open .menu-toggle-btn {
            color: var(--primary);
        }

        .menu-toggle-btn:hover {
            color: var(--primary);
            transform: none;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            padding-right: 20px;
        }

        .header-icon {
            color: var(--primary);
            font-size: 20px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }

        .header-icon:hover {
            color: var(--primary-dark);
            transform: scale(1.1);
        }

        .header-icon-link {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 4px;
            white-space: nowrap;
        }

        .header-icon-link span {
            color: var(--text-muted);
        }

        .header-icon-link:hover {
            color: var(--primary);
            background: var(--primary-soft);
        }

        .header-icon-link:hover span {
            color: var(--primary);
        }

        .header-icon-link svg,
        .header-icon svg,
        .search-btn svg,
        .user-avatar svg,
        .menu-toggle-btn svg,
        .sidebar-menu svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
        }

        .header-icon-link svg,
        .header-icon svg,
        .search-btn svg {
            color: var(--primary);
        }

        /* USER DROPDOWN */
        .user-dropdown {
            position: relative;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .user-btn svg {
            color: var(--primary);
        }

        .user-btn:hover {
            background: var(--primary-soft);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            font-weight: bold;
            font-size: 16px;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            border-radius: 50%;
        }

        .user-name {
            font-weight: 500;
            white-space: nowrap;
        }

        .user-caret {
            width: 14px;
            height: 14px;
            stroke: currentColor;
        }

        /* DROPDOWN MENU */
        .dropdown-menu {
            position: absolute;
            top: 50px;
            right: 0;
            background: white;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.15);
            min-width: 190px;
            opacity: 0;
            visibility: hidden;
            z-index: 1002;
            border: 1px solid #e8ecf3;
        }

        .dropdown-menu.active {
            opacity: 1;
            visibility: visible;
        }

        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            color: var(--text-muted);
            text-decoration: none;
            transition: 0.2s;
            font-size: 14px;
            min-height: 42px;
        }

        .dropdown-menu a svg {
            flex-shrink: 0;
        }

        .dropdown-menu a:first-child {
            border-radius: 6px 6px 0 0;
        }

        .dropdown-menu a:last-child {
            border-radius: 0 0 6px 6px;
        }

        .dropdown-menu a:hover {
            background: var(--primary-soft);
            color: var(--primary);
        }

        .dropdown-menu a svg {
            width: 18px;
            height: 18px;
            text-align: center;
            color: var(--primary);
            stroke: currentColor;
        }

        .dropdown-divider {
            height: 1px;
            background: #e8ecf3;
            margin: 2px 0;
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

        .modal-overlay.active {
            display: flex;
        }

        .modal-card {
            width: min(520px, 100%);
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);
            border: 1px solid #e8ecf3;
            overflow: hidden;
            animation: modalIn 180ms ease-out;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
            border-bottom: 1px solid var(--primary-soft);
            background: #f8faff;
        }

        .modal-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
        }

        .modal-close {
            border: none;
            background: transparent;
            color: var(--primary-dark);
            font-size: 31px;
            line-height: 1;
            cursor: pointer;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: var(--primary-dark);
            background: var(--primary-soft);
        }

        .activity-detail-close {
            border: none;
            background: transparent;
            color: var(--primary-dark);
            font-size: 31px;
            line-height: 1;
            cursor: pointer;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .activity-detail-close:hover {
            color: var(--primary-dark);
            background: var(--primary-soft);
        }

        .modal-body {
            padding: 18px;
            display: grid;
            gap: 12px;
        }

        .modal-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 6px;
        }

        .modal-field .req {
            color: #ef4444;
            margin-left: 4px;
        }

        .modal-field input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--primary-border);
            background: #fbfdff;
            font-size: 14px;
            outline: none;
        }

        .modal-input-wrap {
            position: relative;
        }

        .modal-input-wrap input {
            padding-right: 44px;
        }

        .modal-toggle {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-45%);
            border: none;
            background: transparent;
            color: var(--primary-dark);
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

        .modal-field input:focus {
            border-color: var(--primary-border-strong);
            box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.12);
        }

        .modal-actions {
            padding: 0 18px 18px;
        }

        .modal-save {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 700;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(var(--primary-rgb), 0.25);
        }

        .modal-error {
            color: #dc2626;
            font-size: 12px;
            font-weight: 600;
            margin-top: 6px;
        }

        /* CONFIRM LOGOUT MODAL */
        #confirmStudentLogoutModal { display: none; }
        #confirmStudentLogoutModal.active { display: grid; place-items: center; position: fixed; inset: 0; z-index: 1300; }
        #confirmStudentLogoutModal .modal-card {
            width: min(420px, calc(100% - 32px));
            min-width: 320px;
            max-width: calc(100% - 32px);
            padding: 12px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.2);
        }
        #confirmStudentLogoutModal .modal-header { padding: 2px 10px; }
        #confirmStudentLogoutModal .modal-title { font-size: 14px; font-weight: 800; color: var(--primary); }
        #confirmStudentLogoutModal .modal-close {
            width: 34px;
            height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 31px;
            line-height: 1;
        }
        #confirmStudentLogoutModal .modal-body { padding: 12px 10px 8px; }
        #confirmStudentLogoutModal .confirm-text { margin: 0; text-align: center; }
        #confirmStudentLogoutModal .confirm-question { margin: 0; font-size: 15px; line-height: 1.45; font-weight: 700; color: #1f2937; overflow-wrap: anywhere; }
        #confirmStudentLogoutModal .modal-actions { display: flex; gap: 10px; justify-content: center; padding: 24px 10px 10px; }
        #confirmStudentLogoutModal .action-btn {
            width: auto !important;
            height: auto !important;
            white-space: nowrap;
            min-height: 40px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.2;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s;
        }
        #confirmStudentLogoutModal .action-btn.secondary {
            color: #dc2626;
            background: #ffffff;
            border-color: #e5e7eb;
        }
        #confirmStudentLogoutModal .action-btn.secondary:hover {
            color: #dc2626;
            background: #e5e7eb;
            border-color: #cbd5e1;
        }
        #confirmStudentLogoutModal .action-btn.primary {
            color: #ffffff;
            background: linear-gradient(180deg, #16a34a 0%, #15803d 100%);
            border-color: #16a34a;
        }
        #confirmStudentLogoutModal .action-btn.primary:hover {
            color: #ffffff;
            background: linear-gradient(180deg, #15803d 0%, #166534 100%);
            border-color: #15803d;
        }
        @media (max-width: 420px) {
            #confirmStudentLogoutModal .modal-card { min-width: 0; }
        }

        .student-toast {
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
            pointer-events: none;
            opacity: 1;
            transform: translateY(0);
            transition: opacity 220ms ease, transform 220ms ease;
        }

        .student-toast.is-hiding {
            opacity: 0;
            transform: translateY(-8px);
        }

        .student-toast.success {
            color: #166534;
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .student-toast.error {
            color: #991b1b;
            background: #fef2f2;
            border-color: #fecaca;
        }

        .student-theme-toast {
            position: fixed;
            top: 78px;
            right: 18px;
            z-index: 1600;
            min-width: 220px;
            max-width: min(360px, calc(100vw - 32px));
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #dbeafe;
            background: #ffffff;
            color: #1f2937;
            font-size: 13px;
            font-weight: 800;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.18);
            opacity: 1;
            transform: translateY(0);
            transition: opacity 220ms ease, transform 220ms ease;
        }

        .student-theme-toast.success {
            color: #166534;
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .student-theme-toast.error {
            color: #991b1b;
            background: #fef2f2;
            border-color: #fecaca;
        }

        .student-theme-toast.is-hiding {
            opacity: 0;
            transform: translateY(-8px);
        }

        @keyframes toastIn {
            from { transform: translateY(-8px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes modalIn {
            from { transform: translateY(8px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 70px;
            left: calc(-1 * var(--sidebar-width));
            width: var(--sidebar-width);
            height: auto;
            max-height: calc(100vh - 90px);
            background: #ffffff;
            color: var(--text-muted);
            padding: 20px 0;
            transition: left 0.3s ease;
            z-index: 999;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar-menu {
            list-style: none;
            padding-top: 28px; /* tạo khoảng cách với nút menu */
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-muted);
            text-decoration: none;
            transition: 0.3s;
            border-left: 3px solid transparent;
            font-size: 14px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: var(--primary);
            background: var(--primary-soft);
            border-left-color: transparent;
        }

        .sidebar-menu svg {
            width: 20px;
            height: 20px;
            text-align: center;
            stroke: currentColor;
        }

        .sidebar-menu svg {
            color: var(--primary);
        }

        .sidebar-menu .has-submenu {
            position: relative;
        }

        .sidebar-menu .submenu-caret {
            margin-left: auto;
            width: 12px;
            height: 12px;
            stroke: currentColor;
            opacity: 0.7;
            transition: transform 0.2s ease;
        }

        .sidebar-menu .has-submenu.open .submenu-caret {
            transform: rotate(180deg);
        }

        .sidebar-menu .submenu {
            list-style: none;
            margin: 2px 0 6px 0;
            padding: 0 0 0 8px;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-8px);
            transition: max-height 420ms cubic-bezier(.2,.8,.2,1), opacity 300ms ease, transform 360ms cubic-bezier(.2,.8,.2,1);
            pointer-events: none;
        }

        .sidebar-menu .has-submenu.open .submenu {
            max-height: 560px;
            opacity: 1;
            transform: translateY(0);
            transition-delay: 80ms;
            pointer-events: auto;
        }

        .sidebar-menu .submenu a {
            padding: 8px 16px 8px 28px;
            font-size: 13px;
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            margin-top: 56px;
            margin-bottom: 60px;
            padding: 30px 20px;
            min-width: 0;
            width: 100%;
        }

        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            min-width: 0;
            width: 100%;
        }

        /* Desktop: sidebar luôn hiện giống cổng thông tin */
        @media (min-width: 992px) {
            .main-content {
                margin-left: 0;
                padding: 30px 30px;
            }

            .content-wrapper {
                max-width: 1400px;
            }
        }

        @media (min-width: 769px) and (max-width: 1120px) {
            .header-left {
                flex: 0 0 auto;
            }

            .header-logo {
                max-width: 150px;
                height: auto;
            }

            .header-right {
                flex: 1 1 auto;
                justify-content: flex-end;
                gap: 8px;
                padding-right: 12px;
            }

            .header-right .search-box {
                flex: 1 1 220px;
                max-width: 300px;
            }

            .user-name {
                display: none;
            }
        }

        @media (min-width: 769px) and (max-width: 960px) {
            .header-icon-link span {
                display: none;
            }

            .header-icon-link {
                padding: 6px 8px;
            }

            .header-right .search-box {
                max-width: 240px;
            }
        }

        /* FOOTER */
        .student-footer {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 8% 18%, rgba(var(--secondary-rgb), 0.28), transparent 28%),
                radial-gradient(circle at 92% 12%, rgba(var(--primary-rgb), 0.18), transparent 24%),
                linear-gradient(135deg, var(--footer-start) 0%, var(--footer-mid) 48%, var(--footer-end) 100%);
            color: rgba(255,255,255,0.92);
            font-size: 13px;
            margin-top: auto;
            border-top: 1px solid rgba(125, 211, 252, 0.22);
            padding: 16px 0 12px;
        }

        .student-footer::before,
        .student-footer::after {
            content: "";
            position: absolute;
            pointer-events: none;
            opacity: 0.24;
        }

        .student-footer::before {
            width: 180px;
            height: 180px;
            right: -60px;
            top: -80px;
            background-image: radial-gradient(rgba(255,255,255,0.42) 1.2px, transparent 1.2px);
            background-size: 16px 16px;
        }

        .student-footer::after {
            width: 250px;
            height: 110px;
            right: 26px;
            bottom: 22px;
            border: 1px solid rgba(255,255,255,0.14);
            border-radius: 12px;
            transform: skewX(-10deg);
        }

        .footer-inner {
            position: relative;
            z-index: 1;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        .footer-content {
            width: 100%;
            align-items: flex-start;
        }

        .footer-section {
            min-width: 0;
            text-align: center;
        }

        .student-footer .footer-section[class*="col-"] {
            padding-left: calc(var(--bs-gutter-x) * .5);
            padding-right: calc(var(--bs-gutter-x) * .5);
        }

        @media (min-width: 768px) {
            .footer-section {
                text-align: left;
            }
        }

        .footer-heading {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0;
            margin-bottom: 8px;
        }

        .footer-heading i {
            opacity: 0.9;
            color: #fff;
        }

        .footer-logo-heading {
            margin-bottom: 6px;
            justify-content: center;
        }

        .footer-logo {
            display: block;
            width: min(190px, 78vw);
            max-height: 48px;
            object-fit: contain;
        }

        .footer-text {
            margin: 0;
            font-size: 12px;
            line-height: 1.45;
            opacity: 0.92;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .footer-brand {
            font-size: 18px;
            font-weight: 800;
            color: #fff;
        }

        .footer-social {
            margin-top: 10px;
            display: flex;
            gap: 9px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .footer-social a {
            display: inline-flex;
            width: 34px;
            height: 34px;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.14);
            border-radius: 50%;
            color: #fff;
            text-decoration: none;
            transition: background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .footer-social a:hover {
            background: rgba(255,255,255,0.17);
            border-color: rgba(255,255,255,0.28);
            box-shadow: 0 5px 12px rgba(15, 23, 42, 0.14);
            transform: translateY(-1px);
        }

        .footer-contact, .footer-links {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .footer-contact li {
            display: flex;
            align-items: center;
            gap: 9px;
            margin: 7px 0;
            color: rgba(255,255,255,0.9);
            line-height: 1.35;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .footer-contact li:hover {
            color: #fff;
            transform: translateY(-1px);
        }

        .footer-item-icon {
            width: 30px;
            height: 30px;
            flex: 0 0 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #fff;
            background: rgba(var(--primary-rgb), 0.42);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1);
        }

        .footer-item-icon i {
            font-size: 12px;
        }

        .footer-links li {
            margin: 7px 0;
        }

        .footer-links a, .footer-contact a { color: rgba(255,255,255,0.95); }

        .footer-links a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.9);
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .footer-links a i {
            color: #fff;
            font-size: 12px;
        }

        .footer-section a {
            text-decoration: none;
            opacity: 0.9;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .footer-section a:hover {
            opacity: 1;
            text-decoration: underline;
        }

        .footer-links a:hover {
            text-decoration: none;
            transform: translateX(3px);
        }

        .footer-bottom {
            width: 100%;
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid rgba(255,255,255,0.18);
            font-size: 12px;
            opacity: 0.9;
            text-align: center;
        }

        .footer-bottom p {
            margin: 0;
            line-height: 1.45;
        }

        .footer-bottom a {
            color: rgba(255,255,255,0.95);
            text-decoration: none;
        }

        .footer-bottom a:hover {
            color: var(--primary-border-strong);
        }

        .footer-bottom .footer-bottom-sep {
            opacity: 0.55;
            margin: 0 4px;
        }

        .footer-version {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 3px 10px;
            border-radius: 999px;
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.96);
            line-height: 1.2;
        }

        @media (min-width: 768px) {
            .footer-bottom p { display: flex; gap: 10px; align-items: center; justify-content: center; flex-wrap: wrap; }
            .footer-logo-heading,
            .footer-social { justify-content: flex-start; }
        }

        @media (max-width: 767.98px) {
            .student-footer {
                padding: 14px 0 10px;
            }

            .footer-inner {
                padding: 0 16px;
            }

            .footer-contact li {
                justify-content: center;
                margin: 6px 0;
            }

            .footer-links a {
                justify-content: center;
            }

            .footer-bottom {
                margin-top: 10px;
                padding-top: 9px;
            }

            .footer-bottom p {
                display: flex;
                gap: 8px;
                align-items: center;
                justify-content: center;
                flex-wrap: wrap;
            }
        }

        /* OVERLAY untuk mobile */
        .sidebar-overlay {
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            z-index: 998;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .student-header.navbar {
                min-width: 0;
                height: 62px;
                min-height: 62px;
                align-items: center;
                align-content: center;
                flex-wrap: nowrap;
                padding: 0;
                overflow: hidden;
            }

            .header-left.navbar-brand {
                flex: 0 0 auto;
                min-width: 0;
                padding-left: 8px;
            }

            .header-logo {
                max-width: 132px;
                height: auto;
            }

            .header-center {
                display: none;
            }

            .header-right {
                flex: 1 1 auto;
                flex-wrap: nowrap;
                justify-content: flex-end;
                gap: 6px;
                min-width: 0;
                padding-right: 10px;
                overflow: hidden;
            }

            .header-right .search-box {
                order: 0;
                flex: 1 1 180px;
                min-width: 0;
                max-width: 260px;
                padding: 0 10px;
            }

            .header-right .header-icon-link,
            .header-right .user-dropdown {
                order: 0;
                flex: 0 0 auto;
            }

            .search-input {
                min-width: 0;
            }

            .header-icon-link span {
                display: none;
            }

            .user-name {
                display: none;
            }

            .user-avatar {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }

            .header-icon-link span {
                display: none;
            }

            .header-icon-link {
                padding: 6px 8px;
                flex: 0 0 auto;
            }

            .menu-toggle-btn {
                top: 75px;
                left: 20px;
            }

            .sidebar {
                top: 70px;
                max-height: calc(100vh - 90px);
            }

            .sidebar-overlay {
                top: 70px;
            }

            .main-content {
                margin-top: 56px;
                padding: 30px 12px 24px;
            }

            .dropdown-menu {
                right: -10px;
            }
        }

        @media (max-width: 480px) {
            .header-left.navbar-brand {
                padding-left: 8px;
            }

            .header-logo {
                max-width: 104px;
            }

            .header-right .search-box {
                flex-basis: 130px;
                max-width: 160px;
            }

            .logo span {
                display: none;
            }
        }

        @media (max-width: 380px) {
            .header-logo {
                max-width: 92px;
            }

            .header-right {
                gap: 4px;
                padding-right: 6px;
            }

            .header-icon-link {
                padding: 6px;
            }

            .header-right .search-box {
                flex: 1 1 110px;
                max-width: 128px;
                padding: 0 8px;
            }

            .user-btn {
                padding: 4px 6px;
            }

            .search-input {
                padding: 8px;
                font-size: 13px;
            }
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f0f2f5;
        }

        ::-webkit-scrollbar-thumb {
            background: #d0d5e8;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-muted);
        }
    </style>
</head>
<body class="student-app" data-theme="<?= htmlspecialchars($studentThemeColor, ENT_QUOTES, 'UTF-8') ?>">
<?php if (!empty($passwordToast)): ?>
    <div class="student-toast alert <?= htmlspecialchars($passwordToast['type']) ?> <?= ($passwordToast['type'] ?? '') === 'success' ? 'alert-success' : 'alert-danger' ?>"<?= !empty($passwordLogoutAfterToast) ? ' data-logout-after="true"' : '' ?>>
        <?= htmlspecialchars($passwordToast['message']) ?>
    </div>
<?php endif; ?>
<?php include __DIR__ . '/header.php'; ?>

<button class="menu-toggle-btn btn btn-light" onclick="toggleMenu()" title="Mở menu">
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 7h16M4 12h16M4 17h16" stroke-width="2" stroke-linecap="round"/>
    </svg>
</button>

<?php include __DIR__ . '/menu.php'; ?>

<?php include __DIR__ . '/change_password_modal.php'; ?>

<div class="main-content">
    <div class="content-wrapper">
        <?php if (!empty($content) && is_file($content)): ?>
            <?php require $content; ?>
        <?php else: ?>
            <div class="alert alert-danger">
                Không tìm thấy nội dung trang sinh viên.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>

<div class="modal-overlay modal" id="confirmStudentLogoutModal" aria-hidden="true">
    <div class="modal-card modal-content" role="dialog" aria-modal="true" aria-labelledby="confirmStudentLogoutTitle">
        <div class="modal-header">
            <span class="modal-title" id="confirmStudentLogoutTitle">Xác nhận đăng xuất</span>
            <button class="modal-close btn btn-light" type="button" aria-label="Đóng" onclick="closeStudentLogoutConfirm()">×</button>
        </div>
        <div class="modal-body">
            <div class="confirm-text">
                <p class="confirm-question">Bạn có chắc chắn muốn<br>đăng xuất không?</p>
            </div>
        </div>
        <div class="modal-actions">
            <button class="action-btn secondary cancel-btn btn btn-outline-secondary" type="button" onclick="closeStudentLogoutConfirm()">Hủy</button>
            <button class="action-btn primary btn btn-primary" type="button" onclick="confirmStudentLogout()">Đăng xuất</button>
        </div>
    </div>
</div>

<script>
    function setSidebarState(isOpen) {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (isOpen) {
            sidebar.classList.add('active');
            document.body.classList.add('sidebar-open');
            overlay.classList.add('active');
        } else {
            sidebar.classList.remove('active');
            document.body.classList.remove('sidebar-open');
            overlay.classList.remove('active');
            closeSidebarSubmenus();
        }
    }

    function toggleMenu() {
        const sidebar = document.getElementById('sidebar');
        setSidebarState(!sidebar.classList.contains('active'));
    }

    function closeSidebar() {
        closeSidebarSubmenus();
        setSidebarState(false);
    }

    function closeSidebarSubmenus() {
        document.querySelectorAll('.sidebar-menu .has-submenu.open').forEach(function(item) {
            item.classList.remove('open');
        });
    }

    function syncSidebarForViewport() {
        const sidebar = document.getElementById('sidebar');
        setSidebarState(sidebar.classList.contains('active'));
    }

    window.addEventListener('resize', syncSidebarForViewport);
    setSidebarState(false);

    // Đóng sidebar khi nhấp vào overlay
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.querySelector('.menu-toggle-btn');

        if (sidebar.classList.contains('active') && 
            !sidebar.contains(event.target) && 
            !toggleBtn.contains(event.target)) {
            closeSidebar();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePasswordModal();
        }
    });

    document.querySelectorAll('.student-toast').forEach(function(toast) {
        const shouldLogout = toast.dataset.logoutAfter === 'true';
        window.setTimeout(function() {
            toast.classList.add('is-hiding');
            window.setTimeout(function() {
                toast.remove();
                if (shouldLogout) {
                    window.location.replace('/KhoaLuan/public/student.php?action=logout_after_password_change');
                }
            }, 220);
        }, 1580);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/KhoaLuan/public/js/menu.js"></script>
<script src="/KhoaLuan/public/js/student.js"></script>

<script src="/KhoaLuan/public/js/password-strength.js"></script>
</body>
</html>
