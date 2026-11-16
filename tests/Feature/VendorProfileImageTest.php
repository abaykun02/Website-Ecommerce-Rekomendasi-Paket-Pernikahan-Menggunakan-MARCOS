<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VendorProfileImageTest extends TestCase
{
    public function test_vendor_can_upload_profile_image_directly()
    {
        Storage::fake('public');

        $vendorUser = User::where('role', 'vendor')->first();
        $this->actingAs($vendorUser);

        $file = UploadedFile::fake()->create('profile.jpg', 100, 'image/jpeg');

        $response = $this->post(route('vendor.profile.update'), [
            'vendor_name' => $vendorUser->vendor->vendor_name,
            'owner_name' => $vendorUser->vendor->owner_name,
            'phone' => '08123456789',
            'location_address' => 'Jl. Pengantin No. 1 Medan',
            'profile_image' => $file,
        ]);

        $response->assertSessionHas('success');

        $vendorUser->vendor->refresh();
        $this->assertNotNull($vendorUser->vendor->profile_image);
        $this->assertTrue($vendorUser->vendor->hasAvatar());
        Storage::disk('public')->assertExists($vendorUser->vendor->profile_image);
        $this->assertStringContainsString('storage/', $vendorUser->vendor->avatar_url);
    }

    public function test_vendor_can_remove_profile_image()
    {
        Storage::fake('public');

        $vendorUser = User::where('role', 'vendor')->first();
        $this->actingAs($vendorUser);

        // Pasang foto awal
        $vendorUser->vendor->update(['profile_image' => 'vendor_profiles/dummy.jpg']);
        $this->assertTrue($vendorUser->vendor->hasAvatar());

        $response = $this->post(route('vendor.profile.update'), [
            'vendor_name' => $vendorUser->vendor->vendor_name,
            'owner_name' => $vendorUser->vendor->owner_name,
            'phone' => '08123456789',
            'location_address' => 'Jl. Pengantin No. 1 Medan',
            'remove_profile_image' => 1,
        ]);

        $response->assertSessionHas('success');

        $vendorUser->vendor->refresh();
        $this->assertNull($vendorUser->vendor->profile_image);
        $this->assertFalse($vendorUser->vendor->hasAvatar());
        $this->assertStringStartsWith('data:image/svg+xml', $vendorUser->vendor->avatar_url);
    }

    public function test_vendor_can_upload_cover_image_directly()
    {
        Storage::fake('public');

        $vendorUser = User::where('role', 'vendor')->first();
        $this->actingAs($vendorUser);

        $coverFile = UploadedFile::fake()->create('wedding_cover.jpg', 200, 'image/jpeg');

        $response = $this->post(route('vendor.profile.update'), [
            'vendor_name' => $vendorUser->vendor->vendor_name,
            'owner_name' => $vendorUser->vendor->owner_name,
            'phone' => '08123456789',
            'location_address' => 'Jl. Pengantin No. 1 Medan',
            'cover_image' => $coverFile,
        ]);

        $response->assertSessionHas('success');

        $vendorUser->vendor->refresh();
        $this->assertNotNull($vendorUser->vendor->cover_image);
        $this->assertTrue($vendorUser->vendor->hasCover());
        Storage::disk('public')->assertExists($vendorUser->vendor->cover_image);
        $this->assertStringContainsString('storage/', $vendorUser->vendor->cover_url);
    }

    public function test_vendor_can_remove_cover_image()
    {
        Storage::fake('public');

        $vendorUser = User::where('role', 'vendor')->first();
        $this->actingAs($vendorUser);

        // Pasang cover awal
        $vendorUser->vendor->update(['cover_image' => 'vendor_covers/dummy_cover.jpg']);
        $this->assertTrue($vendorUser->vendor->hasCover());

        $response = $this->post(route('vendor.profile.update'), [
            'vendor_name' => $vendorUser->vendor->vendor_name,
            'owner_name' => $vendorUser->vendor->owner_name,
            'phone' => '08123456789',
            'location_address' => 'Jl. Pengantin No. 1 Medan',
            'remove_cover_image' => 1,
        ]);

        $response->assertSessionHas('success');

        $vendorUser->vendor->refresh();
        $this->assertNull($vendorUser->vendor->cover_image);
        $this->assertFalse($vendorUser->vendor->hasCover());
        $this->assertStringContainsString('images.unsplash.com', $vendorUser->vendor->cover_url);
    }

    public function test_vendor_avatar_and_cover_appear_in_customer_directory_and_detail()
    {
        $vendor = Vendor::where('status', 'approved')->first();
        $vendor->update([
            'profile_image' => 'vendor_profiles/test_vendor_avatar.png',
            'cover_image' => 'vendor_covers/test_vendor_cover.png',
        ]);

        // 1. Direktori Vendor Pelanggan (/vendor)
        $response = $this->get(route('vendors', ['search' => $vendor->vendor_name]));
        $response->assertStatus(200);
        $response->assertSee('test_vendor_avatar.png');
        $response->assertSee('test_vendor_cover.png');

        // 2. Detail Toko Vendor Pelanggan (/vendor/{id})
        $responseDetail = $this->get(route('vendor.detail', $vendor->id));
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('test_vendor_avatar.png');
        $responseDetail->assertSee('test_vendor_cover.png');
    }

    public function test_vendor_avatar_appears_in_admin_directory()
    {
        $admin = User::where('role', 'admin')->first();
        $vendor = Vendor::first();
        $vendor->update(['profile_image' => 'vendor_profiles/test_admin_view_avatar.png']);

        $response = $this->actingAs($admin)->get(route('admin.vendors', ['search' => $vendor->vendor_name]));
        $response->assertStatus(200);
        $response->assertSee('test_admin_view_avatar.png');
    }

    public function test_vendor_avatar_and_cover_appear_in_vendor_dashboard_and_profile()
    {
        $vendorUser = User::where('role', 'vendor')->first();
        $vendorUser->vendor->update([
            'profile_image' => 'vendor_profiles/test_vendor_dashboard_avatar.png',
            'cover_image' => 'vendor_covers/test_vendor_dashboard_cover.png',
        ]);
        $vendorUser->load('vendor');

        // Vendor Dashboard
        $responseDash = $this->actingAs($vendorUser)->get(route('vendor.dashboard'));
        $responseDash->assertStatus(200);
        $responseDash->assertSee('test_vendor_dashboard_avatar.png');
        $responseDash->assertSee('test_vendor_dashboard_cover.png');

        // Vendor Profile
        $responseProf = $this->actingAs($vendorUser)->get(route('vendor.profile'));
        $responseProf->assertStatus(200);
        $responseProf->assertSee('test_vendor_dashboard_avatar.png');
        $responseProf->assertSee('test_vendor_dashboard_cover.png');
        $responseProf->assertSee('Foto Sampul Toko (Banner)');
    }
}
