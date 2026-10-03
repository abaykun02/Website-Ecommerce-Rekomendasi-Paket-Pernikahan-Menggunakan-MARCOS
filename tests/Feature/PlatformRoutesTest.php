<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class PlatformRoutesTest extends TestCase
{
    public function test_login_with_plain_credentials_works(): void
    {
        // 1. Admin login with username Admin and password 123
        $resp = $this->post('/login', [
            'email' => 'Admin',
            'password' => '123',
        ]);
        $resp->assertRedirect('/admin/dashboard');

        // 2. Vendor login with password 123
        $respVendor = $this->post('/login', [
            'email' => 'dyra@glowgrace.com',
            'password' => '123',
        ]);
        $respVendor->assertRedirect('/vendor/dashboard');

        // 3. Customer login with password 123
        $respCust = $this->post('/login', [
            'email' => 'customer@gmail.com',
            'password' => '123',
        ]);
        $respCust->assertRedirect('/');
    }

    public function test_registration_validates_unique_email_and_phone_and_min_8_chars(): void
    {
        // Duplicate email
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'customer@gmail.com',
            'password' => '123',
            'password_confirmation' => '123',
            'phone' => '08999999999',
        ])->assertSessionHasErrors('email');

        // Duplicate phone
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'newuser@gmail.com',
            'password' => '123',
            'password_confirmation' => '123',
            'phone' => '082167891234', // Sarah's phone
        ])->assertSessionHasErrors('phone');

        // Phone less than 8 chars
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'newuser2@gmail.com',
            'password' => '123',
            'password_confirmation' => '123',
            'phone' => '08123', // 5 chars
        ])->assertSessionHasErrors('phone');
    }

    public function test_public_pages_load_successfully(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/paket')->assertStatus(200);
        $this->get('/paket/1')->assertStatus(200);
        $this->get('/vendor')->assertStatus(200);
        $this->get('/vendor/1')->assertStatus(200);
        $this->get('/rekomendasi-spk')->assertStatus(200);
        $this->get('/login')->assertStatus(200);
        $this->get('/register')->assertStatus(200);
        $this->get('/register-vendor')->assertStatus(200);
    }

    public function test_customer_pages_load_successfully(): void
    {
        $customer = User::where('role', 'customer')->first();
        $this->actingAs($customer);

        $this->get('/pesanan-saya')->assertStatus(200);
        $this->get('/pesanan-saya/1')->assertStatus(200);
    }

    public function test_vendor_pages_load_successfully(): void
    {
        $vendorUser = User::where('role', 'vendor')->first();
        $this->actingAs($vendorUser);

        $this->get('/vendor/dashboard')->assertStatus(200);
        $this->get('/vendor/paket')->assertStatus(200);
        $this->get('/vendor/galeri')->assertStatus(200);
        $this->get('/vendor/pesanan')->assertStatus(200);
        $this->get('/vendor/profil')->assertStatus(200);
    }

    public function test_admin_pages_load_successfully(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $this->get('/admin/dashboard')->assertStatus(200);
        $this->get('/admin/kriteria')->assertStatus(200);
        $this->get('/admin/evaluasi')->assertStatus(200);
        $this->get('/admin/pesanan')->assertStatus(200);
        $this->get('/admin/spk-marcos')->assertStatus(200);
        $this->get('/admin/pengguna')->assertStatus(200);
    }
}
