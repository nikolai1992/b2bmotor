<?php

namespace App\Services;

use App\Category;
use App\User;
use App\Services\Product\ProductServiceImpl;
use App\Services\Product\CacheService;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    private $ids = [];
    private $have_products = false;

    public function getSubcategoriesIds(Category $category)
    {
        $this->inspectCategory($category);

        return $this->ids;
    }

    function inspectCategory(Category $category)
    {
        $this->ids[] = $category->id;
        foreach ($category->subcategories as $subcategory) {
            $this->inspectCategory($subcategory);
        }
    }

    public function findProducts($category)
    {
        if ($category->getDisplayedProducts()->count()) {
            $this->have_products = true;
            return $this->have_products;
        } else {
            if ($category->subcategories->count()) {
                $this->findProductsInTree($category);
                return $this->have_products;
            } else {
                return $this->have_products;
            }
        }
//        return $result;
    }

    public function findProductsInTree($category)
    {
        foreach ($category->subcategories as $category) {
            if ($category->getDisplayedProducts()->count()) {
                $this->have_products = true;
                return $this->have_products;
            } else {
                if ($category->subcategories->count()) {
                    $this->findProductsInTree($category);
                }
            }
        }
    }

    public function removeUsersCachedMenu()
    {
        $users = User::where('email', '!=', "")->get();
        foreach ($users as $user) {
            Cache::forget('categories'.$user->id);
        }
    }


    public function removeAllProductsInFolder($category)
    {
        $products = (new ProductServiceImpl)->getProductsByCategoryAndSubcategoriesWithoutPagination($category);
        foreach ($products->get() as $product) {
            (new ProductServiceImpl)->removeMedia($product);
        }
        $products->delete();
    }

    public function removeCategory($uuid)
    {
        $category = Category::where('uuid', '=', $uuid)->firstOrFail();
        $this->removeAllProductsInFolder($category);
//        $this->removeUsersCachedMenu();
        $category->delete();
        (new CacheService)->clearCache();
    }
}