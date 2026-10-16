@extends('layouts.app')

@section('title', 'Manajemen Mitra Vendor - Admin Panel')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Admin Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Direktori Mitra</span>
            <h1 class="headline-lg">Pengelolaan Mitra Vendor</h1>
            <p class="body-sm">Kelola profil toko make-up & dekorasi, pemilik, data kontak, dan pantau performa vendor terdaftar.</p>
        </div>

        <!-- Search Form -->
        <form action="{{ route('admin.vendors') }}" method="GET" style="display: flex; gap: 8px; margin: 0;">
            <input type="text" name="search" class="form-input" placeholder="Cari nama toko, pemilik, kota..." value="{{ request('search') }}" style="width: 240px; height: 38px;">
            <button type="submit" class="btn btn-primary btn-sm" style="height: 38px;">
                <i data-lucide="search" style="width: 14px; height: 14px;"></i>
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.vendors') }}" class="btn btn-secondary btn-sm" style="height: 38px;">Reset</a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div style="background-color: var(--color-success); color: var(--color-success-text); padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; gap: 10px; border: 1px solid rgba(11,114,64,0.2);">
            <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="card-kabut" style="padding: 24px; border-radius: 14px; background: #fff;">
        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th>Nama Toko Vendor</th>
                        <th>Pemilik & Akun</th>
                        <th>Kontak Telepon</th>
                        <th>Alamat & Lokasi</th>
                        <th>Katalog Layanan</th>
                        <th>Pesanan</th>
                        <th>Rating</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $v)
                        <tr>
                            <td>
                                <strong style="font-size: 14.5px; color: var(--ink);">{{ $v->vendor_name }}</strong>
                                <div class="caption">ID Mitra #{{ $v->id }}</div>
                            </td>
                            <td>
                                <div><strong>{{ $v->owner_name }}</strong></div>
                                <div class="caption">{{ $v->user->email ?? '-' }}</div>
                            </td>
                            <td>
                                <div>{{ $v->phone }}</div>
                                @if($v->whatsapp_url)
                                    <a href="{{ $v->whatsapp_url }}" target="_blank" class="caption" style="color: #16A34A; display: inline-flex; align-items: center; gap: 3px;">
                                        <i data-lucide="phone" style="width: 11px; height: 11px;"></i>
                                        WhatsApp
                                    </a>
                                @endif
                            </td>
                            <td>
                                <div class="caption" style="max-width: 200px; color: var(--ink);">{{ Str::limit($v->location_address, 45) }}</div>
                            </td>
                            <td>
                                <div class="caption">
                                    <strong>{{ $v->bundlings_count }}</strong> Paket &bull; <strong>{{ $v->service_items_count }}</strong> Layanan
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-moss">
                                    {{ $v->orders_count }} Pesanan
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 4px; font-weight: 700; color: #F59E0B; font-size: 13px;">
                                    <i data-lucide="star" style="width: 13px; height: 13px; fill: #F59E0B;"></i>
                                    <span>{{ $v->averageRating() }}</span>
                                    <span class="caption" style="font-weight: 400; color: var(--muted);">({{ $v->reviews_count }})</span>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 6px;">
                                    <a href="{{ route('vendor.detail', $v->id) }}" target="_blank" class="btn btn-sm btn-secondary" style="height: 30px; padding: 0 8px; font-size: 11.5px;" title="Lihat di Web">
                                        <i data-lucide="external-link" style="width: 12px; height: 12px;"></i>
                                    </a>

                                    <button type="button" class="btn btn-sm btn-secondary" 
                                            onclick="openEditVendorModal({{ $v->id }}, '{{ addslashes($v->vendor_name) }}', '{{ addslashes($v->owner_name) }}', '{{ addslashes($v->phone) }}', '{{ addslashes($v->location_address) }}', '{{ addslashes($v->google_maps_url ?? '') }}', '{{ addslashes($v->description ?? '') }}')"
                                            style="height: 30px; padding: 0 10px; font-size: 11.5px;">
                                        <i data-lucide="edit-2" style="width: 12px; height: 12px;"></i>
                                        <span>Edit</span>
                                    </button>

                                    <form action="{{ route('admin.vendor.delete', $v->id) }}" method="POST" style="margin: 0;"
                                          onsubmit="return confirm('Peringatan: Yakin ingin menghapus data mitra vendor {{ $v->vendor_name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="height: 30px; padding: 0 8px; font-size: 11.5px; background: var(--color-error-bg); color: var(--color-error); border: 1px solid rgba(229,72,77,0.3);">
                                            <i data-lucide="trash-2" style="width: 12px; height: 12px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 48px; color: var(--muted);">
                                Tidak ada data mitra vendor yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $vendors->links() }}
        </div>
    </div>
