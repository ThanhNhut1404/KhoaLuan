<style>
    #evidenceModal {
        z-index: 1250;
        overflow-y: auto;
    }

    #evidenceModal .evidence-modal-card {
        width: min(760px, 100%);
        max-height: calc(100vh - 40px);
        display: flex;
        flex-direction: column;
        border-radius: 12px;
    }

    #evidenceModal .modal-header {
        min-height: 42px;
        padding: 8px 14px;
    }

    #evidenceModal .modal-title {
        font-size: 16px;
        font-weight: 800;
    }

    #evidenceModal .modal-close {
        width: 28px;
        height: 28px;
        padding: 0;
        font-size: 28px;
    }

    #evidenceModal .evidence-modal-body {
        padding: 10px 14px;
        overflow-y: auto;
        display: grid;
        gap: 10px;
    }

    #evidenceModal .evidence-criteria-box {
        display: grid;
        gap: 6px;
        padding: 8px 12px;
        border: 1px solid #dbe7ff;
        border-radius: 8px;
        background: #f8fbff;
    }

    #evidenceModal .evidence-criteria-title {
        display: grid;
        gap: 4px;
        font-size: 13px;
        color: #4b5563;
        font-weight: 700;
    }

    #evidenceModal .evidence-criteria-title strong {
        color: #1f2937;
        font-size: 14px;
        line-height: 1.4;
    }

    #evidenceModal .evidence-criteria-meta {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    #evidenceModal .evidence-pill {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        padding: 5px 10px;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        color: var(--primary);
        font-size: 12px;
        font-weight: 800;
    }

    #evidenceModal .evidence-field {
        display: grid;
        gap: 6px;
    }

    #evidenceModal .evidence-field label {
        color: var(--primary);
        font-size: 13px;
        font-weight: 800;
    }

    #evidenceModal .evidence-upload-zone {
        min-height: 108px;
        border: 1.5px dashed #b8c8e6;
        border-radius: 10px;
        background: #f8fafc;
        color: #475569;
        display: grid;
        place-items: center;
        padding: 14px;
        text-align: center;
        transition: border-color 0.18s, background 0.18s;
    }

    #evidenceModal .evidence-upload-zone.is-dragging {
        border-color: var(--primary);
        background: #eef4ff;
    }

    #evidenceModal .evidence-upload-inner {
        display: grid;
        gap: 6px;
        justify-items: center;
    }

    #evidenceModal .evidence-upload-icon {
        width: 36px;
        height: 36px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eef4ff;
        color: var(--primary);
        font-size: 15px;
    }

    #evidenceModal .evidence-upload-text {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        color: #1f2937;
    }

    #evidenceModal .evidence-upload-note,
    #evidenceModal .evidence-error {
        margin: 0;
        font-size: 12px;
        font-weight: 600;
    }

    #evidenceModal .evidence-upload-note {
        color: #64748b;
    }

    #evidenceModal .evidence-error {
        color: #dc2626;
    }

    #evidenceModal .evidence-error:empty {
        display: none;
    }

    #evidenceModal .evidence-select-btn {
        min-height: 34px;
        padding: 7px 12px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #ffbc49;
        font-size: 13px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
    }

    #evidenceModal .evidence-select-btn:hover {
        background: #fefce8;
        border-color: #d1d5db;
    }

    #evidenceModal .evidence-input,
    #evidenceModal .evidence-textarea {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #ffffff;
        color: #1f2937;
        font-size: 13px;
    }

    #evidenceModal .evidence-input {
        min-height: 38px;
        padding: 8px 11px;
    }

    #evidenceModal .evidence-textarea {
        min-height: 84px;
        padding: 10px 11px;
        resize: vertical;
    }

    #evidenceModal .evidence-list {
        display: grid;
        gap: 8px;
    }

    #evidenceModal .evidence-list-title {
        color: var(--primary);
        font-size: 13px;
        font-weight: 800;
    }

    #evidenceModal .evidence-empty {
        padding: 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        color: #6b7280;
        background: #f9fafb;
        font-size: 13px;
        text-align: center;
    }

    #evidenceModal .evidence-file-row {
        display: grid;
        grid-template-columns: minmax(160px, 1fr) 90px auto;
        gap: 10px;
        align-items: center;
        padding: 9px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
        font-size: 13px;
    }

    #evidenceModal .evidence-file-name {
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #1f2937;
        font-weight: 700;
    }

    #evidenceModal .evidence-file-size {
        color: #6b7280;
        font-weight: 600;
        text-align: right;
        white-space: nowrap;
    }

    #evidenceModal .evidence-file-actions {
        display: inline-flex;
        gap: 6px;
        justify-content: flex-end;
    }

    #evidenceModal .evidence-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    #evidenceModal .evidence-icon-btn:hover {
        background: #eef4ff;
    }

    #evidenceModal .evidence-icon-btn.delete {
        color: #dc2626;
    }

    #evidenceModal .evidence-icon-btn.delete:hover {
        background: #fef2f2;
    }

    #evidenceModal .evidence-modal-footer {
        padding: 8px 16px 8px;
        border-top: 1px solid var(--primary-soft);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    #evidenceModal .evidence-action {
        min-height: 38px;
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        font-size: 13px;
        font-weight: 900;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    #evidenceModal .evidence-action.cancel {
        color: #dc2626;
        background: #ffffff;
    }

    #evidenceModal .evidence-action.cancel:hover {
        background: #f3f4f6;
    }

    #evidenceModal .evidence-action.save {
        color: #ffffff;
        background: linear-gradient(180deg, #16a34a 0%, #15803d 100%);
        border-color: #16a34a;
    }

    #evidenceModal .evidence-action.save:hover {
        background: linear-gradient(180deg, #15803d 0%, #166534 100%);
    }

    @media (max-width: 576px) {
        #evidenceModal {
            align-items: flex-start;
            padding: 10px;
        }

        #evidenceModal .evidence-modal-card {
            max-height: calc(100vh - 20px);
        }

        #evidenceModal .evidence-file-row {
            grid-template-columns: 1fr;
            gap: 6px;
        }

        #evidenceModal .evidence-file-size {
            text-align: left;
        }

        #evidenceModal .evidence-file-actions {
            justify-content: flex-start;
        }

        #evidenceModal .evidence-modal-footer {
            justify-content: stretch;
        }

        #evidenceModal .evidence-action {
            flex: 1 1 140px;
        }
    }
