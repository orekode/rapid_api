<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function categories() {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function properties() {
        return $this->hasMany(ProductProperty::class);
    }

    public function images() {
        return $this->hasMany(ProductImage::class);
    }
}
