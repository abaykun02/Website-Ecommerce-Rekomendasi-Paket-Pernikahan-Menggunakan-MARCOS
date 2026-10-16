# SPESIFIKASI SISTEM IMPLEMENTASI: E-MARKETPLACE JASA MAKE-UP & DEKORASI B2C (SPK MARCOS)

## 1. INFORMASI UMUM PROYEK & STACK
- **Nama Database**: `db_ecommerceyesi`
- **Framework Backend**: Laravel 10.x / 11.x (PHP 8.2+)
- **Database Engine**: MySQL 8.0 / MariaDB
- **Desain & UI Style**: Dribbble Minimal Style Specification
  - Primary Color: `#0D0C22` (Deep Ink)
  - Accent Color: `#FFAD48` (Amber)
  - Highlight Color: `#EA4C89` (Pink Dribbble)
  - Neutral Base: `#FFFFFF` / Surface `#FAFAFB`
  - Font Family: Mona Sans / Plus Jakarta Sans
  - Component Shapes: Rounded Full (Pill-shaped) for actions, Rounded Medium (12px-16px) for cards
- **Metode SPK**: MARCOS (Measurement of Alternatives and Ranking according to COmpromise Solution)
- **Bahasa Antarmuka**: Bahasa Indonesia (istilah teknis umum diizinkan dalam Bahasa Inggris/Laravel convention)

---

## 2. SKEMA DATABASE (MIGRATIONS) - DATABASE: `db_ecommerceyesi`

### 2.1 Table: `users`
- `id` (PK, BigInt, Auto-Increment)
- `name` (String, 100)
- `email` (String, 100, Unique)
- `password` (String)
- `role` (Enum: `'admin'`, `'vendor'`, `'customer'`)
- `phone` (String, 20, Nullable)
- `address` (Text, Nullable)
- `timestamps`

### 2.2 Table: `vendors`
- `id` (PK, BigInt, Auto-Increment)
- `user_id` (FK -> `users.id`, On Delete Cascade)
- `vendor_name` (String, 100)
- `owner_name` (String, 100)
- `location_address` (Text)
- `google_maps_url` (Text, Nullable)
- `phone` (String, 20)
- `description` (Text, Nullable)
- `timestamps`

### 2.3 Table: `bundlings`
- `id` (PK, BigInt, Auto-Increment)
- `vendor_id` (FK -> `vendors.id`, On Delete Cascade)
- `package_name` (String, 100)
- `description` (Text)
- `base_price` (Decimal: 12, 2)
- `is_active` (Boolean, Default: true)
- `timestamps`

### 2.4 Table: `criterias`
- `id` (PK, BigInt, Auto-Increment)
- `code` (String, 10, Unique) -- 'C1', 'C2', ..., 'C7'
- `name` (String, 100)
- `type` (Enum: `'benefit'`, `'cost'`)
- `weight` (Decimal: 5, 4) -- e.g., 0.2000
- `description` (Text, Nullable)
- `timestamps`

### 2.5 Table: `bundling_evaluations`
- `id` (PK, BigInt, Auto-Increment)
- `bundling_id` (FK -> `bundlings.id`, On Delete Cascade)
- `criteria_id` (FK -> `criterias.id`, On Delete Cascade)
- `score` (Integer) -- nilai skor konversi (1, 3, atau 5)
- `timestamps`

### 2.6 Table: `galleries`
- `id` (PK, BigInt, Auto-Increment)
- `vendor_id` (FK -> `vendors.id`, On Delete Cascade)
- `bundling_id` (FK -> `bundlings.id`, On Delete Cascade, Nullable)
- `image_path` (String)
- `caption` (String, Nullable)
- `timestamps`

### 2.7 Table: `orders`
- `id` (PK, BigInt, Auto-Increment)
- `order_code` (String, 50, Unique) -- e.g., 'ORD-YESI-20260929-001'
- `customer_id` (FK -> `users.id`, On Delete Cascade)
- `vendor_id` (FK -> `vendors.id`, On Delete Cascade)
- `bundling_id` (FK -> `bundlings.id`, On Delete Cascade)
- `event_date` (Date)
- `event_location` (Text)
- `total_price` (Decimal: 12, 2)
- `status` (Enum: `'pending'`, `'approved'`, `'paid'`, `'completed'`, `'cancelled'`)
- `timestamps`

### 2.8 Table: `payments`
- `id` (PK, BigInt, Auto-Increment)
- `order_id` (FK -> `orders.id`, On Delete Cascade)
- `bank_name` (String, 50)
- `account_name` (String, 100)
- `proof_image` (String)
- `payment_status` (Enum: `'pending_verification'`, `'verified'`, `'rejected'`)
- `verified_at` (Timestamp, Nullable)
- `timestamps`

### 2.9 Table: `reviews`
- `id` (PK, BigInt, Auto-Increment)
- `order_id` (FK -> `orders.id`, On Delete Cascade)
- `customer_id` (FK -> `users.id`, On Delete Cascade)
- `vendor_id` (FK -> `vendors.id`, On Delete Cascade)
- `rating` (Integer) -- skala 1-5
- `comment` (Text, Nullable)
- `timestamps`

---

## 3. SEEDER DATA ASLI PROPOSAL (`DatabaseSeeder.php`)

