<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'order_number' => strtoupper('ORD-' . $this->faker->unique()->bothify('########')),
            'total_amount' => $this->faker->randomFloat(2, 5, 500),
            'status' => 'pending',
            'processed_at' => Carbon::now()
        ];
    }
}
