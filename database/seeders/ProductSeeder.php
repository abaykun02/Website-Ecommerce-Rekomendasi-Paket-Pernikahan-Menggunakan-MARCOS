<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ServiceItem;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Categories
        $categoriesData = [
            [
                'name' => 'Paket Bundling Utama',
                'slug' => 'paket-bundling',
                'icon' => 'sparkles',
                'description' => 'Paket all-in lengkap kombinasi MUA tata rias pengantin & dekorasi pelaminan eksklusif.',
            ],
            [
                'name' => 'Make-Up Only',
                'slug' => 'make-up-only',
                'icon' => 'face-smile',
                'description' => 'Layanan tata rias wajah pengantin (Akad, Resepsi, Prewedding) dengan hasil flawless & tahan lama.',
            ],
            [
                'name' => 'Dekorasi Only',
                'slug' => 'dekorasi-only',
                'icon' => 'home-modern',
                'description' => 'Dekorasi panggung pelaminan, mini gallery, photo booth, dan tata ruang resepsi megah.',
            ],
            [
                'name' => 'Dekorasi Akad',
                'slug' => 'dekorasi-akad',
                'icon' => 'heart',
                'description' => 'Konsep dekorasi akad nikah intimate, backdrop minimalis, meja akad aesthetic, dan tata bunga segar.',
            ],
            [
                'name' => 'Gaun & Busana Pengantin',
                'slug' => 'gaun-busana',
                'icon' => 'sparkles',
                'description' => 'Sewa dan fitting gaun pengantin modern internasional, ballgown swarovski, dan dress muslimah syari.',
            ],
            [
                'name' => 'Baju Adat & Tradisional',
                'slug' => 'baju-adat',
                'icon' => 'globe-alt',
                'description' => 'Koleksi busana pengantin tradisional khas Nusantara: Melayu, Sunda, Jawa, Batak, Minang, dll.',
            ],
        ];

        $categoryModels = [];
        foreach ($categoriesData as $c) {
            $categoryModels[$c['slug']] = Category::updateOrCreate(
                ['slug' => $c['slug']],
                $c
            );
        }

        // 2. Fetch Vendors
        $vendors = Vendor::all()->keyBy('vendor_name');

        if ($vendors->isEmpty()) {
            return;
        }

        // Helper to safely get vendor id
        $getVendorId = function ($keyword) use ($vendors) {
            foreach ($vendors as $name => $vendor) {
                if (stripos($name, $keyword) !== false) {
                    return $vendor->id;
                }
            }
            return $vendors->first()->id;
        };

        $dyraId = $getVendorId('Dyra');
        $syarifahId = $getVendorId('Syarifah');
        $adillaaId = $getVendorId('Adilla');
        $feezaId = $getVendorId('Feeza');
        $alesyaId = $getVendorId('Alesya');
        $meccaId = $getVendorId('Mecca');

        // 3. Service Items Data yang 100% SESUAI dengan Nama Paket Asli di Direktori Vendor (Murni Tanpa Tanda Kurung)
        $items = [
            // =========================================================================
            // A. KATEGORI: MAKE-UP ONLY (Fokus Rias Pengantin Flawless)
            // =========================================================================
            [
                'vendor_id' => $syarifahId,
                'category_id' => $categoryModels['make-up-only']->id,
                'name' => 'Bundling 12',
                'slug' => 'bundling-12-syarifah',
                'badge' => 'Make-Up Only',
                'price' => 2000000,
                'image_url' => 'catalog_photos/Bundling 12.jpeg',
                'description' => 'Makeup wedding resepsi flawless + retouch riasan by Syarifah Make Up. Termasuk hijabdo styling rapi, free softlens, dan free makeup 1 orang keluarga/ibu. Batas waktu retouch sampai jam 6 sore.',
            ],
            [
                'vendor_id' => $adillaaId,
                'category_id' => $categoryModels['make-up-only']->id,
                'name' => 'Akad Pakage',
                'slug' => 'akad-pakage-adilla',
                'badge' => 'Make-Up Only',
                'price' => 1500000,
                'image_url' => 'catalog_photos/AKAD PACKAGE.jpeg',
                'description' => 'Makeup service pengantin akad nikah by Adilla Makeup, dilengkapi 1 set gown untuk CPP & CPW, clean hijabdo styling, melati imitasi premium, dan free softlens berkualitas.',
            ],
            [
                'vendor_id' => $adillaaId,
                'category_id' => $categoryModels['make-up-only']->id,
                'name' => 'Akad + Resepsi Pakage',
                'slug' => 'akad-resepsi-pakage-adilla',
                'badge' => 'Make-Up Only',
                'price' => 2000000,
                'image_url' => 'catalog_photos/AKAD+RESEPSI PACKAGE.jpeg',
                'description' => 'Makeup service akad & resepsi di hari yang sama by Adilla Makeup: 3 set gown mix adat untuk CPP & CPW, retouch, MUA standby sampai jam 5 sore, clean hijabdo, softlens, dan free makeup family bride 1 orang.',
            ],

            // =========================================================================
            // B. KATEGORI: DEKORASI ONLY (Fokus Dekorasi Pelaminan & Panggung Resepsi)
            // =========================================================================
            [
                'vendor_id' => $dyraId,
                'category_id' => $categoryModels['dekorasi-only']->id,
                'name' => 'Mini Pakage',
                'slug' => 'mini-pakage-dyra',
                'badge' => 'Dekorasi Only',
                'price' => 10000000,
                'image_url' => 'catalog_photos/Mini Pakage.jpeg',
                'description' => 'Paket pelaminan 6 meter ekonomis Dyraaerlia: tenda 5 gawang, pentas kibot & pelaminan, kursi 150 (50 balut), alat dapur lengkap, meja VIP 2, pondok es & kue, tenda terima tamu, dan kursi akad.',
            ],
            [
                'vendor_id' => $dyraId,
                'category_id' => $categoryModels['dekorasi-only']->id,
                'name' => 'Medium Pakage',
                'slug' => 'medium-pakage-dyra',
                'badge' => 'Dekorasi Only',
                'price' => 12000000,
                'image_url' => 'catalog_photos/Medium Pakage.jpeg',
                'description' => 'Paket pelaminan 8 meter Dyraaerlia: tenda 6 gawang, pentas kibot & pentas pelaminan, kursi 200 balut 100, alat dapur, meja VIP 3, pondok es & kue, tenda terima tamu, dan kursi akad depan pelaminan.',
            ],
            [
                'vendor_id' => $dyraId,
                'category_id' => $categoryModels['dekorasi-only']->id,
                'name' => 'Lux Pakage',
                'slug' => 'lux-pakage-dyra',
                'badge' => 'Dekorasi Only',
                'price' => 13000000,
                'image_url' => 'catalog_photos/Lux Pakage.jpeg',
                'description' => 'Paket wedding mewah 7 gawang Dyraaerlia: pelaminan 8 meter megah, kursi 200 balut 100, alat dapur, meja VIP 3, pondok es & kue, tenda terima tamu, lampu hias & izin PLN terpadu.',
            ],
            [
                'vendor_id' => $syarifahId,
                'category_id' => $categoryModels['dekorasi-only']->id,
                'name' => 'Bundling 1',
                'slug' => 'bundling-1-syarifah',
                'badge' => 'Dekorasi Only',
                'price' => 9500000,
                'image_url' => 'catalog_photos/Bundling 1.jpeg',
                'description' => 'Pelaminan 5 meter Syarifah Make Up: tenda pelaminan + pentas 5m, tenda tamu 2 gawang sisir, tenda kibot 1 gawang + pentas kibot, bekakas dapur 1 set, meja hidang 2 + meja buah 1 + kursi 100, foto 2 roll.',
            ],
            [
                'vendor_id' => $syarifahId,
                'category_id' => $categoryModels['dekorasi-only']->id,
                'name' => 'Bundling 2',
                'slug' => 'bundling-2-syarifah',
                'badge' => 'Dekorasi Only',
                'price' => 10500000,
                'image_url' => 'catalog_photos/Bundling 2.jpeg',
                'description' => 'Pelaminan 6 meter Syarifah Make Up: tenda pelaminan + pentas 6m, tenda tamu 3 gawang sisir, tenda kibot 1 gawang + pentas kibot, bekakas dapur 1 set, meja hidang 2, meja buah 1, kursi 100 pcs, foto 2 roll.',
            ],
            [
                'vendor_id' => $syarifahId,
                'category_id' => $categoryModels['dekorasi-only']->id,
                'name' => 'Bundling 6',
                'slug' => 'bundling-6-syarifah',
                'badge' => 'Dekorasi Only',
                'price' => 19000000,
                'image_url' => 'catalog_photos/Bundling 6.jpeg',
                'description' => 'Pelaminan 8 meter Syarifah Make Up: altar jalan cermin gate masuk, decor akad dalam rumah, tenda pelaminan 8m, tenda tamu 6 gawang sisir, full flooring, kain tutup keliling, blower pendingin, foto 4 roll + video cinematik.',
            ],
            [
                'vendor_id' => $feezaId,
                'category_id' => $categoryModels['dekorasi-only']->id,
                'name' => 'Wedding Pakage 1',
                'slug' => 'wedding-pakage-1-feeza',
                'badge' => 'Dekorasi Only',
                'price' => 10000000,
                'image_url' => 'catalog_photos/Wedding Pakage 1.jpeg',
                'description' => 'Paket wedding standar Feeza Wedding Kisaran: pelaminan 8m adat/nasional megah, gaun pengantin mewah, tenda resepsi semi-dekorasi, kursi tamu 150 pcs, meja hidang, dan dokumentasi foto 2 roll.',
            ],
            [
                'vendor_id' => $feezaId,
                'category_id' => $categoryModels['dekorasi-only']->id,
                'name' => 'Wedding Pakage 2',
                'slug' => 'wedding-pakage-2-feeza',
                'badge' => 'Dekorasi Only',
                'price' => 15000000,
                'image_url' => 'catalog_photos/Wedding Pakage 2.jpeg',
                'description' => 'Paket wedding megah Feeza Wedding Kisaran: pelaminan 12m adat/modern, tenda VIP dekorasi ombak & full flooring, lorong karpet jalan bunga & full tata lampu panggung, 3 pasang busana pengantin akad & resepsi.',
            ],
            [
                'vendor_id' => $alesyaId,
                'category_id' => $categoryModels['dekorasi-only']->id,
                'name' => 'Paket Wedding Gedung Luxury',
                'slug' => 'paket-wedding-gedung-luxury-alesya',
                'badge' => 'Dekorasi Only',
                'price' => 21000000,
                'image_url' => 'catalog_photos/Paket Wedding Gedung Luxury.jpeg',
                'description' => 'Dekorasi pelaminan gedung 10-12 meter luxury Alesya Gallery: taman pelaminan fresh flower, set meja akad tiffani, altar jalan, gazebo, tata lighting megah, photobooth, mirror sign, WO include MC & 3 crew.',
            ],

            // =========================================================================
            // C. KATEGORI: DEKORASI AKAD (Backdrop & Set Akad Nikah Intimate)
            // =========================================================================
            [
                'vendor_id' => $dyraId,
                'category_id' => $categoryModels['dekorasi-akad']->id,
                'name' => 'Akad Pakage',
                'slug' => 'akad-pakage-dyra',
                'badge' => 'Dekorasi Akad',
                'price' => 4000000,
                'image_url' => 'catalog_photos/Akad Pakage.jpeg',
                'description' => 'Khusus acara ijab qabul khidmat Dyraaerlia: backdrop akad 2-3 meter modern, meja akad, lighting standart, welcome sign, cinematic video, foto unlimited (5 jam kerja), cetak magazine, makeup akad & baju sepasang.',
            ],
            [
                'vendor_id' => $syarifahId,
                'category_id' => $categoryModels['dekorasi-akad']->id,
                'name' => 'Bundling 10',
                'slug' => 'bundling-10-syarifah',
                'badge' => 'Dekorasi Akad',
                'price' => 2500000,
                'image_url' => 'catalog_photos/Bundling 10.jpeg',
                'description' => 'Paket minimalis 3-in-1 Syarifah Make Up: dekorasi indoor 3x3 meter, bucket bunga, kursi, karpet, meja akad. Dokumentasi durasi maksimal 3 jam, foto tak terbatas, album foto, makeup pengantin & attire 1 pasang.',
            ],
            [
                'vendor_id' => $adillaaId,
                'category_id' => $categoryModels['dekorasi-akad']->id,
                'name' => 'Akad Intimate Pakage',
                'slug' => 'akad-intimate-pakage-adilla',
                'badge' => 'Dekorasi Akad',
                'price' => 3200000,
                'image_url' => 'catalog_photos/Akad Intimate Pakage.jpeg',
                'description' => 'Akad intimate package Adilla: dekorasi indoor ukuran 3x3 meter, free mirror sign, photo unlimited, cetak album wedding book, frame 8x12 inch, makeup pengantin, hijab do, sepasang baju akad, henna maroon.',
            ],
            [
                'vendor_id' => $meccaId,
                'category_id' => $categoryModels['dekorasi-akad']->id,
                'name' => 'Paket Akad Intimate Mecca',
                'slug' => 'paket-akad-intimate-mecca-gallery',
                'badge' => 'Dekorasi Akad',
                'price' => 2500000,
                'image_url' => 'catalog_photos/Akad Intimate Pakage.jpeg',
                'description' => 'Akad Intimate Package Mecca Gallery: dekorasi indoor 3x3 meter, free mirror sign, dokumentasi file photo unlimited, album wedding book, foto plus bingkai 8x12 inch, makeup + hijab do, sepasang baju akad, henna.',
            ],

            // =========================================================================
            // D. KATEGORI: GAUN & BUSANA PENGANTIN (Attire Resepsi & Prewedding)
            // =========================================================================
            [
                'vendor_id' => $syarifahId,
                'category_id' => $categoryModels['gaun-busana']->id,
                'name' => 'Bundling 11',
                'slug' => 'bundling-11-syarifah',
                'badge' => 'Gaun & Busana',
                'price' => 3500000,
                'image_url' => 'catalog_photos/Bundling 11.jpeg',
                'description' => 'Paket makeup resepsi + retouch Syarifah: 3 pasang attire pengantin koleksi by syr_gown, aksesoris pengantin lengkap, hijab do styling, free softlens, free makeup 1 orang keluarga.',
            ],
            [
                'vendor_id' => $syarifahId,
                'category_id' => $categoryModels['gaun-busana']->id,
                'name' => 'Bundling 14',
                'slug' => 'bundling-14-syarifah',
                'badge' => 'Gaun & Busana',
                'price' => 5000000,
                'image_url' => 'catalog_photos/Bundling 14.jpeg',
                'description' => 'Makeup wedding + retouch Syarifah: 3 pasang attire premium eksklusif, hijabdo styling, aksesoris lengkap, free softlens, free makeup 1 orang keluarga/ibu, ronce melati asli.',
            ],
            [
                'vendor_id' => $adillaaId,
                'category_id' => $categoryModels['gaun-busana']->id,
                'name' => 'Prewedding Pakage',
                'slug' => 'prewedding-pakage-adilla',
                'badge' => 'Gaun & Busana',
                'price' => 2100000,
                'image_url' => 'catalog_photos/Prewedding Pakage.jpeg',
                'description' => 'Prewedding package Adilla Makeup: 2 session indoor include studio, makeup service by Adilla, softlens, hijabdo, 1 set premium gown bebas pilih, cetak 24 inch + frame, 4 cetak 6 inch + frame.',
            ],
            [
                'vendor_id' => $meccaId,
                'category_id' => $categoryModels['gaun-busana']->id,
                'name' => 'Pretty Gown Premium Exclusive',
                'slug' => 'pretty-gown-premium-mecca',
                'badge' => 'Gaun & Busana',
                'price' => 3500000,
                'image_url' => 'catalog_photos/Bundling 13.jpeg',
                'description' => 'Layanan Pretty Gown Mecca Gallery: riasan akad & resepsi, retouch 2x, 3 pasang gaun/attire mix adat, 1 aksesori adat pengantin, free softlens, melati akad, dan henna maroon.',
            ],

            // =========================================================================
            // E. KATEGORI: BAJU ADAT & TRADISIONAL (Koleksi Busana Adat Nusantara)
            // =========================================================================
            [
                'vendor_id' => $dyraId,
                'category_id' => $categoryModels['baju-adat']->id,
                'name' => 'Wedding Silver Pakage',
                'slug' => 'wedding-silver-pakage-dyra',
                'badge' => 'Baju Adat',
                'price' => 16000000,
                'image_url' => 'catalog_photos/Medium Pakage.jpeg',
                'description' => 'Paket Silver eksklusif Dyra: makeup pengantin + baju 3 pasang (mix 1 adat), tenda 5 plong matahari, pelaminan 8m, meja VIP 2, kursi 150 balut, panstove 2 set, photoshoot s/d 20.00 WIB, album magnetik, red henna.',
            ],
            [
                'vendor_id' => $adillaaId,
                'category_id' => $categoryModels['baju-adat']->id,
                'name' => 'Wedding Pakage 2',
                'slug' => 'wedding-pakage-2-adilla',
                'badge' => 'Baju Adat',
                'price' => 4000000,
                'image_url' => 'catalog_photos/Wedding Pakage 2.jpeg',
                'description' => 'Paket pelaminan 6 meter Adilla: makeup resepsi + baju 4 pasang + acc adat pengantin, tenda tamu 3 gang & tenda pentas, meja prasmanan 2 set, kursi 200 balut 100, alat dapur, foto 4 roll, video syuting 2 jam.',
            ],
            [
                'vendor_id' => $adillaaId,
                'category_id' => $categoryModels['baju-adat']->id,
                'name' => 'Wedding Pakage 3',
                'slug' => 'wedding-pakage-3-adilla',
                'badge' => 'Baju Adat',
                'price' => 15000000,
                'image_url' => 'catalog_photos/Wedding Pakage 3.jpeg',
                'description' => 'Pelaminan indoor max 4 meter Adilla: MUA makeup full retouch, attire adat & acc 1 pasang, attire nasional premium 1 pasang, reguler 1 pasang, tenda matahari 4, kursi 100, foto 3 roll, album magnetic, video 2 disk.',
            ],
            [
                'vendor_id' => $alesyaId,
                'category_id' => $categoryModels['baju-adat']->id,
                'name' => 'Paket Wedding Standart Rumah',
                'slug' => 'paket-wedding-standart-rumah-alesya',
                'badge' => 'Baju Adat',
                'price' => 10000000,
                'image_url' => 'catalog_photos/Paket Wedding Standart Rumah.jpeg',
                'description' => 'Paket Standart Rumah Alesya Gallery: MUA makeup akad & resepsi, 3 baju (mix 1 adat) + ACC, tenda 5 plong (sisir), pelaminan 4-5 meter, kursi 100 pcs, gapura masuk, foto unlimited, cetak album 100 pcs, all file G-Drive.',
            ],
            [
                'vendor_id' => $alesyaId,
                'category_id' => $categoryModels['baju-adat']->id,
                'name' => 'Paket Wedding Rumah',
                'slug' => 'paket-wedding-rumah-alesya',
                'badge' => 'Baju Adat',
                'price' => 23000000,
                'image_url' => 'catalog_photos/PAKET WEDDING RUMAH 23 JT.jpeg',
                'description' => 'Paket Wedding Rumah Alesya Gallery: 3 baju pengantin (mix 1 adat) + ACC, makeup + retouch, tenda 8 plong tanpa tiang tengah, full flooring karpet, pelaminan 8-10 meter, foto unlimited, video cinematic, blower 2 unit.',
            ],
        ];

        // Bersihkan seluruh data lama agar tidak ada nama fiktif atau slug lama yang tersisa
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        ServiceItem::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        foreach ($items as $item) {
            ServiceItem::create($item);
        }
    }
}
