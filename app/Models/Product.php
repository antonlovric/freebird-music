<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'sleeve_condition',
        'media_condition',
        'sku',
        'price',
        'rating',
        'product_type_id',
        'number_of_ratings',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, "product_tags");
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, "product_genres");
    }

    public function media_condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function sleeve_condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
