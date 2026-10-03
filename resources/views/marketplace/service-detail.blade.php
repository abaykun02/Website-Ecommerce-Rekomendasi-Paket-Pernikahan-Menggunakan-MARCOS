@extends('layouts.app')

@section('title', $service->name . ' - ' . $service->vendor->vendor_name)

@section('content')
<div class="container-wide" style="padding-top: 40px; padding-bottom: 72px;">
    <!-- Breadcrumb (Gaya Kabut) -->
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px;" class="caption">
        <a href="{{ route('home') }}">Beranda</a>
        <i data-lucide="chevron-right" style="width: 12px; height: 12px;"></i>
        <a href="{{ route('services') }}">Kategori & Layanan</a>
        <i data-lucide="chevron-right" style="width: 12px; height: 12px;"></i>
        <span>{{ $service->category->name }}</span>
        <i data-lucide="chevron-right" style="width: 12px; height: 12px;"></i>
        <span style="color: var(--ink); font-weight: 600;">{{ $service->name }}</span>
    </div>

    <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 40px; margin-bottom: 48px;" class="detail-grid">
        <!-- Left: Image & Description -->
        <div>
            @php
                $mainImg = $service->image_url 
                    ?? $service->vendor->galleries->first()?->url 
                    ?? 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=80';
            @endphp
            <div style="margin-bottom: 20px; border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--line); position: relative; background: var(--line);">
                <img id="mainServiceImg" src="{{ $mainImg }}" alt="{{ $service->name }}" style="width: 100%; max-height: 480px; object-fit: cover; display: block;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=80';">
                <div style="position: absolute; top: 16px; left: 16px; display: flex; gap: 8px;">
                    <span class="badge badge-moss">{{ $service->category->name }}</span>
                    <span class="badge badge-primary">{{ $service->vendor->vendor_name }}</span>
                </div>
            </div>

            <!-- Description Card -->
            <div class="card-kabut" style="margin-bottom: 28px;">
                <h2 class="headline-sm" style="margin-bottom: 14px;">Deskripsi Layanan</h2>
                <div class="body-md" style="line-height: 1.7; white-space: pre-line; color: var(--body);">
                    {{ $service->description }}
                </div>
            </div>

            <!-- Customer Reviews Section -->
            <div class="card-kabut">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h3 class="headline-sm">Ulasan & Rating Vendor</h3>
                    <span class="badge badge-amber">
                        ★ {{ $service->vendor->averageRating() }} ({{ $service->vendor->reviews->count() }} ulasan)
                    </span>
                </div>

                @forelse($service->vendor->reviews as $rev)
                    <div style="border-bottom: 1px solid var(--line); padding: 14px 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <div style="font-weight: 600; font-size: 0.88rem; color: var(--ink);">{{ $rev->customer->name ?? 'Pelanggan Terverifikasi' }}</div>
                            <div style="color: var(--warning); font-size: 0.85rem;">
                                @for($i = 0; $i < $rev->rating; $i++) ★ @endfor
                            </div>
                        </div>
                        <p class="body-sm" style="color: var(--body);">{{ $rev->comment }}</p>
                        <div class="caption" style="margin-top: 4px;">{{ $rev->created_at->format('d M Y') }}</div>
                    </div>
                @empty
                    <p class="body-sm" style="color: var(--muted); font-style: italic;">Belum ada ulasan untuk vendor ini. Jadilah yang pertama memberikan ulasan setelah pemesanan!</p>
                @endforelse
            </div>
        </div>

        <!-- Right: Booking Form & Vendor Profile Card (Sticky) -->
        <div>
            <div class="card-kabut" style="position: sticky; top: 92px;">
                <!-- Vendor Info Header -->
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid var(--line);">
                    <img src="{{ $service->vendor->logo_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80' }}" 
                         alt="{{ $service->vendor->vendor_name }}" 
                         style="width: 48px; height: 48px; border-radius: var(--radius-sm); object-fit: cover; border: 1px solid var(--line);">
                    <div>
                        <div class="label-lg">{{ $service->vendor->vendor_name }}</div>
                        <div class="caption">
                            <i data-lucide="map-pin" style="width: 11px; height: 11px; vertical-align: middle;"></i>
                            {{ $service->vendor->city }} &bull; {{ $service->vendor->experience_years }} Tahun Pengalaman
                        </div>
                    </div>
                </div>

                <div class="caption" style="margin-bottom: 2px;">Harga Layanan Satuan:</div>
                <div class="price-lg" style="margin-bottom: 20px;">
                    Rp {{ number_format($service->price, 0, ',', '.') }}
                </div>

                @auth
                    @if(auth()->user()->isCustomer() || auth()->user()->isAdmin())
                        <form action="{{ route('customer.service.order.create', $service->id) }}" method="POST" id="checkoutServiceForm">
                            @csrf
                            
                            <div class="form-group" style="margin-bottom: 14px;">
                                <label class="form-label" for="customer_name">Nama Lengkap Pemesan *</label>
                                <input type="text" name="customer_name" id="customer_name" class="form-input" value="{{ old('customer_name', auth()->user()->name) }}" placeholder="Nama Anda" required>
                            </div>

                            <div class="form-group" style="margin-bottom: 14px;">
                                <label class="form-label" for="customer_phone">Nomor WhatsApp / HP Aktif *</label>
                                <input type="tel" name="customer_phone" id="customer_phone" class="form-input" value="{{ old('customer_phone', auth()->user()->phone) }}" placeholder="Contoh: 08123456789" required>
                            </div>

                            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 12px; margin-bottom: 14px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" for="event_date">Tanggal Acara *</label>
                                    <input type="date" name="event_date" id="event_date" class="form-input" min="{{ date('Y-m-d') }}" value="{{ old('event_date') }}" required>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" for="event_time">Jam Mulai *</label>
                                    <input type="text" name="event_time" id="event_time" class="form-input" placeholder="08:00 WIB" value="{{ old('event_time', '08:00 WIB') }}" required>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 14px;">
                                <label class="form-label" for="event_location">Alamat / Lokasi Lengkap Acara *</label>
                                <textarea name="event_location" id="event_location" class="form-textarea" rows="2" placeholder="Alamat rumah / nama gedung lokasi..." required>{{ old('event_location') }}</textarea>
                            </div>

                            <!-- Pilihan Tipe Pembayaran (Dropdown Rapi) -->
                            <div class="form-group" style="margin-bottom: 14px;">
                                <label class="form-label" for="payment_type_select">Opsi Pembayaran *</label>
                                <select name="payment_type" id="payment_type_select" class="form-select" onchange="togglePaymentDropdown()">
                                    <option value="full_payment">Pembayaran Penuh / Lunas 100%</option>
                                    <option value="dp">Uang Muka (Down Payment / DP)</option>
                                </select>
                            </div>

                            <!-- Opsi Persentase DP -->
                            <div id="dpOptionsWrapper" style="display: none; background: var(--cream); border-radius: var(--radius-sm); padding: 12px; margin-bottom: 14px; border: 1px dashed var(--line);">
                                <label class="form-label" for="dp_percent_select" style="font-size: 0.82rem; margin-bottom: 4px;">Pilih Nominal DP:</label>
                                <select name="dp_percentage" id="dp_percent_select" class="form-select" onchange="updatePriceCalculation()">
                                    <option value="30">DP 30% (Rp {{ number_format($service->price * 0.3, 0, ',', '.') }})</option>
                                    <option value="50">DP 50% (Rp {{ number_format($service->price * 0.5, 0, ',', '.') }})</option>
                                </select>
                            </div>

                            <!-- Ringkasan Tagihan Pembayaran -->
                            <div style="background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 14px; margin-bottom: 16px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                    <span class="caption">Total Biaya Layanan:</span>
                                    <span style="font-size: 0.9rem; font-weight: 600;">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--line); padding-top: 8px; margin-top: 6px;">
                                    <span class="label-md" style="color: var(--ink);">Tagihan Sekarang:</span>
                                    <span id="calculatedBillAmount" style="font-size: 1.15rem; font-weight: 700; color: var(--rust);">
                                        Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div id="remainingBillNotice" class="caption" style="display: none; color: var(--muted); margin-top: 4px; text-align: right;">
                                    Sisa pelunasan nanti: <strong id="remainingAmountText">Rp {{ number_format($service->price * 0.7, 0, ',', '.') }}</strong>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 16px;">
                                <label class="form-label" for="notes">Catatan Khusus Pemesan (Opsional)</label>
                                <textarea name="notes" id="notes" class="form-textarea" rows="2" placeholder="Contoh: Riasan wisuda jam 06.00 pagi, kebaya adat batak toba, dll...">{{ old('notes') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block" style="height: 48px;">
                                <i data-lucide="credit-card" style="width: 16px; height: 16px;"></i>
                                <span>Pesan & Bayar via Midtrans</span>
                            </button>

                            <div style="display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 10px;" class="caption">
                                <i data-lucide="shield-check" style="width: 13px; height: 13px; color: var(--moss);"></i>
                                <span>Didukung Pembayaran Digital Midtrans Snap</span>
                            </div>
                        </form>

                        <script>
                            const serviceBasePrice = {{ (float) $service->price }};

                            function formatRupiah(number) {
                                return 'Rp ' + Math.round(number).toLocaleString('id-ID');
                            }

                            function togglePaymentDropdown() {
                                const payType = document.getElementById('payment_type_select').value;
                                const dpWrapper = document.getElementById('dpOptionsWrapper');
                                const remainingNotice = document.getElementById('remainingBillNotice');

                                if (payType === 'dp') {
                                    dpWrapper.style.display = 'block';
                                    remainingNotice.style.display = 'block';
                                } else {
                                    dpWrapper.style.display = 'none';
                                    remainingNotice.style.display = 'none';
                                }
                                updatePriceCalculation();
                            }

                            function updatePriceCalculation() {
                                const payType = document.getElementById('payment_type_select').value;
                                const billEl = document.getElementById('calculatedBillAmount');
                                const remEl = document.getElementById('remainingAmountText');

                                if (payType === 'dp') {
                                    const percent = parseInt(document.getElementById('dp_percent_select').value) || 30;
                                    const dpVal = serviceBasePrice * (percent / 100);
                                    const remVal = serviceBasePrice - dpVal;
                                    billEl.textContent = formatRupiah(dpVal);
                                    remEl.textContent = formatRupiah(remVal);
                                } else {
                                    billEl.textContent = formatRupiah(serviceBasePrice);
                                    remEl.textContent = formatRupiah(0);
                                }
                            }
                        </script>
                    @else
                        <div style="background: var(--cream); border-radius: var(--radius-sm); padding: 18px; text-align: center; border: 1px solid var(--line);">
                            <p class="body-sm" style="margin-bottom: 12px;">Akun Vendor tidak dapat memesan layanan. Masuk sebagai Customer untuk memesan.</p>
                            <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">Kembali ke Beranda</a>
                        </div>
                    @endif
                @else
                    <div style="background: var(--cream); border-radius: var(--radius-sm); padding: 22px; text-align: center; border: 1px solid var(--line);">
                        <p class="body-sm" style="margin-bottom: 16px;">Silakan masuk atau buat akun pelanggan untuk memesan layanan ini.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-block" style="margin-bottom: 8px;">
                            Masuk untuk Memesan
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-secondary btn-block">
                            Daftar Akun Baru
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