</style>

<div class="modal-overlay modal" id="evidenceModal" aria-hidden="true">
    <div class="modal-card modal-content evidence-modal-card" role="dialog" aria-modal="true" aria-labelledby="evidenceModalTitle">
        <div class="modal-header">
            <span class="modal-title" id="evidenceModalTitle">Tải minh chứng</span>
            <button class="modal-close btn btn-light" type="button" aria-label="Đóng" id="evidenceCloseBtn">&times;</button>
        </div>

        <div class="evidence-modal-body modal-body">
            <div class="evidence-criteria-box">
                <div class="evidence-criteria-title">
                    <span>Tiêu chí:</span>
                    <strong id="evidenceCriteriaTitle"></strong>
                </div>
                <div class="evidence-criteria-meta">
                    <span class="evidence-pill">Điểm tối đa: <span id="evidenceCriteriaMax">0</span></span>
                    <span class="evidence-pill">Điểm sinh viên tự chấm: <span id="evidenceCriteriaScore">0</span></span>
                </div>
            </div>

            <div class="evidence-field">
                <label for="evidenceFileInput">Upload file minh chứng</label>
                <div class="evidence-upload-zone" id="evidenceDropZone">
                    <div class="evidence-upload-inner">
                        <span class="evidence-upload-icon">
                            <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                        </span>
                        <p class="evidence-upload-text">Kéo thả file vào đây hoặc chọn từ máy tính</p>
                        <p class="evidence-upload-note">Hỗ trợ JPG, PNG, PDF, DOC, DOCX. Tối đa 5 file, mỗi file không quá 5MB</p>
                        <button class="evidence-select-btn btn btn-outline-primary" type="button" id="evidenceSelectBtn">
                            <i class="fa-solid fa-folder-open" aria-hidden="true"></i>
                            Chọn file
                        </button>
                    </div>
                </div>
                <input id="evidenceFileInput" type="file" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>
                <p class="evidence-error" id="evidenceFileError" aria-live="polite"></p>
            </div>

            <div class="evidence-field">
                <label for="evidenceLinkInput">Link minh chứng Google Drive / OneDrive (nếu có)</label>
                <input class="evidence-input form-control" id="evidenceLinkInput" type="url" placeholder="https://...">
            </div>

            <div class="evidence-field">
                <label for="evidenceDescriptionInput">Mô tả (nếu có)</label>
                <textarea class="evidence-textarea form-control" id="evidenceDescriptionInput" rows="3" placeholder="Nhập mô tả chi tiết"></textarea>
            </div>

            <div class="evidence-list">
                <div class="evidence-list-title">Danh sách minh chứng đã tải</div>
                <div id="evidenceFileList"></div>
            </div>
        </div>

        <div class="evidence-modal-footer modal-footer">
            <button class="evidence-action cancel btn btn-outline-secondary" type="button" id="evidenceCancelBtn">Hủy</button>
            <button class="evidence-action save btn btn-primary" type="button" id="evidenceSaveBtn">
                Nộp minh chứng
            </button>
        </div>
    </div>
