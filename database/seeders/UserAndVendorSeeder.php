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
                ]
            );
        }
    }
}
