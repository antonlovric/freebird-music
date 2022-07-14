<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'request',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
