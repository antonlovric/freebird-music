<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    use HasFactory;
    protected $fillable = [
        "filename",
        "url",
        "post_id",
        "is_display"
    ];

    public function posts() {
        return $this->belongsTo(Post::class, "post_id", "id");
    }
}
