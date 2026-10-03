<?php

namespace Database\Seeders;

use App\Models\Bundling;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('role', 'customer')->first();
        if (!$customer) {
            return;
        }

        $bundling1 = Bundling::where('package_name', 'Medium Package')->first() ?? Bundling::first();
        if (!$bundling1) {
            return;
        }

        // 1. Order Selesai dengan Review
        $order1 = Order::updateOrCreate(
            ['order_code' => 'ORD-GG-20260901-001'],
            [
                'customer_id' => $customer->id,
                'vendor_id' => $bundling1->vendor_id,
                'bundling_id' => $bundling1->id,
                'event_date' => now()->subDays(10)->toDateString(),
                'event_location' => 'Gedung Serbaguna Tanjung Morawa, Deli Serdang',
                'total_price' => $bundling1->base_price,
                'status' => 'completed',
            ]
        );

        Payment::updateOrCreate(
            ['order_id' => $order1->id],
            [
                'bank_name' => 'BCA',
                'account_name' => $customer->name,
                'proof_image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=80',
                'payment_status' => 'verified',
                'verified_at' => now()->subDays(9),
            ]
        );

        Review::updateOrCreate(
            ['order_id' => $order1->id],
            [
                'customer_id' => $customer->id,
                'vendor_id' => $bundling1->vendor_id,
                'rating' => 5,
                'comment' => 'Hasil riasannya luar biasa halus dan tahan dari pagi sampai malam! Tamu-tamu banyak yang memuji dekorasi pelaminannya yang mewah dan rapi.',
            ]
        );

        // 2. Order Aktif (Approved - menunggu bayar / pending verifikasi)
        $bundling2 = Bundling::where('package_name', 'like', '%Akad%')->first() ?? $bundling1;
        $order2 = Order::updateOrCreate(
            ['order_code' => 'ORD-GG-20260929-002'],
            [
                'customer_id' => $customer->id,
                'vendor_id' => $bundling2->vendor_id,
                'bundling_id' => $bundling2->id,
                'event_date' => now()->addDays(14)->toDateString(),
                'event_location' => 'Jl. Karya Wisata No. 45, Medan',
                'total_price' => $bundling2->base_price,
                'status' => 'paid',
            ]
        );

        Payment::updateOrCreate(
            ['order_id' => $order2->id],
            [
                'bank_name' => 'Bank Mandiri',
                'account_name' => $customer->name,
                'proof_image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=600&q=80',
                'payment_status' => 'pending_verification',
            ]
        );
    }
}
