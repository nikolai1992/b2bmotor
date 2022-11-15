<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\Product\ProductServiceImpl;
use App\Services\FacticalPriceService;
use App\Services\CategoryService;
use App\{Brand, Client, Product, FacticalPrice, Category, Services\Product\ProductService, User};
use Auth;
use Image;

class ProductController extends Controller
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
    public function show(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)->with([
            'category',
            'stores' => function ($query) {
                $query->select('title', 'amount');
            },
            'files.product',
            'prices.priceType',
            'tags.personalPrices' => function ($query) {
                $query->where('user_id', Auth::user()->id);
            },
        ])->firstOrFail();

        $product->productPrice =$this->productService->getPersonalPriceForProduct($product);


        return view('product.show', compact('product'));
    }
    public function updateTotalAmountAndPrice()
    {
//        $products = Product::whereHas('files')->get();
//        foreach ($products as $product) {
//            foreach ($product->files as $file) {
//                $arr = explode("/", $file->url);
//                $file->file_name = array_pop($arr);
//                $file->save();
//            }
//        }
        dd('finished');
//        $products = Product::where('thumb', "/images/no-image-icon.png")->whereHas('files', function ($query) {
//            $query->where("url", 'like', 'images/%');
//        })->get();
//        dd($products);
//        foreach ($products as $product) {
//            dump($product->id);
//            dump($product->slug);
//            $path = $product->files()->where("url", 'like', 'images/%')->first()->url;
//            $exists = \Storage::disk('public')->exists($path);
////            dump($exists);
//            if ($exists) {
//                $search = "images/".$product->id."/";
//                $replace = "images/".$product->id."/thumbnail_";
//                $thumbnail_path = str_replace($search, $replace, $path);
//
//                (new ProductServiceImpl)->imageCompression($path, $thumbnail_path);
//
//                $product->thumb = $thumbnail_path;
//                $product->save();
//            }
//        }
//        die;

//        $users = User::doesntHave('factPrice')->where('email', '!=', "")->orderBy('id', 'desc')->get();
        stream_context_set_default(array(
            'ssl'                => array(
                'peer_name'          => 'generic-server',
                'verify_peer'        => FALSE,
                'verify_peer_name'   => FALSE,
                'allow_self_signed'  => TRUE
            )));
//        $image_resize = Image::make("/var/www/motorimpex.net/data/www/motorimpex.net/storage/app/public/images/710174/Насос X2P5102ECBA (XV2P17) 1.jpg");
//        dd($image_resize);
        dd(file_get_contents("http://194.44.241.13:49050/photos/Aber/BI170H9%20wm/%D0%9D%D0%B0%D1%81%D0%BE%D1%81%20BI170H9%201.JPG", false));

//        dd($products);
//        dd((new CategoryService)->findProducts(Category::find(5943)));
//        dd($factical_prices);
//        $user = User::find(65053);
//        if ($user) {
//            $products = Product::whereHas('prices')->skip(50)->limit(50)->get();
////            \Log::debug($products->count());
//            foreach ($products as $product) {
//                (new FacticalPriceService)->updateFacticalPrice($product, $user);
//            }
//        }
        echo "Finished";
    }
    public function getTotalAmount(Product $product)
    {
        $amount = 0;
        foreach ($product->stores as $store) {
            $amount = $amount + $store->pivot->amount;
        }

        return $amount;
    }
}
