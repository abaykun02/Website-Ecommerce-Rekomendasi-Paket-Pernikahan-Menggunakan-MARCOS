<?php

namespace App\Http\Controllers;

use App\Models\Bundling;
use App\Models\Criteria;
use App\Models\Gallery;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    protected function getVendor(): Vendor
    {
        $vendor = Auth::user()->vendor;
        if (!$vendor) {
            abort(404, 'Data vendor tidak ditemukan untuk akun ini.');
        }
        return $vendor;
    }

    public function dashboard()
    {
        $vendor = $this->getVendor();

        $stats = [
            'total_bundlings' => $vendor->bundlings()->count(),
            'total_orders' => $vendor->orders()->count(),
            'pending_orders' => $vendor->orders()->where('status', 'pending')->count(),
            'completed_orders' => $vendor->orders()->where('status', 'completed')->count(),
            'avg_rating' => $vendor->averageRating(),
            'total_revenue' => $vendor->orders()->whereIn('status', ['paid', 'completed'])->sum('total_price'),
        ];

        // Chart 1: Distribusi Status Pesanan Toko Vendor (Donut)
        $statusCounts = [
            'pending' => $vendor->orders()->where('status', 'pending')->count(),
            'paid' => $vendor->orders()->where('status', 'paid')->count(),
            'completed' => $vendor->orders()->where('status', 'completed')->count(),
            'cancelled' => $vendor->orders()->where('status', 'cancelled')->count(),
        ];

        // Chart 2: Komparasi Tipe Pembayaran (DP vs Lunas)
        $paymentTypeStats = [
            'full_payment' => $vendor->orders()->where('payment_type', 'full_payment')->count(),
            'dp' => $vendor->orders()->where('payment_type', 'dp')->count(),
        ];

        $recentOrders = $vendor->orders()
            ->with(['customer', 'bundling'])
            ->latest()
            ->take(5)
            ->get();

        return view('vendor.dashboard', compact('vendor', 'stats', 'recentOrders', 'statusCounts', 'paymentTypeStats'));
    }

    public function profile()
    {
        $vendor = $this->getVendor();
        return view('vendor.profile', compact('vendor'));
    }

    public function updateProfile(Request $request)
    {
        $vendor = $this->getVendor();

        $validated = $request->validate([
            'vendor_name' => ['required', 'string', 'max:100'],
            'owner_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'location_address' => ['required', 'string'],
            'google_maps_url' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'remove_profile_image' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:6144'],
            'remove_cover_image' => ['nullable', 'boolean'],
            'cropped_profile_image' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string', 'max:50'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_account_name' => ['nullable', 'string', 'max:100'],
            'bank_notes' => ['nullable', 'string', 'max:500'],
        ]);

        // 1. Kelola Foto Profil
        if ($request->boolean('remove_profile_image')) {
            if ($vendor->profile_image && Storage::disk('public')->exists($vendor->profile_image)) {
                Storage::disk('public')->delete($vendor->profile_image);
            }
            $validated['profile_image'] = null;
        } elseif ($request->hasFile('profile_image')) {
            if ($vendor->profile_image && Storage::disk('public')->exists($vendor->profile_image)) {
                Storage::disk('public')->delete($vendor->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('vendor_profiles', 'public');
        } elseif ($request->filled('cropped_profile_image')) {
            $newAvatar = $this->saveUploadedOrCroppedImage($request, 'vendor_profiles', 'cropped_profile_image', 'profile_image');
            if ($newAvatar) {
                if ($vendor->profile_image && Storage::disk('public')->exists($vendor->profile_image)) {
                    Storage::disk('public')->delete($vendor->profile_image);
                }
                $validated['profile_image'] = $newAvatar;
            }
        }

        // 2. Kelola Foto Sampul (Cover Banner)
        if ($request->boolean('remove_cover_image')) {
            if ($vendor->cover_image && Storage::disk('public')->exists($vendor->cover_image)) {
                Storage::disk('public')->delete($vendor->cover_image);
            }
            $validated['cover_image'] = null;
        } elseif ($request->hasFile('cover_image')) {
            if ($vendor->cover_image && Storage::disk('public')->exists($vendor->cover_image)) {
                Storage::disk('public')->delete($vendor->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('vendor_covers', 'public');
        }

        unset($validated['remove_profile_image'], $validated['remove_cover_image'], $validated['cropped_profile_image']);

        $vendor->update($validated);

        return back()->with('success', 'Profil toko, foto profil, foto sampul, dan informasi rekening penerima pembayaran berhasil diperbarui.');
    }

    public function bundlings()
    {
        $vendor = $this->getVendor();
        $bundlings = $vendor->bundlings()->with('galleries')->latest()->paginate(10);
        $criterias = Criteria::orderBy('code')->get();

        return view('vendor.bundlings', compact('vendor', 'bundlings', 'criterias'));
    }

    public function storeBundling(Request $request)
    {
        $vendor = $this->getVendor();

        $validated = $request->validate([
            'package_name' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'base_price' => ['required', 'numeric', 'min:100000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'cropped_image' => ['nullable', 'string'],
        ]);

        $bundling = Bundling::create([
            'vendor_id' => $vendor->id,
            'package_name' => $validated['package_name'],
            'description' => $validated['description'],
            'base_price' => $validated['base_price'],
            'is_active' => true,
        ]);

        $path = $this->saveUploadedOrCroppedImage($request, 'bundlings');
        if ($path) {
            Gallery::create([
                'vendor_id' => $vendor->id,
                'bundling_id' => $bundling->id,
                'image_path' => $path,
                'caption' => 'Foto ' . $bundling->package_name,
            ]);
        }

        return back()->with('success', 'Paket bundling berhasil ditambahkan!');
    }

    public function updateBundling(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $bundling = $vendor->bundlings()->findOrFail($id);

        $validated = $request->validate([
            'package_name' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'base_price' => ['required', 'numeric', 'min:100000'],
            'is_active' => ['required', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'cropped_image' => ['nullable', 'string'],
        ]);

        $bundling->update([
            'package_name' => $validated['package_name'],
            'description' => $validated['description'],
            'base_price' => $validated['base_price'],
            'is_active' => $validated['is_active'],
        ]);

        $path = $this->saveUploadedOrCroppedImage($request, 'bundlings');
        if ($path) {
            $existingGallery = $bundling->galleries()->first();
            if ($existingGallery) {
                if ($existingGallery->image_path && Storage::disk('public')->exists($existingGallery->image_path)) {
                    Storage::disk('public')->delete($existingGallery->image_path);
                }
                $existingGallery->update([
                    'image_path' => $path,
                    'caption' => 'Foto ' . $bundling->package_name,
                ]);
            } else {
                Gallery::create([
                    'vendor_id' => $vendor->id,
                    'bundling_id' => $bundling->id,
                    'image_path' => $path,
                    'caption' => 'Foto ' . $bundling->package_name,
                ]);
            }
        }

        return back()->with('success', 'Paket bundling berhasil diperbarui.');
    }

    public function deleteBundling($id)
    {
        $vendor = $this->getVendor();
        $bundling = $vendor->bundlings()->findOrFail($id);
        $bundling->delete();

        return back()->with('success', 'Paket bundling berhasil dihapus.');
    }

    public function galleries()
    {
        $vendor = $this->getVendor();
        $galleries = $vendor->galleries()->with('bundling')->latest()->paginate(12);
        $bundlings = $vendor->bundlings;

        return view('vendor.galleries', compact('vendor', 'galleries', 'bundlings'));
    }

    public function storeGallery(Request $request)
    {
        $vendor = $this->getVendor();

        $validated = $request->validate([
            'bundling_id' => ['nullable', 'exists:bundlings,id'],
            'caption' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'cropped_image' => ['nullable', 'string'],
        ]);

        $imagePath = $this->saveUploadedOrCroppedImage($request, 'galleries');
        if (!$imagePath && !empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        if (empty($imagePath)) {
            return back()->with('error', 'Harap unggah berkas foto atau masukkan tautan URL gambar.');
        }

        Gallery::create([
            'vendor_id' => $vendor->id,
            'bundling_id' => $validated['bundling_id'] ?? null,
            'image_path' => $imagePath,
            'caption' => $validated['caption'] ?? 'Galeri ' . $vendor->vendor_name,
        ]);

        return back()->with('success', 'Foto galeri berhasil ditambahkan!');
    }

    /**
     * Simpan file gambar yang diunggah baik melalui cropping Base64 maupun file stream multipart normal.
     */
    private function saveUploadedOrCroppedImage(Request $request, string $folder = 'bundlings', string $croppedInput = 'cropped_image', string $fileInput = 'image'): ?string
    {
        // 1. Cek jika pengguna menggunakan fitur crop / cut gambar (data URL base64)
        if ($request->filled($croppedInput) && str_starts_with($request->input($croppedInput), 'data:image/')) {
            $dataUrl = $request->input($croppedInput);
            if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $matches)) {
                $ext = strtolower($matches[1]);
                if ($ext === 'jpeg') {
                    $ext = 'jpg';
                }
                $imageData = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1));
                if ($imageData !== false) {
                    $fileName = $folder . '/' . uniqid('crop_', true) . '.' . $ext;
                    Storage::disk('public')->put($fileName, $imageData);
                    return $fileName;
                }
            }
        }

        // 2. Fallback jika unggah file biasa tanpa potong
        if ($request->hasFile($fileInput)) {
            return $request->file($fileInput)->store($folder, 'public');
        }

        return null;
    }

    public function deleteGallery($id)
    {
        $vendor = $this->getVendor();
        $gallery = $vendor->galleries()->findOrFail($id);
        $gallery->delete();

        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }

    public function orders()
    {
        $vendor = $this->getVendor();
        $orders = $vendor->orders()
            ->with(['customer', 'bundling', 'payment', 'review', 'items'])
            ->whereIn('status', ['pending', 'approved', 'paid'])
            ->latest()
            ->paginate(10);

        $activeCount = $vendor->orders()->whereIn('status', ['pending', 'approved', 'paid'])->count();
        $historyCount = $vendor->orders()->whereIn('status', ['completed', 'cancelled'])->count();

        return view('vendor.orders', compact('vendor', 'orders', 'activeCount', 'historyCount'));
    }

    public function ordersHistory()
    {
        $vendor = $this->getVendor();
        $orders = $vendor->orders()
            ->with(['customer', 'bundling', 'payment', 'review', 'items'])
            ->whereIn('status', ['completed', 'cancelled'])
            ->latest()
            ->paginate(10);

        $activeCount = $vendor->orders()->whereIn('status', ['pending', 'approved', 'paid'])->count();
        $historyCount = $vendor->orders()->whereIn('status', ['completed', 'cancelled'])->count();

        return view('vendor.orders-history', compact('vendor', 'orders', 'activeCount', 'historyCount'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $order = $vendor->orders()->findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:approved,completed,cancelled'],
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Status pesanan ' . $order->order_code . ' berhasil diperbarui ke: ' . $order->status_label);
    }
}
