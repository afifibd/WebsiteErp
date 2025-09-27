<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'address',
        'category',
        'rating',
        'orders_count',
        'total_purchased',
        'terms',
        'delivery_days',
        'status'
    ];

    // app/Models/Supplier.php
    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id');
    }
}
