<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PriceProduct;

class PriceType extends Model
{

    protected $table = 'price_types';

    protected $fillable = [
        'uuid',
        'title'
    ];

    public $timestamps = false;

    public function priceProduct()
    {
        return $this->belongsTo('App\PriceProduct');
    }
}
