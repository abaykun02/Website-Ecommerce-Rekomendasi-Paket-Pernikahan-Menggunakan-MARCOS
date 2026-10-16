@extends('layouts.app')

@section('title', 'Checkout Pesanan Multi-Item - E-Marketplace')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 72px;">
    <!-- Page Header -->
    <div style="margin-bottom: 28px;">
        <a href="{{ route('customer.cart') }}" class="btn btn-tertiary" style="padding: 0; margin-bottom: 8px; display: inline-flex; align-items: center; gap: 4px; font-size: 13px;">
            &larr; Kembali ke Keranjang
        </a>
        <h1 class="headline-lg">Checkout & Pembayaran Pesanan</h1>
        <p class="body-sm">Lengkapi data jadwal dan lokasi acara Anda untuk menyelesaikan pesanan seluruh paket & layanan.</p>
    </div>

    <form action="{{ route('customer.cart.checkout.process') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 380px; gap: 32px; align-items: start;">
            <!-- Left: Form Input -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <!-- 1. Data Pemesan -->
                <div class="card-kabut" style="padding: 24px; border-radius: 14px;">
                    <h3 style="font-family: var(--font-heading); font-size: 1.2rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="user" style="width: 18px; height: 18px; color: var(--primary);"></i>
                        Informasi Kontak Pemesan
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label class="form-label">Nama Lengkap Pemesan <span style="color: var(--error);">*</span></label>
                            <input type="text" name="customer_name" class="form-input" value="{{ old('customer_name', $user->name) }}" required>
                        </div>
                        <div>
                            <label class="form-label">Nomor WhatsApp / HP <span style="color: var(--error);">*</span></label>
                            <input type="text" name="customer_phone" class="form-input" value="{{ old('customer_phone', $user->phone) }}" placeholder="Contoh: 08123456789" required>
                        </div>
                    </div>
                </div>

                <!-- 2. Jadwal & Lokasi Acara -->
                <div class="card-kabut" style="padding: 24px; border-radius: 14px;">
                    <h3 style="font-family: var(--font-heading); font-size: 1.2rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="calendar" style="width: 18px; height: 18px; color: var(--primary);"></i>
                        Jadwal & Lokasi Acara Pernikahan
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label class="form-label">Tanggal Pelaksanaan Acara <span style="color: var(--error);">*</span></label>
                            <input type="date" name="event_date" class="form-input" value="{{ old('event_date', date('Y-m-d', strtotime('+7 days'))) }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div>
                            <label class="form-label">Waktu Mulai Acara</label>
                            <input type="text" name="event_time" class="form-input" value="{{ old('event_time', '08:00 WIB') }}" placeholder="Contoh: 08:00 - 14:00 WIB">
                        </div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label class="form-label">Alamat Lengkap Venue / Lokasi Acara <span style="color: var(--error);">*</span></label>
                        <textarea name="event_location" class="form-textarea" rows="3" placeholder="Masukkan gedung, nama jalan, nomor rumah, kelurahan, kecamatan, dan kota..." required>{{ old('event_location', $user->address) }}</textarea>
                    </div>

                    <div>
                        <label class="form-label">Catatan Tambahan untuk Vendor (Opsional)</label>
                        <textarea name="notes" class="form-textarea" rows="2" placeholder="Contoh: Nuansa busana adat melayu hijau mint, dekorasi pelaminan rustic floral...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- 3. Metode Pembayaran -->
                <div class="card-kabut" style="padding: 24px; border-radius: 14px;">
                    <h3 style="font-family: var(--font-heading); font-size: 1.2rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="credit-card" style="width: 18px; height: 18px; color: var(--primary);"></i>
                        Skema Pembayaran Transaksi
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <label style="display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border: 1px solid var(--line); border-radius: 10px; cursor: pointer; background: #fff;" id="labelFull">
                            <input type="radio" name="payment_type" value="full_payment" checked style="margin-top: 3px;" onchange="updatePaymentCalculation()">
                            <div>
                                <strong style="display: block; font-size: 0.95rem; color: var(--ink);">Pelunasan Penuh (100%)</strong>
                                <span class="caption" style="color: var(--muted);">Bayar lunas seluruh total biaya transaksi sekarang via Midtrans (BCA, Mandiri, BRI, QRIS, GoPay, Kartu Kredit).</span>
                            </div>
                        </label>

                        <label style="display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border: 1px solid var(--line); border-radius: 10px; cursor: pointer; background: #fff;" id="labelDp30">
                            <input type="radio" name="payment_type" value="dp" style="margin-top: 3px;" onchange="updatePaymentCalculation()">
                            <div style="width: 100%;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <strong style="font-size: 0.95rem; color: var(--ink);">Down Payment (DP) / Uang Muka</strong>
                                    <span class="badge badge-moss" style="font-size: 10px;">Rekomendasi</span>
                                </div>
                                <span class="caption" style="display: block; margin-bottom: 8px; color: var(--muted);">Kunci tanggal booking vendor dengan uang muka terlebih dahulu, pelunasan sebelum hari H.</span>
                                
                                <div id="dpPercentBox" style="display: none; align-items: center; gap: 12px; margin-top: 8px; padding-top: 8px; border-top: 1px dashed var(--line);">
                                    <span class="caption" style="font-weight: 600;">Pilih Besaran DP:</span>
                                    <label style="font-size: 13px; display: inline-flex; align-items: center; gap: 4px; cursor: pointer;">
                                        <input type="radio" name="dp_percentage" value="30" checked onchange="updatePaymentCalculation()"> DP 30%
                                    </label>
                                    <label style="font-size: 13px; display: inline-flex; align-items: center; gap: 4px; cursor: pointer;">
                                        <input type="radio" name="dp_percentage" value="50" onchange="updatePaymentCalculation()"> DP 50%
                                    </label>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right: Order Breakdown & Checkout Action -->
            <div class="card-kabut" style="padding: 24px; border-radius: 16px; position: sticky; top: 90px; background: #fff; box-shadow: var(--shadow-tier1);">
                <h3 style="font-family: var(--font-heading); font-size: 1.25rem; margin-bottom: 16px; border-bottom: 1px solid var(--line); padding-bottom: 12px;">
                    Item Pesanan ({{ $cartItems->sum('quantity') }})
                </h3>

                <div style="display: flex; flex-direction: column; gap: 12px; max-height: 240px; overflow-y: auto; margin-bottom: 16px; padding-right: 4px;">
                    @foreach($cartItems as $item)
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; font-size: 0.88rem; padding-bottom: 8px; border-bottom: 1px solid rgba(0,0,0,0.04);">
                            <div>
                                <div style="font-weight: 600; color: var(--ink);">{{ $item->resolved_name }}</div>
                                <div class="caption">{{ $item->quantity }}x @ Rp {{ number_format($item->resolved_price, 0, ',', '.') }}</div>
                            </div>
                            <div style="font-weight: 600; color: var(--ink); white-space: nowrap;">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="border-top: 1px solid var(--line); padding-top: 14px; margin-bottom: 14px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.9rem; color: var(--muted);">
                        <span>Total Nilai Layanan:</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.9rem; color: var(--muted);" id="dpRow" style="display: none;">
                        <span id="dpLabel">Uang Muka (DP):</span>
                        <span id="dpValue" style="color: var(--primary); font-weight: 600;">-</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-top: 10px; padding-top: 10px; border-top: 2px dashed var(--line); font-size: 1.05rem;">
                        <span style="font-weight: 700; color: var(--ink);">Harus Dibayar Sekarang:</span>
                        <span style="font-weight: 800; color: var(--primary);" id="totalPayableText">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 13px; font-size: 0.95rem; margin-top: 8px;">
                    <i data-lucide="credit-card" style="width: 16px; height: 16px;"></i>
                    Buat Pesanan & Bayar via Midtrans
                </button>

                <p class="caption" style="text-align: center; margin-top: 12px; line-height: 1.4;">
                    Dengan menekan tombol di atas, pesanan akan diteruskan ke vendor dan pop-up pembayaran otomatis Midtrans akan ditampilkan.
                </p>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const totalOrderAmount = {{ $subtotal }};

    function updatePaymentCalculation() {
        const paymentTypeRadio = document.querySelector('input[name="payment_type"]:checked');
        const dpPercentBox = document.getElementById('dpPercentBox');
        const dpRow = document.getElementById('dpRow');
        const dpLabel = document.getElementById('dpLabel');
        const dpValue = document.getElementById('dpValue');
        const totalPayableText = document.getElementById('totalPayableText');

        if (paymentTypeRadio && paymentTypeRadio.value === 'dp') {
            dpPercentBox.style.display = 'flex';
            dpRow.style.display = 'flex';

            const dpPercentRadio = document.querySelector('input[name="dp_percentage"]:checked');
            const percent = dpPercentRadio ? parseInt(dpPercentRadio.value) : 30;
            const dpAmount = Math.round((totalOrderAmount * percent) / 100);

            dpLabel.textContent = 'Uang Muka (DP ' + percent + '%):';
            dpValue.textContent = 'Rp ' + dpAmount.toLocaleString('id-ID');
            totalPayableText.textContent = 'Rp ' + dpAmount.toLocaleString('id-ID');
        } else {
            dpPercentBox.style.display = 'none';
            dpRow.style.display = 'none';
            totalPayableText.textContent = 'Rp ' + totalOrderAmount.toLocaleString('id-ID');
        }
    }

    document.addEventListener('DOMContentLoaded', updatePaymentCalculation);
</script>
@endpush
@endsection
