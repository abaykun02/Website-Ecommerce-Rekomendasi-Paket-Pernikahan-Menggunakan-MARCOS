<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Glow & Grace - E-Marketplace Jasa Make-Up & Dekorasi Pernikahan B2C Berbasis Sistem Pendukung Keputusan MARCOS">
    <title>@yield('title', 'Glow & Grace - Marketplace Make-Up & Dekorasi')</title>
    
    <!-- Google Fonts: Playfair Display (Editorial Serif) & Plus Jakarta Sans (Clean Modern Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Material Symbols Outlined for Stitch Components -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Stitch Design System Master Stylesheet (Bridal Opulence & Precision) -->
    <link rel="stylesheet" href="{{ asset('css/kabut.css') }}">
    
    <!-- SheetJS CDN for Interactive Excel Import & View -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    
    <!-- Lucide Icons via unpkg -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Midtrans Snap JS Sandbox/Production -->
    <script type="text/javascript" 
            src="{{ config('midtrans.is_production', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" 
            data-client-key="{{ config('midtrans.client_key', env('MIDTRANS_CLIENT_KEY', 'Mid-client-4nu_ocWBGxT4uwca')) }}"></script>
    
    @stack('styles')
</head>
<body>
    <!-- Top Navigation (Context-Aware: Admin / Vendor / Public) -->
    <header class="header-nav">
        <div class="container-wide" style="width: 100%;">
            <div class="nav-inner">
                @if(auth()->check() && (request()->is('admin*') || request()->routeIs('admin.*')))
                    <!-- ADMIN NAVBAR -->
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <a href="{{ route('admin.dashboard') }}" class="logo-brand">
                            <span>Glow & Grace</span>
                            <span class="logo-dot"></span>
                        </a>
                        <span class="badge badge-primary" style="font-size: 11px;">Admin Panel</span>
                    </div>

                    <nav>
                        <ul class="nav-links">
                            <li>
                                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.criterias') }}" class="nav-link {{ request()->routeIs('admin.criterias*') ? 'active' : '' }}">
                                    Kriteria & Bobot
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.evaluations') }}" class="nav-link {{ request()->routeIs('admin.evaluations*') ? 'active' : '' }}">
                                    Evaluasi Alternatif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                                    Pesanan Masuk
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.spk') }}" class="nav-link {{ request()->routeIs('admin.spk*') ? 'active' : '' }}">
                                    Laporan MARCOS
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                                    Pengguna
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="nav-actions">
                        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" title="Lihat Tampilan Pengunjung" target="_blank" style="height: 32px; display: inline-flex; align-items: center; gap: 6px; font-size: 12px;">
                            <i data-lucide="external-link" style="width: 13px; height: 13px;"></i>
                            <span>Lihat Web</span>
                        </a>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="badge badge-muted" style="font-size: 11px; height: 32px; padding: 0 12px; display: inline-flex; align-items: center; gap: 6px;">
                                <i data-lucide="shield" style="width: 12px; height: 12px;"></i>
                                <span>{{ Str::limit(auth()->user()->name, 12) }}</span>
                            </span>
                            <button type="button" onclick="openLogoutModal()" class="btn btn-secondary btn-sm" title="Keluar dari Akun" style="padding: 0 10px; height: 32px; display: inline-flex; align-items: center; justify-content: center; color: var(--error);">
                                <i data-lucide="log-out" style="width: 13px; height: 13px;"></i>
                            </button>
                        </div>
                    </div>

                @elseif(auth()->check() && auth()->user()->isVendor() && request()->routeIs('vendor.*') && !request()->routeIs('vendor.detail'))
                    <!-- VENDOR NAVBAR -->
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <a href="{{ route('vendor.dashboard') }}" class="logo-brand">
                            <span>Glow & Grace</span>
                            <span class="logo-dot"></span>
                        </a>
                        <span class="badge badge-moss" style="font-size: 11px;">Toko Vendor</span>
                    </div>

                    <nav>
                        <ul class="nav-links">
                            <li>
                                <a href="{{ route('vendor.dashboard') }}" class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('vendor.bundlings') }}" class="nav-link {{ request()->routeIs('vendor.bundlings*') ? 'active' : '' }}">
                                    Kelola Paket
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('vendor.galleries') }}" class="nav-link {{ request()->routeIs('vendor.galleries*') ? 'active' : '' }}">
                                    Galeri Foto
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('vendor.orders') }}" class="nav-link {{ request()->routeIs('vendor.orders*') ? 'active' : '' }}">
                                    Pesanan Pelanggan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('vendor.profile') }}" class="nav-link {{ request()->routeIs('vendor.profile*') ? 'active' : '' }}">
                                    Profil Toko
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="nav-actions">
                        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" title="Lihat Tampilan Pengunjung" target="_blank" style="height: 32px; display: inline-flex; align-items: center; gap: 6px; font-size: 12px;">
                            <i data-lucide="external-link" style="width: 13px; height: 13px;"></i>
                            <span>Lihat Web</span>
                        </a>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="badge badge-muted" style="font-size: 11px; height: 32px; padding: 0 12px; display: inline-flex; align-items: center; gap: 6px;">
                                <i data-lucide="store" style="width: 12px; height: 12px;"></i>
                                <span>{{ Str::limit(auth()->user()->name, 12) }}</span>
                            </span>
                            <button type="button" onclick="openLogoutModal()" class="btn btn-secondary btn-sm" title="Keluar dari Akun" style="padding: 0 10px; height: 32px; display: inline-flex; align-items: center; justify-content: center; color: var(--error);">
                                <i data-lucide="log-out" style="width: 13px; height: 13px;"></i>
                            </button>
                        </div>
                    </div>

                @else
                    <!-- PUBLIC & CUSTOMER NAVBAR -->
                    <a href="{{ route('home') }}" class="logo-brand">
                        <span>Glow & Grace</span>
                        <span class="logo-dot"></span>
                    </a>

                    <nav>
                        <ul class="nav-links">
                            <li>
                                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                                    Beranda
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('services') }}" class="nav-link {{ request()->routeIs('services*') || request()->routeIs('service.detail*') ? 'active' : '' }}">
                                    Kategori & Layanan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('bundlings') }}" class="nav-link {{ request()->routeIs('bundlings*') ? 'active' : '' }}">
                                    Paket Bundling
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('vendors') }}" class="nav-link {{ request()->routeIs('vendors*') ? 'active' : '' }}">
                                    Direktori Vendor
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('spk.rekomendasi') }}" class="nav-link {{ request()->routeIs('spk.rekomendasi') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 6px;">
                                    <span class="badge badge-moss" style="font-size: 9px; padding: 1px 6px;">SPK</span>
                                    <span>Rekomendasi MARCOS</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="nav-actions">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                                    <i data-lucide="shield" style="width: 13px; height: 13px;"></i>
                                    Panel Admin
                                </a>
                            @elseif(auth()->user()->isVendor())
                                <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary btn-sm">
                                    <i data-lucide="store" style="width: 13px; height: 13px;"></i>
                                    Toko Vendor
                                </a>
                            @else
                                <a href="{{ route('customer.orders') }}" class="btn btn-secondary btn-sm">
                                    <i data-lucide="shopping-bag" style="width: 13px; height: 13px;"></i>
                                    Pesanan Saya
                                </a>
                            @endif

                            <div style="display: flex; align-items: center; gap: 8px; margin-left: 2px;">
                                <span class="badge badge-muted" style="font-size: 11px; height: 28px; padding: 0 10px; border-radius: var(--radius-sm);">
                                    <i data-lucide="user" style="width: 12px; height: 12px;"></i>
                                    {{ Str::limit(auth()->user()->name, 12) }}
                                </span>
                                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm" title="Keluar" style="padding: 0 8px; height: 28px;">
                                        <i data-lucide="log-out" style="width: 13px; height: 13px;"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                                Daftar Pelanggan
                            </a>
                            <a href="{{ route('register.vendor') }}" class="btn btn-tertiary" style="font-size: 11px;">
                                Gabung Vendor
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="container-wide" style="margin-top: 14px;">
        @if(session('success'))
            <div class="badge-moss" style="padding: 10px 14px; border-radius: var(--radius-sm); display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
                <i data-lucide="check-circle" style="width: 16px; height: 16px; color: var(--moss);"></i>
                <span style="font-weight: 500; font-size: 0.85rem;">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="badge-danger" style="padding: 10px 14px; border-radius: var(--radius-sm); display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
                <i data-lucide="alert-circle" style="width: 16px; height: 16px; color: var(--danger);"></i>
                <span style="font-weight: 500; font-size: 0.85rem;">{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="badge-danger" style="padding: 10px 14px; border-radius: var(--radius-sm); display: flex; align-items: flex-start; gap: 8px; margin-bottom: 14px;">
                <i data-lucide="alert-triangle" style="width: 16px; height: 16px; color: var(--danger); margin-top: 2px;"></i>
                <div style="font-size: 0.85rem;">
                    @foreach($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer (Editorial Gaya Kabut) -->
    <footer class="footer-kabut">
        <div class="container-wide">
            <div class="footer-inner">
                <div>
                    <div class="logo-brand" style="margin-bottom: 10px;">
                        <span>Glow & Grace</span>
                        <span class="logo-dot"></span>
                    </div>
                    <p class="body-sm" style="max-width: 360px; margin-bottom: 14px; line-height: 1.55;">
                        E-Marketplace Jasa Make-Up & Dekorasi Pernikahan B2C. Didukung algoritma Sistem Pendukung Keputusan (SPK) metode <strong>MARCOS</strong> untuk menghitung rekomendasi terbaik dengan pembobotan preferensi dinamis.
                    </p>
                    <div style="display: flex; gap: 6px;">
                        <span class="badge badge-moss">SPK MARCOS</span>
                        <span class="badge badge-outline">B2C Marketplace</span>
                    </div>
                </div>

                <div class="footer-col">
                    <h4>Navigasi</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Beranda Utama</a></li>
                        <li><a href="{{ route('services') }}">Kategori & Layanan</a></li>
                        <li><a href="{{ route('bundlings') }}">Paket Bundling</a></li>
                        <li><a href="{{ route('vendors') }}">Direktori Vendor</a></li>
                        <li><a href="{{ route('spk.rekomendasi') }}">Rekomendasi SPK</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Akses Portal</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('login') }}">Masuk Akun</a></li>
                        <li><a href="{{ route('register') }}">Registrasi Pelanggan</a></li>
                        <li><a href="{{ route('register.vendor') }}">Daftar Mitra Vendor</a></li>
                        <li><a href="{{ route('admin.dashboard') }}">Konsol Admin</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Kriteria Evaluasi</h4>
                    <ul class="footer-links" style="font-size: 0.78rem;">
                        <li>C1: Kualitas Riasan (20%)</li>
                        <li>C2: Estetika & Desain (20%)</li>
                        <li>C3: Kelengkapan Fasilitas (15%)</li>
                        <li>C4: Rating & Ulasan (10%)</li>
                        <li>C5: Harga Layanan (Cost, 20%)</li>
                        <li>C6: Lokasi & Respon (10%)</li>
                        <li>C7: Kualitas Bahan/Alat (5%)</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="caption">
                    &copy; {{ date('Y') }} Glow & Grace. Seluruh hak cipta dilindungi undang-undang.
                </div>
                <div class="caption">
                    Wilayah Layanan: Medan &bull; Deli Serdang &bull; Tanjung Morawa &bull; Sumatera Utara
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal Konfirmasi Logout Animasi Pop-Up (Stitch Bridal Opulence Style) -->
    <div id="logoutModal" class="modal-backdrop-luxury" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle">
        <div class="modal-box-luxury">
            <div class="modal-icon-luxury">
                <span class="material-symbols-outlined" style="font-size: 30px; color: var(--primary-container);">logout</span>
            </div>
            <h3 id="logoutModalTitle" class="modal-title-luxury">Konfirmasi Keluar Akun</h3>
            <p class="modal-desc-luxury">
                Apakah Anda yakin ingin mengakhiri sesi dan keluar dari sistem <strong>Glow & Grace</strong> saat ini?
            </p>
            <div class="modal-actions-luxury">
                <button type="button" class="btn btn-secondary" onclick="closeLogoutModal()" style="min-width: 110px;">
                    Batal
                </button>
                <form id="confirmedLogoutForm" action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="min-width: 130px;">
                        <i data-lucide="log-out" style="width: 14px; height: 14px;"></i>
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Init Lucide Icons & Logout Interception -->
    <script>
        lucide.createIcons();

        function openLogoutModal(e) {
            if (e) e.preventDefault();
            const modal = document.getElementById('logoutModal');
            if (modal) {
                modal.style.display = 'flex';
                // Trigger CSS transition
                setTimeout(() => {
                    modal.classList.add('active');
                }, 10);
                document.body.style.overflow = 'hidden';
            }
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            if (modal) {
                modal.classList.remove('active');
                setTimeout(() => {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }, 250);
            }
        }

        // Close on backdrop click
        document.getElementById('logoutModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogoutModal();
            }
        });

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLogoutModal();
            }
        });

        // Intercept all logout form submissions across navbars
        document.querySelectorAll('form[action="{{ route('logout') }}"]').forEach(form => {
            if (form.id !== 'confirmedLogoutForm') {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    openLogoutModal(e);
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
