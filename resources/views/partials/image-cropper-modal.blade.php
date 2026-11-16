<!-- ==========================================================================
     KOMPONEN MODAL CUT / CROP GAMBAR INTERAKTIF (CROPPER.JS)
     ========================================================================== -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" integrity="sha512-hvNR0F/e2J7zPPfLC9auFe3/SEWYJLVOaJEDXXx3GYDQIirXd/dITu02j3Gve35t07Z542VEAc0v1000Gl44aw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js" integrity="sha512-9KkIqdfN7bTSvPFI8D/DYhTO5yGoN3VCQegC2TutTl4zZ54S5vvTWYevFzBsBIvZvUGhCU//TRNPvBO0050qng==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<style>
    .cropper-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(4px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
    }
    .cropper-modal-backdrop.active {
        opacity: 1;
    }
    .cropper-modal-card {
        background: #FFFFFF;
        border-radius: 16px;
        width: 100%;
        max-width: 680px;
        max-height: 92vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        overflow: hidden;
        border: 1px solid rgba(197, 160, 89, 0.25);
    }
    .cropper-workspace-box {
        width: 100%;
        height: 380px;
        background: #0D1615;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cropper-workspace-box img {
        max-width: 100%;
        max-height: 100%;
        display: block;
    }
    .cropper-toolbar-btn {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: #1E293B;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .cropper-toolbar-btn:hover {
        background: #FAF5EB;
        color: #C5A059;
        border-color: #C5A059;
    }
    .cropper-toolbar-btn.active {
        background: #141E1C;
        color: #FFFFFF;
        border-color: #141E1C;
    }
    .cropper-view-box.is-circular {
        border-radius: 50% !important;
        outline: 0 !important;
        box-shadow: 0 0 0 2px #C5A059 !important;
    }
    .cropper-face.is-circular {
        border-radius: 50% !important;
    }
    .crop-preview-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: #F0FDF4;
        border: 1px solid #BBF7D0;
        border-radius: 8px;
        color: #166534;
        font-size: 12px;
        font-weight: 600;
        margin-top: 8px;
    }
</style>

<!-- MODAL CROPPER -->
<div id="cropperModalDialog" class="cropper-modal-backdrop" role="dialog" aria-modal="true">
    <div class="cropper-modal-card">
        <!-- Header -->
        <div style="padding: 18px 24px; border-bottom: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: #FAF5EB; display: flex; align-items: center; justify-content: center; color: var(--primary); border: 1px solid rgba(197, 160, 89, 0.3);">
                    <i data-lucide="crop" style="width: 18px; height: 18px;"></i>
                </div>
                <div>
                    <h3 style="font-family: var(--font-heading); font-size: 1.1rem; font-weight: 700; margin: 0; color: #141E1C;">
                        Potong & Sesuaikan Gambar (Crop)
                    </h3>
                    <p style="margin: 2px 0 0; font-size: 11.5px; color: var(--muted);">
                        Sesuaikan area foto agar tidak terpotong saat ditampilkan di katalog dan beranda
                    </p>
                </div>
            </div>
            <button type="button" onclick="window.ImageCropperHelper.closeModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #94A3B8; line-height: 1;">&times;</button>
        </div>

        <!-- Canvas Box -->
        <div class="cropper-workspace-box">
            <img id="cropperWorkspaceImage" src="" alt="Area Potong">
        </div>

        <!-- Toolbar Options -->
        <div style="padding: 12px 24px; background: #FAF8F5; border-top: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9; display: flex; flex-wrap: wrap; gap: 8px; justify-content: space-between; align-items: center;">
            <!-- Aspect Ratio Buttons -->
            <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                <span style="font-size: 11.5px; font-weight: 600; color: #64748B; margin-right: 4px;">Bentuk & Rasio:</span>
                <button type="button" class="cropper-toolbar-btn" id="btnRatioCircle" data-ratio="circle" onclick="window.ImageCropperHelper.setCircleMode(this)" title="Bentuk Lingkaran Bulat untuk Foto Profil Avatar">
                    <i data-lucide="circle" style="width: 13px; height: 13px;"></i>
                    Lingkaran (Bulat)
                </button>
                <button type="button" class="cropper-toolbar-btn active" data-ratio="0.75" onclick="window.ImageCropperHelper.setAspectRatio(0.75, this)" title="Rasio 3:4 cocok untuk poster brosur vertikal">
                    3:4 (Poster)
                </button>
                <button type="button" class="cropper-toolbar-btn" data-ratio="1" onclick="window.ImageCropperHelper.setAspectRatio(1, this)" title="Rasio 1:1 persegi">
                    1:1 (Persegi)
                </button>
                <button type="button" class="cropper-toolbar-btn" data-ratio="1.777" onclick="window.ImageCropperHelper.setAspectRatio(16/9, this)" title="Rasio 16:9 landscape pelaminan">
                    16:9 (Landscape)
                </button>
                <button type="button" class="cropper-toolbar-btn" data-ratio="NaN" onclick="window.ImageCropperHelper.setAspectRatio(NaN, this)" title="Bebas tanpa batasan aspek rasio">
                    Bebas
                </button>
            </div>

            <!-- Transform Buttons -->
            <div style="display: flex; gap: 6px; align-items: center;">
                <button type="button" class="cropper-toolbar-btn" onclick="window.ImageCropperHelper.zoom(0.1)" title="Perbesar">
                    <i data-lucide="zoom-in" style="width: 14px; height: 14px;"></i>
                </button>
                <button type="button" class="cropper-toolbar-btn" onclick="window.ImageCropperHelper.zoom(-0.1)" title="Perkecil">
                    <i data-lucide="zoom-out" style="width: 14px; height: 14px;"></i>
                </button>
                <button type="button" class="cropper-toolbar-btn" onclick="window.ImageCropperHelper.rotate(-90)" title="Putar Kiri 90°">
                    <i data-lucide="rotate-ccw" style="width: 14px; height: 14px;"></i>
                </button>
                <button type="button" class="cropper-toolbar-btn" onclick="window.ImageCropperHelper.rotate(90)" title="Putar Kanan 90°">
                    <i data-lucide="rotate-cw" style="width: 14px; height: 14px;"></i>
                </button>
                <button type="button" class="cropper-toolbar-btn" onclick="window.ImageCropperHelper.reset()" title="Reset Potongan">
                    <i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i>
                </button>
            </div>
        </div>

        <!-- Footer Actions -->
        <div style="padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; gap: 12px;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.ImageCropperHelper.closeModal()">
                Batal
            </button>
            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="window.ImageCropperHelper.useOriginal()" title="Gunakan foto asli tanpa memotong">
                    Gunakan Asli Tanpa Crop
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="window.ImageCropperHelper.applyCrop()" style="padding: 8px 20px;">
                    <i data-lucide="check" style="width: 15px; height: 15px;"></i>
                    Terapkan Potongan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
