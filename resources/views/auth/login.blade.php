@extends('layouts.app')

@section('title', 'Masuk ke Akun - Glow & Grace')

@section('content')
<div class="container-wide" style="padding-top: 40px; padding-bottom: 72px;">
    <!-- Split Layout Card (Stitch Haute Opulence Style) -->
    <div class="card-dribbble" style="max-width: 980px; margin: 0 auto; padding: 0; border-radius: 18px; display: grid; grid-template-columns: 1fr 1.15fr; overflow: hidden; box-shadow: var(--shadow-tier2);">
        <!-- Sisi Kiri: Hero Visual Showcase (Stitch Luxury Imagery) -->
        <div style="position: relative; background: #1A2B28; color: #FFFFFF; padding: 48px 36px; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden;">
            <!-- Background Image with Emerald Overlay -->
            <div style="position: absolute; inset: 0; background-image: url('https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center; opacity: 0.28;"></div>
            <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(26,43,40,0.85) 0%, rgba(26,43,40,0.96) 100%);"></div>

            <div style="position: relative; z-index: 2;">
                <div class="badge" style="background: rgba(255,255,255,0.12); color: #FFDEA5; border: 1px solid rgba(197,160,89,0.4); margin-bottom: 20px;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">verified</span>
                    Platform Terkurasi Mitra Vendor
                </div>
                <h2 style="font-family: var(--font-heading); font-size: 26px; color: #FAF8F5; line-height: 1.3; margin-bottom: 12px;">
                    Keanggunan Tradisi Bertemu Kepastian Analitik
                </h2>
                <p style="font-size: 13.5px; color: #D1C5B4; line-height: 1.6;">
                    Kurasi paket MUA dan dekorasi pelaminan terbaik dengan pendampingan objektif Sistem Pendukung Keputusan MARCOS.
                </p>
            </div>

            <div style="position: relative; z-index: 2; margin-top: 40px; padding-top: 24px; border-top: 1px solid rgba(197,160,89,0.25);">
                <div style="font-style: italic; font-size: 13px; color: #FFDEA5; margin-bottom: 6px;">
                    "Keputusan pernikahan terindah diawali dengan pilihan vendor yang tepat dan transparan."
                </div>
                <div style="font-size: 11.5px; color: #D1C5B4;">
                    &mdash; Glow & Grace Bridal Intelligence
                </div>
            </div>
        </div>

        <!-- Sisi Kanan: Form Masuk -->
        <div style="padding: 44px 38px; background: #FFFFFF;">
            <div style="margin-bottom: 26px;">
                <span class="badge badge-accent" style="margin-bottom: 8px;">Portal Masuk</span>
                <h1 class="headline-md" style="margin-bottom: 6px; font-size: 22px;">Masuk ke Akun Glow & Grace</h1>
                <p class="body-sm">Akses paket pernikahan terbaik dan dashboard sistem Anda</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email atau Username</label>
                    <input type="text" name="email" id="email" class="input-pill" placeholder="Admin atau email Anda" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label class="form-label" for="password" style="margin-bottom: 0;">Kata Sandi</label>
                    </div>
                    <input type="password" name="password" id="password" class="input-pill" placeholder="Masukkan kata sandi" required>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; font-size: 13px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: var(--color-secondary);">
                        <input type="checkbox" name="remember" style="accent-color: var(--primary);"> Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 46px; font-size: 14px;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">login</span>
                    Masuk Sekarang
                </button>
            </form>

            <div style="margin-top: 22px; padding-top: 16px; border-top: 1px solid var(--border-gold-subtle); text-align: center;" class="body-sm">
                Belum punya akun? <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600;">Daftar Akun Customer</a>
                <div style="margin-top: 6px;">
                    Ingin bermitra? <a href="{{ route('register.vendor') }}" style="color: var(--emerald-deep); font-weight: 600;">Daftar sebagai Mitra Vendor</a>
                </div>
            </div>

            <!-- Akun Demo Box -->
            <div class="card-quiet" style="margin-top: 20px; border-radius: 10px; font-size: 12px; padding: 14px 16px;">
                <div style="font-weight: 700; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; color: var(--emerald-deep);">
                    <i data-lucide="key" style="width: 13px; height: 13px; color: var(--primary-container);"></i>
                    Akun Demo Pengujian Cepat:
                </div>
                <div style="color: var(--color-secondary); line-height: 1.6;">
                    <div><strong>Admin:</strong> <code>Admin</code> &bull; Password: <code>123</code></div>
                    <div><strong>Vendor:</strong> <code>dyra@glowgrace.com</code> &bull; Password: <code>123</code></div>
                    <div><strong>Customer:</strong> <code>customer@gmail.com</code> &bull; Password: <code>123</code></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
