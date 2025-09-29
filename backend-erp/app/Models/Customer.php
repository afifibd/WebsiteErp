<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'customer_id';
    protected $fillable = ['name','email','phone','address','type','credit_limit','status'];

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
