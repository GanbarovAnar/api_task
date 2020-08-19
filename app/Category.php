<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'external_id',
        'name'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
