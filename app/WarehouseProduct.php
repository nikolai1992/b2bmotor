<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseProduct extends Model
{
    protected $fillable = [
        'product_1c_id',
        'warehouse_1c_id',
        'availability',
    ];

    public function warehouse()
    {
        return $this->hasOne('App\Warehouse', '1c_id', 'warehouse_1c_id');
    }
}
