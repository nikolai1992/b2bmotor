<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\OrderModel;

/**
 * App\Product
 *
 * @property int $id
 * @property string $uuid
 * @property string $slug
 * @property int $brand_id
 * @property string|null $article
 * @property string $title
 * @property string $category_id
 * @property int $is_active
 * @property string $thumb
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PriceProduct[] $prices
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Store[] $stores
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tags
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Product sort(\Illuminate\Http\Request $request)
 *
 * @mixin \Eloquent
 */
class Product extends Model
{
    use OrderModel;

    protected $table = 'products';

//    protected $appends = [
//        'fact_price'
//    ];

    protected $fillable = [
        'uuid',
        'slug',
        'article',
        'title',
        'short_title',
        'is_active',
        'thumb',
        'category_id',
        'total_amount',
        'cat_page',
        'total_price',
        'images_request',
        'files_request'
    ];


    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function stores()
    {
        return $this->belongsToMany('App\Store')->withPivot('amount');
    }

    public function prices()
    {
        return $this->hasMany('App\PriceProduct');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Order')
            ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function files()
    {
        return $this->hasMany('App\FileProducts');
    }

    public function getTotalAmountAttribute()
    {
        return $this->stores()->sum('amount');
    }

    public function getFactPriceAttribute(User $user)
    {
        if ($this->prices->count()) {
            $retailPrice = $this->getRetailPrice();

            if ($this->tags->count()) {
                foreach ($this->tags as $tag) {
                    $personalPrice = PersonalPrice::where('user_id', $user->id)->where('tag_id', $tag->id)->first();
                    if ($personalPrice) {
                        $res = $this->prices->where('price_type_id', $personalPrice->price_type_id)->first();
                        if ($res) {
                            return $res;
                        }
                    }
                }
                return $retailPrice;
            } else {
                return $retailPrice;
            }
        } else {
            return 0;
        }
    }

    public function facticalPrice()
    {
        return $this->belongsTo('App\FacticalPrice', 'id', 'product_id');
    }

    public function getRetailPrice()
    {
        $prices = $this->prices();
        return $prices->whereHas('priceType', function ($query) {
            $query->where('title', "Розничная");
        })->first();
    }

    public function factPrice()
    {
        return $this->hasMany('App\FacticalPrice','product_id', 'id');
    }

    public function uploadingQueue()
    {
        return $this->belongsTo('App\WaitingForDownload','id', 'product_id');
    }

    public function findSmallestPrice()
    {
        if ($this->prices->count()) {
            return $this->prices->where('price','>',0)
                ->min('price');
        } else {
            return 0;
        }
    }
}
