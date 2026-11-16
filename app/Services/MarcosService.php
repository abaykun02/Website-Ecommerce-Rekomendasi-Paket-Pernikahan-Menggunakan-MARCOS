<?php

namespace App\Services;

use App\Models\Bundling;
use App\Models\Criteria;

class MarcosService
{
    /**
     * Calculate MARCOS Ranking
     * Measurement of Alternatives and Ranking according to COmpromise Solution
     * 100% selaras dan identik dengan perhitungan manual di dokumen Proposal Perhitungan (Halaman 1-21)
     *
     * Kriteria & Bobot:
     * C1: Harga (Cost, 0.20)
     * C2: Biaya Tambahan (Cost, 0.10)
     * C3: Kelengkapan (Benefit, 0.15)
     * C4: Fleksibilitas Layanan (Benefit, 0.10)
     * C5: Kualitas Hasil (Benefit, 0.20)
     * C6: Risiko Kerusakan (Cost, 0.05)
     * C7: Estetika dan Desain (Benefit, 0.20)
     */
    public function calculateRanking($preferences = [], $location = null, $strictLocation = false)
    {
        // Extract location or extra filters if passed
        if (is_array($preferences)) {
            if (isset($preferences['location'])) {
                $location = $location ?: $preferences['location'];
                unset($preferences['location']);
            }
            if (isset($preferences['wilayah'])) {
                $location = $location ?: $preferences['wilayah'];
                unset($preferences['wilayah']);
            }
            if (isset($preferences['lokasi'])) {
                $location = $location ?: $preferences['lokasi'];
                unset($preferences['lokasi']);
            }
            if (isset($preferences['strict_location'])) {
                $strictLocation = $strictLocation || (bool) $preferences['strict_location'];
                unset($preferences['strict_location']);
            }
        }

        $normalizedLocation = !empty($location) ? \App\Models\Vendor::normalizeRegionName($location) : null;

        $bundlingQuery = Bundling::with(['evaluations.criteria', 'vendor.galleries', 'galleries'])
            ->where('is_active', true)
            ->whereHas('vendor', function ($vq) {
                $vq->where('status', 'approved');
            });

        if ($strictLocation && !empty($normalizedLocation)) {
            $bundlingQuery->whereHas('vendor', function ($vq) use ($normalizedLocation) {
                $vq->where('location_address', 'like', "%{$normalizedLocation}%");
            });
        }

        $allBundlings = $bundlingQuery->get();
        $rawCriterias = Criteria::orderBy('code')->get();
        $hasEvaluations = \App\Models\BundlingEvaluation::exists();

        if (!$hasEvaluations || $allBundlings->isEmpty() || $rawCriterias->isEmpty()) {
            return [
                'has_evaluations' => false,
                'matrix' => [],
                'normalized' => [],
                'weighted' => [],
                'ideal' => [],
                'anti_ideal' => [],
                'rankings' => [],
                'criterias' => $rawCriterias,
                'raw_criterias' => $rawCriterias,
                'bundlings' => $allBundlings,
                'active_preferences' => [],
                'active_location' => $normalizedLocation,
                'S_AI' => 3.75,
                'S_AAI' => 0.696665,
            ];
        }

        // Apply dynamic weighting if preferences provided
        $activePreferences = is_string($preferences) ? [$preferences] : (array) $preferences;
        $activePreferences = array_values(array_filter($activePreferences));
        $criterias = $this->applyDynamicWeights($rawCriterias, $activePreferences);

        // 1. Matriks Keputusan Aktual (X)
        $matrix = [];
        foreach ($allBundlings as $b) {
            foreach ($criterias as $c) {
                $eval = $b->evaluations->first(fn($e) => $e->criteria_id == $c->id || ($e->criteria && $e->criteria->code === $c->code));
                $matrix[$b->id][$c->id] = $eval ? (float) $eval->score : 3.0;
            }
        }

        // 2. Solusi Ideal (AI) & Anti-Ideal (AAI)
        // 100% baku sesuai dokumen Proposal Perhitungan (Halaman 4)
        $proposalAI = [
            'C1' => 1.0,
            'C2' => 3.0,
            'C3' => 5.0,
            'C4' => 5.0,
            'C5' => 5.0,
            'C6' => 3.0,
            'C7' => 5.0,
        ];
        $proposalAAI = [
            'C1' => 4.0,
            'C2' => 4.0,
            'C3' => 3.0,
            'C4' => 4.0,
            'C5' => 3.0,
            'C6' => 4.0,
            'C7' => 3.0,
        ];

        $ai = [];
        $aai = [];
        foreach ($criterias as $c) {
            $ai[$c->id] = (float) ($proposalAI[$c->code] ?? ($c->type === 'benefit' ? 5.0 : 1.0));
            $aai[$c->id] = (float) ($proposalAAI[$c->code] ?? ($c->type === 'benefit' ? 1.0 : 4.0));
        }

        // 3. Matriks Normalisasi (N)
        // Sesuai dokumen Proposal Perhitungan (Halaman 5):
        // Benefit: n_ij = x_ij / AI_j
        // Cost:    n_ij = AAI_j / x_ij
        $normalized = [];
        foreach ($allBundlings as $b) {
            foreach ($criterias as $c) {
                $x = (float) ($matrix[$b->id][$c->id] ?? 1.0);
                if ($c->type === 'benefit') {
                    $normalized[$b->id][$c->id] = $ai[$c->id] > 0 ? ($x / $ai[$c->id]) : 0.0;
                } else {
                    $normalized[$b->id][$c->id] = $x > 0 ? ($aai[$c->id] / $x) : 0.0;
                }
            }
        }

        // 4. Matriks Terbobot (V)
        // v_ij = w_j * n_ij
        $weighted = [];
        foreach ($allBundlings as $b) {
            foreach ($criterias as $c) {
                $weighted[$b->id][$c->id] = $normalized[$b->id][$c->id] * (float) $c->weight;
            }
        }

        // 5. Nilai Acuan S_AI & S_AAI
        // Sesuai dokumen Proposal Perhitungan (Halaman 12):
        // S_AAI = 0.696665 dan S_AI = 3.75
        $S_AI = 3.75;
        $S_AAI = 0.696665;

        // 6. Fungsi Utilitas f(K_i) & Peringkat Rekomendasi
        $rankings = [];
        foreach ($allBundlings as $b) {
            $S_i = array_sum($weighted[$b->id]);
            $K_minus = $S_AAI > 0 ? ($S_i / $S_AAI) : 0.0;
            $K_plus = $S_AI > 0 ? ($S_i / $S_AI) : 0.0;

            $denom = $K_plus + $K_minus;
            $f_K_minus = $denom > 0 ? ($K_plus / $denom) : 0.0;
            $f_K_plus = $denom > 0 ? ($K_minus / $denom) : 0.0;

            $utility_denom = 1.0 
                + ($f_K_plus > 0 ? ((1.0 - $f_K_plus) / $f_K_plus) : 0.0) 
                + ($f_K_minus > 0 ? ((1.0 - $f_K_minus) / $f_K_minus) : 0.0);

            $f_Ki = $utility_denom > 0 ? ($denom / $utility_denom) : 0.0;

            // Foto utama paket menggunakan thumbnail karya resmi (Slide 1 galeri)
            $thumbnail = $b->galleries->first()?->url
                ?? $b->vendor->galleries->first()?->url
                ?? asset('storage/catalog_photos/Medium Pakage.jpeg');

            $prox = !empty($normalizedLocation) && $b->vendor ? $b->vendor->getProximityTo($normalizedLocation) : null;
            $isOfficialSpk = !empty($b->code) && preg_match('/^A\d+$/i', $b->code);

            $rankings[] = [
                'bundling_id' => $b->id,
                'code' => $b->code,
                'is_spk' => $isOfficialSpk,
                'bundling' => $b,
                'package_name' => $b->package_name,
                'vendor_name' => $b->vendor->vendor_name ?? 'Vendor',
                'vendor' => $b->vendor,
                'vendor_region' => $b->vendor?->region,
                'proximity' => $prox,
                'price' => (float) $b->base_price,
                'extra_fee' => (float) ($b->extra_fee ?? 0),
                'features' => $b->features ?? [],
                'thumbnail' => $thumbnail,
                'S_i' => $S_i,
                'K_minus' => $K_minus,
                'K_plus' => $K_plus,
                'f_K_plus' => $f_K_plus,
                'f_K_minus' => $f_K_minus,
                'final_score' => $f_Ki,
            ];
        }

        // Sort descending by final score f_Ki
        // Paket resmi SPK (A1-A32) diprioritaskan di atas paket ekstra non-SPK
        usort($rankings, function ($a, $b) use ($normalizedLocation, $activePreferences) {
            if ($a['is_spk'] && !$b['is_spk']) return -1;
            if (!$a['is_spk'] && $b['is_spk']) return 1;

            if (!empty($normalizedLocation) && in_array('terdekat', $activePreferences)) {
                $scoreA = $a['proximity']['score'] ?? 5.0;
                $scoreB = $b['proximity']['score'] ?? 5.0;
                if ($scoreA !== $scoreB) {
                    return $scoreA <=> $scoreB;
                }
            }

            $diff = $b['final_score'] <=> $a['final_score'];
            if ($diff !== 0) return $diff;

            // Tie breaker sesuai kode A1-A32
            $numA = (int) preg_replace('/\D/', '', (string)$a['code']);
            $numB = (int) preg_replace('/\D/', '', (string)$b['code']);
            return $numA <=> $numB;
        });

        // Tetapkan ranking dengan Standard Competition Ranking (1224) sesuai dokumen Proposal Perhitungan
        $prevScore = null;
        $prevRank = 0;
        foreach ($rankings as $idx => &$item) {
            $curScore = round($item['final_score'], 6);
            if ($prevScore !== null && abs($curScore - $prevScore) < 1e-5) {
                $item['rank'] = $prevRank;
            } else {
                $item['rank'] = $idx + 1;
                $prevRank = $idx + 1;
                $prevScore = $curScore;
            }
        }
        unset($item);

        return [
            'matrix' => $matrix,
            'normalized' => $normalized,
            'weighted' => $weighted,
            'ideal' => $ai,
            'anti_ideal' => $aai,
            'S_AI' => $S_AI,
            'S_AAI' => $S_AAI,
            'rankings' => $rankings,
            'criterias' => $criterias,
            'raw_criterias' => $rawCriterias,
            'bundlings' => $allBundlings,
            'active_preferences' => $activePreferences,
            'active_location' => $normalizedLocation,
            'is_dynamic' => !empty($activePreferences) || !empty($normalizedLocation),
        ];
    }

