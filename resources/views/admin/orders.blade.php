@extends('layouts.app')

@section('title', 'Monitoring Pemesanan Vendor - Admin Panel')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Admin Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <span class="badge badge-moss" style="margin-bottom: 6px;">Monitoring Transaksi B2C</span>
            <h1 class="headline-lg">Daftar Pemesanan Tiap Mitra Vendor</h1>
            <p class="body-sm">Pantau proses dan status pemesanan pelanggan ke masing-masing mitra vendor secara langsung dan transparan.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <span class="badge badge-accent" style="font-size: 11.5px; padding: 6px 12px;">
                <i data-lucide="shield-check" style="width: 14px; height: 14px;"></i>
                Mode Pengawasan (Read-Only)
            </span>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card-kabut" style="padding: 18px 22px; border-radius: 14px; margin-bottom: 20px; background: #FFFFFF;">
        <form method="GET" action="{{ route('admin.orders') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) auto; gap: 12px; align-items: end;">
            <!-- Filter Vendor -->
            <div>
                <label class="caption" style="display: block; margin-bottom: 4px; font-weight: 600; color: var(--ink);">Pilih Mitra Vendor:</label>
                <select name="vendor_id" class="input-dribbble" style="width: 100%; height: 38px; font-size: 13px;" onchange="this.form.submit()">
                    <option value="">-- Semua Mitra Vendor ({{ $vendors->count() }}) --</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>
                            {{ $v->vendor_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <label class="caption" style="display: block; margin-bottom: 4px; font-weight: 600; color: var(--ink);">Status Pemesanan:</label>
                <select name="status" class="input-dribbble" style="width: 100%; height: 38px; font-size: 13px;" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Menunggu Persetujuan Vendor</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>💬 Disetujui Vendor (Menunggu Bayar)</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>💳 Sudah Dibayar (Sedang Dipersiapkan)</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>✅ Acara Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>❌ Ditolak / Dibatalkan</option>
                </select>
            </div>

            <!-- Pencarian Kata Kunci -->
            <div>
                <label class="caption" style="display: block; margin-bottom: 4px; font-weight: 600; color: var(--ink);">Pencarian:</label>
                <div style="position: relative;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode pesanan / pelanggan..." class="input-dribbble" style="width: 100%; height: 38px; font-size: 13px; padding-left: 32px;">
                    <i data-lucide="search" style="position: absolute; left: 10px; top: 11px; width: 14px; height: 14px; color: var(--muted);"></i>
                </div>
            </div>

            <!-- Tombol Aksi Filter -->
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary btn-sm" style="height: 38px; padding: 0 16px;">
                    <i data-lucide="filter" style="width: 13px; height: 13px;"></i>
                    Filter
                </button>
                @if(request()->hasAny(['vendor_id', 'status', 'search']))
                    <a href="{{ route('admin.orders') }}" class="btn btn-secondary btn-sm" style="height: 38px; padding: 0 14px; color: var(--muted);" title="Reset Filter">
                        <i data-lucide="rotate-ccw" style="width: 13px; height: 13px;"></i>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table (Read-Only Monitoring) -->
    <div class="card-kabut" style="padding: 24px; border-radius: 14px; background: #FFFFFF;">
        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Mitra Vendor</th>
                        <th>Data Pelanggan</th>
                        <th>Paket / Layanan</th>
                        <th>Total Tagihan</th>
                        <th>Bukti / Pembayaran</th>
                        <th style="text-align: right;">Status Proses Pemesanan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong style="font-size: 13.5px; color: var(--ink);">{{ $order->order_code }}</strong>
                                <div class="caption">{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                                @if($order->event_date)
                                    <div class="caption" style="margin-top: 3px; color: var(--moss); font-weight: 600;">
                                        📅 Acara: {{ $order->event_date->format('d M Y') }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <span class="badge badge-moss" style="font-size: 11.5px; padding: 3px 8px; font-weight: 600;">
                                        🏢 {{ $order->vendor->vendor_name ?? '-' }}
                                    </span>
                                </div>
                                <div class="caption" style="margin-top: 3px;">
                                    {{ $order->vendor->phone ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div><strong>{{ $order->customer_name ?: ($order->customer->name ?? '-') }}</strong></div>
                                <div class="caption">{{ $order->customer_phone ?: ($order->customer->phone ?? '-') }}</div>
                                @if($order->event_region)
                                    <div style="margin-top: 3px;">
                                        <span class="badge" style="background: #F1F5F9; color: #1E293B; font-size: 10px; border: 1px solid #CBD5E1; padding: 1px 6px;">
                                            📍 {{ $order->event_region }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($order->bundling)
                                    <div><strong>{{ $order->bundling->package_name }}</strong></div>
                                    <div class="caption">Paket Bundling Utama</div>
                                @elseif($order->serviceItem)
                                    <div><strong>{{ $order->serviceItem->name }}</strong></div>
                                    <div class="caption">{{ $order->serviceItem->badge ?? 'Layanan' }}</div>
                                @elseif($order->items && $order->items->isNotEmpty())
                                    <div><strong>{{ $order->items->first()->item_name }}</strong></div>
                                    @if($order->items->count() > 1)
                                        <div class="caption" style="color: var(--primary);">+ {{ $order->items->count() - 1 }} item tambahan</div>
                                    @endif
                                @else
                                    <div><strong>Pesanan Layanan</strong></div>
                                @endif
                            </td>
                            <td>
                                <strong style="color: var(--ink);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                @if($order->payment_type === 'dp')
                                    <div class="caption" style="color: var(--color-highlight); font-weight: 600;">
                                        DP ({{ $order->dp_percentage ?? 30 }}%): Rp {{ number_format($order->dp_amount, 0, ',', '.') }}
                                    </div>
                                @else
                                    <div class="caption" style="color: var(--muted);">Pelunasan Penuh</div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $hasPayment = (bool) $order->payment;
                                    $isMidtrans = $hasPayment && ($order->payment->payment_type === 'midtrans' || !empty($order->payment->transaction_id) || !empty($order->midtrans_transaction_id));
                                    $isManual = $hasPayment && !$isMidtrans && !empty($order->payment->proof_image);
                                    $isPaid = $order->status === 'paid' || ($hasPayment && $order->payment->payment_status === 'verified');
                                    
                                    $paymentDate = $hasPayment 
                                        ? ($order->payment->verified_at ? $order->payment->verified_at->format('d M Y, H:i') . ' WIB' : $order->payment->created_at->format('d M Y, H:i') . ' WIB')
                                        : ($order->paid_at ? \Carbon\Carbon::parse($order->paid_at)->format('d M Y, H:i') . ' WIB' : '-');
                                        
                                    $bankInfo = $isManual ? ($order->payment->bank_name . ' a.n. ' . $order->payment->account_name) : ($isMidtrans ? 'Payment Gateway Midtrans' : '-');
                                    $proofUrl = $isManual ? $order->payment->proof_url : '';
                                    $paymentStatusLabel = $hasPayment ? ucfirst($order->payment->payment_status) : ($isPaid ? 'Verified' : 'Belum Bayar');
                                @endphp

                                @if($isMidtrans)
                                    <button type="button" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; font-size: 11.5px; height: 30px; padding: 0 10px;"
                                            onclick="openPaymentModal({
                                                code: '{{ $order->order_code }}',
                                                type: 'Midtrans Online Payment',
                                                status: '{{ $paymentStatusLabel }}',
                                                date: '{{ $paymentDate }}',
                                                amount: 'Rp {{ number_format($order->total_price, 0, ',', '.') }}',
                                                bank: '{{ $bankInfo }}',
                                                proofUrl: '',
                                                isMidtrans: true
                                            })">
                                        <i data-lucide="zap" style="width: 13px; height: 13px; color: #C5A059;"></i>
                                        <span>Midtrans Online</span>
                                    </button>
                                    <div class="caption" style="margin-top: 2px;">{{ $paymentDate }}</div>
                                @elseif($isManual)
                                    <button type="button" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; font-size: 11.5px; height: 30px; padding: 0 10px;"
                                            onclick="openPaymentModal({
                                                code: '{{ $order->order_code }}',
                                                type: 'Transfer Bank Manual',
                                                status: '{{ $paymentStatusLabel }}',
                                                date: '{{ $paymentDate }}',
                                                amount: 'Rp {{ number_format($order->total_price, 0, ',', '.') }}',
                                                bank: '{{ $bankInfo }}',
                                                proofUrl: '{{ $proofUrl }}',
                                                isMidtrans: false
                                            })">
                                        <i data-lucide="image" style="width: 13px; height: 13px; color: var(--emerald-deep);"></i>
                                        <span>Lihat Bukti Bayar</span>
                                    </button>
                                    <div class="caption" style="margin-top: 2px;">{{ $bankInfo }}</div>
                                @else
                                    <button type="button" class="badge badge-muted" style="border: none; cursor: pointer; padding: 4px 8px; font-size: 11px;"
                                            onclick="openPaymentModal({
                                                code: '{{ $order->order_code }}',
                                                type: 'Belum Ada Transaksi',
                                                status: 'Belum Dibayar',
                                                date: 'Menunggu proses pembayaran oleh pelanggan',
                                                amount: 'Rp {{ number_format($order->total_price, 0, ',', '.') }}',
                                                bank: '-',
                                                proofUrl: '',
                                                isMidtrans: false
                                            })">
                                        Belum Ada Bukti
                                    </button>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($order->status === 'pending')
                                    <span class="badge badge-amber" style="font-size: 11.5px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i data-lucide="clock" style="width: 13px; height: 13px;"></i>
                                        Menunggu Vendor
                                    </span>
                                @elseif($order->status === 'approved')
                                    <span class="badge badge-primary" style="font-size: 11.5px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i data-lucide="message-circle" style="width: 13px; height: 13px;"></i>
                                        Disetujui Vendor
                                    </span>
                                @elseif($order->status === 'paid')
                                    <span class="badge badge-accent" style="font-size: 11.5px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i data-lucide="credit-card" style="width: 13px; height: 13px;"></i>
                                        Sudah Dibayar
                                    </span>
                                @elseif($order->status === 'completed')
                                    <span class="badge badge-success" style="font-size: 11.5px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i data-lucide="check-circle" style="width: 13px; height: 13px;"></i>
                                        Acara Selesai
                                    </span>
                                @elseif($order->status === 'cancelled')
                                    <span class="badge badge-muted" style="font-size: 11.5px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i data-lucide="x-circle" style="width: 13px; height: 13px;"></i>
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="badge badge-muted">{{ $order->status_label }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px; color: var(--muted);">
                                Tidak ada data pemesanan yang sesuai dengan filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 18px;">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL POPUP: STATUS & BUKTI PEMBAYARAN (READ-ONLY)
     ========================================================================= -->
<div id="paymentModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(26, 43, 40, 0.65); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div class="card-kabut" style="background: #FFFFFF; border-radius: 16px; max-width: 520px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 28px; box-shadow: 0 20px 50px rgba(0,0,0,0.25); position: relative;" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 1px solid var(--border-gold-subtle); padding-bottom: 14px;">
            <div>
                <span class="badge badge-moss" style="margin-bottom: 6px;">Rincian Pembayaran Pelanggan</span>
                <h3 id="modalOrderCode" style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 600; color: var(--emerald-deep); margin: 0;">
                    Pesanan #
                </h3>
            </div>
            <button type="button" onclick="closePaymentModal()" style="background: none; border: none; font-size: 24px; line-height: 1; cursor: pointer; color: var(--muted);">&times;</button>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px; background: var(--surface-low); padding: 16px; border-radius: 12px; border: 1px solid var(--border-gold-subtle);">
            <div>
                <div class="caption">Metode Pembayaran</div>
                <strong id="modalPaymentType" style="font-size: 13px; color: var(--ink);">-</strong>
            </div>
            <div>
                <div class="caption">Status Pembayaran</div>
                <span id="modalPaymentStatusBadge" class="badge badge-accent" style="margin-top: 2px;">-</span>
            </div>
            <div>
                <div class="caption">Waktu / Tanggal</div>
                <strong id="modalPaymentDate" style="font-size: 12.5px; color: var(--ink);">-</strong>
            </div>
            <div>
                <div class="caption">Total Tagihan</div>
                <strong id="modalPaymentAmount" style="font-size: 14px; color: var(--rust); font-weight: 700;">-</strong>
            </div>
            <div style="grid-column: 1 / -1;">
                <div class="caption">Keterangan / Rekening Transfer</div>
                <span id="modalBankInfo" style="font-size: 12.5px; color: var(--body); font-weight: 500;">-</span>
            </div>
        </div>

        <!-- Preview Foto Bukti Transfer Manual -->
        <div id="modalProofImageSection" style="margin-bottom: 20px; display: none;">
            <div class="caption" style="margin-bottom: 8px; font-weight: 600; color: var(--ink);">Foto Struk / Bukti Transfer:</div>
            <div style="border-radius: 10px; overflow: hidden; border: 1px solid var(--border-gold-medium); background: #FAF8F5; text-align: center; max-height: 320px; display: flex; align-items: center; justify-content: center;">
                <img id="modalProofImg" src="" alt="Bukti Transfer" style="max-width: 100%; max-height: 320px; object-fit: contain; display: block;" onerror="this.src='https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=600&q=80';">
            </div>
            <div style="text-align: right; margin-top: 6px;">
                <a id="modalProofExternalLink" href="#" target="_blank" class="caption" style="color: var(--primary); text-decoration: underline; font-weight: 600;">
                    Buka Gambar Ukuran Penuh &rarr;
                </a>
            </div>
        </div>

        <!-- Notifikasi Khusus Midtrans -->
        <div id="modalMidtransSection" style="display: none; margin-bottom: 20px; padding: 14px; background: #E8F5EF; border-radius: 10px; border: 1px solid rgba(30,107,82,0.3); text-align: center;">
            <i data-lucide="shield-check" style="width: 24px; height: 24px; color: #1E6B52; margin: 0 auto 6px;"></i>
            <div style="font-size: 13px; font-weight: 600; color: #1E6B52;">Transaksi Terverifikasi Midtrans Snap</div>
            <div class="caption" style="color: #2D5A4C; margin-top: 2px;">Pembayaran diproses secara realtime dan otomatis diverifikasi langsung via Midtrans Payment Gateway.</div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 8px; padding-top: 14px; border-top: 1px solid var(--border-gold-subtle);">
            <button type="button" onclick="closePaymentModal()" class="btn btn-secondary btn-sm" style="height: 36px; padding: 0 18px;">
                Tutup Rincian
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openPaymentModal(data) {
        document.getElementById('modalOrderCode').innerText = 'Pesanan #' + data.code;
        document.getElementById('modalPaymentType').innerText = data.type;
        document.getElementById('modalPaymentStatusBadge').innerText = data.status;
        document.getElementById('modalPaymentDate').innerText = data.date;
        document.getElementById('modalPaymentAmount').innerText = data.amount;
        document.getElementById('modalBankInfo').innerText = data.bank;

        const proofSection = document.getElementById('modalProofImageSection');
        const midtransSection = document.getElementById('modalMidtransSection');

        if (data.isMidtrans) {
            proofSection.style.display = 'none';
            midtransSection.style.display = 'block';
        } else if (data.proofUrl) {
            proofSection.style.display = 'block';
            midtransSection.style.display = 'none';
            document.getElementById('modalProofImg').src = data.proofUrl;
            document.getElementById('modalProofExternalLink').href = data.proofUrl;
        } else {
            proofSection.style.display = 'none';
            midtransSection.style.display = 'none';
        }

        const modal = document.getElementById('paymentModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        if (window.lucide) { lucide.createIcons(); }
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Close on backdrop click
    document.addEventListener('click', function(e) {
        const pModal = document.getElementById('paymentModal');
        if (e.target === pModal) { closePaymentModal(); }
    });
</script>
@endpush
@endsection
