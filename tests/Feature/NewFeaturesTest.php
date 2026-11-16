<?php

namespace Tests\Feature;

use App\Models\Bundling;
use App\Models\Category;
use App\Models\Criteria;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ServiceItem;
use App\Models\User;
use App\Models\Vendor;
use App\Services\MarcosService;
use Tests\TestCase;

class NewFeaturesTest extends TestCase
{
    /**
     * Test dynamic preference weighting in MarcosService
     */
    public function test_marcos_service_dynamic_weighting()
    {
        $service = new MarcosService();
        
        // 1. Standard calculation
        $standard = $service->calculateRanking();
        $this->assertNotEmpty($standard['rankings']);
        $standardC5 = collect($standard['criterias'])->firstWhere('code', 'C5');

        // 2. Low price preference calculation
        $lowPrice = $service->calculateRanking(['low_price']);
        $this->assertTrue($lowPrice['is_dynamic']);
        $lowPriceC5 = collect($lowPrice['criterias'])->firstWhere('code', 'C5');
        
        // C5 weight must be higher under low_price preference
        $this->assertGreaterThan($standardC5->weight, $lowPriceC5->weight);
    }

    /**
     * Test multi-category landing page and services routes
     */
    public function test_multi_category_services_routes()
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Jelajahi Layanan');

        $servicesResponse = $this->get(route('services'));
        $servicesResponse->assertStatus(200);
        $servicesResponse->assertSee('Katalog Multi-Kategori');

