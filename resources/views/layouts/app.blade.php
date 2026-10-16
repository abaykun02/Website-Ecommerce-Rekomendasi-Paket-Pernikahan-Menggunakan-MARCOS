<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Marketplace Jasa Make-Up & Dekorasi Pernikahan B2C Berbasis Sistem Pendukung Keputusan MARCOS">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E- Marketplace Make-Up & Dekorasi')</title>
    
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
        @php
            $userUnreadChats = auth()->check() ? auth()->user()->unreadChatCount() : 0;
        @endphp
        <div class="container-wide" style="width: 100%;">
            <div class="nav-inner">
                @if(auth()->check() && (request()->is('admin*') || request()->routeIs('admin.*')))
                    <!-- ADMIN NAVBAR -->
                    <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                        <a href="{{ route('admin.dashboard') }}" class="logo-brand">
                            <span>E-MarketPlace</span>
                            <span class="logo-dot"></span>
                        </a>
                        <span class="badge badge-primary" style="font-size: 10px; padding: 2px 7px;">Admin</span>
                    </div>

                    <nav class="nav-desktop">
                        <ul class="nav-links">
                            <li>
                                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.vendors') }}" class="nav-link {{ request()->routeIs('admin.vendors*') ? 'active' : '' }}">
                                    Mitra Vendor
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                                    Pengguna
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                                    Pesanan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.evaluations') }}" class="nav-link {{ request()->routeIs('admin.evaluations*') ? 'active' : '' }}">
                                    Evaluasi
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.criterias') }}" class="nav-link {{ request()->routeIs('admin.criterias*') ? 'active' : '' }}">
                                    Kriteria
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.spk') }}" class="nav-link {{ request()->routeIs('admin.spk*') ? 'active' : '' }}">
                                    MARCOS
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.chat.monitoring') }}" class="nav-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}" style="display: inline-flex; align-items: center; gap: 4px; position: relative;">
                                    <span style="position: relative; display: inline-flex;">
                                        <i data-lucide="message-square" style="width: 13px; height: 13px;"></i>
                                        @if($userUnreadChats > 0)
                                            <span class="nav-chat-badge-dot" title="{{ $userUnreadChats }} pesan belum dibaca"></span>
                                        @endif
                                    </span>
                                    <span>Chat</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="nav-actions">
                        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" title="Lihat Tampilan Pengunjung" target="_blank" style="height: 32px; display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; padding: 0 10px;">
                            <i data-lucide="external-link" style="width: 13px; height: 13px;"></i>
                            <span>Web</span>
                        </a>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span class="badge badge-muted" style="font-size: 11px; height: 32px; padding: 0 10px; display: inline-flex; align-items: center; gap: 5px;">
                                <i data-lucide="shield" style="width: 12px; height: 12px;"></i>
                                <span>{{ Str::limit(auth()->user()->name, 10) }}</span>
                            </span>
                        </div>
                        <!-- Tombol Garis Tiga (Hamburger Menu) -->
                        <button type="button" class="nav-toggle-btn" onclick="toggleNavDrawer()" aria-label="Buka Menu" title="Menu Navigasi" style="position: relative;">
                            <i data-lucide="menu" style="width: 20px; height: 20px;"></i>
                            @if($userUnreadChats > 0)
                                <span class="nav-chat-badge-dot"></span>
                            @endif
                        </button>
                    </div>

                @elseif(auth()->check() && auth()->user()->isVendor() && request()->routeIs('vendor.*') && !request()->routeIs('vendor.detail'))
                    <!-- VENDOR NAVBAR -->
                    <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                        <a href="{{ route('vendor.dashboard') }}" class="logo-brand">
                            <span>E-Marketplace</span>
                            <span class="logo-dot"></span>
                        </a>
                        <span class="badge badge-moss" style="font-size: 10px; padding: 2px 7px;">Vendor</span>
                    </div>

                    <nav class="nav-desktop">
                        <ul class="nav-links">
                            <li>
                                <a href="{{ route('vendor.dashboard') }}" class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('vendor.bundlings') }}" class="nav-link {{ request()->routeIs('vendor.bundlings*') ? 'active' : '' }}">
                                    Paket
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('vendor.galleries') }}" class="nav-link {{ request()->routeIs('vendor.galleries*') ? 'active' : '' }}">
                                    Galeri
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('vendor.orders') }}" class="nav-link {{ request()->routeIs('vendor.orders') ? 'active' : '' }}">
                                    Pesanan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('vendor.orders.history') }}" class="nav-link {{ request()->routeIs('vendor.orders.history') ? 'active' : '' }}">
                                    Riwayat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('vendor.chat') }}" class="nav-link {{ request()->routeIs('vendor.chat*') ? 'active' : '' }}" style="display: inline-flex; align-items: center; gap: 4px; position: relative;">
                                    <span style="position: relative; display: inline-flex;">
                                        <i data-lucide="message-circle" style="width: 13px; height: 13px;"></i>
                                        @if($userUnreadChats > 0)
                                            <span class="nav-chat-badge-dot" title="{{ $userUnreadChats }} pesan belum dibaca"></span>
                                        @endif
                                    </span>
                                    <span>Chat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('vendor.profile') }}" class="nav-link {{ request()->routeIs('vendor.profile*') ? 'active' : '' }}">
                                    Profil
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="nav-actions">
                        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" title="Lihat Tampilan Pengunjung" target="_blank" style="height: 32px; display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; padding: 0 10px;">
                            <i data-lucide="external-link" style="width: 13px; height: 13px;"></i>
                            <span>Web</span>
                        </a>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span class="badge badge-muted" style="font-size: 11px; height: 32px; padding: 0 10px; display: inline-flex; align-items: center; gap: 5px;">
                                <i data-lucide="store" style="width: 12px; height: 12px;"></i>
                                <span>{{ Str::limit(auth()->user()->name, 10) }}</span>
                            </span>
                        </div>
                        <!-- Tombol Garis Tiga (Hamburger Menu) -->
                        <button type="button" class="nav-toggle-btn" onclick="toggleNavDrawer()" aria-label="Buka Menu" title="Menu Navigasi" style="position: relative;">
                            <i data-lucide="menu" style="width: 20px; height: 20px;"></i>
                            @if($userUnreadChats > 0)
                                <span class="nav-chat-badge-dot"></span>
                            @endif
                        </button>
                    </div>

                @else
                    <!-- PUBLIC & CUSTOMER NAVBAR -->
                    <a href="{{ route('home') }}" class="logo-brand" style="flex-shrink: 0;">
                        <span>E-Marketplace</span>
                        <span class="logo-dot"></span>
                    </a>

                    <nav class="nav-desktop">
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
                                <a href="{{ route('spk.rekomendasi') }}" class="nav-link {{ request()->routeIs('spk.rekomendasi') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 5px;">
                                    <span class="badge badge-moss" style="font-size: 9px; padding: 1px 5px;">SPK</span>
                                    <span>MARCOS</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="nav-actions">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                                    <i data-lucide="shield" style="width: 13px; height: 13px;"></i>
                                    <span>Admin</span>
                                </a>
                            @elseif(auth()->user()->isVendor())
                                <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary btn-sm">
                                    <i data-lucide="store" style="width: 13px; height: 13px;"></i>
                                    <span>Vendor</span>
                                </a>
                            @else
                                <!-- Customer Cart Link with Badge -->
                                @php
                                    $cartItemsCount = \App\Models\CartItem::where('user_id', auth()->id())->count();
                                @endphp
                                <a href="{{ route('customer.cart') }}" class="btn btn-secondary btn-sm {{ request()->routeIs('customer.cart*') ? 'btn-active' : '' }}" title="Keranjang Belanja" style="position: relative; display: inline-flex; align-items: center; gap: 5px;">
                                    <i data-lucide="shopping-cart" style="width: 14px; height: 14px;"></i>
                                    <span>Keranjang</span>
                                    @if($cartItemsCount > 0)
                                        <span style="background-color: var(--primary); color: #fff; border-radius: 999px; padding: 1px 5px; font-size: 10px; font-weight: 700; line-height: 1.2;">
                                            {{ $cartItemsCount }}
                                        </span>
                                    @endif
                                </a>

                                <a href="{{ route('customer.chat') }}" class="btn btn-secondary btn-sm {{ request()->routeIs('customer.chat*') ? 'btn-active' : '' }}" title="Chat Vendor" style="position: relative; display: inline-flex; align-items: center; gap: 5px;">
                                    <span style="position: relative; display: inline-flex;">
                                        <i data-lucide="message-circle" style="width: 14px; height: 14px;"></i>
                                        @if($userUnreadChats > 0)
                                            <span class="nav-chat-badge-dot" title="{{ $userUnreadChats }} pesan belum dibaca"></span>
                                        @endif
                                    </span>
                                    <span>Chat</span>
                                </a>

                                <a href="{{ route('customer.orders') }}" class="btn btn-secondary btn-sm {{ request()->routeIs('customer.orders*') ? 'btn-active' : '' }}">
                                    <i data-lucide="shopping-bag" style="width: 13px; height: 13px;"></i>
                                    <span>Pesanan</span>
                                </a>
                            @endif

                            <div style="display: flex; align-items: center; gap: 6px; margin-left: 2px;">
                                @if(auth()->user()->isCustomer())
                                    <a href="{{ route('customer.profile') }}" class="badge badge-muted {{ request()->routeIs('customer.profile*') ? 'badge-primary' : '' }}" style="font-size: 11px; height: 30px; padding: 0 9px; border-radius: var(--radius-sm); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Profil Saya">
                                        <i data-lucide="user" style="width: 12px; height: 12px;"></i>
                                        <span>{{ Str::limit(auth()->user()->name, 10) }}</span>
                                    </a>
                                @else
                                    <span class="badge badge-muted" style="font-size: 11px; height: 30px; padding: 0 9px; border-radius: var(--radius-sm);">
                                        <i data-lucide="user" style="width: 12px; height: 12px;"></i>
                                        {{ Str::limit(auth()->user()->name, 10) }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                                Daftar
                            </a>
                            <a href="{{ route('register.vendor') }}" class="btn btn-tertiary" style="font-size: 11px;">
                                Gabung Vendor
                            </a>
                        @endauth

                        <!-- Tombol Garis Tiga (Hamburger Menu) -->
                        <button type="button" class="nav-toggle-btn" onclick="toggleNavDrawer()" aria-label="Buka Menu" title="Menu Navigasi" style="position: relative;">
                            <i data-lucide="menu" style="width: 20px; height: 20px;"></i>
                            @if($userUnreadChats > 0)
                                <span class="nav-chat-badge-dot"></span>
                            @endif
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </header>

    <!-- Mobile & Tablet Offcanvas Navigation Drawer (Tombol Garis Tiga) -->
    <div id="mobileNavDrawer" class="nav-drawer-backdrop" onclick="if(event.target === this) closeNavDrawer()">
        <div class="nav-drawer">
            <div class="nav-drawer-header">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="logo-brand" style="font-size: 1.15rem;">
                        <span>E-Marketplace</span>
                        <span class="logo-dot"></span>
                    </span>
                    @if(auth()->check() && (request()->is('admin*') || request()->routeIs('admin.*')))
                        <span class="badge badge-primary" style="font-size: 10px; padding: 2px 6px;">Admin</span>
                    @elseif(auth()->check() && auth()->user()->isVendor() && request()->routeIs('vendor.*'))
                        <span class="badge badge-moss" style="font-size: 10px; padding: 2px 6px;">Vendor</span>
                    @endif
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeNavDrawer()" style="padding: 4px 8px; height: 32px;" aria-label="Tutup Menu">
                    <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                </button>
            </div>

            <div class="nav-drawer-body">
                @if(auth()->check() && (request()->is('admin*') || request()->routeIs('admin.*')))
                    <!-- Admin Drawer Links -->
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; letter-spacing: 0.5px;">Navigasi Administrator</div>
                    <ul class="nav-drawer-links">
                        <li class="nav-drawer-item">
                            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i>
                                <span>Dashboard Admin</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('admin.vendors') }}" class="{{ request()->routeIs('admin.vendors*') ? 'active' : '' }}">
                                <i data-lucide="store" style="width: 16px; height: 16px;"></i>
                                <span>Kelola Mitra Vendor</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                                <i data-lucide="users" style="width: 16px; height: 16px;"></i>
                                <span>Data Pengguna</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                                <i data-lucide="shopping-bag" style="width: 16px; height: 16px;"></i>
                                <span>Semua Pesanan</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('admin.evaluations') }}" class="{{ request()->routeIs('admin.evaluations*') ? 'active' : '' }}">
                                <i data-lucide="clipboard-check" style="width: 16px; height: 16px;"></i>
                                <span>Evaluasi Alternatif</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('admin.criterias') }}" class="{{ request()->routeIs('admin.criterias*') ? 'active' : '' }}">
                                <i data-lucide="sliders" style="width: 16px; height: 16px;"></i>
                                <span>Kriteria & Bobot</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('admin.spk') }}" class="{{ request()->routeIs('admin.spk*') ? 'active' : '' }}">
                                <i data-lucide="calculator" style="width: 16px; height: 16px;"></i>
                                <span>Laporan SPK MARCOS</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('admin.chat.monitoring') }}" class="{{ request()->routeIs('admin.chat.*') ? 'active' : '' }}" style="position: relative;">
                                <span style="position: relative; display: inline-flex;">
                                    <i data-lucide="message-square" style="width: 16px; height: 16px;"></i>
                                    @if($userUnreadChats > 0)
                                        <span class="nav-chat-badge-dot"></span>
                                    @endif
                                </span>
                                <span>Pengawasan Chat</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item" style="margin-top: 8px; border-top: 1px dashed var(--line); padding-top: 8px;">
                            <a href="{{ route('home') }}" target="_blank">
                                <i data-lucide="external-link" style="width: 16px; height: 16px;"></i>
                                <span>Lihat Tampilan Web</span>
                            </a>
                        </li>
                    </ul>

                @elseif(auth()->check() && auth()->user()->isVendor() && request()->routeIs('vendor.*') && !request()->routeIs('vendor.detail'))
                    <!-- Vendor Drawer Links -->
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; letter-spacing: 0.5px;">Panel Toko Vendor</div>
                    <ul class="nav-drawer-links">
                        <li class="nav-drawer-item">
                            <a href="{{ route('vendor.dashboard') }}" class="{{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                                <i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i>
                                <span>Dashboard Toko</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('vendor.bundlings') }}" class="{{ request()->routeIs('vendor.bundlings*') ? 'active' : '' }}">
                                <i data-lucide="package" style="width: 16px; height: 16px;"></i>
                                <span>Kelola Paket Bundling</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('vendor.galleries') }}" class="{{ request()->routeIs('vendor.galleries*') ? 'active' : '' }}">
                                <i data-lucide="image" style="width: 16px; height: 16px;"></i>
                                <span>Galeri & Portofolio</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('vendor.orders') }}" class="{{ request()->routeIs('vendor.orders') ? 'active' : '' }}">
                                <i data-lucide="clock" style="width: 16px; height: 16px;"></i>
                                <span>Pesanan Berjalan</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('vendor.orders.history') }}" class="{{ request()->routeIs('vendor.orders.history') ? 'active' : '' }}">
                                <i data-lucide="check-circle" style="width: 16px; height: 16px;"></i>
                                <span>Riwayat Pesanan Selesai</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('vendor.chat') }}" class="{{ request()->routeIs('vendor.chat*') ? 'active' : '' }}" style="position: relative;">
                                <span style="position: relative; display: inline-flex;">
                                    <i data-lucide="message-circle" style="width: 16px; height: 16px;"></i>
                                    @if($userUnreadChats > 0)
                                        <span class="nav-chat-badge-dot"></span>
                                    @endif
                                </span>
                                <span>Chat Pelanggan</span>
                                @if($userUnreadChats > 0)
                                    <span class="badge badge-danger" style="margin-left: auto; font-size: 10px; padding: 1px 6px;">{{ $userUnreadChats }} baru</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('vendor.profile') }}" class="{{ request()->routeIs('vendor.profile*') ? 'active' : '' }}">
                                <i data-lucide="store" style="width: 16px; height: 16px;"></i>
                                <span>Profil & Rekening Toko</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item" style="margin-top: 8px; border-top: 1px dashed var(--line); padding-top: 8px;">
                            <a href="{{ route('home') }}" target="_blank">
                                <i data-lucide="external-link" style="width: 16px; height: 16px;"></i>
                                <span>Lihat Tampilan Web</span>
                            </a>
                        </li>
                    </ul>

                @else
                    <!-- Customer & Public Drawer Links -->
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; letter-spacing: 0.5px;">Navigasi Utama</div>
                    <ul class="nav-drawer-links">
                        <li class="nav-drawer-item">
                            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                                <i data-lucide="home" style="width: 16px; height: 16px;"></i>
                                <span>Beranda</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('services') }}" class="{{ request()->routeIs('services*') || request()->routeIs('service.detail*') ? 'active' : '' }}">
                                <i data-lucide="layers" style="width: 16px; height: 16px;"></i>
                                <span>Kategori & Layanan</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('bundlings') }}" class="{{ request()->routeIs('bundlings*') ? 'active' : '' }}">
                                <i data-lucide="gift" style="width: 16px; height: 16px;"></i>
                                <span>Paket Bundling Hemat</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('vendors') }}" class="{{ request()->routeIs('vendors*') ? 'active' : '' }}">
                                <i data-lucide="store" style="width: 16px; height: 16px;"></i>
                                <span>Direktori Vendor</span>
                            </a>
                        </li>
                        <li class="nav-drawer-item">
                            <a href="{{ route('spk.rekomendasi') }}" class="{{ request()->routeIs('spk.rekomendasi') ? 'active' : '' }}">
                                <i data-lucide="award" style="width: 16px; height: 16px;"></i>
                                <span>Rekomendasi SPK MARCOS</span>
                            </a>
                        </li>

                        @auth
                            @if(auth()->user()->isAdmin())
                                <li class="nav-drawer-item" style="margin-top: 10px; border-top: 1px dashed var(--line); padding-top: 10px;">
                                    <a href="{{ route('admin.dashboard') }}">
                                        <i data-lucide="shield" style="width: 16px; height: 16px;"></i>
                                        <span>Masuk Panel Admin</span>
                                    </a>
                                </li>
                            @elseif(auth()->user()->isVendor())
                                <li class="nav-drawer-item" style="margin-top: 10px; border-top: 1px dashed var(--line); padding-top: 10px;">
                                    <a href="{{ route('vendor.dashboard') }}">
                                        <i data-lucide="store" style="width: 16px; height: 16px;"></i>
                                        <span>Masuk Panel Toko Vendor</span>
                                    </a>
                                </li>
                            @else
                                <li class="nav-drawer-item" style="margin-top: 10px; border-top: 1px dashed var(--line); padding-top: 10px;">
                                    @php
                                        $drawerCartCount = \App\Models\CartItem::where('user_id', auth()->id())->count();
                                    @endphp
                                    <a href="{{ route('customer.cart') }}" class="{{ request()->routeIs('customer.cart*') ? 'active' : '' }}">
                                        <i data-lucide="shopping-cart" style="width: 16px; height: 16px;"></i>
                                        <span>Keranjang Belanja ({{ $drawerCartCount }})</span>
                                    </a>
                                </li>
                                <li class="nav-drawer-item">
                                    <a href="{{ route('customer.chat') }}" class="{{ request()->routeIs('customer.chat*') ? 'active' : '' }}" style="position: relative;">
                                        <span style="position: relative; display: inline-flex;">
                                            <i data-lucide="message-circle" style="width: 16px; height: 16px;"></i>
                                            @if($userUnreadChats > 0)
                                                <span class="nav-chat-badge-dot"></span>
                                            @endif
                                        </span>
                                        <span>Chat dengan Vendor</span>
                                        @if($userUnreadChats > 0)
                                            <span class="badge badge-danger" style="margin-left: auto; font-size: 10px; padding: 1px 6px;">{{ $userUnreadChats }} baru</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-drawer-item">
                                    <a href="{{ route('customer.orders') }}" class="{{ request()->routeIs('customer.orders*') ? 'active' : '' }}">
                                        <i data-lucide="shopping-bag" style="width: 16px; height: 16px;"></i>
                                        <span>Pesanan Saya</span>
                                    </a>
                                </li>
                                <li class="nav-drawer-item">
                                    <a href="{{ route('customer.profile') }}" class="{{ request()->routeIs('customer.profile*') ? 'active' : '' }}">
                                        <i data-lucide="user" style="width: 16px; height: 16px;"></i>
                                        <span>Profil Akun Saya</span>
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                @endif
            </div>

            <div class="nav-drawer-footer">
                @auth
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(142,60,0,0.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                <i data-lucide="user" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: 13px; color: var(--ink);">{{ auth()->user()->name }}</div>
                                <div style="font-size: 11px; color: var(--muted); text-transform: capitalize;">{{ auth()->user()->role }}</div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="closeNavDrawer(); openLogoutModal();" class="btn btn-secondary btn-sm" style="width: 100%; justify-content: center; color: var(--error); border-color: rgba(229,72,77,0.3);">
                        <i data-lucide="log-out" style="width: 14px; height: 14px;"></i>
                        <span>Keluar Akun</span>
                    </button>
                @else
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="justify-content: center;">
                            <i data-lucide="log-in" style="width: 14px; height: 14px;"></i>
                            <span>Masuk Akun</span>
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm" style="justify-content: center;">
                            <i data-lucide="user-plus" style="width: 14px; height: 14px;"></i>
                            <span>Daftar Pelanggan</span>
                        </a>
                        <a href="{{ route('register.vendor') }}" class="btn btn-tertiary" style="justify-content: center; font-size: 11.5px;">
                            <span>Daftar Sebagai Mitra Vendor</span>
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <div class="container-wide" style="margin-top: 14px;">
        @if(session('success'))
            <div class="badge-moss" style="padding: 10px 14px; border-radius: var(--radius-sm); display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
                <i data-lucide="check-circle" style="width: 16px; height: 16px; color: var(--moss);"></i>
                <span style="font-weight: 500; font-size: 0.85rem;">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error') && !request()->routeIs('login'))
            <div class="badge-danger" style="padding: 10px 14px; border-radius: var(--radius-sm); display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
                <i data-lucide="alert-circle" style="width: 16px; height: 16px; color: var(--danger);"></i>
                <span style="font-weight: 500; font-size: 0.85rem;">{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any() && !request()->routeIs('login'))
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
                        <span>E-MarketPlace</span>
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
                    &copy; {{ date('Y') }} Yesi Dalila Anwar. Seluruh hak cipta dilindungi undang-undang.
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
                Apakah Anda yakin ingin mengakhiri sesi dan keluar dari sistem <strong>E-Marketplace</strong> saat ini?
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

    <!-- Init Lucide Icons & Interceptions -->
    <script>
        lucide.createIcons();

        /* Mobile & Responsive Offcanvas Navigation Drawer */
        function openNavDrawer() {
            const drawer = document.getElementById('mobileNavDrawer');
            if (drawer) {
                drawer.style.display = 'block';
                setTimeout(() => {
                    drawer.classList.add('open');
                }, 10);
                document.body.style.overflow = 'hidden';
                if (window.lucide) {
                    lucide.createIcons();
                }
            }
        }

        function closeNavDrawer() {
            const drawer = document.getElementById('mobileNavDrawer');
            if (drawer) {
                drawer.classList.remove('open');
                setTimeout(() => {
                    drawer.style.display = 'none';
                    document.body.style.overflow = '';
                }, 280);
            }
        }

        function toggleNavDrawer() {
            const drawer = document.getElementById('mobileNavDrawer');
            if (drawer && (drawer.classList.contains('open') || drawer.style.display === 'block')) {
                closeNavDrawer();
            } else {
                openNavDrawer();
            }
        }

        /* Logout Modal */
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

        // Close on ESC key for modals & drawer
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLogoutModal();
                closeNavDrawer();
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
