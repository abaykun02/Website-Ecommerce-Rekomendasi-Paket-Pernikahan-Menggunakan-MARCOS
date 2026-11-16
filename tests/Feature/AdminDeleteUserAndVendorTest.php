<?php

namespace Tests\Feature;

use App\Models\Bundling;
use App\Models\CartItem;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Review;
use App\Models\ServiceItem;
use App\Models\User;
use App\Models\Vendor;
use Tests\TestCase;

class AdminDeleteUserAndVendorTest extends TestCase
{
    private function createAdmin(): User
    {
        $unique = time() . rand(100, 999);
        return User::create([
            'name' => 'Admin Test ' . $unique,
            'email' => 'admin_' . $unique . '@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_admin_delete_vendor_removes_vendor_and_associated_user_from_database()
    {
        $admin = $this->createAdmin();
        $unique = time() . rand(100, 999);

        $vendorUser = User::create([
            'name' => 'Vendor Owner ' . $unique,
            'email' => 'vendor_' . $unique . '@example.com',
            'password' => bcrypt('password123'),
            'role' => 'vendor',
            'is_active' => true,
            'phone' => '0812' . rand(10000000, 99999999),
        ]);

        $vendor = Vendor::create([
            'user_id' => $vendorUser->id,
            'vendor_name' => 'Toko Vendor ' . $unique,
            'owner_name' => $vendorUser->name,
            'location_address' => 'Medan',
            'phone' => $vendorUser->phone,
            'status' => 'approved',
        ]);

        $bundling = Bundling::create([
            'vendor_id' => $vendor->id,
            'package_name' => 'Paket Test ' . $unique,
            'description' => 'Deskripsi test',
            'base_price' => 5000000,
            'is_active' => true,
        ]);

        $customer = User::create([
            'name' => 'Customer Test ' . $unique,
            'email' => 'cust_' . $unique . '@example.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        CartItem::create([
            'user_id' => $customer->id,
            'vendor_id' => $vendor->id,
            'bundling_id' => $bundling->id,
            'item_type' => 'bundling',
            'item_name' => $bundling->package_name,
            'price' => $bundling->base_price,
            'quantity' => 1,
        ]);

        $order = Order::create([
            'order_code' => 'ORD-' . $unique,
            'customer_id' => $customer->id,
            'vendor_id' => $vendor->id,
            'bundling_id' => $bundling->id,
            'event_date' => now()->addDays(7)->toDateString(),
            'event_location' => 'Medan',
            'total_price' => $bundling->base_price,
            'status' => 'pending',
        ]);

        Payment::create([
            'order_id' => $order->id,
            'bank_name' => 'BCA',
            'account_name' => 'Customer',
            'proof_image' => 'proofs/dummy.jpg',
            'payment_status' => 'pending_verification',
        ]);

        Review::create([
            'order_id' => $order->id,
            'customer_id' => $customer->id,
            'vendor_id' => $vendor->id,
            'rating' => 5,
            'comment' => 'Bagus sekali!',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.vendor.delete', $vendor->id));
        $response->assertSessionHas('success');

        // Pastikan record vendor TERHAPUS di database
        $this->assertDatabaseMissing('vendors', ['id' => $vendor->id]);

        // Pastikan akun user login vendor TERHAPUS di database
        $this->assertDatabaseMissing('users', ['id' => $vendorUser->id]);

        // Pastikan bundlings, orders, payments, reviews terkait vendor juga terhapus
        $this->assertDatabaseMissing('bundlings', ['id' => $bundling->id]);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_admin_delete_customer_removes_user_and_orders_from_database()
    {
        $admin = $this->createAdmin();
        $unique = time() . rand(100, 999);

        $customer = User::create([
            'name' => 'Customer Delete ' . $unique,
            'email' => 'custdel_' . $unique . '@example.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        CartItem::create([
            'user_id' => $customer->id,
            'item_type' => 'bundling',
            'item_name' => 'Item Test',
            'price' => 1000000,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.user.delete', $customer->id));
        $response->assertSessionHas('success');

        // Pastikan user customer TERHAPUS di database
        $this->assertDatabaseMissing('users', ['id' => $customer->id]);
        $this->assertDatabaseMissing('cart_items', ['user_id' => $customer->id]);
    }

    public function test_admin_delete_vendor_user_from_users_menu_removes_both_user_and_vendor()
    {
        $admin = $this->createAdmin();
        $unique = time() . rand(100, 999);

        $vendorUser = User::create([
            'name' => 'Vendor User Via Users Menu ' . $unique,
            'email' => 'vendor_menu_' . $unique . '@example.com',
            'password' => bcrypt('password123'),
            'role' => 'vendor',
            'is_active' => true,
            'phone' => '0812' . rand(10000000, 99999999),
        ]);

        $vendor = Vendor::create([
            'user_id' => $vendorUser->id,
            'vendor_name' => 'Toko Menu ' . $unique,
            'owner_name' => $vendorUser->name,
            'location_address' => 'Medan',
            'phone' => $vendorUser->phone,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.user.delete', $vendorUser->id));
        $response->assertSessionHas('success');

        // Keduanya harus terhapus di database
        $this->assertDatabaseMissing('users', ['id' => $vendorUser->id]);
        $this->assertDatabaseMissing('vendors', ['id' => $vendor->id]);
    }

    public function test_admin_cannot_delete_own_account()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->delete(route('admin.user.delete', $admin->id));
        $response->assertSessionHas('error');

        // Admin tetap ada di database
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}

