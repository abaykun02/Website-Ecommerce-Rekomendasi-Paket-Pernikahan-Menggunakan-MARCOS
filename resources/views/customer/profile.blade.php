@extends('layouts.app')

@section('title', 'Profil Saya - E-Marketplace')

@section('content')
<div class="container" style="max-width: 800px; padding-top: 36px; padding-bottom: 72px;">
    <!-- Page Header -->
    <div style="margin-bottom: 28px;">
        <span class="badge badge-moss" style="margin-bottom: 6px;">Akun Pelanggan</span>
        <h1 class="headline-lg">Profil Saya</h1>
        <p class="body-sm">Kelola informasi data diri, nomor kontak WhatsApp, alamat pengiriman, dan keamanan akun Anda.</p>
    </div>

    @if(session('success'))
        <div style="background-color: var(--color-success); color: var(--color-success-text); padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; gap: 10px; border: 1px solid rgba(11,114,64,0.2);">
            <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('customer.profile.update') }}" method="POST">
        @csrf
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- 1. Biodata Pribadi -->
            <div class="card-kabut" style="padding: 28px; border-radius: 16px;">
                <h3 style="font-family: var(--font-heading); font-size: 1.25rem; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="user-check" style="width: 20px; height: 20px; color: var(--primary);"></i>
                    Data Diri & Kontak
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                    <div>
                        <label class="form-label" for="name">Nama Lengkap <span style="color: var(--error);">*</span></label>
                        <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="caption" style="color: var(--error); margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="email">Alamat Email <span style="color: var(--error);">*</span></label>
                        <input type="email" name="email" id="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="caption" style="color: var(--error); margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                    <div>
                        <label class="form-label" for="phone">Nomor WhatsApp / HP Aktif</label>
                        <input type="tel" name="phone" id="phone" class="form-input" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789">
                        <span class="caption" style="color: var(--muted); margin-top: 4px; display: block;">Digunakan untuk konfirmasi pesanan dan koordinasi acara.</span>
                        @error('phone')
                            <div class="caption" style="color: var(--error); margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Role Akun</label>
                        <input type="text" class="form-input" value="{{ ucfirst($user->role) }}" disabled style="background: var(--surface-low); opacity: 0.8;">
                        <span class="caption" style="color: var(--muted); margin-top: 4px; display: block;">Terdaftar sejak {{ $user->created_at->format('d M Y') }}.</span>
                    </div>
                </div>

                <div>
                    <label class="form-label" for="address">Alamat Domisili / Lokasi Acara</label>
                    <textarea name="address" id="address" class="form-textarea" rows="3" placeholder="Masukkan alamat lengkap rumah / domisili...">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <div class="caption" style="color: var(--error); margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 2. Ubah Kata Sandi (Opsional) -->
            <div class="card-kabut" style="padding: 28px; border-radius: 16px;">
                <h3 style="font-family: var(--font-heading); font-size: 1.25rem; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                    <i data-lucide="lock" style="width: 20px; height: 20px; color: var(--primary);"></i>
                    Ubah Password (Keamanan Akun)
                </h3>
                <p class="body-sm" style="color: var(--muted); margin-bottom: 20px;">
                    Biarkan kosong jika Anda tidak ingin mengubah password akun Anda saat ini.
                </p>

                <div style="margin-bottom: 16px;">
                    <label class="form-label" for="current_password">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" class="form-input" placeholder="Masukkan password lama untuk verifikasi perubahan">
                    @error('current_password')
                        <div class="caption" style="color: var(--error); margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                    <div>
                        <label class="form-label" for="password">Password Baru</label>
                        <input type="password" name="password" id="password" class="form-input" placeholder="Minimal 6 karakter">
                        @error('password')
                            <div class="caption" style="color: var(--error); margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('home') }}" class="btn btn-secondary" style="padding: 12px 24px;">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 12px 32px;">
                    <i data-lucide="save" style="width: 16px; height: 16px;"></i>
                    Simpan Perubahan Profil
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
