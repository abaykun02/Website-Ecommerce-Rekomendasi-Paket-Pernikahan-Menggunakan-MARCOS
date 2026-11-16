@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_code . ' - Glow & Grace')

@section('content')
<div class="container" style="max-width: 900px; padding-top: 36px; padding-bottom: 64px;">
    <!-- Breadcrumb -->
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;" class="caption">
        <a href="{{ route('customer.orders') }}">Pesanan Saya</a>
        <i data-lucide="chevron-right" style="width: 12px; height: 12px;"></i>
        <span style="color: var(--color-primary); font-weight: 700;">{{ $order->order_code }}</span>
    </div>

    <!-- Order Header Card -->
    <div class="card-dribbble" style="padding: 28px; border-radius: 14px; margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 20px;">
            <div>
                <span class="badge badge-accent" style="margin-bottom: 6px;">Detail Transaksi</span>
                <h1 class="headline-md">{{ $order->order_code }}</h1>
                <div class="caption">Dipesan pada {{ $order->created_at->format('d F Y, H:i') }} WIB</div>
            </div>
            <div>
                @php
                    $badgeClass = match($order->status) {
                        'pending' => 'badge-amber',
                        'approved' => 'badge-primary',
                        'paid' => 'badge-accent',
                        'completed' => 'badge-success',
                        'cancelled' => 'badge-muted',
                        default => 'badge-muted',
                    };
                @endphp
                <span class="badge {{ $badgeClass }}" style="font-size: 13px; padding: 6px 14px;">
                    {{ $order->status_label }}
                </span>
            </div>
        </div>

        <!-- Order Information Grid -->
        <div class="grid-cols-2" style="gap: 20px; border-top: 1px solid var(--color-border); padding-top: 20px;">
            <div>
                <div class="caption" style="margin-bottom: 4px;">Item Layanan Dipesan</div>
                @if($order->items && $order->items->isNotEmpty())
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        @foreach($order->items as $item)
                            <div style="font-size: 14.5px; font-weight: 700; display: flex; align-items: center; justify-content: space-between;">
                                <span>
                                    {{ $item->item_name }}
                                    <span class="caption" style="font-weight: 400;">({{ $item->quantity }}x)</span>
                                </span>
                                <span style="font-size: 13px; font-weight: 600; color: var(--color-highlight);">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @elseif($order->bundling)
                    <div style="font-size: 16px; font-weight: 700;">
                        <a href="{{ route('bundling.detail', $order->bundling_id) }}">
                            {{ $order->bundling->package_name }}
                        </a>
                        <span class="badge badge-accent" style="font-size: 10px; margin-left: 4px;">Paket Bundling</span>
                    </div>
                @elseif($order->serviceItem)
                    <div style="font-size: 16px; font-weight: 700;">
                        <a href="{{ route('service.detail', $order->service_item_id) }}">
                            {{ $order->serviceItem->name }}
                        </a>
                        <span class="badge badge-primary" style="font-size: 10px; margin-left: 4px;">{{ $order->serviceItem->badge ?? 'Layanan' }}</span>
                    </div>
                @else
                    <div style="font-size: 16px; font-weight: 700;">Pesanan Layanan</div>
                @endif
                <div class="body-sm" style="color: var(--color-secondary); margin-top: 6px; display: flex; align-items: center; justify-content: space-between;">
                    <span>Vendor: <strong>{{ $order->vendor->vendor_name ?? 'Mitra Vendor' }}</strong></span>
                    @if($order->vendor_id)
                        <a href="{{ route('customer.chat.start', ['vendorId' => $order->vendor_id, 'order_id' => $order->id]) }}" class="btn btn-secondary btn-sm" style="height: 28px; padding: 0 10px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                            <i data-lucide="message-circle" style="width: 13px; height: 13px;"></i>
                            <span>Chat Vendor</span>
                        </a>
                    @endif
                </div>
            </div>

            <div>
                <div class="caption" style="margin-bottom: 4px;">Skema Pembayaran</div>
                <div style="font-size: 15px; font-weight: 700; color: var(--color-primary);">
                    @if($order->payment_type === 'dp')
                        <span class="badge badge-amber">Down Payment (DP {{ $order->dp_percentage ?? 30 }}%)</span>
                        <div style="font-size: 20px; font-weight: 800; color: var(--color-highlight); margin-top: 4px;">
                            Tagihan: Rp {{ number_format($order->payable_amount, 0, ',', '.') }}
                        </div>
                        <div class="caption" style="color: var(--color-secondary);">Total Keseluruhan: Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                    @else
                        <span class="badge badge-success">Pelunasan Penuh (100%)</span>
                        <div style="font-size: 20px; font-weight: 800; color: var(--color-highlight); margin-top: 4px;">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <div class="caption" style="margin-bottom: 4px;">Data Pemesan & Kontak</div>
                <div class="body-md" style="font-weight: 700;">
                    {{ $order->customer_name ?: ($order->customer->name ?? '-') }}
                </div>
                <div class="caption" style="color: var(--color-secondary); margin-top: 2px;">
                    <i data-lucide="phone" style="width: 12px; height: 12px; vertical-align: middle;"></i>
                    {{ $order->customer_phone ?: ($order->customer->phone ?? '-') }}
                </div>
            </div>

            <div>
                <div class="caption" style="margin-bottom: 4px;">Jadwal Acara</div>
                <div class="body-md" style="font-weight: 600;">
                    <i data-lucide="calendar" style="width: 14px; height: 14px; vertical-align: middle;"></i>
                    {{ $order->event_date->format('d F Y') }} ({{ $order->event_time ?: '08:00 WIB' }})
                </div>
            </div>

            <div style="grid-column: span 2;">
                <div class="caption" style="margin-bottom: 4px;">Wilayah & Alamat Lengkap Acara</div>
                <div class="body-md" style="font-weight: 600; margin-bottom: 6px;">
                    <i data-lucide="map-pin" style="width: 14px; height: 14px; vertical-align: middle; color: #C5A059;"></i>
                    @if($order->event_region)
                        <span class="badge" style="background: #F1F5F9; color: #1E293B; border: 1px solid #CBD5E1; margin-right: 6px; font-weight: 700;">
                            📍 {{ $order->event_region }}
                        </span>
                    @endif
                    <span>{{ $order->event_location }}</span>
                </div>

                @php
                    $prox = $order->proximity;
                @endphp
                @if($prox)
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-top: 6px; padding: 8px 12px; border-radius: 8px; background: {{ $prox['badge_bg'] }}; border: 1px solid {{ $prox['badge_border'] }};">
                        <span class="badge" style="background: {{ $prox['badge_color'] }}; color: #FFFFFF; font-size: 11px; font-weight: 700;">
                            {{ $prox['label'] }} ({{ $prox['distance_km'] }})
                        </span>
                        <span style="font-size: 12px; color: {{ $prox['badge_color'] }}; font-weight: 500;">
                            {{ $prox['description'] }}
                        </span>
                    </div>
                @endif
            </div>

            @if($order->notes)
                <div style="grid-column: span 2; background: var(--color-neutral); border-radius: 8px; padding: 10px 14px;">
                    <div class="caption" style="margin-bottom: 2px; font-weight: 700;">Catatan Khusus Pemesan:</div>
                    <div class="body-sm" style="color: var(--color-primary);">{{ $order->notes }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Section Pembayaran (Midtrans Gateway & Manual Transfer) -->
    <div class="card-dribbble" style="padding: 28px; border-radius: 14px; margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 8px;">
            <div>
                <h2 class="headline-sm">Pembayaran Pesanan</h2>
                <div class="caption">Selesaikan transaksi menggunakan Payment Gateway Midtrans Otomatis atau Transfer Manual.</div>
            </div>
            @if(in_array($order->status, ['paid', 'completed']))
                <span class="badge badge-success" style="font-size: 13px; padding: 6px 14px;">
                    <i data-lucide="check-circle" style="width: 14px; height: 14px;"></i>
                    Lunas Terbayar
                </span>
            @endif
        </div>

        <!-- SECTION PEMBAYARAN: ALUR UTAMA TRANSFER MANUAL & OPSIONAL PAYMENT GATEWAY MIDTRANS -->
        @if(!$order->payment || $order->payment->payment_status === 'rejected')
            @php
                $targetVendor = $order->vendor ?? $order->bundling?->vendor ?? $order->serviceItem?->vendor ?? $order->items->first()?->vendor;
            @endphp
            <div style="background: #FFFFFF; border-radius: 14px; border: 1px solid var(--color-border); padding: 24px; margin-bottom: 24px; box-shadow: var(--shadow-subtle);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 18px; flex-wrap: wrap;">
                    <div>
                        <div class="caption" style="color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Instruksi Pembayaran Pesanan</div>
                        <h3 style="font-size: 18px; font-weight: 700; color: #1E293B; margin: 4px 0;">
                            Transfer Bank & Konfirmasi Bukti Pembayaran
                        </h3>
                        <p style="font-size: 13px; color: var(--muted); margin: 0;">
                            @if($targetVendor && $targetVendor->hasBankAccount())
                                Silakan lakukan transfer tagihan langsung ke rekening bank resmi mitra vendor <strong>{{ $targetVendor->vendor_name }}</strong> di bawah ini.
                            @else
                                Silakan lakukan transfer sesuai jumlah tagihan ke rekening bank resmi di bawah ini.
                            @endif
                        </p>
                    </div>

                    <div style="text-align: right; background: #FAF8F5; padding: 10px 16px; border-radius: 10px; border: 1px solid rgba(197, 160, 89, 0.3);">
                        <div style="font-size: 11.5px; color: var(--muted);">Total Tagihan Saat Ini:</div>
                        <div style="font-size: 20px; font-weight: 800; color: var(--primary);">
                            Rp {{ number_format($order->payable_amount, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 11px; color: #64748B;">
                            ({{ $order->payment_type === 'dp' ? 'DP ' . ($order->dp_percentage ?? 30) . '%' : 'Pelunasan Penuh' }})
                        </div>
                    </div>
                </div>

                @if($targetVendor && $targetVendor->hasBankAccount())
                    <!-- Nomor Rekening Resmi Vendor (Terhubung Langsung) -->
                    <div style="background: linear-gradient(135deg, #FAF8F5 0%, #FFFFFF 100%); border-radius: 12px; border: 1.5px solid rgba(197, 160, 89, 0.4); padding: 18px 20px; margin-bottom: 22px; box-shadow: 0 4px 12px rgba(142,60,0,0.04);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span class="badge badge-moss" style="display: inline-flex; align-items: center; gap: 4px;">
                                    <i data-lucide="store" style="width: 12px; height: 12px;"></i>
                                    Rekening Resmi Mitra Vendor
                                </span>
                                <strong style="color: #1E293B; font-size: 14.5px;">{{ $targetVendor->vendor_name }}</strong>
                            </div>
                            <span class="caption" style="color: #64748B;">Pemilik: {{ $targetVendor->owner_name }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; background: #FFFFFF; padding: 14px 18px; border-radius: 10px; border: 1px solid #E2E8F0; flex-wrap: wrap; gap: 14px;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <div style="width: 44px; height: 44px; border-radius: 8px; background: rgba(142,60,0,0.08); display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0;">
                                    <i data-lucide="building-2" style="width: 22px; height: 22px;"></i>
                                </div>
                                <div>
                                    <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); font-weight: 600;">Bank Penerima</div>
                                    <div style="font-size: 16.5px; font-weight: 800; color: var(--primary);">
                                        {{ $targetVendor->bank_name }}
                                    </div>
                                    <div style="font-size: 12.5px; color: #475569; margin-top: 2px;">
                                        a.n. <strong style="color: #1E293B;">{{ $targetVendor->bank_account_name }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 10px; background: #F8FAFC; padding: 8px 14px; border-radius: 8px; border: 1px dashed #CBD5E1;">
                                <div>
                                    <div style="font-size: 10.5px; color: var(--muted);">Nomor Rekening Vendor:</div>
                                    <div id="vendorAccountNo" style="font-size: 18px; font-weight: 800; color: #0F172A; font-family: monospace; letter-spacing: 0.5px;">
                                        {{ $targetVendor->bank_account_number }}
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="copyVendorAccount()" title="Salin Nomor Rekening" style="height: 32px; padding: 0 10px; font-size: 11px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i data-lucide="copy" style="width: 12px; height: 12px;"></i>
                                    <span id="copyBtnText">Salin</span>
                                </button>
                            </div>
                        </div>

                        @if(!empty($targetVendor->bank_notes))
                            <div style="margin-top: 10px; font-size: 12px; color: #64748B; display: flex; align-items: center; gap: 6px;">
                                <i data-lucide="info" style="width: 14px; height: 14px; color: var(--primary); flex-shrink: 0;"></i>
                                <span><strong>Catatan dari Vendor:</strong> {{ $targetVendor->bank_notes }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Nomor Rekening Marketplace (Fallback jika vendor belum atur rekening) -->
                    <div style="margin-bottom: 12px; font-size: 12px; color: #B45309; background: #FFFBEB; padding: 8px 12px; border-radius: 8px; border: 1px solid #FDE68A;">
                        <i data-lucide="info" style="width: 13px; height: 13px; display: inline-block; vertical-align: middle;"></i>
                        Mitra vendor belum mengatur rekening khusus. Anda dapat melakukan pembayaran melalui rekening penampung resmi platform berikut:
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-bottom: 20px;">
                        <div style="padding: 12px 16px; border-radius: 10px; background: #F8FAFC; border: 1px solid #E2E8F0;">
                            <span class="badge badge-accent" style="font-size: 10px; margin-bottom: 4px;">Bank BCA</span>
                            <div style="font-size: 16px; font-weight: 800; color: #1E293B; font-family: monospace;">828-123-4567</div>
                            <div style="font-size: 11.5px; color: #64748B;">a.n. PT Glow & Grace Wedding</div>
                        </div>
                        <div style="padding: 12px 16px; border-radius: 10px; background: #F8FAFC; border: 1px solid #E2E8F0;">
                            <span class="badge badge-accent" style="font-size: 10px; margin-bottom: 4px;">Bank Mandiri</span>
                            <div style="font-size: 16px; font-weight: 800; color: #1E293B; font-family: monospace;">105-00-987654</div>
                            <div style="font-size: 11.5px; color: #64748B;">a.n. PT Glow & Grace Wedding</div>
                        </div>
                        <div style="padding: 12px 16px; border-radius: 10px; background: #F8FAFC; border: 1px solid #E2E8F0;">
                            <span class="badge badge-accent" style="font-size: 10px; margin-bottom: 4px;">Bank BSI (Syariah)</span>
                            <div style="font-size: 16px; font-weight: 800; color: #1E293B; font-family: monospace;">714-555-8899</div>
                            <div style="font-size: 11.5px; color: #64748B;">a.n. PT Glow & Grace Wedding</div>
                        </div>
                    </div>
                @endif

                <!-- Form Upload Bukti Transfer -->
                <form action="{{ route('customer.order.payment', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid-cols-2" style="gap: 14px; margin-bottom: 14px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="bank_name">Bank Pengirim *</label>
                            <input type="text" name="bank_name" id="bank_name" class="input-pill" placeholder="Contoh: BCA / Mandiri / BRI / BSI" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="account_name">Nama Pemilik Rekening Pengirim *</label>
                            <input type="text" name="account_name" id="account_name" class="input-pill" placeholder="Sesuai nama di rekening/struk" required>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 18px;">
                        <label class="form-label" for="proof_image">Unggah Bukti Struk / Tangkapan Layar Transfer *</label>
                        <input type="file" name="proof_image" id="proof_image" class="input-pill" accept="image/*" required style="padding-top: 10px;">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; height: 46px; font-weight: 700;">
                        <i data-lucide="upload-cloud" style="width: 16px; height: 16px;"></i>
                        Unggah Bukti Pembayaran
                    </button>
                </form>

                <!-- Opsi Pembayaran Otomatis Midtrans (Tersimpan Siap Digunakan / Untuk Uji Dosen) -->
                @if(in_array($order->status, ['pending', 'approved']))
                    <div style="margin-top: 20px; padding-top: 18px; border-top: 1px dashed #E2E8F0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 14px 18px; border-radius: 10px;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <span class="badge badge-outline" style="font-size: 10px; border-color: #94A3B8; color: #475569;">Opsi Gateway</span>
                                    <strong style="font-size: 13px; color: #1E293B;">Pembayaran Otomatis via Midtrans (Snap Pop-Up)</strong>
                                </div>
                                <div style="font-size: 11.5px; color: #64748B; margin-top: 2px;">
                                    Tersedia untuk demonstrasi pembayaran instan otomatis (GoPay, QRIS, Virtual Account, Kartu Kredit).
                                </div>
                            </div>
                            <button type="button" id="payButtonMidtrans" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600;">
                                <i data-lucide="credit-card" style="width: 14px; height: 14px;"></i>
                                Buka Pop-Up Midtrans
                            </button>
                        </div>
                    </div>

                    <!-- Midtrans Snap Script Handler -->
                    <script type="text/javascript">
                        document.getElementById('payButtonMidtrans')?.addEventListener('click', function () {
                            const snapToken = "{{ $order->snap_token }}";

                            if (!snapToken) {
                                fetch("{{ route('midtrans.snap-token', $order->id) }}")
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.snap_token) {
                                            triggerSnap(data.snap_token);
                                        } else {
                                            alert('Gagal memuat snap token Midtrans. Silakan coba kembali.');
                                        }
                                    })
                                    .catch(err => {
                                        console.error(err);
                                        alert('Koneksi Midtrans belum tersedia. Anda dapat menggunakan transfer manual di atas.');
                                    });
                            } else {
                                triggerSnap(snapToken);
                            }
                        });

                        function triggerSnap(token) {
                            if (typeof snap === 'undefined') {
                                alert('Midtrans Snap SDK sedang dimuat. Silakan coba 2 detik lagi.');
                                return;
                            }

                            snap.pay(token, {
                                onSuccess: function (result) {
                                    window.location.reload();
                                },
                                onPending: function (result) {
                                    window.location.reload();
                                },
                                onError: function (result) {
                                    alert('Pembayaran Midtrans tidak selesai. Silakan gunakan metode transfer manual.');
                                    window.location.reload();
                                },
                                onClose: function () {
                                    console.log('Snap pop-up ditutup.');
                                }
                            });
                        }
                    </script>
                @endif
            </div>
        @endif

        @if($order->payment)
            <div class="card-quiet" style="border-radius: 12px; padding: 20px; margin-top: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
                    <div>
                        <div class="caption">Status Verifikasi Pembayaran</div>
                        <div style="font-weight: 700; font-size: 15px;">
                            @if($order->payment->payment_status === 'verified')
                                <span class="badge badge-success">Pembayaran Terverifikasi Sah</span>
                            @elseif($order->payment->payment_status === 'rejected')
                                <span class="badge badge-amber">Pembayaran Ditolak Admin</span>
                            @else
                                <span class="badge badge-accent">Sedang Diverifikasi Admin</span>
                            @endif
                        </div>
                    </div>
                    <div class="caption">
                        Metode: <strong>{{ strtoupper($order->payment->payment_type ?? 'Manual') }}</strong> | Bank: <strong>{{ $order->payment->bank_name }}</strong>
                    </div>
                </div>

                @if($order->payment->transaction_id)
                    <div class="caption" style="margin-top: 6px;">
                        Transaction ID: <code>{{ $order->payment->transaction_id }}</code>
                    </div>
                @endif

                @if($order->payment->proof_image)
                    <div style="margin-top: 12px;">
                        <div class="caption" style="margin-bottom: 6px;">Bukti Transfer:</div>
                        <a href="{{ $order->payment->proof_url }}" target="_blank">
                            <img src="{{ $order->payment->proof_url }}" alt="Bukti Transfer" style="max-width: 240px; border-radius: 8px; border: 1px solid var(--color-border); box-shadow: var(--shadow-subtle);">
                        </a>
                    </div>
                @endif
            </div>
        @endif


    </div>

    <!-- Section Ulasan Pelanggan (Ketika Order Selesai) -->
    @if($order->status === 'completed')
        <div class="card-dribbble" style="padding: 28px; border-radius: 14px;">
            <h2 class="headline-sm" style="margin-bottom: 12px;">Ulasan & Rating Layanan</h2>
            
            @if($order->review)
                <div class="card-quiet" style="border-radius: 12px; padding: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <div style="font-weight: 700;">Ulasan Anda</div>
                        <div style="color: var(--color-accent); font-size: 16px;">
                            @for($i = 0; $i < $order->review->rating; $i++) ★ @endfor
                        </div>
                    </div>
                    <p class="body-md">{{ $order->review->comment }}</p>
                </div>
            @else
                <form action="{{ route('customer.order.review', $order->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Berikan Skor Rating (1 s/d 5 Bintang)</label>
                        <select name="rating" class="select-pill" required>
                            <option value="5">★★★★★ - Sangat Puas / Istimewa (5 Bintang)</option>
                            <option value="4">★★★★☆ - Puas / Bagus (4 Bintang)</option>
                            <option value="3">★★★☆☆ - Cukup Baik (3 Bintang)</option>
                            <option value="2">★★☆☆☆ - Kurang Memuaskan (2 Bintang)</option>
                            <option value="1">★☆☆☆☆ - Mengecewakan (1 Bintang)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="comment">Tulis Ulasan & Komentar Pengalaman Anda</label>
                        <textarea name="comment" id="comment" class="textarea-pill" rows="3" placeholder="Ceritakan bagaimana hasil make up dan dekorasi dari vendor ini..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Kirim Ulasan Layanan
                    </button>
                </form>
            @endif
        </div>
    @endif
</div>

<script>
    function copyVendorAccount() {
        const el = document.getElementById('vendorAccountNo');
        if (!el) return;
        const text = el.innerText.trim();
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                const btnText = document.getElementById('copyBtnText');
                if (btnText) {
                    const orig = btnText.innerText;
                    btnText.innerText = 'Tersalin!';
                    setTimeout(() => { btnText.innerText = orig; }, 2000);
                }
            }).catch(function() {
                alert('Nomor rekening disalin: ' + text);
            });
        } else {
            alert('Nomor rekening: ' + text);
        }
    }
</script>
@endsection
