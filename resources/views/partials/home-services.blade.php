@forelse($services as $item)
    @php
        $img = $item->image_url 
            ?: ($item->vendor->galleries->first()?->url 
            ?: asset('storage/catalog_photos/Medium Pakage.jpeg'));
    @endphp
    <article class="product-card service-item-card" data-category="{{ $item->category->slug }}" style="animation: fadeInCard 0.3s ease;">
        <a href="{{ route('service.detail', $item->id) }}" class="product-card__media">
            <img src="{{ $img }}" alt="{{ $item->name }}" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('storage/catalog_photos/Medium Pakage.jpeg') }}';">
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
@empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 48px 24px; background: #FFFFFF; border-radius: 14px; border: 1px dashed var(--border-gold-medium);">
        <i data-lucide="tag" style="width: 36px; height: 36px; color: var(--muted); margin: 0 auto 10px;"></i>
        <h4 style="font-family: var(--font-heading); color: var(--emerald-deep); margin-bottom: 4px;">Tidak Ada Layanan Ditemukan</h4>
        <p class="body-sm" style="color: var(--muted); max-width: 480px; margin: 0 auto;">
            Belum ada item layanan individual yang cocok dengan kategori atau pencarian yang dipilih. Silakan pilih kategori lain atau reset filter.
        </p>
    </div>
@endforelse
