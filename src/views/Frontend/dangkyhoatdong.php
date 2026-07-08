<?php
    $student = $student ?? [];
?>

<style>
    .activity-page {
        display: grid;
        gap: 16px;
    }

    .activity-page-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
        text-transform: none;
        letter-spacing: 0.6px;
    }

    .activity-panel {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .activity-panel__header {
        padding: 12px 14px;
        border-bottom: 1px solid #e5e7eb;
    }

    .activity-panel__body {
        padding: 12px;
    }

    .activity-toolbar {
        display: grid;
        gap: 12px;
    }

    .activity-filters {
        display: grid;
        grid-template-columns:
            minmax(220px, 1.35fr)
            minmax(180px, 1fr)
            minmax(120px, 0.66fr)
            minmax(170px, 0.9fr)
            minmax(120px, 0.66fr)
            minmax(150px, 0.78fr)
            auto;
        gap: 12px;
        align-items: end;
        background: transparent;
        border: none;
        border-radius: 0;
        padding: 0 8px 0 0;
        box-shadow: none;
    }

    .filter-field {
        display: grid;
        gap: 6px;
        font-size: 12px;
        color: var(--primary);
        font-weight: 600;
    }

    .filter-input {
        display: flex;
        align-items: center;
        gap: 8px;
        min-height: 38px;
        padding: 0 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 13px;
        color: #1f2937;
    }

    .filter-input input {
        border: none;
        background: transparent;
        outline: none;
        width: 100%;
        height: 100%;
        font-size: 13px;
        color: #1f2937;
    }

    .filter-select {
        width: 100%;
        min-height: 38px;
        padding: 0 34px 0 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background-color: #f9fafb;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231047a1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-position: right 10px center;
        background-size: 16px;
        background-repeat: no-repeat;
        font-size: 13px;
        color: #1f2937;
        font-weight: 600;
        outline: none;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    .filter-select:focus {
        border-color: var(--primary-border-strong);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.12);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        justify-self: end;
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
    }

    .filter-btn.primary {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .filter-actions .filter-reset-btn,
    .filter-actions .filter-apply-btn {
        min-height: 38px;
        padding: 8px 20px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        font-size: 13px;
        font-weight: 900 !important;
        line-height: 1.2;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .filter-actions .filter-reset-btn {
        color: #dc2626 !important;
        background: #ffffff !important;
        border-color: #e5e7eb !important;
    }

    .filter-actions .filter-reset-btn:hover {
        color: #dc2626 !important;
        background: #e5e7eb !important;
        border-color: #cbd5e1 !important;
    }

    .filter-actions .filter-apply-btn {
        color: #ffffff !important;
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%) !important;
        border-color: #16a34a !important;
    }

    .filter-actions .filter-apply-btn:hover {
        color: #ffffff !important;
        background: linear-gradient(180deg, #15803d 0%, #166534 100%) !important;
        border-color: #15803d !important;
    }

    .activity-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 16px;
        align-items: center;
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 12px;
    }

    .activity-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 999px;
        border: 1px solid transparent;
        cursor: pointer;
    }

    .meta-label {
        color: #6b7280;
        font-weight: 800;
    }

    /* activity-detail styles moved to activity_detail_modal.php */

    .activity-detail-close:hover { background: var(--primary-soft); }

    .activity-detail-body {
        padding: 16px;
        overflow-y: auto;
        display: grid;
        gap: 16px;
    }

    .activity-detail-top {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 16px;
        align-items: start;
    }

    .activity-detail-image {
        width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e8ecf3;
        background: #f8fafc;
    }

    .activity-detail-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .activity-detail-summary {
        display: grid;
        gap: 12px;
        align-content: start;
    }

    .activity-detail-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .activity-detail-summary-item {
        border: 1px solid #e8ecf3;
        background: #f8fafc;
        border-radius: 12px;
        padding: 10px 12px;
        display: grid;
        gap: 4px;
        min-height: 58px;
    }

    .activity-detail-summary-label {
        font-size: 11px;
        text-transform: none;
        letter-spacing: 0.6px;
        font-weight: 800;
        color: var(--primary);
    }

    .activity-detail-summary-value {
        font-size: 13px;
        color: #0f172a;
        font-weight: 700;
        line-height: 1.35;
    }

    .activity-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .detail-box {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 14px;
        padding: 12px 13px;
        display: grid;
        gap: 6px;
        box-shadow: 0 1px 0 rgba(15, 23, 42, 0.02);
    }

    .detail-label {
        font-size: 11px;
        text-transform: none;
        letter-spacing: 0.6px;
        font-weight: 800;
        color: var(--primary);
    }

    .detail-label::before {
        content: "";
        display: inline-block;
        width: 14px;
        height: 14px;
        margin-right: 8px;
        vertical-align: middle;
        background-image: url("data:image/svg+xml;utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Ccircle cx='12' cy='12' r='6' fill='%231d4ed8'/%3E%3C/svg%3E");
        background-size: 100% 100%;
        background-repeat: no-repeat;
        opacity: 0.95;
    }

    .stat-box {
        background: linear-gradient(90deg, #fff7ed 0%, #fffaf0 100%);
        border-color: #fcd34d;
        box-shadow: 0 1px 0 rgba(245, 158, 11, 0.03);
    }

    .stat-box .detail-label {
        color: #b45309;
    }

    .stat-box .detail-value {
        color: #92400e;
        font-weight: 800;
    }

    .detail-value {
        font-size: 13px;
        color: #1f2937;
        font-weight: 600;
        line-height: 1.55;
        white-space: pre-line;
    }

    .detail-box.full-width {
        grid-column: 1 / -1;
    }

    .detail-contact {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .detail-contact-item {
        padding: 10px 12px;
        background: #f8fafc;
        border: 1px solid #e8ecf3;
        border-radius: 12px;
        display: grid;
        gap: 4px;
    }

    .detail-contact-item .detail-label {
        margin-bottom: 0;
    }

    .activity-detail-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 8px 18px 8px;
    }

    .detail-action {
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #dc2626;
        min-height: 38px;
        padding: 8px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 800;
        line-height: 1.2;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .detail-action.primary {
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%);
        border-color: #16a34a;
        color: #fff;
    }

    .pagination-container {
        padding: 16px 14px;
        border-top: 1px solid #e8ecf3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #6b7280;
    }

    .pagination {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .pagination-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #fff;
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s;
    }

    .pagination-btn:hover {
        border-color: #d1d5db;
        background: #f9fafb;
        color: #4b5563;
    }

    .pagination-btn.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .pagination-btn.disabled {
        opacity: .45;
        cursor: not-allowed;
        pointer-events: none;
        background: #f9fafb;
        color: #9ca3af;
    }

    .pagination-btn.prev,
    .pagination-btn.next,
    .pagination-btn.first,
    .pagination-btn.last {
        min-width: auto;
        padding: 0 8px;
    }

    @media (max-width: 1100px) {
        .activity-filters {
            grid-template-columns: 1fr 1fr;
        }

        .filter-actions {
            justify-content: flex-end;
        }
    }

    @media (max-width: 640px) {
        .activity-filters {
            grid-template-columns: 1fr;
        }

        .activity-sort {
            width: 100%;
            justify-content: flex-start;
        }
    }

    /* Activity detail modal styles (kept here to preserve original page layout) */
    .activity-detail-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1500;
        padding: 20px;
    }

    .activity-detail-overlay.active {
        display: flex;
    }

    .activity-detail-card {
        width: min(780px, 100%);
        max-height: 80vh;
        overflow: hidden;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.2);
        border: 1px solid #e8ecf3;
        display: grid;
        grid-template-rows: auto 1fr auto;
    }

    .activity-detail-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 18px;
        border-bottom: 1px solid var(--primary-soft);
        background: #f8faff;
    }

    .activity-detail-header .modal-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary);
    }

    .activity-detail-close {
        position: static;
        width: 34px;
        height: 34px;
        border: 0 !important;
        border-radius: 8px;
        background: transparent !important;
        color: var(--primary-dark) !important;
        font-size: 31px;
        font-weight: 400;
        line-height: 1;
        cursor: pointer;
        box-shadow: none !important;
        padding: 0 !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .activity-detail-close:hover {
        color: var(--primary-dark) !important;
        background: #eef4ff !important;
        border-color: transparent !important;
    }

    .activity-detail-close:focus,
    .activity-detail-close:active {
        border: 0 !important;
        box-shadow: none !important;
        outline: none;
    }

    .activity-detail-body {
        padding: 16px;
        overflow-y: auto;
        display: grid;
        gap: 16px;
    }

    .activity-detail-top {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 16px;
        align-items: start;
    }

    .activity-detail-image {
        width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e8ecf3;
        background: #f8fafc;
    }

    .activity-detail-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .activity-detail-summary {
        display: grid;
        gap: 12px;
        align-content: start;
    }

    .activity-detail-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .activity-detail-summary-item {
        border: 1px solid #e8ecf3;
        background: #f8fafc;
        border-radius: 12px;
        padding: 10px 12px;
        display: grid;
        gap: 4px;
        min-height: 58px;
    }

    .activity-detail-summary-label {
        font-size: 11px;
        text-transform: none;
        letter-spacing: 0.6px;
        font-weight: 800;
        color: var(--primary);
    }

    .activity-detail-summary-value {
        font-size: 13px;
        color: #0f172a;
        font-weight: 700;
        line-height: 1.35;
    }

    .activity-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .detail-box {
        background: #ffffff;
        border: 1px solid #e8ecf3;
        border-radius: 14px;
        padding: 12px 13px;
        display: grid;
        gap: 6px;
        box-shadow: 0 1px 0 rgba(15, 23, 42, 0.02);
    }

    .detail-label {
        font-size: 11px;
        text-transform: none;
        letter-spacing: 0.6px;
        font-weight: 800;
        color: var(--primary);
    }

    .detail-label::before {
        content: "🔹";
        display: inline-block;
        margin-right: 8px;
        font-size: 12px;
        vertical-align: middle;
        opacity: 0.95;
    }

    .detail-value {
        font-size: 13px;
        color: #1f2937;
        font-weight: 600;
        line-height: 1.55;
        white-space: pre-line;
    }

    .detail-box.full-width {
        grid-column: 1 / -1;
    }

    .detail-contact {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .detail-contact-item {
        padding: 10px 12px;
        background: #f8fafc;
        border: 1px solid #e8ecf3;
        border-radius: 12px;
        display: grid;
        gap: 4px;
    }

    .detail-contact-item .detail-label {
        margin-bottom: 0;
    }

    .activity-detail-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 8px 18px 8px;
    }

    .detail-action {
        min-height: 38px;
        padding: 8px 20px;
        border-radius: 10px;
        border: 1px solid #e5e7eb !important;
        background: #ffffff !important;
        color: #dc2626 !important;
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

    .detail-action:hover {
        color: #dc2626 !important;
        background: #e5e7eb !important;
        border-color: #cbd5e1 !important;
    }

    .detail-action.primary {
        color: #ffffff !important;
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%) !important;
        border-color: #16a34a !important;
    }

    .detail-action.primary:hover {
        color: #ffffff !important;
        background: linear-gradient(180deg, #15803d 0%, #166534 100%) !important;
        border-color: #15803d !important;
    }

    /* icon uses SVG data URI */

    .stat-box.point-box {
        background: linear-gradient(90deg, #f0fdf4 0%, #fafdfb 100%);
        border-color: #86efac;
        box-shadow: 0 1px 0 rgba(22, 163, 74, 0.03);
    }

    .stat-box.point-box .detail-label {
        color: #15803d;
    }

    .stat-box.point-box .detail-value {
        color: #166534;
    }

    .detail-box.clothing-box {
        background: linear-gradient(90deg, #f0fdf4 0%, #fafdfb 100%);
        border-color: #86efac;
        box-shadow: 0 1px 0 rgba(22, 163, 74, 0.03);
    }

    .detail-box.clothing-box .detail-label {
        color: #15803d;
    }

    .detail-box.clothing-box .detail-value {
        color: #166534;
    }

    .stat-box.remaining-box {
        background: linear-gradient(90deg, #fff7ed 0%, #fffaf0 100%);
        border-color: #fcd34d;
        box-shadow: 0 1px 0 rgba(245, 158, 11, 0.03);
    }

    .stat-box.remaining-box .detail-label {
        color: #b45309;
    }

    .stat-box.remaining-box .detail-value {
        color: #92400e;
    }

    .detail-label::before {
        display: none !important;
    }

    .detail-label i {
        margin-right: 8px;
        font-size: 13px;
        width: 14px;
        text-align: center;
    }

    /* Highlighted Detail Boxes */
    .detail-box.benefits-box {
        background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
        border-color: #bae6fd;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.04);
    }
    .detail-box.benefits-box .detail-label {
        color: #0284c7;
    }
    
    .detail-box.audience-box {
        background: linear-gradient(135deg, #faf5ff 0%, #ffffff 100%);
        border-color: #e9d5ff;
        box-shadow: 0 4px 12px rgba(168, 85, 247, 0.04);
    }
    .detail-box.audience-box .detail-label {
        color: #9333ea;
    }

    .activity-detail-summary-item.time-box {
        background: linear-gradient(135deg, #faf5ff 0%, #ffffff 100%);
        border-color: #e9d5ff;
        box-shadow: 0 4px 12px rgba(168, 85, 247, 0.04);
    }

    .activity-detail-summary-item.time-box .activity-detail-summary-label {
        color: #9333ea;
    }

    .detail-box.contact-box {
        background: #f8fafc;
        border-color: #e2e8f0;
    }
    .detail-box.contact-box .detail-label {
        color: #475569;
    }

    /* Inline status badges in modal */
    .activity-badge-inline {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 12px !important;
        font-weight: 800;
        border: 1px solid currentColor;
        margin-top: 4px;
        width: fit-content;
    }
    .badge-study { color: var(--primary); background: #eff6ff; border-color: #bfdbfe; }
    .badge-ethics { color: #16a34a; background: #f0fdf4; border-color: #bbf7d0; }
    .badge-volunteer { color: #f59e0b; background: #fffbeb; border-color: #fde68a; }
    .badge-fitness { color: #7c3aed; background: #faf5ff; border-color: #ddd6fe; }
    .badge-integration { color: #14b8a6; background: #f0fdfa; border-color: #99f6e4; }
    .badge-other { color: #6b7280; background: #f9fafb; border-color: #d1d5db; }

    /* Card visual improvements */
    .activity-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 18px;
    }

    .activity-card {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e8ecf3;
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .activity-card:hover { transform: translateY(-6px); box-shadow: 0 14px 32px rgba(15,23,42,0.08); }

    .activity-cover { height: 140px; background: #f3f4f6; position: relative; }
    .activity-cover img { width:100%; height:100%; object-fit:cover; display:block; }

    .activity-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 2;
        padding: 5px 10px;
        border-radius: 999px;
        background: rgba(255,255,255,0.92);
        border: 1px solid #34d399;
        color: #059669;
        font-size: 11px;
        font-weight: 800;
        backdrop-filter: blur(6px);
    }

    .activity-body { padding: 16px; display:flex; flex:1; flex-direction:column; gap:12px; }
    .activity-title { font-size:16px; font-weight:800; color:#0f172a; line-height:1.35; }
    .activity-meta {
        display:grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap:10px 12px;
        font-size:12px;
        color:#0f172a;
    }
    .activity-meta > span {
        position:relative;
        display:block;
        min-width:0;
        padding-left:24px;
        line-height:1.35;
        overflow-wrap:normal;
        word-break:normal;
    }
    .activity-meta i {
        position:absolute;
        left:0;
        top:2px;
        color: var(--primary);
        font-size: 13px;
        width: 16px;
        text-align: center;
    }
    .activity-meta .meta-label {
        display:block;
        color:#64748b;
        font-size:10.5px;
        font-weight:800;
        margin-bottom:2px;
    }
    .activity-clothing-meta {
        color:#15803d;
        font-weight:800;
    }
    .activity-clothing-meta i {
        color: var(--primary);
    }

    .activity-score {
        display:grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap:8px;
        max-width: 92%;
    }
    .activity-score > div {
        background:#f8fafc;
        border:1px solid #e8ecf3;
        border-radius:12px;
        min-height: auto;
        padding:6px 10px;
        font-size:12.5px;
        font-weight:800;
        color:#0f172a;
    }
    .activity-score > div:first-child {
        background:linear-gradient(135deg, #ecfdf5 0%, #f7fffb 100%);
        border-color:#bbf7d0;
        color:#15803d;
    }
    .activity-score > div:last-child {
        position:relative;
        overflow:hidden;
        background:linear-gradient(135deg, #fff7ed 0%, #fffaf4 100%);
        border-color:#fed7aa;
        color:#b45309;
    }
    .activity-score .meta-label {
        display:inline-flex;
        align-items:center;
        gap:5px;
        color:inherit;
        opacity:.86;
    }
    .activity-score .meta-label i {
        width:13px;
        text-align:center;
        font-size:12px;
    }
    .activity-score strong {
        font-size:15px;
        line-height:1.3;
    }

    .activity-footer {
        margin-top:auto;
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:8px;
        width:100%;
        flex-wrap:nowrap;
    }
    .activity-btn {
        padding: 6px 12px;
        border-radius: 10px;
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%) !important;
        color: #fff !important;
        border: 1px solid #16a34a !important;
        cursor: pointer;
        font-weight: 700 !important;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: none !important;
        flex:0 0 auto;
    }

    .activity-btn:hover,
    .activity-btn:focus {
        background: linear-gradient(180deg, #15803d 0%, #166534 100%) !important;
        border-color: #15803d !important;
        color: #fff !important;
    }

    .activity-tag {
        display:inline-flex;
        align-items:center;
        gap:6px;
        padding:6px 10px;
        border-radius:999px;
        border:1px solid currentColor;
        background:#fff;
        font-size:12px;
        font-weight:800;
        flex:0 1 auto;
        min-width:0;
        white-space:nowrap;
    }

    .activity-tag--study { color:var(--primary); border-color:var(--primary); }
    .activity-tag--ethics { color:#16a34a; border-color:#16a34a; }
    .activity-tag--volunteer { color:#f59e0b; border-color:#f59e0b; }
    .activity-tag--fitness { color:#7c3aed; border-color:#7c3aed; }
    .activity-tag--integration { color:#14b8a6; border-color:#14b8a6; }
    .activity-tag--other { color:#6b7280; border-color:#6b7280; }

    @media (max-width: 640px) {
        .activity-filters {
            grid-template-columns: minmax(0, 1fr);
            padding: 0;
        }

        .filter-actions {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            justify-self: stretch;
            width: 100%;
        }

        .filter-btn {
            display: inline-flex;
            justify-content: center;
            min-width: 0;
            width: 100%;
            padding: 8px 10px;
        }

        .activity-tabs {
            gap: 8px 10px;
            overflow: visible;
        }

        .activity-tab {
            white-space: normal;
        }

        .activity-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .activity-card {
            min-width: 0;
        }

        .activity-meta {
            grid-template-columns: 1fr;
        }

        .activity-footer {
            flex-wrap: nowrap;
        }

        .pagination-container {
            align-items: flex-start;
            flex-direction: column;
            gap: 10px;
        }
    }

</style>

<div class="activity-page">
    <div class="activity-toolbar">
        <div class="activity-panel card">
            <div class="activity-panel__header card-header">
                <div class="activity-page-title">Đăng ký hoạt động</div>
            </div>
            <div class="activity-panel__body card-body">
                <div class="activity-filters">
                    <div class="filter-field">
                        <span>Tìm kiếm hoạt động</span>
                        <div class="filter-input">
                            <input class="form-control" type="text" placeholder="Tìm kiếm hoạt động..." />
                        </div>
                    </div>
                    <div class="filter-field">
                        <span>Đơn vị tổ chức</span>
                        <select class="filter-select form-select" aria-label="Lọc theo đơn vị tổ chức">
                            <option>Tất cả</option>
                            <option>Đoàn trường</option>
                            <option>Hội Sinh viên</option>
                            <option>Phòng CTSV</option>
                            <option>Khoa</option>
                            <option>Câu lạc bộ</option>
                        </select>
                    </div>
                    <div class="filter-field filter-field--compact">
                        <span>Loại hoạt động</span>
                        <select class="filter-select form-select" aria-label="Lọc theo loại hoạt động">
                            <option>Tất cả</option>
                            <option>Học tập</option>
                            <option>Đạo đức</option>
                            <option>Thể lực</option>
                            <option>Tình nguyện</option>
                            <option>Hội nhập</option>
                            <option>Khác</option>
                        </select>
                    </div>
                    <div class="filter-field">
                        <span>Học kỳ</span>
                        <select class="filter-select form-select" aria-label="Lọc theo học kỳ">
                            <option>Học kỳ 2 (2024 - 2025)</option>
                            <option>Học kỳ 1 (2024 - 2025)</option>
                            <option>Học kỳ hè (2024 - 2025)</option>
                            <option>Tất cả</option>
                        </select>
                    </div>
                    <div class="filter-field filter-field--compact">
                        <span>Trạng thái</span>
                        <select class="filter-select form-select" aria-label="Lọc theo trạng thái">
                            <option>Đang mở</option>
                            <option>Sắp diễn ra</option>
                            <option>Đã kết thúc</option>
                            <option>Tạm ngưng</option>
                            <option>Tất cả</option>
                        </select>
                    </div>
                    <div class="filter-field filter-field--register">
                        <span>Tình trạng đăng ký</span>
                        <select class="filter-select form-select" aria-label="Lọc theo tình trạng đăng ký">
                            <option>Tất cả</option>
                            <option>Còn chỗ</option>
                            <option>Đã đủ người</option>
                        </select>
                    </div>
                    <div class="filter-actions">
                        <button class="filter-btn filter-reset-btn btn btn-outline-secondary" type="button">Đặt lại</button>
                        <button class="filter-btn filter-apply-btn primary btn btn-primary" type="button">Áp dụng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="activity-grid">
        <article class="activity-card card" tabindex="0"
            data-title="Chiến dịch Mùa hè xanh 2024"
            data-unit="Đoàn trường Đại học ABC"
            data-time="20/06/2024 - 25/06/2024"
            data-location="TP. Hồ Chí Minh"
            data-point="10 điểm"
            data-remaining="45 / 100"
            data-tag="Tình nguyện"
            data-benefits="Được cộng 10 điểm rèn luyện; được ghi nhận tham gia chiến dịch tình nguyện cấp trường."
            data-clothing="Áo xanh thanh niên, quần dài, giày thể thao."
            data-audience="Sinh viên toàn trường"
            data-content="Tham gia các hoạt động hỗ trợ cộng đồng, dọn dẹp cảnh quan, tổ chức hoạt động thiếu nhi và tuyên truyền bảo vệ môi trường."
            data-contact-name="Nguyễn Văn A"
            data-contact-phone="0901 234 567"
            data-image="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge badge rounded-pill">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Chiến dịch Mùa hè xanh 2024</div>
                <div class="activity-meta">
                    <span><i class="fa-solid fa-university"></i><span class="meta-label">Đơn vị tổ chức:</span> Đoàn trường Đại học ABC</span>
                    <span><i class="fa-solid fa-calendar-days"></i><span class="meta-label">Thời gian:</span> 7h - ngày 25/06/2024</span>
                    <span><i class="fa-solid fa-location-dot"></i><span class="meta-label">Địa điểm:</span> TP. Hồ Chí Minh</span>
                    <span class="activity-clothing-meta"><i class="fa-solid fa-shirt"></i><span class="meta-label">Trang phục:</span> Áo xanh thanh niên, quần dài</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label"><i class="fa-solid fa-star"></i>Điểm cộng:</span><br><strong>10 điểm</strong></div>
                    <div><span class="meta-label"><i class="fa-solid fa-users"></i>Còn lại:</span><br><strong>45 / 100</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag badge rounded-pill activity-tag--volunteer">Tình nguyện</span>
                    <button class="activity-btn btn btn-primary" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card card" tabindex="0"
            data-title="Hội thảo: AI và tương lai nghề nghiệp"
            data-unit="Khoa Công nghệ thông tin"
            data-time="15/05/2024 - 15/05/2024"
            data-location="Hội trường B, Cơ sở 1"
            data-point="8 điểm"
            data-remaining="120 / 150"
            data-tag="Học tập"
            data-benefits="Cập nhật kiến thức về trí tuệ nhân tạo, nhận điểm rèn luyện và giao lưu với doanh nghiệp."
            data-clothing="Lịch sự, áo sơ mi hoặc đồng phục sinh viên."
            data-audience="Sinh viên năm 2 trở lên, ưu tiên ngành CNTT"
            data-content="Chuyên gia chia sẻ xu hướng AI, kỹ năng chuẩn bị hồ sơ nghề nghiệp và định hướng việc làm."
            data-contact-name="Trần Thị B"
            data-contact-phone="0902 111 222"
            data-image="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge badge rounded-pill">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Hội thảo: AI và tương lai nghề nghiệp</div>
                <div class="activity-meta">
                    <span><i class="fa-solid fa-university"></i><span class="meta-label">Đơn vị tổ chức:</span> Khoa Công nghệ thông tin</span>
                    <span><i class="fa-solid fa-calendar-days"></i><span class="meta-label">Thời gian:</span> 15/05/2024 - 15/05/2024</span>
                    <span><i class="fa-solid fa-location-dot"></i><span class="meta-label">Địa điểm:</span> Hội trường B, Cơ sở 1</span>
                    <span class="activity-clothing-meta"><i class="fa-solid fa-shirt"></i><span class="meta-label">Trang phục:</span> Lịch sự, áo sơ mi</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label"><i class="fa-solid fa-star"></i>Điểm cộng:</span><br><strong>8 điểm</strong></div>
                    <div><span class="meta-label"><i class="fa-solid fa-users"></i>Còn lại:</span><br><strong>120 / 150</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag badge rounded-pill activity-tag--study">Học tập</span>
                    <button class="activity-btn btn btn-primary" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card card" tabindex="0"
            data-title="Giải bóng đá sinh viên mở rộng 2024"
            data-unit="Hội Sinh viên"
            data-time="10/05/2024 - 30/05/2024"
            data-location="Sân bóng đá trường ABC"
            data-point="6 điểm"
            data-remaining="8 / 16 đội"
            data-tag="Thể lực"
            data-benefits="Rèn luyện thể lực, tinh thần đồng đội, giao lưu giữa các khoa và được cộng điểm rèn luyện."
            data-clothing="Áo thể thao, quần short, giày đế mềm."
            data-audience="Sinh viên toàn trường có sức khỏe tốt"
            data-content="Thi đấu theo thể thức vòng bảng và loại trực tiếp; các đội đăng ký thi đấu theo lớp/khoa/câu lạc bộ."
            data-contact-name="Lê Văn C"
            data-contact-phone="0913 456 789"
            data-image="https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge badge rounded-pill">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Giải bóng đá sinh viên mở rộng 2024</div>
                <div class="activity-meta">
                    <span><i class="fa-solid fa-university"></i><span class="meta-label">Đơn vị tổ chức:</span> Hội Sinh viên</span>
                    <span><i class="fa-solid fa-calendar-days"></i><span class="meta-label">Thời gian:</span> 10/05/2024 - 30/05/2024</span>
                    <span><i class="fa-solid fa-location-dot"></i><span class="meta-label">Địa điểm:</span> Sân bóng đá trường ABC</span>
                    <span class="activity-clothing-meta"><i class="fa-solid fa-shirt"></i><span class="meta-label">Trang phục:</span> Áo thể thao, giày đế mềm</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label"><i class="fa-solid fa-star"></i>Điểm cộng:</span><br><strong>6 điểm</strong></div>
                    <div><span class="meta-label"><i class="fa-solid fa-users"></i>Còn lại:</span><br><strong>8 / 16 đội</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag badge rounded-pill activity-tag--fitness">Thể lực</span>
                    <button class="activity-btn btn btn-primary" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card card" tabindex="0"
            data-title="Hiến máu nhân đạo đợt 1/2024"
            data-unit="Đoàn trường Đại học ABC"
            data-time="08/05/2024 - 08/05/2024"
            data-location="Giảng đường A, Cơ sở 1"
            data-point="7 điểm"
            data-remaining="60 / 100"
            data-tag="Đạo đức"
            data-benefits="Được kiểm tra sức khỏe cơ bản, nhận giấy chứng nhận tham gia và cộng điểm rèn luyện."
            data-clothing="Trang phục gọn gàng, thoải mái, ưu tiên áo tay ngắn."
            data-audience="Sinh viên đủ điều kiện hiến máu theo quy định y tế"
            data-content="Đăng ký, khám sàng lọc, hiến máu và theo dõi sức khỏe sau hiến máu."
            data-contact-name="Phạm Thị D"
            data-contact-phone="0904 888 999"
            data-image="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge badge rounded-pill">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Hiến máu nhân đạo đợt 1/2024</div>
                <div class="activity-meta">
                    <span><i class="fa-solid fa-university"></i><span class="meta-label">Đơn vị tổ chức:</span> Đoàn trường Đại học ABC</span>
                    <span><i class="fa-solid fa-calendar-days"></i><span class="meta-label">Thời gian:</span> 08/05/2024 - 08/05/2024</span>
                    <span><i class="fa-solid fa-location-dot"></i><span class="meta-label">Địa điểm:</span> Giảng đường A, Cơ sở 1</span>
                    <span class="activity-clothing-meta"><i class="fa-solid fa-shirt"></i><span class="meta-label">Trang phục:</span> Gọn gàng, thoải mái</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label"><i class="fa-solid fa-star"></i>Điểm cộng:</span><br><strong>7 điểm</strong></div>
                    <div><span class="meta-label"><i class="fa-solid fa-users"></i>Còn lại:</span><br><strong>60 / 100</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag badge rounded-pill activity-tag--ethics">Đạo đức</span>
                    <button class="activity-btn btn btn-primary" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card card" tabindex="0"
            data-title="Đêm văn nghệ chào tân sinh viên K15"
            data-unit="Hội Sinh viên"
            data-time="25/05/2024 - 25/05/2024"
            data-location="Hội trường lớn"
            data-point="5 điểm"
            data-remaining="30 / 80"
            data-tag="Hội nhập"
            data-benefits="Giao lưu văn nghệ, phát triển kỹ năng biểu diễn và cộng điểm rèn luyện."
            data-clothing="Trang phục tự chọn phù hợp tiết mục; lịch sự, gọn gàng khi vào khán đài."
            data-audience="Sinh viên toàn trường"
            data-content="Các tiết mục hát, múa, kịch và trình diễn của các khoa, câu lạc bộ chào đón tân sinh viên."
            data-contact-name="Nguyễn Thị E"
            data-contact-phone="0905 123 456"
            data-image="https://images.unsplash.com/photo-1483412033650-1015ddeb83d1?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1483412033650-1015ddeb83d1?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge badge rounded-pill">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Đêm văn nghệ chào tân sinh viên K15</div>
                <div class="activity-meta">
                    <span><i class="fa-solid fa-university"></i><span class="meta-label">Đơn vị tổ chức:</span> Hội Sinh viên</span>
                    <span><i class="fa-solid fa-calendar-days"></i><span class="meta-label">Thời gian:</span> 25/05/2024 - 25/05/2024</span>
                    <span><i class="fa-solid fa-location-dot"></i><span class="meta-label">Địa điểm:</span> Hội trường lớn</span>
                    <span class="activity-clothing-meta"><i class="fa-solid fa-shirt"></i><span class="meta-label">Trang phục:</span> Phù hợp tiết mục</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label"><i class="fa-solid fa-star"></i>Điểm cộng:</span><br><strong>5 điểm</strong></div>
                    <div><span class="meta-label"><i class="fa-solid fa-users"></i>Còn lại:</span><br><strong>30 / 80</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag badge rounded-pill activity-tag--integration">Hội nhập</span>
                    <button class="activity-btn btn btn-primary" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card card" tabindex="0"
            data-title="Workshop: Kỹ năng thuyết trình hiệu quả"
            data-unit="Trung tâm Kỹ năng mềm"
            data-time="18/05/2024 - 18/05/2024"
            data-location="Phòng B.302"
            data-point="6 điểm"
            data-remaining="25 / 40"
            data-tag="Học tập"
            data-benefits="Nâng cao kỹ năng thuyết trình, tự tin trước đám đông và được nhận tài liệu hướng dẫn."
            data-clothing="Trang phục lịch sự, thoải mái để tham gia thực hành."
            data-audience="Sinh viên có nhu cầu phát triển kỹ năng mềm"
            data-content="Học cách xây dựng slide, luyện giọng nói, ngôn ngữ cơ thể và thực hành thuyết trình."
            data-contact-name="Võ Văn F"
            data-contact-phone="0906 222 333"
            data-image="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge badge rounded-pill">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Workshop: Kỹ năng thuyết trình hiệu quả</div>
                <div class="activity-meta">
                    <span><i class="fa-solid fa-university"></i><span class="meta-label">Đơn vị tổ chức:</span> Trung tâm Kỹ năng mềm</span>
                    <span><i class="fa-solid fa-calendar-days"></i><span class="meta-label">Thời gian:</span> 18/05/2024 - 18/05/2024</span>
                    <span><i class="fa-solid fa-location-dot"></i><span class="meta-label">Địa điểm:</span> Phòng B.302</span>
                    <span class="activity-clothing-meta"><i class="fa-solid fa-shirt"></i><span class="meta-label">Trang phục:</span> Lịch sự, thoải mái</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label"><i class="fa-solid fa-star"></i>Điểm cộng:</span><br><strong>6 điểm</strong></div>
                    <div><span class="meta-label"><i class="fa-solid fa-users"></i>Còn lại:</span><br><strong>25 / 40</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag badge rounded-pill activity-tag--study">Học tập</span>
                    <button class="activity-btn btn btn-primary" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card card" tabindex="0"
            data-title="Cuộc thi Nhiếp ảnh: Khoảnh khắc sinh viên"
            data-unit="Câu lạc bộ Nhiếp ảnh"
            data-time="01/05/2024 - 20/05/2024"
            data-location="Online"
            data-point="6 điểm"
            data-remaining="30 / 120"
            data-tag="Khác"
            data-benefits="Sân chơi sáng tạo, cơ hội trưng bày tác phẩm và nhận giải thưởng."
            data-clothing="Tự do, phù hợp khi tham gia chụp ảnh thực tế."
            data-audience="Sinh viên toàn trường yêu thích nhiếp ảnh"
            data-content="Gửi ảnh dự thi theo chủ đề, bình chọn online và triển lãm ảnh đẹp của sinh viên."
            data-contact-name="Hoàng Thị G"
            data-contact-phone="0907 444 555"
            data-image="https://images.unsplash.com/photo-1453928582365-b6ad33cbcf64?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1453928582365-b6ad33cbcf64?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge badge rounded-pill">Đang mở</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Cuộc thi Nhiếp ảnh: Khoảnh khắc sinh viên</div>
                <div class="activity-meta">
                    <span><i class="fa-solid fa-university"></i><span class="meta-label">Đơn vị tổ chức:</span> Câu lạc bộ Nhiếp ảnh</span>
                    <span><i class="fa-solid fa-calendar-days"></i><span class="meta-label">Thời gian:</span> 01/05/2024 - 20/05/2024</span>
                    <span><i class="fa-solid fa-location-dot"></i><span class="meta-label">Địa điểm:</span> Online</span>
                    <span class="activity-clothing-meta"><i class="fa-solid fa-shirt"></i><span class="meta-label">Trang phục:</span> Tự do, phù hợp</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label"><i class="fa-solid fa-star"></i>Điểm cộng:</span><br><strong>6 điểm</strong></div>
                    <div><span class="meta-label"><i class="fa-solid fa-users"></i>Còn lại:</span><br><strong>30 / 120</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag badge rounded-pill activity-tag--other">Khác</span>
                    <button class="activity-btn btn btn-primary" type="button">Đăng ký</button>
                </div>
            </div>
        </article>

        <article class="activity-card card" tabindex="0"
            data-title="Ngày hội \"Vì môi trường xanh\""
            data-unit="CLB Môi trường xanh"
            data-time="28/05/2024 - 28/05/2024"
            data-location="Công viên 23/9, Quận 1"
            data-point="8 điểm"
            data-remaining="90 / 120"
            data-tag="Tình nguyện"
            data-benefits="Góp phần bảo vệ môi trường, tăng tinh thần cộng đồng và được cộng điểm rèn luyện."
            data-clothing="Áo xanh/đồng phục sinh viên, giày thể thao, mang theo bình nước cá nhân."
            data-audience="Sinh viên quan tâm hoạt động môi trường"
            data-content="Thu gom rác, trồng cây, phân loại rác và tuyên truyền lối sống xanh."
            data-contact-name="Đặng Văn H"
            data-contact-phone="0908 777 888"
            data-image="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=60"
        >
            <div class="activity-cover">
                <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=60" alt="Hoạt động" />
                <span class="activity-badge badge rounded-pill">Sắp diễn ra</span>
            </div>
            <div class="activity-body">
                <div class="activity-title">Ngày hội "Vì môi trường xanh"</div>
                <div class="activity-meta">
                    <span><i class="fa-solid fa-university"></i><span class="meta-label">Đơn vị tổ chức:</span> CLB Môi trường xanh</span>
                    <span><i class="fa-solid fa-calendar-days"></i><span class="meta-label">Thời gian:</span> 28/05/2024 - 28/05/2024</span>
                    <span><i class="fa-solid fa-location-dot"></i><span class="meta-label">Địa điểm:</span> Công viên 23/9, Quận 1</span>
                    <span class="activity-clothing-meta"><i class="fa-solid fa-shirt"></i><span class="meta-label">Trang phục:</span> Áo xanh, giày thể thao</span>
                </div>
                <div class="activity-score">
                    <div><span class="meta-label"><i class="fa-solid fa-star"></i>Điểm cộng:</span><br><strong>8 điểm</strong></div>
                    <div><span class="meta-label"><i class="fa-solid fa-users"></i>Còn lại:</span><br><strong>90 / 120</strong></div>
                </div>
                <div class="activity-footer">
                    <span class="activity-tag badge rounded-pill activity-tag--volunteer">Tình nguyện</span>
                    <button class="activity-btn btn btn-primary" type="button">Đăng ký</button>
                </div>
            </div>
        </article>
    </div>

    <div class="pagination-container" id="activityPaginationContainer">
        <div class="pagination-info" id="activityPaginationInfo"></div>
        <div class="pagination mb-0" id="activityPagination"></div>
    </div>
</div>

<script>
    // Paginate activity cards using the same control style as admin list pages.
    (function() {
        const itemsPerPage = 8;
        const cards = Array.from(document.querySelectorAll('.activity-card'));
        const pagination = document.getElementById('activityPagination');
        const paginationInfo = document.getElementById('activityPaginationInfo');
        let currentPage = 1;

        function getVisibleCards() {
            return cards.filter(function(card) {
                return !card.hasAttribute('data-filter-hidden');
            });
        }

        function createButton(label, page, classes, disabled) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'pagination-btn page-link page-item' + (classes ? ' ' + classes : '');
            button.textContent = label;

            if (disabled) {
                button.classList.add('disabled');
                button.disabled = true;
            } else {
                button.addEventListener('click', function() {
                    renderPage(page);
                });
            }

            return button;
        }

        function renderPage(page) {
            const visibleCards = getVisibleCards();
            const totalItems = visibleCards.length;
            const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
            currentPage = Math.min(Math.max(1, page), totalPages);

            cards.forEach(function(card) {
                card.style.display = 'none';
            });

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, totalItems);

            visibleCards.slice(startIndex, endIndex).forEach(function(card) {
                card.style.display = '';
            });

            if (paginationInfo) {
                const from = totalItems === 0 ? 0 : startIndex + 1;
                paginationInfo.textContent = 'Hiển thị ' + from + ' - ' + endIndex + ' của ' + totalItems + ' hoạt động';
            }

            if (!pagination) return;
            pagination.innerHTML = '';
            pagination.appendChild(createButton('<<', 1, 'first', currentPage === 1));
            pagination.appendChild(createButton('<', currentPage - 1, 'prev', currentPage === 1));

            for (let i = 1; i <= totalPages; i++) {
                pagination.appendChild(createButton(String(i), i, i === currentPage ? 'active' : '', false));
            }

            pagination.appendChild(createButton('>', currentPage + 1, 'next', currentPage === totalPages));
            pagination.appendChild(createButton('>>', totalPages, 'last', currentPage === totalPages));
        }

        renderPage(currentPage);
    })();

    // Wire up Đăng ký buttons to the registration page with the activity title
    (function() {
        const base = 'http://localhost/KhoaLuan/public/student.php?action=dangkyhoatdong';
        document.querySelectorAll('.activity-card').forEach(function(card) {
            const btn = card.querySelector('.activity-btn');
            if (!btn) return;
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const title = card.dataset.title || '';
                const url = base + '&title=' + encodeURIComponent(title);
                window.location.href = url;
            });
        });
    })();
</script>

<?php include __DIR__ . '/activity_detail_modal.php'; ?>
