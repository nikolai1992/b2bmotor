<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 20.10.2022
 * Time: 20:22
 */

namespace App\Services;


class CacheService
{
    public function clearCache()
    {
        \Cache::store('redis')->tags("catalog_products")->flush();
    }
}