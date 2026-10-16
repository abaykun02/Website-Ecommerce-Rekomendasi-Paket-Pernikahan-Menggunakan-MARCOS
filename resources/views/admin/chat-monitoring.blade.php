@extends('layouts.app')

@section('title', 'Pengawasan Chat & Pesan - Admin Panel')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 72px;">
    <!-- Admin Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Monitoring Komunikasi</span>
            <h1 class="headline-lg">Pengawasan Chat Pelanggan & Mitra Vendor</h1>
            <p class="body-sm">Pantau interaksi komunikasi, negosiasi harga, dan koordinasi jadwal antara calon pengantin dan mitra vendor.</p>
        </div>

        <!-- Search Bar -->
        <form action="{{ route('admin.chat.monitoring') }}" method="GET" style="display: flex; gap: 8px; margin: 0;">
            <input type="text" name="search" class="form-input" placeholder="Cari pelanggan / vendor..." value="{{ request('search') }}" style="width: 260px; height: 38px;">
            <button type="submit" class="btn btn-primary btn-sm" style="height: 38px;">
                <i data-lucide="search" style="width: 14px; height: 14px;"></i>
                Cari
            </button>
        </form>
    </div>

    @if($conversations->isEmpty())
        <div class="card-kabut" style="text-align: center; padding: 48px 24px; border-radius: 14px;">
            <i data-lucide="message-square" style="width: 36px; height: 36px; color: var(--muted); margin-bottom: 12px;"></i>
            <h3 style="font-size: 1.1rem; margin-bottom: 6px;">Belum Ada Riwayat Percakapan</h3>
            <p class="caption">Belum ada aktivitas obrolan antara pelanggan dan vendor yang terekam dalam sistem.</p>
        </div>
    @else
        <div class="card-kabut" style="padding: 24px; border-radius: 14px; background: #fff;">
            <div class="table-responsive">
                <table class="table-dribbble">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Mitra Vendor</th>
                            <th>Pesanan Terkait</th>
                            <th>Pesan Terakhir</th>
                            <th>Jumlah Pesan</th>
                            <th>Aktivitas Terakhir</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($conversations as $c)
                            <tr>
                                <td>
                                    <strong>{{ $c->customer->name }}</strong>
                                    <div class="caption">{{ $c->customer->email }}</div>
                                </td>
                                <td>
                                    <strong style="color: var(--primary);">{{ $c->vendor->vendor_name }}</strong>
                                    <div class="caption">{{ $c->vendor->phone }}</div>
                                </td>
                                <td>
                                    @if($c->order)
                                        <a href="{{ route('admin.orders') }}" style="font-weight: 600;">
                                            #{{ $c->order->order_code }}
                                        </a>
                                        <div class="caption">Rp {{ number_format($c->order->total_price, 0, ',', '.') }}</div>
                                    @else
                                        <span class="caption">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($c->latestMessage)
                                        <div class="caption" style="color: var(--ink); max-width: 240px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <strong>{{ $c->latestMessage->sender_role === 'vendor' ? '[Vendor]' : '[Pelanggan]' }}:</strong>
                                            {{ $c->latestMessage->message }}
                                        </div>
                                    @else
                                        <span class="caption">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-moss">
                                        {{ $c->messages_count }} Pesan
                                    </span>
                                </td>
                                <td>
                                    {{ $c->last_message_at ? $c->last_message_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('admin.chat.detail', $c->id) }}" class="btn btn-secondary btn-sm" style="height: 32px; padding: 0 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 5px;">
                                        <i data-lucide="eye" style="width: 13px; height: 13px;"></i>
                                        <span>Inspeksi Chat</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px;">
                {{ $conversations->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
