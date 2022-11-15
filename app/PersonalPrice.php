<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PersonalPrice
 *
 * @property int $id
 * @property string|null $uuid
 * @property int|null $tag_id
 * @property int|null $user_id
 * @property int|null $price_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\PriceType|null $priceType
 * @property-read \App\Tag|null $tag
 * @property-read \App\User|null $user
 */
class PersonalPrice extends Model
{
    protected $table = 'personal_prices';

    protected $fillable = [
        'uuid'
    ];


    public function priceType()
    {
        return $this->belongsTo('App\PriceType');
    }

    public function tag()
    {
        return $this->belongsTo('App\Tag');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
