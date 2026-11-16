@extends('layouts.app')

@section('title', 'Profil Vendor - ' . $vendor->vendor_name)

@section('content')
<div class="container" style="max-width: 720px; padding-top: 36px; padding-bottom: 64px;">
    <!-- Vendor Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Pengaturan Toko</span>
            <h1 class="headline-md">Pengaturan Profil Vendor</h1>
        </div>
        <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary btn-sm">
            &larr; Kembali ke Dashboard
        </a>
    </div>


    <div class="card-dribbble" style="padding: 32px; border-radius: 14px;">
        <form action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Section Foto Sampul & Foto Profil Toko -->
            <div style="background: #FAF8F5; border: 1px solid rgba(197, 160, 89, 0.35); border-radius: 14px; overflow: hidden; margin-bottom: 26px;">
                <!-- Cover Banner Area -->
                <div style="position: relative; width: 100%; height: 160px; background: #262626; overflow: hidden;">
                    <img id="vendorCoverPreview" 
                         src="{{ $vendor->cover_url }}" 
                         alt="Cover {{ $vendor->vendor_name }}" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.65) 100%);"></div>

                    <!-- Cover Actions Top/Bottom Right -->
                    <div style="position: absolute; bottom: 12px; right: 14px; display: flex; gap: 8px; align-items: center; z-index: 2;">
                        <label class="btn btn-sm btn-primary" style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; font-size: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                            <i data-lucide="image" style="width: 14px; height: 14px;"></i>
                            <span>{{ $vendor->hasCover() ? 'Ganti Foto Sampul' : 'Unggah Foto Sampul' }}</span>
                            <input type="file" 
                                   name="cover_image" 
                                   id="cover_image" 
                                   accept="image/png,image/jpeg,image/jpg,image/webp" 
                                   onchange="onSelectCover(this)"
                                   style="display: none;">
                        </label>
                        <input type="hidden" name="remove_cover_image" id="remove_cover_image_input" value="0">
                        @if($vendor->hasCover())
                            <button type="button" 
                                    id="btnRemoveCover"
                                    class="btn btn-sm" 
                                    onclick="removeCoverImage()" 
                                    style="background: rgba(220, 38, 38, 0.9); color: #fff; border: none; padding: 6px 12px; font-size: 12px; border-radius: 8px; display: inline-flex; align-items: center; gap: 4px;"
                                    title="Hapus foto sampul">
                                <i data-lucide="trash-2" style="width: 13px; height: 13px;"></i>
                                <span>Hapus Sampul</span>
                            </button>
                        @endif
                    </div>

                    <div style="position: absolute; top: 12px; left: 16px; color: #fff; z-index: 2; font-size: 11.5px; font-weight: 600; text-shadow: 0 1px 3px rgba(0,0,0,0.8); display: flex; align-items: center; gap: 5px;">
                        <i data-lucide="panorama" style="width: 14px; height: 14px;"></i>
                        <span>Foto Sampul Toko (Banner)</span>
                    </div>
                </div>

                <!-- Profile Avatar Area -->
                <div style="padding: 16px 22px 20px; display: flex; align-items: flex-start; gap: 20px; flex-wrap: wrap;">
                    <div style="position: relative; width: 92px; height: 92px; margin-top: -46px; flex-shrink: 0; z-index: 3;">
                        <img id="vendorAvatarPreview" 
                             src="{{ $vendor->avatar_url }}" 
                             alt="{{ $vendor->vendor_name }}" 
                             style="width: 92px; height: 92px; border-radius: 50%; object-fit: cover; border: 3.5px solid #fff; box-shadow: 0 4px 14px rgba(0,0,0,0.18); background: #FAF5EB;">
                        <span style="position: absolute; bottom: 0; right: 0; background: var(--color-primary); color: white; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.2);" title="Foto Profil Toko">
                            <i data-lucide="store" style="width: 13px; height: 13px;"></i>
                        </span>
                    </div>

                    <div style="flex: 1; min-width: 240px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <h3 style="font-size: 15px; font-weight: 700; color: #1E293B; margin: 0;">Foto Profil / Logo Toko</h3>
                            <span class="badge badge-accent" style="font-size: 10px; padding: 2px 6px;">Avatar Lingkaran</span>
                        </div>
                        <p style="font-size: 12px; color: var(--color-secondary); margin-bottom: 12px; line-height: 1.45;">
                            Tampil bulat di direktori vendor, admin, dan katalog publik. Pilih foto langsung tanpa perlu dipotong manual.
                        </p>

                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <label class="btn btn-primary btn-sm" style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px; padding: 7px 15px;">
                                <i data-lucide="upload" style="width: 14px; height: 14px;"></i>
                                <span>{{ $vendor->hasAvatar() ? 'Ganti Foto Profil' : 'Pilih Foto Profil' }}</span>
                                <input type="file" 
                                       name="profile_image" 
                                       id="profile_image" 
                                       accept="image/png,image/jpeg,image/jpg,image/webp" 
                                       onchange="onSelectAvatar(this)"
                                       style="display: none;">
                            </label>

                            <input type="hidden" name="remove_profile_image" id="remove_profile_image_input" value="0">

                            @if($vendor->hasAvatar())
                                <button type="button" 
                                        id="btnRemoveAvatar"
                                        class="btn btn-secondary btn-sm" 
                                        onclick="removeProfileImage()" 
                                        style="color: #DC2626; border-color: #FCA5A5; padding: 7px 12px; display: inline-flex; align-items: center; gap: 4px;"
                                        title="Hapus foto profil saat ini">
                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                    <span>Hapus Foto</span>
                                </button>
                            @endif

                            <span id="avatarStatusBadge" style="font-size: 11.5px; color: #64748B;">
                                @if($vendor->hasAvatar())
                                    <span style="color: #16A34A; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                        <i data-lucide="check" style="width: 13px; height: 13px;"></i> Foto profil terpasang
                                    </span>
                                @else
                                    Maks. 5MB (JPG, PNG, WEBP)
                                @endif
                            </span>
                        </div>
                        <div id="coverStatusBadge" style="font-size: 11.5px; color: #64748B; margin-top: 6px;"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="vendor_name">Nama Brand / Usaha Vendor *</label>
                <input type="text" name="vendor_name" id="vendor_name" class="input-pill" value="{{ old('vendor_name', $vendor->vendor_name) }}" required>
            </div>

            <div class="grid-cols-2" style="gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="owner_name">Nama Pemilik / MUA *</label>
                    <input type="text" name="owner_name" id="owner_name" class="input-pill" value="{{ old('owner_name', $vendor->owner_name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">No. WhatsApp / Telepon *</label>
                    <input type="text" name="phone" id="phone" class="input-pill" value="{{ old('phone', $vendor->phone) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="location_address">Alamat Lengkap Galeri / Studio *</label>
                <textarea name="location_address" id="location_address" class="textarea-pill" rows="3" required>{{ old('location_address', $vendor->location_address) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="google_maps_url">Tautan Google Maps</label>
                <input type="url" name="google_maps_url" id="google_maps_url" class="input-pill" placeholder="https://maps.google.com/..." value="{{ old('google_maps_url', $vendor->google_maps_url) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Deskripsi & Keunggulan Layanan</label>
                <textarea name="description" id="description" class="textarea-pill" rows="4">{{ old('description', $vendor->description) }}</textarea>
            </div>

            <!-- Section Rekening Bank Penerima Pembayaran -->
            <div style="margin: 28px 0 24px; padding: 22px; border-radius: 12px; background: #FAF8F5; border: 1px solid rgba(197, 160, 89, 0.35);">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <div style="width: 30px; height: 30px; border-radius: 8px; background: rgba(142, 60, 0, 0.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                        <i data-lucide="credit-card" style="width: 17px; height: 17px;"></i>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #1E293B; margin: 0;">Rekening Bank Penerima Pembayaran Pelanggan</h3>
                </div>
                <p style="font-size: 12.5px; color: var(--muted); margin-bottom: 18px; line-height: 1.5;">
                    Rekening ini akan otomatis terhubung dan ditampilkan langsung kepada pelanggan saat mereka menyelesaikan pembayaran pesanan paket/layanan toko Anda.
                </p>

                <div class="grid-cols-2" style="gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="bank_name">Nama Bank Penerima</label>
                        <input type="text" name="bank_name" id="bank_name" class="input-pill" 
                               list="bankOptions"
                               placeholder="Contoh: BCA / Mandiri / BRI / BSI" 
                               value="{{ old('bank_name', $vendor->bank_name) }}">
                        <datalist id="bankOptions">
                            <option value="BCA">
                            <option value="Mandiri">
                            <option value="BRI">
                            <option value="BNI">
                            <option value="BSI (Bank Syariah Indonesia)">
                            <option value="Bank Sumut">
                            <option value="Bank Sumut Syariah">
                            <option value="Permata">
                            <option value="CIMB Niaga">
                            <option value="Danamon">
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="bank_account_number">Nomor Rekening Bank</label>
                        <input type="text" name="bank_account_number" id="bank_account_number" class="input-pill" 
                               placeholder="Contoh: 828-1234-5678" 
                               value="{{ old('bank_account_number', $vendor->bank_account_number) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="bank_account_name">Atas Nama Pemilik Rekening</label>
                    <input type="text" name="bank_account_name" id="bank_account_name" class="input-pill" 
                           placeholder="Nama pemilik rekening sesuai buku tabungan" 
                           value="{{ old('bank_account_name', $vendor->bank_account_name) }}">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="bank_notes">Catatan Tambahan untuk Pelanggan (Opsional)</label>
                    <input type="text" name="bank_notes" id="bank_notes" class="input-pill" 
                           placeholder="Contoh: Mohon cantumkan kode pesanan pada kolom berita transfer" 
                           value="{{ old('bank_notes', $vendor->bank_notes) }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; height: 48px;">
                <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                Simpan Perubahan Profil
            </button>
        </form>
    </div>
</div>

<script>
    function onSelectAvatar(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('vendorAvatarPreview').src = e.target.result;
                document.getElementById('remove_profile_image_input').value = '0';
                document.getElementById('avatarStatusBadge').innerHTML = 
                    '<span style="color: #16A34A; font-weight: 600;">✓ Foto profil dipilih: ' + file.name + ' (Klik simpan di bawah)</span>';
                const btnRemove = document.getElementById('btnRemoveAvatar');
                if (btnRemove) btnRemove.style.display = 'inline-flex';
            };
            reader.readAsDataURL(file);
        }
    }

    function removeProfileImage() {
        if (confirm('Apakah Anda yakin ingin menghapus foto profil ini dan kembali ke logo avatar inisial standar?')) {
            document.getElementById('remove_profile_image_input').value = '1';
            document.getElementById('profile_image').value = '';
            
            // Fallback SVG avatar
            const initials = "{{ strtoupper(substr(trim($vendor->vendor_name ?: 'V'), 0, 2)) }}";
            const svg = 'data:image/svg+xml;utf8,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" rx="50" fill="#8E3C00"/><text x="50%" y="54%" text-anchor="middle" dominant-baseline="middle" fill="#FFFFFF" font-family="Arial, sans-serif" font-weight="bold" font-size="38">' + initials + '</text></svg>');
            document.getElementById('vendorAvatarPreview').src = svg;
            
            document.getElementById('avatarStatusBadge').innerHTML = 
                '<span style="color: #DC2626; font-weight: 600;">Foto profil akan dihapus saat disimpan</span>';
            const btnRemove = document.getElementById('btnRemoveAvatar');
            if (btnRemove) btnRemove.style.display = 'none';
        }
    }

    function onSelectCover(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('vendorCoverPreview').src = e.target.result;
                document.getElementById('remove_cover_image_input').value = '0';
                document.getElementById('coverStatusBadge').innerHTML = 
                    '<span style="color: #16A34A; font-weight: 600;">✓ Foto sampul banner dipilih: ' + file.name + '</span>';
                const btnRemove = document.getElementById('btnRemoveCover');
                if (btnRemove) btnRemove.style.display = 'inline-flex';
            };
            reader.readAsDataURL(file);
        }
    }

    function removeCoverImage() {
        if (confirm('Apakah Anda yakin ingin menghapus foto sampul toko dan kembali ke gambar sampul bawaan?')) {
            document.getElementById('remove_cover_image_input').value = '1';
            document.getElementById('cover_image').value = '';
            document.getElementById('vendorCoverPreview').src = 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=80';
            document.getElementById('coverStatusBadge').innerHTML = 
                '<span style="color: #DC2626; font-weight: 600;">Foto sampul akan dihapus saat disimpan</span>';
            const btnRemove = document.getElementById('btnRemoveCover');
            if (btnRemove) btnRemove.style.display = 'none';
        }
    }
</script>
@endsection
