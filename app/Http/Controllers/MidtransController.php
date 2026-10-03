<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Webhook Callback from Midtrans (CSRF Exempt)
     */
    public function callback(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook Callback Received: ', $payload);

        $rawOrderId = $payload['order_id'] ?? null;
        if (!$rawOrderId) {
            return response()->json(['message' => 'Order ID is missing'], 400);
        }

        // Clean order_code from timestamp suffix (e.g., ORD-YESI-123456-1727623940 -> ORD-YESI-123456)
        $orderCode = preg_replace('/-[0-9]{10,}$/', '', $rawOrderId);

        $order = Order::where('order_code', $orderCode)
            ->orWhere('order_code', $rawOrderId)
            ->first();

        if (!$order) {
            Log::warning('Midtrans Webhook: Order not found for ' . $rawOrderId);
            return response()->json(['message' => 'Order not found in database'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? '';
        $paymentType = $payload['payment_type'] ?? 'midtrans';
        $transactionId = $payload['transaction_id'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? $order->payable_amount;

        $order->midtrans_transaction_id = $transactionId;
        $order->midtrans_status = $transactionStatus;

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $order->status = 'paid';
                $this->recordPayment($order, $grossAmount, $paymentType, $transactionId, $payload, 'verified');
            } else {
                $order->status = 'pending';
            }
        } elseif ($transactionStatus === 'settlement') {
            $order->status = 'paid';
            $this->recordPayment($order, $grossAmount, $paymentType, $transactionId, $payload, 'verified');
        } elseif ($transactionStatus === 'pending') {
            $order->status = 'pending';
            $this->recordPayment($order, $grossAmount, $paymentType, $transactionId, $payload, 'pending_verification');
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $order->status = 'cancelled';
            $this->recordPayment($order, $grossAmount, $paymentType, $transactionId, $payload, 'rejected');
        }

        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Notification handled successfully',
            'order_code' => $order->order_code,
            'order_status' => $order->status,
        ]);
    }

    /**
     * Helper to create or update Payment record
     */
    protected function recordPayment(Order $order, $amount, string $paymentType, ?string $transactionId, array $rawPayload, string $status): void
    {
        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'amount' => (float) $amount,
                'payment_type' => $paymentType,
                'bank_name' => strtoupper($paymentType),
                'account_name' => $order->customer_name ?: ($order->customer->name ?? 'Customer Midtrans'),
                'payment_status' => $status,
                'transaction_id' => $transactionId,
                'midtrans_response' => json_encode($rawPayload),
                'verified_at' => $status === 'verified' ? now() : null,
            ]
        );
    }

    /**
     * Endpoint to obtain or regenerate Snap Token via AJAX for an order
     */
    public function getSnapToken($id): JsonResponse
    {
        $order = Order::with('customer')->findOrFail($id);

        // Security check: ensure user owns order or is admin
        if (auth()->check() && auth()->id() !== $order->customer_id && auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $snapToken = $this->midtransService->createSnapToken($order);

        if (!$snapToken) {
            return response()->json(['error' => 'Gagal membuat snap token'], 500);
        }

        return response()->json([
            'snap_token' => $snapToken,
            'order_code' => $order->order_code,
            'amount' => $order->payable_amount,
        ]);
    }
}
