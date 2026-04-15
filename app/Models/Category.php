<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug', 'description', 'image', 'is_active', 'meta_title', 'meta_description', 'sort_order'])]
class Category extends Model
{
    //
}
