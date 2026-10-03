@extends('layouts.app')

@section('title', 'Katalog Paket Make-Up & Dekorasi - Glow & Grace')

@section('content')
<div class="container-wide" style="padding-top: 48px; padding-bottom: 72px;">
    <!-- Breadcrumb & Header (Gaya Kabut) -->
    <div style="margin-bottom: 32px; max-width: 800px;">
        <span class="badge badge-moss" style="margin-bottom: 10px;">Katalog Terpadu</span>
        <h1 class="headline-lg" style="margin-bottom: 10px; font-weight: 500;">Semua Paket Make-Up & Dekorasi</h1>
        <p class="body-md" style="line-height: 1.6; color: var(--body);">
            Pilih paket pernikahan lengkap dari vendor terpercaya dengan transparansi rincian fasilitas dan dukungan evaluasi SPK MARCOS.
        </p>
    </div>

    <!-- Filter Bar Kabut Terpadu -->
    <div class="filter-bar-kabut" style="margin-bottom: 36px;">
        <form action="{{ route('bundlings') }}" method="GET" style="display: contents;">
            <div class="filter-item" style="flex: 2;">
                <label for="bundling-search">Pencarian Nama Paket</label>
                <input type="text" id="bundling-search" name="search" class="form-input" 
                       placeholder="Contoh: Akad, Resepsi, Gedung Luxury..." 
                       value="{{ request('search') }}">
            </div>

            <div class="filter-item">
                <label for="bundling-vendor-select">Mitra Vendor</label>
                <select id="bundling-vendor-select" name="vendor_id" class="form-select" style="color: #1B1C1A; background-color: #FFFFFF;">
                    <option value="" style="color: #1B1C1A;">Semua Vendor ({{ $vendors->count() }})</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }} style="color: #1B1C1A;">
                            {{ $v->vendor_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="bundling-price-select">Kisaran Anggaran</label>
                <select id="bundling-price-select" name="max_price" class="form-select">
                    <option value="">Semua Anggaran</option>
                    <option value="6000000" {{ request('max_price') == '6000000' ? 'selected' : '' }}>Di bawah Rp 6 Juta</option>
                    <option value="12000000" {{ request('max_price') == '12000000' ? 'selected' : '' }}>Di bawah Rp 12 Juta</option>
                    <option value="18000000" {{ request('max_price') == '18000000' ? 'selected' : '' }}>Di bawah Rp 18 Juta</option>
                    <option value="25000000" {{ request('max_price') == '25000000' ? 'selected' : '' }}>Di bawah Rp 25 Juta</option>
                </select>
            </div>

            <div style="display: flex; gap: 8px; align-items: flex-end; padding-top: 22px;">
                <button type="submit" class="btn btn-primary" style="height: 42px;">
                    <i data-lucide="filter" style="width: 14px; height: 14px;"></i>
                    Terapkan
                </button>
                @if(request()->anyFilled(['search', 'vendor_id', 'max_price']))
                    <a href="{{ route('bundlings') }}" class="btn btn-secondary" style="height: 42px;" title="Reset Filter">
                        <i data-lucide="rotate-ccw" style="width: 14px; height: 14px;"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bundling Grid (Standard E-Commerce Proportions) -->
    <div class="grid-cards" style="margin-bottom: 48px;">
        @forelse($bundlings as $item)
            @php
                $cover = $item->galleries->first()?->url 
                    ?? $item->vendor->galleries->first()?->url 
                    ?? 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80';
                $marcos = $rankMap[$item->id] ?? null;
            @endphp
            <article class="product-card">
                <div class="card-media-box">
                    <img src="{{ $cover }}" alt="{{ $item->package_name }}" class="img-cover-box" loading="lazy" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80';">
                    
                    @if($marcos)
                        @php
                            $r = $marcos['rank'];
                            $rBadge = match($r) {
                                1 => 'badge-narrative-gold',
                                2 => 'badge-narrative-moss',
                                default => 'badge-narrative-soft',
                            };
                            $rText = match($r) {
                                1 => 'Rekomendasi #1',
                                2 => 'Pilihan #2',
                                3 => 'Favorit #3',
                                default => 'Peringkat #' . $r,
                            };
                        @endphp
                        <div style="position: absolute; top: 8px; left: 8px; z-index: 2;">
                            <span class="{{ $rBadge }}">
                                {{ $rText }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="product-card__body">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                        <span class="caption" style="display: inline-flex; align-items: center; gap: 4px;">
                            <i data-lucide="store" style="width: 12px; height: 12px; color: var(--muted);"></i>
                            {{ $item->vendor->vendor_name ?? '-' }}
                        </span>
                        <span style="font-size: 0.78rem; font-weight: 600; color: var(--ink); display: flex; align-items: center; gap: 3px;">
                            <i data-lucide="star" style="width: 12px; height: 12px; fill: #FFAD48; stroke: #FFAD48;"></i>
                            {{ $item->vendor->averageRating() }}
                        </span>
                    </div>

                    <h3 class="product-card__name">
                        <a href="{{ route('bundling.detail', $item->id) }}">
                            {{ $item->package_name }}
                        </a>
                    </h3>

                    <p class="product-card__desc">
                        {{ $item->description }}
                    </p>

                    <div class="product-card__footer">
                        <div>
                            <div class="caption" style="font-size: 11px;">Harga Paket</div>
                            <div class="price">
                                Rp {{ number_format($item->base_price ?? $item->price ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <a href="{{ route('bundling.detail', $item->id) }}" class="btn btn-primary btn-sm">
                            Lihat Paket
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="card-kabut" style="grid-column: 1 / -1; text-align: center; padding: 64px 20px;">
                <i data-lucide="inbox" style="width: 48px; height: 48px; color: var(--muted); margin-bottom: 16px;"></i>
                <h3 style="font-family: var(--font-heading); font-size: 1.3rem; margin-bottom: 8px;">Tidak Ada Paket yang Sesuai</h3>
                <p class="body-sm" style="margin-bottom: 24px;">Silakan sesuaikan pilihan vendor atau batas anggaran Anda.</p>
                <a href="{{ route('bundlings') }}" class="btn btn-primary">
                    Lihat Semua Paket
                </a>
            </div>
        @endforelse
    </div>

    <div style="display: flex; justify-content: center;">
        {{ $bundlings->withQueryString()->links() }}
    </div>
</div>
@endsection
