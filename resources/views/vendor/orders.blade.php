@extends('layouts.app')

@section('title', 'Pesanan Masuk & Berjalan - ' . $vendor->vendor_name)

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Vendor Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Pesanan Aktif</span>
            <h1 class="headline-md">Pesanan Masuk & Berjalan</h1>
            <p class="body-sm">Kelola pesanan yang menunggu konfirmasi, menunggu pembayaran, atau sedang dalam persiapan acara.</p>
        </div>
        
        <!-- Toggle Tabs: Berjalan vs Selesai -->
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('vendor.orders') }}" class="btn btn-primary btn-sm">
                <i data-lucide="clock" style="width: 14px; height: 14px;"></i>
                Pesanan Berjalan ({{ $activeCount }})
            </a>
            <a href="{{ route('vendor.orders.history') }}" class="btn btn-secondary btn-sm">
                <i data-lucide="check-circle" style="width: 14px; height: 14px;"></i>
                Riwayat Selesai ({{ $historyCount }})
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: var(--color-success); color: var(--color-success-text); padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; gap: 10px; border: 1px solid rgba(11,114,64,0.2);">
            <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="card-kabut" style="padding: 24px; border-radius: 14px; background: #fff;">
        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Data Pelanggan</th>
                        <th>Paket / Layanan Dipesan</th>
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
                                <div style="margin-top: 4px;">
                                    <a href="{{ route('vendor.chat', ['order_id' => $order->id]) }}" class="caption" style="display: inline-flex; align-items: center; gap: 4px; color: var(--primary); font-weight: 600;">
                                        <i data-lucide="message-circle" style="width: 12px; height: 12px;"></i>
                                        Chat Pelanggan
                                    </a>
                                </div>
                            </td>
                            <td>
                                @if($order->items && $order->items->isNotEmpty())
                                    <div>
                                        <strong>{{ $order->items->first()->item_name }}</strong>
                                        @if($order->items->count() > 1)
                                            <div class="caption" style="color: var(--primary); font-weight: 600;">
                                                + {{ $order->items->count() - 1 }} item tambahan lainnya
                                            </div>
                                        @endif
                                    </div>
                                @elseif($order->bundling)
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
                                        <form action="{{ route('vendor.order.status', $order->id) }}" method="POST" onsubmit="return confirm('Tolak dan batalkan pesanan ini?');">
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
                                        <form action="{{ route('vendor.order.status', $order->id) }}" method="POST" onsubmit="return confirm('Tandai acara ini telah selesai dilaksanakan?');">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-secondary btn-sm" style="font-size: 11px; background: var(--color-success); color: var(--color-success-text); border-color: rgba(11,114,64,0.3);">
                                                Selesaikan Acara
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px; color: var(--muted);">
                                Tidak ada pesanan aktif atau berjalan saat ini.
                            </td>
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
