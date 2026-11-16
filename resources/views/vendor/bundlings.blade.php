@extends('layouts.app')

@section('title', 'Kelola Paket Bundling - ' . $vendor->vendor_name)

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Vendor Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Katalog Vendor</span>
            <h1 class="headline-md">Paket Make-Up & Dekorasi Saya</h1>
        </div>
        <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary btn-sm">
            &larr; Kembali ke Dashboard
        </a>
    </div>


    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 32px; align-items: start;">
        <!-- Form Tambah Paket Baru -->
        <div class="card-dribbble" style="padding: 24px; border-radius: 14px;">
            <h2 class="headline-sm" style="margin-bottom: 16px;">Tambah Paket Baru</h2>
            <form action="{{ route('vendor.bundling.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="package_name">Nama Paket *</label>
                    <input type="text" name="package_name" id="package_name" class="input-pill" placeholder="Contoh: Paket Resepsi Platinum" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="base_price">Harga Paket (Rp) *</label>
                    <input type="number" name="base_price" id="base_price" class="input-pill" placeholder="12000000" min="100000" step="50000" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Rincian & Item Paket *</label>
                    <textarea name="description" id="description" class="textarea-pill" rows="4" placeholder="Jelaskan fasilitas: riasan pengantin, jumlah baju, panjang pelaminan, dekorasi meja..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="image">Foto Representasi Paket</label>
                    <input type="file" name="image" id="image" class="input-pill enable-cropper" accept="image/*" style="padding-top: 12px;">
                    <div style="font-size: 11px; color: var(--muted); margin-top: 5px; display: flex; align-items: center; gap: 4px;">
                        <i data-lucide="scissors" style="width: 12px; height: 12px; color: var(--primary);"></i>
                        <span>Dilengkapi alat potong (crop) otomatis untuk menyesuaikan rasio poster / persegi.</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 46px;">
                    <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                    Simpan Paket ke Etalase
                </button>
            </form>
        </div>

        <!-- Daftar Paket Terdaftar -->
        <div class="card-dribbble" style="padding: 24px; border-radius: 14px;">
            <h2 class="headline-sm" style="margin-bottom: 16px;">Daftar Paket Terdaftar ({{ $bundlings->total() }})</h2>

            <div class="table-responsive">
                <table class="table-dribbble">
                    <thead>
                        <tr>
                            <th>Paket</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bundlings as $b)
                            @php
                                $photoUrl = $b->galleries->first()?->url;
                            @endphp
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        @if($photoUrl)
                                            <img src="{{ $photoUrl }}" alt="{{ $b->package_name }}" style="width: 46px; height: 46px; border-radius: 8px; object-fit: cover; flex-shrink: 0; border: 1px solid var(--line);">
                                        @else
                                            <div style="width: 46px; height: 46px; border-radius: 8px; background: #FAF5EB; display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0; border: 1px solid rgba(197, 160, 89, 0.25);">
                                                <i data-lucide="package" style="width: 20px; height: 20px;"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $b->package_name }}</strong>
                                            <p class="caption" style="margin-top: 4px; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                {{ $b->description }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($b->base_price, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <span class="badge {{ $b->is_active ? 'badge-success' : 'badge-muted' }}">
                                        {{ $b->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                        <!-- Tombol Edit Paket -->
                                        <button type="button" 
                                                class="btn btn-secondary btn-sm" 
                                                title="Edit Paket"
                                                onclick="openEditBundlingModal({{ json_encode([
                                                    'id' => $b->id,
                                                    'package_name' => $b->package_name,
                                                    'base_price' => (int)$b->base_price,
                                                    'description' => $b->description,
                                                    'is_active' => $b->is_active ? 1 : 0,
                                                    'photo_url' => $photoUrl,
                                                    'update_url' => route('vendor.bundling.update', $b->id)
                                                ]) }})">
                                            <i data-lucide="pencil" style="width: 14px; height: 14px; color: var(--primary);"></i>
                                        </button>

                                        <!-- Tombol Pratinjau -->
                                        <a href="{{ route('bundling.detail', $b->id) }}" class="btn btn-secondary btn-sm" title="Pratinjau">
                                            <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('vendor.bundling.delete', $b->id) }}" method="POST" onsubmit="return confirmDelete(event, this, 'Apakah Anda yakin ingin menghapus paket bundling ini?');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--color-error); border-color: rgba(229,72,77,0.3);" title="Hapus">
                                                <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 24px;">Belum ada paket bundling yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px;">
                {{ $bundlings->links() }}
            </div>
        </div>
    </div>
</div>

<!-- ==========================================================================
     MODAL EDIT PAKET BUNDLING VENDOR
     ========================================================================== -->