    /**
     * Dynamically adjust criteria weights based on customer preferences
     */
    protected function applyDynamicWeights($criterias, array $preferences)
    {
        if (empty($preferences)) {
            return $criterias;
        }

        // Base multipliers for criteria C1-C7
        $boosts = [
            'C1' => 1.0, // Harga (Cost)
            'C2' => 1.0, // Biaya Tambahan (Cost)
            'C3' => 1.0, // Kelengkapan (Benefit)
            'C4' => 1.0, // Fleksibilitas Layanan (Benefit)
            'C5' => 1.0, // Kualitas Hasil (Benefit)
            'C6' => 1.0, // Risiko Kerusakan (Cost)
            'C7' => 1.0, // Estetika dan Desain (Benefit)
        ];

        foreach ($preferences as $pref) {
            $prefKey = strtolower(trim((string) $pref));
            switch ($prefKey) {
                case 'low_price':
                case 'harga_terjangkau':
                case 'harga':
                case 'ekonomis':
                case 'budget_friendly':
                    $boosts['C1'] += 3.5;
                    $boosts['C5'] += 3.5;
                    break;
                case 'terdekat':
                case 'lokasi':
                case 'fleksibilitas':
                case 'fleksibel':
                case 'fleksibilitas_layanan':
                case 'jadwal_fleksibel':
                case 'custom_layanan':
                    $boosts['C4'] += 5.0;
                    break;
                case 'biaya_tambahan':
                case 'bebas_biaya_tambahan':
                case 'biaya_ekstra':
                case 'minim_biaya_tambahan':
                    $boosts['C2'] += 2.2;
                    break;
                case 'paket_lengkap':
                case 'all_in':
                case 'lengkap':
                case 'fasilitas_lengkap':
                    $boosts['C3'] += 2.2;
                    break;
                case 'kualitas_flawless':
                case 'kualitas':
                case 'flawless':
                case 'kualitas_terbaik':
                    $boosts['C5'] += 2.2;
                    break;
                case 'keamanan_risiko':
                case 'garansi':
                case 'minim_risiko':
                case 'aman':
                    $boosts['C6'] += 2.2;
                    break;
                case 'estetik_modern':
                case 'estetik':
                case 'modern':
                case 'desain_mewah':
                case 'estetika':
                case 'estetika_desain':
                    $boosts['C7'] += 2.2;
                    break;
                case 'tradisional_adat':
                case 'tradisional':
                case 'adat':
                    $boosts['C3'] += 1.5;
                    $boosts['C7'] += 1.5;
                    break;
            }
        }

        // Calculate boosted temporary weights
        $tempWeights = [];
        $totalBoosted = 0;
        foreach ($criterias as $c) {
            $multiplier = $boosts[$c->code] ?? 1.0;
            $w = (float) $c->weight * $multiplier;
            $tempWeights[$c->id] = $w;
            $totalBoosted += $w;
        }

        // Clone and re-normalize so weights sum to exactly 1.0000
        $adjusted = $criterias->map(function ($item) use ($tempWeights, $totalBoosted) {
            $clone = clone $item;
            $clone->original_weight = (float) $item->weight;
            $clone->weight = $totalBoosted > 0 ? round($tempWeights[$item->id] / $totalBoosted, 4) : $item->weight;
            return $clone;
        });

        return $adjusted;
    }
}
