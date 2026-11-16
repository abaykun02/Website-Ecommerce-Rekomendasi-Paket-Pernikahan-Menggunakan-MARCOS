@extends('layouts.app')

@section('title', 'Manajemen Mitra Vendor - Admin Panel')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Admin Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Direktori Mitra</span>
            <h1 class="headline-lg">Pengelolaan Mitra Vendor</h1>
            <p class="body-sm">Verifikasi akun vendor baru, kelola profil toko make-up & dekorasi, pemilik, kontak, dan pantau status mitra.</p>
        </div>

        <!-- Search Form -->
        <form action="{{ route('admin.vendors') }}" method="GET" style="display: flex; gap: 8px; margin: 0;">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="search" class="form-input" placeholder="Cari nama toko, pemilik, kota..." value="{{ request('search') }}" style="width: 240px; height: 38px;">
            <button type="submit" class="btn btn-primary btn-sm" style="height: 38px;">
                <i data-lucide="search" style="width: 14px; height: 14px;"></i>
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.vendors', array_filter(['status' => request('status')])) }}" class="btn btn-secondary btn-sm" style="height: 38px;">Reset</a>
            @endif
        </form>
    </div>

    <!-- Status Filter Tabs -->
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 24px; flex-wrap: wrap; border-bottom: 1px solid var(--border); padding-bottom: 14px;">
        @php
            $currentStatus = request('status', 'all');
        @endphp
        <a href="{{ route('admin.vendors', array_filter(['search' => request('search')])) }}" 
           class="btn btn-sm {{ $currentStatus === 'all' ? 'btn-primary' : 'btn-secondary' }}" 
           style="border-radius: 20px; padding: 6px 14px; font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px;">
            <span>Semua Mitra</span>
            <span style="background: rgba(0,0,0,0.08); padding: 1px 7px; border-radius: 10px; font-size: 11px;">{{ $totalCount ?? 0 }}</span>
        </a>

        <a href="{{ route('admin.vendors', array_filter(['status' => 'pending', 'search' => request('search')])) }}" 
           class="btn btn-sm {{ $currentStatus === 'pending' ? 'btn-primary' : 'btn-secondary' }}" 
           style="border-radius: 20px; padding: 6px 14px; font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px; {{ ($pendingCount ?? 0) > 0 && $currentStatus !== 'pending' ? 'border: 1px solid #F59E0B; color: #B45309; background: #FFFBEB;' : '' }}">
            <i data-lucide="clock" style="width: 13px; height: 13px;"></i>
            <span>Menunggu Verifikasi</span>
            <span style="background: {{ ($pendingCount ?? 0) > 0 ? '#F59E0B' : 'rgba(0,0,0,0.08)' }}; color: {{ ($pendingCount ?? 0) > 0 ? '#FFFFFF' : 'inherit' }}; padding: 1px 7px; border-radius: 10px; font-size: 11px; font-weight: 700;">
                {{ $pendingCount ?? 0 }}
            </span>
        </a>

        <a href="{{ route('admin.vendors', array_filter(['status' => 'approved', 'search' => request('search')])) }}" 
           class="btn btn-sm {{ $currentStatus === 'approved' ? 'btn-primary' : 'btn-secondary' }}" 
           style="border-radius: 20px; padding: 6px 14px; font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px;">
            <i data-lucide="check-circle" style="width: 13px; height: 13px;"></i>
            <span>Disetujui / Aktif</span>
            <span style="background: rgba(0,0,0,0.08); padding: 1px 7px; border-radius: 10px; font-size: 11px;">{{ $approvedCount ?? 0 }}</span>
        </a>

        <a href="{{ route('admin.vendors', array_filter(['status' => 'rejected', 'search' => request('search')])) }}" 
           class="btn btn-sm {{ $currentStatus === 'rejected' ? 'btn-primary' : 'btn-secondary' }}" 
           style="border-radius: 20px; padding: 6px 14px; font-size: 12.5px; display: inline-flex; align-items: center; gap: 6px;">
            <i data-lucide="x-circle" style="width: 13px; height: 13px;"></i>
            <span>Ditolak</span>
            <span style="background: rgba(0,0,0,0.08); padding: 1px 7px; border-radius: 10px; font-size: 11px;">{{ $rejectedCount ?? 0 }}</span>
        </a>
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
                        <th>Status Verifikasi</th>
                        <th>Kontak Telepon</th>
                        <th>Alamat & Lokasi</th>
                        <th>Katalog</th>
                        <th>Pesanan</th>
                        <th>Rating</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $v)
                        <tr style="{{ $v->status === 'pending' ? 'background-color: #FFFDF7;' : '' }}">
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 42px; height: 42px; border-radius: 50%; overflow: hidden; border: 2px solid var(--color-border); flex-shrink: 0; background: #FAF5EB; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                                        <img src="{{ $v->avatar_url }}" alt="{{ $v->vendor_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div>
                                        <strong style="font-size: 14.5px; color: var(--ink);">{{ $v->vendor_name }}</strong>
                                        <div class="caption">ID Mitra #{{ $v->id }} &bull; Daftar: {{ $v->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><strong>{{ $v->owner_name }}</strong></div>
                                <div class="caption">{{ $v->user->email ?? '-' }}</div>
                            </td>
                            <td>
                                @if($v->status === 'pending')
                                    <span class="badge" style="background-color: #FEF3C7; color: #B45309; border: 1px solid #FCD34D; display: inline-flex; align-items: center; gap: 5px; font-weight: 600;">
                                        <i data-lucide="clock" style="width: 12px; height: 12px;"></i>
                                        Menunggu Verifikasi
                                    </span>
                                @elseif($v->status === 'approved')
                                    <span class="badge badge-success" style="display: inline-flex; align-items: center; gap: 5px;">
                                        <i data-lucide="check-circle-2" style="width: 12px; height: 12px;"></i>
                                        Disetujui
                                    </span>
                                @elseif($v->status === 'rejected')
                                    <span class="badge" style="background-color: var(--color-error-bg); color: var(--color-error); border: 1px solid rgba(229,72,77,0.3); display: inline-flex; align-items: center; gap: 5px;">
                                        <i data-lucide="x-circle" style="width: 12px; height: 12px;"></i>
                                        Ditolak
                                    </span>
                                    @if($v->rejection_reason)
                                        <div class="caption" style="color: var(--color-error); margin-top: 3px; max-width: 160px;" title="{{ $v->rejection_reason }}">
                                            Ket: {{ Str::limit($v->rejection_reason, 25) }}
                                        </div>
                                    @endif
                                @endif
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
                                <div class="caption" style="max-width: 180px; color: var(--ink);">{{ Str::limit($v->location_address, 40) }}</div>
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
                                <div style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 6px; flex-wrap: nowrap;">
                                    {{-- AKSI VERIFIKASI VENDOR --}}
                                    @if($v->status === 'pending')
                                        <!-- Tombol Setujui -->
                                        <form action="{{ route('admin.vendor.approve', $v->id) }}" method="POST" style="margin: 0;"
                                              onsubmit="return confirmAction(event, this, 'Verifikasi dan setujui pendaftaran mitra vendor {{ $v->vendor_name }}? Vendor akan dapat langsung masuk ke akunnya.', 'Verifikasi Vendor', 'Ya, Setujui');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="height: 30px; padding: 0 10px; font-size: 11.5px; background: #DCFCE7; color: #15803D; border: 1px solid #86EFAC; font-weight: 600;" title="Setujui Akun Vendor">
                                                <i data-lucide="check" style="width: 12px; height: 12px;"></i>
                                                <span>Setujui</span>
                                            </button>
                                        </form>

                                        <!-- Tombol Tolak -->
                                        <button type="button" class="btn btn-sm" onclick="openRejectVendorModal({{ $v->id }}, '{{ addslashes($v->vendor_name) }}')"
                                                style="height: 30px; padding: 0 8px; font-size: 11.5px; background: #FEE2E2; color: #B91C1C; border: 1px solid #FCA5A5;" title="Tolak Pendaftaran Vendor">
                                            <i data-lucide="x" style="width: 12px; height: 12px;"></i>
                                            <span>Tolak</span>
                                        </button>
                                    @elseif($v->status === 'rejected')
                                        <!-- Tombol Setujui Kembali -->
                                        <form action="{{ route('admin.vendor.approve', $v->id) }}" method="POST" style="margin: 0;"
                                              onsubmit="return confirmAction(event, this, 'Setujui kembali akun mitra vendor {{ $v->vendor_name }}?', 'Setujui Kembali', 'Ya, Setujui');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="height: 30px; padding: 0 8px; font-size: 11.5px; background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0;" title="Setujui Kembali">
                                                <i data-lucide="rotate-ccw" style="width: 11px; height: 11px;"></i>
                                                <span>Pulihkan</span>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('vendor.detail', $v->id) }}" class="btn btn-sm btn-secondary" style="height: 30px; padding: 0 8px; font-size: 11.5px;" title="Lihat Profil di Web">
                                        <i data-lucide="globe" style="width: 12px; height: 12px;"></i>
                                    </a>

                                    <button type="button" class="btn btn-sm btn-secondary" 
                                            onclick="openEditVendorModal({{ $v->id }}, '{{ addslashes($v->vendor_name) }}', '{{ addslashes($v->owner_name) }}', '{{ addslashes($v->phone) }}', '{{ addslashes($v->location_address) }}', '{{ addslashes($v->google_maps_url ?? '') }}', '{{ addslashes($v->description ?? '') }}')"
                                            style="height: 30px; padding: 0 10px; font-size: 11.5px;">
                                        <i data-lucide="edit-2" style="width: 12px; height: 12px;"></i>
                                        <span>Edit</span>
                                    </button>

                                    <!-- Form Hapus dengan Custom Dialog -->
                                    <form action="{{ route('admin.vendor.delete', $v->id) }}" method="POST" style="margin: 0;"
                                          onsubmit="return confirmDelete(event, this, 'Peringatan: Yakin ingin menghapus seluruh data mitra vendor {{ $v->vendor_name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="height: 30px; padding: 0 8px; font-size: 11.5px; background: var(--color-error-bg); color: var(--color-error); border: 1px solid rgba(229,72,77,0.3);" title="Hapus Data Vendor">
                                            <i data-lucide="trash-2" style="width: 12px; height: 12px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 48px; color: var(--muted);">
                                Tidak ada data mitra vendor yang sesuai dengan filter ini.
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