<div id="editBundlingModal" class="modal-backdrop-luxury" style="display: none;" role="dialog" aria-modal="true">
    <div class="modal-box-luxury" style="max-width: 540px; text-align: left; padding: 28px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #F1F5F9;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #FAF5EB; display: flex; align-items: center; justify-content: center; color: var(--primary); border: 1px solid rgba(197, 160, 89, 0.3);">
                    <i data-lucide="pencil-line" style="width: 18px; height: 18px;"></i>
                </div>
                <div>
                    <h3 style="font-family: var(--font-heading); font-size: 1.15rem; font-weight: 600; margin: 0; color: #1A2B28;">
                        Edit Paket Bundling
                    </h3>
                    <div style="font-size: 11.5px; color: var(--muted); margin-top: 2px;">
                        Perbarui rincian, harga, dan status paket pernikahan Anda
                    </div>
                </div>
            </div>
            <button type="button" onclick="closeEditBundlingModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--muted); line-height: 1;">&times;</button>
        </div>

        <form id="editBundlingForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" for="edit_package_name">Nama Paket *</label>
                <input type="text" name="package_name" id="edit_package_name" class="input-pill" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="edit_base_price">Harga Paket (Rp) *</label>
                    <input type="number" name="base_price" id="edit_base_price" class="input-pill" min="100000" step="50000" required>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="edit_is_active">Status Etalase *</label>
                    <select name="is_active" id="edit_is_active" class="input-pill" required>
                        <option value="1">Aktif (Ditampilkan)</option>
                        <option value="0">Nonaktif (Disembunyikan)</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" for="edit_description">Rincian & Item Paket *</label>
                <textarea name="description" id="edit_description" class="textarea-pill" rows="4" required></textarea>
            </div>

            <!-- Foto Saat Ini & Upload Foto Baru -->
            <div class="form-group" style="margin-bottom: 22px;">
                <label class="form-label">Foto Representasi Paket</label>
                <div id="editCurrentPhotoWrapper" style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px; padding: 10px; background: #FAF8F5; border-radius: 10px; border: 1px solid var(--line);">
                    <img id="editCurrentPhotoImg" src="" alt="Foto Paket" style="width: 52px; height: 52px; border-radius: 8px; object-fit: cover; border: 1px solid var(--line);">
                    <div style="font-size: 12px; color: var(--body);">
                        <strong style="color: var(--ink);">Foto Saat Ini</strong>
                        <div style="font-size: 11px; color: var(--muted); margin-top: 2px;">Pilih file baru di bawah ini jika ingin mengganti foto paket.</div>
                    </div>
                </div>
                <input type="file" name="image" id="edit_image" class="input-pill enable-cropper" accept="image/*" style="padding-top: 10px;">
                <div style="font-size: 11px; color: var(--muted); margin-top: 5px; display: flex; align-items: center; gap: 4px;">
                    <i data-lucide="scissors" style="width: 12px; height: 12px; color: var(--primary);"></i>
                    <span>Dilengkapi alat potong (crop) otomatis saat memilih gambar baru.</span>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #F1F5F9; padding-top: 16px;">
                <button type="button" class="btn btn-secondary" onclick="closeEditBundlingModal()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary" style="padding: 10px 22px;">
                    <i data-lucide="check" style="width: 15px; height: 15px;"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials.image-cropper-modal')

@push('scripts')
<script>
    function openEditBundlingModal(data) {
        const modal = document.getElementById('editBundlingModal');
        const form = document.getElementById('editBundlingForm');
        if (!modal || !form) return;

        form.action = data.update_url;
        document.getElementById('edit_package_name').value = data.package_name || '';
        document.getElementById('edit_base_price').value = data.base_price || '';
        document.getElementById('edit_is_active').value = (data.is_active == 1) ? '1' : '0';
        document.getElementById('edit_description').value = data.description || '';
        document.getElementById('edit_image').value = '';

        // Bersihkan data cropped preview sebelumnya
        const existingCropInput = form.querySelector('input[name="cropped_image"]');
        if (existingCropInput) existingCropInput.remove();
        const existingPreview = form.querySelector('.cropper-result-preview');
        if (existingPreview) existingPreview.remove();

        const photoWrapper = document.getElementById('editCurrentPhotoWrapper');
        const photoImg = document.getElementById('editCurrentPhotoImg');
        if (data.photo_url) {
            photoImg.src = data.photo_url;
            photoWrapper.style.display = 'flex';
        } else {
            photoWrapper.style.display = 'none';
        }

        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('active');
        }, 10);
        document.body.style.overflow = 'hidden';

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    function closeEditBundlingModal() {
        const modal = document.getElementById('editBundlingModal');
        if (!modal) return;

        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 200);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('editBundlingModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeEditBundlingModal();
                }
            });
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditBundlingModal();
        }
    });
</script>
@endpush
@endsection
