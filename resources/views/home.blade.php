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

        <!-- Filter Bar Kabut Terpadu (Dropdown Rapi & Terorganisir) -->
        <div class="filter-bar-kabut" style="text-align: left; margin-bottom: 16px;">
            <form action="{{ route('home') }}" method="GET" style="display: contents;">
                <!-- 1. Input Pencarian -->
                <div class="filter-item" style="flex: 1.5; min-width: 200px;">
                    <label for="filter-search">Pencarian Kata Kunci</label>
                    <input type="text" id="filter-search" name="search" value="{{ request('search') }}" 
                           placeholder="Cari paket rias, dekorasi, atau nama vendor..." 
                           class="form-input">
                </div>

                <!-- 2. Dropdown Kategori -->
                <div class="filter-item">
                    <label for="filter-kategori">Pilihan Kategori Layanan</label>
                    <select id="filter-kategori" name="kategori" class="form-select">
                        <option value="all" {{ $activeCategory === 'all' ? 'selected' : '' }}>Semua Kategori Layanan</option>
                        <option value="bundling" {{ $activeCategory === 'bundling' ? 'selected' : '' }}>Paket Bundling Utama (MUA + Dekorasi)</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ $activeCategory === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Multi-Select Prioritas Rekomendasi MARCOS -->
                <div class="filter-item" style="position: relative; flex: 1.6; min-width: 240px;">
                    <label>Prioritas Kebutuhan (Bisa Pilih > 1)</label>
                    <div id="prefDropdownTrigger" class="form-select" style="display: flex; align-items: center; justify-content: space-between; cursor: pointer; user-select: none; background: #fff;" onclick="togglePrefDropdown(event)">
                        <span id="prefDropdownLabel" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 13px;">
                            @if(count($preferences) > 0)
                                <strong style="color: var(--emerald-deep);">{{ count($preferences) }} Prioritas Dipilih</strong>
                            @else
                                Pilih Prioritas (Bisa > 1)
                            @endif
                        </span>
                        <i data-lucide="chevron-down" style="width: 14px; height: 14px; flex-shrink: 0; color: var(--muted);"></i>
                    </div>

                    <!-- Dropdown Options Panel -->
                    <div id="prefDropdownMenu" style="display: none; position: absolute; top: calc(100% + 6px); left: 0; right: 0; min-width: 290px; background: #FFFFFF; border: 1px solid var(--border-gold-medium); border-radius: 12px; box-shadow: var(--shadow-tier2); padding: 12px 14px; z-index: 100; max-height: 310px; overflow-y: auto;">
                        <div style="font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.04em;">
                            Centang 1, 2, 3 atau lebih prioritas:
                        </div>
                        @php
                            $allPrefOptions = [
                                'low_price' => ['label' => 'Harga Paling Terjangkau', 'sub' => 'Prioritas Efisiensi Anggaran (C5)'],
                                'kualitas_flawless' => ['label' => 'Kualitas Riasan Flawless', 'sub' => 'Tahan Lama & Hasil Terbaik (C1)'],
                                'estetik_modern' => ['label' => 'Konsep Estetik & Modern', 'sub' => 'Desain Pelaminan Mewah (C2)'],
                                'paket_lengkap' => ['label' => 'Kelengkapan Paket All-In', 'sub' => 'Fasilitas Terlengkap (C3)'],
                                'rating_ulasan' => ['label' => 'Rating & Ulasan Terbaik', 'sub' => 'Kepuasan Pengantin Tertinggi (C4)'],
                                'keamanan_risiko' => ['label' => 'Keamanan & Minim Risiko', 'sub' => 'Garansi Busana & Fasilitas (C6)'],
                                'terdekat' => ['label' => 'Lokasi Terdekat / Respon Cepat', 'sub' => 'Efisiensi Transportasi (C7)'],
                                'tradisional_adat' => ['label' => 'Busana Adat & Tradisional', 'sub' => 'Nuansa Budaya Nusantara (C2 & C3)'],
                            ];
                        @endphp
                        @foreach($allPrefOptions as $key => $opt)
                            <label style="display: flex; align-items: flex-start; gap: 10px; padding: 7px 6px; border-radius: 6px; cursor: pointer; transition: background 0.15s ease;" onmouseover="this.style.background='var(--surface-container)'" onmouseout="this.style.background='transparent'">
                                <input type="checkbox" name="preferences[]" value="{{ $key }}" {{ in_array($key, $preferences) ? 'checked' : '' }} onchange="updatePrefLabel()" style="margin-top: 3px; accent-color: var(--primary);">
                                <div style="flex: 1;">
                                    <div style="font-size: 12.5px; font-weight: 600; color: var(--ink);">{{ $opt['label'] }}</div>
                                    <div style="font-size: 11px; color: var(--muted);">{{ $opt['sub'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 4. Tombol Aksi -->
                <div style="display: flex; gap: 8px; align-items: flex-end; padding-top: 22px;">
                    <button type="submit" class="btn btn-primary" style="height: 42px;">
                        <i data-lucide="filter" style="width: 15px; height: 15px;"></i>
                        Terapkan
                    </button>
                    @if(count($preferences) > 0 || $activeCategory !== 'all' || request('search'))
                        <a href="{{ route('home') }}" class="btn btn-secondary" style="height: 42px;" title="Reset Filter">
                            <i data-lucide="rotate-ccw" style="width: 14px; height: 14px;"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if(count($preferences) > 0)
            <div style="display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.85rem; color: var(--muted); flex-wrap: wrap;">
                <span class="badge badge-moss">{{ count($preferences) }} Prioritas Aktif</span>
                <span>Kombinasi bobot MARCOS disesuaikan berdasarkan:</span>
                @foreach($preferences as $p)
                    <span class="badge badge-outline" style="font-size: 11px;">
                        {{ $allPrefOptions[$p]['label'] ?? ucfirst($p) }}
                    </span>
                @endforeach
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

<!-- Section 3: Cerita & Nilai Kurasi (Editorial Kabut) -->
<section class="section-paper">
    <div class="container" style="max-width: 900px; text-align: center;">
        <span class="caption" style="text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; color: var(--moss); display: block; margin-bottom: 8px;">
            Komitmen Layanan Glow & Grace
        </span>
        <h2 class="headline-md" style="margin-bottom: 18px; font-size: 1.8rem;">
            Kepastian Mutu Melalui Evaluasi Matematis yang Transparan
        </h2>
        <p class="body-md" style="line-height: 1.7; margin-bottom: 36px; color: var(--body);">
            Kami memahami bahwa pernikahan adalah momen sakral sekali seumur hidup. Karena itu, setiap rekomendasi di platform kami tidak ditentukan oleh siapa yang membayar iklan, melainkan hasil komputasi multi-kriteria MARCOS yang mempertimbangkan kualitas riasan, keandalan portofolio, kepuasan pelanggan terdahulu, serta kepatutan harga secara seimbang.
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; text-align: left;">
            <div class="card-kabut" style="background: var(--cream);">
                <div style="width: 32px; height: 32px; border-radius: var(--radius-sm); background: var(--moss); color: var(--cream); display: flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                    <i data-lucide="shield-check" style="width: 18px; height: 18px;"></i>
                </div>
                <h4 style="font-family: var(--font-heading); font-size: 1.05rem; margin-bottom: 6px; color: var(--ink);">Vendor Terverifikasi</h4>
                <p class="body-sm">Seluruh mitra MUA dan dekorator telah melalui verifikasi portofolio dan rekam jejak acara riil.</p>
            </div>

            <div class="card-kabut" style="background: var(--cream);">
                <div style="width: 32px; height: 32px; border-radius: var(--radius-sm); background: var(--rust); color: var(--cream); display: flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                    <i data-lucide="credit-card" style="width: 18px; height: 18px;"></i>
                </div>
                <h4 style="font-family: var(--font-heading); font-size: 1.05rem; margin-bottom: 6px; color: var(--ink);">Pembayaran Aman Midtrans</h4>
                <p class="body-sm">Transaksi terproteksi dengan opsi Down Payment (DP 30%/50%) serta pelunasan fleksibel.</p>
            </div>

            <div class="card-kabut" style="background: var(--cream);">
                <div style="width: 32px; height: 32px; border-radius: var(--radius-sm); background: var(--ink); color: var(--cream); display: flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                    <i data-lucide="sliders" style="width: 18px; height: 18px;"></i>
                </div>
                <h4 style="font-family: var(--font-heading); font-size: 1.05rem; margin-bottom: 6px; color: var(--ink);">Preferensi Personal</h4>
                <p class="body-sm">Sistem MARCOS menyesuaikan bobot kriteria secara real-time mengikuti kebutuhan spesifik Anda.</p>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function togglePrefDropdown(e) {
        if (e) e.stopPropagation();
        const menu = document.getElementById('prefDropdownMenu');
        if (menu) {
            menu.style.display = menu.style.display === 'none' || menu.style.display === '' ? 'block' : 'none';
        }
    }

    function updatePrefLabel() {
        const checkboxes = document.querySelectorAll('input[name="preferences[]"]:checked');
        const label = document.getElementById('prefDropdownLabel');
        if (checkboxes.length === 0) {
            label.innerHTML = 'Pilih Prioritas (Bisa > 1)';
        } else {
            label.innerHTML = '<strong style="color: var(--emerald-deep);">' + checkboxes.length + ' Prioritas Dipilih</strong>';
        }
    }

    document.addEventListener('click', function(e) {
        const menu = document.getElementById('prefDropdownMenu');
        const trigger = document.getElementById('prefDropdownTrigger');
        if (menu && menu.style.display === 'block') {
            if (!menu.contains(e.target) && !trigger.contains(e.target)) {
                menu.style.display = 'none';
            }
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
