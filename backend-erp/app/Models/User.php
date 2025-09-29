<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'user_id';
    protected $fillable = ['name','email','password','role','status'];
    protected $hidden = ['password'];

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
