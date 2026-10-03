<?php

namespace Database\Seeders;

use App\Models\Bundling;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class BundlingSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = Vendor::all()->keyBy('vendor_name');

        $packages = [
            // 1. Dyra Erlia Make-Up & Decoration
            [
                'vendor' => 'Dyra Erlia Make-Up & Decoration',
                'package_name' => 'Medium Package',
                'description' => 'Tata rias pengantin 2 pasang baju, pelaminan medium 6-8 meter, dekorasi kamar pengantin, mini backdrop akad, & henna art.',
                'base_price' => 12000000,
            ],
            [
                'vendor' => 'Dyra Erlia Make-Up & Decoration',
                'package_name' => 'Mini Package',
                'description' => 'Riasan pengantin akad nikah, 1 pasang busana premium, pelaminan mini minimalis 4 meter, bunga segar & standing flower.',
                'base_price' => 7500000,
            ],
            [
                'vendor' => 'Dyra Erlia Make-Up & Decoration',
                'package_name' => 'Lux Package',
                'description' => 'Paket eksklusif: Rias pengantin akad + resepsi 3x ganti busana, pelaminan mewah 10-12 meter, pencahayaan panggung pro, karpet jalan, gate masuk & photobooth.',
                'base_price' => 22000000,
            ],
            [
                'vendor' => 'Dyra Erlia Make-Up & Decoration',
                'package_name' => 'Wedding Silver Package',
                'description' => 'Rias pengantin akad + resepsi, busana sepasang + orang tua, dekor pelaminan 8 meter nuansa rustic elegan & pergola masuk.',
                'base_price' => 15500000,
            ],
            [
                'vendor' => 'Dyra Erlia Make-Up & Decoration',
                'package_name' => 'Standart Package',
                'description' => 'Rias wajah flawless pengantin resepsi, pelaminan standart 6 meter, meja penerima tamu & kotak angpau dekoratif.',
                'base_price' => 9500000,
            ],
            [
                'vendor' => 'Dyra Erlia Make-Up & Decoration',
                'package_name' => 'Akad Package',
                'description' => 'Khusus acara ijab qabul khidmat: Rias pengantin flawless tahan lama, 1 pasang baju akad + melati segar, backdrop akad cantik 3 meter.',
                'base_price' => 5000000,
            ],

            // 2. Syarifah Make-Up
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 1 (Akad Simpel)',
                'description' => 'Make up akad nikah hijab/non hijab, busana akad pengantin, softlens, melati ronce, & backdrop akad bunga artifisial.',
                'base_price' => 4500000,
            ],
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 2 (Resepsi Manis)',
                'description' => 'Make up resepsi 2 baju, pelaminan 6 meter, panggung musik mini, bunga hidup 50%, & hand bouquet.',
                'base_price' => 8800000,
            ],
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 3 (Adat Melayu/Batak)',
                'description' => 'Riasan pengantin adat tradisional lengkap dengan mahkota/suntiang/tengkuluk, busana adat songket mewah, dekorasi pelaminan etnik kontemporer 8 meter.',
                'base_price' => 14000000,
            ],
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 4 (Modern Glam)',
                'description' => 'Riasan pengantin tren internasional glitz & glam, pelaminan semi-akrilik 8 meter, lampu gantung kristal, & standing flower lorong.',
                'base_price' => 16500000,
            ],
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 5 (Intimate Garden)',
                'description' => 'Konsep outdoor/semi-outdoor intimate: Rias pengantin natural glowing, dekorasi arch pelaminan kayu modern & fairy lights.',
                'base_price' => 11000000,
            ],
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 6 (Akad + Resepsi Day)',
                'description' => 'Paket satu hari full: Rias akad pagi + resepsi siang, 2x touch up, busana 2 pasang, dekorasi pelaminan 8m.',
                'base_price' => 13500000,
            ],
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 7 (Rumah Idaman)',
                'description' => 'Paket pernikahan di rumah: Tenda dekorasi plafon serut 2 plong, pelaminan rumah 6 meter, rias pengantin 2 pasang baju.',
                'base_price' => 12500000,
            ],
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 8 (Pesta Rakyat Elegan)',
                'description' => 'Tenda dekorasi 3 plong, pelaminan 8 meter, riasan pengantin + ibu kedua mempelai, kotak sumbangan.',
                'base_price' => 15000000,
            ],
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 9 (Exclusive Gold)',
                'description' => 'Make up premium artist, pelaminan 10m dengan fresh flower 70%, karpet beludru, photobooth 3D.',
                'base_price' => 19000000,
            ],
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 10 (Gedung Silver)',
                'description' => 'Paket gedung ekonomis: Rias pengantin 2 pasang, pelaminan gedung 8-10m, red carpet, standing photo 4 pcs.',
                'base_price' => 17500000,
            ],
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 11 (Gedung Platinum)',
                'description' => 'Pelaminan gedung 12-14 meter, lighting parled moving head, rias pengantin adat/nasional, ronce melati pesanan.',
                'base_price' => 24000000,
            ],
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 12 (Minimalist White)',
                'description' => 'Nuansa serba putih suci: Pelaminan styrofoam ukir modern 6 meter, rias pengantin hijab syar\'i flawless.',
                'base_price' => 9000000,
            ],
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 13 (Sweet Pastel)',
                'description' => 'Nuansa warna peach & lilac pastel, bunga meja prasmanan, pelaminan 7 meter, rias pengantin natural barbie look.',
                'base_price' => 10500000,
            ],
            [
                'vendor' => 'Syarifah Make-Up',
                'package_name' => 'Bundling 14 (Royal Majesty)',
                'description' => 'Paket terlengkap mega wedding: Riasan pengantin + 4 orang tua + 4 bridesmaid, pelaminan megah 16m full fresh flower & instalasi lampu kristal.',
                'base_price' => 32000000,
            ],

            // 3. Adillaa Make-Up
            [
                'vendor' => 'Adillaa Make-Up',
                'package_name' => 'Akad Package',
                'description' => 'Make up akad nikah bold/soft glam, busana pengantin akad, ronce melati, dekorasi meja akad & backdrop 3 meter.',
                'base_price' => 4800000,
            ],
            [
                'vendor' => 'Adillaa Make-Up',
                'package_name' => 'Wedding Package 1',
                'description' => 'Rias pengantin akad & resepsi (2 baju), pelaminan 6 meter minimalis, dekor kamar pengantin.',
                'base_price' => 10000000,
            ],
            [
                'vendor' => 'Adillaa Make-Up',
                'package_name' => 'Wedding Package 2',
                'description' => 'Rias pengantin 3 baju, rias orang tua (2 pasang), pelaminan 8 meter bunga fresh & artifisial mix, gate masuk, kotak amplop.',
                'base_price' => 14500000,
            ],
            [
                'vendor' => 'Adillaa Make-Up',
                'package_name' => 'Akad + Resepsi Package',
                'description' => 'Layanan hari H komplit: 2x makeup sesi, 3 pasang gaun & beskap pengantin, dekorasi pelaminan modern 8 meter & karpet jalan.',
                'base_price' => 13000000,
            ],
            [
                'vendor' => 'Adillaa Make-Up',
                'package_name' => 'Akad Intimate Package',
                'description' => 'Khusus acara intimate nikah keluarga: Rias flawless, busana akad, backdrop bunga 4 meter, handbouquet & corsage.',
                'base_price' => 6500000,
            ],
            [
                'vendor' => 'Adillaa Make-Up',
                'package_name' => 'Wedding Package 3',
                'description' => 'Paket super komplit: Rias pengantin + rias 4 pagar ayu/bridesmaid, pelaminan mewah 10 meter, gate jalan bunga segar & photobooth.',
                'base_price' => 18000000,
            ],
            [
                'vendor' => 'Adillaa Make-Up',
                'package_name' => 'Prewedding Package',
                'description' => 'Make up 2 tema prewedding (outdoor/indoor studio), 2 set busana gaun kasual & modern, sewa properti bouquet & payung.',
                'base_price' => 3500000,
            ],

            // 4. Feeza Wedding Kisaran
            [
                'vendor' => 'Feeza Wedding Kisaran',
                'package_name' => 'Wedding Package 1 (Kisaran Gold)',
                'description' => 'Make up pengantin resepsi, sewa gaun pengantin mewah, pelaminan 8 meter adat/nasional, dekorasi panggung musik & tenda VIP.',
                'base_price' => 13500000,
            ],
            [
                'vendor' => 'Feeza Wedding Kisaran',
                'package_name' => 'Wedding Package 2 (Kisaran Platinum)',
                'description' => 'Make up pengantin akad & resepsi, 3 pasang busana, rias 2 pasang ortu, pelaminan megah 12 meter, lorong karpet bunga & full lighting.',
                'base_price' => 21000000,
            ],

            // 5. Alesya Gallery Wedding
            [
                'vendor' => 'Alesya Gallery Wedding',
                'package_name' => 'Paket Wedding Standart Rumah',
                'description' => 'Make up pengantin anggun, busana 2 pasang, pelaminan rumah 6-8 meter tema modern garden, meja penerima tamu & standing bunga.',
                'base_price' => 11500000,
            ],
            [
                'vendor' => 'Alesya Gallery Wedding',
                'package_name' => 'Paket Wedding Gedung Luxury',
                'description' => 'Rias pengantin high-end look tahan 16 jam, pelaminan gedung 12-16 meter konsep istana modern, pencahayaan panggung spektakuler, photobooth & mini gallery foto prewed.',
                'base_price' => 28000000,
            ],
            [
                'vendor' => 'Alesya Gallery Wedding',
                'package_name' => 'Paket Wedding Rumah',
                'description' => 'Solusi lengkap pernikahan di kediaman mempelai: Rias pengantin 2 sesi, pelaminan 8 meter, tenda semi-dekorasi 2 plong, meja prasmanan.',
                'base_price' => 14000000,
            ],
        ];

        $vendors = Vendor::all();
        $findVendor = function($name) use ($vendors) {
            $norm = fn($str) => strtolower(preg_replace('/[^a-z0-9]/', '', $str));
            return $vendors->first(fn($v) => $norm($v->vendor_name) === $norm($name) || str_contains($norm($v->vendor_name), substr($norm($name), 0, 6)));
        };

        foreach ($packages as $pkg) {
            $vendor = $findVendor($pkg['vendor']);
            if ($vendor) {
                Bundling::updateOrCreate(
                    [
                        'vendor_id' => $vendor->id,
                        'package_name' => $pkg['package_name'],
                    ],
                    [
                        'description' => $pkg['description'],
                        'base_price' => $pkg['base_price'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
