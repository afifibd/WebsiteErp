<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $primaryKey = 'movement_id';
    protected $fillable = ['product_id','type','quantity','reference_id'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
