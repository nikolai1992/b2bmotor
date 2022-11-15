<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use App\Product;
use App\Services\Product\ProductServiceImpl;
use Illuminate\Support\Facades\DB;

class ProductController
{
    public function index(): Collection
    {
        return Product::all();
    }

    public function show(Product $product, $uuid)
    {
        $product = Product::where('uuid', $uuid)->firstOrFail();
        return response()->json($product, 200);
    }

    public function store(Request $request)
    {
        $product = (new ProductServiceImpl)->apiStoreProduct($request);

        return response()->json($product, 201);
    }

    public function update(Request $request, $uuid)
    {
        $product = Product::where('uuid', '=', $uuid)->firstOrFail();
        (new ProductServiceImpl)->apiUpdateProduct($product, $request);

        return response()->json($product, 200);

    }

    public function delete($uuid)
    {
        $product = Product::where('uuid', $uuid)->firstOrFail();
        (new ProductServiceImpl)->removeProduct($product);

        return response()->json(null, 204);
    }
}
