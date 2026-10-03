@extends('layouts.app')

@section('title', 'Direktori Vendor Make-Up & Dekorasi - Glow & Grace')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Breadcrumb & Header -->
    <div style="margin-bottom: 28px;">
        <span class="badge badge-accent" style="margin-bottom: 8px;">Mitra Terverifikasi</span>
        <h1 class="headline-lg" style="margin-bottom: 8px;">Direktori Vendor Make-Up & Dekorasi</h1>
        <p class="body-sm">Temukan perias profesional dan desainer pelaminan terpercaya di Tanjung Morawa, Medan, Kisaran, dan sekitarnya.</p>
    </div>

    <!-- Search Bar -->
    <form action="{{ route('vendors') }}" method="GET" class="card-quiet" style="margin-bottom: 32px; border-radius: 14px; padding: 18px;">
        <div style="display: flex; gap: 12px; align-items: center;">
            <div style="flex: 1;">
                <input type="text" name="search" class="input-pill" placeholder="Cari nama vendor, pemilik, atau kota / kecamatan..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 48px;">
                <i data-lucide="search" style="width: 16px; height: 16px;"></i>
                Cari Vendor
            </button>
            @if(request('search'))
                <a href="{{ route('vendors') }}" class="btn btn-secondary" style="height: 48px;">Reset</a>
            @endif
        </div>
    </form>

    <!-- Vendors Grid -->
    <div class="grid-cards" style="margin-bottom: 40px;">
        @forelse($vendors as $vendor)
            @php
                $photo = $vendor->galleries->first()?->url 
                    ?? 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=800&q=80';
            @endphp
            <div class="card-dribbble" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="position: relative; margin-bottom: 16px;">
                        <img src="{{ $photo }}" alt="{{ $vendor->vendor_name }}" class="img-cover-box" style="height: 180px;">
                        <div style="position: absolute; bottom: -18px; left: 16px; width: 44px; height: 44px; border-radius: 50%; background: var(--color-primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; border: 2px solid white; box-shadow: var(--shadow-subtle);">
                            {{ strtoupper(substr($vendor->vendor_name, 0, 1)) }}
                        </div>
                    </div>

                    <div style="padding-top: 6px; margin-bottom: 8px;">
                        <h3 style="font-size: 17px; font-weight: 700; margin-bottom: 4px;">
                            <a href="{{ route('vendor.detail', $vendor->id) }}">
                                {{ $vendor->vendor_name }}
                            </a>
                        </h3>
                        <div class="caption" style="color: var(--color-secondary);">Owner: {{ $vendor->owner_name }}</div>
                    </div>

                    <div class="body-sm" style="display: flex; align-items: flex-start; gap: 6px; margin-bottom: 12px; color: var(--color-secondary);">
                        <i data-lucide="map-pin" style="width: 13px; height: 13px; flex-shrink: 0; margin-top: 3px;"></i>
                        <span>{{ Str::limit($vendor->location_address, 65) }}</span>
                    </div>

                    <p class="body-sm" style="margin-bottom: 16px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.45;">
                        {{ $vendor->description }}
                    </p>
                </div>

                <div style="padding-top: 14px; border-top: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; gap: 6px;">
                        <span class="badge badge-muted">{{ $vendor->bundlings->count() }} Paket</span>
                        <span class="badge badge-amber">★ {{ $vendor->averageRating() }}</span>
                    </div>
                    <a href="{{ route('vendor.detail', $vendor->id) }}" class="btn btn-secondary btn-sm">
                        Kunjungi Toko
                    </a>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 64px 20px;">
                <p class="body-sm">Tidak ada vendor ditemukan.</p>
            </div>
        @endforelse
    </div>

    <div>
        {{ $vendors->links() }}
    </div>
</div>
@endsection
