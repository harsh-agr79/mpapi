<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_no',
        'password',
        'email_enc',
        'token_fp',
        'fp_at',
        'email_verified_at',
        'billing_full_name', 'billing_phone_number', 'billing_country_region', 
        'billing_city', 'billing_state', 'billing_email', 'billing_postal_code',
        'shipping_full_name', 'shipping_phone_number', 'shipping_country_region', 
        'shipping_city', 'shipping_state', 'shipping_email', 'shipping_postal_code',
        'billing_street_address',
        'billing_municipality',
        'billing_ordernote',
        'shipping_street_address',
        'shipping_municipality',
        'shipping_ordernote',
        'google_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'fp_at' => 'datetime',
    ];

    protected $appends = [
        'google_connected',
    ];
    
    public function getGoogleConnectedAttribute(): bool
    {
        return !is_null($this->google_id);
    }
    

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, 'customer_id'); // Ensure foreign key is correct
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

