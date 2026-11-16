<?php

namespace Tests\Feature;

use App\Models\Bundling;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use Tests\TestCase;

class VendorBankAccountAndPaymentTest extends TestCase
{
    public function test_vendor_can_update_bank_account_information()
    {
        $vendorUser = User::where('role', 'vendor')->first();
        $this->actingAs($vendorUser);

        $response = $this->post(route('vendor.profile.update'), [
            'vendor_name' => $vendorUser->vendor->vendor_name,
            'owner_name' => $vendorUser->vendor->owner_name,
            'phone' => '08123456789',
            'location_address' => 'Jl. Pengantin No. 1 Medan',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Nama Pemilik Test',
            'bank_notes' => 'Tulis kode pesanan saat transfer',
        ]);

        $response->assertSessionHas('success');

        $vendorUser->vendor->refresh();
        $this->assertEquals('BCA', $vendorUser->vendor->bank_name);
        $this->assertEquals('1234567890', $vendorUser->vendor->bank_account_number);
        $this->assertEquals('Nama Pemilik Test', $vendorUser->vendor->bank_account_name);
        $this->assertTrue($vendorUser->vendor->hasBankAccount());
    }

    public function test_customer_order_detail_shows_vendor_bank_account_for_payment()
    {
        $customer = User::where('role', 'customer')->first();
        $vendor = Vendor::whereNotNull('bank_account_number')->first();
        $bundling = Bundling::where('vendor_id', $vendor->id)->first();

        $order = Order::create([
            'order_code' => 'ORD-TEST-BANK-' . time(),
            'customer_id' => $customer->id,
            'vendor_id' => $vendor->id,
            'bundling_id' => $bundling->id,
            'event_date' => now()->addDays(14)->toDateString(),
            'event_location' => 'Medan',
            'total_price' => $bundling->base_price,
            'status' => 'pending',
            'payment_type' => 'full_payment',
            'payable_amount' => $bundling->base_price,
        ]);

        $response = $this->actingAs($customer)->get(route('customer.order.detail', $order->id));
        $response->assertStatus(200);

        // Memastikan nama bank, nomor rekening, dan nama pemilik rekening vendor muncul di halaman detail pesanan pelanggan
        $response->assertSee($vendor->bank_name);
        $response->assertSee($vendor->bank_account_number);
        $response->assertSee($vendor->bank_account_name);
        $response->assertSee($vendor->vendor_name);
    }
}
