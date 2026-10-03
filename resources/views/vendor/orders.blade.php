@extends('layouts.app')

@section('title', 'Pesanan Masuk - ' . $vendor->vendor_name)

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Vendor Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Manajemen Transaksi</span>
            <h1 class="headline-md">Pesanan Masuk Pelanggan</h1>
        </div>
        <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary btn-sm">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <!-- Navigation Tabs for Vendor -->
    <div style="display: flex; gap: 10px; margin-bottom: 28px; border-bottom: 1px solid var(--color-border); padding-bottom: 12px; overflow-x: auto;">
        <a href="{{ route('vendor.dashboard') }}" class="chip">Ringkasan</a>
        <a href="{{ route('vendor.bundlings') }}" class="chip">Kelola Paket</a>
        <a href="{{ route('vendor.galleries') }}" class="chip">Galeri Foto</a>
        <a href="{{ route('vendor.orders') }}" class="chip active">Pesanan Masuk ({{ $orders->total() }})</a>
        <a href="{{ route('vendor.profile') }}" class="chip">Profil Vendor</a>
    </div>

    <div class="card-dribbble" style="padding: 24px; border-radius: 14px;">
        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Data Pelanggan</th>
                        <th>Paket Dipesan</th>
                        <th>Tanggal & Lokasi Acara</th>
                        <th>Total Tagihan</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi Vendor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_code }}</strong>
                                <div class="caption">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 700;">{{ $order->customer_name ?: ($order->customer->name ?? '-') }}</div>
                                <div class="caption">
                                    <i data-lucide="phone" style="width: 11px; height: 11px; vertical-align: middle;"></i>
                                    {{ $order->customer_phone ?: ($order->customer->phone ?? '-') }}
                                </div>
                            </td>
                            <td>
                                @if($order->bundling)
                                    <strong>{{ $order->bundling->package_name }}</strong>
                                    <div class="caption">Paket Bundling Utama</div>
                                @elseif($order->serviceItem)
                                    <strong>{{ $order->serviceItem->name }}</strong>
                                    <div class="caption">{{ $order->serviceItem->badge ?? 'Layanan' }}</div>
                                @else
                                    <strong>Pesanan Layanan</strong>
                                @endif
                            </td>
                            <td>
                                <div><strong>{{ $order->event_date->format('d M Y') }}</strong> ({{ $order->event_time ?: '08:00 WIB' }})</div>
                                <div class="caption" style="max-width: 220px;">{{ $order->event_location }}</div>
                            </td>
                            <td>
                                <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                @if($order->payment_type === 'dp')
                                    <div class="caption" style="color: var(--color-highlight);">DP: Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</div>
                                @endif
                                @if($order->payment)
                                    <div class="caption" style="color: var(--color-success-text);">
                                        {{ $order->payment->payment_type === 'midtrans' ? 'Midtrans Online' : 'Manual ' . $order->payment->bank_name }} ({{ $order->payment->payment_status }})
                                    </div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badge = match($order->status) {
                                        'pending' => 'badge-amber',
                                        'approved' => 'badge-primary',
                                        'paid' => 'badge-accent',
                                        'completed' => 'badge-success',
                                        'cancelled' => 'badge-muted',
                                        default => 'badge-muted'
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ $order->status_label }}</span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    @if($order->status === 'pending')
                                        <form action="{{ route('vendor.order.status', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-primary btn-sm" style="font-size: 11px;">
                                                Setujui Pesanan
                                            </button>
                                        </form>
                                        <form action="{{ route('vendor.order.status', $order->id) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?');">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="btn btn-secondary btn-sm" style="font-size: 11px; color: var(--color-error);">
                                                Tolak
                                            </button>
                                        </form>
                                    @elseif($order->status === 'approved')
                                        <span class="caption" style="color: var(--color-secondary);">Menunggu Bayar</span>
                                    @elseif($order->status === 'paid')
                                        <form action="{{ route('vendor.order.status', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-secondary btn-sm" style="font-size: 11px; background: var(--color-success); color: var(--color-success-text); border-color: rgba(11,114,64,0.3);">
                                                Selesaikan Acara
                                            </button>
                                        </form>
                                    @elseif($order->status === 'completed')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-muted">Dibatalkan</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px;">Belum ada pesanan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
