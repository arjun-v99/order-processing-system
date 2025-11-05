<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Category;

class DashboardController extends Controller
{
    public function getOrdersSummmary()
    {
        try {
            $orders = Order::query()
                ->with(['user:id,name', 'orderItems.product.category'])
                ->withCount('orderItems')
                ->paginate(20);

            $orderStats = $orders->through(function ($order) {
                return [
                    'order_number' => $order->order_number,
                    'customer_name' => $order->user->name,
                    'total_amount' => $order->orderItems->sum(
                        fn($item) =>
                        $item->quantity * $item->unit_price
                    ),
                    'items_count' => $order->order_items_count,
                    'categories' => $order->orderItems
                        ->pluck('product.category.name')
                        ->unique()
                        ->values(),
                    'status' => $order->status,
                    'order_date' => $order->created_at->toDateString(),
                ];
            });

            $popularCategoryDetails = Category::query()
                ->withCount('orderItems')
                ->orderByDesc('order_items_count')
                // Since we are sorted by DESC order this will return the top category
                ->first();

            // average order amount value for all time
            $averageOrderValue = Order::avg('total_amount');

            // total for orders placed in the last 30 days:
            $totalOrderValue = Order::where('created_at', '>=', now()->subDays(30))
                ->sum('total_amount');

            // pending orders count
            $pendingOrdersCount = Order::where('status', '=', 'pending')->count();

            return response()->json([
                'orders' => $orderStats,
                'popular_category' => [
                    'name' => $popularCategoryDetails->name,
                    'count' => (int) $popularCategoryDetails->order_items_count,
                ],
                'average_order_value' => $averageOrderValue,
                'last_month_total_amount' => $totalOrderValue,
                'pending_orders_count' => $pendingOrdersCount
            ]);
        } catch (Exception $e) {
            Log::error('Unable to login user. Error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
