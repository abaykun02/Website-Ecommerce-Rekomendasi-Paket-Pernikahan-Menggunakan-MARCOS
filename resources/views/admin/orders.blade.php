@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran & Pesanan - Admin Panel')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Admin Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Manajemen Transaksi</span>
            <h1 class="headline-lg">Pesanan & Verifikasi Pembayaran</h1>
            <p class="body-sm">Periksa struk transfer bank pelanggan dan verifikasi status pembayaran marketplace.</p>
        </div>
    </div>

    <!-- Admin Navigation Chips -->
    <div style="display: flex; gap: 10px; margin-bottom: 32px; border-bottom: 1px solid var(--color-border); padding-bottom: 12px; overflow-x: auto;">
        <a href="{{ route('admin.dashboard') }}" class="chip">Ringkasan</a>
        <a href="{{ route('admin.criterias') }}" class="chip">Kriteria & Bobot (C1-C7)</a>
        <a href="{{ route('admin.evaluations') }}" class="chip">Matriks Evaluasi Alternatif</a>
        <a href="{{ route('admin.orders') }}" class="chip active">Pesanan & Pembayaran</a>
        <a href="{{ route('admin.spk') }}" class="chip">Laporan Lengkap MARCOS</a>
        <a href="{{ route('admin.users') }}" class="chip">Pengguna & Vendor</a>
    </div>

    <!-- Orders Table -->
    <div class="card-dribbble" style="padding: 24px; border-radius: 14px;">
        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Vendor & Paket</th>
                        <th>Total Tagihan</th>
                        <th>Status Order</th>
                        <th>Bukti Pembayaran</th>
                        <th style="text-align: right;">Aksi Verifikasi</th>
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
                                <div><strong>{{ $order->customer_name ?: ($order->customer->name ?? '-') }}</strong></div>
                                <div class="caption">{{ $order->customer_phone ?: ($order->customer->phone ?? '-') }} &bull; {{ $order->customer->email ?? '' }}</div>
                            </td>
                            <td>
                                @if($order->bundling)
                                    <div><strong>{{ $order->bundling->package_name }}</strong></div>
                                    <div class="caption">Paket Bundling &bull; Vendor: {{ $order->vendor->vendor_name }}</div>
                                @elseif($order->serviceItem)
                                    <div><strong>{{ $order->serviceItem->name }}</strong></div>
                                    <div class="caption">{{ $order->serviceItem->badge ?? 'Layanan' }} &bull; Vendor: {{ $order->vendor->vendor_name }}</div>
                                @else
                                    <div><strong>Pesanan Layanan</strong></div>
                                    <div class="caption">Vendor: {{ $order->vendor->vendor_name }}</div>
                                @endif
                            </td>
                            <td>
                                <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                @if($order->payment_type === 'dp')
                                    <div class="caption" style="color: var(--color-highlight);">DP: Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</div>
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
                                        
                                    $paymentMethodLabel = $isMidtrans ? 'Midtrans Online Payment' : ($isManual ? 'Transfer Bank Manual' : 'Belum Ada Transaksi');
                                    $bankInfo = $isManual ? ($order->payment->bank_name . ' a.n. ' . $order->payment->account_name) : ($isMidtrans ? 'Payment Gateway Midtrans' : '-');
                                    $proofUrl = $isManual ? $order->payment->proof_url : '';
                                    $paymentStatusLabel = $hasPayment ? ucfirst($order->payment->payment_status) : ($isPaid ? 'Verified' : 'Belum Bayar');
                                @endphp

                                @if($isMidtrans)
                                    <button type="button" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; font-size: 11.5px; height: 32px; padding: 0 10px;"
                                            onclick="openPaymentModal({
                                                code: '{{ $order->order_code }}',
                                                type: 'Midtrans Online',
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
                                    <div class="caption" style="margin-top: 3px; font-size: 11px;">{{ $paymentDate }}</div>
                                @elseif($isManual)
                                    <button type="button" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; font-size: 11.5px; height: 32px; padding: 0 10px;"
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
                                        <span>Bukti Transfer</span>
                                    </button>
                                    <div class="caption" style="margin-top: 3px; font-size: 11px;">{{ $bankInfo }}</div>
                                @else
                                    <button type="button" class="badge badge-muted" style="border: none; cursor: pointer; padding: 5px 10px;"
                                            onclick="openPaymentModal({
                                                code: '{{ $order->order_code }}',
                                                type: 'Manual / Online',
                                                status: 'Belum Dibayar',
                                                date: 'Belum ada transaksi',
                                                amount: 'Rp {{ number_format($order->total_price, 0, ',', '.') }}',
                                                bank: '-',
                                                proofUrl: '',
                                                isMidtrans: false
                                            })">
                                        Belum Ada Pembayaran
                                    </button>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($order->status === 'paid' || $order->status === 'completed')
                                    <span class="badge badge-success" style="font-size: 11px; padding: 4px 10px;">
                                        <i data-lucide="check-circle" style="width: 12px; height: 12px;"></i>
                                        Terverifikasi Sah
                                    </span>
                                @elseif($order->status === 'cancelled')
                                    <span class="badge badge-muted" style="font-size: 11px; padding: 4px 10px;">
                                        Ditolak
                                    </span>
                                @else
                                    <!-- Aksi Terima / Tolak dengan Modal Konfirmasi -->
                                    <div style="display: flex; gap: 6px; justify-content: flex-end; align-items: center;">
                                        <button type="button" class="btn btn-primary btn-sm" style="font-size: 11px; height: 30px; padding: 0 10px; background: var(--success); border-color: var(--success); color: #fff;"
                                                onclick="openConfirmOrderModal('accept', '{{ $order->id }}', '{{ $order->order_code }}', {{ $isPaid ? 'true' : 'false' }})">
                                            Terima
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm" style="font-size: 11px; height: 30px; padding: 0 10px; color: var(--error);"
                                                onclick="openConfirmOrderModal('reject', '{{ $order->id }}', '{{ $order->order_code }}', false)">
                                            Tolak
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 36px;">Belum ada data transaksi.</td>
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
     MODAL POPUP: STATUS & BUKTI PEMBAYARAN
     ========================================================================= -->
