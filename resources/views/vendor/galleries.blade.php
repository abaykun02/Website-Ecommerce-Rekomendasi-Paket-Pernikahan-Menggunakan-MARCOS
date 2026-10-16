@extends('layouts.app')

@section('title', 'Galeri Foto Portofolio - ' . $vendor->vendor_name)

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Vendor Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Portofolio Vendor</span>
            <h1 class="headline-md">Galeri Foto Riasan & Pelaminan</h1>
        </div>
        <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary btn-sm">
            &larr; Kembali ke Dashboard
        </a>
    </div>


    <!-- Form Unggah Foto -->
    <div class="card-dribbble" style="padding: 24px; border-radius: 14px; margin-bottom: 32px;">
        <h2 class="headline-sm" style="margin-bottom: 16px;">Unggah Foto Portofolio Baru</h2>
        
        <form action="{{ route('vendor.gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid-cols-3" style="gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="bundling_id">Kaitkan ke Paket (Opsional)</label>
                    <select name="bundling_id" id="bundling_id" class="select-pill">
                        <option value="">Galeri Umum Vendor</option>
                        @foreach($bundlings as $b)
                            <option value="{{ $b->id }}">{{ $b->package_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="caption">Keterangan / Caption Foto</label>
                    <input type="text" name="caption" id="caption" class="input-pill" placeholder="Contoh: Riasan Sunda Siger Flawless">
                </div>

                <div class="form-group">
                    <label class="form-label" for="image">Berkas Gambar (File Foto)</label>
                    <input type="file" name="image" id="image" class="input-pill" accept="image/*" style="padding-top: 12px;">
                </div>
            </div>

            <div class="form-group" style="margin-top: 8px;">
                <label class="form-label" for="image_url">Atau Masukkan Tautan URL Gambar Langsung</label>
                <input type="url" name="image_url" id="image_url" class="input-pill" placeholder="https://images.unsplash.com/photo-...">
            </div>

            <button type="submit" class="btn btn-primary" style="height: 44px; padding: 0 24px;">
                <i data-lucide="upload" style="width: 16px; height: 16px;"></i>
                Simpan Foto ke Portofolio
            </button>
        </form>
    </div>

    <!-- Photo Grid -->
    <h2 class="headline-sm" style="margin-bottom: 16px;">Koleksi Foto Portofolio ({{ $galleries->total() }})</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; margin-bottom: 32px;">
        @forelse($galleries as $gal)
            <div class="card-dribbble" style="padding: 10px; position: relative;">
                <img src="{{ $gal->url }}" alt="{{ $gal->caption }}" class="img-cover-box" style="height: 200px; margin-bottom: 10px;">
                
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                    <div>
                        <div class="caption" style="font-weight: 700; color: var(--color-primary);">
                            {{ $gal->caption ?: 'Tanpa keterangan' }}
                        </div>
                        @if($gal->bundling)
                            <span class="badge badge-muted" style="margin-top: 4px; font-size: 10px;">
                                {{ $gal->bundling->package_name }}
                            </span>
                        @endif
                    </div>

                    <form action="{{ route('vendor.gallery.delete', $gal->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini dari galeri?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--color-error); padding: 0 10px; height: 28px;" title="Hapus Foto">
                            <i data-lucide="trash-2" style="width: 12px; height: 12px;"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 48px;">
                <p class="body-sm">Belum ada foto yang diunggah ke portofolio Anda.</p>
            </div>
        @endforelse
    </div>

    <div>
        {{ $galleries->links() }}
    </div>
</div>
@endsection
