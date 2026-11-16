<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class UserAndVendorSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Account (Email/Username: Admin, Password: 123 - Plain Text)
        User::updateOrCreate(
            ['email' => 'Admin'],
            [
                'name' => 'Administrator Glow & Grace',
                'password' => '123',
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Medan, Sumatera Utara',
            ]
        );

        // 2. Customer Accounts (Password: 123)
        User::updateOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Sarah Amanda',
                'password' => '123',
                'role' => 'customer',
                'phone' => '082167891234',
                'address' => 'Jl. Karya Wisata No. 45, Medan Johor',
            ]
        );

        User::updateOrCreate(
            ['email' => 'siti@gmail.com'],
            [
                'name' => 'Siti Rahma',
                'password' => '123',
                'role' => 'customer',
                'phone' => '085278912345',
                'address' => 'Jl. Ringroad No. 12, Medan Selayang',
            ]
        );

        // 3. Real Proposal Vendors (Password: 123)
        $vendorsData = [
            [
                'email' => 'dyra@glowgrace.com',
                'name' => 'Dyra Erlia',
                'vendor_name' => 'Dyra Erlia Makeup & Decoration',
                'owner_name' => 'Dyra Erlia',
                'location_address' => 'GQPC+Q37, Gg. Swadaya, Bangun Sari Baru, Kec. Tj. Morawa, Kabupaten Deli Serdang, Sumatera Utara 20362',
                'google_maps_url' => 'https://www.google.com/maps/search/?api=1&query=GQPC%2BQ37%2C+Gg.+Swadaya%2C+Bangun+Sari+Baru%2C+Kec.+Tj.+Morawa%2C+Kabupaten+Deli+Serdang%2C+Sumatera+Utara+20362',
                'phone' => '081376054211',
                'description' => 'Spesialis Wedding Makeup Artist dan Dekorasi Modern Elegan di Deli Serdang & Medan. Menghadirkan sentuhan riasan flawless tahan lama dan dekorasi panggung pelaminan berkelas.',
                'bank_name' => 'BCA',
                'bank_account_number' => '828-0912-334',
                'bank_account_name' => 'Dyra Erlia',
                'bank_notes' => 'Harap cantumkan kode pesanan Anda pada berita transfer.',
            ],
            [
                'email' => 'adillaa@glowgrace.com',
                'name' => 'Adilla',
                'vendor_name' => 'Adilla Makeup',
                'owner_name' => 'Adilla',
                'location_address' => 'Gg. Sedap Malam, Limau Manis, Kec. Tj. Morawa, Kabupaten Deli Serdang, Sumatera Utara 20362',
                'google_maps_url' => 'https://www.google.com/maps/search/?api=1&query=Gg.+Sedap+Malam%2C+Limau+Manis%2C+Kec.+Tj.+Morawa%2C+Kabupaten+Deli+Serdang%2C+Sumatera+Utara+20362',
                'phone' => '083890749461',
                'description' => 'Layanan tata rias pengantin modern, intimate wedding package, dan tata busana pengantin lengkap dengan aksesori premium.',
                'bank_name' => 'BRI',
                'bank_account_number' => '0234-01-098765-50-1',
                'bank_account_name' => 'Adilla Makeup',
                'bank_notes' => 'Mohon konfirmasi ke WhatsApp kami setelah transfer berhasil.',
            ],
            [
                'email' => 'syarifah@glowgrace.com',
                'name' => 'Syarifah',
                'vendor_name' => 'Syarifah Makeup',
                'owner_name' => 'Syarifah',
                'location_address' => 'Bakaran Batu, Kec. Batang Kuis, Kabupaten Deli Serdang, Sumatera Utara 20372',
                'google_maps_url' => 'https://www.google.com/maps/search/?api=1&query=Bakaran+Batu%2C+Kec.+Batang+Kuis%2C+Kabupaten+Deli+Serdang%2C+Sumatera+Utara+20372',
                'phone' => '085262264549',
                'description' => 'MUA profesional berpengalaman untuk acara akad nikah, resepsi adat, hijab styling pengantin, dan dekorasi bunga modern minimalis.',
                'bank_name' => 'Mandiri',
                'bank_account_number' => '105-00-443322-1',
                'bank_account_name' => 'Syarifah',
                'bank_notes' => 'Bisa transfer melalui Mandiri Livin atau jaringan ATM Bersama.',
            ],
            [
                'email' => 'feeza@glowgrace.com',
                'name' => 'Feeza Wedding',
                'vendor_name' => 'Feeza Wedding Kisaran',
                'owner_name' => 'Feeza',
                'location_address' => 'Duta Mas 14 Kisaran Residence, Jl. Sanusi Pane, Mutiara, Kec. Kota Kisaran Timur, Kabupaten Asahan, Sumatera Utara 21223',
                'google_maps_url' => 'https://www.google.com/maps/search/?api=1&query=Duta+Mas+14+Kisaran+Residence%2C+Jl.+Sanusi+Pane%2C+Mutiara%2C+Kec.+Kota+Kisaran+Timur%2C+Kabupaten+Asahan%2C+Sumatera+Utara+21223',
                'phone' => '082269701502',
                'description' => 'Vendor wedding organizer, dekorasi pelaminan megah, dan riasan pengantin terlengkap di Asahan, Kisaran, dan sekitarnya.',
                'bank_name' => 'BSI (Bank Syariah Indonesia)',
                'bank_account_number' => '714-8899-001',
                'bank_account_name' => 'Feeza Wedding',
                'bank_notes' => 'Rekening resmi Feeza Wedding Kisaran.',
            ],
            [
                'email' => 'alesya@glowgrace.com',
                'name' => 'Alesya',
                'vendor_name' => 'Alesya Gallery Wedding',
                'owner_name' => 'Alesya',
                'location_address' => 'JMXV+WG9, Mabar Hilir, Kec. Medan Deli, Kota Medan, Sumatera Utara 20241',
                'google_maps_url' => 'https://www.google.com/maps/search/?api=1&query=JMXV%2BWG9%2C+Mabar+Hilir%2C+Kec.+Medan+Deli%2C+Kota+Medan%2C+Sumatera+Utara+20241',
                'phone' => '082182202669',
                'description' => 'Galeri dekorasi pernikahan mewah untuk rumah dan gedung, paket bundling komplit dengan tim lighting dan tata rias pengantin eksklusif.',
                'bank_name' => 'BNI',
                'bank_account_number' => '098-7654-321',
                'bank_account_name' => 'Alesya Gallery Wedding',
                'bank_notes' => 'Sertakan nama pemesan pada kolom berita transfer.',
            ],
            [
                'email' => 'mecca@glowgrace.com',
                'name' => 'Mecca Gallery',
                'vendor_name' => 'Mecca Gallery',
                'owner_name' => 'Mecca',
                'location_address' => 'Jl. Willem Iskandar / Pasar V, Medan Estate, Kec. Percut Sei Tuan, Deli Serdang, Sumatera Utara',
                'google_maps_url' => 'https://www.google.com/maps/search/?api=1&query=Mecca+Gallery+Medan',
                'phone' => '082382086993',
                'description' => 'Layanan Pretty Gown Premium Exclusive, rias pengantin hijab/nasional modern, dan dekorasi intimate akad minimalis elegan.',
                'bank_name' => 'BCA',
                'bank_account_number' => '800-1122-334',
                'bank_account_name' => 'Mecca Gallery',
                'bank_notes' => 'Transfer via m-BCA atau ATM BCA.',
            ],
        ];

        foreach ($vendorsData as $v) {
            $user = User::updateOrCreate(
                ['email' => $v['email']],
                [
                    'name' => $v['name'],
                    'password' => '123', // Plain text
                    'role' => 'vendor',
                    'phone' => $v['phone'],
                    'address' => $v['location_address'],
                ]
            );

            Vendor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'vendor_name' => $v['vendor_name'],
                    'owner_name' => $v['owner_name'],
                    'location_address' => $v['location_address'],
                    'google_maps_url' => $v['google_maps_url'],
                    'phone' => $v['phone'],
                    'description' => $v['description'],
                    'bank_name' => $v['bank_name'] ?? null,
                    'bank_account_number' => $v['bank_account_number'] ?? null,
                    'bank_account_name' => $v['bank_account_name'] ?? null,
                    'bank_notes' => $v['bank_notes'] ?? null,
                ]
            );
        }
    }
}
