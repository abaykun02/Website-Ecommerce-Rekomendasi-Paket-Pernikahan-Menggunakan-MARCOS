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
        $adillaaId = $getVendorId('Adillaa');
        $feezaId = $getVendorId('Feeza');
        $alesyaId = $getVendorId('Alesya');

        // 3. Service Items Data
        $items = [
            // --- MAKE-UP ONLY ---
            [
                'vendor_id' => $dyraId,
                'category_id' => $categoryModels['make-up-only']->id,
                'name' => 'Rias Pengantin Akad Flawless Glow',
                'slug' => 'rias-pengantin-akad-flawless-glow',
                'badge' => 'Make-Up Only',
                'price' => 2500000,
                'image_url' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=900&q=80',
                'description' => 'Tata rias pengantin akad nikah dengan teknik complexion tahan 16 jam, soft glam eyes, premium lashes, dan touch-up kit eksklusif.',
            ],
            [
                'vendor_id' => $syarifahId,
                'category_id' => $categoryModels['make-up-only']->id,
                'name' => 'Rias Pengantin Resepsi Luxury Velvet',
                'slug' => 'rias-pengantin-resepsi-luxury-velvet',
                'badge' => 'Make-Up Only',
                'price' => 3000000,
                'image_url' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=900&q=80',
                'description' => 'Makeup resepsi glamour dengan efek velvet glow bercahaya, shading proporsional, soft lens premium, dan pemasangan ronce melati segar.',
            ],
            [
                'vendor_id' => $adillaaId,
                'category_id' => $categoryModels['make-up-only']->id,
                'name' => 'Soft Glam Barbie Bridal Makeup',
                'slug' => 'soft-glam-barbie-bridal-makeup',
                'badge' => 'Make-Up Only',
                'price' => 2200000,
                'image_url' => 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?auto=format&fit=crop&w=900&q=80',
                'description' => 'Look riasan ala boneka Barbie yang manis, natural glowing skin, lipstik ombre glossy, serta penataan hijab/hair do rapi kekinian.',
            ],
            [
                'vendor_id' => $feezaId,
                'category_id' => $categoryModels['make-up-only']->id,
                'name' => 'Signature Romantic Bridal Makeup & Hairdo',
                'slug' => 'signature-romantic-bridal-makeup-hairdo',
                'badge' => 'Make-Up Only',
                'price' => 2800000,
                'image_url' => 'https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=900&q=80',
                'description' => 'Riasan pengantin romantis dengan sentuhan eyeshadow peach-champagne, contouring natural, styling mahkota/headpiece dan free rias 1 ibu pendamping.',
            ],
            [
                'vendor_id' => $alesyaId,
                'category_id' => $categoryModels['make-up-only']->id,
                'name' => 'High Fashion Radiant Bridal Look',
                'slug' => 'high-fashion-radiant-bridal-look',
                'badge' => 'Make-Up Only',
                'price' => 3200000,
                'image_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=900&q=80',
                'description' => 'Pengalaman makeup couture internasional dengan brand kosmetik high-end (Dior, Charlotte Tilbury, MAC), kulit tampak berkilau sehat tanpa cakey.',
            ],

            // --- DEKORASI ONLY ---
            [
                'vendor_id' => $dyraId,
                'category_id' => $categoryModels['dekorasi-only']->id,
                'name' => 'Pelaminan Modern Floral Arch 6-8 Meter',
                'slug' => 'pelaminan-modern-floral-arch',
                'badge' => 'Dekorasi Only',
                'price' => 7500000,
                'image_url' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=900&q=80',
                'description' => 'Dekorasi pelaminan megah dengan panggung kayu lapis karpet, curved arch bernuansa putih pastel, kombinasi bunga segar & artificial premium, spotlight LED warm.',
            ],
            [
                'vendor_id' => $feezaId,
                'category_id' => $categoryModels['dekorasi-only']->id,
                'name' => 'Pelaminan Grand Luxury Rose Gold & Chandelier',
                'slug' => 'pelaminan-grand-luxury-rose-gold',
                'badge' => 'Dekorasi Only',
                'price' => 9500000,
                'image_url' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=900&q=80',
                'description' => 'Dekorasi pelaminan panggung luas 8-10 meter dilengkapi lampu kristal gantung (chandelier), gate masuk VIP, karpet jalan mawar, dan standing flower mewah.',
            ],
            [
                'vendor_id' => $syarifahId,
                'category_id' => $categoryModels['dekorasi-only']->id,
                'name' => 'Dekorasi Pelaminan Minimalis Botanical',
                'slug' => 'dekorasi-pelaminan-minimalis-botanical',
                'badge' => 'Dekorasi Only',
                'price' => 5500000,
                'image_url' => 'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?auto=format&fit=crop&w=900&q=80',
                'description' => 'Nuansa segar dedaunan eucalyptus dan monstera dipadu bunga putih gading, panel kayu geometris minimalis, kursi pelaminan sofa scandinavian.',
            ],
            [
                'vendor_id' => $alesyaId,
                'category_id' => $categoryModels['dekorasi-only']->id,
                'name' => 'Backdrop Pelaminan Rustic Warm Lights',
                'slug' => 'backdrop-pelaminan-rustic-warm-lights',
                'badge' => 'Dekorasi Only',
                'price' => 6800000,
                'image_url' => 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=900&q=80',
                'description' => 'Konsep rustic modern dengan palet warna earthy tone, untaian fairy lights hangat, pampas grass impor, dan inisial nama akrilik LED menyala.',
            ],

            // --- DEKORASI AKAD ---
            [
                'vendor_id' => $adillaaId,
                'category_id' => $categoryModels['dekorasi-akad']->id,
                'name' => 'Backdrop Akad Intimate Hexagon Floral',
                'slug' => 'backdrop-akad-intimate-hexagon-floral',
                'badge' => 'Dekorasi Akad',
                'price' => 2500000,
                'image_url' => 'https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=900&q=80',
                'description' => 'Set dekorasi akad nikah bentuk heksagonal geometris dihiasi bunga warna pastel lembut, 1 meja akad nikah kaca, 6 kursi tiffany putih, dan bantal duduk.',
            ],
            [
                'vendor_id' => $dyraId,
                'category_id' => $categoryModels['dekorasi-akad']->id,
                'name' => 'Set Meja Akad Minimalis Pure White & Glass',
                'slug' => 'set-meja-akad-minimalis-pure-white',
                'badge' => 'Dekorasi Akad',
                'price' => 2000000,
                'image_url' => 'https://images.unsplash.com/photo-1527529482837-4698179dc6ce?auto=format&fit=crop&w=900&q=80',
                'description' => 'Penataan meja akad bersih elegan dengan taplak satin putih, center piece bunga melati segar, tempat cincin terarium kaca, dan microphone stand.',
            ],
            [
                'vendor_id' => $feezaId,
                'category_id' => $categoryModels['dekorasi-akad']->id,
                'name' => 'Dekorasi Akad Lesehan Arabian Boho Style',
                'slug' => 'dekorasi-akad-lesehan-arabian-boho',
                'badge' => 'Dekorasi Akad',
                'price' => 3000000,
                'image_url' => 'https://images.unsplash.com/photo-1507676184212-d03ab07a01bf?auto=format&fit=crop&w=900&q=80',
                'description' => 'Suasana khidmat dan hangat dengan konsep lesehan permadani Turki, bantal beludru empuk, tirai kain flowy putih, serta lilin aromatik dekoratif.',
            ],
            [
                'vendor_id' => $alesyaId,
                'category_id' => $categoryModels['dekorasi-akad']->id,
                'name' => 'Pergola Akad Bunga Segar Mewah',
                'slug' => 'pergola-akad-bunga-segar-mewah',
                'badge' => 'Dekorasi Akad',
                'price' => 3500000,
                'image_url' => 'https://images.unsplash.com/photo-1478146896981-b80fe463b330?auto=format&fit=crop&w=900&q=80',
                'description' => 'Pergola 4 tiang berbalut kain sifon dan untaian bunga anggrek & mawar segar di sekeliling area ijab kabul, memberikan kesan sakral dan memukau.',
            ],

            // --- GAUN & BUSANA PENGANTIN ---
            [
                'vendor_id' => $alesyaId,
                'category_id' => $categoryModels['gaun-busana']->id,
                'name' => 'Gaun Pengantin Ballgown Swarovski Princess',
                'slug' => 'gaun-pengantin-ballgown-swarovski-princess',
                'badge' => 'Gaun & Busana',
                'price' => 4500000,
                'image_url' => 'https://images.unsplash.com/photo-1594552072238-b8a33785b261?auto=format&fit=crop&w=900&q=80',
                'description' => 'Ballgown megah dengan rok mengembang bertabur kristal swarovski asli, ekor gaun 2.5 meter, veil panjang berenda Prancis, dan petticoat tebal.',
            ],
            [
                'vendor_id' => $dyraId,
                'category_id' => $categoryModels['gaun-busana']->id,
                'name' => 'Mermaid Gown Classic Ivory Silk',
                'slug' => 'mermaid-gown-classic-ivory-silk',
                'badge' => 'Gaun & Busana',
                'price' => 3800000,
                'image_url' => 'https://images.unsplash.com/photo-1546804784-896d0dca3805?auto=format&fit=crop&w=900&q=80',
                'description' => 'Siluet mermaid ramping berbahan sutra ivory premium dengan aksen brokat 3D bordir halus pada bagian punggung dan korset pembentuk pinggang.',
            ],
            [
                'vendor_id' => $adillaaId,
                'category_id' => $categoryModels['gaun-busana']->id,
                'name' => 'A-Line French Lace Bridal Dress',
                'slug' => 'a-line-french-lace-bridal-dress',
                'badge' => 'Gaun & Busana',
                'price' => 3200000,
                'image_url' => 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=900&q=80',
                'description' => 'Gaun potongan A-Line yang anggun dan nyaman dipakai bergerak, dilapisi brukat Prancis halus motif flora dan mutiara jepang pada bagian leher.',
            ],
            [
                'vendor_id' => $syarifahId,
                'category_id' => $categoryModels['gaun-busana']->id,
                'name' => 'Modern Hijab Wedding Dress Champagne Gold',
                'slug' => 'modern-hijab-wedding-dress-champagne-gold',
                'badge' => 'Gaun & Busana',
                'price' => 3600000,
                'image_url' => 'https://images.unsplash.com/photo-1596451190630-186aff535bf2?auto=format&fit=crop&w=900&q=80',
                'description' => 'Busana pengantin muslimah syari modern tidak menerawang, berbahan tule glitter warna champagne gold, include jilbab wedding kristal dan crown.',
            ],

            // --- BAJU ADAT & TRADISIONAL ---
            [
                'vendor_id' => $dyraId,
                'category_id' => $categoryModels['baju-adat']->id,
                'name' => 'Busana Adat Melayu Deli Songket Emas Lengkap',
                'slug' => 'busana-adat-melayu-deli-songket-emas',
                'badge' => 'Baju Adat',
                'price' => 3500000,
                'image_url' => 'https://images.unsplash.com/photo-1594465919760-441fe5908ab0?auto=format&fit=crop&w=900&q=80',
                'description' => 'Sepasang busana pengantin Melayu Deli berbahan kain tenun songket benang emas asli, lengkap dengan tengkulok, mahkota jurai, pending emas, dan keris hias.',
            ],
            [
                'vendor_id' => $syarifahId,
                'category_id' => $categoryModels['baju-adat']->id,
                'name' => 'Kebaya Sunda Siger Kencana & Jarik Prada',
                'slug' => 'kebaya-sunda-siger-kencana-jarik-prada',
                'badge' => 'Baju Adat',
                'price' => 3000000,
                'image_url' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=900&q=80',
                'description' => 'Kebaya brokat Sunda putih/gold dengan mahkota Siger Sunda Kencana asli, kembang goyang, ronce melati silih asih, bendo, dan kain jarik batik prada.',
            ],
            [
                'vendor_id' => $adillaaId,
                'category_id' => $categoryModels['baju-adat']->id,
                'name' => 'Busana Pengantin Adat Batak Toba Ulos Sadum',
                'slug' => 'busana-pengantin-adat-batak-toba-ulos',
                'badge' => 'Baju Adat',
                'price' => 4000000,
                'image_url' => 'https://images.unsplash.com/photo-1583939411023-14783179e581?auto=format&fit=crop&w=900&q=80',
                'description' => 'Setelan lengkap pakaian adat Batak Toba dengan selempang Ulos Sadum tenun tangan berkelas, sortali kepala berlapis emas, gelang gajah, dan tali-tali.',
            ],
            [
                'vendor_id' => $feezaId,
                'category_id' => $categoryModels['baju-adat']->id,
                'name' => 'Paes Ageng Basahan Adat Jawa Mataraman',
                'slug' => 'paes-ageng-basahan-adat-jawa',
                'badge' => 'Baju Adat',
                'price' => 3800000,
                'image_url' => 'https://images.unsplash.com/photo-1532712938310-34cb3982ef74?auto=format&fit=crop&w=900&q=80',
                'description' => 'Pakaian adat pengantin Jawa klasik basahan dengan motif cinde sutra merah-emas, dodot panjang, rias paes ageng pakem keraton, kalung ulur, dan cunduk mentul.',
            ],
            [
                'vendor_id' => $alesyaId,
                'category_id' => $categoryModels['baju-adat']->id,
                'name' => 'Baju Bodo Pengantin Modern Glamour',
                'slug' => 'baju-bodo-pengantin-modern-glamour',
                'badge' => 'Baju Adat',
                'price' => 3200000,
                'image_url' => 'https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=900&q=80',
                'description' => 'Busana adat Bugis-Makassar kontemporer dari kain sutra organza warna fuchsia/maroon dihiasi manik-manik emas, bando saloko perak, dan gelang ponto karro.',
            ],
        ];

        foreach ($items as $item) {
            ServiceItem::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
