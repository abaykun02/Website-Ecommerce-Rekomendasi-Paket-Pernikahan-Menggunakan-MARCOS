@extends('layouts.app')

@section('title', $bundling->package_name . ' - ' . $bundling->vendor->vendor_name)

@section('content')
<div class="container-wide" style="padding-top: 40px; padding-bottom: 72px;">
    <!-- Breadcrumb (Gaya Kabut) -->
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px;" class="caption">
        <a href="{{ route('home') }}">Beranda</a>
        <i data-lucide="chevron-right" style="width: 12px; height: 12px;"></i>
        <a href="{{ route('bundlings') }}">Paket Bundling</a>
        <i data-lucide="chevron-right" style="width: 12px; height: 12px;"></i>
        <span style="color: var(--ink); font-weight: 600;">{{ $bundling->package_name }}</span>
    </div>

    <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 40px; margin-bottom: 48px;" class="detail-grid">
        <!-- Left: Image Gallery & Details -->
        <div>
            @php
                $mainImg = $bundling->galleries->first()?->url 
                    ?? $bundling->vendor->galleries->first()?->url 
                    ?? 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=80';
            @endphp
            <div style="margin-bottom: 16px; border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--line); background: var(--line);">
                <img id="mainGalleryImg" src="{{ $mainImg }}" alt="{{ $bundling->package_name }}" style="width: 100%; max-height: 480px; object-fit: cover; display: block;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=80';">
            </div>

            <!-- Thumbnail Selector -->
            @if($bundling->vendor->galleries->count() > 1 || $bundling->galleries->count() > 0)
                <div style="display: flex; gap: 10px; overflow-x: auto; padding-bottom: 12px; margin-bottom: 32px;">
                    @foreach($bundling->galleries->concat($bundling->vendor->galleries)->take(5) as $gal)
                        <img src="{{ $gal->url }}" alt="Thumbnail" style="width: 80px; height: 60px; object-fit: cover; border-radius: var(--radius-sm); cursor: pointer; border: 2px solid transparent;" onclick="document.getElementById('mainGalleryImg').src='{{ $gal->url }}'; this.style.borderColor='var(--rust)';" onmouseout="this.style.borderColor='transparent'">
                    @endforeach
                </div>
            @endif

            <!-- Package Description Card -->
            <div class="card-kabut" style="margin-bottom: 28px;">
                <h2 class="headline-sm" style="margin-bottom: 14px;">Deskripsi & Rincian Fasilitas</h2>
                <div class="body-md" style="line-height: 1.7; white-space: pre-line; color: var(--body);">
                    {{ $bundling->description }}
                </div>
            </div>

            <!-- SPK MARCOS Recommendation Evaluation (Formal Narasi Pelanggan) -->
            <div class="card-kabut" style="margin-bottom: 28px; background: var(--paper);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <span class="caption" style="text-transform: uppercase; letter-spacing: 0.04em; font-weight: 600; color: var(--moss); display: block; margin-bottom: 2px;">
                            Evaluasi Sistem Pendukung Keputusan
                        </span>
                        <h3 class="headline-sm">Rekomendasi Algoritma MARCOS</h3>
                    </div>
                    @if($marcosInfo)
                        @php
                            $r = $marcosInfo['rank'];
                            $bClass = match($r) {
                                1 => 'badge-narrative-gold',
                                2 => 'badge-narrative-moss',
                                default => 'badge-narrative-soft',
                            };
                            $rText = match($r) {
                                1 => 'Peringkat ke-1 Terbaik (Rekomendasi Utama)',
                                2 => 'Peringkat ke-2 Terbaik dari Lainnya',
                                3 => 'Peringkat ke-3 Pilihan Favorit',
                                default => 'Alternatif Rekomendasi Peringkat ke-' . $r,
                            };
                        @endphp
                        <div>
                            <span class="{{ $bClass }}">
                                {{ $rText }}
                            </span>
                        </div>
                    @endif
                </div>

                <p class="body-sm" style="line-height: 1.6; margin-bottom: 16px;">
                    Paket ini telah dievaluasi melalui 7 kriteria multi-dimensi (kualitas riasan, estetika desain dekorasi, kelengkapan item, kepuasan ulasan, harga paket, lokasi respon, dan kualitas kosmetik).
                </p>

                <!-- Tombol Info (!) untuk Lihat Detail Perhitungan -->
                <div style="border-top: 1px solid var(--line); padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <span class="caption">Ingin memeriksa transparansi nilai kriteria paket ini?</span>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="toggleSpkDetail()" style="display: inline-flex; align-items: center; gap: 6px;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; background: var(--moss); color: var(--cream); font-size: 11px; font-weight: 700;">!</span>
                        <span id="txtSpkDetail">Lihat Detail Kriteria</span>
                    </button>
                </div>

                <!-- Collapsible Detailed Criteria Scores -->
                <div id="spkDetailWrapper" style="display: none; margin-top: 16px; padding-top: 14px; border-top: 1px dashed var(--line);">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-bottom: 12px;">
                        @foreach($bundling->evaluations as $eval)
                            <div style="background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 8px 12px; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span style="font-size: 0.82rem; font-weight: 600; color: var(--ink);">{{ $eval->criteria->name }}</span>
                                    <div class="caption">{{ $eval->criteria->code }} ({{ ucfirst($eval->criteria->type) }})</div>
                                </div>
                                <span class="badge badge-moss" style="font-size: 0.78rem;">
                                    Skor {{ $eval->score }}/5
                                </span>
                            </div>
                        @endforeach
                    </div>
                    @if($marcosInfo)
                        <div class="caption" style="text-align: right;">
                            Nilai Utilitas Akhir $f(K_i)$: <strong>{{ number_format($marcosInfo['final_score'], 4) }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Reviews Section -->
            <div class="card-kabut">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h3 class="headline-sm">Ulasan & Penilaian Pelanggan</h3>
                    <span class="badge badge-amber">
                        ★ {{ $bundling->vendor->averageRating() }} ({{ $bundling->vendor->reviews->count() }} ulasan)
                    </span>
                </div>

                @forelse($bundling->vendor->reviews as $rev)
                    <div style="border-bottom: 1px solid var(--line); padding: 14px 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <div style="font-weight: 600; font-size: 0.88rem; color: var(--ink);">{{ $rev->customer->name ?? 'Pelanggan Terverifikasi' }}</div>
                            <div style="color: var(--warning); font-size: 0.85rem;">
                                @for($i=1; $i<=5; $i++)
                                    {{ $i <= $rev->rating ? '★' : '☆' }}
                                @endfor
                            </div>
                        </div>
                        <p class="body-sm" style="color: var(--body);">{{ $rev->comment }}</p>
                    </div>
                @empty
                    <p class="body-sm" style="color: var(--muted); font-style: italic;">Belum ada ulasan untuk vendor ini. Jadilah pemesan pertama!</p>
                @endforelse
            </div>
        </div>

        <!-- Right: Sticky Booking & Checkout Box -->
        <div>
            <div class="card-kabut" style="position: sticky; top: 92px;">
                <!-- Vendor Info Header -->
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid var(--line);">
                    <img src="{{ $bundling->vendor->logo_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80' }}" 
                         alt="{{ $bundling->vendor->vendor_name }}" 
                         style="width: 48px; height: 48px; border-radius: var(--radius-sm); object-fit: cover; border: 1px solid var(--line);">
                    <div>
                        <div class="label-lg">{{ $bundling->vendor->vendor_name }}</div>
                        <div class="caption">
                            <i data-lucide="map-pin" style="width: 11px; height: 11px; vertical-align: middle;"></i>
                            {{ $bundling->vendor->city }} &bull; {{ $bundling->vendor->experience_years }} Tahun Pengalaman
                        </div>
                    </div>
                </div>

                <div class="caption" style="margin-bottom: 2px;">Harga Paket Pernikahan:</div>
                <div class="price-lg" style="margin-bottom: 20px;">
                    Rp {{ number_format($bundling->base_price, 0, ',', '.') }}
                </div>

                @auth
                    @if(auth()->user()->isCustomer() || auth()->user()->isAdmin())
                        <!-- Quick Actions: Tambah Keranjang & Chat Vendor -->
                        <div style="display: flex; gap: 8px; margin-bottom: 20px;">
                            <button type="button" onclick="openAddToCartModal()" class="btn btn-secondary btn-block" style="flex: 1; height: 42px; justify-content: center; font-size: 13px;">
                                <i data-lucide="shopping-cart" style="width: 15px; height: 15px;"></i>
                                <span>+ Keranjang</span>
                            </button>
                            <a href="{{ route('customer.chat.start', $bundling->vendor_id) }}" class="btn btn-secondary" title="Chat Vendor Diskusi Paket" style="height: 42px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0 14px; font-size: 13px;">
                                <i data-lucide="message-circle" style="width: 15px; height: 15px; color: var(--primary);"></i>
                                <span>Chat Vendor</span>
                            </a>
                        </div>

                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; color: var(--muted); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                            <span style="flex: 1; height: 1px; background: var(--line);"></span>
                            <span>Atau Pesan Langsung</span>
                            <span style="flex: 1; height: 1px; background: var(--line);"></span>
                        </div>

                        <form action="{{ route('customer.order.create', $bundling->id) }}" method="POST" id="checkoutForm">
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
                                    <label class="form-label" for="event_time">Jam Mulai Acara *</label>
                                    <input type="text" name="event_time" id="event_time" class="form-input" placeholder="08:00 WIB" value="{{ old('event_time', '08:00 WIB') }}" required>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 14px;">
                                <label class="form-label" for="event_location">Alamat / Lokasi Lengkap Acara *</label>
                                <textarea name="event_location" id="event_location" class="form-textarea" rows="2" placeholder="Gedung / Rumah, Jalan, RT/RW, Kecamatan..." required>{{ old('event_location') }}</textarea>
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
                                    <option value="30">DP 30% (Rp {{ number_format($bundling->base_price * 0.3, 0, ',', '.') }})</option>
                                    <option value="50">DP 50% (Rp {{ number_format($bundling->base_price * 0.5, 0, ',', '.') }})</option>
                                </select>
                            </div>

                            <!-- Ringkasan Tagihan Pembayaran -->
                            <div style="background: var(--cream); border: 1px solid var(--line); border-radius: var(--radius-sm); padding: 14px; margin-bottom: 16px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                    <span class="caption">Total Harga Paket:</span>
                                    <span style="font-size: 0.9rem; font-weight: 600;">Rp {{ number_format($bundling->base_price, 0, ',', '.') }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--line); padding-top: 8px; margin-top: 6px;">
                                    <span class="label-md" style="color: var(--ink);">Tagihan Sekarang:</span>
                                    <span id="calculatedBillAmount" style="font-size: 1.15rem; font-weight: 700; color: var(--rust);">
                                        Rp {{ number_format($bundling->base_price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div id="remainingBillNotice" class="caption" style="display: none; color: var(--muted); margin-top: 4px; text-align: right;">
                                    Sisa pelunasan nanti: <strong id="remainingAmountText">Rp {{ number_format($bundling->base_price * 0.7, 0, ',', '.') }}</strong>
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 16px;">
                                <label class="form-label" for="notes">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" id="notes" class="form-textarea" rows="2" placeholder="Contoh: Konsep warna soft pastel, hijab syar'i, touch up 2x...">{{ old('notes') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block" style="height: 48px;">
                                <i data-lucide="credit-card" style="width: 16px; height: 16px;"></i>
                                <span>Pesan & Bayar Langsung</span>
                            </button>

                            <button type="button" onclick="openAddToCartModal()" class="btn btn-secondary btn-block" style="height: 44px; margin-top: 8px; justify-content: center; gap: 6px;">
                                <i data-lucide="shopping-cart" style="width: 15px; height: 15px;"></i>
                                <span>+ Masukkan ke Keranjang Terlebih Dahulu</span>
                            </button>

                            <div style="display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 10px;" class="caption">
                                <i data-lucide="shield-check" style="width: 13px; height: 13px; color: var(--moss);"></i>
                                <span>Didukung Pembayaran Digital Midtrans Snap</span>
                            </div>
                        </form>

                        <script>
                            const basePrice = {{ (float) $bundling->base_price }};

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
                                    const dpVal = basePrice * (percent / 100);
                                    const remVal = basePrice - dpVal;
                                    billEl.textContent = formatRupiah(dpVal);
                                    remEl.textContent = formatRupiah(remVal);
                                } else {
                                    billEl.textContent = formatRupiah(basePrice);
                                    remEl.textContent = formatRupiah(0);
                                }
                            }

                            function toggleSpkDetail() {
                                const el = document.getElementById('spkDetailWrapper');
                                const txt = document.getElementById('txtSpkDetail');
                                if (el.style.display === 'none') {
                                    el.style.display = 'block';
                                    txt.textContent = 'Sembunyikan Kriteria';
                                } else {
                                    el.style.display = 'none';
                                    txt.textContent = 'Lihat Detail Kriteria';
                                }
                            }

                            function openAddToCartModal() {
                                const modal = document.getElementById('addToCartModal');
                                if (modal) {
                                    // Salin data jika sudah diisi di form bawah
                                    const bDate = document.getElementById('event_date')?.value;
                                    const bLoc = document.getElementById('event_location')?.value;
                                    const bNotes = document.getElementById('notes')?.value;
                                    if (bDate && document.getElementById('cart_event_date')) {
                                        document.getElementById('cart_event_date').value = bDate;
                                    }
                                    if (bLoc && document.getElementById('cart_event_location')) {
                                        document.getElementById('cart_event_location').value = bLoc;
                                    }
                                    if (bNotes && document.getElementById('cart_notes')) {
                                        document.getElementById('cart_notes').value = bNotes;
                                    }
                                    modal.style.display = 'flex';
                                    modal.classList.add('show');
                                    document.body.style.overflow = 'hidden';
                                    if (window.lucide) lucide.createIcons();
                                }
                            }

                            function closeAddToCartModal() {
                                const modal = document.getElementById('addToCartModal');
                                if (modal) {
                                    modal.classList.remove('show');
                                    modal.style.display = 'none';
                                    document.body.style.overflow = '';
                                }
                            }

                            document.getElementById('addToCartModal')?.addEventListener('click', function(e) {
                                if (e.target === this) closeAddToCartModal();
                            });
                        </script>
                    @else
                        <div style="background: var(--cream); border-radius: var(--radius-sm); padding: 18px; text-align: center; border: 1px solid var(--line);">
                            <p class="body-sm" style="margin-bottom: 12px;">Akun Vendor tidak dapat memesan paket. Masuk sebagai Customer untuk memesan.</p>
                            <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">Kembali ke Beranda</a>
                        </div>
                    @endif
                @else
                    <div style="background: var(--cream); border-radius: var(--radius-sm); padding: 22px; text-align: center; border: 1px solid var(--line);">
                        <p class="body-sm" style="margin-bottom: 16px;">Silakan masuk atau buat akun pelanggan untuk memesan paket ini atau berkonsultasi dengan vendor.</p>
                        <a href="{{ route('customer.chat.start', $bundling->vendor_id) }}" class="btn btn-secondary btn-block" style="margin-bottom: 10px; justify-content: center; gap: 6px;">
                            <i data-lucide="message-circle" style="width: 15px; height: 15px; color: var(--primary);"></i>
                            <span>Chat Diskusi dengan Vendor</span>
                        </a>
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

<!-- Modal Tambah ke Keranjang (Harus Mengisi Data Acara Terlebih Dahulu) -->
@auth
<div id="addToCartModal" class="admin-modal-backdrop" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="cartModalTitle">
    <div class="admin-modal-box" style="max-width: 480px; text-align: left;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(142,60,0,0.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                    <i data-lucide="shopping-cart" style="width: 18px; height: 18px;"></i>
                </div>
                <div>
                    <h3 id="cartModalTitle" style="margin: 0; font-size: 1.1rem; font-weight: 700;">Tambah ke Keranjang</h3>
                    <div style="font-size: 11px; color: var(--muted);">Lengkapi rencana acara Anda sebelum disimpan</div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAddToCartModal()" style="padding: 4px 8px; height: 30px;" aria-label="Tutup">
                <i data-lucide="x" style="width: 16px; height: 16px;"></i>
            </button>
        </div>

        <form action="{{ route('customer.cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="bundling_id" value="{{ $bundling->id }}">

            <div style="background: var(--cream); border-radius: 8px; padding: 10px 14px; margin-bottom: 14px; border: 1px solid var(--line);">
                <div style="font-size: 11.5px; color: var(--muted);">Paket Pernikahan:</div>
                <div style="font-weight: 700; color: var(--ink); font-size: 14px;">{{ $bundling->package_name }}</div>
                <div style="color: var(--rust); font-weight: 700; font-size: 13.5px; margin-top: 2px;">Rp {{ number_format($bundling->base_price, 0, ',', '.') }}</div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" for="cart_event_date">Tanggal Rencana Acara *</label>
                <input type="date" name="event_date" id="cart_event_date" class="form-input" min="{{ date('Y-m-d') }}" required>
                <span class="caption" style="color: var(--muted); font-size: 11px; margin-top: 4px; display: block;">Wajib diisi agar vendor dapat memeriksa ketersediaan jadwal.</span>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" for="cart_event_location">Lokasi / Wilayah Acara</label>
                <input type="text" name="event_location" id="cart_event_location" class="form-input" placeholder="Contoh: Gedung Tiara Medan / Rumah Pengantin" value="{{ $bundling->vendor->city ?? '' }}">
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label" for="cart_quantity">Jumlah Paket</label>
                <input type="number" name="quantity" id="cart_quantity" class="form-input" min="1" max="10" value="1" required>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label" for="cart_notes">Catatan Kebutuhan / Preferensi (Opsional)</label>
                <textarea name="notes" id="cart_notes" class="form-textarea" rows="2" placeholder="Contoh: Riasan tema soft glam, request nuansa bunga pastel..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; padding-top: 12px; border-top: 1px solid var(--border);">
                <button type="button" class="btn btn-secondary" onclick="closeAddToCartModal()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="shopping-cart" style="width: 14px; height: 14px;"></i>
                    <span>Masukkan ke Keranjang</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endauth
@endsection
