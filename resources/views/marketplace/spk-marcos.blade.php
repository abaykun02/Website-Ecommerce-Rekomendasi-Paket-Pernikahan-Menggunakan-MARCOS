@extends('layouts.app')

@section('title', 'Hasil Rekomendasi Paket Pernikahan - Glow & Grace')

@section('content')
@php
    $currentPref = is_array($preferences) ? ($preferences[0] ?? '') : $preferences;
@endphp

<div class="container-wide" style="padding-top: 48px; padding-bottom: 72px;">
    <!-- Editorial Header (Gaya Kabut) -->
    <div style="margin-bottom: 36px; max-width: 820px;">
        <span class="badge badge-moss" style="margin-bottom: 10px;">Rekomendasi Paket</span>
        <h1 class="headline-lg" style="margin-bottom: 12px; font-weight: 500;">
            Hasil Rekomendasi Paket Pernikahan Terbaik
        </h1>
        <p class="body-md" style="line-height: 1.65; color: var(--body);">
            Rekomendasi paket rias pengantin dan dekorasi pelaminan disesuaikan secara objektif berdasarkan kebutuhan, jarak lokasi acara, dan preferensi anggaran pernikahan Anda.
        </p>
    </div>

    <!-- Filter Panel Kustomisasi Prioritas Kebutuhan (Formal & Terstruktur) -->
    <div class="card-dribbble" style="margin-bottom: 36px; padding: 24px 28px; border-radius: 14px; background: #FFFFFF; border: 1px solid #E2E8F0; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        <form action="{{ route('spk.rekomendasi') }}" method="GET">
            <!-- Header Panel -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; flex-wrap: wrap; gap: 16px; border-bottom: 1px solid #F1F5F9; padding-bottom: 16px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <i data-lucide="sliders-horizontal" style="width: 18px; height: 18px; color: #C5A059;"></i>
                        <h3 style="font-family: var(--font-heading); font-size: 1.15rem; font-weight: 600; color: #1A2B28; margin: 0;">
                            Kustomisasi Prioritas Kebutuhan Anda
                        </h3>
                        <span class="badge" style="font-size: 11px; background: #F1F5F9; color: #475569; font-weight: 600;">
                            Bisa Pilih &gt; 1 Kriteria
                        </span>
                    </div>
                    <p class="caption" style="margin: 0; color: #64748B; font-size: 12px;">
                        Tentukan faktor yang paling Anda utamakan untuk mendapatkan urutan rekomendasi paket terbaik.
                    </p>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    @if(count($preferences) > 0)
                        <span class="badge badge-success" style="font-size: 11.5px; padding: 6px 12px;">
                            {{ count($preferences) }} Prioritas Aktif
                        </span>
                        <a href="{{ route('spk.rekomendasi') }}" class="btn btn-secondary btn-sm" style="height: 36px; padding: 0 14px; font-size: 12px;" title="Reset ke preferensi default">
                            <i data-lucide="rotate-ccw" style="width: 13px; height: 13px;"></i>
                            <span>Reset</span>
                        </a>
                    @else
                        <span class="badge" style="background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0; font-size: 11.5px; padding: 6px 12px;">
                            Preferensi Standar
                        </span>
                    @endif
                    <button type="submit" class="btn btn-primary btn-sm" style="height: 36px; padding: 0 18px; font-size: 12.5px; font-weight: 600;">
                        <i data-lucide="refresh-cw" style="width: 13px; height: 13px;"></i>
                        <span>Hitung Ulang</span>
                    </button>
                </div>
            </div>

            <!-- Wilayah / Lokasi Acara Filter -->
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px 18px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px; min-width: 260px;">
                        <div style="width: 34px; height: 34px; border-radius: 8px; background: #FFFBEB; border: 1px solid #FDE68A; display: flex; align-items: center; justify-content: center; color: #92400E; flex-shrink: 0;">
                            <i data-lucide="map-pin" style="width: 17px; height: 17px;"></i>
                        </div>
                        <div>
                            <label for="location_select" style="display: block; font-size: 13px; font-weight: 700; color: #1E293B; margin-bottom: 2px;">
                                Lokasi / Wilayah Acara Anda
                            </label>
                            <span class="caption" style="color: #64748B; font-size: 11.5px;">
                                Digunakan untuk menyaring vendor yang beroperasi di sekitar lokasi acara Anda.
                            </span>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; flex: 1; justify-content: flex-end;">
                        <div style="min-width: 250px; max-width: 340px; width: 100%;">
                            @php
                                $selectedLoc = $location ?? request('location', request('wilayah', ''));
                            @endphp
                            <select name="location" id="location_select" class="form-select" style="height: 40px; font-size: 13px; border-radius: 8px; border-color: #CBD5E1; background-color: #FFFFFF; font-weight: 500;">
                                <option value="">🌐 Semua Wilayah (Tanpa Filter Jarak Spesifik)</option>
                                <option value="Tanjung Morawa" {{ $selectedLoc === 'Tanjung Morawa' ? 'selected' : '' }}>📍 Kec. Tanjung Morawa (Kab. Deli Serdang)</option>
                                <option value="Batang Kuis" {{ $selectedLoc === 'Batang Kuis' ? 'selected' : '' }}>📍 Kec. Batang Kuis (Kab. Deli Serdang)</option>
                                <option value="Deli Serdang" {{ $selectedLoc === 'Deli Serdang' ? 'selected' : '' }}>📍 Kab. Deli Serdang (Umum / Sekitarnya)</option>
                                <option value="Medan" {{ $selectedLoc === 'Medan' ? 'selected' : '' }}>📍 Kota Medan</option>
                                <option value="Kisaran" {{ $selectedLoc === 'Kisaran' ? 'selected' : '' }}>📍 Kota Kisaran (Kab. Asahan)</option>
                                <option value="Lubuk Pakam" {{ $selectedLoc === 'Lubuk Pakam' ? 'selected' : '' }}>📍 Kec. Lubuk Pakam (Kab. Deli Serdang)</option>
                                <option value="Binjai" {{ $selectedLoc === 'Binjai' ? 'selected' : '' }}>📍 Kota Binjai</option>
                                <option value="Tebing Tinggi" {{ $selectedLoc === 'Tebing Tinggi' ? 'selected' : '' }}>📍 Kota Tebing Tinggi</option>
                                <option value="Lainnya" {{ $selectedLoc === 'Lainnya' ? 'selected' : '' }}>📍 Wilayah Lainnya / Luar Kota</option>
                            </select>
                        </div>

                        <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: #475569; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="strict_location" value="1" {{ !empty($strictLocation) || request('strict_location') ? 'checked' : '' }} style="accent-color: #C5A059; width: 15px; height: 15px;">
                            <span>Hanya tampilkan vendor wilayah ini</span>
                        </label>
                    </div>
                </div>
            </div>

            @php
                $allPrefOptions = [
                    'low_price' => [
                        'code' => 'C1',
                        'type' => 'Cost',
                        'label' => 'Harga Paling Terjangkau',
                        'sub' => 'Efisiensi anggaran total biaya paket',
                    ],
                    'biaya_tambahan' => [
                        'code' => 'C2',
                        'type' => 'Cost',
                        'label' => 'Minim Biaya Tambahan',
                        'sub' => 'Estimasi biaya transportasi luar area & overtime minim',
                    ],
                    'paket_lengkap' => [
                        'code' => 'C3',
                        'type' => 'Benefit',
                        'label' => 'Kelengkapan Fasilitas All-In',
                        'sub' => 'Fasilitas rias, gaun, tenda, pelaminan & dokumentasi',
                    ],
                    'fleksibilitas' => [
                        'code' => 'C4',
                        'type' => 'Benefit',
                        'label' => 'Fleksibilitas Layanan Tinggi',
                        'sub' => 'Kemudahan penyesuaian jadwal, pergantian busana/adat & konsep acara',
                    ],
                    'kualitas_flawless' => [
                        'code' => 'C5',
                        'type' => 'Benefit',
                        'label' => 'Kualitas Riasan Flawless',
                        'sub' => 'Riasan tahan lama, halus & teruji',
                    ],
                    'keamanan_risiko' => [
                        'code' => 'C6',
                        'type' => 'Cost',
                        'label' => 'Garansi & Bebas Risiko',
                        'sub' => 'Jaminan busana, keamanan properti & kebersihan',
                    ],
                    'estetik_modern' => [
                        'code' => 'C7',
                        'type' => 'Benefit',
                        'label' => 'Estetika & Konsep Modern',
                        'sub' => 'Desain pelaminan mewah, artistik & estetis',
                    ],
                    'tradisional_adat' => [
                        'code' => 'C3 & C7',
                        'type' => 'Benefit',
                        'label' => 'Pakem Busana Adat',
                        'sub' => 'Kelengkapan adat nusantara terstruktur',
                    ],
                ];
            @endphp

            <style>
                .spk-pref-grid {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 12px;
                }
                @media (max-width: 1024px) {
                    .spk-pref-grid {
                        grid-template-columns: repeat(2, 1fr);
                    }
                }
                @media (max-width: 600px) {
                    .spk-pref-grid {
                        grid-template-columns: 1fr;
                    }
                }
                .spk-pref-card {
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    min-height: 94px;
                    padding: 12px 14px;
                    border-radius: 10px;
                    border: 1px solid #E2E8F0;
                    background: #FFFFFF;
                    cursor: pointer;
                    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
                    user-select: none;
                }
                .spk-pref-card:hover {
                    border-color: #CBD5E1;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
                }
                .spk-pref-card.is-active {
                    border-color: #C5A059;
                    background: #FFFDF8;
                    box-shadow: 0 2px 8px rgba(197, 160, 89, 0.12);
                }
            </style>

            <!-- Grid Symmetrical 4-Column Options -->
            <div class="spk-pref-grid">
                @foreach($allPrefOptions as $key => $opt)
                    @php $isChecked = in_array($key, (array)$preferences); @endphp
                    <label class="spk-pref-card {{ $isChecked ? 'is-active' : '' }}" onclick="this.classList.toggle('is-active', this.querySelector('input').checked)">
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                <span style="font-size: 11px; font-weight: 600; color: #475569;">
                                    Aspek Layanan
                                </span>
                                <input type="checkbox" name="preferences[]" value="{{ $key }}" {{ $isChecked ? 'checked' : '' }} 
                                       style="accent-color: #775A19; width: 16px; height: 16px; cursor: pointer;"
                                       onchange="this.closest('.spk-pref-card').classList.toggle('is-active', this.checked)">
                            </div>
                            <div style="font-size: 13px; font-weight: 600; color: #1E293B; line-height: 1.3; margin-bottom: 4px;">
                                {{ $opt['label'] }}
                            </div>
                        </div>
                        <div style="font-size: 11px; color: #64748B; line-height: 1.35;">
                            {{ $opt['sub'] }}
                        </div>
                    </label>
                @endforeach
            </div>

            @if(count($preferences) > 0)
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-top: 16px; padding-top: 14px; border-top: 1px solid #F1F5F9; font-size: 12px; color: #475569;">
                    <span style="font-weight: 600;">Prioritas Terpilih:</span>
                    @foreach($preferences as $p)
                        @if(isset($allPrefOptions[$p]))
                            <span class="badge" style="background: #FFFBEB; color: #92400E; border: 1px solid #FDE68A; font-size: 11px;">
                                {{ $allPrefOptions[$p]['label'] }}
                            </span>
                        @endif
                    @endforeach
                </div>
            @endif
        </form>
    </div>

    <!-- Tampilan Standar Pelanggan: Narasi Formal Peringkat (Tanpa Deretan Angka Rumit) -->
    <div style="margin-bottom: 48px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="caption" style="text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; color: var(--moss); display: block; margin-bottom: 4px;">
                    Daftar Urutan Rekomendasi
                </span>
                <h2 class="headline-md">Peringkat Alternatif Paket Terbaik</h2>
            </div>

            <!-- Tombol Toggle Detail Perhitungan Matematis (!) -->
            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleMathSection()" id="btnToggleMath" style="display: inline-flex; align-items: center; gap: 8px;">
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; background: var(--moss); color: var(--cream); font-size: 11px; font-weight: 700; line-height: 1;">!</span>
                <span id="txtToggleMath">Lihat Rincian Analisis Matematis</span>
            </button>
        </div>

        @php
            $activeLoc = $marcosData['active_location'] ?? ($location ?? null);
        @endphp

        @if(!empty($activeLoc))
            <div style="background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 10px; padding: 12px 18px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="compass" style="width: 18px; height: 18px; color: #059669; flex-shrink: 0;"></i>
                    <div style="font-size: 13px; color: #065F46;">
                        Rekomendasi dioptimalkan untuk Lokasi Acara: <strong>{{ $activeLoc }}</strong>. 
                        Paket telah diselaraskan dengan estimasi jarak vendor ke venue Anda.
                    </div>
                </div>
                <span class="badge badge-success" style="font-size: 11px;">
                    Kedekatan Lokasi Terintegrasi
                </span>
            </div>
        @endif

        <!-- Kartu Narasi Peringkat Formal -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            @forelse($marcosData['rankings'] as $item)
                @php
                    $rank = $item['rank'];
                    $rankTitle = match($rank) {
                        1 => 'Peringkat ke-1 Terbaik (Rekomendasi Utama)',
                        2 => 'Peringkat ke-2 Terbaik dari Seluruh Alternatif',
                        3 => 'Peringkat ke-3 Pilihan Favorit Pelanggan',
                        default => 'Alternatif Rekomendasi Peringkat ke-' . $rank,
                    };
                    $rankNarration = match($rank) {
                        1 => 'Paket ini ditetapkan sebagai rekomendasi paling optimal berdasarkan evaluasi komprehensif pada kualitas riasan, estetika desain dekorasi, kelengkapan fasilitas, dan kepuasan pelanggan yang paling unggul.',
                        2 => 'Paket ini menempati peringkat kedua terbaik dari seluruh alternatif yang dievaluasi, dengan konsistensi mutu yang tinggi serta penawaran layanan yang sangat seimbang.',
                        3 => 'Paket ini merupakan alternatif unggulan ketiga yang banyak diminati pelanggan dengan rekam jejak kepuasan yang tinggi dan kelayakan harga yang bersaing.',
                        default => 'Paket ini direkomendasikan sebagai alternatif pilihan terpercaya dengan keunggulan spesifik yang sesuai untuk konsep penyesuaian khusus.',
                    };
                    $badgeStyle = match($rank) {
                        1 => 'badge-narrative-gold',
                        2 => 'badge-narrative-moss',
                        default => 'badge-narrative-soft',
                    };
                    $vendorRegion = $item['vendor_region'] ?? ($item['vendor']?->region ?? 'Deli Serdang');
                    $cityName = $item['vendor']->city ?? ($item['vendor']->location_address ?? 'Sumatera Utara');
                    $prox = $item['proximity'] ?? null;
                @endphp
                <div class="card-kabut" style="display: flex; flex-direction: row; gap: 28px; align-items: center; flex-wrap: wrap;">
                    <!-- Thumbnail Poster Utuh Tanpa Terpotong -->
                    <div style="width: 190px; height: 260px; flex-shrink: 0; background: #141E1C; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <img src="{{ $item['thumbnail'] }}" alt="{{ $item['package_name'] }}" style="width: 100%; height: 100%; object-fit: contain; display: block;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80';">
                    </div>

                    <!-- Informasi Paket & Narasi Formal -->
                    <div style="flex: 1; min-width: 260px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px; flex-wrap: wrap;">
                            <span class="{{ $badgeStyle }}">
                                {{ $rankTitle }}
                            </span>
                            <span class="caption" style="display: inline-flex; align-items: center; gap: 4px;">
                                <i data-lucide="store" style="width: 13px; height: 13px; color: var(--muted);"></i>
                                {{ $item['vendor_name'] }}
                            </span>
                            <span class="badge" style="background: #F1F5F9; color: #475569; font-size: 11px; border: 1px solid #E2E8F0;">
                                📍 Area: {{ $vendorRegion }}
                            </span>

                            @if($prox)
                                <span class="badge" style="background: {{ $prox['badge_bg'] }}; color: {{ $prox['badge_color'] }}; border: 1px solid {{ $prox['badge_border'] }}; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                    <i data-lucide="map-pin" style="width: 11px; height: 11px;"></i>
                                    {{ $prox['label'] }} ({{ $prox['distance_km'] }})
                                </span>
                            @endif

                            <span class="badge badge-moss" style="font-size: 0.72rem;">Mitra Terverifikasi</span>
                        </div>

                        <h3 style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 500; margin-bottom: 8px;">
                            <a href="{{ route('bundling.detail', $item['bundling_id']) }}">
                                @if(!empty($item['code']))
                                    <span style="font-size: 11px; font-weight: 700; background: #0D0C22; color: #FFFFFF; padding: 3px 8px; border-radius: 4px; margin-right: 6px;">{{ $item['code'] }}</span>
                                @endif
                                {{ $item['package_name'] }}
                            </a>
                        </h3>

                        @if(!empty($item['features']) && count($item['features']) > 0)
                            <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 10px;">
                                @foreach(array_slice($item['features'], 0, 3) as $feat)
                                    <span style="font-size: 11px; background: #F8FAFC; border: 1px solid #E2E8F0; color: #334155; padding: 2px 8px; border-radius: 4px;">
                                        ✓ {{ Str::limit($feat, 34) }}
                                    </span>
                                @endforeach
                                @if(count($item['features']) > 3)
                                    <span style="font-size: 11px; color: #64748B; padding: 2px 4px;">+{{ count($item['features']) - 3 }} fasilitas lainnya</span>
                                @endif
                            </div>
                        @endif

                        <p class="body-sm" style="line-height: 1.6; color: var(--body); margin-bottom: 12px;">
                            {{ $rankNarration }}
                        </p>

                        @if($prox && $prox['is_same_region'])
                            <div style="font-size: 11.5px; color: #166534; font-weight: 600; margin-bottom: 6px; display: flex; align-items: center; gap: 4px;">
                                <i data-lucide="sparkles" style="width: 13px; height: 13px; color: #16A34A;"></i>
                                Satu wilayah dengan acara Anda ({{ $activeLoc }}). Respon cepat & hemat biaya transportasi luar kota!
                            </div>
                        @elseif($prox && $prox['level'] === 'luar_kota')
                            <div style="font-size: 11.5px; color: #991B1B; margin-bottom: 6px; display: flex; align-items: center; gap: 4px;">
                                <i data-lucide="alert-circle" style="width: 13px; height: 13px;"></i>
                                Vendor berdomisili di {{ $vendorRegion }} (~{{ $prox['distance_km'] }}). Mungkin memerlukan koordinasi biaya akomodasi.
                            </div>
                        @endif

                        <div style="font-size: 0.82rem; color: var(--muted);">
                            Wilayah Layanan: <strong>{{ $cityName }}</strong> &bull; Estimasi Harga: <strong style="color: var(--rust); font-size: 1rem;">Rp {{ number_format($item['price'], 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <!-- Aksi Pemesanan -->
                    <div style="display: flex; flex-direction: column; gap: 8px; justify-content: center; align-items: flex-end;">
                        <a href="{{ route('bundling.detail', $item['bundling_id']) }}" class="btn btn-primary" style="padding: 10px 24px;">
                            Pesan Paket Ini
                        </a>
                        <a href="{{ route('bundling.detail', $item['bundling_id']) }}" class="btn-tertiary" style="font-size: 0.82rem;">
                            Lihat Rincian Fasilitas &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 64px 24px; background: #FFFFFF; border-radius: 16px; border: 1px dashed #CBD5E1; margin-bottom: 24px;">
                    <i data-lucide="package-search" style="width: 48px; height: 48px; color: #94A3B8; margin: 0 auto 16px;"></i>
                    <h3 style="font-family: var(--font-heading); font-size: 1.35rem; color: #1E293B; margin-bottom: 8px; font-weight: 600;">
                        Paket yang direkomendasikan belum ada
                    </h3>
                    <p class="body-md" style="color: #64748B; max-width: 520px; margin: 0 auto 20px;">
                        Evaluasi skor alternatif belum diisi atau sedang dikosongkan oleh administrator.
                    </p>
                    @if(auth()->check() && auth()->user()->isAdmin())
                        <a href="{{ route('admin.evaluations') }}" class="btn btn-primary btn-sm">
                            Buka Evaluasi Skor Alternatif
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    @if(auth()->check() && auth()->user()->isAdmin())
    <!-- SECTION MATEMATIS MARCOS (HANYA UNTUK ADMINISTRATOR / AUDIT AKADEMIK) -->
    <div id="spkMathSection" style="padding-top: 36px; border-top: 2px dashed var(--line); margin-top: 24px;">
        <div style="background: var(--paper); border: 1px solid var(--line); border-radius: var(--radius-lg); padding: 28px; margin-bottom: 36px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; background: var(--moss); color: var(--cream); font-size: 13px; font-weight: 700;">!</span>
                <h3 style="font-family: var(--font-heading); font-size: 1.3rem; color: var(--ink);">
                    Dokumentasi Transparansi Perhitungan Matematis (SPK MARCOS)
                </h3>
            </div>
            <p class="body-sm" style="line-height: 1.6; margin-bottom: 24px;">
                Bagian ini menyajikan pembuktian komputasi langkah demi langkah sesuai metodologi MARCOS baku untuk keperluan evaluasi akademis, pengujian reliabilitas, dan audit sistem.
            </p>

            <!-- 1. Ringkasan Skor Akhir f(Ki) -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 12px; color: var(--ink);">
                    1. Matriks Derajat Utilitas & Nilai Akhir $f(K_i)$
                </h4>
                <div style="overflow-x: auto;">
                    <table class="table-kabut">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Alternatif Paket</th>
                                <th>Mitra Vendor</th>
                                <th style="text-align: right;">Jumlah Terbobot ($S_i$)</th>
                                <th style="text-align: right;">Derajat $K_i^-$</th>
                                <th style="text-align: right;">Derajat $K_i^+$</th>
                                <th style="text-align: right; background: rgba(85, 98, 74, 0.1);">Utilitas Akhir $f(K_i)$</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($marcosData['rankings'] as $row)
                                <tr>
                                    <td><span class="badge {{ $row['rank'] == 1 ? 'badge-rust' : 'badge-outline' }}">#{{ $row['rank'] }}</span></td>
                                    <td style="font-weight: 600;">{{ $row['package_name'] }}</td>
                                    <td>{{ $row['vendor_name'] }}</td>
                                    <td style="text-align: right; font-family: monospace;">{{ number_format($row['S_i'], 4) }}</td>
                                    <td style="text-align: right; font-family: monospace;">{{ number_format($row['K_minus'], 4) }}</td>
                                    <td style="text-align: right; font-family: monospace;">{{ number_format($row['K_plus'], 4) }}</td>
                                    <td style="text-align: right; font-weight: 700; color: var(--rust); font-family: monospace; background: rgba(85, 98, 74, 0.06);">
                                        {{ number_format($row['final_score'], 5) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Matriks Keputusan X -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 12px; color: var(--ink);">
                    2. Matriks Keputusan Aktual ($X$)
                </h4>
                <div style="overflow-x: auto;">
                    <table class="table-kabut">
                        <thead>
                            <tr>
                                <th>Alternatif Paket</th>
                                @foreach($marcosData['criterias'] as $c)
                                    <th style="text-align: right;">{{ $c->code }} ({{ ucfirst($c->type) }})</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($marcosData['bundlings'] as $b)
                                <tr>
                                    <td style="font-weight: 600;">{{ $b->package_name }}</td>
                                    @foreach($marcosData['criterias'] as $c)
                                        <td style="text-align: right; font-family: monospace;">
                                            {{ $marcosData['matrix'][$b->id][$c->id] ?? '-' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. Solusi Ideal & Anti-Ideal -->
            <div style="margin-bottom: 32px;">
                <h4 style="font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 12px; color: var(--ink);">
                    3. Solusi Ideal ($A^+$) dan Solusi Anti-Ideal ($A^-$)
                </h4>
                <div style="overflow-x: auto;">
                    <table class="table-kabut">
                        <thead>
                            <tr>
                                <th>Kondisi Solusi</th>
                                @foreach($marcosData['criterias'] as $c)
                                    <th style="text-align: right;">{{ $c->code }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background: rgba(85, 98, 74, 0.08);">
                                <td style="font-weight: 600; color: var(--moss);">Solusi Ideal ($A^+$)</td>
                                @foreach($marcosData['criterias'] as $c)
                                    <td style="text-align: right; font-family: monospace; font-weight: 600;">
                                        {{ $marcosData['ideal'][$c->id] ?? '-' }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr style="background: rgba(168, 50, 31, 0.06);">
                                <td style="font-weight: 600; color: var(--danger);">Solusi Anti-Ideal ($A^-$)</td>
                                @foreach($marcosData['criterias'] as $c)
                                    <td style="text-align: right; font-family: monospace; font-weight: 600;">
                                        {{ $marcosData['anti_ideal'][$c->id] ?? '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. Matriks Ternormalisasi Terbobot V -->
            <div>
                <h4 style="font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 12px; color: var(--ink);">
                    4. Matriks Ternormalisasi Terbobot ($V = N \times w_j$)
                </h4>
                <div style="overflow-x: auto;">
                    <table class="table-kabut">
                        <thead>
                            <tr>
                                <th>Alternatif Paket</th>
                                @foreach($marcosData['criterias'] as $c)
                                    <th style="text-align: right;">{{ $c->code }} ($w={{ number_format($c->weight, 3) }})</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($marcosData['bundlings'] as $b)
                                <tr>
                                    <td style="font-weight: 600;">{{ $b->package_name }}</td>
                                    @foreach($marcosData['criterias'] as $c)
                                        <td style="text-align: right; font-family: monospace;">
                                            {{ number_format($marcosData['weighted'][$b->id][$c->id] ?? 0, 4) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function toggleMathSection() {
        const sec = document.getElementById('spkMathSection');
        const txt = document.getElementById('txtToggleMath');
        if (sec.style.display === 'none') {
            sec.style.display = 'block';
            txt.textContent = 'Sembunyikan Rincian Analisis Matematis';
            sec.scrollIntoView({ behavior: 'smooth' });
        } else {
            sec.style.display = 'none';
            txt.textContent = 'Lihat Rincian Analisis Matematis';
        }
    }
</script>
@endpush
@endsection
