<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $fillable = [
        'category_1c_id',
        'product_1c_id',
    ];

    public $timestamps = false;
}
