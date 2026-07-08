(function () {
    var avatarInput = document.getElementById('avatarInput');
    var avatarImg = document.getElementById('avatarPreviewImg');
    var avatarText = document.getElementById('avatarPreviewText');
    var cropModalEl = document.getElementById('avatarCropModal');
    var cropImage = document.getElementById('avatarCropImage');
    var cropError = document.getElementById('avatarCropError');
    var avatarClientError = document.getElementById('avatarClientError');
    var cropper = null;
    var selectedAvatarFile = null;
    var selectedObjectUrl = '';
    var previousPreviewSrc = avatarImg ? avatarImg.getAttribute('src') || '' : '';
    var previousPreviewDisplay = avatarImg ? avatarImg.style.display : '';
    var previousTextDisplay = avatarText ? avatarText.style.display : '';
    var allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    var maxAvatarSize = 5 * 1024 * 1024;
    var outputWidth = 300;
    var outputHeight = 400;

    if (!avatarInput) {
        return;
    }

    function setCropError(message) {
        if (cropError) {
            cropError.textContent = message || '';
        }
    }

    function setAvatarClientError(message) {
        if (!avatarClientError) {
            return;
        }

        avatarClientError.textContent = message || '\u00a0';
        avatarClientError.classList.toggle('is-empty', !message);
    }

    function destroyCropper() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }

        if (selectedObjectUrl) {
            URL.revokeObjectURL(selectedObjectUrl);
            selectedObjectUrl = '';
        }
    }

    function restorePreview() {
        if (avatarImg) {
            avatarImg.src = previousPreviewSrc;
            avatarImg.style.display = previousPreviewDisplay;
        }

        if (avatarText) {
            avatarText.style.display = previousTextDisplay;
        }
    }

    function closeCropModal() {
        if (window.bootstrap && cropModalEl) {
            bootstrap.Modal.getOrCreateInstance(cropModalEl).hide();
        }

        destroyCropper();
    }

    function cancelCrop() {
        avatarInput.value = '';
        selectedAvatarFile = null;
        restorePreview();
        closeCropModal();
    }

    function openCropModal(file) {
        if (!window.Cropper || !window.bootstrap || !cropModalEl || !cropImage) {
            setAvatarClientError('Không thể mở công cụ cắt ảnh lúc này.');
            avatarInput.value = '';
            return;
        }

        destroyCropper();
        selectedAvatarFile = file;
        selectedObjectUrl = URL.createObjectURL(file);
        cropImage.src = selectedObjectUrl;
        setCropError('');

        bootstrap.Modal.getOrCreateInstance(cropModalEl).show();
        window.setTimeout(function () {
            var backdrops = document.querySelectorAll('.modal-backdrop');
            var latestBackdrop = backdrops[backdrops.length - 1];
            if (latestBackdrop) {
                latestBackdrop.classList.add('avatar-crop-backdrop');
            }
        }, 0);
    }

    function croppedMimeType(file) {
        if (file.type === 'image/png') {
            return 'image/png';
        }

        if (file.type === 'image/webp') {
            return 'image/webp';
        }

        return 'image/jpeg';
    }

    function croppedExtension(mimeType) {
        if (mimeType === 'image/png') {
            return 'png';
        }

        if (mimeType === 'image/webp') {
            return 'webp';
        }

        return 'jpg';
    }

    if (cropModalEl) {
        cropModalEl.addEventListener('shown.bs.modal', function () {
            if (!cropImage || !selectedAvatarFile) {
                return;
            }

            cropper = new Cropper(cropImage, {
                aspectRatio: 3 / 4,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.85,
                background: false,
                responsive: true,
                restore: false,
                guides: true,
                center: true,
                movable: true,
                zoomable: true,
                scalable: false,
                rotatable: false,
                cropBoxResizable: true,
                cropBoxMovable: true,
                minCropBoxWidth: 160,
                minCropBoxHeight: 200
            });
        });

        cropModalEl.addEventListener('hidden.bs.modal', function () {
            destroyCropper();
        });
    }

    var cropClose = document.getElementById('avatarCropClose');
    var cropCancel = document.getElementById('avatarCropCancel');
    var cropSave = document.getElementById('avatarCropSave');
    var cropReset = document.getElementById('avatarCropReset');
    var zoomIn = document.getElementById('avatarZoomIn');
    var zoomOut = document.getElementById('avatarZoomOut');

    if (cropClose) {
        cropClose.addEventListener('click', cancelCrop);
    }
    if (cropCancel) {
        cropCancel.addEventListener('click', cancelCrop);
    }
    if (cropReset) {
        cropReset.addEventListener('click', function () {
            if (cropper) {
                cropper.reset();
            }
        });
    }
    if (zoomIn) {
        zoomIn.addEventListener('click', function () {
            if (cropper) {
                cropper.zoom(0.1);
            }
        });
    }
    if (zoomOut) {
        zoomOut.addEventListener('click', function () {
            if (cropper) {
                cropper.zoom(-0.1);
            }
        });
    }

    if (cropSave) {
        cropSave.addEventListener('click', function () {
            if (!cropper || !selectedAvatarFile) {
                return;
            }

            var canvas = cropper.getCroppedCanvas({
                width: outputWidth,
                height: outputHeight,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            if (!canvas) {
                setCropError('Không thể cắt ảnh. Vui lòng chọn ảnh khác.');
                return;
            }

            var mimeType = croppedMimeType(selectedAvatarFile);
            canvas.toBlob(function (blob) {
                if (!blob) {
                    setCropError('Không thể lưu ảnh đã cắt. Vui lòng thử lại.');
                    return;
                }

                var croppedFile = new File(
                    [blob],
                    'avatar_cropped.' + croppedExtension(mimeType),
                    { type: blob.type || mimeType }
                );
                var transfer = new DataTransfer();
                transfer.items.add(croppedFile);
                avatarInput.files = transfer.files;

                if (avatarImg) {
                    avatarImg.src = canvas.toDataURL(croppedFile.type);
                    avatarImg.style.display = 'block';
                }
                if (avatarText) {
                    avatarText.style.display = 'none';
                }

                selectedAvatarFile = null;
                closeCropModal();
            }, mimeType, 0.92);
        });
    }

    avatarInput.addEventListener('change', function () {
        var file = avatarInput.files && avatarInput.files[0];
        if (!file) {
            return;
        }

        previousPreviewSrc = avatarImg ? avatarImg.getAttribute('src') || '' : '';
        previousPreviewDisplay = avatarImg ? avatarImg.style.display : '';
        previousTextDisplay = avatarText ? avatarText.style.display : '';

        if (allowedTypes.indexOf(file.type) === -1) {
            setAvatarClientError('Avatar chỉ hỗ trợ JPG, JPEG, PNG hoặc WEBP.');
            avatarInput.value = '';
            return;
        }

        if (file.size > maxAvatarSize) {
            setAvatarClientError('Avatar không được vượt quá 5MB.');
            avatarInput.value = '';
            return;
        }

        setAvatarClientError('');
        openCropModal(file);
    });
})();