<div id="paymentModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(26, 43, 40, 0.65); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div class="card-kabut" style="background: #FFFFFF; border-radius: 16px; max-width: 520px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 28px; box-shadow: 0 20px 50px rgba(0,0,0,0.25); position: relative;" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 1px solid var(--border-gold-subtle); padding-bottom: 14px;">
            <div>
                <span class="badge badge-moss" style="margin-bottom: 6px;">Rincian Pembayaran</span>
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
                <div class="caption">Status Saat Ini</div>
                <span id="modalPaymentStatusBadge" class="badge badge-accent" style="margin-top: 2px;">-</span>
            </div>
            <div>
                <div class="caption">Waktu Pembayaran / Tanggal</div>
                <strong id="modalPaymentDate" style="font-size: 12.5px; color: var(--ink);">-</strong>
            </div>
            <div>
                <div class="caption">Total Tagihan</div>
                <strong id="modalPaymentAmount" style="font-size: 14px; color: var(--rust); font-weight: 700;">-</strong>
            </div>
            <div style="grid-column: 1 / -1;">
                <div class="caption">Keterangan / Rekening</div>
                <span id="modalBankInfo" style="font-size: 12.5px; color: var(--body); font-weight: 500;">-</span>
            </div>
        </div>

        <!-- Preview Foto Bukti Transfer Manual -->
        <div id="modalProofImageSection" style="margin-bottom: 20px; display: none;">
            <div class="caption" style="margin-bottom: 8px; font-weight: 600;">Foto Struk / Bukti Transfer:</div>
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
            <div style="font-size: 13px; font-weight: 600; color: #1E6B52;">Transaksi Terotentikasi Midtrans Snap</div>
            <div class="caption" style="color: #2D5A4C; margin-top: 2px;">Pembayaran diproses secara realtime dan otomatis disinkronkan melalui Midtrans Webhook Notification.</div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 8px; padding-top: 14px; border-top: 1px solid var(--border-gold-subtle);">
            <button type="button" onclick="closePaymentModal()" class="btn btn-secondary btn-sm" style="height: 36px; padding: 0 18px;">
                Tutup Rincian
            </button>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL POPUP: KONFIRMASI TERIMA / TOLAK PESANAN
     ========================================================================= -->
