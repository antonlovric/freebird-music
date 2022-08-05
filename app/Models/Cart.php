<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'session_id',
        'active',
        'total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "session_id");
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, "cart_items");
    }

    public function orders()
    {
        return $this->belongsTo(Order::class, "cart_id");
    }
}
