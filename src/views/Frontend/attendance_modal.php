<style>
    #attendanceModal {
        z-index: 1260;
    }

    #attendanceModal .attendance-card {
        width: min(620px, 100%);
        border-radius: 12px;
        overflow: hidden;
    }

    #attendanceModal .modal-header {
        min-height: 46px;
        padding: 10px 16px;
    }

    #attendanceModal .modal-title {
        color: var(--primary);
        font-size: 16px;
        font-weight: 800;
    }

    #attendanceModal .modal-close {
        width: 30px;
        height: 30px;
        padding: 0;
        color: var(--primary-dark);
        background: transparent;
        font-size: 30px;
        line-height: 1;
    }

    #attendanceModal .attendance-body {
        padding: 14px 16px;
        display: grid;
        gap: 12px;
    }

    #attendanceModal .attendance-info {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        padding: 12px;
        border: 1px solid #dbe7ff;
        border-radius: 8px;
        background: #f8fbff;
    }

    #attendanceModal .attendance-info-item {
        display: grid;
        gap: 4px;
        min-width: 0;
    }

    #attendanceModal .attendance-info-item.full {
        grid-column: span 3;
    }

    #attendanceModal .attendance-info + .attendance-camera {
        display: none;
    }

    #attendanceModal .attendance-label {
        color: var(--primary);
        font-size: 12px;
        font-weight: 800;
    }

    #attendanceModal .attendance-value {
        color: #1f2937;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.35;
        word-break: break-word;
    }

    #attendanceModal .attendance-camera {
        min-height: 190px;
        border: 1.5px dashed #b8c8e6;
        border-radius: 10px;
        background: #f8fafc;
        display: grid;
        place-items: center;
        text-align: center;
        padding: 18px;
    }

    #attendanceModal .attendance-camera-inner {
        display: grid;
        justify-items: center;
        gap: 8px;
    }

    #attendanceModal .attendance-camera-icon {
        width: 54px;
        height: 54px;
        border-radius: 999px;
        background: #eef4ff;
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    #attendanceModal .attendance-camera-title {
        margin: 0;
        color: #1f2937;
        font-size: 14px;
        font-weight: 800;
    }

    #attendanceModal .attendance-camera-note {
        margin: 0;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }

    #attendanceModal .attendance-success {
        display: none;
        gap: 4px;
        padding: 10px 12px;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        background: #f0fdf4;
        color: #15803d;
        font-size: 13px;
        font-weight: 700;
    }

    #attendanceModal .attendance-success.show {
        display: grid;
    }

    #attendanceModal .attendance-success strong {
        font-size: 14px;
    }

    #attendanceModal .attendance-footer {
        padding: 10px 16px 12px;
        border-top: 1px solid var(--primary-soft);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    #attendanceModal .attendance-action {
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

    #attendanceModal .attendance-action.cancel {
        color: #dc2626 !important;
        background: #ffffff !important;
        border-color: #e5e7eb !important;
    }

    #attendanceModal .attendance-action.cancel:hover {
        color: #dc2626 !important;
        background: #e5e7eb !important;
        border-color: #cbd5e1 !important;
    }

    #attendanceModal .attendance-action.save {
        color: #ffffff !important;
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%) !important;
        border-color: #16a34a !important;
    }

    #attendanceModal .attendance-action.save:hover {
        color: #ffffff !important;
        background: linear-gradient(180deg, #15803d 0%, #166534 100%) !important;
        border-color: #15803d !important;
    }

    #attendanceModal .attendance-action.save:disabled {
        opacity: 0.75;
        cursor: not-allowed;
    }

    .attendance-toast {
        position: fixed;
        top: 18px;
        right: 18px;
        z-index: 3200;
        max-width: min(360px, calc(100vw - 32px));
        padding: 12px 14px;
        border-radius: 8px;
        background: #16a34a;
        color: #ffffff;
        font-size: 13px;
        font-weight: 800;
        box-shadow: 0 14px 30px rgba(22, 163, 74, 0.25);
        opacity: 0;
        transform: translateY(-8px);
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .attendance-toast.show {
        opacity: 1;
        transform: translateY(0);
    }

    @media (max-width: 576px) {
        #attendanceModal .attendance-info {
            grid-template-columns: 1fr;
        }

        #attendanceModal .attendance-info-item.full {
            grid-column: span 1;
        }

        #attendanceModal .attendance-action {
            flex: 1 1 140px;
        }
    }
</style>

