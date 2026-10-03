@extends('layouts.app')

@section('title', 'Katalog Layanan Make-Up, Dekorasi, Gaun & Busana - Glow & Grace')

@section('content')
<div class="container-wide" style="padding-top: 48px; padding-bottom: 72px;">
    <!-- Page Header (Editorial) -->
    <div style="margin-bottom: 32px; max-width: 800px;">
        <span class="badge badge-moss" style="margin-bottom: 10px;">Katalog Multi-Kategori</span>
        <h1 class="headline-lg" style="margin-bottom: 10px; font-weight: 500;">
            Katalog Layanan Terpisah & Busana Pernikahan
        </h1>
        <p class="body-md" style="line-height: 1.6; color: var(--body);">
            Pesan kebutuhan pernikahan Anda secara satuan: Rias Wajah Pengantin (MUA), Dekorasi Pelaminan, Konsep Akad Intimate, Sewa Gaun Modern, maupun Busana Adat Tradisional.
        </p>
    </div>

    <!-- Filter Bar Kabut Terpadu (Dropdown Rapi) -->
    <div class="filter-bar-kabut" style="margin-bottom: 36px;">
        <form action="{{ route('services') }}" method="GET" style="display: contents;">
            <!-- 1. Input Pencarian -->
            <div class="filter-item" style="flex: 2;">
                <label for="filter-search-service">Pencarian Nama Layanan</label>
                <input type="text" id="filter-search-service" name="search" value="{{ request('search') }}" 
                       placeholder="Cari kebaya, rias akad, dekorasi pelaminan..." 
                       class="form-input">
            </div>

            <!-- 2. Dropdown Kategori -->
            <div class="filter-item">
                <label for="filter-cat-select">Kategori Layanan</label>
                <select id="filter-cat-select" name="kategori" class="form-select">
                    <option value="all" {{ request('kategori', 'all') === 'all' ? 'selected' : '' }}>Semua Kategori</option>
                    @foreach($categories as $cat)
                        @if($cat->slug !== 'paket-bundling')
                            <option value="{{ $cat->slug }}" {{ request('kategori') === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- 3. Dropdown Vendor -->
            <div class="filter-item">
                <label for="filter-vendor-select">Mitra Vendor</label>
                <select id="filter-vendor-select" name="vendor_id" class="form-select" style="color: #1B1C1A; background-color: #FFFFFF;">
                    <option value="" style="color: #1B1C1A;">Semua Vendor ({{ $vendors->count() }})</option>
                    @foreach($vendors as $ven)
                        <option value="{{ $ven->id }}" {{ request('vendor_id') == $ven->id ? 'selected' : '' }} style="color: #1B1C1A;">
                            {{ $ven->vendor_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 4. Tombol Aksi -->
            <div style="display: flex; gap: 8px; align-items: flex-end; padding-top: 22px;">
                <button type="submit" class="btn btn-primary" style="height: 42px;">
                    <i data-lucide="filter" style="width: 14px; height: 14px;"></i>
                    Terapkan
                </button>
                @if(request()->hasAny(['search', 'vendor_id', 'kategori']))
                    <a href="{{ route('services') }}" class="btn btn-secondary" style="height: 42px;" title="Reset Filter">
                        <i data-lucide="rotate-ccw" style="width: 14px; height: 14px;"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Services Grid (Product Card Ratio 4:5 Gaya Kabut) -->
    @if($services->count() > 0)
        <div class="grid-cards" style="margin-bottom: 48px;">
            @foreach($services as $item)
                @php
                    $img = $item->image_url 
                        ?? $item->vendor->galleries->first()?->url 
                        ?? 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80';
                @endphp
                <article class="product-card">
                    <a href="{{ route('service.detail', $item->id) }}" class="product-card__media">
                        <img src="{{ $img }}" alt="{{ $item->name }}" loading="lazy" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80';">
                        <div style="position: absolute; top: 10px; left: 10px;">
                            <span class="badge badge-moss">
                                {{ $item->category->name }}
                            </span>
                        </div>
                    </a>

                    <div class="product-card__body">
                        <div class="caption" style="margin-bottom: 6px; display: inline-flex; align-items: center; gap: 4px;">
                            <i data-lucide="store" style="width: 12px; height: 12px; color: var(--muted);"></i>
                            {{ $item->vendor->vendor_name ?? '-' }}
                        </div>

                        <h3 class="product-card__name">
                            <a href="{{ route('service.detail', $item->id) }}">
                                {{ $item->name }}
                            </a>
                        </h3>

                        <p class="product-card__desc">
                            {{ $item->description ?? 'Layanan profesional dengan standar kualitas terbaik, bahan premium, dan penyesuaian kebutuhan acara.' }}
                        </p>

                        <div class="product-card__footer">
                            <div>
                                <div class="caption" style="font-size: 11px;">Tarif Layanan</div>
                                <div class="price">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <a href="{{ route('service.detail', $item->id) }}" class="btn btn-secondary btn-sm">
                                Detail Layanan
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div style="display: flex; justify-content: center;">
            {{ $services->withQueryString()->links() }}
        </div>
    @else
        <div class="card-kabut" style="text-align: center; padding: 64px 20px;">
            <i data-lucide="inbox" style="width: 48px; height: 48px; color: var(--muted); margin-bottom: 16px;"></i>
            <h3 style="font-family: var(--font-heading); font-size: 1.3rem; margin-bottom: 8px;">Tidak Ada Layanan yang Ditemukan</h3>
            <p class="body-sm" style="margin-bottom: 24px;">Silakan sesuaikan kata kunci pencarian atau ubah filter kategori.</p>
            <a href="{{ route('services') }}" class="btn btn-primary">
                Kembali ke Semua Layanan
            </a>
        </div>
    @endif
</div>
@endsection
