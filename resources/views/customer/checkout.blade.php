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

                    <!-- Wilayah / Kecamatan Acara -->
                    <div style="margin-bottom: 16px;">
                        <label class="form-label">Wilayah / Kecamatan / Kabupaten Acara <span style="color: var(--error);">*</span></label>
                        <select name="event_region" id="event_region_select" class="form-select" required onchange="updateLocationProximityMatch()">
                            <option value="">-- Pilih Wilayah / Kecamatan Acara --</option>
                            <option value="Tanjung Morawa" {{ old('event_region') === 'Tanjung Morawa' ? 'selected' : '' }}>Kec. Tanjung Morawa (Kab. Deli Serdang)</option>
                            <option value="Batang Kuis" {{ old('event_region') === 'Batang Kuis' ? 'selected' : '' }}>Kec. Batang Kuis (Kab. Deli Serdang)</option>
                            <option value="Lubuk Pakam" {{ old('event_region') === 'Lubuk Pakam' ? 'selected' : '' }}>Kec. Lubuk Pakam (Kab. Deli Serdang)</option>
                            <option value="Deli Serdang" {{ old('event_region') === 'Deli Serdang' ? 'selected' : '' }}>Kabupaten Deli Serdang (Umum / Sekitarnya)</option>
                            <option value="Medan" {{ old('event_region') === 'Medan' ? 'selected' : '' }}>Kota Medan</option>
                            <option value="Kisaran" {{ old('event_region') === 'Kisaran' ? 'selected' : '' }}>Kota Kisaran (Kab. Asahan)</option>
                            <option value="Binjai" {{ old('event_region') === 'Binjai' ? 'selected' : '' }}>Kota Binjai</option>
                            <option value="Tebing Tinggi" {{ old('event_region') === 'Tebing Tinggi' ? 'selected' : '' }}>Kota Tebing Tinggi</option>
                            <option value="Lainnya" {{ old('event_region') === 'Lainnya' ? 'selected' : '' }}>Wilayah Lainnya / Luar Kota</option>
                        </select>
                        <span class="caption" style="color: var(--muted); display: block; margin-top: 4px;">
                            Penting agar sistem mencocokkan kedekatan lokasi vendor & efisiensi transportasi.
                        </span>
                    </div>

                    <!-- Live Matching Kedekatan Lokasi Vendor -->
                    <div id="vendorProximityBox" style="margin-bottom: 16px; padding: 14px 16px; border-radius: 10px; background: #F8FAFC; border: 1px solid #E2E8F0; display: flex; align-items: flex-start; gap: 12px;">
                        <div id="proxIconContainer" style="width: 32px; height: 32px; border-radius: 8px; background: #E2E8F0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #475569;">
                            <i data-lucide="map-pin" style="width: 16px; height: 16px;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px; margin-bottom: 4px;">
                                <strong style="font-size: 13px; color: #1E293B;">
                                    Lokasi Vendor: {{ $primaryVendor?->vendor_name ?? 'Vendor' }} <span style="font-weight: 500; color: #64748B;">({{ $primaryVendor?->region ?? 'Deli Serdang' }})</span>
                                </strong>
                                <span id="proxBadge" class="badge" style="background: #E2E8F0; color: #475569; font-size: 11px;">
                                    Pilih Wilayah Acara
                                </span>
                            </div>
                            <div id="proxDescription" style="font-size: 12px; color: #64748B; line-height: 1.4;">
                                Pilih wilayah pelaksanaan acara di atas untuk mencocokkan estimasi jarak dan ongkir transportasi.
                            </div>
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
                                <span class="caption" style="color: var(--muted);">Bayar lunas seluruh total biaya transaksi melalui transfer bank manual atau opsi payment gateway otomatis.</span>
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
                    <i data-lucide="check-circle" style="width: 16px; height: 16px;"></i>
                    Konfirmasi & Buat Pesanan
                </button>

                <p class="caption" style="text-align: center; margin-top: 12px; line-height: 1.4;">
                    Pesanan Anda akan langsung diteruskan ke mitra vendor untuk diverifikasi. Anda dapat menyelesaikan pembayaran melalui transfer bank manual atau menggunakan payment gateway otomatis.
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
    const vendorRegion = "{{ $primaryVendor?->region ?? 'Deli Serdang' }}";

    function updateLocationProximityMatch() {
        const select = document.getElementById('event_region_select');
        const box = document.getElementById('vendorProximityBox');
        const badge = document.getElementById('proxBadge');
        const desc = document.getElementById('proxDescription');
        const iconContainer = document.getElementById('proxIconContainer');

        if (!select || !select.value) {
            badge.textContent = 'Pilih Wilayah Acara';
            badge.style.background = '#E2E8F0';
            badge.style.color = '#475569';
            desc.textContent = 'Pilih wilayah pelaksanaan acara di atas untuk mencocokkan estimasi jarak dan ongkir transportasi.';
            return;
        }

        const customerRegion = select.value;

        if (customerRegion === vendorRegion) {
            box.style.background = '#DCFCE7';
            box.style.borderColor = '#86EFAC';
            badge.textContent = 'Satu Wilayah / Sangat Dekat (< 5 km)';
            badge.style.background = '#166534';
            badge.style.color = '#FFFFFF';
            desc.innerHTML = '<strong style="color: #166534;">Sempurna!</strong> Lokasi acara berada di satu area utama vendor (<strong>' + vendorRegion + '</strong>). Bebas biaya transportasi luar kota & respon kedatangan tercepat.';
            iconContainer.style.background = '#BBF7D0';
            iconContainer.style.color = '#166534';
        } else if ((vendorRegion === 'Tanjung Morawa' && customerRegion === 'Batang Kuis') || 
                   (vendorRegion === 'Batang Kuis' && customerRegion === 'Tanjung Morawa') ||
                   (customerRegion === 'Deli Serdang' && (vendorRegion === 'Tanjung Morawa' || vendorRegion === 'Batang Kuis'))) {
            box.style.background = '#E0F2FE';
            box.style.borderColor = '#7DD3FC';
            badge.textContent = 'Wilayah Bersebelahan / Dekat (~10-15 km)';
            badge.style.background = '#0369A1';
            badge.style.color = '#FFFFFF';
            desc.innerHTML = 'Jarak sangat dekat antar-kecamatan di Deli Serdang. Akses jalan cepat dan efisien.';
            iconContainer.style.background = '#BAE6FD';
            iconContainer.style.color = '#0369A1';
        } else if ((vendorRegion === 'Medan' && (customerRegion === 'Tanjung Morawa' || customerRegion === 'Batang Kuis' || customerRegion === 'Deli Serdang')) ||
                   (customerRegion === 'Medan' && (vendorRegion === 'Tanjung Morawa' || vendorRegion === 'Batang Kuis' || vendorRegion === 'Deli Serdang'))) {
            box.style.background = '#FEF9C3';
            box.style.borderColor = '#FDE047';
            badge.textContent = 'Jarak Menengah (~20-25 km)';
            badge.style.background = '#854D0E';
            badge.style.color = '#FFFFFF';
            desc.innerHTML = 'Jarak menengah via jalan arteri / tol. Vendor dapat melayani acara Anda tepat waktu.';
            iconContainer.style.background = '#FEF08A';
            iconContainer.style.color = '#854D0E';
        } else if (vendorRegion === 'Kisaran' || customerRegion === 'Kisaran' || customerRegion === 'Lainnya') {
            box.style.background = '#FEE2E2';
            box.style.borderColor = '#FCA5A5';
            badge.textContent = 'Luar Kota / Jarak Jauh (> 100 km)';
            badge.style.background = '#991B1B';
            badge.style.color = '#FFFFFF';
            desc.innerHTML = '<strong style="color: #991B1B;">Luar Kota:</strong> Jarak vendor cukup jauh dari lokasi acara (~135-155 km). Tim vendor mungkin memerlukan biaya akomodasi/transportasi tambahan yang dapat dikoordinasikan via Chat.';
            iconContainer.style.background = '#FECACA';
            iconContainer.style.color = '#991B1B';
        } else {
            box.style.background = '#F1F5F9';
            box.style.borderColor = '#CBD5E1';
            badge.textContent = 'Jarak Regional Standar';
            badge.style.background = '#475569';
            badge.style.color = '#FFFFFF';
            desc.innerHTML = 'Vendor dapat menjangkau wilayah acara Anda dengan konfirmasi jadwal lebih awal.';
            iconContainer.style.background = '#E2E8F0';
            iconContainer.style.color = '#475569';
        }

        if (window.lucide) {
            lucide.createIcons();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updatePaymentCalculation();
        updateLocationProximityMatch();
    });
</script>
@endpush
@endsection
