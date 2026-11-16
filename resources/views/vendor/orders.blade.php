@extends('layouts.app')

@section('title', 'Pesanan Masuk & Berjalan - ' . $vendor->vendor_name)

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Vendor Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Pesanan Aktif</span>
            <h1 class="headline-md">Pesanan Masuk & Berjalan</h1>
            <p class="body-sm">Kelola pesanan langsung dari pelanggan (B2C), pantau bukti pembayaran, dan konfirmasi jadwal pelaksanaan acara.</p>
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
                        <th>Bukti Pembayaran</th>
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
                                <div style="display: flex; gap: 4px; align-items: center; flex-wrap: wrap; margin: 3px 0;">
                                    @if($order->event_region)
                                        <span class="badge" style="background: #F1F5F9; color: #1E293B; font-size: 10.5px; padding: 1px 6px; border: 1px solid #E2E8F0;">
                                            📍 {{ $order->event_region }}
                                        </span>
                                    @endif
                                    @php $prox = $order->proximity; @endphp
                                    @if($prox)
                                        <span class="badge" style="background: {{ $prox['badge_bg'] }}; color: {{ $prox['badge_color'] }}; font-size: 10px; padding: 1px 6px; border: 1px solid {{ $prox['badge_border'] }};">
                                            {{ $prox['label'] }}
                                        </span>
                                    @endif
                                </div>
                                <div class="caption" style="max-width: 220px;">{{ $order->event_location }}</div>
                            </td>
                            <td>
                                <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                @if($order->payment_type === 'dp')
                                    <div class="caption" style="color: var(--color-highlight); font-weight: 600;">
                                        DP: Rp {{ number_format($order->dp_amount, 0, ',', '.') }}
                                    </div>
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
                                    $paymentStatusLabel = $hasPayment ? ucfirst($order->payment->payment_status) : ($isPaid ? 'Sudah Dibayar' : 'Belum Bayar');
                                @endphp

                                @if($isMidtrans)
                                    <button type="button" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; height: 30px; padding: 0 10px;"
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
                                        <i data-lucide="zap" style="width: 12px; height: 12px; color: #C5A059;"></i>
                                        <span>Midtrans</span>
                                    </button>
                                    <div class="caption" style="margin-top: 2px; color: var(--color-success-text); font-size: 10.5px;">Otomatis Lunas</div>
                                @elseif($isManual)
                                    <button type="button" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; height: 30px; padding: 0 10px;"
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
                                        <i data-lucide="image" style="width: 12px; height: 12px;"></i>
                                        <span>Lihat Bukti Bayar</span>
                                    </button>
                                    <div class="caption" style="margin-top: 2px; font-size: 10.5px;">{{ $order->payment->bank_name ?? 'Transfer' }}</div>
                                @else
                                    <span class="badge badge-muted" style="font-size: 10.5px; padding: 3px 8px;">
                                        Belum Ada Bukti
                                    </span>
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
                                        <form action="{{ route('vendor.order.status', $order->id) }}" method="POST" onsubmit="return confirmAction(event, this, 'Tolak dan batalkan pesanan #{{ $order->order_code }} ini?', 'Tolak Pesanan', 'Ya, Tolak');">
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
                                        <form action="{{ route('vendor.order.status', $order->id) }}" method="POST" onsubmit="return confirmAction(event, this, 'Tandai acara untuk pesanan #{{ $order->order_code }} ini telah selesai dilaksanakan?', 'Selesaikan Acara', 'Ya, Selesai');">
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
                            <td colspan="8" style="text-align: center; padding: 48px; color: var(--muted);">
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
