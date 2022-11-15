<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PriceProduct
 *
 * @property int $id
 * @property string|null $price_type_id
 * @property int|null $product_id
 * @property float|null $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\PriceType|null $priceType
 * @property-read \App\Product|null $product
 */
class PriceProduct extends Model
{
    protected $guarded = [];

    public function priceType()
    {
        return $this->belongsTo('App\PriceType');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
