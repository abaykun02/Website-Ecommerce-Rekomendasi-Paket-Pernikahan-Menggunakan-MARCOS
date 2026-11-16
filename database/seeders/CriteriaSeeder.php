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
                'name' => 'Harga',
                'type' => 'cost',
                'weight' => 0.2000,
                'description' => 'Total biaya dasar paket layanan pernikahan (semakin terjangkau dan ekonomis semakin baik).',
            ],
            [
                'code' => 'C2',
                'name' => 'Biaya Tambahan',
                'type' => 'cost',
                'weight' => 0.1000,
                'description' => 'Estimasi biaya tambahan seperti overtime, sewa busana ekstra, transport luar area, atau touch up.',
            ],
            [
                'code' => 'C3',
                'name' => 'Kelengkapan',
                'type' => 'benefit',
                'weight' => 0.1500,
                'description' => 'Kelengkapan cakupan item fasilitas (tenda, pelaminan, busana, tata rias, dokumentasi, bekakas).',
            ],
            [
                'code' => 'C4',
                'name' => 'Fleksibilitas Layanan',
                'type' => 'benefit',
                'weight' => 0.1000,
                'description' => 'Tingkat kemudahan penyesuaian jadwal, perubahan konsep riasan/dekorasi, dan opsi pergantian busana/adat.',
            ],
            [
                'code' => 'C5',
                'name' => 'Kualitas Hasil',
                'type' => 'benefit',
                'weight' => 0.2000,
                'description' => 'Tingkat kehalusan dan ketahanan riasan pengantin serta kemegahan dan kerapian instalasi dekorasi.',
            ],
            [
                'code' => 'C6',
                'name' => 'Risiko Kerusakan',
                'type' => 'cost',
                'weight' => 0.0500,
                'description' => 'Tingkat potensi insiden, kerusakan properti panggung/gaun, atau risiko kendala operasional di lapangan.',
            ],
            [
                'code' => 'C7',
                'name' => 'Estetika dan Desain',
                'type' => 'benefit',
                'weight' => 0.2000,
                'description' => 'Kesesuaian tema visual, keindahan artistik panggung pelaminan, serta keserasian busana dan tata rias.',
            ],
        ];

        foreach ($criterias as $criteria) {
            Criteria::updateOrCreate(['code' => $criteria['code']], $criteria);
        }
    }
}
