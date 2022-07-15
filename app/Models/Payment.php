<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'paid_amount',
        'payment_type_id',
        'payment_secret',
    ];

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