</div>

<!-- Modal Edit Vendor -->
<div id="editVendorModal" class="admin-modal-backdrop" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="editVendorTitle">
    <div class="admin-modal-box" style="max-width: 540px; text-align: left;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(142, 60, 0, 0.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                    <i data-lucide="store" style="width: 18px; height: 18px;"></i>
                </div>
                <h3 id="editVendorTitle" class="headline-sm" style="margin: 0; font-size: 1.15rem; font-weight: 600;">Edit Data Mitra Vendor</h3>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeEditVendorModal()" style="padding: 4px 8px; height: 30px;" aria-label="Tutup">
                <i data-lucide="x" style="width: 16px; height: 16px;"></i>
            </button>
        </div>

        <form id="editVendorForm" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div>
                    <label class="form-label" for="edit_vendor_name">Nama Toko Vendor *</label>
                    <input type="text" name="vendor_name" id="edit_vendor_name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label" for="edit_owner_name">Nama Pemilik Toko *</label>
                    <input type="text" name="owner_name" id="edit_owner_name" class="form-input" required>
                </div>
            </div>

            <div style="margin-bottom: 14px;">
                <label class="form-label" for="edit_phone">Nomor WhatsApp / HP Aktif *</label>
                <input type="text" name="phone" id="edit_phone" class="form-input" required>
            </div>

            <div style="margin-bottom: 14px;">
                <label class="form-label" for="edit_location_address">Alamat / Lokasi Toko *</label>
                <textarea name="location_address" id="edit_location_address" class="form-textarea" rows="2" required></textarea>
            </div>

            <div style="margin-bottom: 14px;">
                <label class="form-label" for="edit_google_maps_url">Tautan Google Maps URL</label>
                <input type="url" name="google_maps_url" id="edit_google_maps_url" class="form-input" placeholder="https://maps.google.com/...">
            </div>

            <div style="margin-bottom: 20px;">
                <label class="form-label" for="edit_description">Deskripsi Profil Vendor</label>
                <textarea name="description" id="edit_description" class="form-textarea" rows="3"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; padding-top: 12px; border-top: 1px solid var(--border);">
                <button type="button" class="btn btn-secondary" onclick="closeEditVendorModal()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" style="width: 14px; height: 14px;"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditVendorModal(id, name, owner, phone, address, mapsUrl, desc) {
        const modal = document.getElementById('editVendorModal');
        const form = document.getElementById('editVendorForm');
        if (!modal || !form) return;

        form.action = '/admin/vendor/' + id;
        document.getElementById('edit_vendor_name').value = name || '';
        document.getElementById('edit_owner_name').value = owner || '';
        document.getElementById('edit_phone').value = phone || '';
        document.getElementById('edit_location_address').value = address || '';
        document.getElementById('edit_google_maps_url').value = mapsUrl || '';
        document.getElementById('edit_description').value = desc || '';

        modal.style.display = 'flex';
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';

        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function closeEditVendorModal() {
        const modal = document.getElementById('editVendorModal');
        if (!modal) return;
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Close on backdrop click
    document.getElementById('editVendorModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditVendorModal();
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditVendorModal();
        }
    });
</script>
@endpush
@endsection
