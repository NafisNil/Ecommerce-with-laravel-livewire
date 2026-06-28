<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['name', 'email', 'password', 'phone', 'date_of_birth', 'gender', 'is_active', 'email_verified_at', 'remember_token'])]
class Customer extends Model
{
    use HasFactory;
    //
    protected $hidden = [
        'password', 'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'date_of_birth' => 'date',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    #[Scope()]
    public static function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function getTotalSpentAttribute()
    {
        return $this->orders()->where('payment_status', 'completed')->sum('total_amount');
    }

    public function getTotalOrdersAttribute()
    {
        return $this->orders()->count();
    }
}
