<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        'name', 'id', 'description','expire_date','price','category_id'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductsImage::class,'product_id');
    }
}
