<?php

namespace Database\Seeders;

use App\Models\Bundling;
use App\Models\BundlingEvaluation;
use App\Models\Criteria;
use Illuminate\Database\Seeder;

class BundlingEvaluationSeeder extends Seeder
{
    public function run(): void
    {
        $criterias = Criteria::all()->keyBy('code');
        $bundlings = Bundling::all();

        foreach ($bundlings as $bundling) {
            $price = (float) $bundling->base_price;

            // Logika konversi kriteria berdasarkan karakteristik paket
            // C1 (Kualitas Hasil): 1=Standar, 3=Bagus, 5=Sangat Istimewa/Flawless
            // C2 (Estetika & Desain): 1=Sederhana, 3=Menarik Modern, 5=Mewah Spektakuler
            // C3 (Kelengkapan): 1=Basic, 3=Lengkap, 5=Super Komplit (Full Item)
            // C4 (Rating & Ulasan): 1=Baru/Cukup, 3=Bintang 4, 5=Bintang 5 Unggulan
            // C5 (Harga - Cost): 1=Terjangkau (<8jt), 3=Sedang (8jt-16jt), 5=Tinggi (>16jt)
            // C6 (Risiko Kerusakan - Cost): 1=Rendah, 3=Sedang, 5=Tinggi (properti kaca/kristal/moving head)
            // C7 (Biaya Tambahan - Cost): 1=Minim, 3=Sedang, 5=Tinggi (luar kota/banyak personel)

            if ($price <= 7000000) {
                // Paket Mini / Akad Hemat
                $scores = [
                    'C1' => 3,
                    'C2' => 3,
                    'C3' => 1,
                    'C4' => 3,
                    'C5' => 1,
                    'C6' => 1,
                    'C7' => 1,
                ];
            } elseif ($price <= 14000000) {
                // Paket Medium / Standar Rumah / Resepsi
                $scores = [
                    'C1' => 5,
                    'C2' => 3,
                    'C3' => 3,
                    'C4' => 5,
                    'C5' => 3,
                    'C6' => 3,
                    'C7' => 3,
                ];
            } elseif ($price <= 20000000) {
                // Paket Elegan / Gedung Silver
                $scores = [
                    'C1' => 5,
                    'C2' => 5,
                    'C3' => 3,
                    'C4' => 5,
                    'C5' => 5,
                    'C6' => 3,
                    'C7' => 3,
                ];
            } else {
                // Paket Luxury / Royal Majesty / Gedung Luxury
                $scores = [
                    'C1' => 5,
                    'C2' => 5,
                    'C3' => 5,
                    'C4' => 5,
                    'C5' => 5,
                    'C6' => 5,
                    'C7' => 5,
                ];
            }

            // Variasi spesifik berdasarkan nama paket
            if (str_contains(strtolower($bundling->package_name), 'medium package')) {
                $scores['C1'] = 5;
                $scores['C2'] = 5;
                $scores['C3'] = 3;
                $scores['C4'] = 5;
                $scores['C5'] = 3;
                $scores['C6'] = 1;
                $scores['C7'] = 1;
            } elseif (str_contains(strtolower($bundling->package_name), 'lux') || str_contains(strtolower($bundling->package_name), 'royal')) {
                $scores['C1'] = 5;
                $scores['C2'] = 5;
                $scores['C3'] = 5;
                $scores['C4'] = 5;
            }

            foreach ($scores as $code => $score) {
                if (isset($criterias[$code])) {
                    BundlingEvaluation::updateOrCreate(
                        [
                            'bundling_id' => $bundling->id,
                            'criteria_id' => $criterias[$code]->id,
                        ],
                        [
                            'score' => $score,
                        ]
                    );
                }
            }
        }
    }
}
