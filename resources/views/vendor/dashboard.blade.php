@extends('layouts.app')

@section('title', 'Dashboard Vendor - ' . $vendor->vendor_name)

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Vendor Banner Header -->
    <div style="position: relative; border-radius: 16px; overflow: hidden; margin-bottom: 24px; box-shadow: var(--shadow-subtle); background: #1C1917;">
        <div style="width: 100%; height: 140px; position: relative;">
            <img src="{{ $vendor->cover_url }}" alt="Cover {{ $vendor->vendor_name }}" style="width: 100%; height: 100%; object-fit: cover;">
            <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.7) 100%);"></div>
        </div>
        <div style="padding: 0 24px 20px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-top: -40px; position: relative; z-index: 2;">
            <div style="display: flex; align-items: flex-end; gap: 16px; flex-wrap: wrap;">
                <a href="{{ route('vendor.profile') }}" title="Ganti Foto Profil Toko" style="position: relative; display: block; flex-shrink: 0;">
                    <img src="{{ $vendor->avatar_url }}" alt="{{ $vendor->vendor_name }}" style="width: 76px; height: 76px; border-radius: 50%; object-fit: cover; border: 3.5px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.25); background: #FAF5EB;">
                    <span style="position: absolute; bottom: 0; right: 0; background: var(--color-primary); color: #fff; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1.5px solid #fff;" title="Ubah Foto">
                        <i data-lucide="camera" style="width: 11px; height: 11px;"></i>
                    </span>
                </a>
                <div style="padding-bottom: 4px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
                        <h1 class="headline-lg" style="margin: 0; color: #fff; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">{{ $vendor->vendor_name }}</h1>
                        <span class="badge badge-moss" style="font-size: 11px; padding: 2px 8px;">Mitra Vendor</span>
                    </div>
                    <p class="body-sm" style="margin: 0; color: rgba(255,255,255,0.85); text-shadow: 0 1px 3px rgba(0,0,0,0.6);">
                        Owner: <strong>{{ $vendor->owner_name }}</strong> &bull; {{ $vendor->location_address }}
                    </p>
                </div>
            </div>

            <div style="display: flex; gap: 8px; padding-bottom: 4px;">
                <a href="{{ route('vendor.bundlings') }}" class="btn btn-primary btn-sm">
                    <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
                    Tambah Paket
                </a>
                <a href="{{ route('vendor.profile') }}" class="btn btn-secondary btn-sm" style="background: rgba(255,255,255,0.9); border: none;">
                    <i data-lucide="settings" style="width: 14px; height: 14px;"></i>
                    Edit Profil & Sampul
                </a>
                <a href="{{ route('vendor.detail', $vendor->id) }}" class="btn btn-secondary btn-sm" style="background: rgba(255,255,255,0.9); border: none;">
                    <i data-lucide="globe" style="width: 14px; height: 14px;"></i>
                    Lihat Toko
                </a>
            </div>
        </div>
    </div>

    @if(!$vendor->hasBankAccount())
        <div style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 12px; padding: 14px 18px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i data-lucide="credit-card" style="width: 20px; height: 20px;"></i>
                </div>
                <div>
                    <strong style="color: #92400E; font-size: 14px; display: block;">Rekening Bank Toko Belum Diatur</strong>
                    <span style="color: #B45309; font-size: 12.5px;">Atur rekening bank toko Anda agar pelanggan dapat langsung mentransfer dana pembayaran pesanan ke rekening Anda.</span>
                </div>
            </div>
            <a href="{{ route('vendor.profile') }}" class="btn btn-sm" style="background: #D97706; color: #FFFFFF; font-weight: 600; padding: 6px 14px; border-radius: 8px; text-decoration: none;">
                Atur Rekening Sekarang &rarr;
            </a>
        </div>
    @else
        <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 10px; padding: 10px 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; gap: 12px; font-size: 12.5px; color: #166534; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <i data-lucide="check-circle" style="width: 16px; height: 16px; color: #15803D;"></i>
                <span>Rekening Penerima Pembayaran: <strong>{{ $vendor->bank_name }} - {{ $vendor->bank_account_number }} (a.n. {{ $vendor->bank_account_name }})</strong></span>
            </div>
            <a href="{{ route('vendor.profile') }}" style="color: #15803D; font-weight: 600; text-decoration: underline; font-size: 12px;">Ubah Rekening</a>
        </div>
    @endif

    <!-- Stats Grid (Minimalist Scale) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 28px;">
        <div class="card-kabut" style="padding: 16px;">
            <div class="caption">Total Paket Layanan</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--ink); margin-top: 2px;">
                {{ $stats['total_bundlings'] }}
            </div>
            <div class="caption">Aktif di etalase</div>
        </div>

        <div class="card-kabut" style="padding: 16px;">
            <div class="caption">Pesanan Menunggu</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--rust); margin-top: 2px;">
                {{ $stats['pending_orders'] }}
            </div>
            <div class="caption">Perlu konfirmasi Anda</div>
        </div>

        <div class="card-kabut" style="padding: 16px;">
            <div class="caption">Rating Toko</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--warning); margin-top: 2px;">
                ★ {{ $stats['avg_rating'] }}
            </div>
            <div class="caption">Dari ulasan pelanggan</div>
        </div>

        <div class="card-kabut" style="padding: 16px;">
            <div class="caption">Total Omset Pesanan</div>
            <div style="font-size: 1.15rem; font-weight: 700; color: var(--moss); margin-top: 4px;">
                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
            </div>
            <div class="caption">Pesanan lunas / selesai</div>
        </div>
    </div>

    <!-- 2 GRAFIK VENDOR DASHBOARD (CHART JS) -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px;" class="grid-cols-2">
        <!-- Grafik 1: Donut Chart (Status Pesanan Toko) -->
        <div class="card-kabut" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <div>
                    <h3 class="headline-sm" style="font-size: 0.98rem;">1. Status Pesanan Toko</h3>
                    <div class="caption">Distribusi status booking pelanggan saat ini</div>
                </div>
                <span class="badge badge-moss">Grafik Donat</span>
            </div>
            <div style="position: relative; height: 210px; width: 100%;">
                <canvas id="vendorStatusChart"></canvas>
            </div>
        </div>

        <!-- Grafik 2: Bar Chart (Komparasi Skema Pembayaran) -->
        <div class="card-kabut" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <div>
                    <h3 class="headline-sm" style="font-size: 0.98rem;">2. Pilihan Pembayaran Pelanggan</h3>
                    <div class="caption">Rasio pemesanan dengan Lunas vs Uang Muka (DP)</div>
                </div>
                <span class="badge badge-rust">Grafik Batang</span>
            </div>
            <div style="position: relative; height: 210px; width: 100%;">
                <canvas id="vendorPaymentChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table (Wrapped in Table-Responsive) -->
    <div class="card-kabut" style="padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <h3 class="headline-sm" style="font-size: 0.98rem;">Pesanan Terbaru Masuk</h3>
            <a href="{{ route('vendor.orders') }}" class="btn-tertiary">Lihat Semua Pesanan &rarr;</a>
        </div>

        <div class="table-responsive">
            <table class="table-kabut">
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Item Dipesan</th>
                        <th>Tanggal Acara</th>
                        <th>Total Tagihan</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td><strong style="color: var(--ink);">{{ $order->order_code }}</strong></td>
                            <td>
                                <div style="font-weight: 600;">{{ $order->customer_name ?: ($order->customer->name ?? '-') }}</div>
                                <div class="caption">{{ $order->customer_phone ?: ($order->customer->phone ?? '-') }}</div>
                            </td>
                            <td>{{ Str::limit($order->item_name, 22) }}</td>
                            <td>
                                <div>{{ $order->event_date->format('d M Y') }}</div>
                                <div class="caption">{{ $order->event_time ?: '08:00 WIB' }}</div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--rust);">
                                    Rp {{ number_format($order->payable_amount ?: $order->total_price, 0, ',', '.') }}
                                </div>
                                @if($order->payment_type === 'dp')
                                    <span class="caption" style="color: var(--muted);">(DP {{ $order->dp_percentage }}%)</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badge = match($order->status) {
                                        'pending' => 'badge-amber',
                                        'paid' => 'badge-moss',
                                        'completed' => 'badge-moss',
                                        'cancelled' => 'badge-danger',
                                        default => 'badge-muted'
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ $order->status_label }}</span>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('vendor.orders') }}" class="btn btn-secondary btn-sm">
                                    Tinjau
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 24px; color: var(--muted);">
                                Belum ada pesanan masuk untuk toko Anda saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Chart 1: Vendor Order Status (Donut)
        const statusCounts = {!! json_encode($statusCounts) !!};
        const ctxStatus = document.getElementById('vendorStatusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu (Pending)', 'Sudah Bayar (Paid)', 'Selesai (Completed)', 'Dibatalkan'],
                datasets: [{
                    data: [
                        statusCounts.pending || 0,
                        statusCounts.paid || 0,
                        statusCounts.completed || 0,
                        statusCounts.cancelled || 0
                    ],
                    backgroundColor: ['#D6BC87', '#55624A', '#9C4A26', '#A8321F'],
                    borderWidth: 2,
                    borderColor: '#FBF6EC'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'Work Sans', size: 10 },
                            color: '#3c2f24',
                            boxWidth: 10,
                            padding: 8
                        }
                    }
                },
                cutout: '68%'
            }
        });

        // Chart 2: Payment Type Comparison (Bar)
        const paymentStats = {!! json_encode($paymentTypeStats) !!};
        const ctxPayment = document.getElementById('vendorPaymentChart').getContext('2d');
        new Chart(ctxPayment, {
            type: 'bar',
            data: {
                labels: ['Pembayaran Lunas (100%)', 'Uang Muka (DP 30%/50%)'],
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: [
                        paymentStats.full_payment || 0,
                        paymentStats.dp || 0
                    ],
                    backgroundColor: ['#55624A', '#9C4A26'],
                    borderRadius: 4,
                    barPercentage: 0.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        ticks: { font: { family: 'Work Sans', size: 10 }, color: '#6b5a4b' },
                        grid: { display: false }
                    },
                    y: {
                        ticks: { font: { family: 'Work Sans', size: 10 }, color: '#6b5a4b', stepSize: 1 },
                        grid: { color: 'rgba(33, 23, 16, 0.06)' }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
