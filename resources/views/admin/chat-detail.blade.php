@extends('layouts.app')

@section('title', 'Inspeksi Transkrip Chat - Admin Panel')

@section('content')
<div class="container" style="max-width: 900px; padding-top: 36px; padding-bottom: 72px;">
    <!-- Breadcrumb & Back -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.chat.monitoring') }}" class="btn btn-tertiary" style="padding: 0; display: inline-flex; align-items: center; gap: 6px; font-size: 13px;">
            &larr; Kembali ke Pengawasan Chat
        </a>
    </div>

    <!-- Participants Summary Card -->
    <div class="card-kabut" style="padding: 24px; border-radius: 14px; margin-bottom: 24px; background: #fff;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="badge badge-accent" style="margin-bottom: 4px;">Log Percakapan Terpantau</span>
                <h1 class="headline-md" style="font-size: 1.35rem; margin-bottom: 6px;">
                    {{ $conversation->customer->name }} &bull; {{ $conversation->vendor->vendor_name }}
                </h1>
                <div class="caption">
                    Dibuat pada {{ $conversation->created_at->format('d M Y, H:i') }} WIB &bull; Total {{ $conversation->messages->count() }} Pesan Terkirim
                </div>
            </div>

            @if($conversation->order)
                <div style="background: var(--surface-low); padding: 10px 14px; border-radius: 8px; font-size: 13px;">
                    <span class="caption" style="display: block;">Pesanan Terkait:</span>
                    <strong>#{{ $conversation->order->order_code }}</strong> (Rp {{ number_format($conversation->order->total_price, 0, ',', '.') }})
                    <span class="badge badge-moss" style="font-size: 10px; margin-left: 4px;">{{ $conversation->order->status_label }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Full Transcript Feed -->
    <div class="card-kabut" style="padding: 24px; border-radius: 14px; background: #FAF9F6;">
        <h3 style="font-size: 1.05rem; margin-bottom: 18px; border-bottom: 1px solid var(--line); padding-bottom: 10px; color: var(--ink);">
            Transkrip Percakapan Lengkap
        </h3>

        @if($conversation->messages->isEmpty())
            <div style="text-align: center; color: var(--muted); padding: 32px 0;">
                Belum ada pesan yang dikirim dalam percakapan ini.
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach($conversation->messages as $msg)
                    @php
                        $isCustomer = $msg->sender_role === 'customer';
                    @endphp
                    <div style="display: flex; flex-direction: column; align-items: {{ $isCustomer ? 'flex-start' : 'flex-end' }};">
                        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 3px;">
                            <span class="caption" style="font-weight: 700; color: {{ $isCustomer ? 'var(--primary)' : 'var(--emerald-deep)' }};">
                                {{ $isCustomer ? 'Pelanggan: ' . $conversation->customer->name : 'Vendor: ' . $conversation->vendor->vendor_name }}
                            </span>
                            <span class="caption" style="color: var(--muted); font-size: 11px;">
                                ({{ $msg->created_at->format('d M Y, H:i:s') }})
                            </span>
                        </div>

                        <div style="max-width: 75%; padding: 12px 18px; border-radius: 12px; background: {{ $isCustomer ? '#FFFFFF' : 'var(--emerald-deep)' }}; color: {{ $isCustomer ? 'var(--ink)' : '#FFFFFF' }}; border: {{ $isCustomer ? '1px solid var(--line)' : 'none' }}; box-shadow: 0 1px 3px rgba(0,0,0,0.05); font-size: 0.92rem; line-height: 1.5; word-break: break-word;">
                            {{ $msg->message }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
