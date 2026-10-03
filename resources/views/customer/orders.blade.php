@extends('layouts.app')

@section('title', 'Pesanan Saya - Glow & Grace')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <div style="margin-bottom: 28px;">
        <span class="badge badge-accent" style="margin-bottom: 8px;">Area Pelanggan</span>
        <h1 class="headline-lg" style="margin-bottom: 8px;">Daftar Riwayat Pesanan</h1>
        <p class="body-sm">Pantau status konfirmasi vendor, unggah bukti bayar, dan berikan review layanan.</p>
    </div>

    <div class="card-dribbble" style="padding: 24px; border-radius: 14px;">
        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Paket Bundling</th>
                        <th>Vendor</th>
                        <th>Tanggal Acara</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                        <tr>
                            <td>
                                <strong>{{ $o->order_code }}</strong>
                                <div class="caption">{{ $o->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td>
                                @if($o->bundling)
                                    <a href="{{ route('bundling.detail', $o->bundling_id) }}" style="font-weight: 600;">
                                        {{ $o->bundling->package_name }}
                                    </a>
                                    <div class="caption">Paket Bundling</div>
                                @elseif($o->serviceItem)
                                    <a href="{{ route('service.detail', $o->service_item_id) }}" style="font-weight: 600;">
                                        {{ $o->serviceItem->name }}
                                    </a>
                                    <div class="caption">{{ $o->serviceItem->badge ?? 'Layanan Terpisah' }}</div>
                                @else
                                    <span style="font-weight: 600;">Pesanan Layanan</span>
                                @endif
                            </td>
                            <td>{{ $o->vendor->vendor_name }}</td>
                            <td>
                                <div>{{ $o->event_date->format('d M Y') }}</div>
                                <div class="caption">{{ $o->event_time ?: '08:00 WIB' }}</div>
                            </td>
                            <td>
                                <strong>Rp {{ number_format($o->total_price, 0, ',', '.') }}</strong>
                                @if($o->payment_type === 'dp')
                                    <div class="caption" style="color: var(--color-highlight);">DP: Rp {{ number_format($o->dp_amount, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($o->status) {
                                        'pending' => 'badge-amber',
                                        'approved' => 'badge-primary',
                                        'paid' => 'badge-accent',
                                        'completed' => 'badge-success',
                                        'cancelled' => 'badge-muted',
                                        default => 'badge-muted',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ $o->status_label }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('customer.order.detail', $o->id) }}" class="btn btn-secondary btn-sm">
                                    Lihat Detail & Bayar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px 20px;">
                                <i data-lucide="shopping-bag" style="width: 40px; height: 40px; color: var(--color-secondary); margin-bottom: 12px;"></i>
                                <h3 class="headline-sm" style="margin-bottom: 6px;">Belum Ada Pesanan</h3>
                                <p class="body-sm" style="margin-bottom: 16px;">Anda belum melakukan pemesanan paket make-up & dekorasi.</p>
                                <a href="{{ route('bundlings') }}" class="btn btn-primary btn-sm">Jelajahi Paket</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
