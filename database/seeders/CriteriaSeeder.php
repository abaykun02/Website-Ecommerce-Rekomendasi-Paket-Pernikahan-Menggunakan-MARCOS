<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $criterias = [
            [
                'code' => 'C1',
                'name' => 'Kualitas Hasil',
                'type' => 'benefit',
                'weight' => 0.2000,
                'description' => 'Tingkat kehalusan make-up, ketahanan riasan, dan kualitas bahan dekorasi.',
            ],
            [
                'code' => 'C2',
                'name' => 'Estetika & Desain',
                'type' => 'benefit',
                'weight' => 0.2000,
                'description' => 'Kesesuaian tema, keindahan visual, dan tren tata rias & panggung.',
            ],
            [
                'code' => 'C3',
                'name' => 'Kelengkapan',
                'type' => 'benefit',
                'weight' => 0.1500,
                'description' => 'Kelengkapan item layanan seperti busana, melati, bunga segar, & lighting.',
            ],
            [
                'code' => 'C4',
                'name' => 'Rating & Ulasan',
                'type' => 'benefit',
                'weight' => 0.1000,
                'description' => 'Reputasi vendor dan kepuasan pelanggan sebelumnya.',
            ],
            [
                'code' => 'C5',
                'name' => 'Harga',
                'type' => 'cost',
                'weight' => 0.2000,
                'description' => 'Total biaya paket layanan (semakin terjangkau semakin baik).',
            ],
            [
                'code' => 'C6',
                'name' => 'Risiko Kerusakan',
                'type' => 'cost',
                'weight' => 0.0500,
                'description' => 'Tingkat potensi insiden atau risiko properti dekorasi/makeup saat acara.',
            ],
            [
                'code' => 'C7',
                'name' => 'Biaya Tambahan',
                'type' => 'cost',
                'weight' => 0.1000,
                'description' => 'Estimasi biaya transportasi luar kota, overtime, touch-up berulang, dll.',
            ],
        ];

        foreach ($criterias as $criteria) {
            Criteria::updateOrCreate(['code' => $criteria['code']], $criteria);
        }
    }
}
