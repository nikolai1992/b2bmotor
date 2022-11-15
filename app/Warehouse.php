<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use \AlexeyMezenin\LaravelRussianSlugs\SlugsTrait;

    protected $fillable = [
        'name',
        'slug',
        '1c_id',
        'is_active',
    ];

    protected $slugFrom = 'name';
}