<div id="confirmOrderModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(26, 43, 40, 0.65); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div class="card-kabut" style="background: #FFFFFF; border-radius: 16px; max-width: 440px; width: 100%; padding: 28px; box-shadow: 0 20px 50px rgba(0,0,0,0.25); text-align: center;" onclick="event.stopPropagation()">
        <div id="confirmModalIcon" style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
            <i id="confirmModalIconLucide" data-lucide="help-circle" style="width: 26px; height: 26px;"></i>
        </div>

        <h3 id="confirmModalTitle" style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 600; margin-bottom: 8px; color: var(--emerald-deep);">
            Konfirmasi Pesanan
        </h3>

        <p id="confirmModalMessage" class="body-sm" style="margin-bottom: 24px; color: var(--body); line-height: 1.5;">
            Apakah Anda yakin ingin memproses pesanan ini?
        </p>

        <form id="confirmOrderForm" action="" method="POST" style="display: inline;">
            @csrf
            <input type="hidden" name="action" id="confirmActionInput" value="">
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" onclick="closeConfirmOrderModal()" class="btn btn-secondary" style="height: 38px; padding: 0 18px;">
                    Batal
                </button>
                <button type="submit" id="confirmSubmitBtn" class="btn btn-primary" style="height: 38px; padding: 0 20px;">
                    Ya, Lanjutkan
                </button>
            </div>
        </form>
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
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function openConfirmOrderModal(action, orderId, orderCode, isPaid) {
        const form = document.getElementById('confirmOrderForm');
        form.action = '/admin/pesanan/' + orderId + '/status';
        document.getElementById('confirmActionInput').value = action;

        const title = document.getElementById('confirmModalTitle');
        const msg = document.getElementById('confirmModalMessage');
        const iconContainer = document.getElementById('confirmModalIcon');
        const submitBtn = document.getElementById('confirmSubmitBtn');

        if (action === 'accept') {
            title.innerText = 'Konfirmasi Terima Pesanan';
            iconContainer.style.background = '#E8F5EF';
            iconContainer.style.color = '#1E6B52';
            submitBtn.style.background = 'var(--success)';
            submitBtn.style.borderColor = 'var(--success)';
            submitBtn.innerText = 'Ya, Terima Pesanan';

            if (!isPaid) {
                msg.innerHTML = '<strong>Perhatian:</strong> Pesanan <code>#' + orderCode + '</code> belum terverifikasi lunas. Apakah Anda yakin ingin tetap <strong>menerima dan mengesahkan</strong> pesanan ini sekarang?';
            } else {
                msg.innerHTML = 'Apakah Anda yakin ingin <strong>menerima dan mengesahkan</strong> pesanan <code>#' + orderCode + '</code>?';
            }
        } else {
            title.innerText = 'Konfirmasi Tolak Pesanan';
            iconContainer.style.background = '#FFDAD6';
            iconContainer.style.color = '#BA1A1A';
            submitBtn.style.background = 'var(--error)';
            submitBtn.style.borderColor = 'var(--error)';
            submitBtn.innerText = 'Ya, Tolak Pesanan';
            msg.innerHTML = 'Apakah Anda yakin ingin <strong>menolak</strong> pesanan <code>#' + orderCode + '</code>? Tindakan ini akan membatalkan transaksi.';
        }

        const modal = document.getElementById('confirmOrderModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        if (window.lucide) { lucide.createIcons(); }
    }

    function closeConfirmOrderModal() {
        document.getElementById('confirmOrderModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Close on backdrop click
    document.addEventListener('click', function(e) {
        const pModal = document.getElementById('paymentModal');
        if (e.target === pModal) { closePaymentModal(); }

        const cModal = document.getElementById('confirmOrderModal');
        if (e.target === cModal) { closeConfirmOrderModal(); }
    });
</script>
@endpush
@endsection
