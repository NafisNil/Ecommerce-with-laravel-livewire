<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['name', 'short_description', 'slug', 'description', 'price','compare_price','cost_price', 'stock_quantity','stock_threshold','manage_stock','stock_status', 'category_id', 'brand_id', 'sku', 'weight', 'status', 'meta_title', 'meta_description',  'is_featured', 'has_variants','is_active', 'view_count'])]
class Product extends Model
{
    //
    use SoftDeletes, HasFactory;
    protected $casts = [
        'manage_stock' => 'boolean',
        'is_featured' => 'boolean',
        'has_variants' => 'boolean',
        'is_active' => 'boolean',
        'view_count' => 'integer',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'stock_quantity' => 'integer',
        'stock_threshold' => 'integer',
    ];

    #[Scope()]
    public function active(Builder$query)
    {
        return $query->where('is_active', true);
    }

     #[Scope()]
     public function featured(Builder$query)
     {
         return $query->where('is_featured', true);
     }

     #[Scope()]
        public function inStock(Builder$query)
        {
            return $query->where(function ($q) {
                $q->where('manage_stock', false)
                  ->orWhere(function ($q2) {
                      $q2->where('manage_stock', true)
                         ->where('stock_quantity', '>', 0);
                  });
            });
        }
    
    #[Scope()]
    public function lowStock(Builder$query)
    {
        return $query->where('manage_stock', true)
                     ->whereColumn('stock_quantity', '<=', 'stock_threshold');
    }

    #[Scope()]
    protected function inPriceRange(Builder$query, float $min, float $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    #[Scope()]
    protected function inCategory(Builder$query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    #[Scope()]
    protected function inBrand(Builder$query, int $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    //relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

     public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    //helper methods
    public function getDiscountPercentageAttribute()
    {
        if ($this->compare_price > 0 && $this->price < $this->compare_price) {
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }

    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating');
    }

    public function getReviewCountAttribute()
    {
        return $this->approvedReviews()->count();
    }

    public function incrementViewCount()
    {
        $this->increment('view_count', 1);
    }

    //boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'SKU-' . strtoupper(Str::random(8));
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }


}
