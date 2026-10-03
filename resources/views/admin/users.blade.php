@extends('layouts.app')

@section('title', 'Manajemen Pengguna & Vendor - Admin Panel')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Admin Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Otoritas Akun</span>
            <h1 class="headline-lg">Manajemen Pengguna & Mitra Vendor</h1>
            <p class="body-sm">Daftar akun administrator, mitra vendor make-up & dekorasi, dan pelanggan terdaftar.</p>
        </div>
    </div>

    <!-- Admin Navigation Chips -->
    <div style="display: flex; gap: 10px; margin-bottom: 32px; border-bottom: 1px solid var(--color-border); padding-bottom: 12px; overflow-x: auto;">
        <a href="{{ route('admin.dashboard') }}" class="chip">Ringkasan</a>
        <a href="{{ route('admin.criterias') }}" class="chip">Kriteria & Bobot (C1-C7)</a>
        <a href="{{ route('admin.evaluations') }}" class="chip">Matriks Evaluasi Alternatif</a>
        <a href="{{ route('admin.orders') }}" class="chip">Pesanan & Pembayaran</a>
        <a href="{{ route('admin.spk') }}" class="chip">Laporan Lengkap MARCOS</a>
        <a href="{{ route('admin.users') }}" class="chip active">Pengguna & Vendor ({{ $users->total() }})</a>
    </div>

    @if(session('success'))
        <div style="background-color: var(--color-success); color: var(--color-success-text); padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; gap: 10px; border: 1px solid rgba(11,114,64,0.2);">
            <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: var(--color-error-bg); color: var(--color-error); padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-weight: 600; display: flex; align-items: center; gap: 10px; border: 1px solid rgba(229,72,77,0.3);">
            <i data-lucide="alert-triangle" style="width: 18px; height: 18px;"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="card-dribbble" style="padding: 24px; border-radius: 14px;">
        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Alamat Email</th>
                        <th>Peran (Role)</th>
                        <th>No. Telepon</th>
                        <th>Vendor Terkait</th>
                        <th>Status Akun</th>
                        <th>Terdaftar Sejak</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr style="{{ !$user->is_active ? 'opacity: 0.65; background-color: rgba(0,0,0,0.015);' : '' }}">
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if(!$user->is_active)
                                    <span class="caption" style="display: block; color: var(--color-error); font-size: 10.5px;">(Akses Diblokir)</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $roleBadge = match($user->role) {
                                        'admin' => 'badge-accent',
                                        'vendor' => 'badge-primary',
                                        default => 'badge-muted'
                                    };
                                @endphp
                                <span class="badge {{ $roleBadge }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->phone ?: '-' }}</td>
                            <td>
                                @if($user->vendor)
                                    <a href="{{ route('vendor.detail', $user->vendor->id) }}" target="_blank" style="font-weight: 600;">
                                        {{ $user->vendor->vendor_name }}
                                    </a>
                                @else
                                    <span class="caption">-</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge badge-success" style="display: inline-flex; align-items: center; gap: 4px;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--color-success-text);"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge" style="background-color: var(--color-error-bg); color: var(--color-error); display: inline-flex; align-items: center; gap: 4px;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--color-error);"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td style="text-align: right;">
                                @if($user->id === auth()->id())
                                    <span class="caption" style="color: var(--color-secondary); font-style: italic;">Akun Anda</span>
                                @else
                                    <div style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                        <!-- Toggle Status Form -->
                                        <form action="{{ route('admin.user.toggle-status', $user->id) }}" method="POST" style="margin: 0;"
                                              onsubmit="return confirm('{{ $user->is_active ? 'Yakin ingin menonaktifkan akun '.$user->name.'? Pengguna tidak akan dapat login lagi.' : 'Aktifkan kembali akun '.$user->name.'?' }}');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" 
                                                    style="height: 30px; padding: 0 10px; font-size: 11.5px; border-radius: 6px; border: 1px solid {{ $user->is_active ? '#F59E0B' : '#10B981' }}; color: {{ $user->is_active ? '#B45309' : '#047857' }}; background-color: {{ $user->is_active ? '#FFFBEB' : '#ECFDF5' }};">
                                                <i data-lucide="{{ $user->is_active ? 'user-x' : 'user-check' }}" style="width: 13px; height: 13px;"></i>
                                                <span>{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                                            </button>
                                        </form>

                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" style="margin: 0;"
                                              onsubmit="return confirm('Peringatan: Yakin ingin menghapus akun {{ $user->name }} secara permanen dari sistem?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" 
                                                    style="height: 30px; padding: 0 10px; font-size: 11.5px; border-radius: 6px; background-color: var(--color-error-bg); color: var(--color-error); border: 1px solid rgba(229,72,77,0.3);">
                                                <i data-lucide="trash-2" style="width: 13px; height: 13px;"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