window.ImageCropperHelper = (function() {
    let cropperInstance = null;
    let currentInput = null;
    let currentForm = null;
    let currentFile = null;
    let currentRatio = 0.75; // Default 3:4 portrait untuk poster paket!
    let originalDataUrl = null;
    let isCircleMode = false;
    let customHiddenInput = null;
    let customPreviewSelector = null;
    let customCallback = null;

    function applyCircleStyle() {
        const viewBox = document.querySelector('.cropper-view-box');
        const face = document.querySelector('.cropper-face');
        if (viewBox && face) {
            if (isCircleMode) {
                viewBox.classList.add('is-circular');
                face.classList.add('is-circular');
            } else {
                viewBox.classList.remove('is-circular');
                face.classList.remove('is-circular');
            }
        }
    }

    function setCircleMode(buttonElement) {
        isCircleMode = true;
        currentRatio = 1;
        if (cropperInstance) {
            cropperInstance.setAspectRatio(1);
        }
        document.querySelectorAll('.cropper-toolbar-btn[data-ratio]').forEach(btn => {
            btn.classList.remove('active');
        });
        if (buttonElement) {
            buttonElement.classList.add('active');
        } else {
            const circleBtn = document.getElementById('btnRatioCircle');
            if (circleBtn) circleBtn.classList.add('active');
        }
        applyCircleStyle();
    }

    function setAspectRatio(ratio, buttonElement) {
        isCircleMode = false;
        applyCircleStyle();
        currentRatio = ratio;
        if (cropperInstance) {
            cropperInstance.setAspectRatio(ratio);
        }
        document.querySelectorAll('.cropper-toolbar-btn[data-ratio]').forEach(btn => {
            btn.classList.remove('active');
        });
        if (buttonElement) {
            buttonElement.classList.add('active');
        }
    }

    function getRoundedCanvas(sourceCanvas) {
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        const width = sourceCanvas.width;
        const height = sourceCanvas.height;

        canvas.width = width;
        canvas.height = height;
        context.imageSmoothingEnabled = true;
        context.imageSmoothingQuality = 'high';
        context.drawImage(sourceCanvas, 0, 0, width, height);
        context.globalCompositeOperation = 'destination-in';
        context.beginPath();
        context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI, true);
        context.fill();
        return canvas;
    }

    function openModalWithFile(file, inputElement, options = {}) {
        if (!file || !file.type.startsWith('image/')) {
            alert('Silakan pilih file gambar yang valid (JPG, PNG, WEBP).');
            return;
        }

        currentInput = inputElement;
        currentForm = inputElement ? inputElement.closest('form') : null;
        currentFile = file;

        // Options override
        customHiddenInput = options.hiddenInput || (inputElement ? inputElement.dataset.cropTarget : null);
        customPreviewSelector = options.previewImg || (inputElement ? inputElement.dataset.previewImg : null);
        customCallback = options.callback || null;

        const wantCircle = options.isCircle || (inputElement && inputElement.dataset.circle === 'true');
        if (wantCircle) {
            isCircleMode = true;
            currentRatio = 1;
        } else {
            isCircleMode = false;
            currentRatio = options.ratio || (inputElement && inputElement.dataset.ratio ? parseFloat(inputElement.dataset.ratio) : 0.75);
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            originalDataUrl = e.target.result;
            const img = document.getElementById('cropperWorkspaceImage');
            img.src = originalDataUrl;

            const modal = document.getElementById('cropperModalDialog');
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('active');
            }, 10);
            document.body.style.overflow = 'hidden';

            if (cropperInstance) {
                cropperInstance.destroy();
            }

            // Sync active ratio button
            document.querySelectorAll('.cropper-toolbar-btn[data-ratio]').forEach(btn => {
                btn.classList.remove('active');
            });
            if (isCircleMode) {
                const circleBtn = document.getElementById('btnRatioCircle');
                if (circleBtn) circleBtn.classList.add('active');
            } else {
                const matchBtn = document.querySelector(`.cropper-toolbar-btn[data-ratio="${currentRatio}"]`);
                if (matchBtn) matchBtn.classList.add('active');
            }

            // Inisialisasi Cropper setelah gambar terpasang
            cropperInstance = new Cropper(img, {
                aspectRatio: currentRatio,
                viewMode: 1, // Batasi crop box di dalam canvas
                autoCropArea: 0.95,
                responsive: true,
                restore: false,
                guides: true,
                center: true,
                highlight: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                ready: function() {
                    applyCircleStyle();
                },
                cropstart: function() {
                    if (isCircleMode) applyCircleStyle();
                },
                cropend: function() {
                    if (isCircleMode) applyCircleStyle();
                }
            });

            if (window.lucide) {
                window.lucide.createIcons();
            }
        };
        reader.readAsDataURL(file);
    }

    function closeModal() {
        const modal = document.getElementById('cropperModalDialog');
        if (!modal) return;
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            if (cropperInstance) {
                cropperInstance.destroy();
                cropperInstance = null;
            }
        }, 200);
    }

    function zoom(value) {
        if (cropperInstance) {
            cropperInstance.zoom(value);
        }
    }

    function rotate(degree) {
        if (cropperInstance) {
            cropperInstance.rotate(degree);
            if (isCircleMode) {
                setTimeout(applyCircleStyle, 50);
            }
        }
    }

    function reset() {
        if (cropperInstance) {
            cropperInstance.reset();
            if (isCircleMode) {
                setTimeout(applyCircleStyle, 50);
            }
        }
    }

    function useOriginal() {
        const hiddenInputName = customHiddenInput || (currentInput ? currentInput.dataset.cropTarget : 'cropped_image') || 'cropped_image';
        if (currentForm) {
            let hiddenInput = currentForm.querySelector(`input[name="${hiddenInputName}"], #${hiddenInputName.replace('#','')}`);
            if (hiddenInput) {
                hiddenInput.value = '';
            }
            if (customPreviewSelector && originalDataUrl) {
                const previewImg = document.querySelector(customPreviewSelector);
                if (previewImg) previewImg.src = originalDataUrl;
            } else {
                renderPreviewBadge(currentFile, originalDataUrl, false);
            }
        }
        closeModal();
    }

    function applyCrop() {
        if (!cropperInstance) return;

        // Ambil canvas hasil crop dengan resolusi maksimal yang optimal
        let canvas = cropperInstance.getCroppedCanvas({
            maxWidth: 1920,
            maxHeight: 2560,
            fillColor: isCircleMode ? 'transparent' : '#FFFFFF',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        if (!canvas) {
            alert('Gagal memproses potongan gambar. Silakan coba kembali.');
            return;
        }

        let croppedDataUrl;
        if (isCircleMode) {
            canvas = getRoundedCanvas(canvas);
            croppedDataUrl = canvas.toDataURL('image/png');
        } else {
            croppedDataUrl = canvas.toDataURL('image/jpeg', 0.92);
        }

        // 1. Simpan base64 ke hidden input form
        const hiddenInputName = customHiddenInput || (currentInput ? currentInput.dataset.cropTarget : 'cropped_image') || 'cropped_image';
        if (currentForm) {
            let hiddenInput = currentForm.querySelector(`input[name="${hiddenInputName}"], #${hiddenInputName.replace('#','')}`);
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = hiddenInputName;
                hiddenInput.id = hiddenInputName.replace('#','');
                currentForm.appendChild(hiddenInput);
            }
            hiddenInput.value = croppedDataUrl;

            // Update custom preview image jika diset
            if (customPreviewSelector) {
                const previewImg = document.querySelector(customPreviewSelector);
                if (previewImg) {
                    previewImg.src = croppedDataUrl;
                }
            }

            // 2. Upayakan konversi ke Blob dan masukkan ke input file via DataTransfer
            const mimeType = isCircleMode ? 'image/png' : 'image/jpeg';
            const fileExt = isCircleMode ? '.png' : '.jpg';
            canvas.toBlob(function(blob) {
                if (blob && currentInput) {
                    try {
                        const croppedFileName = 'crop_' + (currentFile ? currentFile.name.replace(/\.[^/.]+$/, "") : "image") + fileExt;
                        const croppedFile = new File([blob], croppedFileName, { type: mimeType });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(croppedFile);
                        currentInput.files = dataTransfer.files;
                    } catch (e) {
                        console.log('DataTransfer tidak didukung atau dibatasi browser, fallback ke hidden', e);
                    }
                }
            }, mimeType, 0.92);

            // 3. Render visual preview badge di form jika tidak menggunakan custom preview
            if (!customPreviewSelector) {
                renderPreviewBadge(currentFile, croppedDataUrl, true);
            }
        }

        if (typeof customCallback === 'function') {
            customCallback(croppedDataUrl);
        }

        closeModal();
    }

    function renderPreviewBadge(file, previewUrl, isCropped) {
        if (!currentInput) return;

        let previewContainer = currentInput.parentElement.querySelector('.cropper-result-preview');
        if (!previewContainer) {
            previewContainer = document.createElement('div');
            previewContainer.className = 'cropper-result-preview';
            currentInput.parentElement.appendChild(previewContainer);
        }

        previewContainer.innerHTML = `
            <div style="margin-top: 10px; display: flex; align-items: center; gap: 12px; padding: 10px; background: #FAF8F5; border-radius: 10px; border: 1px solid rgba(197, 160, 89, 0.35);">
                <img src="${previewUrl}" alt="Preview" style="width: 54px; height: 54px; border-radius: ${isCircleMode ? '50%' : '8px'}; object-fit: cover; border: 1px solid var(--line); flex-shrink: 0;">
                <div style="flex-grow: 1;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span class="badge ${isCropped ? 'badge-success' : 'badge-muted'}" style="font-size: 10px; padding: 2px 6px;">
                            ${isCropped ? (isCircleMode ? '✓ Dipotong Lingkaran' : '✓ Sudah Dipotong') : 'Foto Asli'}
                        </span>
                        <span style="font-size: 11.5px; font-weight: 600; color: #1E293B;">${file ? file.name : 'Gambar'}</span>
                    </div>
                    <div style="font-size: 11px; color: var(--muted); margin-top: 3px;">
                        ${isCropped ? 'Gambar siap diunggah dengan potongan yang disesuaikan' : 'Foto siap diunggah tanpa pemotongan'}
                    </div>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="window.ImageCropperHelper.reopenCrop(this)" style="padding: 4px 10px; font-size: 11px;">
                    <i data-lucide="crop" style="width: 12px; height: 12px;"></i>
                    Potong Ulang
                </button>
            </div>
        `;

        // Simpan referensi file pada preview container
        previewContainer._file = file;
        previewContainer._input = currentInput;

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    function reopenCrop(buttonElement) {
        const previewContainer = buttonElement.closest('.cropper-result-preview');
        if (previewContainer && previewContainer._file && previewContainer._input) {
            openModalWithFile(previewContainer._file, previewContainer._input, {
                isCircle: isCircleMode,
                hiddenInput: customHiddenInput,
                previewImg: customPreviewSelector
            });
        }
    }

    // Attach listener ke seluruh input dengan class .enable-cropper
    function attachToInput(inputElement) {
        if (!inputElement || inputElement._cropperAttached) return;
        inputElement._cropperAttached = true;

        inputElement.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                openModalWithFile(files[0], inputElement, {
                    isCircle: inputElement.dataset.circle === 'true',
                    hiddenInput: inputElement.dataset.cropTarget || null,
                    previewImg: inputElement.dataset.previewImg || null,
                });
            }
        });
    }

    function initAuto() {
        document.querySelectorAll('input[type="file"].enable-cropper').forEach(input => {
            attachToInput(input);
        });
    }

    document.addEventListener('DOMContentLoaded', initAuto);

    return {
        openModalWithFile,
        closeModal,
        setCircleMode,
        setAspectRatio,
        zoom,
        rotate,
        reset,
        useOriginal,
        applyCrop,
        reopenCrop,
        attachToInput,
        initAuto
    };
})();
</script>