        $firstService = ServiceItem::first();
        if ($firstService) {
            $detailResponse = $this->get(route('service.detail', $firstService->id));
            $detailResponse->assertStatus(200);
            $detailResponse->assertSee($firstService->name);
        }
    }

    /**
     * Test complete checkout with DP calculation
     */
    public function test_customer_complete_checkout_with_dp()
    {
        $customer = User::where('role', 'customer')->first();
        $bundling = Bundling::first();

        $this->actingAs($customer);

        $uniquePhone = '0812' . rand(10000000, 99999999);
        $orderData = [
            'customer_name' => 'Siti Aisyah',
            'customer_phone' => $uniquePhone,
            'event_date' => now()->addDays(30)->format('Y-m-d'),
            'event_time' => '10:00 WIB',
            'event_location' => 'Tiara Convention Center, Medan',
            'payment_type' => 'dp',
            'dp_percentage' => 30,
            'notes' => 'Nuansa peach soft glam',
        ];

        $response = $this->post(route('customer.order.create', $bundling->id), $orderData);
        $response->assertRedirect();

        $order = Order::where('customer_phone', $uniquePhone)->latest('id')->first();
        $this->assertNotNull($order);
        $this->assertEquals('Siti Aisyah', $order->customer_name);
        $this->assertEquals('dp', $order->payment_type);
        $expectedDp = round((float) $bundling->base_price * 0.3);
        $this->assertEquals($expectedDp, (float) $order->dp_amount);
        $this->assertEquals($expectedDp, $order->payable_amount);
    }

    /**
     * Test Midtrans webhook callback updates order status to paid without CSRF token
     */
    public function test_midtrans_webhook_callback_without_csrf()
    {
        $order = Order::first();
        $this->assertNotNull($order);

        $payload = [
            'order_id' => $order->order_code . '-' . time(),
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'gross_amount' => $order->payable_amount,
            'payment_type' => 'qris',
            'transaction_id' => 'midtrans-test-' . uniqid(),
        ];

        $response = $this->postJson(route('midtrans.callback'), $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'order_status' => 'paid',
        ]);

        $order->refresh();
        $this->assertEquals('paid', $order->status);
        $this->assertEquals('settlement', $order->midtrans_status);

        $payment = Payment::where('order_id', $order->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals('verified', $payment->payment_status);
    }

    /**
     * Test distance/location criteria C4 priority weighting and dynamic location ranking
     */
    public function test_marcos_service_distance_and_location_prioritization()
    {
        $service = new MarcosService();

        // 1. Standard calculation without preference
        $standard = $service->calculateRanking();
        $standardC4 = collect($standard['criterias'])->firstWhere('code', 'C4');
        $this->assertEquals(0.10, (float) $standardC4->weight);

        // 2. Preference 'terdekat' boosts C4 to high priority (Cost)
        $terdekat = $service->calculateRanking(['terdekat']);
        $this->assertTrue($terdekat['is_dynamic']);
        $terdekatC4 = collect($terdekat['criterias'])->firstWhere('code', 'C4');

        // C4 weight must be heavily boosted (e.g. > 0.30)
        $this->assertGreaterThan(0.30, (float) $terdekatC4->weight);
        $this->assertGreaterThan($standardC4->weight, $terdekatC4->weight);

        // 3. Location-aware ranking for Kisaran
        // When customer is in Kisaran and chooses 'terdekat', Feeza Wedding (Kisaran) should rank top
        $kisaranRank = $service->calculateRanking(['terdekat'], 'Kisaran');
        $topRanking = $kisaranRank['rankings'][0] ?? null;
        $this->assertNotNull($topRanking);
        $this->assertStringContainsString('Kisaran', $topRanking['vendor_name']);
        $this->assertEquals(1.0, (float) $topRanking['proximity']['score']);

        // 4. Location-aware ranking for Tanjung Morawa
        $tjMorawaRank = $service->calculateRanking(['terdekat'], 'Tanjung Morawa');
        $topTjRanking = $tjMorawaRank['rankings'][0] ?? null;
        $this->assertNotNull($topTjRanking);
        $this->assertEquals('Tanjung Morawa', $topTjRanking['vendor_region']);
        $this->assertEquals(1.0, (float) $topTjRanking['proximity']['score']);
    }

    /**
     * Test checkout saves event_region and calculates proximity with vendor
     */
    public function test_checkout_saves_event_region_and_matches_vendor_proximity()
    {
        $customer = User::where('role', 'customer')->first();
        $bundling = Bundling::whereHas('vendor', fn($q) => $q->where('location_address', 'like', '%Tj. Morawa%'))->first();

        $this->actingAs($customer);

        $orderData = [
            'customer_name' => 'Fadhil Rahman',
            'customer_phone' => '082199887766',
            'event_date' => now()->addDays(20)->format('Y-m-d'),
            'event_time' => '09:00 WIB',
            'event_region' => 'Tanjung Morawa',
            'event_location' => 'Gedung Serbaguna Bangun Sari Baru, Kec. Tanjung Morawa',
            'payment_type' => 'full_payment',
        ];

        $response = $this->post(route('customer.order.create', $bundling->id), $orderData);
        $response->assertRedirect();

        $order = Order::where('customer_phone', '082199887766')->first();
        $this->assertNotNull($order);
        $this->assertEquals('Tanjung Morawa', $order->event_region);

        // Check proximity matching
        $proximity = $order->proximity;
        $this->assertNotNull($proximity);
        $this->assertEquals('sangat_dekat', $proximity['level']);
        $this->assertTrue($proximity['is_same_region']);
        $this->assertEquals(1.0, (float) $proximity['score']);
    }

    /**
     * Test SPK recommendation page with location filter loads successfully
     */
    public function test_spk_recommendation_page_with_location_filter()
    {
        $response = $this->get(route('spk.rekomendasi', [
            'location' => 'Tanjung Morawa',
            'preferences' => ['terdekat'],
        ]));

        $response->assertStatus(200);
        $response->assertSee('Lokasi / Wilayah Acara Anda');
        $response->assertSee('Tanjung Morawa');
        $response->assertSee('Kedekatan Lokasi Terintegrasi');
    }

    /**
     * Test vendor can edit bundling package details and status
     */
    public function test_vendor_can_edit_bundling_package()
    {
        $vendorUser = User::where('role', 'vendor')->first();
        $this->assertNotNull($vendorUser);
        $vendor = $vendorUser->vendor;
        $this->assertNotNull($vendor);

        $bundling = Bundling::create([
            'vendor_id' => $vendor->id,
            'package_name' => 'Paket Testing Khusus Edit',
            'description' => 'Deskripsi lama fasilitas paket',
            'base_price' => 7500000,
            'is_active' => true,
        ]);

        $newName = 'Paket Resepsi Eksklusif (Telah Diperbarui)';
        $newPrice = 8500000;
        $newDesc = 'Deskripsi baru dengan tambahan gaun premium dan dekor pelaminan 8 meter.';

        $response = $this->actingAs($vendorUser)->put(route('vendor.bundling.update', $bundling->id), [
            'package_name' => $newName,
            'base_price' => $newPrice,
            'description' => $newDesc,
            'is_active' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $bundling->refresh();
        $this->assertEquals($newName, $bundling->package_name);
        $this->assertEquals($newPrice, (int)$bundling->base_price);
        $this->assertEquals($newDesc, $bundling->description);
        $this->assertTrue((bool)$bundling->is_active);

        // Pastikan di halaman kelola paket vendor, nama baru dan tombol edit muncul
        $pageResponse = $this->actingAs($vendorUser)->get(route('vendor.bundlings'));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee($newName);
        $pageResponse->assertSee('Edit Paket');

        // Bersihkan data tes
        $bundling->delete();
    }

    /**
     * Test admin orders monitoring (read-only per vendor) & vendor payment proof feature
     */
    public function test_admin_orders_monitoring_and_vendor_payment_proof()
    {
        $admin = User::where('role', 'admin')->first();
        $vendorUser = User::where('role', 'vendor')->first();

        // 1. Admin order monitoring test
        $adminResp = $this->actingAs($admin)->get('/admin/pesanan');
        $adminResp->assertStatus(200);
        $adminResp->assertSee('Daftar Pemesanan Tiap Mitra Vendor');
        $adminResp->assertSee('Mode Pengawasan (Read-Only)');
        $adminResp->assertSee('Pilih Mitra Vendor:');
        $adminResp->assertDontSee('Aksi Verifikasi');

        // Test filter vendor di admin
        $vendorId = $vendorUser->vendor->id;
        $adminFilterResp = $this->actingAs($admin)->get("/admin/pesanan?vendor_id={$vendorId}");
        $adminFilterResp->assertStatus(200);

        // 2. Vendor orders payment proof test
        $vendorResp = $this->actingAs($vendorUser)->get('/vendor/pesanan');
        $vendorResp->assertStatus(200);
        $vendorResp->assertSee('Bukti Pembayaran');
        $vendorResp->assertSee('vendorPaymentModal');
    }

    /**
     * Test home realtime AJAX filtering without page reload
     */
    public function test_home_realtime_ajax_filtering()
    {
        $response = $this->get('/?categories[]=make-up-only&preferences[]=c1&preferences[]=c2&location=Medan', [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'selected_cat_count' => 1,
            'selected_pref_count' => 2,
            'location' => 'Medan',
        ]);
        $response->assertJsonStructure([
            'status',
            'recommendations_html',
            'services_html',
            'selected_cat_count',
            'selected_pref_count',
            'active_categories',
            'preferences',
            'location',
        ]);

        $content = $response->json();
        $this->assertNotEmpty($content['recommendations_html']);
    }

    /**
     * Test MARCOS calculation 100% matches manual calculation in Proposal Perhhitungan.pdf
     */
    public function test_marcos_calculation_exactly_matches_proposal_pdf()
    {
        $service = new MarcosService();
        $res = $service->calculateRanking();

        $this->assertEquals(3.75, (float) $res['S_AI']);
        $this->assertEquals(0.696665, (float) $res['S_AAI']);

        $rankings = collect($res['rankings'])->where('is_spk', true)->values();

        // 1. Rank 1 must be A31 Paket Wedding Gedung Luxury
        $rank1 = $rankings->firstWhere('code', 'A31');
        $this->assertNotNull($rank1);
        $this->assertEquals(1, $rank1['rank']);
        $this->assertEqualsWithDelta(0.429282, $rank1['final_score'], 0.0001);

        // 2. Rank 2 must be A12 and A15 (score ~ 0.401641)
        $rankA12 = $rankings->firstWhere('code', 'A12');
        $rankA15 = $rankings->firstWhere('code', 'A15');
        $this->assertEquals(2, $rankA12['rank']);
        $this->assertEquals(2, $rankA15['rank']);
        $this->assertEqualsWithDelta(0.401641, $rankA12['final_score'], 0.0001);
        $this->assertEqualsWithDelta(0.401641, $rankA15['final_score'], 0.0001);

        // 3. Rank 4 must be A32 (score ~ 0.400778)
        $rankA32 = $rankings->firstWhere('code', 'A32');
        $this->assertEquals(4, $rankA32['rank']);
        $this->assertEqualsWithDelta(0.400778, $rankA32['final_score'], 0.0001);

        // 4. Rank 5 must be A13 and A14 (score ~ 0.393867)
        $rankA13 = $rankings->firstWhere('code', 'A13');
        $rankA14 = $rankings->firstWhere('code', 'A14');
        $this->assertEquals(5, $rankA13['rank']);
        $this->assertEquals(5, $rankA14['rank']);
        $this->assertEqualsWithDelta(0.393867, $rankA13['final_score'], 0.0001);
        $this->assertEqualsWithDelta(0.393867, $rankA14['final_score'], 0.0001);

        // 5. Solusi Ideal (AI) & Anti-Ideal (AAI) must match Proposal PDF
        $criterias = collect($res['criterias'])->keyBy('code');
        $this->assertEquals(1.0, (float) $res['ideal'][$criterias['C1']->id]);
        $this->assertEquals(4.0, (float) $res['anti_ideal'][$criterias['C1']->id]);
        $this->assertEquals(3.0, (float) $res['ideal'][$criterias['C2']->id]);
        $this->assertEquals(4.0, (float) $res['anti_ideal'][$criterias['C2']->id]);
        $this->assertEquals(5.0, (float) $res['ideal'][$criterias['C3']->id]);
        $this->assertEquals(3.0, (float) $res['anti_ideal'][$criterias['C3']->id]);
        $this->assertEquals(5.0, (float) $res['ideal'][$criterias['C4']->id]);
        $this->assertEquals(4.0, (float) $res['anti_ideal'][$criterias['C4']->id]);
        $this->assertEquals(5.0, (float) $res['ideal'][$criterias['C5']->id]);
        $this->assertEquals(3.0, (float) $res['anti_ideal'][$criterias['C5']->id]);
        $this->assertEquals(3.0, (float) $res['ideal'][$criterias['C6']->id]);
        $this->assertEquals(4.0, (float) $res['anti_ideal'][$criterias['C6']->id]);
        $this->assertEquals(5.0, (float) $res['ideal'][$criterias['C7']->id]);
        $this->assertEquals(3.0, (float) $res['anti_ideal'][$criterias['C7']->id]);
    }

    /**
     * Test clearing evaluations and re-importing yields exact same calculations
     */
    public function test_clear_evaluations_and_reimport_preserves_exact_rankings()
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        // 1. Export current evaluations to CSV
        $exportResponse = $this->get(route('admin.evaluations.template-excel'));
        $exportResponse->assertStatus(200);
        $csvContent = $exportResponse->streamedContent();
        $this->assertNotEmpty($csvContent);

        // 2. Clear evaluations
        $clearResponse = $this->post(route('admin.evaluations.clear'));
        $clearResponse->assertRedirect();
        $this->assertEquals(0, \App\Models\BundlingEvaluation::count());

        // 3. Verify customer home displays "Paket yang direkomendasikan belum ada"
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Paket yang direkomendasikan belum ada');

        // 4. Create temporary CSV file from exported content and re-import
        $tempPath = tempnam(sys_get_temp_dir(), 'eval_test_') . '.csv';
        file_put_contents($tempPath, $csvContent);

        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $tempPath,
            'evaluations_backup.csv',
            'text/csv',
            null,
            true
        );

        $importResponse = $this->post(route('admin.evaluations.import-excel'), [
            'excel_file' => $uploadedFile,
        ]);
        $importResponse->assertRedirect();
        @unlink($tempPath);

        // 5. Verify calculations are 100% restored and identical to Proposal PDF
        $service = new MarcosService();
        $res = $service->calculateRanking();
        $this->assertNotEmpty($res['rankings']);

        $rank1 = collect($res['rankings'])->firstWhere('code', 'A31');
        $this->assertNotNull($rank1);
        $this->assertEquals(1, $rank1['rank']);
        $this->assertEqualsWithDelta(0.429282, $rank1['final_score'], 0.0001);

        $rankA12 = collect($res['rankings'])->firstWhere('code', 'A12');
        $this->assertEquals(2, $rankA12['rank']);
        $this->assertEqualsWithDelta(0.401641, $rankA12['final_score'], 0.0001);
    }
}

