<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class ProductReviews extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "product_id",
        "rating",
        "review"
    ];
}
