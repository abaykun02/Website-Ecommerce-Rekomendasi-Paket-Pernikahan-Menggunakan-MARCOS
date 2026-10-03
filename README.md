# Glow & Grace — E-Marketplace Jasa Make-Up & Dekorasi B2C dengan SPK MARCOS

Platform E-Marketplace B2C modern untuk pemesanan jasa Make-Up Artist (MUA), Dekorasi Pernikahan/Akad, dan Busana Pengantin yang dilengkapi dengan **Sistem Pendukung Keputusan (SPK) Multi-Criteria Optimization and Compromise Solution (MARCOS)** dengan **Pembobotan Preferensi Pelanggan Dinamis (Dynamic Customer Preference Weighting)** serta integrasi pembayaran digital **Midtrans Snap Payment Gateway**.

---

## 🎨 Karakteristik Desain (Gaya "Kabut" — Earthy, Hangat & Editorial)
- **Visual Tone:** Hangat, tenang, dan bersahaja (earthy), menghindari warna putih/biru generik toko online.
- **Warna Utama (Ink):** `#211710` (Teks utama, heading, dan tombol primer)
- **Background Halaman (Cream):** `#F2E9DA` (Latar belakang utama bernuansa hangat dan adem)
- **Background Kartu / Panel (Paper):** `#FBF6EC` (Latar kartu dan section selang-seling)
- **Aksen Sekunder (Moss):** `#55624A` (Badge status, label verifikasi, dan aksen tenang)
- **Aksen Utama (Rust):** `#9C4A26` (Harga, link hover, dan highlight CTA)
- **Garis Pemisah (Line):** `rgba(33, 23, 16, 0.14)` (Garis tipis minimalis)
- **Tipografi:** Heading menggunakan **Fraunces** (Serif editorial elegan) dan body text menggunakan **Work Sans** (Sangat mudah dibaca dan bersih).
- **Elemen Bentuk:** Datar, tegas, dengan radius kecil (2–10px) dan shadow hanya untuk elemen melayang.
- **Kontrol Antarmuka:** Filter dan pilihan terorganisir rapi dalam bentuk dropdown terpadu (*unified filter bar*).
- **Penyajian SPK untuk Pelanggan:** Narasi formal kualitatif (contoh: *"Peringkat ke-1 Terbaik (Rekomendasi Utama)"*, *"Peringkat ke-2 Terbaik dari Seluruh Alternatif"*) tanpa deretan angka desimal rumit di kartu utama, dilengkapi tombol `!` untuk membuka rincian matematis bagi yang membutuhkan.

---

## 🚀 Fitur Utama

### 1. Multi-Kategori Jasa & Paket Bundling
- **Paket Bundling Utama (MUA + Dekorasi):** Solusi hemat dan terpadu untuk pernikahan dan resepsi.
- **Make-Up Only:** Rias Pengantin Akad, Resepsi, Wisuda, Pre-wedding, dan Engagement.
- **Dekorasi Only:** Dekorasi Pelaminan Modern, Minimalis, Rustik, Glamour, dan Panggung Pengantin.
- **Dekorasi Akad:** Konsep Intimate Wedding, Garden Party, dan Minimalist Backdrop.
- **Gaun & Busana Pengantin:** Gaun modern bridal, kebaya akad, dan jas pengantin pria.
- **Baju Adat Tradisional:** Busana adat lengkap (Jawa, Sunda, Minang, Melayu, dll) lengkap aksesoris & paes.

### 2. SPK MARCOS dengan Dynamic Customer Preference
Metode MARCOS (Measurement of Alternatives and Ranking according to COmpromise Solution) digunakan untuk merekomendasikan vendor terbaik berdasarkan 7 kriteria multi-dimensi:
- **C1: Harga Paket / Layanan** (Cost) — Bobot dasar 20%
- **C2: Portofolio & Pengalaman Acara** (Benefit) — Bobot dasar 15%
- **C3: Rating & Kepuasan Pelanggan** (Benefit) — Bobot dasar 15%
- **C4: Kelengkapan Fasilitas & Item** (Benefit) — Bobot dasar 15%
- **C5: Fleksibilitas Custom & Revisi** (Benefit) — Bobot dasar 10%
- **C6: Kedekatan Lokasi & Respon Waktu** (Benefit) — Bobot dasar 10%
- **C7: Kualitas Bahan / Kosmetik & Alat** (Benefit) — Bobot dasar 15%

