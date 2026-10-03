@extends('layouts.app')

@section('title', 'Pendaftaran Mitra Vendor - Glow & Grace')

@section('content')
<div class="container" style="max-width: 620px; padding-top: 40px; padding-bottom: 64px;">
    <div class="card-dribbble" style="padding: 32px 28px; border-radius: 14px;">
        <div style="text-align: center; margin-bottom: 24px;">
            <span class="badge badge-accent" style="margin-bottom: 8px;">Kemitraan Vendor</span>
            <h1 class="headline-md" style="margin-bottom: 6px;">Pendaftaran Mitra Make-Up & Dekorasi</h1>
            <p class="body-sm">Bergabung dalam jaringan vendor pernikahan terkurasi di Glow & Grace</p>
        </div>

        <form action="{{ route('register.vendor') }}" method="POST">
            @csrf
            
            <div class="label-md" style="color: var(--color-primary); margin-bottom: 12px; font-weight: 700; border-bottom: 1px solid var(--color-border); padding-bottom: 6px;">
                1. Profil Usaha Vendor
            </div>

            <div class="form-group">
                <label class="form-label" for="vendor_name">Nama Usaha / Brand Vendor *</label>
                <input type="text" name="vendor_name" id="vendor_name" class="input-pill" placeholder="Contoh: Dyra Erlia Make-Up & Decoration" value="{{ old('vendor_name') }}" required>
            </div>

            <div class="grid-cols-2" style="gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="name">Nama Pemilik / MUA *</label>
                    <input type="text" name="name" id="name" class="input-pill" placeholder="Nama lengkap pemilik" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">No. WhatsApp Bisnis * (Min. 8 digit)</label>
                    <input type="tel" name="phone" id="phone" class="input-pill" placeholder="08xxxxxxxx (min. 8 digit)" value="{{ old('phone') }}" required minlength="8">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="location_address">Alamat Studio / Galeri *</label>
                <textarea name="location_address" id="location_address" class="textarea-pill" rows="2" placeholder="Alamat lengkap lokasi vendor..." required>{{ old('location_address') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="google_maps_url">Tautan Google Maps (Opsional)</label>
                <input type="url" name="google_maps_url" id="google_maps_url" class="input-pill" placeholder="https://maps.google.com/..." value="{{ old('google_maps_url') }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Deskripsi Layanan & Spesialisasi</label>
                <textarea name="description" id="description" class="textarea-pill" rows="3" placeholder="Ceritakan spesialisasi riasan dan konsep dekorasi Anda...">{{ old('description') }}</textarea>
            </div>

            <div class="label-md" style="color: var(--color-primary); margin: 20px 0 12px; font-weight: 700; border-bottom: 1px solid var(--color-border); padding-bottom: 6px;">
                2. Kredensial Login Vendor
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Alamat Email Login *</label>
                <input type="email" name="email" id="email" class="input-pill" placeholder="vendor@email.com" value="{{ old('email') }}" required>
            </div>

            <div class="grid-cols-2" style="gap: 16px; margin-bottom: 24px;">
                <div>
                    <label class="form-label" for="password">Kata Sandi *</label>
                    <input type="password" name="password" id="password" class="input-pill" placeholder="Kata sandi" required>
                </div>
                <div>
                    <label class="form-label" for="password_confirmation">Konfirmasi Sandi *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="input-pill" placeholder="Ulangi sandi" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; height: 46px; font-size: 14px;">
                Daftar & Masuk Dashboard Vendor
            </button>
        </form>

        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--color-border); text-align: center;" class="body-sm">
            Sudah menjadi mitra vendor? <a href="{{ route('login') }}" style="color: var(--color-highlight); font-weight: 600;">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
