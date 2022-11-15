<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Tag
 *
 * @property int $id
 * @property string $uuid
 * @property string $slug
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PersonalPrice[] $personalPrices
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 *
 * @mixin \Eloquent
 */
class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'uuid',
        'slug',
        'title'
    ];

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }

    public function personalPrices(): HasMany
    {
        return $this->hasMany(PersonalPrice::class);
    }
}
