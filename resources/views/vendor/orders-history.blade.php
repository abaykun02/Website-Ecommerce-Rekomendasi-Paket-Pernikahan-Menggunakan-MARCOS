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
                        <th>Total & Pembayaran</th>
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
                                @php
                                    $hasPayment = (bool) $order->payment;
                                    $isMidtrans = $hasPayment && ($order->payment->payment_type === 'midtrans' || !empty($order->payment->transaction_id) || !empty($order->midtrans_transaction_id));
                                    $isManual = $hasPayment && !$isMidtrans && !empty($order->payment->proof_image);
                                    
                                    $paymentDate = $hasPayment 
                                        ? ($order->payment->verified_at ? $order->payment->verified_at->format('d M Y, H:i') . ' WIB' : $order->payment->created_at->format('d M Y, H:i') . ' WIB')
                                        : ($order->paid_at ? \Carbon\Carbon::parse($order->paid_at)->format('d M Y, H:i') . ' WIB' : '-');
                                        
                                    $bankInfo = $isManual ? ($order->payment->bank_name . ' a.n. ' . $order->payment->account_name) : ($isMidtrans ? 'Payment Gateway Midtrans' : '-');
                                    $proofUrl = $isManual ? $order->payment->proof_url : '';
                                    $paymentStatusLabel = $hasPayment ? ucfirst($order->payment->payment_status) : 'Lunas';
                                @endphp

                                @if($isMidtrans)
                                    <div style="margin-top: 4px;">
                                        <button type="button" class="btn btn-secondary btn-sm" style="font-size: 10.5px; height: 26px; padding: 0 8px; display: inline-flex; align-items: center; gap: 4px;"
                                                onclick="openVendorPaymentModal({
                                                    code: '{{ $order->order_code }}',
                                                    customer: '{{ $order->customer_name ?: ($order->customer->name ?? '-') }}',
                                                    type: 'Midtrans Online Payment',
                                                    status: '{{ $paymentStatusLabel }}',
                                                    date: '{{ $paymentDate }}',
                                                    amount: 'Rp {{ number_format($order->total_price, 0, ',', '.') }}',
                                                    bank: '{{ $bankInfo }}',
                                                    proofUrl: '',
                                                    isMidtrans: true
                                                })">
                                            <i data-lucide="zap" style="width: 11px; height: 11px; color: #C5A059;"></i>
                                            Midtrans
                                        </button>
                                    </div>
                                @elseif($isManual)
                                    <div style="margin-top: 4px;">
                                        <button type="button" class="btn btn-primary btn-sm" style="font-size: 10.5px; height: 26px; padding: 0 8px; display: inline-flex; align-items: center; gap: 4px;"
                                                onclick="openVendorPaymentModal({
                                                    code: '{{ $order->order_code }}',
                                                    customer: '{{ $order->customer_name ?: ($order->customer->name ?? '-') }}',
                                                    type: 'Transfer Bank Manual',
                                                    status: '{{ $paymentStatusLabel }}',
                                                    date: '{{ $paymentDate }}',
                                                    amount: 'Rp {{ number_format($order->total_price, 0, ',', '.') }}',
                                                    bank: '{{ $bankInfo }}',
                                                    proofUrl: '{{ $proofUrl }}',
                                                    isMidtrans: false
                                                })">
                                            <i data-lucide="image" style="width: 11px; height: 11px;"></i>
                                            Bukti Bayar
                                        </button>
                                    </div>
                                @endif
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

<!-- =========================================================================
     MODAL POPUP: BUKTI PEMBAYARAN PELANGGAN (VENDOR VIEW)
     ========================================================================= -->