<div class="modal-overlay modal" id="attendanceModal" aria-hidden="true">
    <div class="modal-card modal-content attendance-card" role="dialog" aria-modal="true" aria-labelledby="attendanceModalTitle">
        <div class="modal-header">
            <span class="modal-title" id="attendanceModalTitle">Điểm danh hoạt động</span>
            <button class="modal-close btn btn-light" type="button" aria-label="Đóng" id="attendanceCloseBtn">&times;</button>
        </div>

        <div class="attendance-body modal-body">
            <div class="attendance-camera">
                <div class="attendance-camera-inner">
                    <span class="attendance-camera-icon">
                        <i class="fa-solid fa-camera" aria-hidden="true"></i>
                    </span>
                    <p class="attendance-camera-title">Khung camera</p>
                    <p class="attendance-camera-note">Vui lòng đưa khuôn mặt vào khung hình để điểm danh</p>
                </div>
            </div>

            <div class="attendance-info">
                <div class="attendance-info-item full">
                    <span class="attendance-label">Tên hoạt động</span>
                    <span class="attendance-value" id="attendanceActivityName"></span>
                </div>
                <div class="attendance-info-item">
                    <span class="attendance-label">Đơn vị tổ chức</span>
                    <span class="attendance-value" id="attendanceActivityUnit"></span>
                </div>
                <div class="attendance-info-item">
                    <span class="attendance-label">Loại hoạt động</span>
                    <span class="attendance-value" id="attendanceActivityType"></span>
                </div>
                <div class="attendance-info-item">
                    <span class="attendance-label">Cấp hoạt động</span>
                    <span class="attendance-value" id="attendanceActivityLevel"></span>
                </div>
                <div class="attendance-info-item">
                    <span class="attendance-label">Thời gian</span>
                    <span class="attendance-value" id="attendanceActivityTime"></span>
                </div>
                <div class="attendance-info-item">
                    <span class="attendance-label">Địa điểm</span>
                    <span class="attendance-value" id="attendanceActivityLocation"></span>
                </div>
                <div class="attendance-info-item">
                    <span class="attendance-label">Điểm</span>
                    <span class="attendance-value" id="attendanceActivityPoint"></span>
                </div>
            </div>

            <div class="attendance-camera">
                <div class="attendance-camera-inner">
                    <span class="attendance-camera-icon">
                        <i class="fa-solid fa-camera" aria-hidden="true"></i>
                    </span>
                    <p class="attendance-camera-title">Khung camera</p>
                    <p class="attendance-camera-note">Vui lòng đưa khuôn mặt vào khung hình để điểm danh</p>
                </div>
            </div>

            <div class="attendance-success" id="attendanceSuccess" aria-live="polite">
                <strong>Điểm danh thành công</strong>
                <span id="attendanceSuccessTime"></span>
            </div>
        </div>

        <div class="attendance-footer modal-footer">
            <button class="attendance-action cancel btn btn-outline-secondary" type="button" id="attendanceCancelBtn">Hủy</button>
            <button class="attendance-action save btn btn-primary" type="button" id="attendanceConfirmBtn">Xác nhận điểm danh</button>
        </div>
    </div>
</div>

<div class="attendance-toast" id="attendanceToast" role="status" aria-live="polite">
    Điểm danh thành công! Bạn đã được ghi nhận tham gia hoạt động.
</div>

<script>
    (function() {
        const modal = document.getElementById('attendanceModal');
        const closeBtn = document.getElementById('attendanceCloseBtn');
        const cancelBtn = document.getElementById('attendanceCancelBtn');
        const confirmBtn = document.getElementById('attendanceConfirmBtn');
        const successBox = document.getElementById('attendanceSuccess');
        const successTime = document.getElementById('attendanceSuccessTime');
        const toast = document.getElementById('attendanceToast');
        let currentButton = null;

        function setText(id, value) {
            const element = document.getElementById(id);
            if (element) element.textContent = value || '';
        }

        function pad(value) {
            return String(value).padStart(2, '0');
        }

        function getCheckinTime(date) {
            return pad(date.getHours()) + ':' + pad(date.getMinutes()) + ' - ' +
                pad(date.getDate()) + '/' + pad(date.getMonth() + 1) + '/' + date.getFullYear();
        }

        function showToast() {
            if (!toast) return;
            toast.classList.add('show');
            window.setTimeout(function() {
                toast.classList.remove('show');
            }, 1800);
        }

        function closeModal() {
            if (!modal) return;
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }

        function markRowAsChecked(button, checkinText) {
            const row = button.closest('tr');
            if (!row) return;

            row.dataset.attendanceStatus = 'Đã điểm danh';
            row.dataset.checkinTime = checkinText;
            row.dataset.canCheckin = '0';

            const badge = row.querySelector('.attendance-status-badge');
            if (badge) {
                badge.textContent = 'Đã điểm danh';
                badge.className = 'attendance-status-badge done';
            }

            const disabledButton = document.createElement('button');
            disabledButton.className = 'attendance-action-btn disabled done btn';
            disabledButton.type = 'button';
            disabledButton.title = 'Bạn đã điểm danh thành công.';
            disabledButton.setAttribute('aria-label', 'Bạn đã điểm danh thành công.');
            disabledButton.disabled = true;
            disabledButton.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
            button.replaceWith(disabledButton);
        }

        window.openAttendanceModal = function(button) {
            if (!modal || !button) return;
            currentButton = button;

            setText('attendanceActivityName', button.dataset.name);
            setText('attendanceActivityTime', button.dataset.time);
            setText('attendanceActivityLocation', button.dataset.location);
            setText('attendanceActivityType', button.dataset.type);
            setText('attendanceActivityLevel', button.dataset.level);
            setText('attendanceActivityUnit', button.dataset.unit);
            setText('attendanceActivityPoint', button.dataset.point);

            if (successBox) successBox.classList.remove('show');
            if (successTime) successTime.textContent = '';
            if (confirmBtn) {
                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Xác nhận điểm danh';
            }

            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
        };

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                if (!currentButton) return;
                const checkinText = getCheckinTime(new Date());

                if (successTime) successTime.textContent = 'Thời gian điểm danh: ' + checkinText;
                if (successBox) successBox.classList.add('show');
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Đã điểm danh';

                markRowAsChecked(currentButton, checkinText);
                showToast();
            });
        }

        [closeBtn, cancelBtn].forEach(function(button) {
            if (!button) return;
            button.addEventListener('click', closeModal);
        });

        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) closeModal();
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal && modal.classList.contains('active')) {
                closeModal();
            }
        });
    })();
</script>
