@forelse($topRecommendations as $rec)
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
    <article class="product-card" style="animation: fadeInCard 0.3s ease;">
        <!-- Media Cover Box -->
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
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; flex-wrap: wrap; gap: 4px;">
                <span class="caption" style="display: inline-flex; align-items: center; gap: 4px;">
                    <i data-lucide="store" style="width: 12px; height: 12px; color: var(--muted);"></i>
                    {{ $rec['vendor_name'] }} <small style="color: #64748B;">({{ $rec['vendor_region'] ?? ($rec['vendor']?->region ?? 'Deli Serdang') }})</small>
                </span>
                @if(!empty($rec['proximity']))
                    <span class="badge" style="background: {{ $rec['proximity']['badge_bg'] }}; color: {{ $rec['proximity']['badge_color'] }}; border: 1px solid {{ $rec['proximity']['badge_border'] }}; font-size: 10px; padding: 1px 6px;">
                        📍 {{ $rec['proximity']['label'] }}
                    </span>
                @else
                    <span class="badge badge-moss" style="font-size: 10px; padding: 1px 6px;">Terverifikasi</span>
                @endif
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
@empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 48px 24px; background: #FFFFFF; border-radius: 14px; border: 1px dashed #CBD5E1;">
        <i data-lucide="package-search" style="width: 42px; height: 42px; color: #94A3B8; margin: 0 auto 12px;"></i>
        <h4 style="font-family: var(--font-heading); color: #1E293B; margin-bottom: 6px; font-size: 1.15rem; font-weight: 600;">
            Paket yang direkomendasikan belum ada
        </h4>
        <p class="body-sm" style="color: #64748B; max-width: 480px; margin: 0 auto;">
            Evaluasi skor alternatif belum diisi atau sedang dikosongkan oleh administrator.
        </p>
    </div>
@endforelse
