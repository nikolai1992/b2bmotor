<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WaitingForDownload extends Model
{
    //
    protected $guarded = [];

    protected $casts = [
        'file_url' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
