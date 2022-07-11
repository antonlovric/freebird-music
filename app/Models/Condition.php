<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function media_conditions()
    {
        return $this->hasMany(Product::class, "media_condition", "id");
    }

    public function sleeve_conditions()
    {
        return $this->hasMany(Product::class, "sleeve_condition", "id");
    }
}
