<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        $this->initMidtrans();
    }

    protected function initMidtrans(): void
    {
        Config::$serverKey = config('midtrans.server_key', env('MIDTRANS_SERVER_KEY', 'MIDTRANS_SERVER_KEY_ANDA'));
        Config::$clientKey = config('midtrans.client_key', env('MIDTRANS_CLIENT_KEY', 'MIDTRANS_SERVER_KEY_ANDA'));
        Config::$isProduction = (bool) config('midtrans.is_production', false);
        Config::$isSanitized = (bool) config('midtrans.is_sanitized', true);
        Config::$is3ds = (bool) config('midtrans.is_3ds', true);
    }

    /**
     * Create Snap Token for an Order
     */
    public function createSnapToken(Order $order): ?string
    {
        $this->initMidtrans();

        $grossAmount = (int) round($order->payable_amount);
        if ($grossAmount <= 0) {
            $grossAmount = 10000;
        }

        // Format items
        $itemName = $order->item_name;
        if ($order->payment_type === 'dp') {
            $itemName .= ' (DP ' . ($order->dp_percentage ?: 30) . '%)';
        } else {
            $itemName .= ' (Pelunasan Penuh)';
        }
        $itemName = mb_strimwidth($itemName, 0, 48, '...');

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_code . '-' . time(),
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name ?: ($order->customer->name ?? 'Customer'),
                'email' => $order->customer->email ?? 'customer@example.com',
                'phone' => $order->customer_phone ?: ($order->customer->phone ?? '08123456789'),
                'billing_address' => [
                    'address' => $order->event_location ?: 'Alamat Acara',
                ],
            ],
            'item_details' => [
                [
                    'id' => $order->bundling_id ? 'BND-' . $order->bundling_id : 'SRV-' . ($order->service_item_id ?: '1'),
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => $itemName,
                ],
            ],
            'callbacks' => [
                'finish' => route('customer.order.detail', $order->id),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);
            return $snapToken;
        } catch (Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'params' => $params,
            ]);
            return null;
        }
    }
}
