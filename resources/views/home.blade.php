@extends('layouts.app')

@section('title', 'Glow & Grace - Marketplace Make-Up & Dekorasi Pernikahan')

@section('content')
@php
    $currentPref = is_array($preferences) ? ($preferences[0] ?? '') : $preferences;
    $activeCategories = (array) ($activeCategories ?? ['all']);
    if (empty($activeCategories) || in_array('all', $activeCategories)) {
        $activeCategories = ['all'];
    }
    $selectedCatCount = in_array('all', $activeCategories) ? 0 : count($activeCategories);
    $selectedPrefCount = count($preferences);

    $allPrefOptions = [
        'low_price' => ['label' => 'Harga Terjangkau', 'sub' => 'Efisiensi Anggaran & Biaya Terjangkau', 'short' => '🏷️ Harga Terjangkau'],
        'biaya_tambahan' => ['label' => 'Bebas Biaya Tambahan', 'sub' => 'Estimasi Biaya Transport / Overtime Minim', 'short' => '💰 Bebas Biaya Tambahan'],
        'paket_lengkap' => ['label' => 'Kelengkapan Fasilitas', 'sub' => 'Fasilitas Terlengkap & All-In', 'short' => '📦 Kelengkapan'],
        'fleksibilitas' => ['label' => 'Fleksibilitas Layanan', 'sub' => 'Penyesuaian Jadwal & Busana Fleksibel', 'short' => '🤝 Fleksibilitas Layanan'],
        'kualitas_flawless' => ['label' => 'Kualitas Hasil Flawless', 'sub' => 'Riasan Halus & Tahan Lama', 'short' => '✨ Kualitas Hasil'],
        'keamanan_risiko' => ['label' => 'Minim Risiko Kerusakan', 'sub' => 'Garansi Busana & Keamanan Fasilitas', 'short' => '🛡️ Minim Risiko'],
        'estetik_modern' => ['label' => 'Estetika & Desain Modern', 'sub' => 'Dekorasi & Konsep Pelaminan Elegan', 'short' => '🏛️ Estetika & Desain'],
        'tradisional_adat' => ['label' => 'Busana Adat & Tradisional', 'sub' => 'Nuansa Budaya Nusantara', 'short' => '👑 Busana Adat'],
    ];
@endphp