#### Preferensi Pelanggan Interaktif (Dynamic Preference Weighting):
Pelanggan dapat memilih preferensi prioritas mereka di Beranda atau halaman Rekomendasi:
1. `low_price`: Memprioritaskan harga paling terjangkau (Boost bobot C1 sebesar +0.25).
2. `terdekat`: Mengutamakan vendor terdekat dan respon cepat (Boost bobot C6 sebesar +0.25).
3. `estetik_modern`: Mengutamakan portofolio desain dan fleksibilitas custom (Boost bobot C2 & C5).
4. `kualitas_flawless`: Mengutamakan kualitas kosmetik/alat dan rating tinggi (Boost bobot C7 & C3).
5. `paket_lengkap`: Mengutamakan kelengkapan fasilitas & bonus (Boost bobot C4 sebesar +0.25).
6. `tradisional_adat`: Mengutamakan pengalaman jam terbang busana & paes adat (Boost bobot C2 & C7).

Setiap pemilihan preferensi dinormalisasi ulang secara otomatis sehingga total bobot kriteria tetap:
$$\sum_{j=1}^{m} w_j = 1.0000$$

### 3. Formulir Checkout & Booking Lengkap
- **Data Pemesan:** Nama Lengkap, Nomor WhatsApp / Telepon aktif.
- **Detail Acara:** Tanggal Pelaksanaan Acara, Waktu / Jam Mulai Acara, Alamat Lengkap & Titik Lokasi Venue.
- **Metode Pembayaran:** 
  - **Full Payment:** Pembayaran lunas 100%.
  - **Down Payment (DP):** Opsi fleksibel 30% atau 50% dengan kalkulasi nominal otomatis.
- **Catatan Tambahan:** Request konsep, tema warna, detail ukuran busana, dsb.

### 4. Integrasi Midtrans Payment Gateway (Sandbox)
- Menggunakan library resmi `midtrans/midtrans-php`.
- Menggunakan **Midtrans Snap Pop-Up** langsung di halaman detail pesanan tanpa perlu meninggalkan aplikasi.
- Mendukung metode pembayaran: GoPay, ShopeePay, QRIS, Virtual Account (BCA, Mandiri, BNI, BRI), Kartu Kredit/Debit.
- Endpoint Webhook Callback `/midtrans/callback` (CSRF-exempt) yang secara otomatis memperbarui status transaksi pesanan (`pending` -> `settlement` -> `paid`).
- Fallback manual transfer bank dengan bukti transfer tetap tersedia.

### 5. Transparansi SPK MARCOS untuk Admin & Pelanggan
Tersedia visualisasi matriks perhitungan lengkap:
1. **Matriks Keputusan ($X$):** Nilai performa aktual setiap vendor pada setiap kriteria.
2. **Matriks Keputusan Diperluas:** Penentuan Solusi Ideal ($A^+$) dan Anti-Ideal ($A^-$).
3. **Matriks Normalisasi ($N$):** Normalisasi tipe benefit dan cost terhadap $A^-$ dan $A^+$.
4. **Matriks Normalisasi Terbobot ($V$):** Perkalian matriks ternormalisasi dengan bobot kriteria $w_j$.
5. **Derajat Utilitas ($K_i^-$ dan $K_i^+$):** Perhitungan kedekatan alternatif terhadap solusi anti-ideal dan ideal.
6. **Fungsi Utilitas Alternatif $f(K_i)$:** Agregasi nilai akhir dan perankingan alternatif vendor terbaik.

---

## 🛠️ Persyaratan Sistem & Instalasi

### Spesifikasi Teknis
- **PHP:** 8.2 atau lebih tinggi
- **Database:** MySQL 8.x / MariaDB (XAMPP)
- **Web Server:** Apache / Nginx
- **Package Manager:** Composer

### Langkah Instalasi
1. **Clone / Buka Repositori:**
   ```bash
   cd c:\xampp\htdocs\YESI
   ```

