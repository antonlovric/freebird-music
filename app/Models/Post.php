<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'heading',
        'subheading',
        'body',
        'user_id',
    ];

    public function images() {
        return $this->hasMany(PostImage::class, "post_id", "id");
    }

    public function user() {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
