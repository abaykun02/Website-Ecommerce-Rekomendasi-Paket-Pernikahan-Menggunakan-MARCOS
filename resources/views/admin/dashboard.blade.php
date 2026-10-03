@extends('layouts.app')

@section('title', 'Admin Dashboard SPK - Glow & Grace')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Admin Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <span class="badge badge-moss" style="margin-bottom: 6px;">Konsol Administrator</span>
            <h1 class="headline-lg">Dashboard Kontrol & Analisis SPK</h1>
            <p class="body-sm">Ringkasan aktivitas platform, pemantauan transaksi pesanan, dan evaluasi rekomendasi MARCOS.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.spk') }}" class="btn btn-primary btn-sm">
                <i data-lucide="calculator" style="width: 14px; height: 14px;"></i>
                Laporan MARCOS
            </a>
            <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary btn-sm">
                <i data-lucide="external-link" style="width: 14px; height: 14px;"></i>
                Lihat Website
            </a>
        </div>
    </div>

    <!-- Navigation Chips Admin -->
    <div style="display: flex; gap: 8px; margin-bottom: 28px; border-bottom: 1px solid var(--line); padding-bottom: 12px; overflow-x: auto;">
        <a href="{{ route('admin.dashboard') }}" class="chip active">Ringkasan</a>
        <a href="{{ route('admin.criterias') }}" class="chip">Kriteria & Bobot (C1-C7)</a>
        <a href="{{ route('admin.evaluations') }}" class="chip">Matriks Evaluasi Alternatif</a>
        <a href="{{ route('admin.orders') }}" class="chip">Pesanan & Pembayaran ({{ $stats['pending_payments'] }} Pending)</a>
        <a href="{{ route('admin.spk') }}" class="chip">Laporan Lengkap MARCOS</a>
        <a href="{{ route('admin.users') }}" class="chip">Pengguna & Vendor</a>
    </div>

    <!-- Stats 6-Card Grid (Minimalist Scale) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; margin-bottom: 28px;">
        <div class="card-kabut" style="padding: 16px;">
            <div class="caption">Total Pengguna</div>
            <div style="font-size: 1.45rem; font-weight: 700; color: var(--ink); margin-top: 2px;">{{ $stats['total_users'] }}</div>
            <div class="caption">Customer & Vendor</div>
        </div>

        <div class="card-kabut" style="padding: 16px;">
            <div class="caption">Mitra Vendor</div>
            <div style="font-size: 1.45rem; font-weight: 700; color: var(--ink); margin-top: 2px;">{{ $stats['total_vendors'] }}</div>
            <div class="caption">Make-up & Dekorasi</div>
        </div>

        <div class="card-kabut" style="padding: 16px;">
            <div class="caption">Paket Alternatif</div>
            <div style="font-size: 1.45rem; font-weight: 700; color: var(--ink); margin-top: 2px;">{{ $stats['total_bundlings'] }}</div>
            <div class="caption">Dihitung di SPK</div>
        </div>

        <div class="card-kabut" style="padding: 16px;">
            <div class="caption">Total Pesanan</div>
            <div style="font-size: 1.45rem; font-weight: 700; color: var(--ink); margin-top: 2px;">{{ $stats['total_orders'] }}</div>
            <div class="caption">Semua transaksi</div>
        </div>

        <div class="card-kabut" style="padding: 16px;">
            <div class="caption">Menunggu Verifikasi</div>
            <div style="font-size: 1.45rem; font-weight: 700; color: var(--rust); margin-top: 2px;">{{ $stats['pending_payments'] }}</div>
            <div class="caption">Perlu tinjauan</div>
        </div>

        <div class="card-kabut" style="padding: 16px;">
            <div class="caption">Total Transaksi Sah</div>
            <div style="font-size: 1.15rem; font-weight: 700; color: var(--moss); margin-top: 4px;">
                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
            </div>
            <div class="caption">Status paid / completed</div>
        </div>
    </div>

    <!-- 2 GRAFIK ADMIN DASHBOARD (CHART JS) -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px;" class="grid-cols-2">
        <!-- Grafik 1: Bar Chart (Volume Pesanan per Vendor) -->
        <div class="card-kabut" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <div>
                    <h3 class="headline-sm" style="font-size: 0.98rem;">1. Volume Pesanan per Vendor Teratas</h3>
                    <div class="caption">Perbandingan jumlah booking pesanan yang masuk ke mitra</div>
                </div>
                <span class="badge badge-moss">Grafik Batang</span>
            </div>
            <div style="position: relative; height: 220px; width: 100%;">
                <canvas id="vendorOrdersChart"></canvas>
            </div>
        </div>

        <!-- Grafik 2: Donut Chart (Distribusi Status Pesanan) -->
        <div class="card-kabut" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <div>
                    <h3 class="headline-sm" style="font-size: 0.98rem;">2. Distribusi Status Transaksi Pesanan</h3>
                    <div class="caption">Persentase status pesanan di marketplace saat ini</div>
                </div>
                <span class="badge badge-rust">Grafik Donat</span>
            </div>
            <div style="position: relative; height: 220px; width: 100%;">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Sections: Recent Orders & Top 5 SPK Rankings -->
    <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 24px;" class="grid-cols-2">
        <!-- Recent Orders Table (Wrapped to Never Overflow) -->
        <div class="card-kabut" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <h3 class="headline-sm" style="font-size: 0.98rem;">Pesanan Terbaru Masuk</h3>
                <a href="{{ route('admin.orders') }}" class="btn-tertiary">Kelola Semua &rarr;</a>
            </div>

            <div class="table-responsive">
                <table class="table-kabut">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>Vendor</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td><strong style="color: var(--ink);">{{ $order->order_code }}</strong></td>
                                <td>{{ Str::limit($order->customer_name ?: ($order->customer->name ?? '-'), 14) }}</td>
                                <td>{{ Str::limit($order->vendor->vendor_name, 16) }}</td>
                                <td>
                                    <span style="color: var(--rust); font-weight: 600;">
                                        Rp {{ number_format($order->payable_amount ?: $order->total_price, 0, ',', '.') }}
                                    </span>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px; color: var(--muted);">Belum ada transaksi pesanan tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top 5 MARCOS Rankings Card -->
        <div class="card-kabut" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <h3 class="headline-sm" style="font-size: 0.98rem;">Top 5 Rekomendasi MARCOS</h3>
                <a href="{{ route('admin.spk') }}" class="btn-tertiary">Matriks Lengkap &rarr;</a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 8px;">
                @foreach($topRankings as $rank)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; border-radius: var(--radius-sm); background: var(--cream); border: 1px solid var(--line);">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="badge {{ $rank['rank'] == 1 ? 'badge-rust' : 'badge-outline' }}" style="font-size: 0.72rem;">
                                #{{ $rank['rank'] }}
                            </span>
                            <div>
                                <div style="font-weight: 600; font-size: 0.82rem; color: var(--ink);">{{ Str::limit($rank['package_name'], 22) }}</div>
                                <div class="caption">{{ Str::limit($rank['vendor_name'], 18) }}</div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span class="badge badge-moss" style="font-size: 0.72rem;">
                                f(Ki): {{ number_format($rank['final_score'], 4) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Chart 1: Vendor Orders Bar Chart
        const vendorLabels = {!! json_encode($vendorOrderStats->pluck('name')) !!};
        const vendorData = {!! json_encode($vendorOrderStats->pluck('orders_count')) !!};

        const ctxVendor = document.getElementById('vendorOrdersChart').getContext('2d');
        new Chart(ctxVendor, {
            type: 'bar',
            data: {
                labels: vendorLabels.length ? vendorLabels : ['Dyra Erlia', 'Syarifah', 'Adillaa', 'Feeza', 'Alesya'],
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: vendorData.length ? vendorData : [3, 2, 2, 1, 1],
                    backgroundColor: '#55624A',
                    borderRadius: 4,
                    barPercentage: 0.55
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#211710',
                        titleFont: { family: 'Work Sans', size: 12 },
                        bodyFont: { family: 'Work Sans', size: 11 }
                    }
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

        // Chart 2: Order Status Doughnut Chart
        const statusCounts = {!! json_encode($statusCounts) !!};
        const ctxStatus = document.getElementById('orderStatusChart').getContext('2d');
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
                            font: { family: 'Work Sans', size: 11 },
                            color: '#3c2f24',
                            boxWidth: 12,
                            padding: 10
                        }
                    },
                    tooltip: {
                        backgroundColor: '#211710',
                        titleFont: { family: 'Work Sans', size: 12 },
                        bodyFont: { family: 'Work Sans', size: 11 }
                    }
                },
                cutout: '68%'
            }
        });
    });
</script>
@endpush
@endsection
