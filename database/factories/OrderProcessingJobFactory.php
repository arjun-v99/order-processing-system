<?php

namespace Database\Factories;

use App\Models\OrderProcessingJob;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderProcessingJob>
 */
class OrderProcessingJobFactory extends Factory
{
    protected $model = OrderProcessingJob::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'order_id' => \App\Models\Order::factory(),
            'job_type' => $this->faker->randomElement([
                'payment_verification',
                'inventory_check',
                'shipping_trigger',
            ]),
            'status' => $this->faker->randomElement([
                'queued',
                'in_progress',
                'completed',
                'failed'
            ]),
            'started_at' => now(),
            'completed_at' => now()->addMinutes(rand(1, 10)),
            'error_message' => null,
        ];
    }
}
