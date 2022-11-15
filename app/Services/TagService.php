<?php

namespace App\Services;

use App\Services\FacticalPriceService;
use App\Tag;
use App\User;

class TagService
{
    public function updateProductsTotalPrice(Tag $tag, User $user)
    {
        if ($tag->products->count()) {
            foreach ($tag->products as $product) {
                (new FacticalPriceService)->updateFacticalPrice($product, $user);
            }
        }
    }
}