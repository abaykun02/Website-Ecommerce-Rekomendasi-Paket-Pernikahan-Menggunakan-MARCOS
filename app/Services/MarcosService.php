<?php

namespace App\Services;

use App\Models\Bundling;
use App\Models\Criteria;

class MarcosService
{
    /**
     * Calculate MARCOS Ranking
     * Measurement of Alternatives and Ranking according to COmpromise Solution
     * Supports dynamic weighting based on customer preferences
     */
    public function calculateRanking($preferences = [])
    {
        $bundlings = Bundling::with(['evaluations', 'vendor.galleries', 'galleries'])
            ->where('is_active', true)
            ->get();
        $rawCriterias = Criteria::orderBy('code')->get();

        if ($bundlings->isEmpty() || $rawCriterias->isEmpty()) {
            return [
                'matrix' => [],
                'normalized' => [],
                'weighted' => [],
                'ideal' => [],
                'anti_ideal' => [],
                'rankings' => [],
                'criterias' => $rawCriterias,
                'active_preferences' => [],
            ];
        }

        // Apply dynamic weighting if preferences provided
        $activePreferences = is_string($preferences) ? [$preferences] : (array) $preferences;
        $activePreferences = array_filter($activePreferences);
        $criterias = $this->applyDynamicWeights($rawCriterias, $activePreferences);

        // 1. Matriks Keputusan X
        $matrix = [];
        foreach ($bundlings as $b) {
            foreach ($criterias as $c) {
                $eval = $b->evaluations->where('criteria_id', $c->id)->first();
                $matrix[$b->id][$c->id] = $eval ? (float) $eval->score : 1.0;
            }
        }

        // 2. Solusi Ideal (AI) & Anti-Ideal (AAI)
        $ai = [];
        $aai = [];
        foreach ($criterias as $c) {
            $scores = array_column($matrix, $c->id);
            if (empty($scores)) {
                $ai[$c->id] = 1;
                $aai[$c->id] = 1;
                continue;
            }

            if ($c->type === 'benefit') {
                $ai[$c->id] = max($scores);  // A+
                $aai[$c->id] = min($scores); // A-
            } else {
                $ai[$c->id] = min($scores);  // A+
                $aai[$c->id] = max($scores); // A-
            }
        }

        // 3. Matriks Normalisasi (N)
        $normalized = [];
        foreach ($bundlings as $b) {
            foreach ($criterias as $c) {
                $x = $matrix[$b->id][$c->id];
                if ($c->type === 'benefit') {
                    $normalized[$b->id][$c->id] = $ai[$c->id] > 0 ? ($x / $ai[$c->id]) : 0;
                } else {
                    $normalized[$b->id][$c->id] = $x > 0 ? ($ai[$c->id] / $x) : 0;
                }
            }
        }

        // 4. Matriks Terbobot (V)
        $weighted = [];
        foreach ($bundlings as $b) {
            foreach ($criterias as $c) {
                $weighted[$b->id][$c->id] = $normalized[$b->id][$c->id] * (float) $c->weight;
            }
        }

        // 5. Perhitungan Nilai S_AI & S_AAI
        $S_AI = 0;
        $S_AAI = 0;
        foreach ($criterias as $c) {
            if ($c->type === 'benefit') {
                $S_AI += ($ai[$c->id] > 0 ? ($ai[$c->id] / $ai[$c->id]) : 1) * (float) $c->weight;
                $S_AAI += ($ai[$c->id] > 0 ? ($aai[$c->id] / $ai[$c->id]) : 1) * (float) $c->weight;
            } else {
                $S_AI += ($ai[$c->id] > 0 ? ($ai[$c->id] / $ai[$c->id]) : 1) * (float) $c->weight;
                $S_AAI += ($aai[$c->id] > 0 ? ($ai[$c->id] / $aai[$c->id]) : 1) * (float) $c->weight;
            }
        }

        // 6. Fungsi Utilitas f(K_i) & Peringkat
        $rankings = [];
        foreach ($bundlings as $b) {
            $S_i = array_sum($weighted[$b->id]);
            $K_minus = $S_AAI > 0 ? ($S_i / $S_AAI) : 0;
            $K_plus = $S_AI > 0 ? ($S_i / $S_AI) : 0;

            $denom = $K_plus + $K_minus;
            $f_K_minus = $denom > 0 ? ($K_plus / $denom) : 0;
            $f_K_plus = $denom > 0 ? ($K_minus / $denom) : 0;

            $utility_denom = 1 + ($f_K_plus > 0 ? ((1 - $f_K_plus) / $f_K_plus) : 0) + ($f_K_minus > 0 ? ((1 - $f_K_minus) / $f_K_minus) : 0);
            $f_Ki = $utility_denom > 0 ? ($denom / $utility_denom) : 0;

            $thumbnail = $b->galleries->first()?->url 
                ?? $b->vendor->galleries->first()?->url 
                ?? 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=800&q=80';

            $rankings[] = [
                'bundling_id' => $b->id,
                'bundling' => $b,
                'package_name' => $b->package_name,
                'vendor_name' => $b->vendor->vendor_name,
                'vendor' => $b->vendor,
                'price' => $b->base_price,
                'thumbnail' => $thumbnail,
                'S_i' => $S_i,
                'K_minus' => $K_minus,
                'K_plus' => $K_plus,
                'final_score' => $f_Ki
            ];
        }

        usort($rankings, fn($a, $b) => $b['final_score'] <=> $a['final_score']);

        // Tambahkan ranking index
        foreach ($rankings as $idx => &$item) {
            $item['rank'] = $idx + 1;
        }

        return [
            'matrix' => $matrix,
            'normalized' => $normalized,
            'weighted' => $weighted,
            'ideal' => $ai,
            'anti_ideal' => $aai,
            'rankings' => $rankings,
            'criterias' => $criterias,
            'raw_criterias' => $rawCriterias,
            'bundlings' => $bundlings,
            'active_preferences' => $activePreferences,
            'is_dynamic' => !empty($activePreferences),
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
            'C1' => 1.0,
            'C2' => 1.0,
            'C3' => 1.0,
            'C4' => 1.0,
            'C5' => 1.0,
            'C6' => 1.0,
            'C7' => 1.0,
        ];

        foreach ($preferences as $pref) {
            $prefKey = strtolower(trim((string) $pref));
            switch ($prefKey) {
                case 'low_price':
                case 'harga_terjangkau':
                case 'harga':
                    $boosts['C5'] += 2.2;
                    break;
                case 'kualitas_flawless':
                case 'kualitas':
                case 'flawless':
                    $boosts['C1'] += 2.2;
                    break;
                case 'estetik_modern':
                case 'estetik':
                case 'modern':
                    $boosts['C2'] += 2.2;
                    break;
                case 'paket_lengkap':
                case 'all_in':
                case 'lengkap':
                    $boosts['C3'] += 2.2;
                    break;
                case 'rating_ulasan':
                case 'ulasan':
                case 'reputasi':
                    $boosts['C4'] += 2.2;
                    break;
                case 'keamanan_risiko':
                case 'garansi':
                case 'minim_risiko':
                    $boosts['C6'] += 2.2;
                    break;
                case 'terdekat':
                case 'lokasi':
                case 'transport':
                    $boosts['C7'] += 2.2;
                    break;
                case 'tradisional_adat':
                case 'tradisional':
                case 'adat':
                    $boosts['C2'] += 1.6;
                    $boosts['C3'] += 1.6;
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