### 3.1 Data Kriteria & Bobot (`CriteriaSeeder.php`)
1. **C1**: Kualitas Hasil | Benefit | Bobot: 0.20 (20%)
2. **C2**: Estetika & Desain | Benefit | Bobot: 0.20 (20%)
3. **C3**: Kelengkapan | Benefit | Bobot: 0.15 (15%)
4. **C4**: Rating & Ulasan | Benefit | Bobot: 0.10 (10%)
5. **C5**: Harga | Cost | Bobot: 0.20 (20%)
6. **C6**: Risiko Kerusakan | Cost | Bobot: 0.05 (5%)
7. **C7**: Biaya Tambahan | Cost | Bobot: 0.10 (10%)

### 3.2 Data Vendor (`VendorSeeder.php`)
1. **Dyra Erlia Make-Up & Decoration**
   - Alamat: GGFC+D0T, Gg. Swadaya, Bangun Sari Baru, Kec. Tanjung Morawa, Kabupaten Deli Serdang, Sumatera Utara 20362
   - Telp: 081376054211
2. **Syarifah Make-Up**
   - Alamat: Bakaran Batu, Kec. Batang Kuis, Kabupaten Deli Serdang, Sumatera Utara 20372
3. **Adillaa Make-Up**
   - Alamat: GQJJ+BPR, Gg. Sedap Malam, Limau Manis, Kec. Tanjung Morawa, Kabupaten Deli Serdang, Sumatera Utara 20362
   - Telp: 083890749461
4. **Feeza Wedding Kisaran**
   - Alamat: Duta Mas 14 Kisaran Residence, Jl. Saktilubis, Mutiara, Kec. Kota Kisaran Timur, Kabupaten Asahan, Sumatera Utara 21223
5. **Alesya Gallery Wedding**
   - Alamat: Sumatera Utara

### 3.3 Data Paket Bundling (`BundlingSeeder.php`)
- **Dyra Erlia Make-Up & Decoration**: Medium Package (Rp 12.000.000), Mini Package, Lux Package, Wedding Silver Package, Standart Package, Akad Package.
- **Syarifah Make-Up**: Bundling 1 s/d Bundling 14.
- **Adillaa Make-Up**: Akad Package, Wedding Package 1, Wedding Package 2, Akad + Resepsi Package, Akad Intimate Package, Wedding Package 3, Prewedding Package.
- **Feeza Wedding Kisaran**: Wedding Package 1, Wedding Package 2.
- **Alesya Gallery Wedding**: Paket Wedding Standart Rumah, Paket Wedding Gedung Luxury, Paket Wedding Rumah.

---

## 4. LOGIKA PERHITUNGAN SERVICE MARCOS (`app/Services/MarcosService.php`)

```php
namespace App\Services;

use App\Models\Bundling;
use App\Models\Criteria;

class MarcosService
{
    public function calculateRanking()
    {
        $bundlings = Bundling::with(['evaluations', 'vendor'])->where('is_active', true)->get();
        $criterias = Criteria::all();

        if ($bundlings->isEmpty() || $criterias->isEmpty()) {
            return [];
        }

        // 1. Matriks Keputusan X
        $matrix = [];
        foreach ($bundlings as $b) {
            foreach ($criterias as $c) {
                $eval = $b->evaluations->where('criteria_id', $c->id)->first();
                $matrix[$b->id][$c->id] = $eval ? $eval->score : 1;
            }
        }

        // 2. Solusi Ideal (AI) & Anti-Ideal (AAI)
        $ai = [];
        $aai = [];
        foreach ($criterias as $c) {
            $scores = array_column($matrix, $c->id);
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
                    $normalized[$b->id][$c->id] = $x / $ai[$c->id];
                } else {
                    $normalized[$b->id][$c->id] = $ai[$c->id] / $x;
                }
            }
        }

        // 4. Matriks Terbobot (V)
        $weighted = [];
        foreach ($bundlings as $b) {
            foreach ($criterias as $c) {
                $weighted[$b->id][$c->id] = $normalized[$b->id][$c->id] * $c->weight;
            }
        }

        // 5. Perhitungan Nilai S_AI & S_AAI
        $S_AI = 0;
        $S_AAI = 0;
        foreach ($criterias as $c) {
            if ($c->type === 'benefit') {
                $S_AI += ($ai[$c->id] / $ai[$c->id]) * $c->weight;
                $S_AAI += ($aai[$c->id] / $ai[$c->id]) * $c->weight;
            } else {
                $S_AI += ($ai[$c->id] / $ai[$c->id]) * $c->weight;
                $S_AAI += ($ai[$c->id] / $aai[$c->id]) * $c->weight;
            }
        }

        // 6. Fungsi Utilitas f(K_i) & Peringkat
        $rankings = [];
        foreach ($bundlings as $b) {
            $S_i = array_sum($weighted[$b->id]);
            $K_minus = $S_i / $S_AAI;
            $K_plus = $S_i / $S_AI;

            $f_K_minus = $K_plus / ($K_plus + $K_minus);
            $f_K_plus = $K_minus / ($K_plus + $K_minus);
            
            $f_Ki = ($K_plus + $K_minus) / (1 + ((1 - $f_K_plus) / $f_K_plus) + ((1 - $f_K_minus) / $f_K_minus));

            $rankings[] = [
                'bundling_id' => $b->id,
                'package_name' => $b->package_name,
                'vendor_name' => $b->vendor->vendor_name,
                'price' => $b->base_price,
                'S_i' => $S_i,
                'K_minus' => $K_minus,
                'K_plus' => $K_plus,
                'final_score' => $f_Ki
            ];
        }

        usort($rankings, fn($a, $b) => $b['final_score'] <=> $a['final_score']);

        return [
            'matrix' => $matrix,
            'normalized' => $normalized,
            'weighted' => $weighted,
            'ideal' => $ai,
            'anti_ideal' => $aai,
            'rankings' => $rankings
        ];
    }
}