<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
#[Fillable(['product_id', 'customer_id', 'rating', 'order_id', 'comment', 'title', 'is_verified', 'is_approved'])]

class Review extends Model
{
    //
    use HasFactory;
    protected $casts = [
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
        'rating' => 'integer',
    ];

    #[Scope()]
    protected function approved(Builder $query)
    {
        return $query->where('is_approved', true);
    }

    #[Scope()]
    protected function verified(Builder $query)
    {        
        return $query->where('is_verified', true);
    }

    #[Scope()]
    protected function rating(Builder $query, int $rating)
    {        
        return $query->where('rating', $rating);
    }

    //relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
