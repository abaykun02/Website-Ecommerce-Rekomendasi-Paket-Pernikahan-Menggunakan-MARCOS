<?php

namespace Database\Seeders;

use App\Models\Bundling;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class SampleOrdersSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan customer tersedia
        $customer1 = User::firstOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Sarah Amanda',
                'password' => '123',
                'role' => 'customer',
                'phone' => '082167891234',
                'address' => 'Jl. Karya Wisata No. 45, Medan Johor',
                'is_active' => true,
            ]
        );

        $customer2 = User::firstOrCreate(
            ['email' => 'siti@gmail.com'],
            [
                'name' => 'Siti Rahma',
                'password' => '123',
                'role' => 'customer',
                'phone' => '085278912345',
                'address' => 'Jl. Ringroad No. 12, Medan Selayang',
                'is_active' => true,
            ]
        );

        $customer3 = User::firstOrCreate(
            ['email' => 'budi.santoso@gmail.com'],
            [
                'name' => 'Budi Santoso',
                'password' => '123',
                'role' => 'customer',
                'phone' => '081398765432',
                'address' => 'Jl. Setia Budi No. 88, Medan',
                'is_active' => true,
            ]
        );

        $customer4 = User::firstOrCreate(
            ['email' => 'rian.pratama@gmail.com'],
            [
                'name' => 'Rian Pratama',
                'password' => '123',
                'role' => 'customer',
                'phone' => '081234567891',
                'address' => 'Jl. Imam Bonjol No. 15, Kisaran',
                'is_active' => true,
            ]
        );

        // Ambil Vendor 1 - 5
        $vendors = Vendor::orderBy('id')->take(5)->get();
        if ($vendors->count() < 5) {
            $this->command->warn('Vendor tidak mencukupi (minimal 5 vendor).');
            return;
        }

        $v1 = $vendors[0]; // Dyra Erlia
        $v2 = $vendors[1]; // Syarifah
        $v3 = $vendors[2]; // Adilla
        $v4 = $vendors[3]; // Feeza
        $v5 = $vendors[4]; // Alesya

        // Bundling masing-masing vendor
        $b1_1 = Bundling::where('vendor_id', $v1->id)->first();
        $b1_2 = Bundling::where('vendor_id', $v1->id)->skip(1)->first() ?? $b1_1;

        $b2_1 = Bundling::where('vendor_id', $v2->id)->first();
        $b2_2 = Bundling::where('vendor_id', $v2->id)->skip(1)->first() ?? $b2_1;

        $b3_1 = Bundling::where('vendor_id', $v3->id)->first();
        $b3_2 = Bundling::where('vendor_id', $v3->id)->skip(1)->first() ?? $b3_1;

        $b4_1 = Bundling::where('vendor_id', $v4->id)->first();
        $b4_2 = Bundling::where('vendor_id', $v4->id)->skip(1)->first() ?? $b4_1;

        $b5_1 = Bundling::where('vendor_id', $v5->id)->first();
        $b5_2 = Bundling::where('vendor_id', $v5->id)->skip(1)->first() ?? $b5_1;

        $ordersData = [
            // 1. Vendor 1 (Dyra) - Completed
            [
                'order_code' => 'WED-261101-001',
                'customer' => $customer1,
                'vendor' => $v1,
                'bundling' => $b1_1,
                'event_date' => now()->subDays(12)->toDateString(),
                'event_time' => '09:00',
                'event_location' => 'Gedung Serbaguna Tj. Morawa, Deli Serdang',
                'event_region' => 'Tanjung Morawa',
                'status' => 'completed',
                'payment_type' => 'full',
                'payment_method' => 'midtrans',
                'review' => 'Pelayanan sangat memuaskan, riasan pengantin awet dan dekorasinya elegan sekali!',
                'rating' => 5,
            ],
            // 2. Vendor 1 (Dyra) - Paid
            [
                'order_code' => 'WED-261102-002',
                'customer' => $customer2,
                'vendor' => $v1,
                'bundling' => $b1_2,
                'event_date' => now()->addDays(8)->toDateString(),
                'event_time' => '10:00',
                'event_location' => 'Jl. Medan - Batang Kuis No. 20',
                'event_region' => 'Batang Kuis',
                'status' => 'paid',
                'payment_type' => 'dp_50',
                'payment_method' => 'manual_transfer',
            ],
            // 3. Vendor 2 (Syarifah) - Paid
            [
                'order_code' => 'WED-261102-003',
                'customer' => $customer3,
                'vendor' => $v2,
                'bundling' => $b2_1,
                'event_date' => now()->addDays(15)->toDateString(),
                'event_time' => '13:00',
                'event_location' => 'Kec. Batang Kuis, Deli Serdang',
                'event_region' => 'Batang Kuis',
                'status' => 'paid',
                'payment_type' => 'full',
                'payment_method' => 'midtrans',
            ],
            // 4. Vendor 2 (Syarifah) - Pending
            [
                'order_code' => 'WED-261103-004',
                'customer' => $customer1,
                'vendor' => $v2,
                'bundling' => $b2_2,
                'event_date' => now()->addDays(20)->toDateString(),
                'event_time' => '08:30',
                'event_location' => 'Komplek Perumahan Indah Batang Kuis',
                'event_region' => 'Batang Kuis',
                'status' => 'pending',
                'payment_type' => 'dp_30',
                'payment_method' => 'manual_transfer',
            ],
            // 5. Vendor 3 (Adilla) - Completed
            [
                'order_code' => 'WED-261103-005',
                'customer' => $customer2,
                'vendor' => $v3,
                'bundling' => $b3_1,
                'event_date' => now()->subDays(5)->toDateString(),
                'event_time' => '11:00',
                'event_location' => 'Gg. Sedap Malam, Limau Manis, Tj. Morawa',
                'event_region' => 'Tanjung Morawa',
                'status' => 'completed',
                'payment_type' => 'full',
                'payment_method' => 'midtrans',
                'review' => 'Make up-nya natural tapi fresh banget, tahan seharian tanpa retouch berat.',
                'rating' => 5,
            ],
            // 6. Vendor 3 (Adilla) - Cancelled
            [
                'order_code' => 'WED-261104-006',
                'customer' => $customer4,
                'vendor' => $v3,
                'bundling' => $b3_2,
                'event_date' => now()->addDays(25)->toDateString(),
                'event_time' => '14:00',
                'event_location' => 'Medan Kota',
                'event_region' => 'Medan',
                'status' => 'cancelled',
                'payment_type' => 'full',
                'payment_method' => 'manual_transfer',
            ],
            // 7. Vendor 4 (Feeza Kisaran) - Completed
            [
                'order_code' => 'WED-261104-007',
                'customer' => $customer4,
                'vendor' => $v4,
                'bundling' => $b4_1,
                'event_date' => now()->subDays(3)->toDateString(),
                'event_time' => '10:00',
                'event_location' => 'Gedung Pertemuan Asahan Indah, Kota Kisaran Timur',
                'event_region' => 'Kisaran',
                'status' => 'completed',
                'payment_type' => 'full',
                'payment_method' => 'midtrans',
                'review' => 'Paket wedding lengkap di Kisaran! Dekorasi pelaminan mewah dan tim sangat profesional.',
                'rating' => 5,
            ],
            // 8. Vendor 4 (Feeza Kisaran) - Pending
            [
                'order_code' => 'WED-261105-008',
                'customer' => $customer3,
                'vendor' => $v4,
                'bundling' => $b4_2,
                'event_date' => now()->addDays(30)->toDateString(),
                'event_time' => '09:00',
                'event_location' => 'Duta Mas 14 Kisaran Residence',
                'event_region' => 'Kisaran',
                'status' => 'pending',
                'payment_type' => 'dp_50',
                'payment_method' => 'midtrans',
            ],
            // 9. Vendor 5 (Alesya) - Paid
            [
                'order_code' => 'WED-261105-009',
                'customer' => $customer1,
                'vendor' => $v5,
                'bundling' => $b5_1,
                'event_date' => now()->addDays(12)->toDateString(),
                'event_time' => '10:30',
                'event_location' => 'Mabar Hilir, Medan Deli',
                'event_region' => 'Medan',
                'status' => 'paid',
                'payment_type' => 'full',
                'payment_method' => 'midtrans',
            ],
            // 10. Vendor 5 (Alesya) - Cancelled
            [
                'order_code' => 'WED-261105-010',
                'customer' => $customer2,
                'vendor' => $v5,
                'bundling' => $b5_2,
                'event_date' => now()->addDays(18)->toDateString(),
                'event_time' => '13:00',
                'event_location' => 'Rumah Pengantin, Medan Deli',
                'event_region' => 'Medan',
                'status' => 'cancelled',
                'payment_type' => 'full',
                'payment_method' => 'manual_transfer',
            ],
        ];

        foreach ($ordersData as $data) {
            $totalPrice = (float) $data['bundling']->base_price;
            $isDp = str_starts_with($data['payment_type'], 'dp');
            $orderPaymentType = $isDp ? 'dp' : 'full_payment';
            $dpPercentage = match($data['payment_type']) {
                'dp_30' => 30,
                'dp_50' => 50,
                default => 100,
            };
            $dpAmount = ($dpPercentage < 100) ? round($totalPrice * ($dpPercentage / 100)) : $totalPrice;

            $order = Order::updateOrCreate(
                ['order_code' => $data['order_code']],
                [
                    'customer_id' => $data['customer']->id,
                    'customer_name' => $data['customer']->name,
                    'customer_phone' => $data['customer']->phone,
                    'vendor_id' => $data['vendor']->id,
                    'bundling_id' => $data['bundling']->id,
                    'event_date' => $data['event_date'],
                    'event_time' => $data['event_time'],
                    'event_location' => $data['event_location'],
                    'event_region' => $data['event_region'],
                    'total_price' => $totalPrice,
                    'payment_type' => $orderPaymentType,
                    'dp_amount' => $dpAmount,
                    'dp_percentage' => $dpPercentage,
                    'status' => $data['status'],
                    'notes' => 'Permintaan tema nuansa pastel lembut elegan.',
                ]
            );

            // Buat data payment
            $paymentStatus = match($data['status']) {
                'completed', 'paid' => 'verified',
                'cancelled' => 'rejected',
                default => 'pending_verification',
            };

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'amount' => $dpAmount,
                    'payment_type' => ($data['payment_method'] === 'midtrans' ? 'online' : 'transfer_manual'),
                    'bank_name' => ($data['payment_method'] === 'midtrans' ? 'Midtrans (BCA/QRIS)' : 'Bank BCA'),
                    'account_name' => $data['customer']->name,
                    'proof_image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=80',
                    'payment_status' => $paymentStatus,
                    'verified_at' => in_array($data['status'], ['completed', 'paid']) ? now()->subDays(1) : null,
                ]
            );

            // Buat ulasan jika status completed
            if (!empty($data['review'])) {
                Review::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'customer_id' => $data['customer']->id,
                        'vendor_id' => $data['vendor']->id,
                        'rating' => $data['rating'] ?? 5,
                        'comment' => $data['review'],
                    ]
                );
            }
        }

        $this->command->info('10 pesanan sampel berhasil dibuat di seluruh 5 vendor!');
    }
}
