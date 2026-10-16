@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Admin Panel')

@section('content')
<div class="container-wide" style="padding-top: 36px; padding-bottom: 64px;">
    <!-- Admin Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px;">
        <div>
            <span class="badge badge-accent" style="margin-bottom: 6px;">Otoritas Akun</span>
            <h1 class="headline-lg">Manajemen Pengguna</h1>
            <p class="body-sm">Kelola akun administrator, mitra vendor, dan seluruh pelanggan terdaftar dalam sistem.</p>
        </div>

        <!-- Filter & Search Form -->
        <form action="{{ route('admin.users') }}" method="GET" style="display: flex; gap: 8px; flex-wrap: wrap; margin: 0;">
            <select name="role" class="form-select" style="width: auto; height: 38px; font-size: 13px;" onchange="this.form.submit()">
                <option value="">Semua Peran</option>
                <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Pelanggan</option>
                <option value="vendor" {{ request('role') === 'vendor' ? 'selected' : '' }}>Mitra Vendor</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
            <input type="text" name="search" class="form-input" placeholder="Cari nama, email, hp..." value="{{ request('search') }}" style="width: 220px; height: 38px;">
            <button type="submit" class="btn btn-primary btn-sm" style="height: 38px;">
                <i data-lucide="search" style="width: 14px; height: 14px;"></i>
                Cari
            </button>
            @if(request()->hasAny(['role', 'search']))
                <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm" style="height: 38px;">Reset</a>
            @endif
        </form>
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

    <div class="card-kabut" style="padding: 24px; border-radius: 14px; background: #fff;">
        <div class="table-responsive">
            <table class="table-dribbble">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Peran (Role)</th>
                        <th>No. Telepon</th>
                        <th>Toko Vendor</th>
                        <th>Status</th>
                        <th>Terdaftar</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
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
                                    <a href="{{ route('vendor.detail', $user->vendor->id) }}" target="_blank" style="font-weight: 600; color: var(--primary);">
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
                                <div style="display: inline-flex; align-items: center; justify-content: flex-end; gap: 6px;">
                                    <!-- Edit Button (Opens Modal) -->
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="openEditUserModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->phone ?? '') }}', '{{ $user->role }}', {{ $user->is_active ? 1 : 0 }})" style="height: 30px; padding: 0 10px; font-size: 11.5px;">
                                        <i data-lucide="edit-2" style="width: 12px; height: 12px;"></i>
                                        <span>Edit</span>
                                    </button>

                                    @if($user->id !== auth()->id())
                                        <!-- Toggle Status Form -->
                                        <form action="{{ route('admin.user.toggle-status', $user->id) }}" method="POST" style="margin: 0;"
                                              onsubmit="return confirm('{{ $user->is_active ? 'Yakin ingin menonaktifkan akun '.$user->name.'?' : 'Aktifkan kembali akun '.$user->name.'?' }}');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" 
                                                    style="height: 30px; padding: 0 10px; font-size: 11.5px; border-radius: 6px; border: 1px solid {{ $user->is_active ? '#F59E0B' : '#10B981' }}; color: {{ $user->is_active ? '#B45309' : '#047857' }}; background-color: {{ $user->is_active ? '#FFFBEB' : '#ECFDF5' }};">
                                                <i data-lucide="{{ $user->is_active ? 'user-x' : 'user-check' }}" style="width: 12px; height: 12px;"></i>
                                                <span>{{ $user->is_active ? 'Blokir' : 'Buka' }}</span>
                                            </button>
                                        </form>

                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" style="margin: 0;"
                                              onsubmit="return confirm('Peringatan: Yakin ingin menghapus akun {{ $user->name }} secara permanen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" 
                                                    style="height: 30px; padding: 0 8px; font-size: 11.5px; border-radius: 6px; background-color: var(--color-error-bg); color: var(--color-error); border: 1px solid rgba(229,72,77,0.3);">
                                                <i data-lucide="trash-2" style="width: 12px; height: 12px;"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 48px; color: var(--muted);">
                                Tidak ada data pengguna yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 16px;">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Modal Edit Pengguna -->
<div id="editUserModal" class="admin-modal-backdrop" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="editUserTitle">
    <div class="admin-modal-box" style="max-width: 500px; text-align: left;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border);">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(142, 60, 0, 0.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                    <i data-lucide="user-cog" style="width: 18px; height: 18px;"></i>
                </div>
                <h3 id="editUserTitle" class="headline-sm" style="margin: 0; font-size: 1.15rem; font-weight: 600;">Edit Data Pengguna</h3>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeEditUserModal()" style="padding: 4px 8px; height: 30px;" aria-label="Tutup">
                <i data-lucide="x" style="width: 16px; height: 16px;"></i>
            </button>
        </div>

        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 14px;">
                <label class="form-label" for="edit_user_name">Nama Lengkap *</label>
                <input type="text" name="name" id="edit_user_name" class="form-input" required>
            </div>

            <div style="margin-bottom: 14px;">
                <label class="form-label" for="edit_user_email">Alamat Email *</label>
                <input type="email" name="email" id="edit_user_email" class="form-input" required>
            </div>

            <div style="margin-bottom: 14px;">
                <label class="form-label" for="edit_user_phone">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" id="edit_user_phone" class="form-input">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px;">
                <div>
                    <label class="form-label" for="edit_user_role">Peran (Role) *</label>
                    <select name="role" id="edit_user_role" class="form-select" required>
                        <option value="customer">Pelanggan (Customer)</option>
                        <option value="vendor">Mitra Vendor</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" for="edit_user_is_active">Status Akses *</label>
                    <select name="is_active" id="edit_user_is_active" class="form-select" required>
                        <option value="1">Aktif (Diberikan Akses)</option>
                        <option value="0">Nonaktif (Diblokir)</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; padding-top: 12px; border-top: 1px solid var(--border);">
                <button type="button" class="btn btn-secondary" onclick="closeEditUserModal()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" style="width: 14px; height: 14px;"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditUserModal(id, name, email, phone, role, isActive) {
        const modal = document.getElementById('editUserModal');
        const form = document.getElementById('editUserForm');
        if (!modal || !form) return;

        form.action = '/admin/pengguna/' + id;
        document.getElementById('edit_user_name').value = name || '';
        document.getElementById('edit_user_email').value = email || '';
        document.getElementById('edit_user_phone').value = phone || '';
        document.getElementById('edit_user_role').value = role || 'customer';
        document.getElementById('edit_user_is_active').value = (isActive == 1) ? '1' : '0';

        modal.style.display = 'flex';
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';

        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function closeEditUserModal() {
        const modal = document.getElementById('editUserModal');
        if (!modal) return;
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Close on backdrop click
    document.getElementById('editUserModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditUserModal();
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditUserModal();
        }
    });
</script>
@endpush
@endsection
