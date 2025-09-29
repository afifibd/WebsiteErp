<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';
    protected $fillable = ['name', 'sku', 'category', 'supplier_id', 'stock', 'cost', 'price', 'status', 'margin'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class, 'product_id', 'product_id');
    }
}
