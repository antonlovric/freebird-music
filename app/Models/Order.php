<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'total',
        'order_status_id',
        'billing_address',
        'shipping_address',
        'phone',
        'email',
        'shipping_city',
        'billing_city',
        'shipping_country',
        'billing_country',
        'shipping_zipcode',
        'billing_zipcode',
        'session_id',
        'cart_id',
    ];

    public function order_status()
    {
        return $this->belongsTo(OrderStatus::class, "order_status_id");
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, "cart_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
