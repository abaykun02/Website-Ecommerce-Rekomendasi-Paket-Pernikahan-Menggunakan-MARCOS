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
                <div class="caption" style="margin-bottom: 4px;">Lokasi / Alamat Lengkap Acara</div>
                <div class="body-md" style="font-weight: 600;">
                    <i data-lucide="map-pin" style="width: 14px; height: 14px; vertical-align: middle;"></i>
                    {{ $order->event_location }}
                </div>
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

        <!-- 1. Midtrans Snap Online Payment Option -->
        @if(in_array($order->status, ['pending', 'approved']))
            <div style="background: linear-gradient(135deg, #0D0C22 0%, #1F1D36 100%); color: white; border-radius: 14px; padding: 24px; margin-bottom: 24px; box-shadow: var(--shadow-hover);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 18px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <span class="badge badge-accent" style="font-size: 11px;">Payment Gateway Resmi</span>
                            <span class="badge badge-outline" style="border-color: rgba(255,255,255,0.4); color: white; font-size: 10px;">Sandbox</span>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 800; color: white; margin-bottom: 4px;">Midtrans Snap Pop-Up Payment</h3>
                        <p style="font-size: 13px; color: rgba(255, 255, 255, 0.75); max-width: 520px; line-height: 1.5;">
                            Bayar instan via GoPay, QRIS, BCA/Mandiri/BRI Virtual Account, ShopeePay, atau Kartu Kredit. Status pesanan akan terverifikasi otomatis dalam hitungan detik.
                        </p>
                    </div>

                    <div style="text-align: right;">
                        <div style="font-size: 12px; color: rgba(255,255,255,0.7);">Jumlah Tagihan:</div>
                        <div style="font-size: 24px; font-weight: 800; color: var(--color-accent);">
                            Rp {{ number_format($order->payable_amount, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 11px; color: rgba(255,255,255,0.6);">
                            ({{ $order->payment_type === 'dp' ? 'DP ' . ($order->dp_percentage ?? 30) . '%' : 'Pelunasan Penuh' }})
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <button type="button" id="payButtonMidtrans" class="btn btn-primary" style="height: 48px; padding: 0 28px; font-size: 15px; font-weight: 700; background: var(--color-accent); color: var(--color-primary); border: none;">
                        <i data-lucide="zap" style="width: 18px; height: 18px;"></i>
                        Bayar Sekarang via Midtrans
                    </button>

                    <div style="font-size: 12px; color: rgba(255, 255, 255, 0.65); display: flex; align-items: center; gap: 6px;">
                        <i data-lucide="shield-check" style="width: 14px; height: 14px; color: #4ADE80;"></i>
                        Tersertifikasi PCI DSS & 3D Secure
                    </div>
                </div>
            </div>

            <!-- Midtrans Snap Script Handler -->
            <script type="text/javascript">
                document.getElementById('payButtonMidtrans').onclick = function () {
                    const snapToken = "{{ $order->snap_token }}";

                    if (!snapToken) {
                        // Fetch token dynamically if missing
                        fetch("{{ route('midtrans.snap-token', $order->id) }}")
                            .then(res => res.json())
                            .then(data => {
                                if (data.snap_token) {
                                    triggerSnap(data.snap_token);
                                } else {
                                    alert('Gagal memuat snap token Midtrans. Silakan coba sesaat lagi.');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                alert('Koneksi pembayaran gagal. Silakan coba kembali.');
                            });
                    } else {
                        triggerSnap(snapToken);
                    }
                };

                function triggerSnap(token) {
                    if (typeof snap === 'undefined') {
                        alert('Midtrans Snap SDK sedang dimuat. Silakan tunggu 2 detik dan klik lagi.');
                        return;
                    }

                    snap.pay(token, {
                        onSuccess: function (result) {
                            console.log('Midtrans Payment Success:', result);
                            window.location.reload();
                        },
                        onPending: function (result) {
                            console.log('Midtrans Payment Pending:', result);
                            window.location.reload();
                        },
                        onError: function (result) {
                            console.error('Midtrans Payment Error:', result);
                            alert('Pembayaran gagal atau dibatalkan.');
                            window.location.reload();
                        },
                        onClose: function () {
                            console.log('Snap modal closed by customer.');
                        }
                    });
                }
            </script>
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

        <!-- 2. Manual Bank Transfer Option (Fallback) -->
        @if(!$order->payment || $order->payment->payment_status === 'rejected')
            <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--color-border);">
                <div class="label-md" style="margin-bottom: 8px; color: var(--color-secondary);">
                    Atau Pembayaran Transfer Bank Manual (Konfirmasi Bukti Transfer)
                </div>
                <div class="alert alert-success" style="border-radius: 10px; margin-bottom: 20px;">
                    <i data-lucide="info" style="width: 18px; height: 18px;"></i>
                    <div>
                        Silakan transfer total pembayaran ke rekening resmi marketplace:<br>
                        <strong>BCA 828-123-4567 a.n. Glow & Grace</strong> atau <strong>Mandiri 105-00-987654 a.n. Glow & Grace</strong>
                    </div>
                </div>

                <form action="{{ route('customer.order.payment', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid-cols-2" style="gap: 16px;">
                        <div class="form-group">
                            <label class="form-label" for="bank_name">Bank Pengirim *</label>
                            <input type="text" name="bank_name" id="bank_name" class="input-pill" placeholder="Contoh: BCA / Mandiri / BRI / BSI" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="account_name">Nama Pemilik Rekening Pengirim *</label>
                            <input type="text" name="account_name" id="account_name" class="input-pill" placeholder="Sesuai nama di buku tabungan/struk" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="proof_image">Unggah Bukti Struk / Screenshot Transfer *</label>
                        <input type="file" name="proof_image" id="proof_image" class="input-pill" accept="image/*" required style="padding-top: 12px;">
                    </div>

                    <button type="submit" class="btn btn-secondary" style="width: 100%; height: 48px;">
                        <i data-lucide="upload" style="width: 16px; height: 16px;"></i>
                        Unggah Bukti Transfer Manual
                    </button>
                </form>
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
@endsection
