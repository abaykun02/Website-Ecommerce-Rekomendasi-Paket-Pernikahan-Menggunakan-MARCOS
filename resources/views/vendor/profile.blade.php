@extends('layouts.app')

@section('title', 'Profil Vendor - ' . $vendor->vendor_name)

@section('content')
<div class="container" style="max-width: 720px; padding-top: 36px; padding-bottom: 64px;">
    <!-- Vendor Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Pengaturan Toko</span>
            <h1 class="headline-md">Pengaturan Profil Vendor</h1>
        </div>
        <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary btn-sm">
            &larr; Kembali ke Dashboard
        </a>
    </div>


    <div class="card-dribbble" style="padding: 32px; border-radius: 14px;">
        <form action="{{ route('vendor.profile.update') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label" for="vendor_name">Nama Brand / Usaha Vendor *</label>
                <input type="text" name="vendor_name" id="vendor_name" class="input-pill" value="{{ old('vendor_name', $vendor->vendor_name) }}" required>
            </div>

            <div class="grid-cols-2" style="gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="owner_name">Nama Pemilik / MUA *</label>
                    <input type="text" name="owner_name" id="owner_name" class="input-pill" value="{{ old('owner_name', $vendor->owner_name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">No. WhatsApp / Telepon *</label>
                    <input type="text" name="phone" id="phone" class="input-pill" value="{{ old('phone', $vendor->phone) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="location_address">Alamat Lengkap Galeri / Studio *</label>
                <textarea name="location_address" id="location_address" class="textarea-pill" rows="3" required>{{ old('location_address', $vendor->location_address) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="google_maps_url">Tautan Google Maps</label>
                <input type="url" name="google_maps_url" id="google_maps_url" class="input-pill" placeholder="https://maps.google.com/..." value="{{ old('google_maps_url', $vendor->google_maps_url) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Deskripsi & Keunggulan Layanan</label>
                <textarea name="description" id="description" class="textarea-pill" rows="4">{{ old('description', $vendor->description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; height: 48px;">
                <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                Simpan Perubahan Profil
            </button>
        </form>
    </div>
</div>
@endsection