</div>

<script>
    (function() {
        const modal = document.getElementById('evidenceModal');
        const closeBtn = document.getElementById('evidenceCloseBtn');
        const cancelBtn = document.getElementById('evidenceCancelBtn');
        const saveBtn = document.getElementById('evidenceSaveBtn');
        const dropZone = document.getElementById('evidenceDropZone');
        const selectBtn = document.getElementById('evidenceSelectBtn');
        const fileInput = document.getElementById('evidenceFileInput');
        const fileList = document.getElementById('evidenceFileList');
        const fileError = document.getElementById('evidenceFileError');
        const linkInput = document.getElementById('evidenceLinkInput');
        const descriptionInput = document.getElementById('evidenceDescriptionInput');
        const criteriaTitle = document.getElementById('evidenceCriteriaTitle');
        const criteriaMax = document.getElementById('evidenceCriteriaMax');
        const criteriaScore = document.getElementById('evidenceCriteriaScore');

        const maxFiles = 5;
        const maxFileSize = 5 * 1024 * 1024;
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
        const evidenceStore = {};
        let currentKey = '';
        let currentFiles = [];

        function buildCriteriaKey(button) {
            return [
                button.dataset.sectionTitle || '',
                button.dataset.criteriaTitle || ''
            ].join('::');
        }

        function formatBytes(bytes) {
            if (!bytes) return '0 KB';
            const units = ['B', 'KB', 'MB'];
            let size = bytes;
            let unitIndex = 0;
            while (size >= 1024 && unitIndex < units.length - 1) {
                size = size / 1024;
                unitIndex++;
            }
            return size.toFixed(unitIndex === 0 ? 0 : 1) + ' ' + units[unitIndex];
        }

        function escapeHtml(value) {
            return String(value || '').replace(/[&<>"']/g, function(character) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                }[character];
            });
        }

        function setError(message) {
            if (fileError) fileError.textContent = message || '';
        }

        function getExtension(fileName) {
            const parts = String(fileName || '').split('.');
            return parts.length > 1 ? parts.pop().toLowerCase() : '';
        }

        function persistCurrentState() {
            if (!currentKey) return;
            evidenceStore[currentKey] = {
                files: currentFiles,
                link: linkInput ? linkInput.value : '',
                description: descriptionInput ? descriptionInput.value : ''
            };
        }

        function renderFiles() {
            if (!fileList) return;
            if (!currentFiles.length) {
                fileList.innerHTML = '<div class="evidence-empty">Chưa có minh chứng nào được chọn.</div>';
                return;
            }

            fileList.innerHTML = currentFiles.map(function(file, index) {
                const fileName = escapeHtml(file.name);
                return [
                    '<div class="evidence-file-row">',
                        '<div class="evidence-file-name" title="' + fileName + '">' + fileName + '</div>',
                        '<div class="evidence-file-size">' + formatBytes(file.size) + '</div>',
                        '<div class="evidence-file-actions">',
                            '<button class="evidence-icon-btn" type="button" title="Xem" aria-label="Xem" data-view-index="' + index + '"><i class="fa-solid fa-eye" aria-hidden="true"></i></button>',
                            '<button class="evidence-icon-btn delete" type="button" title="Xóa" aria-label="Xóa" data-delete-index="' + index + '"><i class="fa-solid fa-trash" aria-hidden="true"></i></button>',
                        '</div>',
                    '</div>'
                ].join('');
            }).join('');
        }

        function addFiles(files) {
            setError('');
            const incoming = Array.prototype.slice.call(files || []);
            if (!incoming.length) return;

            const accepted = [];
            for (const file of incoming) {
                const extension = getExtension(file.name);
                if (!allowedExtensions.includes(extension)) {
                    setError('Chỉ hỗ trợ JPG, PNG, PDF, DOC, DOCX.');
                    continue;
                }
                if (file.size > maxFileSize) {
                    setError('Mỗi file không được vượt quá 5MB.');
                    continue;
                }
                if (currentFiles.length + accepted.length >= maxFiles) {
                    setError('Chỉ được chọn tối đa 5 file.');
                    break;
                }
                accepted.push(file);
            }

            currentFiles = currentFiles.concat(accepted);
            persistCurrentState();
            renderFiles();
            if (fileInput) fileInput.value = '';
        }

        function openModal(button) {
            if (!modal || !button) return;

            currentKey = buildCriteriaKey(button);
            const stored = evidenceStore[currentKey] || { files: [], link: '', description: '' };
            currentFiles = stored.files.slice();

            if (criteriaTitle) criteriaTitle.textContent = button.dataset.criteriaTitle || '';
            if (criteriaMax) criteriaMax.textContent = button.dataset.criteriaMax || '0';
            if (criteriaScore) {
                const row = button.closest ? button.closest('.section-row') : null;
                const scoreInput = row ? row.querySelector('.score-select') : null;
                criteriaScore.textContent = scoreInput ? scoreInput.value : (button.dataset.criteriaScore || '0');
            }
            if (linkInput) linkInput.value = stored.link || '';
            if (descriptionInput) descriptionInput.value = stored.description || '';

            setError('');
            renderFiles();
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
        }

        function closeModal() {
            if (!modal) return;
            persistCurrentState();
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }

        document.querySelectorAll('.evidence-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                openModal(button);
            });
        });

        if (selectBtn && fileInput) {
            selectBtn.addEventListener('click', function() {
                fileInput.click();
            });
            fileInput.addEventListener('change', function() {
                addFiles(fileInput.files);
            });
        }

        if (dropZone) {
            ['dragenter', 'dragover'].forEach(function(eventName) {
                dropZone.addEventListener(eventName, function(event) {
                    event.preventDefault();
                    dropZone.classList.add('is-dragging');
                });
            });

            ['dragleave', 'drop'].forEach(function(eventName) {
                dropZone.addEventListener(eventName, function(event) {
                    event.preventDefault();
                    dropZone.classList.remove('is-dragging');
                });
            });

            dropZone.addEventListener('drop', function(event) {
                addFiles(event.dataTransfer ? event.dataTransfer.files : []);
            });
        }

        if (fileList) {
            fileList.addEventListener('click', function(event) {
                const viewButton = event.target.closest('[data-view-index]');
                const deleteButton = event.target.closest('[data-delete-index]');

                if (viewButton) {
                    const file = currentFiles[Number(viewButton.dataset.viewIndex)];
                    if (!file) return;
                    window.open(URL.createObjectURL(file), '_blank', 'noopener');
                }

                if (deleteButton) {
                    currentFiles.splice(Number(deleteButton.dataset.deleteIndex), 1);
                    persistCurrentState();
                    renderFiles();
                }
            });
        }

        [linkInput, descriptionInput].forEach(function(input) {
            if (!input) return;
            input.addEventListener('input', persistCurrentState);
        });

        if (saveBtn) {
            saveBtn.addEventListener('click', function() {
                persistCurrentState();
                closeModal();
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
