<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Order;
use App\Models\OrderProcessingJob;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Facades\DB;


class ProcessOrderJob implements ShouldQueue
{
    use Queueable;

    protected $order;

    public $tries = 3;
    public $timeout = 60;
    // delay before retry.
    public $backoff = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startTime = now();

        $processingRecord = OrderProcessingJob::create([
            'order_id' => $this->order->id,
            'status' => 'in_progress',
            'started_at' => $startTime
        ]);

        try {
            DB::transaction(function () {
                // update order status to processing
                $this->order->update(['status' => 'processing']);

                $totalAmount = 0;

                foreach ($this->order->orderItems as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);

                    // validate product availability
                    if ($product->stock_quantity < $item->quantity) {
                        throw new \Exception("Insufficient stock for product: {$product->name}");
                    }

                    // update stock quantity
                    $product->decrement('stock_quantity', $item->quantity);

                    // calculate total
                    $totalAmount += $item->quantity * $product->price;
                }

                // update final amount
                $this->order->update([
                    'total_amount' => $totalAmount,
                    'status' => 'completed'
                ]);
            });

            // success
            $processingRecord->update([
                'status' => 'completed',
                'completed_at' => $startTime->diffInSeconds(now())
            ]);

            Log::info("Order {$this->order->id} processed successfully.");
        } catch (Throwable $e) {

            // failure
            $this->order->update(['order_status' => 'failed']);

            $processingRecord->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'processing_time' => $startTime->diffInSeconds(now())
            ]);

            Log::error("Failed to process order {$this->order->id}: {$e->getMessage()}");

            throw $e; // rethrow so job retries if configured
        }
    }

    public function failed(Throwable $exception)
    {
        // Update order status
        $this->order->update(['order_status' => 'failed']);

        // Log failure
        OrderProcessingJob::where('order_id', $this->order->id)
            ->latest()
            ->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage()
            ]);

        // Optional extra logging
        Log::error("Order {$this->order->id} failed after retries: {$exception->getMessage()}");
    }
}
