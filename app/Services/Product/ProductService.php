<?php

namespace App\Services\Product;

use App\Product;
use App\Category;
use App\PriceProduct;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

interface ProductService
{

    public function getProductsForCatalog(Request $request): LengthAwarePaginator;

    public function getProductsForCatalogByCategory(Request $request, Category $category): LengthAwarePaginator;

    public function getPersonalPriceForProduct(Product $product): ?PriceProduct;

    public function getSubCategoryForCatalogByCategory(Category $category);

}