<div id="vendorPaymentModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(26, 43, 40, 0.65); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div class="card-kabut" style="background: #FFFFFF; border-radius: 16px; max-width: 520px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 28px; box-shadow: 0 20px 50px rgba(0,0,0,0.25); position: relative;" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 1px solid var(--border-gold-subtle); padding-bottom: 14px;">
            <div>
                <span class="badge badge-accent" style="margin-bottom: 6px;">Bukti Pembayaran Pelanggan</span>
                <h3 id="vendorModalOrderCode" style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 600; color: var(--emerald-deep); margin: 0;">
                    Pesanan #
                </h3>
            </div>
            <button type="button" onclick="closeVendorPaymentModal()" style="background: none; border: none; font-size: 24px; line-height: 1; cursor: pointer; color: var(--muted);">&times;</button>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px; background: var(--surface-low); padding: 16px; border-radius: 12px; border: 1px solid var(--border-gold-subtle);">
            <div>
                <div class="caption">Nama Pemesan</div>
                <strong id="vendorModalCustomerName" style="font-size: 13px; color: var(--ink);">-</strong>
            </div>
            <div>
                <div class="caption">Metode Pembayaran</div>
                <strong id="vendorModalPaymentType" style="font-size: 13px; color: var(--ink);">-</strong>
            </div>
            <div>
                <div class="caption">Waktu / Tanggal Bayar</div>
                <strong id="vendorModalPaymentDate" style="font-size: 12px; color: var(--ink);">-</strong>
            </div>
            <div>
                <div class="caption">Total Tagihan</div>
                <strong id="vendorModalPaymentAmount" style="font-size: 14px; color: var(--rust); font-weight: 700;">-</strong>
            </div>
            <div style="grid-column: 1 / -1;">
                <div class="caption">Keterangan / Rekening Transfer</div>
                <span id="vendorModalBankInfo" style="font-size: 12.5px; color: var(--body); font-weight: 500;">-</span>
            </div>
        </div>

        <!-- Preview Bukti Transfer Manual -->
        <div id="vendorModalProofImageSection" style="margin-bottom: 20px; display: none;">
            <div class="caption" style="margin-bottom: 8px; font-weight: 600; color: var(--ink);">Foto Struk / Bukti Transfer:</div>
            <div style="border-radius: 10px; overflow: hidden; border: 1px solid var(--border-gold-medium); background: #FAF8F5; text-align: center; max-height: 320px; display: flex; align-items: center; justify-content: center;">
                <img id="vendorModalProofImg" src="" alt="Bukti Transfer" style="max-width: 100%; max-height: 320px; object-fit: contain; display: block;" onerror="this.src='https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=600&q=80';">
            </div>
            <div style="text-align: right; margin-top: 6px;">
                <a id="vendorModalProofExternalLink" href="#" target="_blank" class="caption" style="color: var(--primary); text-decoration: underline; font-weight: 600;">
                    Buka Gambar Ukuran Penuh &rarr;
                </a>
            </div>
        </div>

        <!-- Notifikasi Khusus Midtrans -->
        <div id="vendorModalMidtransSection" style="display: none; margin-bottom: 20px; padding: 14px; background: #E8F5EF; border-radius: 10px; border: 1px solid rgba(30,107,82,0.3); text-align: center;">
            <i data-lucide="shield-check" style="width: 24px; height: 24px; color: #1E6B52; margin: 0 auto 6px;"></i>
            <div style="font-size: 13px; font-weight: 600; color: #1E6B52;">Pembayaran Otomatis Midtrans Gateway</div>
            <div class="caption" style="color: #2D5A4C; margin-top: 2px;">Tagihan ini telah diverifikasi lunas secara langsung melalui sistem Payment Gateway Midtrans.</div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 8px; padding-top: 14px; border-top: 1px solid var(--border-gold-subtle);">
            <button type="button" onclick="closeVendorPaymentModal()" class="btn btn-secondary btn-sm" style="height: 36px; padding: 0 18px;">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openVendorPaymentModal(data) {
        document.getElementById('vendorModalOrderCode').innerText = 'Pesanan #' + data.code;
        document.getElementById('vendorModalCustomerName').innerText = data.customer;
        document.getElementById('vendorModalPaymentType').innerText = data.type;
        document.getElementById('vendorModalPaymentDate').innerText = data.date;
        document.getElementById('vendorModalPaymentAmount').innerText = data.amount;
        document.getElementById('vendorModalBankInfo').innerText = data.bank;

        const proofSection = document.getElementById('vendorModalProofImageSection');
        const midtransSection = document.getElementById('vendorModalMidtransSection');

        if (data.isMidtrans) {
            proofSection.style.display = 'none';
            midtransSection.style.display = 'block';
        } else if (data.proofUrl) {
            proofSection.style.display = 'block';
            midtransSection.style.display = 'none';
            document.getElementById('vendorModalProofImg').src = data.proofUrl;
            document.getElementById('vendorModalProofExternalLink').href = data.proofUrl;
        } else {
            proofSection.style.display = 'none';
            midtransSection.style.display = 'none';
        }

        const modal = document.getElementById('vendorPaymentModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        if (window.lucide) { lucide.createIcons(); }
    }

    function closeVendorPaymentModal() {
        document.getElementById('vendorPaymentModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Close on backdrop click
    document.addEventListener('click', function(e) {
        const vModal = document.getElementById('vendorPaymentModal');
        if (e.target === vModal) { closeVendorPaymentModal(); }
    });
</script>
@endpush
@endsection
