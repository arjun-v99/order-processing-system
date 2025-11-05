<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // A Category has many OrderItems through the Product model
    public function orderItems()
    {
        return $this->hasManyThrough(
            OrderItem::class,
            Product::class,
            'category_id',
            'product_id'
        );
    }
}
