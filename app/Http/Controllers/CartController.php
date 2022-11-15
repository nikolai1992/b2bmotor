<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Product;

class CartController extends Controller
{
    public function show(Request $request): View
    {
        $items = Cart::content();
        $total = Cart::total();
        $isEmpty = false;

        if ($items->isEmpty()) {
            $isEmpty = true;
        }

        return view('cart.show', compact('items', 'total', 'isEmpty'));
    }

    public function add(Request $request, Product $product): string
    {
        if (!$request->ajax()) abort(404);

        Cart::add($product->id, $product->title, $request->quantity, $request->price);

        return collect([
            'text' => __('cart.add'),
            'count' => Cart::count()
        ]);
    }

    public function update(Request $request): array
    {
        if (!$request->ajax()) abort(404);

        Cart::update($request->rowId, $request->quantity);

        return [
            'total' => Cart::total(),
            'text' => __('cart.update')
        ];
    }

    public function remove(string $rowId): string
    {
        Cart::remove($rowId);

        return redirect()->route('show_cart');
    }

    public function clear(): string
    {
        Cart::destroy();

        return redirect()->route('show_cart');
    }
}
