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

    <!-- Navigation Tabs for Vendor -->
    <div style="display: flex; gap: 10px; margin-bottom: 28px; border-bottom: 1px solid var(--color-border); padding-bottom: 12px; overflow-x: auto;">
        <a href="{{ route('vendor.dashboard') }}" class="chip">Ringkasan</a>
        <a href="{{ route('vendor.bundlings') }}" class="chip active">Kelola Paket</a>
        <a href="{{ route('vendor.galleries') }}" class="chip">Galeri Foto</a>
        <a href="{{ route('vendor.orders') }}" class="chip">Pesanan Masuk</a>
        <a href="{{ route('vendor.profile') }}" class="chip">Profil Vendor</a>
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
                    <input type="file" name="image" id="image" class="input-pill" accept="image/*" style="padding-top: 12px;">
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
                            <tr>
                                <td>
                                    <strong>{{ $b->package_name }}</strong>
                                    <p class="caption" style="margin-top: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $b->description }}
                                    </p>
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
                                        <a href="{{ route('bundling.detail', $b->id) }}" target="_blank" class="btn btn-secondary btn-sm" title="Pratinjau">
                                            <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                        </a>
                                        <form action="{{ route('vendor.bundling.delete', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?');" style="display: inline;">
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
@endsection