2. **Konfigurasi Environment (`.env`):**
   Pastikan konfigurasi database dan Midtrans pada file `.env` sudah benar:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_ecommerceyesi
   DB_USERNAME=root
   DB_PASSWORD=

   MIDTRANS_SERVER_KEY=SB-Mid-server-YOUR_SERVER_KEY
   MIDTRANS_CLIENT_KEY=SB-Mid-client-YOUR_CLIENT_KEY
   MIDTRANS_IS_PRODUCTION=false
   MIDTRANS_IS_SANITIZED=true
   MIDTRANS_IS_3DS=true
   ```

3. **Install Dependensi & Konfigurasi Autoload:**
   ```bash
   composer install
   ```

4. **Jalankan Migrasi & Database Seeder:**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Seeder mencakup data 5 vendor terpercaya (Dyra Erlia, Syarifah Wedding, Adillaa Make Up, Feeza Wedding, Alesya Gallery), 6 kategori produk, puluhan paket bundling & layanan satuan lengkap dengan foto portofolio berkualitas tinggi.*

5. **Jalankan Pengujian Unit & Fitur:**
   ```bash
   php artisan test
   ```
   *Semua 12 test dan 58 assertions teruji lulus 100%.*

6. **Jalankan Aplikasi:**
   Buka melalui Apache XAMPP:
   `http://localhost/YESI/public`
   atau jalankan Laravel Dev Server:
   ```bash
   php artisan serve
   ```

---

## 👥 Akun Demo & Kredensial Pengujian

| Peran (Role) | Email | Password | Hak Akses |
|---|---|---|---|
| **Admin** | `admin@yesi.com` | `password` | Manajemen Vendor, Kriteria, Transparansi Matriks SPK MARCOS, Verifikasi Pesanan |
| **Customer** | `customer@yesi.com` | `password` | Pemesanan Layanan/Bundling, Checkout DP/Full, Pembayaran Midtrans Snap, Riwayat Pesanan |
| **Vendor (Dyra)** | `dyra@yesi.com` | `password` | Kelola Paket, Kelola Layanan Satuan, Monitor Booking & Jadwal Acara |
| **Vendor (Syarifah)** | `syarifah@yesi.com` | `password` | Kelola Paket & Layanan Syarifah Wedding Planner |

---

## 📁 Struktur Direktori & File Kunci

```
app/
├── Http/Controllers/
│   ├── AdminController.php        # Dashboard admin & perhitungan transparansi MARCOS
│   ├── CustomerController.php     # Checkout booking, perhitungan DP, order detail & invoice
│   ├── HomeController.php         # Beranda dinamis multi-kategori & rekomendasi interaktif
│   ├── MidtransController.php     # Endpoint webhook callback & Snap Token generation
│   └── VendorController.php       # Dashboard vendor & manajemen produk
├── Models/
│   ├── Category.php               # Kategori jasa (MUA, Dekorasi, Gaun, Adat, dll)
│   ├── ServiceItem.php            # Layanan satuan terpisah per vendor
│   ├── BundlingPackage.php        # Paket bundling pernikahan komplit
│   ├── Order.php                  # Pesanan dengan field checkout lengkap & status Midtrans
│   ├── Payment.php                # Rekam transaksi pembayaran (Midtrans / Manual)
│   └── Vendor.php                 # Profil vendor terdaftar
└── Services/
    ├── MarcosService.php          # Algoritma MARCOS & Dynamic Customer Preference
    └── MidtransService.php        # Integrasi Midtrans Snap API
database/
├── migrations/                    # 10 file migrasi database
└── seeders/                       # DatabaseSeeder, ProductSeeder, VendorSeeder, CriteriaSeeder
resources/views/
├── marketplace/                   # Katalog bundling, layanan satuan, & SPK rekomendasi
├── customer/                      # Riwayat pesanan, invoice & pembayaran Midtrans Snap
└── admin/                         # Transparansi matriks SPK MARCOS & manajemen sistem
```

---

## 📜 Lisensi
Aplikasi ini dikembangkan untuk kebutuhan platform E-Marketplace Jasa Pernikahan B2C berstandar industri dengan hak cipta milik Glow & Grace Wedding Marketplace.
