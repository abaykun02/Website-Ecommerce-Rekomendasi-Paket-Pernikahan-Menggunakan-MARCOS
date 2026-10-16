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

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf
                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" for="email">Alamat Email, Username, atau No. HP</label>
                    <input type="text" name="email" id="email" 
                           class="input-pill @error('email') input-error @enderror" 
                           placeholder="Admin, email, atau nomor HP Anda" 
                           value="{{ old('email') }}" required autofocus
                           style="@error('email') border-color: #DC2626; box-shadow: 0 0 0 3px rgba(220,38,38,0.12); @enderror">
                    @error('email')
                        <div style="color: #DC2626; font-size: 12px; margin-top: 6px; display: flex; align-items: center; gap: 5px;">
                            <span class="material-symbols-outlined" style="font-size: 15px;">error</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label class="form-label" for="password" style="margin-bottom: 0;">Kata Sandi</label>
                    </div>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" 
                               class="input-pill @error('password') input-error @enderror" 
                               placeholder="Masukkan kata sandi" required
                               style="padding-right: 48px; @error('password') border-color: #DC2626; box-shadow: 0 0 0 3px rgba(220,38,38,0.12); @enderror">
                        <button type="button" id="togglePasswordBtn" onclick="togglePasswordVisibility()" 
                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--color-secondary); padding: 6px; display: flex; align-items: center; justify-content: center;" 
                                aria-label="Lihat kata sandi">
                            <span class="material-symbols-outlined" id="togglePasswordIcon" style="font-size: 20px;">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <div style="color: #DC2626; font-size: 12px; margin-top: 6px; display: flex; align-items: center; gap: 5px;">
                            <span class="material-symbols-outlined" style="font-size: 15px;">lock_reset</span>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; font-size: 13px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: var(--color-secondary);">
                        <input type="checkbox" name="remember" style="accent-color: var(--primary);"> Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 46px; font-size: 14px; justify-content: center;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">login</span>
                    <span>Masuk Sekarang</span>
                </button>
            </form>

            <div style="margin-top: 22px; padding-top: 16px; border-top: 1px solid var(--border-gold-subtle); text-align: center;" class="body-sm">
                Belum punya akun? <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600;">Daftar Akun Customer</a>
                <div style="margin-top: 6px;">
                    Ingin bermitra? <a href="{{ route('register.vendor') }}" style="color: var(--emerald-deep); font-weight: 600;">Daftar sebagai Mitra Vendor</a>
                </div>
            </div>

            <!-- Akun Demo Box (Klik untuk Isi Otomatis) -->
            <div class="card-quiet" style="margin-top: 20px; border-radius: 12px; font-size: 12px; padding: 14px 16px; background: #FAF9F6; border: 1px solid var(--border-gold-subtle);">
                <div style="font-weight: 700; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; color: var(--emerald-deep);">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <i data-lucide="key" style="width: 14px; height: 14px; color: var(--primary-container);"></i>
                        <span>Akun Demo Pengujian:</span>
                    </div>
                    <span style="font-size: 10.5px; color: var(--muted); font-weight: 500;">Klik untuk isi cepat</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <button type="button" onclick="fillDemo('Admin', '123')" class="btn-demo-row" style="text-align: left; background: #FFFFFF; border: 1px solid var(--line); border-radius: 8px; padding: 6px 10px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-size: 11.5px; transition: border-color 0.15s ease;">
                        <span><strong style="color: var(--ink);">Admin:</strong> <code>Admin</code></span>
                        <span style="color: var(--muted);">Pass: <code>123</code> &rarr;</span>
                    </button>
                    <button type="button" onclick="fillDemo('dyra@glowgrace.com', '123')" class="btn-demo-row" style="text-align: left; background: #FFFFFF; border: 1px solid var(--line); border-radius: 8px; padding: 6px 10px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-size: 11.5px; transition: border-color 0.15s ease;">
                        <span><strong style="color: var(--ink);">Vendor:</strong> <code>dyra@glowgrace.com</code></span>
                        <span style="color: var(--muted);">Pass: <code>123</code> &rarr;</span>
                    </button>
                    <button type="button" onclick="fillDemo('customer@gmail.com', '123')" class="btn-demo-row" style="text-align: left; background: #FFFFFF; border: 1px solid var(--line); border-radius: 8px; padding: 6px 10px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-size: 11.5px; transition: border-color 0.15s ease;">
                        <span><strong style="color: var(--ink);">Customer:</strong> <code>customer@gmail.com</code></span>
                        <span style="color: var(--muted);">Pass: <code>123</code> &rarr;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pop-Up Kesalahan Login / Password Salah -->
