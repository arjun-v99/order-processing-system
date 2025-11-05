<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessOrderJob;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderController extends Controller
{
    public function processOrder(Request $request, $orderId)
    {
        try {
            $order = Order::with('orderItems')->findOrFail($orderId);

            // no need to process completed or failed orders
            if (in_array($order->status, ['completed', 'failed'])) {
                return response()->json([
                    'message' => 'Order cannot be processed again.',
                ], 422);
            }

            // dispatch order job
            ProcessOrderJob::dispatch($order);


            return response()->json([
                'message' => 'Order processing started.',
                'order_id' => $order->id
            ]);
        } catch (Exception $e) {
            Log::error('Unable to login user. Error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
