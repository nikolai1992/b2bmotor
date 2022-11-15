<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\{Brand, Client, Product, Category, Services\Product\ProductService, Warehouse};

class SearchController extends Controller
{
    /* @var ProductService $productService */
    private $productService;

    /**
     * CatalogController constructor.
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function search(Request $request)
    {
        $search = Input::get('query');
        
        $products = $this->productService->getProductsForCatalog($request, $search);

        return view('catalog.index', compact('products', 'search'/*, 'warehouses', 'brands'*/));
    }
}
