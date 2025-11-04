<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_processing_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Example job types, can modify later:
            $table->enum('job_type', [
                'payment_verification',
                'inventory_check',
                'shipping_trigger',
            ]);

            $table->enum('status', ['queued', 'in_progress', 'completed', 'failed'])
                ->default('queued')
                ->index();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->text('error_message')->nullable();

            // Optional mapping to Laravel queued job
            $table->unsignedBigInteger('job_id')->nullable()->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_processing_jobs');
    }
};
