<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'city',
        'country',
        'zipcode',
        'remember_token',
        'user_type_id',
        'session_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function user_type()
    {
        return $this->belongsTo(UserType::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, "session_id", "session_id");
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function orders() 
    {
        return $this->hasMany(Order::class, "user_id", "id");
    }

    public function open_requests()
    {
        return $this->hasMany(OpenRequest::class);
    }

    public function reviews()
    {
        return $this->belongsToMany(Product::class, "product_reviews");
    }
}
