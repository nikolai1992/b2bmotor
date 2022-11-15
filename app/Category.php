<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * App\Category
 *
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string $slug
 * @property string $path
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Kalnoy\Nestedset\Collection|Category[] $children
 * @property-read Category|null $parent
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 *
 * @method static \Kalnoy\Nestedset\Collection|static[] all($columns = ['*'])
 * @method static \Kalnoy\Nestedset\Collection|static[] get($columns = ['*'])
 */
class Category extends Model
{
    protected $table = 'categories';
    protected $guarded = [];

    protected $fillable = [
        'uuid',
        'parent_id',
        'slug',
        'title'
    ];

    use NodeTrait;

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    // Генерация пути
    public function generatePath()
    {
        $slug = $this->slug;

        $this->path = $this->isRoot() ? $slug : $this->parent->path.'/'.$slug;

        return $this;
    }

    public function updateDescendantsPaths()
    {
        // Получаем всех потомков в древовидном порядке
        $descendants = $this->descendants()->defaultOrder()->get();

        // Данный метод заполняет отношения parent и children
        $descendants->push($this)->linkNodes()->pop();

        foreach ($descendants as $model) {
            $model->generatePath()->save();
        }
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function (self $model) {
            if ($model->isDirty('slug', 'parent_id')) {
                $model->generatePath();
            }
        });

        static::saved(function (self $model) {
            // Данная переменная нужна для того, чтобы потомки не начали вызывать
            // метод, т.к. для них путь также изменится
            static $updating = false;

            if ( ! $updating && $model->isDirty('path')) {
                $updating = true;

                $model->updateDescendantsPaths();

                $updating = false;
            }
        });
    }
//
    public function client(int $id): array
    {
        $categories = $this->hasOne('App\Client')->where(['category_id' => $this->id, 'client_id' => $id])->get(['category_id'])->toArray();

        if (!empty($categories)) {
            return $categories[0];
        }
        return $categories;
    }


    public function subcategories()
    {
        return $this->hasMany('App\Category', 'parent_id', 'id');
    }

    public function getDisplayedProducts()
    {
        return $this->products()->whereHas('prices', function ($query) {
            $query->where('price', '!=', null)->where('price', '!=', 0)->whereHas('priceType', function ($query2) {
                $query2->where('title', 'Розничная');
            });
        })->get();
    }
}
