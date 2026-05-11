<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
#[Fillable(['product_id', 'image_path', 'is_primary', 'alt_text', 'sort_order', 'variant_id'])]
class ProductImage extends Model
{
    //
    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    #[Scope()]
    public function primary(Builder$query){
        return $query->where('is_primary', true);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    //helper
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
