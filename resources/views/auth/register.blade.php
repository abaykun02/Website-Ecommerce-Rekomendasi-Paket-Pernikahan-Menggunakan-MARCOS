@extends('layouts.app')

@section('title', 'Daftar Akun Customer - Glow & Grace')

@section('content')
<div class="container" style="max-width: 520px; padding-top: 40px; padding-bottom: 64px;">
    <div class="card-dribbble" style="padding: 32px 28px; border-radius: 14px;">
        <div style="text-align: center; margin-bottom: 24px;">
            <span class="badge badge-accent" style="margin-bottom: 8px;">Pendaftaran Akun</span>
            <h1 class="headline-md" style="margin-bottom: 6px;">Registrasi Pelanggan</h1>
            <p class="body-sm">Pesan riasan pengantin dan dekorasi pelaminan dengan mudah</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap *</label>
                <input type="text" name="name" id="name" class="input-pill" placeholder="Nama lengkap Anda" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Alamat Email *</label>
                <input type="email" name="email" id="email" class="input-pill" placeholder="nama@email.com" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Nomor Telepon / WhatsApp * (Min. 8 digit)</label>
                <input type="tel" name="phone" id="phone" class="input-pill" placeholder="08xxxxxxxx (min. 8 digit)" value="{{ old('phone') }}" required minlength="8">
            </div>

            <div class="form-group">
                <label class="form-label" for="address">Alamat Domisili</label>
                <textarea name="address" id="address" class="textarea-pill" rows="2" placeholder="Alamat lengkap domisili Anda...">{{ old('address') }}</textarea>
            </div>

            <div class="grid-cols-2" style="gap: 16px; margin-bottom: 20px;">
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
                Daftar Sebagai Pelanggan
            </button>
        </form>

        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--color-border); text-align: center;" class="body-sm">
            Sudah memiliki akun? <a href="{{ route('login') }}" style="color: var(--color-highlight); font-weight: 600;">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