<!-- Hero Section (Editorial Gaya Kabut - Hangat & Tenang) -->
<section style="padding: 64px 0 48px; text-align: center; border-bottom: 1px solid var(--line);">
    <div class="container" style="max-width: 860px;">
        <div style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 18px;">
            <span class="badge badge-moss">E-Marketplace Jasa Pernikahan</span>
            <span class="badge badge-outline">Koleksi Terkurasi</span>
        </div>

        <h1 class="headline-display" style="margin-bottom: 32px; font-weight: 500;">
            Temukan Harmoni Riasan & Dekorasi untuk Hari Bahagia Anda
        </h1>

        <!-- Filter Bar Terpadu: Search + Tombol Trigger (Kategori & Prioritas) + Horizontal Drag-to-Scroll Rails -->
        <div class="filter-hybrid-container">
            <form id="homeFilterForm" action="{{ route('home') }}" method="GET" onsubmit="event.preventDefault(); submitHomeFilter();">
                <!-- Hidden inputs for active categories (Multi-Select) -->
                <div id="categoryHiddenInputsContainer">
                    @foreach($activeCategories as $cSlug)
                        <input type="hidden" name="categories[]" value="{{ $cSlug }}">
                    @endforeach
                </div>

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
                        @if($selectedPrefCount > 0 || $selectedCatCount > 0)
                            <span class="filter-badge-counter">{{ $selectedPrefCount + $selectedCatCount }}</span>
                        @endif
                    </button>
                </div>

                @php
                    // Pastikan secara bawaan hanya salah satu panel yang terbuka (eksklusif)
                    $panelToShow = null;
                    if ($selectedCatCount > 0) {
                        $panelToShow = 'category';
                    } elseif ($selectedPrefCount > 0) {
                        $panelToShow = 'priority';
                    }
                @endphp

                <!-- 2. Toolbar Tombol Pemicu Kategori & Prioritas -->
                <div class="filter-triggers-bar">
                    <!-- Tombol Trigger Kategori -->
                    <button type="button" 
                            id="btnToggleCategory" 
                            class="filter-trigger-btn {{ $selectedCatCount > 0 ? 'has-active' : '' }} {{ $panelToShow === 'category' ? 'is-open' : '' }}" 
                            onclick="toggleFilterPanel('category')">
                        <i data-lucide="layout-grid" style="width: 14px; height: 14px;"></i>
                        <span>Kategori</span>
                        <span id="badgeCategoryCount" class="trigger-badge" style="{{ $selectedCatCount > 0 ? '' : 'display:none;' }}">
                            {{ $selectedCatCount }}
                        </span>
                        <i data-lucide="chevron-down" id="chevronCategory" class="trigger-chevron" style="width: 13px; height: 13px;"></i>
                    </button>

                    <!-- Tombol Trigger Prioritas Kebutuhan -->
                    <button type="button" 
                            id="btnTogglePriority" 
                            class="filter-trigger-btn {{ $selectedPrefCount > 0 ? 'has-active' : '' }} {{ $panelToShow === 'priority' ? 'is-open' : '' }}" 
                            onclick="toggleFilterPanel('priority')">
                        <i data-lucide="sparkles" style="width: 14px; height: 14px;"></i>
                        <span>Prioritas Kebutuhan</span>
                        <span id="badgePriorityCount" class="trigger-badge" style="{{ $selectedPrefCount > 0 ? '' : 'display:none;' }}">
                            {{ $selectedPrefCount }}
                        </span>
                        <i data-lucide="chevron-down" id="chevronPriority" class="trigger-chevron" style="width: 13px; height: 13px;"></i>
                    </button>

                    <!-- Filter Wilayah Acara -->
                    <div class="toolbar-location-wrapper">
                        <i data-lucide="map-pin" style="width: 13px; height: 13px; color: #C5A059;"></i>
                        <select name="location" id="homeLocationSelect" class="toolbar-location-select" onchange="submitHomeFilter()">
                            <option value="">🌐 Semua Wilayah</option>
                            <option value="Tanjung Morawa" {{ ($location ?? '') === 'Tanjung Morawa' ? 'selected' : '' }}>📍 Tanjung Morawa</option>
                            <option value="Batang Kuis" {{ ($location ?? '') === 'Batang Kuis' ? 'selected' : '' }}>📍 Batang Kuis</option>
                            <option value="Deli Serdang" {{ ($location ?? '') === 'Deli Serdang' ? 'selected' : '' }}>📍 Deli Serdang</option>
                            <option value="Medan" {{ ($location ?? '') === 'Medan' ? 'selected' : '' }}>📍 Medan</option>
                            <option value="Kisaran" {{ ($location ?? '') === 'Kisaran' ? 'selected' : '' }}>📍 Kisaran</option>
                            <option value="Lubuk Pakam" {{ ($location ?? '') === 'Lubuk Pakam' ? 'selected' : '' }}>📍 Lubuk Pakam</option>
                            <option value="Binjai" {{ ($location ?? '') === 'Binjai' ? 'selected' : '' }}>📍 Binjai</option>
                            <option value="Tebing Tinggi" {{ ($location ?? '') === 'Tebing Tinggi' ? 'selected' : '' }}>📍 Tebing Tinggi</option>
                        </select>
                    </div>

                    <button type="button" id="btnResetFilter" class="toolbar-reset-btn" onclick="resetHomeFilter(event)" title="Reset Semua Filter" style="{{ ($selectedPrefCount > 0 || $selectedCatCount > 0 || request('search') || request('location')) ? '' : 'display:none;' }}">
                        <i data-lucide="rotate-ccw" style="width: 12px; height: 12px;"></i>
                        <span>Reset</span>
                    </button>
                </div>

                <!-- 3. Panel Horizontal Scrollable Kategori (Bisa digeser dengan kursor & multi-select) -->
                <div id="panelCategoryScrollable" class="filter-collapsible-panel" style="{{ $panelToShow === 'category' ? 'display: block;' : 'display: none;' }}">
                    <div class="drag-scroll-hint">
                        <i data-lucide="move-horizontal" style="width: 12px; height: 12px;"></i>
                        <span>Geser dengan kursor &bull; Bisa memilih lebih dari 1 kategori</span>
                    </div>
                    <div id="categoryScrollTrack" class="horizontal-drag-track" data-drag-scroll>
                        <button type="button" 
                                class="filter-chip-item {{ in_array('all', $activeCategories) ? 'is-active' : '' }}" 
                                onclick="toggleCategoryItem('all')">
                            <i data-lucide="sparkles" style="width: 13px; height: 13px;"></i>
                            <span>Semua Layanan</span>
                        </button>
                        <button type="button" 
                                class="filter-chip-item {{ in_array('paket-bundling', $activeCategories) ? 'is-active' : '' }}" 
                                onclick="toggleCategoryItem('paket-bundling')">
                            <i data-lucide="gem" style="width: 13px; height: 13px;"></i>
                            <span>Paket Bundling (MUA & Dekor)</span>
                            @if(in_array('paket-bundling', $activeCategories))
                                <span class="chip-check">✓</span>
                            @endif
                        </button>
                        @foreach($categories as $cat)
                            @if(!in_array($cat->slug, ['bundling', 'paket-bundling']))
                                @php
                                    $isCatActive = in_array($cat->slug, $activeCategories);
                                    $catIcon = match($cat->slug) {
                                        'make-up-only' => 'sparkles',
                                        'dekorasi-only' => 'flower-2',
                                        'dekorasi-akad' => 'landmark',
                                        'gaun-busana' => 'shirt',
                                        'baju-adat' => 'crown',
                                        default => 'tag',
                                    };
                                @endphp
                                <button type="button" 
                                        class="filter-chip-item {{ $isCatActive ? 'is-active' : '' }}" 
                                        onclick="toggleCategoryItem('{{ $cat->slug }}')">
                                    <i data-lucide="{{ $catIcon }}" style="width: 13px; height: 13px;"></i>
                                    <span>{{ $cat->name }}</span>
                                    @if($isCatActive)
                                        <span class="chip-check">✓</span>
                                    @endif
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- 4. Panel Horizontal Scrollable Prioritas Kebutuhan (Bisa digeser dengan kursor & multi-select) -->
                <div id="panelPriorityScrollable" class="filter-collapsible-panel" style="{{ $panelToShow === 'priority' ? 'display: block;' : 'display: none;' }}">
                    <div class="drag-scroll-hint">
                        <i data-lucide="move-horizontal" style="width: 12px; height: 12px;"></i>
                        <span>Geser dengan kursor &bull; Bisa memilih lebih dari 1 prioritas</span>
                    </div>
                    <div id="priorityScrollTrack" class="horizontal-drag-track" data-drag-scroll>
                        @foreach($allPrefOptions as $key => $opt)
                            @php $isSelected = in_array($key, $preferences); @endphp
                            <input type="checkbox" name="preferences[]" id="pref_chk_{{ $key }}" value="{{ $key }}" {{ $isSelected ? 'checked' : '' }} style="display: none;">
                            <button type="button" 
                                    class="filter-chip-item {{ $isSelected ? 'is-active' : '' }}" 
                                    onclick="togglePriorityItem('{{ $key }}')">
                                <span>{{ $opt['short'] }}</span>
                                @if($isSelected)
                                    <span class="chip-check">✓</span>
                                @else
                                    <span class="chip-plus">+</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

        <div id="filterActiveBadgesWrapper" style="{{ ($selectedPrefCount > 0 || $selectedCatCount > 0) ? 'display: flex;' : 'display: none;' }} align-items: center; justify-content: center; gap: 8px; font-size: 0.85rem; color: var(--muted); flex-wrap: wrap; margin-top: 14px;">
            <span id="badgeInfoCategory" class="badge badge-moss" style="{{ $selectedCatCount > 0 ? '' : 'display: none;' }}">{{ $selectedCatCount }} Kategori Dipilih</span>
            <span id="badgeInfoPriority" class="badge badge-amber" style="{{ $selectedPrefCount > 0 ? '' : 'display: none;' }}">{{ $selectedPrefCount }} Prioritas Aktif</span>
        </div>
    </div>
