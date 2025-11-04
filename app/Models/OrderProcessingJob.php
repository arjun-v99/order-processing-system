<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProcessingJob extends Model
{
    protected $fillable = [
        'order_id',
        'job_type',
        'status',
        'started_at',
        'completed_at',
        'error_message',
        'job_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
