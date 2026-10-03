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

        $curatedImages = [
            'https://images.unsplash.com/photo-1594465919760-441fe5908ab0?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1544077960-604201fe74bc?auto=format&fit=crop&w=900&q=80',
        ];

        // Seed gallery for each vendor
        foreach ($vendors as $index => $vendor) {
            $img1 = $curatedImages[($index * 2) % count($curatedImages)];
            $img2 = $curatedImages[($index * 2 + 1) % count($curatedImages)];

            Gallery::firstOrCreate(
                ['vendor_id' => $vendor->id, 'image_path' => $img1],
                ['caption' => 'Portfolio Tata Rias & Dekorasi Pelaminan - ' . $vendor->vendor_name]
            );

            Gallery::firstOrCreate(
                ['vendor_id' => $vendor->id, 'image_path' => $img2],
                ['caption' => 'Koleksi Busana & Rias Pengantin Eksklusif - ' . $vendor->vendor_name]
            );
        }

        // Seed gallery for each bundling
        foreach ($bundlings as $idx => $bundling) {
            $img = $curatedImages[$idx % count($curatedImages)];
            Gallery::firstOrCreate(
                ['vendor_id' => $bundling->vendor_id, 'bundling_id' => $bundling->id],
                [
                    'image_path' => $img,
                    'caption' => 'Foto Representasi ' . $bundling->package_name,
                ]
            );
        }
    }
}
