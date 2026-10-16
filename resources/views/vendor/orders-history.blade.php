@extends('layouts.app')

@section('title', 'Riwayat Pesanan Selesai - ' . $vendor->vendor_name)

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Vendor Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <span class="badge badge-success" style="margin-bottom: 6px;">Arsip Transaksi</span>
            <h1 class="headline-md">Riwayat Pesanan Selesai</h1>
            <p class="body-sm">Daftar arsip pesanan yang telah sukses dilaksanakan dan pesanan yang telah dibatalkan.</p>
        </div>
        
        <!-- Toggle Tabs: Berjalan vs Selesai -->
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('vendor.orders') }}" class="btn btn-secondary btn-sm">
                <i data-lucide="clock" style="width: 14px; height: 14px;"></i>
                Pesanan Berjalan ({{ $activeCount }})
            </a>
            <a href="{{ route('vendor.orders.history') }}" class="btn btn-primary btn-sm">
                <i data-lucide="check-circle" style="width: 14px; height: 14px;"></i>
                Riwayat Selesai ({{ $historyCount }})
            </a>
        </div>
    </div>

    <div class="card-kabut" style="padding: 24px; border-radius: 14px; background: #fff;">
        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Layanan / Paket</th>
                        <th>Tanggal Pelaksanaan</th>
                        <th>Total Transaksi</th>
                        <th>Status Akhir</th>
                        <th>Rating & Ulasan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->order_code }}</strong>
                                <div class="caption">{{ $order->created_at->format('d M Y') }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 700;">{{ $order->customer_name ?: ($order->customer->name ?? '-') }}</div>
                                <div class="caption">{{ $order->customer_phone ?: ($order->customer->phone ?? '-') }}</div>
                            </td>
                            <td>
                                @if($order->items && $order->items->isNotEmpty())
                                    <div>
                                        <strong>{{ $order->items->first()->item_name }}</strong>
                                        @if($order->items->count() > 1)
                                            <div class="caption" style="color: var(--primary);">
                                                + {{ $order->items->count() - 1 }} item lainnya
                                            </div>
                                        @endif
                                    </div>
                                @elseif($order->bundling)
                                    <strong>{{ $order->bundling->package_name }}</strong>
                                @elseif($order->serviceItem)
                                    <strong>{{ $order->serviceItem->name }}</strong>
                                @else
                                    <strong>Pesanan Layanan</strong>
                                @endif
                            </td>
                            <td>
                                <div><strong>{{ $order->event_date->format('d M Y') }}</strong></div>
                                <div class="caption">{{ Str::limit($order->event_location, 35) }}</div>
                            </td>
                            <td>
                                <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if($order->status === 'completed')
                                    <span class="badge badge-success">
                                        <i data-lucide="check-circle" style="width: 12px; height: 12px;"></i>
                                        Selesai
                                    </span>
                                @else
                                    <span class="badge badge-muted">
                                        <i data-lucide="x-circle" style="width: 12px; height: 12px;"></i>
                                        Dibatalkan
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($order->review)
                                    <div style="display: flex; align-items: center; gap: 4px; color: #F59E0B; font-weight: 700;">
                                        <i data-lucide="star" style="width: 14px; height: 14px; fill: #F59E0B;"></i>
                                        <span>{{ $order->review->rating }}/5</span>
                                    </div>
                                    <div class="caption" style="font-style: italic; max-width: 200px;">
                                        "{{ Str::limit($order->review->comment, 45) }}"
                                    </div>
                                @else
                                    <span class="caption" style="color: var(--muted);">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px; color: var(--muted);">
                                Belum ada riwayat pesanan yang telah selesai atau dibatalkan.
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
