<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'external_id',
        'name',
        'description',
        'price',
        'category_id',
        'quantity'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
