<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $primaryKey = 'supplier_id';
    protected $fillable = ['name','email','phone','address','category','payment_terms','status'];

    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id', 'supplier_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'supplier_id', 'supplier_id');
    }
}

