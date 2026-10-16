@extends('layouts.app')

@section('title', 'Keranjang Belanja - E-Marketplace')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 72px;">
    <!-- Page Header -->
    <div style="margin-bottom: 28px;">
        <span class="badge badge-moss" style="margin-bottom: 6px;">Multi-Item Checkout</span>
        <h1 class="headline-lg">Keranjang Belanja</h1>
        <p class="body-sm">Pilih beberapa paket atau layanan dari vendor pernikahan dan lakukan pembayaran sekaligus.</p>
    </div>

    @if($cartItems->isEmpty())
        <div class="card-kabut" style="text-align: center; padding: 64px 24px; max-width: 600px; margin: 0 auto; border-radius: 16px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--blush-light); color: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i data-lucide="shopping-cart" style="width: 32px; height: 32px;"></i>
            </div>
            <h2 class="headline-md" style="font-size: 1.4rem; margin-bottom: 10px;">Keranjang Belanja Masih Kosong</h2>
            <p class="body-sm" style="margin-bottom: 24px; color: var(--muted);">
                Anda belum menambahkan paket riasan, dekorasi pelaminan, atau layanan lainnya ke dalam keranjang.
            </p>
            <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('bundlings') }}" class="btn btn-primary btn-sm">
                    <i data-lucide="package" style="width: 14px; height: 14px;"></i>
                    Jelajahi Paket Bundling
                </a>
                <a href="{{ route('services') }}" class="btn btn-secondary btn-sm">
                    <i data-lucide="grid" style="width: 14px; height: 14px;"></i>
                    Katalog Layanan Spesifik
                </a>
            </div>
        </div>
    @else
        <div style="display: grid; grid-template-columns: 1fr 360px; gap: 32px; align-items: start;">
            <!-- Cart Items List -->
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <span style="font-weight: 700; font-size: 1.05rem; color: var(--ink);">
                        Daftar Item ({{ $cartItems->sum('quantity') }} Layanan)
                    </span>
                    <form action="{{ route('customer.cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan semua item di keranjang?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-tertiary" style="color: var(--error); font-size: 12px; padding: 4px 8px;">
                            <i data-lucide="trash" style="width: 12px; height: 12px;"></i>
                            Kosongkan Keranjang
                        </button>
                    </form>
                </div>

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($cartItems as $item)
                        @php
                            $vendorName = $item->vendor->vendor_name 
                                ?? $item->bundling?->vendor->vendor_name 
                                ?? $item->serviceItem?->vendor->vendor_name 
                                ?? 'Mitra Vendor';
                            $itemUrl = $item->bundling_id 
                                ? route('bundling.detail', $item->bundling_id) 
                                : ($item->service_item_id ? route('service.detail', $item->service_item_id) : '#');
                        @endphp
                        <div class="card-kabut" style="padding: 18px 20px; border-radius: 14px; display: grid; grid-template-columns: 100px 1fr auto; gap: 20px; align-items: center;">
                            <!-- Thumbnail -->
                            <a href="{{ $itemUrl }}" style="display: block; width: 100px; height: 90px; border-radius: 10px; overflow: hidden; background: #eee;">
                                <img src="{{ $item->thumbnail }}" alt="{{ $item->resolved_name }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=400&q=80';">
                            </a>

                            <!-- Info -->
                            <div>
                                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                                    <span class="badge {{ $item->item_type === 'bundling' ? 'badge-primary' : 'badge-moss' }}" style="font-size: 10px; padding: 1px 7px;">
                                        {{ $item->item_type === 'bundling' ? 'Paket Bundling' : 'Layanan Satuan' }}
                                    </span>
                                    <span class="caption" style="display: inline-flex; align-items: center; gap: 4px; font-weight: 600;">
                                        <i data-lucide="store" style="width: 12px; height: 12px;"></i>
                                        {{ $vendorName }}
                                    </span>
                                </div>
                                <h3 style="font-family: var(--font-heading); font-size: 1.15rem; margin-bottom: 6px;">
                                    <a href="{{ $itemUrl }}" style="text-decoration: none; color: var(--ink);">
                                        {{ $item->resolved_name }}
                                    </a>
                                </h3>
                                <div style="color: var(--primary); font-weight: 700; font-size: 1rem;">
                                    Rp {{ number_format($item->resolved_price, 0, ',', '.') }}
                                    <span class="caption" style="font-weight: 400; color: var(--muted);">/ paket/acara</span>
                                </div>

                                @if($item->event_date)
                                    <div style="margin-top: 6px; display: inline-flex; align-items: center; gap: 5px; background: rgba(142,60,0,0.08); padding: 3px 8px; border-radius: 6px; font-size: 11.5px; color: var(--primary); font-weight: 600;">
                                        <i data-lucide="calendar" style="width: 12px; height: 12px;"></i>
                                        <span>Rencana Acara: {{ \Carbon\Carbon::parse($item->event_date)->isoFormat('D MMMM Y') }}</span>
                                    </div>
                                @endif

                                @if($item->event_location)
                                    <div style="margin-top: 3px; font-size: 11.5px; color: var(--muted); display: flex; align-items: center; gap: 4px;">
                                        <i data-lucide="map-pin" style="width: 11px; height: 11px;"></i>
                                        <span>{{ $item->event_location }}</span>
                                    </div>
                                @endif

                                @if($item->notes)
                                    <div style="margin-top: 4px; font-size: 11px; color: var(--muted); font-style: italic; background: var(--surface-low); padding: 4px 8px; border-radius: 4px;">
                                        Catatan: "{{ Str::limit($item->notes, 70) }}"
                                    </div>
                                @endif
                            </div>

                            <!-- Quantity & Action -->
                            <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
                                <div style="font-weight: 700; font-size: 1.1rem; color: var(--ink);">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>

                                <form action="{{ route('customer.cart.update', $item->id) }}" method="POST" style="display: flex; align-items: center; gap: 6px; margin: 0;">
                                    @csrf
                                    @method('PUT')
                                    <span class="caption" style="font-size: 11px;">Jml:</span>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="20" style="width: 54px; height: 32px; padding: 2px 6px; font-size: 13px; text-align: center; border: 1px solid var(--line); border-radius: 6px;" onchange="this.form.submit()">
                                </form>

                                <form action="{{ route('customer.cart.remove', $item->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-tertiary" style="color: var(--error); padding: 4px 6px; font-size: 11px; height: auto;">
                                        <i data-lucide="trash-2" style="width: 12px; height: 12px;"></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 20px;">
                    <a href="{{ route('services') }}" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px;">
                        <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
                        Tambah Paket / Layanan Lain
                    </a>
                </div>
            </div>

            <!-- Cart Summary Box -->
            <div class="card-kabut" style="padding: 24px; border-radius: 16px; position: sticky; top: 90px; background: #fff; box-shadow: var(--shadow-tier1);">
                <h3 style="font-family: var(--font-heading); font-size: 1.25rem; margin-bottom: 18px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">
                    Ringkasan Transaksi
                </h3>

                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.9rem;">
                    <span style="color: var(--muted);">Total Item</span>
                    <span style="font-weight: 600;">{{ $cartItems->sum('quantity') }} Item</span>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 0.9rem;">
                    <span style="color: var(--muted);">Total Estimasi</span>
                    <span style="font-weight: 700; font-size: 1.15rem; color: var(--primary);">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </span>
                </div>

                <div style="background: var(--surface-low); padding: 12px 14px; border-radius: 10px; margin-bottom: 20px; font-size: 0.82rem; line-height: 1.5; color: var(--body);">
                    <div style="font-weight: 600; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                        <i data-lucide="shield-check" style="width: 14px; height: 14px; color: var(--success);"></i>
                        Opsi Pembayaran Fleksibel
                    </div>
                    Tersedia pilihan <strong>Down Payment (DP 30% atau 50%)</strong> atau <strong>Pelunasan Penuh</strong> via Midtrans di halaman checkout berikutnya.
                </div>

                <a href="{{ route('customer.cart.checkout') }}" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px; font-size: 0.95rem;">
                    <i data-lucide="check-circle" style="width: 16px; height: 16px;"></i>
                    Lanjut ke Checkout
                </a>

                <div style="text-align: center; margin-top: 14px;">
                    <span class="caption" style="display: inline-flex; align-items: center; gap: 4px;">
                        <i data-lucide="lock" style="width: 12px; height: 12px;"></i>
                        Transaksi Terenkripsi Midtrans Snap
                    </span>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
