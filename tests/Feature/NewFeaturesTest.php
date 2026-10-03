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

        $orderData = [
            'customer_name' => 'Siti Aisyah',
            'customer_phone' => '081298765432',
            'event_date' => now()->addDays(30)->format('Y-m-d'),
            'event_time' => '10:00 WIB',
            'event_location' => 'Tiara Convention Center, Medan',
            'payment_type' => 'dp',
            'dp_percentage' => 30,
            'notes' => 'Nuansa peach soft glam',
        ];

        $response = $this->post(route('customer.order.create', $bundling->id), $orderData);
        $response->assertRedirect();

        $order = Order::where('customer_phone', '081298765432')->first();
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
}
