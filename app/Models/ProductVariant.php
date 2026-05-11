<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Override;

#[Fillable(['product_id', 'name', 'price', 'compare_price', 'stock_quantity', 'manage_stock', 'stock_status', 'sku', 'options', 'is_active', 'sort_order'])]

class ProductVariant extends Model
{
    //
    protected $casts = [
        'manage_stock' => 'boolean',
        'is_active' => 'boolean',
        'options' => 'array',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    #[Scope()]
    public function active(Builder$query){
        return $query->where('is_active', true);
    }
    #[Scope()]
    protected function inStock(Builder$query){
        return $query->where('stock_status', 'in_stock')->where('stock_quantity', '>', 0);
    }

    //relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'variant_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    

    //helper
    public function getDiscountPercentageAttribute()
    {
        if ($this->compare_price > 0 && $this->price < $this->compare_price) {
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }

    #[Override]
    protected static function boot()
    {
        parent::boot();

        static::creating(function($variant){
            if (empty($variant->sku)) {
                # code...
                $variant->sku = 'VAR-' . strtoupper(uniqid());
            }
        });
    }

}
