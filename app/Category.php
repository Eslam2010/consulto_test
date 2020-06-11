<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = [
        'name', 'id', 'description','image',
    ];
    public function products()
    {
        return $this->hasMany(Product::class,'category_id');
    }
}
