<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable(['name', 'slug', 'description', 'image', 'is_active', 'meta_title', 'meta_description', 'sort_order'])]
class Category extends Model
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

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }


}
