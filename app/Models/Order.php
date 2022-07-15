<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'total',
        'order_details',
        'order_status',
    ];

    public function order_status()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function order_details()
    {
        return $this->belongsTo(OrderDetails::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, "cart_items");
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
