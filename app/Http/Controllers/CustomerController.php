<?php

namespace App\Http\Controllers;

use App\Models\Bundling;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;
use App\Models\ServiceItem;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $orderCode = 'ORD-YESI-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

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

        $orderCode = 'ORD-YESI-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

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

        // Generate Snap Token
        $this->midtransService->createSnapToken($order);

        return redirect()->route('customer.order.detail', $order->id)
            ->with('success', 'Pesanan layanan berhasil dibuat! Silakan lanjutkan pembayaran dengan Midtrans.');
    }

    public function orders()
    {
        $orders = Order::with(['bundling.galleries', 'serviceItem', 'vendor', 'payment', 'review'])
            ->where('customer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function orderDetail($id)
    {
        $order = Order::with(['bundling.galleries', 'serviceItem', 'vendor', 'payment', 'review'])
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
}
