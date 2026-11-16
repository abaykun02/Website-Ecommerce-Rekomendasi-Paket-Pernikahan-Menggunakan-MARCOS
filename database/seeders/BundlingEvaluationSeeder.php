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

        // Matriks Keputusan Aktual (X) 100% eksak sesuai dokumen Proposal Perhitungan (Halaman 3 & 4)
        // Kriteria:
        // C1: Harga (Cost: 5=<=3jt, 4=3-7jt, 3=7-12jt, 2=12-18jt, 1=>18jt)
        // C2: Biaya Tambahan (Cost: 5=<=100rb, 4=100-150rb, 3=150-200rb, 2=200-250rb, 1=>250rb)
        // C3: Kelengkapan (Benefit: 5=Sangat Lengkap, 4=Lengkap, 3=Cukup, 2=Kurang, 1=Tidak)
        // C4: Fleksibilitas Layanan (Benefit: 5=Sangat Baik, 4=Baik, 3=Cukup, 2=Kurang, 1=Tidak)
        // C5: Kualitas Hasil (Benefit: 5=Sangat Baik, 4=Baik, 3=Cukup, 2=Kurang, 1=Tidak)
        // C6: Risiko Kerusakan (Cost: 5=Sangat Rendah, 4=Rendah, 3=Sedang, 2=Tinggi, 1=Sangat Tinggi)
        // C7: Estetika dan Desain (Benefit: 5=Sangat Baik, 4=Baik, 3=Cukup, 2=Kurang, 1=Tidak)
        $officialScores = [
            'A1'  => ['C1' => 3, 'C2' => 4, 'C3' => 4, 'C4' => 5, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            'A2'  => ['C1' => 3, 'C2' => 4, 'C3' => 3, 'C4' => 5, 'C5' => 4, 'C6' => 4, 'C7' => 3],
            'A3'  => ['C1' => 2, 'C2' => 4, 'C3' => 5, 'C4' => 5, 'C5' => 5, 'C6' => 4, 'C7' => 5],
            'A4'  => ['C1' => 2, 'C2' => 4, 'C3' => 4, 'C4' => 5, 'C5' => 5, 'C6' => 3, 'C7' => 4],
            'A5'  => ['C1' => 3, 'C2' => 4, 'C3' => 3, 'C4' => 5, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            'A6'  => ['C1' => 4, 'C2' => 4, 'C3' => 3, 'C4' => 5, 'C5' => 4, 'C6' => 4, 'C7' => 4],
            'A7'  => ['C1' => 3, 'C2' => 3, 'C3' => 5, 'C4' => 4, 'C5' => 5, 'C6' => 3, 'C7' => 5],
            'A8'  => ['C1' => 3, 'C2' => 4, 'C3' => 4, 'C4' => 4, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            'A9'  => ['C1' => 3, 'C2' => 4, 'C3' => 5, 'C4' => 4, 'C5' => 5, 'C6' => 4, 'C7' => 5],
            'A10' => ['C1' => 2, 'C2' => 3, 'C3' => 5, 'C4' => 4, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            'A11' => ['C1' => 2, 'C2' => 4, 'C3' => 4, 'C4' => 4, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            'A12' => ['C1' => 1, 'C2' => 3, 'C3' => 5, 'C4' => 4, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            // A13: Pada dokumen PDF halaman 11, nilai terbobot C5 dan C7 dijumlahkan sebagai 0.1600 (skor 4) sehingga S13 = 1.519995 dan f(K13) = 0.393867 (Rank 5 sama dengan A14)
            'A13' => ['C1' => 1, 'C2' => 3, 'C3' => 4, 'C4' => 4, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            'A14' => ['C1' => 1, 'C2' => 3, 'C3' => 4, 'C4' => 4, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            'A15' => ['C1' => 1, 'C2' => 3, 'C3' => 5, 'C4' => 4, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            'A16' => ['C1' => 5, 'C2' => 5, 'C3' => 3, 'C4' => 4, 'C5' => 4, 'C6' => 4, 'C7' => 4],
            'A17' => ['C1' => 4, 'C2' => 5, 'C3' => 3, 'C4' => 4, 'C5' => 4, 'C6' => 4, 'C7' => 3],
            'A18' => ['C1' => 5, 'C2' => 5, 'C3' => 3, 'C4' => 4, 'C5' => 4, 'C6' => 4, 'C7' => 4],
            'A19' => ['C1' => 4, 'C2' => 5, 'C3' => 3, 'C4' => 4, 'C5' => 4, 'C6' => 4, 'C7' => 3],
            'A20' => ['C1' => 4, 'C2' => 5, 'C3' => 3, 'C4' => 4, 'C5' => 4, 'C6' => 4, 'C7' => 4],
            'A21' => ['C1' => 5, 'C2' => 5, 'C3' => 3, 'C4' => 5, 'C5' => 4, 'C6' => 4, 'C7' => 4],
            'A22' => ['C1' => 4, 'C2' => 3, 'C3' => 5, 'C4' => 5, 'C5' => 5, 'C6' => 3, 'C7' => 5],
            'A23' => ['C1' => 4, 'C2' => 3, 'C3' => 5, 'C4' => 5, 'C5' => 5, 'C6' => 3, 'C7' => 5],
            'A24' => ['C1' => 5, 'C2' => 5, 'C3' => 5, 'C4' => 5, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            'A25' => ['C1' => 4, 'C2' => 5, 'C3' => 3, 'C4' => 5, 'C5' => 4, 'C6' => 4, 'C7' => 4],
            'A26' => ['C1' => 2, 'C2' => 3, 'C3' => 4, 'C4' => 5, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            // A27: Pada dokumen PDF halaman 7, n272 dinormalisasi sebagai 4/5=0.8 sehingga V272=0.08, S27=0.910000 dan f(K27)=0.235803 (Rank 22)
            'A27' => ['C1' => 5, 'C2' => 5, 'C3' => 4, 'C4' => 5, 'C5' => 5, 'C6' => 4, 'C7' => 5],
            'A28' => ['C1' => 3, 'C2' => 2, 'C3' => 5, 'C4' => 1, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            'A29' => ['C1' => 2, 'C2' => 2, 'C3' => 5, 'C4' => 1, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            'A30' => ['C1' => 3, 'C2' => 2, 'C3' => 4, 'C4' => 2, 'C5' => 4, 'C6' => 3, 'C7' => 4],
            'A31' => ['C1' => 1, 'C2' => 2, 'C3' => 5, 'C4' => 2, 'C5' => 5, 'C6' => 3, 'C7' => 5],
            'A32' => ['C1' => 1, 'C2' => 2, 'C3' => 4, 'C4' => 2, 'C5' => 4, 'C6' => 3, 'C7' => 4],
        ];

        // Nilai netral sama rata untuk paket ekstra katalog non-SPK agar tidak menggeser ranking proposal
        $neutralScore = ['C1' => 4, 'C2' => 4, 'C3' => 3, 'C4' => 3, 'C5' => 3, 'C6' => 4, 'C7' => 3];

        foreach ($bundlings as $bundling) {
            $code = $bundling->code;
            $scores = ($code && isset($officialScores[$code])) ? $officialScores[$code] : $neutralScore;

            foreach ($scores as $critCode => $score) {
                if (isset($criterias[$critCode])) {
                    BundlingEvaluation::updateOrCreate(
                        [
                            'bundling_id' => $bundling->id,
                            'criteria_id' => $criterias[$critCode]->id,
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