<!-- Modal Tolak Pendaftaran Vendor -->
<div id="rejectVendorModal" class="modal-backdrop-luxury" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="rejectVendorTitle">
    <div class="modal-box-luxury" style="max-width: 460px; text-align: left; border: 1px solid rgba(229,72,77,0.3);">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
            <div style="width: 44px; height: 44px; border-radius: 50%; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i data-lucide="user-x" style="width: 22px; height: 22px;"></i>
            </div>
            <div>
                <h3 id="rejectVendorTitle" class="headline-sm" style="margin: 0; font-size: 1.15rem; color: #111827;">Tolak Pendaftaran Vendor</h3>
                <p class="caption" style="margin: 2px 0 0 0; color: #6B7280;">Mitra: <strong id="rejectVendorName"></strong></p>
            </div>
        </div>

        <form id="rejectVendorForm" method="POST">
            @csrf
            <div style="margin-bottom: 18px;">
                <label class="form-label" for="rejection_reason" style="font-weight: 600; font-size: 13px;">Alasan Penolakan (Opsional)</label>
                <textarea name="rejection_reason" id="rejection_reason" class="form-textarea" rows="3" placeholder="Contoh: Dokumen toko belum lengkap, nomor kontak tidak dapat dihubungi, dll."></textarea>
                <p class="caption" style="margin-top: 6px; color: #6B7280;">Catatan ini akan tampil saat vendor mencoba masuk ke akunnya.</p>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary" onclick="closeRejectVendorModal()" style="min-width: 90px;">Batal</button>
                <button type="submit" class="btn btn-primary" style="background-color: var(--color-error); border-color: var(--color-error); color: #fff;">
                    <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                    Tolak Akun
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

    function openRejectVendorModal(id, vendorName) {
        const modal = document.getElementById('rejectVendorModal');
        const form = document.getElementById('rejectVendorForm');
        const nameEl = document.getElementById('rejectVendorName');
        if (!modal || !form) return;

        form.action = '/admin/vendor/' + id + '/tolak';
        if (nameEl) nameEl.innerText = vendorName;
        document.getElementById('rejection_reason').value = '';

        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
        document.body.style.overflow = 'hidden';

        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function closeRejectVendorModal() {
        const modal = document.getElementById('rejectVendorModal');
        if (!modal) return;
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 250);
    }

    // Close on backdrop click
    document.getElementById('editVendorModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditVendorModal();
        }
    });

    document.getElementById('rejectVendorModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectVendorModal();
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditVendorModal();
            closeRejectVendorModal();
        }
    });
</script>
@endpush
@endsection
