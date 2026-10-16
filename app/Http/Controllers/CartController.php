<?php

namespace App\Http\Controllers;

use App\Models\Bundling;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ServiceItem;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Display Shopping Cart
     */
    public function index()
    {
        $userId = Auth::id();
        $cartItems = CartItem::with(['bundling.galleries', 'bundling.vendor', 'serviceItem.vendor', 'vendor'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        return view('customer.cart', compact('cartItems', 'subtotal'));
    }

    /**
     * Add Item to Cart (Bundling or Service)
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'bundling_id' => ['nullable', 'exists:bundlings,id'],
            'service_item_id' => ['nullable', 'exists:services_items,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'event_location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'event_date.required' => 'Silakan tentukan tanggal rencana acara/pernikahan Anda terlebih dahulu sebelum memasukkan ke keranjang.',
            'event_date.after_or_equal' => 'Tanggal rencana acara tidak boleh sebelum hari ini.',
        ]);

        if (empty($validated['bundling_id']) && empty($validated['service_item_id'])) {
            return back()->with('error', 'Pilih paket bundling atau layanan yang valid.');
        }

        $userId = Auth::id();
        $qty = (int) ($validated['quantity'] ?? 1);
        $eventDate = $validated['event_date'];
        $eventLocation = $validated['event_location'] ?? null;
        $notes = $validated['notes'] ?? null;

        if (!empty($validated['bundling_id'])) {
            $bundling = Bundling::findOrFail($validated['bundling_id']);
            
            $existing = CartItem::where('user_id', $userId)
                ->where('bundling_id', $bundling->id)
                ->where('event_date', $eventDate)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $qty);
                if (!empty($notes)) {
                    $existing->update([
                        'notes' => $notes,
                        'event_location' => $eventLocation ?: $existing->event_location,
                    ]);
                }
            } else {
                CartItem::create([
                    'user_id' => $userId,
                    'vendor_id' => $bundling->vendor_id,
                    'bundling_id' => $bundling->id,
                    'service_item_id' => null,
                    'item_type' => 'bundling',
                    'item_name' => $bundling->package_name,
                    'price' => $bundling->base_price,
                    'quantity' => $qty,
                    'event_date' => $eventDate,
                    'event_location' => $eventLocation,
                    'notes' => $notes,
                ]);
            }

            return redirect()->route('customer.cart')
                ->with('success', "Paket '{$bundling->package_name}' untuk tanggal {$eventDate} berhasil dimasukkan ke keranjang belanja!");
        }

        if (!empty($validated['service_item_id'])) {
            $service = ServiceItem::findOrFail($validated['service_item_id']);

            $existing = CartItem::where('user_id', $userId)
                ->where('service_item_id', $service->id)
                ->where('event_date', $eventDate)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $qty);
                if (!empty($notes)) {
                    $existing->update([
                        'notes' => $notes,
                        'event_location' => $eventLocation ?: $existing->event_location,
                    ]);
                }
            } else {
                CartItem::create([
                    'user_id' => $userId,
                    'vendor_id' => $service->vendor_id,
                    'bundling_id' => null,
                    'service_item_id' => $service->id,
                    'item_type' => 'service',
                    'item_name' => $service->name,
                    'price' => $service->price,
                    'quantity' => $qty,
                    'event_date' => $eventDate,
                    'event_location' => $eventLocation,
                    'notes' => $notes,
                ]);
            }

            return redirect()->route('customer.cart')
                ->with('success', "Layanan '{$service->name}' untuk tanggal {$eventDate} berhasil dimasukkan ke keranjang belanja!");
        }

        return back();
    }

    /**
     * Update Quantity in Cart
     */
    public function update(Request $request, $id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $cartItem->update([
            'quantity' => $validated['quantity'],
            'notes' => $validated['notes'] ?? $cartItem->notes,
        ]);

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    /**
     * Remove Item from Cart
     */
    public function remove($id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);
        $name = $cartItem->resolved_name;
        $cartItem->delete();

        return back()->with('success', "Item '{$name}' telah dihapus dari keranjang.");
    }

    /**
     * Clear Cart
     */
    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Seluruh isi keranjang belanja telah dikosongkan.');
    }

    /**
     * Checkout Page from Cart
     */
    public function checkout()
    {
        $userId = Auth::id();
        $user = Auth::user();
        $cartItems = CartItem::with(['bundling.galleries', 'bundling.vendor', 'serviceItem.vendor', 'vendor'])
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')
                ->with('error', 'Keranjang belanja Anda masih kosong. Silakan pilih paket atau layanan terlebih dahulu.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        // Determine vendor (use vendor from first item if single or multiple)
        $primaryVendor = $cartItems->first()->vendor ?? $cartItems->first()->bundling?->vendor ?? $cartItems->first()->serviceItem?->vendor;

        return view('customer.checkout', compact('cartItems', 'subtotal', 'user', 'primaryVendor'));
    }

    /**
     * Process Multi-Item Checkout
     */
    public function processCheckout(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();

        $cartItems = CartItem::with(['bundling', 'serviceItem', 'vendor'])
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')
                ->with('error', 'Keranjang belanja kosong.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'event_time' => ['nullable', 'string', 'max:50'],
            'event_location' => ['required', 'string', 'max:500'],
            'payment_type' => ['required', 'in:dp,full_payment'],
            'dp_percentage' => ['nullable', 'in:30,50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $subtotal = (float) $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        $paymentType = $validated['payment_type'];
        $dpPercent = (int) ($validated['dp_percentage'] ?? 30);
        $dpAmount = ($paymentType === 'dp') ? round(($subtotal * $dpPercent) / 100) : null;

        // Generate Order Code: WED-ymd-001
        $orderCode = Order::generateOrderCode();

        // Primary vendor fallback
        $primaryVendorId = $cartItems->first()->vendor_id 
            ?: ($cartItems->first()->bundling?->vendor_id 
            ?: ($cartItems->first()->serviceItem?->vendor_id ?: null));

        DB::beginTransaction();
        try {
            // First item bundling/service ID for backward compatibility
            $firstBundlingId = $cartItems->whereNotNull('bundling_id')->first()?->bundling_id;
            $firstServiceId = $cartItems->whereNotNull('service_item_id')->first()?->service_item_id;

            $order = Order::create([
                'order_code' => $orderCode,
                'customer_id' => $user->id,
                'customer_name' => $validated['customer_name'] ?: $user->name,
                'customer_phone' => $validated['customer_phone'] ?: ($user->phone ?? ''),
                'vendor_id' => $primaryVendorId,
                'bundling_id' => $firstBundlingId,
                'service_item_id' => $firstServiceId,
                'event_date' => $validated['event_date'],
                'event_time' => $validated['event_time'] ?: '08:00 WIB',
                'event_location' => $validated['event_location'],
                'total_price' => $subtotal,
                'payment_type' => $paymentType,
                'dp_amount' => $dpAmount,
                'dp_percentage' => $paymentType === 'dp' ? $dpPercent : null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);

            // Create Order Items
            foreach ($cartItems as $item) {
                $itemVendorId = $item->vendor_id 
                    ?: ($item->bundling?->vendor_id 
                    ?: ($item->serviceItem?->vendor_id ?: $primaryVendorId));

                OrderItem::create([
                    'order_id' => $order->id,
                    'vendor_id' => $itemVendorId,
                    'bundling_id' => $item->bundling_id,
                    'service_item_id' => $item->service_item_id,
                    'item_type' => $item->item_type,
                    'item_name' => $item->resolved_name,
                    'price' => $item->resolved_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                    'event_date' => $item->event_date ?: $order->event_date,
                    'event_location' => $item->event_location ?: $order->event_location,
                    'notes' => $item->notes,
                ]);
            }

            // Clear Cart
            CartItem::where('user_id', $userId)->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses checkout: ' . $e->getMessage())->withInput();
        }

        // Generate Snap Token
        $this->midtransService->createSnapToken($order);

        return redirect()->route('customer.order.detail', $order->id)
            ->with('success', "Pesanan #{$order->order_code} berhasil dibuat! Anda dapat langsung melakukan pembayaran melalui Midtrans.");
    }
}