</section>

<!-- Section 1: Rekomendasi Terpilih (Narasi Formal Tanpa Angka Menumpuk) -->
<section class="section-paper">
    <div class="container-wide">
        <!-- Section Header with Editorial Alignment -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="caption" style="text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; color: var(--moss); display: block; margin-bottom: 6px;">
                    Kurasi Pilihan Terbaik
                </span>
                <h2 class="headline-md" style="margin-bottom: 4px;">Paket Rekomendasi Unggulan untuk Anda</h2>
                <p class="body-sm">
                    Daftar paket bundling pernikahan yang paling direkomendasikan dan sesuai dengan preferensi Anda.
                </p>
            </div>

            <!-- Tautan Semua Paket -->
            <div>
                <a href="{{ route('bundlings') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                    <span>Lihat Semua Paket Bundling</span>
                    <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                </a>
            </div>
        </div>

        <!-- Grid Kartu Rekomendasi (Narasi Peringkat Formal E-Commerce) -->
        <div class="grid-cards" id="recommendationsContainer" style="transition: opacity 0.25s ease;">
            @include('partials.home-recommendations')
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
        <div class="grid-cards" id="servicesContainer" style="transition: opacity 0.25s ease;">
            @include('partials.home-services')
        </div>
    </div>
</section>


<!-- Collapsible Sidebar Filter Drawer (Panel Samping Offcanvas - Mewah & Bersih) -->
<div id="filterDrawerBackdrop" class="filter-drawer-backdrop" onclick="closeFilterDrawer()"></div>
<div id="filterDrawerPanel" class="filter-drawer-panel">
    <!-- Header Drawer -->
    <div class="filter-drawer-header">
        <div>
            <h3 class="filter-drawer-title">Filter Kebutuhan Acara</h3>
            <div style="font-size: 11.5px; color: var(--muted); margin-top: 2px;">
                Sesuaikan Kategori dan Kriteria Paket Pernikahan
            </div>
        </div>
        <button type="button" class="filter-drawer-close-btn" onclick="closeFilterDrawer()" title="Tutup Panel">
            <i data-lucide="x" style="width: 18px; height: 18px;"></i>
        </button>
    </div>

    <!-- Body Drawer -->
    <div class="filter-drawer-body">
        <!-- 1. Kategori Layanan (Bisa Pilih > 1) -->
        <div class="filter-drawer-section">
            <div class="filter-drawer-section-title">
                <span>Pilih Kategori Layanan (Bisa &gt; 1)</span>
                @if($selectedCatCount > 0)
                    <span class="badge badge-moss" style="font-size: 10px;">{{ $selectedCatCount }} Dipilih</span>
                @endif
            </div>
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <div class="filter-drawer-card {{ in_array('all', $activeCategories) ? 'is-active' : '' }}" onclick="toggleCategoryItem('all')">
                    <input type="checkbox" {{ in_array('all', $activeCategories) ? 'checked' : '' }} style="accent-color: var(--primary); margin-top: 3px; pointer-events: none;">
                    <div>
                        <div class="filter-drawer-card-label">Semua Layanan</div>
                        <div class="filter-drawer-card-sub">Tampilkan seluruh pilihan paket dan layanan satuan</div>
                    </div>
                </div>
                <div class="filter-drawer-card {{ in_array('paket-bundling', $activeCategories) ? 'is-active' : '' }}" onclick="toggleCategoryItem('paket-bundling')">
                    <input type="checkbox" {{ in_array('paket-bundling', $activeCategories) ? 'checked' : '' }} style="accent-color: var(--primary); margin-top: 3px; pointer-events: none;">
                    <div>
                        <div class="filter-drawer-card-label">Paket Bundling MUA & Dekorasi</div>
                        <div class="filter-drawer-card-sub">Paket pernikahan terpadu dengan rekomendasi terbaik</div>
                    </div>
                </div>
                @foreach($categories as $cat)
                    @if(!in_array($cat->slug, ['bundling', 'paket-bundling']))
                        @php $isCatSelected = in_array($cat->slug, $activeCategories); @endphp
                        <div class="filter-drawer-card {{ $isCatSelected ? 'is-active' : '' }}" onclick="toggleCategoryItem('{{ $cat->slug }}')">
                            <input type="checkbox" {{ $isCatSelected ? 'checked' : '' }} style="accent-color: var(--primary); margin-top: 3px; pointer-events: none;">
                            <div>
                                <div class="filter-drawer-card-label">{{ $cat->name }}</div>
                                <div class="filter-drawer-card-sub">Katalog spesifik kategori {{ $cat->name }}</div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- 2. Prioritas Kebutuhan (Bisa Pilih > 1) -->
        <div class="filter-drawer-section">
            <div class="filter-drawer-section-title">
                <span>Fokus Kebutuhan Acara (Bisa Pilih > 1)</span>
                @if(count($preferences) > 0)
                    <span class="badge badge-moss" style="font-size: 10px;">{{ count($preferences) }} Dipilih</span>
                @endif
            </div>
            <div style="font-size: 12px; color: var(--body); margin-bottom: 12px; line-height: 1.5;">
                Pilih satu atau beberapa preferensi untuk memprioritaskan rekomendasi paket yang paling sesuai:
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
    // State panel toggle - ACCORDION EKSKLUSIF (Hanya salah satu yang boleh terbuka)
    function toggleFilterPanel(panelName) {
        const isCat = panelName === 'category';
        const targetPanel = document.getElementById(isCat ? 'panelCategoryScrollable' : 'panelPriorityScrollable');
        const targetBtn = document.getElementById(isCat ? 'btnToggleCategory' : 'btnTogglePriority');
        
        const otherPanel = document.getElementById(isCat ? 'panelPriorityScrollable' : 'panelCategoryScrollable');
        const otherBtn = document.getElementById(isCat ? 'btnTogglePriority' : 'btnToggleCategory');

        if (!targetPanel || !targetBtn) return;

        // Tutup panel lain agar tidak bisa terbuka bersamaan
        if (otherPanel) {
            otherPanel.style.display = 'none';
        }
        if (otherBtn) {
            otherBtn.classList.remove('is-open');
        }

        const isCurrentlyOpen = targetPanel.style.display !== 'none';
        if (isCurrentlyOpen) {
            // Jika sedang terbuka, maka ditutup
            targetPanel.style.display = 'none';
            targetBtn.classList.remove('is-open');
            sessionStorage.setItem('active_filter_panel', 'none');
        } else {
            // Jika sedang tertutup, buka panel ini dan pastikan panel lain tertutup
            targetPanel.style.display = 'block';
            targetBtn.classList.add('is-open');
            sessionStorage.setItem('active_filter_panel', panelName);
            
            // Re-init horizontal scroll layout when shown
            const track = targetPanel.querySelector('[data-drag-scroll]');
            if (track) {
                track.scrollLeft = track.scrollLeft;
            }
        }
    }

    // Global AbortController untuk request AJAX real-time
    let homeFilterAbortCtrl = null;

    // Helper untuk update UI chip kategori secara instan (Optimistic UI)
    function updateCategoryChipsUI(activeCats) {
        const track = document.getElementById('categoryScrollTrack');
        if (!track) return;
        const buttons = track.querySelectorAll('.filter-chip-item');
        buttons.forEach(btn => {
            const onclickAttr = btn.getAttribute('onclick') || '';
            const match = onclickAttr.match(/toggleCategoryItem\('([^']+)'\)/);
            if (!match) return;
            const btnSlug = match[1];

            const isActive = (btnSlug === 'all') ? activeCats.includes('all') : activeCats.includes(btnSlug);
            btn.classList.toggle('is-active', isActive);

            let checkSpan = btn.querySelector('.chip-check');
            if (isActive && btnSlug !== 'all') {
                if (!checkSpan) {
                    checkSpan = document.createElement('span');
                    checkSpan.className = 'chip-check';
                    checkSpan.innerText = '✓';
                    btn.appendChild(checkSpan);
                }
            } else if (!isActive && checkSpan) {
                checkSpan.remove();
            }
        });
    }

    // Toggle Multi-Category Selection (REAL TIME TANPA RELOAD)
    function toggleCategoryItem(slug) {
        const container = document.getElementById('categoryHiddenInputsContainer');
        if (!container) return;

        let currentCats = [];
        const inputs = container.querySelectorAll('input[name="categories[]"]');
        inputs.forEach(inp => {
            if (inp.value && !currentCats.includes(inp.value)) {
                currentCats.push(inp.value);
            }
        });

        if (slug === 'all') {
            currentCats = ['all'];
        } else {
            // Remove 'all' if present
            currentCats = currentCats.filter(c => c !== 'all');
            
            // Toggle slug
            const idx = currentCats.indexOf(slug);
            if (idx > -1) {
                currentCats.splice(idx, 1);
            } else {
                currentCats.push(slug);
            }

            // Fallback to 'all' if empty
            if (currentCats.length === 0) {
                currentCats = ['all'];
            }
        }

        // Rebuild hidden inputs
        container.innerHTML = '';
        currentCats.forEach(c => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'categories[]';
            input.value = c;
            container.appendChild(input);
        });

        // Optimistic UI: Update tampilan chip kategori seketika di DOM
        updateCategoryChipsUI(currentCats);

        // Submit AJAX realtime tanpa reload
        submitHomeFilter();
    }

    // Toggle Priority Item (Multi-select) (REAL TIME TANPA RELOAD)
    function togglePriorityItem(val) {
        const chk = document.getElementById('pref_chk_' + val);
        if (!chk) return;
        chk.checked = !chk.checked;

        // Optimistic UI: Update tampilan chip prioritas seketika di DOM
        const track = document.getElementById('priorityScrollTrack');
        if (track) {
            const btn = track.querySelector(`.filter-chip-item[onclick*="'${val}'"]`);
            if (btn) {
                btn.classList.toggle('is-active', chk.checked);
                let badge = btn.querySelector('.chip-check, .chip-plus');
                if (chk.checked) {
                    if (badge) {
                        badge.className = 'chip-check';
                        badge.innerText = '✓';
                    }
                } else {
                    if (badge) {
                        badge.className = 'chip-plus';
                        badge.innerText = '+';
                    }
                }
            }
        }

        // Drawer checkbox sync if present
        const drawerChk = document.getElementById('drawer_pref_chk_' + val);
        if (drawerChk) {
            drawerChk.checked = chk.checked;
        }

        // Submit AJAX realtime tanpa reload
        submitHomeFilter();
    }

    // Drawer helpers
    function openFilterDrawer() {
        document.getElementById('filterDrawerBackdrop')?.classList.add('active');
        document.getElementById('filterDrawerPanel')?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeFilterDrawer() {
        document.getElementById('filterDrawerBackdrop')?.classList.remove('active');
        document.getElementById('filterDrawerPanel')?.classList.remove('active');
        document.body.style.overflow = '';
    }

    function togglePriorityFromDrawer(val, event) {
        if (event) event.preventDefault();
        togglePriorityItem(val);
    }

    // Submit AJAX Real-Time (Tanpa Reload Halaman)
    function submitHomeFilter(pushState = true) {
        const form = document.getElementById('homeFilterForm');
        if (!form) return;

        const recContainer = document.getElementById('recommendationsContainer');
        const srvContainer = document.getElementById('servicesContainer');

        // Loading indicator halus: opacity fade lembut
        if (recContainer) recContainer.style.opacity = '0.35';
        if (srvContainer) srvContainer.style.opacity = '0.35';

        if (homeFilterAbortCtrl) {
            homeFilterAbortCtrl.abort();
        }
        homeFilterAbortCtrl = new AbortController();

        const formData = new FormData(form);
        const searchParams = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (value !== '' && value !== null) {
                searchParams.append(key, value);
            }
        }

        const targetUrl = '{{ route("home") }}' + (searchParams.toString() ? '?' + searchParams.toString() : '');

        fetch(targetUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            signal: homeFilterAbortCtrl.signal
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                if (recContainer && data.recommendations_html !== undefined) {
                    recContainer.innerHTML = data.recommendations_html;
                }
                if (srvContainer && data.services_html !== undefined) {
                    srvContainer.innerHTML = data.services_html;
                }

                // Update badge counter di tombol trigger & info bar
                updateFilterBadges(data.selected_cat_count, data.selected_pref_count);

                // Update URL di browser tanpa reload halaman
                if (pushState && window.history && window.history.replaceState) {
                    window.history.replaceState(null, '', targetUrl);
                }

                // Render icon Lucide yang baru dimuat
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            }
        })
        .catch(err => {
            if (err.name !== 'AbortError') {
                console.error('Filter AJAX error:', err);
            }
        })
        .finally(() => {
            if (recContainer) recContainer.style.opacity = '1';
            if (srvContainer) srvContainer.style.opacity = '1';
        });
    }

    // Update Badges & Reset Button State
    function updateFilterBadges(catCount, prefCount) {
        const btnCat = document.getElementById('btnToggleCategory');
        const badgeCat = document.getElementById('badgeCategoryCount');
        const btnPri = document.getElementById('btnTogglePriority');
        const badgePri = document.getElementById('badgePriorityCount');
        const btnReset = document.getElementById('btnResetFilter');
        const searchInput = document.getElementById('filterSearchInput');
        const locSelect = document.getElementById('homeLocationSelect');

        if (badgeCat && btnCat) {
            badgeCat.innerText = catCount;
            badgeCat.style.display = catCount > 0 ? '' : 'none';
            btnCat.classList.toggle('has-active', catCount > 0);
        }

        if (badgePri && btnPri) {
            badgePri.innerText = prefCount;
            badgePri.style.display = prefCount > 0 ? '' : 'none';
            btnPri.classList.toggle('has-active', prefCount > 0);
        }

        const hasSearch = searchInput && searchInput.value.trim() !== '';
        const hasLoc = locSelect && locSelect.value.trim() !== '';
        const hasAnyFilter = (catCount > 0 || prefCount > 0 || hasSearch || hasLoc);

        if (btnReset) {
            btnReset.style.display = hasAnyFilter ? '' : 'none';
        }

        const infoWrapper = document.getElementById('filterActiveBadgesWrapper');
        const infoCat = document.getElementById('badgeInfoCategory');
        const infoPri = document.getElementById('badgeInfoPriority');

        if (infoWrapper) {
            infoWrapper.style.display = (catCount > 0 || prefCount > 0) ? 'flex' : 'none';
        }
        if (infoCat) {
            infoCat.innerText = catCount + ' Kategori Dipilih';
            infoCat.style.display = catCount > 0 ? '' : 'none';
        }
        if (infoPri) {
            infoPri.innerText = prefCount + ' Prioritas Aktif';
            infoPri.style.display = prefCount > 0 ? '' : 'none';
        }
    }

    // Reset Semua Filter secara Real-time
    function resetHomeFilter(event) {
        if (event) event.preventDefault();

        // 1. Reset categories to 'all'
        const container = document.getElementById('categoryHiddenInputsContainer');
        if (container) {
            container.innerHTML = '<input type="hidden" name="categories[]" value="all">';
        }
        updateCategoryChipsUI(['all']);

        // 2. Reset checkboxes preferences
        const track = document.getElementById('priorityScrollTrack');
        if (track) {
            const chks = track.querySelectorAll('input[type="checkbox"]');
            chks.forEach(c => c.checked = false);
            const btns = track.querySelectorAll('.filter-chip-item');
            btns.forEach(b => {
                b.classList.remove('is-active');
                const badge = b.querySelector('.chip-check, .chip-plus');
                if (badge) {
                    badge.className = 'chip-plus';
                    badge.innerText = '+';
                }
            });
        }

        // 3. Reset search & location
        const sInp = document.getElementById('filterSearchInput');
        if (sInp) sInp.value = '';
        const locSel = document.getElementById('homeLocationSelect');
        if (locSel) locSel.value = '';

        submitHomeFilter();
    }

    // Inisialisasi Mouse Drag-to-Scroll untuk Horizontal Scrollable Containers
    function initMouseDragScroll() {
        const tracks = document.querySelectorAll('[data-drag-scroll]');
        tracks.forEach(track => {
            let isDown = false;
            let startX = 0;
            let scrollLeft = 0;
            let hasDragged = false;

            track.addEventListener('mousedown', (e) => {
                isDown = true;
                hasDragged = false;
                track.classList.add('is-dragging');
                startX = e.pageX - track.offsetLeft;
                scrollLeft = track.scrollLeft;
            });

            track.addEventListener('mouseleave', () => {
                if (isDown) {
                    isDown = false;
                    track.classList.remove('is-dragging');
                }
            });

            track.addEventListener('mouseup', () => {
                if (isDown) {
                    isDown = false;
                    track.classList.remove('is-dragging');
                    setTimeout(() => {
                        hasDragged = false;
                    }, 60);
                }
            });

            track.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - track.offsetLeft;
                const walk = (x - startX) * 1.5; // sensitivitas geser
                if (Math.abs(walk) > 5) {
                    hasDragged = true;
                }
                track.scrollLeft = scrollLeft - walk;
            });

            // Cegah event click pada tombol chip jika pengguna sedang menggeser (drag)
            track.addEventListener('click', (e) => {
                if (hasDragged) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }, true);

            // Opsional: dukung pergeseran halus menggunakan mouse wheel
            track.addEventListener('wheel', (e) => {
                if (e.deltaY !== 0) {
                    e.preventDefault();
                    track.scrollLeft += e.deltaY;
                }
            }, { passive: false });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Init Drag to scroll
        initMouseDragScroll();

        // Restore drawer state if flagged
        if (sessionStorage.getItem('openFilterDrawerAfterReload') === '1') {
            openFilterDrawer();
        }

        // Restore panel open state (Accordion: eksklusif hanya 1 panel terbuka)
        const activePanelName = sessionStorage.getItem('active_filter_panel');
        const catPanel = document.getElementById('panelCategoryScrollable');
        const catBtn = document.getElementById('btnToggleCategory');
        const priPanel = document.getElementById('panelPriorityScrollable');
        const priBtn = document.getElementById('btnTogglePriority');

        if (activePanelName === 'category') {
            if (catPanel && catBtn) {
                catPanel.style.display = 'block';
                catBtn.classList.add('is-open');
            }
            if (priPanel && priBtn) {
                priPanel.style.display = 'none';
                priBtn.classList.remove('is-open');
            }
        } else if (activePanelName === 'priority') {
            if (priPanel && priBtn) {
                priPanel.style.display = 'block';
                priBtn.classList.add('is-open');
            }
            if (catPanel && catBtn) {
                catPanel.style.display = 'none';
                catBtn.classList.remove('is-open');
            }
        } else if (activePanelName === 'none') {
            if (catPanel && catBtn) {
                catPanel.style.display = 'none';
                catBtn.classList.remove('is-open');
            }
            if (priPanel && priBtn) {
                priPanel.style.display = 'none';
                priBtn.classList.remove('is-open');
            }
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
