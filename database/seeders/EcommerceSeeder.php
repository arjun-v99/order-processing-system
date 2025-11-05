<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EcommerceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create some categories
        $categories = \App\Models\Category::factory(5)->create();

        // Create products for created categories
        $products = \App\Models\Product::factory(50)->create([
            'category_id' => $categories->random()->id,
        ]);

        // Create users with related orders + order items + processing jobs
        \App\Models\User::factory(20)
            ->has(
                \App\Models\Order::factory(3)
                    ->has(\App\Models\OrderItem::factory(3))
                    ->has(\App\Models\OrderProcessingJob::factory(2), 'processingJobs')
            )
            ->create();
    }
}
