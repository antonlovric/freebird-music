<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "product_id",
        "cart_id",
        "order_id",
        "quantity",
        "price",
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
