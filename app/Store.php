<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class Store extends Model
{
    protected $table = 'stores';

    protected $fillable = [
        'uuid',
        'slug',
        'title'
    ];

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }
}
