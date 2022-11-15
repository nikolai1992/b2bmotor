<?php

namespace App\Services;

use App\UpdateProductPrice;

class UpdateProductPriceService
{
    public function addQueue($product, $request)
    {
        UpdateProductPrice::create([
           "product_id" =>  $product->id,
            "request" => json_encode($request->prices)
        ]);
    }
}