<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'description', 'logo', 'is_active', 'website', 'sort_order'])]
class Brand extends Model
{
    //

    #[Scope()]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    #[Scope()]
    protected function sortOrder(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });

        static::updating(function ($brand) {
            if ($brand->isDirty('name') && empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }
}
