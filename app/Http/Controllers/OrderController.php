<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\{Manager, Product, Order, User, Services\Product\ProductService, Currency, Tag};

class OrderController extends Controller
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
    public function index(Request $request)
    {
        $user = Auth::user();
        $queryBuilder = Order::with('user');

        if ($user->inRole('client')) {
            $queryBuilder = $queryBuilder->where('user_id', Auth::user()->id);
        } else if ($user->inRole('manager')) {
            $queryBuilder = $queryBuilder->whereIn('user_id', Manager::getClients($user->id));
        }

        $orders = $queryBuilder->sort($request)
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {

        $products=$order->products;
        foreach ($products as $product) {
            $product->productPrice = $this->productService->getPersonalPriceForProduct($product);
        }

        return view('orders.show', compact('order','products'));
    }

    public function store(Request $request)
    {
        $order = new Order();

        $order->user_id = $request->user()->id;

        $manegers = User::where('id','=', $request->user()->manager)->first();

        $order->save();

        $products = $this->getProductsParams($request);

        $order->products()->attach($products);


        $productApi=$order->products;
        foreach ($productApi as $product) {
            $product->productPrice = $this->productService->getPersonalPriceForProduct($product);
        }
        foreach ($productApi as $product) {
            if (!auth()->user()->all_price) {
                $price = $product->getFactPriceAttribute(auth()->user());
                $price = $price ? $price->price : 0;
            } else {
                $price = $product->findSmallestPrice();
            }
            $arproducts[]=[
                "uuid_product" => $product->uuid,
                "quantity" => $product->pivot->qty,
                "price" => $price,

            ];
        }

        if($order->id){
            $order1c=[
               "NEW_DEAL" => [
                    "UUID_CLIENT"=> $request->user()->uuid,
                    "UUID_MANAGER"=> ($manegers)? $manegers->uuid : null,
                    "PRODUCTS" => $arproducts
                ]
            ];
        }

        $response=json_encode($order1c);

        $otvet= $order->data1c($order1c);

        Cart::destroy();

        return redirect()->route('show_order', ['order' => $order->id]);
    }

    public function edit(Order $order)
    {
        $products=$order->products;
        foreach ($products as $product) {
            $product->productPrice = $this->productService->getPersonalPriceForProduct($product);
        }

        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $products = $this->getProductsParams($request);

        $order->updateProducts($products);

        return back();
    }

    public function remove(Order $order, Product $product)
    {
        $order->products()->detach($product);

        return back();
    }

    private function getProductsParams(Request $request)
    {
        $products = array_map(function ($item) {
            return ['qty' => $item];
        }, array_combine($request->product_id, $request->product_quantity));

        return $products;
    }

    public function cansele(Order $order)
    {
        if($order->user_id == Auth::user()->id){

        $order->status = 3;
        $order->save();
            $order1c='{"PING":{}}';

            $otvet= $order->data1c($order1c);
            dd($otvet);
        }

        return [
            'text' => __('order.canceled')
        ];

    }
}
