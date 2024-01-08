<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function subs() {

        return $this->hasMany(Category::class, 'parent_id');
        
    }

    public function parent() {

        return $this->belongsTo(Category::class, 'parent_id');
        
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }
}
