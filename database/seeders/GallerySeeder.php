<?php

namespace Database\Seeders;

use App\Models\Bundling;
use App\Models\Gallery;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $vendors = Vendor::all();
        $bundlings = Bundling::all();

        // 1. Koleksi foto portofolio karya pendukung (estetika riasan & dekorasi pelaminan)
        $curatedWorkPhotos = [
            'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1594465919760-441fe5908ab0?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1544077960-604201fe74bc?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1545232979-8bf68ee9b1af?auto=format&fit=crop&w=900&q=80',
        ];

        // 2. Pemetaan foto karya nyata (Thumbnail Utama Katalog - REF POSTER 2)
        // Berdasarkan analisis visual dan mapping dokumen revisi_gambar_paket_antigravity.md
        $bundlingThumbnails = [
            // Dyraaerlia Makeup
            'A1'  => 'catalog_photos/Medium Pakage.jpeg',
            'A2'  => 'catalog_photos/Mini Pakage.jpeg',
            'A3'  => 'catalog_photos/Lux Pakage.jpeg',
            'A4'  => 'catalog_photos/Medium Pakage.jpeg',
            'A5'  => 'catalog_photos/Standart Pakage.jpeg',
            'A6'  => 'catalog_photos/Akad Pakage.jpeg',
            // Syarifah Make Up
            'A7'  => 'catalog_photos/Bundling 1.jpeg',
            'A8'  => 'catalog_photos/Bundling 2.jpeg',
            'A9'  => 'catalog_photos/Bundling 3.jpeg',
            'A10' => 'catalog_photos/Bundling 4.jpeg',
            'A11' => 'catalog_photos/Bundling 5.jpeg',
            'A12' => 'catalog_photos/Bundling 6.jpeg',
            'A13' => 'catalog_photos/Bundling 7.jpeg',
            'A14' => 'catalog_photos/Bundling 8.jpeg',
            'A15' => 'catalog_photos/Bundling 9.jpeg',
            'A16' => 'catalog_photos/Bundling 10.jpeg',
            'A17' => 'catalog_photos/Bundling 11.jpeg',
            'A18' => 'catalog_photos/Bundling 12.jpeg',
            'A19' => 'catalog_photos/Bundling 13.jpeg',
            'A20' => 'catalog_photos/Bundling 14.jpeg',
            // Adilla Makeup
            'A21' => 'catalog_photos/AKAD PACKAGE.jpeg',
            'A22' => 'catalog_photos/Wedding Pakage 3.jpeg',
            'A23' => 'catalog_photos/Wedding Pakage 2.jpeg',
            'A24' => 'catalog_photos/AKAD+RESEPSI PACKAGE.jpeg',
            'A25' => 'catalog_photos/Akad Intimate Pakage.jpeg',
            'A26' => 'catalog_photos/Wedding Pakage 3.jpeg',
            'A27' => 'catalog_photos/Prewedding Pakage.jpeg',
            // Feeza Wedding (Tetap gunakan poster & portofolio yang ada di sistem)
            'A28' => 'posters/19.jpeg',
            'A29' => 'posters/20.jpeg',
            // Alesya Gallery Wedding
            'A30' => 'catalog_photos/Paket Wedding Standart Rumah.jpeg',
            'A31' => 'catalog_photos/Paket Wedding Gedung Luxury.jpeg',
            'A32' => 'catalog_photos/PAKET WEDDING RUMAH 23 JT.jpeg',
        ];

        // Pemetaan untuk paket katalog tambahan non-SPK
        $nameThumbnails = [
            'Paket Wedding Diamond' => 'catalog_photos/Wedding Pakage 1.jpeg',
            'Paket Wedding Gold' => 'catalog_photos/Wedding Pakage 2.jpeg',
            'Paket Wedding Platinum' => 'catalog_photos/Medium Pakage.jpeg',
            'Paket Akad Intimate Mecca' => 'catalog_photos/Akad Intimate Pakage.jpeg',
            'Pretty Gown Premium Exclusive' => 'catalog_photos/Bundling 11.jpeg',
            'Wedding Package Makeup & Gown Akad & Resepsi' => 'catalog_photos/AKAD+RESEPSI PACKAGE.jpeg',
        ];

        // 3. Pemetaan poster brosur resmi vendor (posters/1.jpeg s/d posters/24.jpeg)
        $bundlingPosters = [
            'A1'  => 'posters/7.jpeg',
            'A2'  => 'posters/8.jpeg',
            'A3'  => 'posters/9.jpeg',
            'A4'  => 'posters/18.jpeg',
            'A5'  => 'posters/10.jpeg',
            'A6'  => 'posters/21.jpeg',
            'A7'  => 'posters/6.jpeg',
            'A8'  => 'posters/6.jpeg',
            'A9'  => 'posters/6.jpeg',
            'A10' => 'posters/1.jpeg',
            'A11' => 'posters/1.jpeg',
            'A12' => 'posters/1.jpeg',
            'A13' => 'posters/5.jpeg',
            'A14' => 'posters/5.jpeg',
            'A15' => 'posters/5.jpeg',
            'A16' => 'posters/2.jpeg',
            'A17' => 'posters/4.jpeg',
            'A18' => 'posters/3.jpeg',
            'A19' => 'posters/3.jpeg',
            'A20' => 'posters/3.jpeg',
            'A21' => 'posters/11.jpeg',
            'A22' => 'posters/23.jpeg',
            'A23' => 'posters/24.jpeg',
            'A24' => 'posters/11.jpeg',
            'A25' => 'posters/15.jpeg',
            'A26' => 'posters/24.jpeg',
            'A27' => 'posters/16.jpeg',
            'A28' => 'posters/19.jpeg',
            'A29' => 'posters/20.jpeg',
            'A30' => 'posters/22.jpeg',
            'A31' => 'posters/14.jpeg',
            'A32' => 'posters/13.jpeg',
        ];

        $namePosters = [
            'Paket Wedding Platinum' => 'posters/19.jpeg',
            'Paket Wedding Gold' => 'posters/20.jpeg',
            'Paket Wedding Diamond' => 'posters/22.jpeg',
            'Pretty Gown Premium Exclusive' => 'posters/14.jpeg',
            'Paket Akad Intimate Mecca' => 'posters/15.jpeg',
            'Wedding Package Makeup & Gown Akad & Resepsi' => 'posters/13.jpeg',
        ];

        // 4. Brosur resmi per vendor (untuk galeri profil vendor)
        $vendorPosters = [
            'dyra' => ['posters/7.jpeg', 'posters/8.jpeg', 'posters/9.jpeg', 'posters/10.jpeg', 'posters/18.jpeg', 'posters/21.jpeg'],
            'syarifah' => ['posters/1.jpeg', 'posters/2.jpeg', 'posters/3.jpeg', 'posters/4.jpeg', 'posters/5.jpeg', 'posters/6.jpeg'],
            'adilla' => ['posters/11.jpeg', 'posters/12.jpeg', 'posters/15.jpeg', 'posters/16.jpeg', 'posters/23.jpeg', 'posters/24.jpeg'],
            'feeza' => ['posters/19.jpeg', 'posters/20.jpeg'],
            'alesya' => ['posters/13.jpeg', 'posters/14.jpeg', 'posters/22.jpeg'],
            'mecca' => ['posters/14.jpeg', 'posters/15.jpeg'],
        ];

        // Bersihkan data galeri lama
        Gallery::truncate();

        // 5. Seed galeri untuk setiap vendor (Koleksi portofolio & brosur resmi lengkap)
        foreach ($vendors as $vIdx => $vendor) {
            $vKey = 'syarifah';
            $vName = strtolower($vendor->vendor_name);
            foreach ($vendorPosters as $k => $imgs) {
                if (str_contains($vName, $k)) {
                    $vKey = $k;
                    break;
                }
            }

            // Foto portofolio karya vendor
            $vPhoto1 = $curatedWorkPhotos[($vIdx * 2) % count($curatedWorkPhotos)];
            $vPhoto2 = $curatedWorkPhotos[($vIdx * 2 + 1) % count($curatedWorkPhotos)];

            Gallery::create([
                'vendor_id' => $vendor->id,
                'bundling_id' => null,
                'image_path' => $vPhoto1,
                'caption' => 'Portofolio Tata Rias & Pelaminan - ' . $vendor->vendor_name,
            ]);

            Gallery::create([
                'vendor_id' => $vendor->id,
                'bundling_id' => null,
                'image_path' => $vPhoto2,
                'caption' => 'Koleksi Busana & Rias Pengantin - ' . $vendor->vendor_name,
            ]);

            // Poster-poster brosur resmi vendor
            $posters = $vendorPosters[$vKey] ?? ['posters/1.jpeg'];
            foreach ($posters as $pIdx => $pPath) {
                Gallery::create([
                    'vendor_id' => $vendor->id,
                    'bundling_id' => null,
                    'image_path' => $pPath,
                    'caption' => 'Brosur & Daftar Harga Resmi ' . $vendor->vendor_name . ' (Hal. ' . ($pIdx + 1) . ')',
                ]);
            }
        }

        // 6. Seed galeri untuk setiap paket bundling:
        //    - Slide 1 (THUMBNAIL UTAMA KATALOG): Foto karya rias/pengantin asli (REF POSTER 2)
        //    - Slide 2 (INFORMASI DETAIL BROSUR): Poster brosur resmi vendor (docs 1-24)
        //    - Slide 3 (GAMBAR SELINGAN): Foto portofolio karya estetika pelaminan
        foreach ($bundlings as $bIdx => $bundling) {
            $workPhoto = $curatedWorkPhotos[$bIdx % count($curatedWorkPhotos)];

            // Tentukan Thumbnail Utama (Slide 1)
            $thumbnail = null;
            if ($bundling->code && isset($bundlingThumbnails[$bundling->code])) {
                $thumbnail = $bundlingThumbnails[$bundling->code];
            } elseif (isset($nameThumbnails[$bundling->package_name])) {
                $thumbnail = $nameThumbnails[$bundling->package_name];
            } else {
                $thumbnail = 'catalog_photos/Medium Pakage.jpeg';
            }

            // Tentukan Poster Brosur (Slide 2)
            $poster = null;
            if ($bundling->code && isset($bundlingPosters[$bundling->code])) {
                $poster = $bundlingPosters[$bundling->code];
            } elseif (isset($namePosters[$bundling->package_name])) {
                $poster = $namePosters[$bundling->package_name];
            } else {
                $poster = 'posters/1.jpeg';
            }

            // Slide 1: Foto Karya Nyata Pengantin/Dekorasi (Thumbnail Utama Katalog)
            Gallery::create([
                'vendor_id' => $bundling->vendor_id,
                'bundling_id' => $bundling->id,
                'image_path' => $thumbnail,
                'caption' => 'Foto Portofolio Utama: ' . $bundling->package_name,
            ]);

            // Slide 2: Poster Brosur Resmi Vendor (Tetap Dipertahankan)
            Gallery::create([
                'vendor_id' => $bundling->vendor_id,
                'bundling_id' => $bundling->id,
                'image_path' => $poster,
                'caption' => 'Brosur & Rincian Paket Resmi: ' . $bundling->package_name,
            ]);

            // Slide 3: Foto Portofolio Tambahan (Gambar Selingan di Detail Produk)
            Gallery::create([
                'vendor_id' => $bundling->vendor_id,
                'bundling_id' => $bundling->id,
                'image_path' => $workPhoto,
                'caption' => 'Karya Riasan & Dekorasi: ' . $bundling->package_name,
            ]);
        }
    }
}