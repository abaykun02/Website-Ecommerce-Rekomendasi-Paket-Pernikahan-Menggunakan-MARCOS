<?php

namespace App\Http\Controllers;

use App\Models\Bundling;
use App\Models\Criteria;
use App\Models\Gallery;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);

        $vendor->update($validated);

        return back()->with('success', 'Profil vendor berhasil diperbarui.');
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
        ]);

        $bundling = Bundling::create([
            'vendor_id' => $vendor->id,
            'package_name' => $validated['package_name'],
            'description' => $validated['description'],
            'base_price' => $validated['base_price'],
            'is_active' => true,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('bundlings', 'public');
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
        ]);

        $bundling->update([
            'package_name' => $validated['package_name'],
            'description' => $validated['description'],
            'base_price' => $validated['base_price'],
            'is_active' => $validated['is_active'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('bundlings', 'public');
            Gallery::create([
                'vendor_id' => $vendor->id,
                'bundling_id' => $bundling->id,
                'image_path' => $path,
                'caption' => 'Foto ' . $bundling->package_name,
            ]);
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
        ]);

        if (!$request->hasFile('image') && empty($validated['image_url'])) {
            return back()->with('error', 'Harap unggah berkas foto atau masukkan tautan URL gambar.');
        }

        $imagePath = $validated['image_url'] ?? '';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('galleries', 'public');
        }

        Gallery::create([
            'vendor_id' => $vendor->id,
            'bundling_id' => $validated['bundling_id'] ?? null,
            'image_path' => $imagePath,
            'caption' => $validated['caption'] ?? 'Galeri ' . $vendor->vendor_name,
        ]);

        return back()->with('success', 'Foto galeri berhasil ditambahkan!');
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
            ->with(['customer', 'bundling', 'payment', 'review'])
            ->latest()
            ->paginate(10);

        return view('vendor.orders', compact('vendor', 'orders'));
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
