@extends('layouts.app')

@section('title', $vendor->vendor_name . ' - Profil & Paket Vendor')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Vendor Profile Header Card -->
    <div class="card-dribbble" style="padding: 32px; border-radius: 16px; margin-bottom: 36px;">
        <div style="display: flex; flex-wrap: wrap; gap: 28px; align-items: flex-start; justify-content: space-between;">
            <div style="display: flex; gap: 20px; align-items: center;">
                <div style="width: 76px; height: 76px; border-radius: 50%; background: var(--color-primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 26px; font-weight: 800; flex-shrink: 0;">
                    {{ strtoupper(substr($vendor->vendor_name, 0, 2)) }}
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                        <h1 class="headline-md">{{ $vendor->vendor_name }}</h1>
                        <span class="badge badge-accent">Mitra Terverifikasi</span>
                    </div>
                    <div class="body-sm" style="margin-bottom: 6px;">
                        Owner: <strong>{{ $vendor->owner_name }}</strong> &bull; {{ $vendor->bundlings->count() }} Paket Layanan
                    </div>
                    <div class="body-sm" style="display: flex; align-items: center; gap: 6px; color: var(--color-secondary);">
                        <i data-lucide="map-pin" style="width: 14px; height: 14px;"></i>
                        <span>{{ $vendor->location_address }}</span>
                    </div>
                </div>
            </div>

            <!-- Action CTAs -->
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <a href="{{ $vendor->maps_url }}" target="_blank" class="btn btn-secondary btn-sm">
                    <i data-lucide="navigation" style="width: 14px; height: 14px;"></i>
                    Peta Lokasi Google Maps
                </a>
                <a href="{{ $vendor->whatsapp_url }}" target="_blank" class="btn btn-primary btn-sm" style="background: #25D366; color: white;">
                    <i data-lucide="message-circle" style="width: 15px; height: 15px;"></i>
                    Hubungi WhatsApp
                </a>
            </div>
        </div>

        @if($vendor->description)
            <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--color-border);" class="body-md">
                {{ $vendor->description }}
            </div>
        @endif
    </div>

    <!-- Section: Paket Bundling Vendor -->
    <div style="margin-bottom: 48px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 class="headline-sm">Paket Make-Up & Dekorasi Ditawarkan</h2>
            <span class="badge badge-muted">{{ $vendor->bundlings->count() }} Paket Aktif</span>
        </div>

        <div class="grid-cards">
            @forelse($vendor->bundlings as $item)
                @php
                    $cover = $item->galleries->first()?->url 
                        ?? $vendor->galleries->first()?->url 
                        ?? 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80';
                @endphp
                <div class="card-dribbble">
                    <img src="{{ $cover }}" alt="{{ $item->package_name }}" class="img-cover-box" style="height: 190px; margin-bottom: 12px;">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 6px;">
                        <a href="{{ route('bundling.detail', $item->id) }}">{{ $item->package_name }}</a>
                    </h3>
                    <p class="body-sm" style="margin-bottom: 14px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $item->description }}
                    </p>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid var(--color-border);">
                        <div>
                            <div class="caption">Mulai dari</div>
                            <div style="font-size: 15px; font-weight: 800;">
                                Rp {{ number_format($item->base_price, 0, ',', '.') }}
                            </div>
                        </div>
                        <a href="{{ route('bundling.detail', $item->id) }}" class="btn btn-primary btn-sm">
                            Lihat Paket
                        </a>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                    <p class="body-sm">Belum ada paket bundling yang aktif.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Section: Galeri Portofolio Foto -->
    <div style="margin-bottom: 48px;">
        <h2 class="headline-sm" style="margin-bottom: 20px;">Galeri Foto Portofolio</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px;">
            @forelse($vendor->galleries as $gal)
                <div class="card-dribbble" style="padding: 8px;">
                    <img src="{{ $gal->url }}" alt="{{ $gal->caption }}" class="img-cover-box" style="height: 200px; margin-bottom: 6px;">
                    @if($gal->caption)
                        <div class="caption" style="padding: 4px;">{{ $gal->caption }}</div>
                    @endif
                </div>
            @empty
                <div style="grid-column: 1 / -1;">
                    <p class="body-sm">Belum ada foto portofolio diunggah.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Section: Ulasan Pelanggan -->
    <div class="card-dribbble" style="padding: 28px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h2 class="headline-sm">Ulasan & Testimoni Pelanggan</h2>
            <span class="badge badge-amber" style="font-size: 13px;">
                ★ {{ $vendor->averageRating() }} / 5.0 ({{ $vendor->reviews->count() }} ulasan)
            </span>
        </div>

        @forelse($vendor->reviews as $rev)
            <div style="border-bottom: 1px solid var(--color-border); padding: 14px 0;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                    <div style="font-weight: 700; font-size: 13px;">{{ $rev->customer->name ?? 'Pelanggan Terverifikasi' }}</div>
                    <div style="color: var(--color-accent); font-size: 12px;">
                        @for($i = 0; $i < $rev->rating; $i++) ★ @endfor
                    </div>
                </div>
                <p class="body-sm" style="color: var(--color-primary);">{{ $rev->comment }}</p>
                <div class="caption" style="margin-top: 4px;">{{ $rev->created_at->format('d M Y') }}</div>
            </div>
        @empty
            <p class="body-sm">Belum ada ulasan untuk vendor ini.</p>
        @endforelse
    </div>
</div>
@endsection
