<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacticalPrice extends Model
{
    //
    protected $guarded = [];

    public function product_price()
    {
        return $this->belongsTo('App\PriceProduct', 'product_price_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
