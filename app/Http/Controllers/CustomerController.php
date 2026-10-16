<?php

namespace App\Http\Controllers;

use App\Models\Bundling;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Review;
use App\Models\ServiceItem;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function createOrder(Request $request, $bundlingId)
    {
        $bundling = Bundling::with('vendor')->findOrFail($bundlingId);

        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:100'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'event_time' => ['nullable', 'string', 'max:50'],
            'event_location' => ['required', 'string', 'max:500'],
            'payment_type' => ['nullable', 'in:dp,full_payment'],
            'dp_percentage' => ['nullable', 'in:30,50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();
        $totalPrice = (float) $bundling->base_price;
        $paymentType = $validated['payment_type'] ?? 'full_payment';
        $dpPercent = (int) ($validated['dp_percentage'] ?? 30);
        $dpAmount = ($paymentType === 'dp') ? round(($totalPrice * $dpPercent) / 100) : null;

        // Generate concise order code without 'YESI': e.g. WED-261012-001
        $orderCode = Order::generateOrderCode();

        $order = Order::create([
            'order_code' => $orderCode,
            'customer_id' => $user->id,
            'customer_name' => $validated['customer_name'] ?: $user->name,
            'customer_phone' => $validated['customer_phone'] ?: ($user->phone ?? ''),
            'vendor_id' => $bundling->vendor_id,
            'bundling_id' => $bundling->id,
            'service_item_id' => null,
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'] ?: '08:00 WIB',
            'event_location' => $validated['event_location'],
            'total_price' => $totalPrice,
            'payment_type' => $paymentType,
            'dp_amount' => $dpAmount,
            'dp_percentage' => $paymentType === 'dp' ? $dpPercent : null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        // Create Order Item
        OrderItem::create([
            'order_id' => $order->id,
            'vendor_id' => $bundling->vendor_id,
            'bundling_id' => $bundling->id,
            'service_item_id' => null,
            'item_type' => 'bundling',
            'item_name' => $bundling->package_name,
            'price' => $totalPrice,
            'quantity' => 1,
            'subtotal' => $totalPrice,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Generate Snap Token
        $this->midtransService->createSnapToken($order);

        return redirect()->route('customer.order.detail', $order->id)
            ->with('success', 'Pesanan berhasil dibuat! Anda dapat langsung melakukan pembayaran melalui Midtrans.');
    }

    public function createServiceOrder(Request $request, $serviceItemId)
    {
        $service = ServiceItem::with('vendor')->findOrFail($serviceItemId);

        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:100'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'event_time' => ['nullable', 'string', 'max:50'],
            'event_location' => ['required', 'string', 'max:500'],
            'payment_type' => ['nullable', 'in:dp,full_payment'],
            'dp_percentage' => ['nullable', 'in:30,50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();
        $totalPrice = (float) $service->price;
        $paymentType = $validated['payment_type'] ?? 'full_payment';
        $dpPercent = (int) ($validated['dp_percentage'] ?? 30);
        $dpAmount = ($paymentType === 'dp') ? round(($totalPrice * $dpPercent) / 100) : null;

        // Generate concise order code without 'YESI': e.g. WED-261012-001
        $orderCode = Order::generateOrderCode();

        $order = Order::create([
            'order_code' => $orderCode,
            'customer_id' => $user->id,
            'customer_name' => $validated['customer_name'] ?: $user->name,
            'customer_phone' => $validated['customer_phone'] ?: ($user->phone ?? ''),
            'vendor_id' => $service->vendor_id,
            'bundling_id' => null,
            'service_item_id' => $service->id,
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'] ?: '08:00 WIB',
            'event_location' => $validated['event_location'],
            'total_price' => $totalPrice,
            'payment_type' => $paymentType,
            'dp_amount' => $dpAmount,
            'dp_percentage' => $paymentType === 'dp' ? $dpPercent : null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        // Create Order Item
        OrderItem::create([
            'order_id' => $order->id,
            'vendor_id' => $service->vendor_id,
            'bundling_id' => null,
            'service_item_id' => $service->id,
            'item_type' => 'service',
            'item_name' => $service->name,
            'price' => $totalPrice,
            'quantity' => 1,
            'subtotal' => $totalPrice,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Generate Snap Token
        $this->midtransService->createSnapToken($order);

        return redirect()->route('customer.order.detail', $order->id)
            ->with('success', 'Pesanan layanan berhasil dibuat! Silakan lanjutkan pembayaran dengan Midtrans.');
    }

    public function orders()
    {
        $orders = Order::with(['bundling.galleries', 'serviceItem', 'vendor', 'payment', 'review', 'items'])
            ->where('customer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function orderDetail($id)
    {
        $order = Order::with(['bundling.galleries', 'serviceItem', 'vendor', 'payment', 'review', 'items'])
            ->where('customer_id', Auth::id())
            ->findOrFail($id);

        // Generate Snap token if not present and order not yet paid
        if (!$order->snap_token && in_array($order->status, ['pending', 'approved'])) {
            $this->midtransService->createSnapToken($order);
            $order->refresh();
        }

        $midtransClientKey = config('midtrans.client_key', env('MIDTRANS_CLIENT_KEY'));
        $midtransIsProduction = config('midtrans.is_production', false);

        return view('customer.order-detail', compact('order', 'midtransClientKey', 'midtransIsProduction'));
    }

    public function uploadPayment(Request $request, $id)
    {
        $order = Order::where('customer_id', Auth::id())->findOrFail($id);

        if (!in_array($order->status, ['approved', 'pending'])) {
            return back()->with('error', 'Status pesanan tidak memungkinkan untuk upload pembayaran saat ini.');
        }

        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:50'],
            'account_name' => ['required', 'string', 'max:100'],
            'proof_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $path = $request->file('proof_image')->store('payments', 'public');

        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'bank_name' => $validated['bank_name'],
                'account_name' => $validated['account_name'],
                'proof_image' => $path,
                'payment_status' => 'pending_verification',
            ]
        );

        $order->update(['status' => 'paid']);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi admin.');
    }

    public function submitReview(Request $request, $id)
    {
        $order = Order::where('customer_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'completed') {
            return back()->with('error', 'Review hanya dapat diberikan untuk pesanan yang telah selesai.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        Review::updateOrCreate(
            ['order_id' => $order->id],
            [
                'customer_id' => Auth::id(),
                'vendor_id' => $order->vendor_id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]
        );

        return back()->with('success', 'Terima kasih atas ulasan dan rating yang Anda berikan!');
    }

    /**
     * Customer Profile Page (Requirement 4)
     */
    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    /**
     * Update Customer Profile & Password (Requirement 4)
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        // If user wants to change password
        if (!empty($validated['password'])) {
            if (empty($validated['current_password'])) {
                return back()->withErrors(['current_password' => 'Masukkan kata sandi lama Anda untuk konfirmasi perubahan kata sandi.'])->withInput();
            }

            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.'])->withInput();
            }

            $user->password = Hash::make($validated['password']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'];
        $user->save();

        return back()->with('success', 'Data profil Anda berhasil diperbarui!');
    }
}
