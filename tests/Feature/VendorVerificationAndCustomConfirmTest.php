<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorVerificationAndCustomConfirmTest extends TestCase
{
    public function test_vendor_registration_creates_pending_and_inactive_account()
    {
        $unique = time() . rand(100, 999);
        $response = $this->post('/register-vendor', [
            'name' => 'Owner Test ' . $unique,
            'email' => 'vendor' . $unique . '@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'phone' => '0812' . rand(10000000, 99999999),
            'vendor_name' => 'Toko Dekor ' . $unique,
            'location_address' => 'Jl. Pahlawan No. 10 Medan',
            'description' => 'Vendor dekorasi pernikahan estetik',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $user = User::where('email', 'vendor' . $unique . '@example.com')->first();
        $this->assertNotNull($user);
        $this->assertFalse((bool) $user->is_active);

        $vendor = Vendor::where('user_id', $user->id)->first();
        $this->assertNotNull($vendor);
        $this->assertEquals('pending', $vendor->status);
    }

    public function test_pending_vendor_cannot_login_and_gets_pending_modal()
    {
        $unique = time() . rand(100, 999);
        $user = User::create([
            'name' => 'Pending Owner ' . $unique,
            'email' => 'pending' . $unique . '@example.com',
            'password' => 'secret123',
            'role' => 'vendor',
            'is_active' => false,
            'phone' => '0812' . rand(10000000, 99999999),
            'address' => 'Medan',
        ]);

        Vendor::create([
            'user_id' => $user->id,
            'vendor_name' => 'Pending Toko ' . $unique,
            'owner_name' => $user->name,
            'location_address' => 'Medan',
            'phone' => $user->phone,
            'status' => 'pending',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();

        $errorModal = session('error_modal');
        $this->assertIsArray($errorModal);
        $this->assertEquals('pending_verification', $errorModal['type']);
    }

    public function test_admin_can_approve_vendor_and_then_vendor_can_login()
    {
        $admin = User::where('role', 'admin')->first() ?: User::create([
            'name' => 'Administrator',
            'email' => 'admintest' . time() . '@example.com',
            'password' => 'admin123',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $unique = time() . rand(100, 999);
        $user = User::create([
            'name' => 'To Approve Owner ' . $unique,
            'email' => 'toapprove' . $unique . '@example.com',
            'password' => 'secret123',
            'role' => 'vendor',
            'is_active' => false,
            'phone' => '0812' . rand(10000000, 99999999),
            'address' => 'Tanjung Morawa',
        ]);

        $vendor = Vendor::create([
            'user_id' => $user->id,
            'vendor_name' => 'Vendor Siap Disetujui ' . $unique,
            'owner_name' => $user->name,
            'location_address' => 'Tanjung Morawa',
            'phone' => $user->phone,
            'status' => 'pending',
        ]);

        // Admin approves vendor
        $response = $this->actingAs($admin)->post(route('admin.vendor.approve', $vendor->id));
        $response->assertSessionHas('success');

        $vendor->refresh();
        $user->refresh();

        $this->assertEquals('approved', $vendor->status);
        $this->assertNotNull($vendor->verified_at);
        $this->assertTrue((bool) $user->is_active);

        // Logout admin and vendor logs in
        auth()->logout();

        $loginResponse = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $this->assertAuthenticatedAs($user);
        $loginResponse->assertRedirect(route('vendor.dashboard'));
    }

    public function test_admin_can_reject_vendor_and_vendor_cannot_login()
    {
        $admin = User::where('role', 'admin')->first();

        $unique = time() . rand(100, 999);
        $user = User::create([
            'name' => 'To Reject Owner ' . $unique,
            'email' => 'toreject' . $unique . '@example.com',
            'password' => 'secret123',
            'role' => 'vendor',
            'is_active' => false,
            'phone' => '0812' . rand(10000000, 99999999),
            'address' => 'Lubuk Pakam',
        ]);

        $vendor = Vendor::create([
            'user_id' => $user->id,
            'vendor_name' => 'Vendor Ditolak ' . $unique,
            'owner_name' => $user->name,
            'location_address' => 'Lubuk Pakam',
            'phone' => $user->phone,
            'status' => 'pending',
        ]);

        // Admin rejects vendor
        $response = $this->actingAs($admin)->post(route('admin.vendor.reject', $vendor->id), [
            'rejection_reason' => 'Nomor WhatsApp tidak aktif saat dihubungi.',
        ]);
        $response->assertSessionHas('success');

        $vendor->refresh();
        $user->refresh();

        $this->assertEquals('rejected', $vendor->status);
        $this->assertEquals('Nomor WhatsApp tidak aktif saat dihubungi.', $vendor->rejection_reason);
        $this->assertFalse((bool) $user->is_active);

        // Vendor tries to login
        auth()->logout();
        $loginResponse = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $this->assertGuest();
        $errorModal = session('error_modal');
        $this->assertIsArray($errorModal);
        $this->assertEquals('rejected', $errorModal['type']);
        $this->assertStringContainsString('Nomor WhatsApp tidak aktif', $errorModal['message']);
    }

    public function test_custom_confirm_modal_rendered_in_layout()
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('globalConfirmModal');
        $response->assertSee('Apakah Anda yakin ingin menghapus ini?');
    }
}
