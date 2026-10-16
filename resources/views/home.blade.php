@extends('layouts.app')

@section('title', 'Glow & Grace - Marketplace Make-Up & Dekorasi Pernikahan')

@section('content')
@php
    $currentPref = is_array($preferences) ? ($preferences[0] ?? '') : $preferences;
    $activeCategory = request('kategori', 'all');
@endphp

<!-- Hero Section (Editorial Gaya Kabut - Hangat & Tenang) -->
<section style="padding: 72px 0 54px; text-align: center; border-bottom: 1px solid var(--line);">
    <div class="container" style="max-width: 860px;">
        <div style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px;">
            <span class="badge badge-moss">E-Marketplace Jasa Pernikahan</span>
            <span class="badge badge-outline">Sistem Pendukung Keputusan MARCOS</span>
        </div>

        <h1 class="headline-display" style="margin-bottom: 20px; font-weight: 500;">
            Temukan Harmoni Riasan & Dekorasi untuk Hari Bahagia Anda
        </h1>

        <p class="body-md" style="margin-bottom: 36px; max-width: 680px; margin-left: auto; margin-right: auto; line-height: 1.65; color: var(--body);">
            Platform kurasi vendor pernikahan terbaik di Sumatera Utara. Dilengkapi sistem pemeringkatan objektif multi-kriteria untuk memudahkan Anda menentukan pilihan yang paling sesuai.
        </p>

        <!-- Filter Hybrid Terpadu: Search + Category Pills + Priority Chips + Collapsible Drawer -->
        @php
            $allPrefOptions = [
                'low_price' => ['label' => 'Harga Paling Terjangkau', 'sub' => 'Prioritas Efisiensi Anggaran (C5)', 'short' => '🏷️ Hemat Biaya'],
                'kualitas_flawless' => ['label' => 'Kualitas Riasan Flawless', 'sub' => 'Tahan Lama & Hasil Terbaik (C1)', 'short' => '✨ Rias Flawless'],
                'estetik_modern' => ['label' => 'Konsep Estetik & Modern', 'sub' => 'Desain Pelaminan Mewah (C2)', 'short' => '🏛️ Dekor Estetik'],
                'paket_lengkap' => ['label' => 'Kelengkapan Paket All-In', 'sub' => 'Fasilitas Terlengkap (C3)', 'short' => '📦 Fasilitas Lengkap'],
                'rating_ulasan' => ['label' => 'Rating & Ulasan Terbaik', 'sub' => 'Kepuasan Pengantin Tertinggi (C4)', 'short' => '⭐ Rating Terbaik'],
                'keamanan_risiko' => ['label' => 'Keamanan & Minim Risiko', 'sub' => 'Garansi Busana & Fasilitas (C6)', 'short' => '🛡️ Minim Risiko'],
                'terdekat' => ['label' => 'Lokasi Terdekat / Respon Cepat', 'sub' => 'Efisiensi Transportasi (C7)', 'short' => '📍 Respon Cepat'],
                'tradisional_adat' => ['label' => 'Busana Adat & Tradisional', 'sub' => 'Nuansa Budaya Nusantara (C2 & C3)', 'short' => '👑 Busana Adat'],
            ];
        @endphp

        <div class="filter-hybrid-container">
            <form id="homeFilterForm" action="{{ route('home') }}" method="GET">
                <!-- Hidden inputs for active category -->
                <input type="hidden" name="kategori" id="hiddenCategoryInput" value="{{ $activeCategory }}">

                <!-- 1. Search Box Modern -->
                <div class="filter-search-box">
                    <i data-lucide="search" style="width: 18px; height: 18px; color: var(--muted); margin-left: 4px; flex-shrink: 0;"></i>
                    <input type="text" id="filterSearchInput" name="search" value="{{ request('search') }}" 
                           placeholder="Cari paket rias, dekorasi, atau nama vendor..."
                           onkeydown="if(event.key === 'Enter') { event.preventDefault(); submitHomeFilter(); }">
                    @if(request('search'))
                        <button type="button" onclick="document.getElementById('filterSearchInput').value=''; submitHomeFilter();" style="background:none; border:none; color:var(--muted); cursor:pointer; padding:4px 8px;" title="Hapus pencarian">
                            <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                        </button>
                    @endif
                    <button type="button" class="btn-open-filter-drawer" onclick="openFilterDrawer()">
                        <i data-lucide="sliders-horizontal" style="width: 14px; height: 14px;"></i>
                        <span>Panel Filter</span>
                        @if(count($preferences) > 0)
                            <span class="filter-badge-counter">{{ count($preferences) }}</span>
                        @endif
                    </button>
                </div>

                <!-- 2. Kategori Layanan (Capsule / Pill Bar Horizontal - Klik Langsung Filter Otomatis) -->
                <div class="category-pills-bar">
                    <button type="button" class="category-pill-btn {{ $activeCategory === 'all' ? 'active' : '' }}" onclick="selectCategory('all')">
                        <i data-lucide="sparkles" style="width: 13px; height: 13px;"></i>
                        Semua Layanan
                    </button>
                    <button type="button" class="category-pill-btn {{ in_array($activeCategory, ['bundling', 'paket-bundling']) ? 'active' : '' }}" onclick="selectCategory('paket-bundling')">
                        <i data-lucide="gem" style="width: 13px; height: 13px;"></i>
                        Paket Bundling MUA & Dekor
                    </button>
                    @foreach($categories as $cat)
                        @if(!in_array($cat->slug, ['bundling', 'paket-bundling']))
                            @php
                                $catIcon = match($cat->slug) {
                                    'make-up-only' => 'sparkles',
                                    'dekorasi-only' => 'flower-2',
                                    'dekorasi-akad' => 'landmark',
                                    'gaun-busana' => 'shirt',
                                    'baju-adat' => 'crown',
                                    default => 'tag',
                                };
                            @endphp
                            <button type="button" class="category-pill-btn {{ $activeCategory === $cat->slug ? 'active' : '' }}" onclick="selectCategory('{{ $cat->slug }}')">
                                <i data-lucide="{{ $catIcon }}" style="width: 13px; height: 13px;"></i>
                                {{ $cat->name }}
                            </button>
                        @endif
                    @endforeach
                </div>

                <!-- 3. Prioritas Kebutuhan MARCOS (Multi-Select Chips: Klik Langsung Filter Tanpa Tombol Terapkan) -->
                <div class="priority-chips-section">
                    <div style="font-size: 11.5px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 6px;">
                        <span>Prioritas Kebutuhan (Pilih > 1):</span>
                    </div>

                    <div class="priority-chips-list">
                        @foreach($allPrefOptions as $key => $opt)
                            @php $isSelected = in_array($key, $preferences); @endphp
                            <!-- Checkbox yang tersimpan dan disubmit form -->
                            <input type="checkbox" name="preferences[]" id="pref_chk_{{ $key }}" value="{{ $key }}" {{ $isSelected ? 'checked' : '' }} style="display: none;">
                            
                            <button type="button" class="priority-chip-btn {{ $isSelected ? 'is-active' : '' }}" onclick="togglePriority('{{ $key }}')">
                                <span class="chip-indicator">
                                    @if($isSelected) ✓ @else + @endif
                                </span>
                                <span>{{ $opt['short'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    @if(count($preferences) > 0 || $activeCategory !== 'all' || request('search'))
                        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" style="border-radius: 9999px; height: 32px; font-size: 11.5px; padding: 0 12px; gap: 4px;" title="Reset Semua Filter">
                            <i data-lucide="rotate-ccw" style="width: 12px; height: 12px;"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if(count($preferences) > 0)
            <div style="display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.85rem; color: var(--muted); flex-wrap: wrap;">
                <span class="badge badge-moss">{{ count($preferences) }} Prioritas Aktif Terpilih</span>
                <span>Bobot MARCOS diperbarui seketika berdasarkan kriteria yang Anda tentukan.</span>
            </div>
        @endif
    </div>
</section>

<!-- Section 1: Rekomendasi Terpilih SPK MARCOS (Narasi Formal Tanpa Angka Menumpuk) -->
<section class="section-paper">
    <div class="container-wide">
        <!-- Section Header with Editorial Alignment -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="caption" style="text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; color: var(--moss); display: block; margin-bottom: 6px;">
                    Hasil Evaluasi Sistem Pendukung Keputusan
                </span>
                <h2 class="headline-md" style="margin-bottom: 4px;">Paket Rekomendasi Teratas untuk Anda</h2>
                <p class="body-sm">
                    Daftar paket bundling yang dinilai paling sesuai berdasarkan evaluasi objektif 7 kriteria multi-dimensi.
                </p>
            </div>

            <!-- Tautan Laporan Lengkap SPK MARCOS -->
            <div>
                <a href="{{ route('spk.rekomendasi', ['preferences' => (array)$currentPref]) }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                    <span>Lihat Laporan Lengkap MARCOS</span>
                    <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                </a>
            </div>
        </div>

        <!-- Grid Kartu Rekomendasi (Narasi Peringkat Formal E-Commerce) -->
        <div class="grid-cards">
            @foreach($topRecommendations as $rec)
                @php
                    $rank = $rec['rank'];
                    $rankTitle = match($rank) {
                        1 => 'Peringkat #1 Utama',
                        2 => 'Peringkat #2 Terbaik',
                        3 => 'Pilihan Favorit #3',
                        default => 'Alternatif #' . $rank,
                    };
                    $rankNarration = match($rank) {
                        1 => 'Pilihan paling optimal dengan performa unggul pada kualitas riasan, kelengkapan item, dan kepuasan pelanggan.',
                        2 => 'Pilihan terbaik kedua dengan keunggulan hasil riasan teruji dan penawaran fasilitas yang sangat seimbang.',
                        3 => 'Pilihan favorit dengan tingkat kepuasan pelanggan tinggi serta fleksibilitas konsep acara yang baik.',
                        default => 'Direkomendasikan sebagai alternatif terpercaya dengan kesesuaian pada kriteria penyesuaian khusus.',
                    };
                    $badgeClass = match($rank) {
                        1 => 'badge-narrative-gold',
                        2 => 'badge-narrative-moss',
                        default => 'badge-narrative-soft',
                    };
                @endphp
                <article class="product-card">
                    <!-- Gambar Paket Sewajarnya (E-Commerce Standard) -->
                    <div class="card-media-box">
                        <img src="{{ $rec['thumbnail'] }}" alt="{{ $rec['package_name'] }}" class="img-cover-box" loading="lazy" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80';">
                        
                        <!-- Formal Rank Badge Overlay -->
                        <div style="position: absolute; top: 8px; left: 8px; z-index: 2;">
                            <span class="{{ $badgeClass }}">
                                {{ $rankTitle }}
                            </span>
                        </div>
                    </div>

                    <!-- Body Card E-Commerce -->
                    <div class="product-card__body">
                        <!-- Vendor & Kategori -->
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                            <span class="caption" style="display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="store" style="width: 12px; height: 12px; color: var(--muted);"></i>
                                {{ $rec['vendor_name'] }}
                            </span>
                            <span class="badge badge-moss" style="font-size: 10px; padding: 1px 6px;">Terverifikasi</span>
                        </div>

                        <!-- Nama Paket -->
                        <h3 class="product-card__name">
                            <a href="{{ route('bundling.detail', $rec['bundling_id']) }}">
                                {{ $rec['package_name'] }}
                            </a>
                        </h3>

                        <!-- Narasi Kualitatif -->
                        <p class="product-card__desc">
                            {{ $rankNarration }}
                        </p>

                        <!-- Harga & CTA -->
                        <div class="product-card__footer">
                            <div>
                                <div class="caption" style="font-size: 11px;">Estimasi Harga</div>
                                <div class="price">
                                    Rp {{ number_format($rec['price'], 0, ',', '.') }}
                                </div>
                            </div>
                            <a href="{{ route('bundling.detail', $rec['bundling_id']) }}" class="btn btn-primary btn-sm">
                                Lihat Paket
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<!-- Section 2: Eksplorasi Layanan Multi-Kategori (Gaya Kabut) -->
<section class="section-cream" id="layanan-section">
    <div class="container-wide">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="caption" style="text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; color: var(--rust); display: block; margin-bottom: 6px;">
                    Katalog Layanan Spesifik
                </span>
                <h2 class="headline-md" style="margin-bottom: 4px;">Jelajahi Layanan & Busana Terpisah</h2>
                <p class="body-sm">
                    Pesan kebutuhan tertentu secara fleksibel: Rias Pengantin Akad/Resepsi, Dekorasi Pelaminan, Gaun Bridal, atau Baju Adat.
                </p>
            </div>

            <!-- Dropdown Filter Kategori Cepat -->
            <div style="display: flex; align-items: center; gap: 10px;">
                <label for="quickCategorySelect" class="caption" style="font-weight: 600; color: var(--ink);">Filter Tampilan:</label>
                <select id="quickCategorySelect" class="form-select" style="width: auto; padding: 6px 32px 6px 12px; font-size: 0.85rem;" onchange="filterServicesByCategory(this.value)">
                    <option value="all">Tampilkan Semua</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->slug }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                <a href="{{ route('services') }}" class="btn btn-secondary btn-sm">
                    Katalog Penuh &rarr;
                </a>
            </div>
        </div>

        <!-- Grid Kartu Layanan E-Commerce -->
        <div class="grid-cards" id="servicesContainer">
            @foreach($services as $item)
                @php
                    $img = $item->image_url 
                        ?? $item->vendor->galleries->first()?->url 
                        ?? 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80';
                @endphp
                <article class="product-card service-item-card" data-category="{{ $item->category->slug }}">
                    <a href="{{ route('service.detail', $item->id) }}" class="product-card__media">
                        <img src="{{ $img }}" alt="{{ $item->name }}" loading="lazy" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80';">
                        <div style="position: absolute; top: 10px; left: 10px;">
                            <span class="badge badge-moss">
                                {{ $item->category->name }}
                            </span>
                        </div>
                    </a>

                    <div class="product-card__body">
                        <div class="caption" style="margin-bottom: 4px; display: inline-flex; align-items: center; gap: 4px;">
                            <i data-lucide="store" style="width: 12px; height: 12px;"></i>
                            {{ $item->vendor->vendor_name ?? '-' }}
                        </div>

                        <h3 class="product-card__name">
                            <a href="{{ route('service.detail', $item->id) }}">
                                {{ $item->name }}
                            </a>
                        </h3>

                        <p class="product-card__desc">
                            {{ $item->description ?? 'Layanan profesional dengan jaminan kualitas terbaik dan penyesuaian kebutuhan acara.' }}
                        </p>

                        <div class="product-card__footer">
                            <div class="price">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                            <a href="{{ route('service.detail', $item->id) }}" class="btn btn-secondary btn-sm">
                                Detail
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>


<!-- Collapsible Sidebar Filter Drawer (Panel Samping Offcanvas - Mewah & Bersih) -->
<div id="filterDrawerBackdrop" class="filter-drawer-backdrop" onclick="closeFilterDrawer()"></div>
<div id="filterDrawerPanel" class="filter-drawer-panel">
    <!-- Header Drawer -->
    <div class="filter-drawer-header">
        <div>
            <h3 class="filter-drawer-title">Filter & Kriteria SPK</h3>
            <div style="font-size: 11.5px; color: var(--muted); margin-top: 2px;">
                Personalisasi Rekomendasi MARCOS Multi-Kriteria
            </div>
        </div>
        <button type="button" class="filter-drawer-close-btn" onclick="closeFilterDrawer()" title="Tutup Panel">
            <i data-lucide="x" style="width: 18px; height: 18px;"></i>
        </button>
    </div>

    <!-- Body Drawer -->
    <div class="filter-drawer-body">
        <!-- 1. Kategori Layanan -->
        <div class="filter-drawer-section">
            <div class="filter-drawer-section-title">
                <span>Pilih Kategori Layanan</span>
                <span style="font-weight: 500; font-size: 11px; text-transform: none; color: var(--primary);">Auto-Filter</span>
            </div>
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <div class="filter-drawer-card {{ $activeCategory === 'all' ? 'is-active' : '' }}" onclick="selectCategory('all')">
                    <input type="radio" name="drawer_kategori" {{ $activeCategory === 'all' ? 'checked' : '' }} style="accent-color: var(--primary); margin-top: 3px; pointer-events: none;">
                    <div>
                        <div class="filter-drawer-card-label">Semua Layanan</div>
                        <div class="filter-drawer-card-sub">Tampilkan seluruh paket bundling dan layanan satuan</div>
                    </div>
                </div>
                <div class="filter-drawer-card {{ in_array($activeCategory, ['bundling', 'paket-bundling']) ? 'is-active' : '' }}" onclick="selectCategory('paket-bundling')">
                    <input type="radio" name="drawer_kategori" {{ in_array($activeCategory, ['bundling', 'paket-bundling']) ? 'checked' : '' }} style="accent-color: var(--primary); margin-top: 3px; pointer-events: none;">
                    <div>
                        <div class="filter-drawer-card-label">Paket Bundling MUA & Dekorasi</div>
                        <div class="filter-drawer-card-sub">Paket pernikahan lengkap dengan evaluasi SPK MARCOS</div>
                    </div>
                </div>
                @foreach($categories as $cat)
                    @if(!in_array($cat->slug, ['bundling', 'paket-bundling']))
                        <div class="filter-drawer-card {{ $activeCategory === $cat->slug ? 'is-active' : '' }}" onclick="selectCategory('{{ $cat->slug }}')">
                            <input type="radio" name="drawer_kategori" {{ $activeCategory === $cat->slug ? 'checked' : '' }} style="accent-color: var(--primary); margin-top: 3px; pointer-events: none;">
                            <div>
                                <div class="filter-drawer-card-label">{{ $cat->name }}</div>
                                <div class="filter-drawer-card-sub">Katalog spesifik kategori {{ $cat->name }}</div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- 2. Prioritas Kebutuhan MARCOS (Bisa Pilih > 1) -->
        <div class="filter-drawer-section">
            <div class="filter-drawer-section-title">
                <span>Prioritas Kebutuhan MARCOS (Bisa Pilih > 1)</span>
                @if(count($preferences) > 0)
                    <span class="badge badge-moss" style="font-size: 10px;">{{ count($preferences) }} Dipilih</span>
                @endif
            </div>
            <div style="font-size: 12px; color: var(--body); margin-bottom: 12px; line-height: 1.5;">
                Centang satu atau beberapa prioritas. Hasil rekomendasi paket langsung dikalkulasi ulang secara otomatis:
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                @foreach($allPrefOptions as $key => $opt)
                    @php $isCardChecked = in_array($key, $preferences); @endphp
                    <div class="filter-drawer-card {{ $isCardChecked ? 'is-active' : '' }}" onclick="togglePriorityFromDrawer('{{ $key }}', event)">
                        <input type="checkbox" id="drawer_chk_{{ $key }}" {{ $isCardChecked ? 'checked' : '' }} style="pointer-events: none;">
                        <div style="flex: 1;">
                            <div class="filter-drawer-card-label">{{ $opt['label'] }}</div>
                            <div class="filter-drawer-card-sub">{{ $opt['sub'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Footer Drawer -->
    <div class="filter-drawer-footer">
        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" style="gap: 6px;">
            <i data-lucide="rotate-ccw" style="width: 13px; height: 13px;"></i>
            Reset Filter
        </a>
        <button type="button" class="btn btn-primary btn-sm" onclick="closeFilterDrawer()">
            Lihat Hasil Rekomendasi &rarr;
        </button>
    </div>
</div>

@push('scripts')
<script>
    function openFilterDrawer() {
        document.getElementById('filterDrawerBackdrop')?.classList.add('active');
        document.getElementById('filterDrawerPanel')?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeFilterDrawer() {
        document.getElementById('filterDrawerBackdrop')?.classList.remove('active');
        document.getElementById('filterDrawerPanel')?.classList.remove('active');
        document.body.style.overflow = '';
        sessionStorage.removeItem('openFilterDrawerAfterReload');
    }

    function selectCategory(slug) {
        const catInput = document.getElementById('hiddenCategoryInput');
        if (catInput) {
            catInput.value = slug;
        }
        submitHomeFilter();
    }

    function togglePriority(val) {
        const chk = document.getElementById('pref_chk_' + val);
        if (chk) {
            chk.checked = !chk.checked;
        }
        submitHomeFilter();
    }

    function togglePriorityFromDrawer(val, event) {
        if (event) event.preventDefault();
        const chk = document.getElementById('pref_chk_' + val);
        if (chk) {
            chk.checked = !chk.checked;
        }
        sessionStorage.setItem('openFilterDrawerAfterReload', '1');
        submitHomeFilter();
    }

    function submitHomeFilter() {
        const form = document.getElementById('homeFilterForm');
        if (form) {
            form.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (sessionStorage.getItem('openFilterDrawerAfterReload') === '1') {
            openFilterDrawer();
        }
        if (window.lucide) {
            window.lucide.createIcons();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeFilterDrawer();
        }
    });

    function filterServicesByCategory(slug) {
        const cards = document.querySelectorAll('.service-item-card');
        cards.forEach(card => {
            if (slug === 'all' || card.getAttribute('data-category') === slug) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endpush
@endsection
