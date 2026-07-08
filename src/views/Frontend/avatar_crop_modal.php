<style>
    @import url('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css');

    #avatarCropModal {
        z-index: 1400;
    }

    .modal-backdrop.avatar-crop-backdrop {
        z-index: 1390;
    }

    #avatarCropModal .modal-dialog {
        max-width: min(640px, calc(100vw - 24px));
    }

    #avatarCropModal .modal-content {
        border-radius: 12px;
        border: 1px solid #e8ecf3;
        overflow: hidden;
    }

    #avatarCropModal .modal-header {
        min-height: 48px;
        padding: 10px 16px;
        background: #f8faff;
        border-bottom: 1px solid var(--primary-soft);
    }

    #avatarCropModal .modal-title {
        color: var(--primary);
        font-size: 15px;
        font-weight: 800;
    }

    #avatarCropModal .btn-close {
        width: 24px;
        height: 24px;
        padding: 4px;
        border-radius: 6px;
        opacity: 1;
        background-size: 15px;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%231d4ed8'%3e%3cpath stroke='%231d4ed8' stroke-width='1.3' d='M2.146 2.146a.5.5 0 0 1 .708 0L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    #avatarCropModal .btn-close:hover {
        background-color: #eef4ff;
    }

    #avatarCropModal .btn-close:focus {
        box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.14);
    }

    #avatarCropModal .modal-body {
        display: grid;
        gap: 8px;
        padding: 12px 14px 8px;
    }

    #avatarCropModal .avatar-crop-frame {
        width: 100%;
        height: min(62vh, 520px);
        min-height: 340px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 10px;
        overflow: hidden;
    }

    #avatarCropModal #avatarCropImage {
        display: block;
        max-width: 100%;
        max-height: 100%;
    }

    #avatarCropModal .avatar-crop-tools {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    #avatarCropModal .crop-tool-btn {
        min-width: 38px;
        min-height: 38px;
        border: 1px solid var(--primary-border);
        border-radius: 10px;
        background: #ffffff;
        color: var(--primary-dark);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
    }

    #avatarCropModal .crop-tool-btn:hover {
        background: var(--primary-soft);
        color: var(--primary);
    }

    #avatarCropModal .modal-footer {
        gap: 10px;
        border-top: 1px solid var(--primary-soft);
        padding: 8px 16px 10px;
    }

    #avatarCropModal .action-btn {
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
        gap: 6px;
        transition: all 0.2s;
        white-space: nowrap;
    }

    #avatarCropModal .action-btn.cancel-btn {
        color: #dc2626 !important;
        background: #ffffff !important;
        border-color: #e5e7eb !important;
    }

    #avatarCropModal .action-btn.cancel-btn:hover {
        color: #dc2626 !important;
        background: #e5e7eb !important;
        border-color: #cbd5e1 !important;
    }

    #avatarCropModal .action-btn.save-change-btn {
        color: #ffffff !important;
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%) !important;
        border-color: #16a34a !important;
    }

    #avatarCropModal .action-btn.save-change-btn:hover {
        color: #ffffff !important;
        background: linear-gradient(180deg, #15803d 0%, #166534 100%) !important;
        border-color: #15803d !important;
    }

    #avatarCropModal .crop-error {
        color: #dc2626;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.3;
        text-align: center;
    }

    #avatarCropModal .crop-error:empty {
        display: none;
    }

    @media (max-width: 576px) {
        #avatarCropModal .modal-dialog {
            margin: 8px auto;
            max-width: calc(100vw - 16px);
        }

        #avatarCropModal .avatar-crop-frame {
            height: min(58vh, 430px);
            min-height: 280px;
        }

        #avatarCropModal .modal-footer {
            justify-content: center;
            padding: 8px 12px 10px;
        }
    }
</style>

<div class="modal fade" id="avatarCropModal" tabindex="-1" aria-labelledby="avatarCropTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarCropTitle">Căn chỉnh ảnh</h5>
                <button type="button" class="btn-close" aria-label="Đóng" id="avatarCropClose"></button>
            </div>
            <div class="modal-body">
                <div class="avatar-crop-frame">
                    <img id="avatarCropImage" src="" alt="Ảnh đại diện cần cắt">
                </div>
                <div class="avatar-crop-tools" aria-label="Công cụ căn chỉnh ảnh đại diện">
                    <button type="button" class="crop-tool-btn btn btn-light" id="avatarZoomIn" title="Zoom +" aria-label="Zoom +">
                        <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="crop-tool-btn btn btn-light" id="avatarZoomOut" title="Zoom -" aria-label="Zoom -">
                        <i class="fa-solid fa-minus" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="crop-tool-btn btn btn-light" id="avatarCropReset" title="Reset" aria-label="Reset">
                        <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="crop-error" id="avatarCropError" aria-live="polite"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="action-btn cancel-btn btn btn-outline-secondary" id="avatarCropCancel">Hủy</button>
                <button type="button" class="action-btn save-change-btn btn btn-primary" id="avatarCropSave">Lưu ảnh</button>
            </div>
        </div>
    </div>
</div>
