<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsImage extends Model
{
    protected $fillable = [
        'name', 'product_id', 'image',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
