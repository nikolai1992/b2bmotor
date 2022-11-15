<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Cache;
use App\Services\CategoryService;
use App\{Brand, Client, Product, Category, Services\Product\ProductService, Currency};
use Session;

class CatalogController extends Controller
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
        $active_currency = CurrencyService::getCurrentCurrency();
        \View::share(['active_currency' => $active_currency]);
    }

    public function index(Request $request): View
    {
        $search = $_GET['query'] ?? "";
        $cache_key = $search ? 'catalog_products_search_'.$search."_user_".auth()->user()->id : 'catalog_products'.auth()->user()->id;
        $products = Cache::store('redis')->tags("catalog_products")->remember($cache_key, Carbon::now()->addMinutes(30), function() use ($request, $search) {
            return $this->productService->getProductsForCatalog($request, $search);
        });

        return view('catalog.index', compact('products', 'search'));
    }

    public function update(Request $request): View
    {
        $queryBuilder = Product::with('warehouse', 'brand');
        $queryBuilder = Product::filter($request, $queryBuilder);
        $products = $queryBuilder->sort($request)
            ->paginate(10);

        return view('catalog.partials.products', compact('products'));
    }

    public function show(Request $request, string $path): View
    {
        $category = Category::where('path', $path)->firstOrFail();

        if (in_array($category->id, Client::getDenies())) {
            abort(403);
        }

//        $products = $this->productService->getProductsForCatalogByCategory($request, $category);
        $subcategories = $this->productService->getSubCategoryForCatalogByCategory($category);
        $products = $this->productService->getProductsForCatalogByCategoryAndSubcategory(
            $request,
            $category,
            $subcategories
        );
//        $brands = Brand::all();
//        $warehouses = Warehouse::all();

//        if(!$subcategory->isEmpty()){
//            return view('catalog.category.empty', compact('subcategory', 'path'/*, 'warehouses','brands'*/));
//        }else{
            return view('catalog.index', compact('products', 'path'/*, 'warehouses','brands'*/));
//        }

    }

    public function showMenu($categories = null)
    {
//        Cache::forget('categories'.auth()->user()->id);
        $categories_view = Cache::store('redis')->tags("catalog_products")->remember('categories'.auth()->user()->id, 30, function () use ($categories) {
            $categories = $categories ? $categories : Category::whereNotIn('id', Client::getDenies())->orWhereHas('subcategories')
                ->orderBy('title', 'ASC')->get()->toTree();
            return view('layouts.partials.menu', compact('categories'))->render();
        });

        return $categories_view;
    }

    public function searchByFields(Request $request)
    {
        $title = $request->title;
        $short_title = isset($request->short_title) ? $request->short_title : null;
        $article = isset($request->article) ? $request->article : null;
        $products = $this->productService->getProductsForCatalog($request);

        return view('catalog.index', compact('products', 'title', 'short_title', 'article'));
    }

    public function changePricesTaxStatus(Request $request)
    {
        $tax = $request->tax_price_status;
        auth()->user()->update([
            "price_tax_status" => $tax
        ]);

        return redirect()->back();
    }
}