@php
    $errorModalData = session('error_modal');
    $hasPasswordError = $errors->has('password');
    $hasEmailError = $errors->has('email');
    $showModal = !empty($errorModalData) || $hasPasswordError || $hasEmailError;

    $modalType = is_array($errorModalData) && isset($errorModalData['type'])
        ? $errorModalData['type']
        : ($hasPasswordError ? 'wrong_password' : ($hasEmailError ? 'user_not_found' : 'general'));

    $modalTitle = is_array($errorModalData) && isset($errorModalData['title'])
        ? $errorModalData['title']
        : ($hasPasswordError ? 'Kata Sandi Salah' : ($hasEmailError ? 'Akun Tidak Ditemukan' : 'Gagal Masuk'));

    $modalMessage = is_array($errorModalData) && isset($errorModalData['message'])
        ? $errorModalData['message']
        : ($errors->first('password') ?? $errors->first('email') ?? 'Terjadi kesalahan saat masuk akun. Silakan periksa kembali data Anda.');
@endphp

@if($showModal)
<div id="loginErrorModal" class="modal-backdrop-luxury active" role="dialog" aria-modal="true" style="display: flex; z-index: 99999;">
    <div class="modal-box-luxury" style="max-width: 440px; text-align: center; border: 1px solid rgba(229,72,77,0.3); box-shadow: 0 20px 45px rgba(0,0,0,0.22); animation: modalBounceIn 0.28s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
        <div class="modal-icon-luxury" style="background: linear-gradient(135deg, #FFF5F5 0%, #FEE2E2 100%); border: 1.5px solid rgba(229,72,77,0.4); color: #DC2626; box-shadow: 0 4px 14px rgba(220,38,38,0.2); display: flex; align-items: center; justify-content: center;">
            @if($modalType === 'wrong_password')
                <i data-lucide="lock-keyhole" style="width: 32px; height: 32px; color: #DC2626;"></i>
            @elseif($modalType === 'user_not_found')
                <i data-lucide="user-x" style="width: 32px; height: 32px; color: #DC2626;"></i>
            @elseif($modalType === 'inactive')
                <i data-lucide="shield-alert" style="width: 32px; height: 32px; color: #DC2626;"></i>
            @else
                <i data-lucide="alert-circle" style="width: 32px; height: 32px; color: #DC2626;"></i>
            @endif
        </div>
        
        <h3 class="modal-title-luxury" style="font-size: 21px; color: #111827; margin-bottom: 8px;">
            {{ $modalTitle }}
        </h3>
        
        <p class="modal-desc-luxury" style="font-size: 13.5px; color: #4B5563; line-height: 1.6; margin-bottom: 24px; padding: 0 10px;">
            {{ $modalMessage }}
        </p>

        <div style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
            @if($modalType === 'user_not_found')
                <a href="{{ route('register') }}" class="btn btn-secondary" style="height: 42px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px; padding: 0 16px;">
                    <i data-lucide="user-plus" style="width: 16px; height: 16px;"></i>
                    Daftar Akun Baru
                </a>
            @endif
            <button type="button" class="btn btn-primary" onclick="closeLoginErrorModal()" style="min-width: 140px; height: 42px; font-size: 13.5px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                Coba Lagi
            </button>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    function togglePasswordVisibility() {
        const passInput = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        if (!passInput || !icon) return;

        if (passInput.type === 'password') {
            passInput.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            passInput.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    function fillDemo(email, pass) {
        const emailInput = document.getElementById('email');
        const passInput = document.getElementById('password');
        if (emailInput) {
            emailInput.value = email;
            emailInput.style.borderColor = '';
            emailInput.style.boxShadow = '';
        }
        if (passInput) {
            passInput.value = pass;
            passInput.style.borderColor = '';
            passInput.style.boxShadow = '';
            passInput.focus();
        }
    }

    function closeLoginErrorModal() {
        const modal = document.getElementById('loginErrorModal');
        if (modal) {
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
                const passInput = document.getElementById('password');
                if (passInput) {
                    passInput.focus();
                    passInput.select();
                }
            }, 180);
        }
    }

    document.getElementById('loginErrorModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeLoginErrorModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLoginErrorModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
<style>
    @keyframes modalBounceIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .btn-demo-row:hover {
        border-color: var(--primary) !important;
        background: #FDF9F0 !important;
    }
</style>
@endpush
@endsection
