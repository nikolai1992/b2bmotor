<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class FileProducts extends Model
{
    protected $table = 'file_products';

    protected $fillable = [
        'url',
        'file_name'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
