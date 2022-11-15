<?php

namespace App\Services;

use App\Product;
use App\FacticalPrice;
use App\User;

class FacticalPriceService
{
    public function updateFacticalPrice(Product $product, User $user, $new_price = false)
    {
        if (!$new_price) {
            $factical_price = FacticalPrice::where('product_id', $product->id)
                ->where('user_id', $user->id)
                ->first();
        }

        $new_fact_price = $product->getFactPriceAttribute($user);
        $data = [
            "user_id" => $user->id,
            "product_id" => $product->id,
            "product_price_id" => $new_fact_price ? $new_fact_price->id : null,
        ];

        if ($data['product_price_id']) {
            if (!$new_price) {
                if ($factical_price) {
                    $factical_price->update($data);
                } else {
                    FacticalPrice::create($data);
                }
            } else {
                FacticalPrice::create($data);
            }
        } else {
            if ($factical_price) {
                $factical_price->delete();
            }
        }
    }

    public function updateFacticalPriceByUsers(Product $product, $users)
    {
        foreach ($users as $user) {
            $this->updateFacticalPrice($product, $user);
        }
    }
}