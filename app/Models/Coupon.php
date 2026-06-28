<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Casts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
#[Fillable(['code', 'type', 'value', 'min_order_amount', 'max_discount_amount', 'usage_limit', 'usage_limit_per_user', 'is_active', 'start_at', 'end_at'])]

class Coupon extends Model
{
    //
    use HasFactory;
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'max_discount_amount' => 'decimal:2',
            'usage_limit' => 'integer',
            'usage_limit_per_user' => 'integer',
        ];
    }

    #[Scope()]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    #[Scope()]
    protected function valid(Builder $query): Builder
    {
        $now = Carbon::now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
        });
    }


    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isValid(){
        if(!$this->is_active){
            return false;
        }

        if ($this->start_at && $this->start_at->isFuture()) {
            return false;
        }

        if ($this->end_at && $this->end_at->isPast()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->usages()->count() >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function canBeUsedByUser($userId)
    {
        if ($this->usage_limit_per_user === null) {
            return true;
        }

        if ($this->usage_limit_per_user <= 0) {
            return false;
        }

        if (!$this->isValid()) {
            return false;
        }

        $userUsageCount = $this->usages()->where('user_id', $userId)->count();
        if ($userUsageCount >= $this->usage_limit_per_user) {
            return false;
        }
        return true;
    }

    public function calculateDiscount($orderAmount)
    {
        if($this->min_order_amount !== null && $orderAmount < $this->min_order_amount){
            return 0;
        }
        if ($this->type === 'fixed') {
            return min($this->value, $orderAmount);
        } elseif ($this->type === 'percent') {
            $discount = ($this->value / 100) * $orderAmount;
            if ($this->max_discount_amount !== null) {
                return min($discount, $this->max_discount_amount);
            }
            return $discount;
        }
        return 0;
    }
}
