<?php

namespace App\Services\Product;

use App\FileProducts;
use App\Services\CategoryService;
use App\Services\CacheService;
use App\Services\FacticalPriceService;
use App\Services\UpdateProductPriceService;
use App\Tag;
use App\Constants;
use App\Product;
use App\Category;
use App\PriceProduct;
use App\User;
use App\PersonalPrice;
use App\WaitingForDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as LengthAwarePagin;
use Session;
use DB;
use Image;
use Storage;
use App\Store;
use App\PriceType;
use Illuminate\Support\Str;

class ProductServiceImpl implements ProductService
{

    public function getProductsForCatalog(Request $request, $search = ""): LengthAwarePaginator
    {

        return $this->getProducts(
            Product::query(),
            $request,
            $search
        );
    }

    public function getProductsForCatalogByCategory(Request $request, Category $category): LengthAwarePaginator
    {
        return $this->getProducts(
            Product::whereHas('category', function ($query) use ($category) {
                $query->where('id', $category->id);
            }),
            $request
        );
    }

    public function getProductsForCatalogByCategoryAndSubcategory(Request $request, Category $category, $subcategories): LengthAwarePaginator
    {
        $search = $_GET['query'] ?? "";
        $ids = (new CategoryService())->getSubcategoriesIds($category);

        return $this->getProducts(
            Product::whereIn('category_id', $ids),
            $request,
            $search
        );
    }

    public function getSubCategoryForCatalogByCategory(Category $category)
    {
//        if (Cache::has('subcategory'.auth()->user()->id.'_'.$category->id)) {
//            //
//            $subcategory = Cache::get('subcategory'.auth()->user()->id.'_'.$category->id);
//        } else {
            $subcategory = Category::where('parent_id','=',$category->id)->get();
//            dd('test');
//            Cache::put('subcategory'.auth()->user()->id.'_'.$category->id, $subcategory, 60);
//        }

        return $subcategory;
    }

    public function getProductsByCategoryAndSubcategoriesWithoutPagination(Category $category)
    {
        $ids = (new CategoryService())->getSubcategoriesIds($category);

        return Product::whereIn('category_id', $ids);
    }

    /**
     * @param Builder|Product $baseQuery
     * @param $request
     * @return LengthAwarePaginator
     */
    private function getProducts(Builder $baseQuery, $request, $search = ""): LengthAwarePaginator
    {
        $pagination_items_count = Session::get('pagination_items_count') ? Session::get('pagination_items_count') : 10;
        if (!empty($search)){

            /* @var Product[]|LengthAwarePaginator $products */
            $products = $baseQuery->with([
                'category',
                'stores' => function ($query) {
                    $query->select('title', 'amount');
                },
                'prices.priceType',
                'tags.personalPrices' => function ($query) {
                    $query->where('user_id', Auth::user()->id);
                },
            ])
                ->where(function ($query) use ($search){
                    $query->where('products.title', 'LIKE', "%{$search}%")
                        ->orWhere('products.short_title', 'LIKE', "%{$search}%")
                        ->orWhere('products.article', 'LIKE', "%{$search}%");
                });
//                where('title', 'LIKE', "%{$search}%")
//                ->orWhere('article', 'LIKE', "%{$search}%");
//                ->orWhere('short_title', 'LIKE', "%{$search}%");
//                ->sort($request);
        }else{

            /* @var Product[]|LengthAwarePaginator $products */
            $products = $baseQuery->with([
                'category',
                'stores' => function ($query) {
                    $query->select('title', 'amount');
                },
                'files.product',
                'prices.priceType',
                'tags.personalPrices' => function ($query) {
                    $query->where('user_id', Auth::user()->id);
                },
            ]);

            $products = $this->getProductBySpecificField($request, $products);
        }
        $products = $products->join('factical_prices', function ($join) {
            $join->on('products.id', '=', 'factical_prices.product_id')
                ->where('factical_prices.user_id', '=', auth()->user()->id)->leftJoin('price_products', function($join) {
                    $join->on('price_products.id', '=', 'factical_prices.product_price_id')->join('price_types', function ($join) {
                        $join->on('price_types.id', '=', 'price_products.price_type_id');
                    });
                });
        });
        $products = $products->whereHas('prices', function ($query) {
            $query->where('price', '!=', null)->where('price', '!=', 0)->whereHas('priceType', function ($query2) {
                $query2->where('title', 'Розничная');
            });
        });
        $products = $products->select('products.slug', 'price_products.price', 'price_products.price_type_id',
            'price_types.title as price_type_title', 'products.slug', 'products.title', 'products.id',
            'products.thumb', 'products.short_title', 'products.article');
        $request->sortBy = $request->sortBy ? $request->sortBy : "title";
        $request->orderBy = $request->orderBy ? $request->orderBy : "asc";
        $products = $this->sortProducts($request, $products);
        foreach ($products as $product) {
            $product->productPrice = $this->getPersonalPriceForProduct($product);
            $product->productPriceRetail = $this->getPersonalPriceOtherForProduct($product,1);
            $product->productPriceOpt = $this->getPersonalPriceOtherForProduct($product,3);
        }
        $products = $products->paginate($pagination_items_count);

        return $products;
    }

    public function paginateCollection(
        $collection,
        $perPage,
        $pageName = 'page',
        $fragment = null
    ) : LengthAwarePaginator
    {
        $currentPage = LengthAwarePagin::resolveCurrentPage($pageName);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage);
        parse_str(request()->getQueryString(), $query);
        unset($query[$pageName]);
        $paginator = new LengthAwarePagin(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'pageName' => $pageName,
                'path' => LengthAwarePagin::resolveCurrentPath(),
                'query' => $query,
                'fragment' => $fragment
            ]
        );

        return $paginator;
    }

    public function getPersonalPriceOtherForProduct(Product $product, $priceType): ?PriceProduct
    {

        /* @var PersonalPrice|null $personalPrice */
        $personalPrice = $this->getPersonalPrice($product);

        return $personalPrice
            ? $this->getProductPriceOther($product, $personalPrice, $priceType)
            : null;
    }

    /**
     * @param Product $product
     * @param PersonalPrice $personalPrice
     * @return PriceProduct|null
     */
    private function getProductPriceOther(Product $product, PersonalPrice $personalPrice, $priceType): ?PriceProduct
    {

        foreach ($product->prices as $price) {
            if($personalPrice->price_type_id != $price->price_type_id) {
                if (($personalPrice->price_type_id == 3 || $personalPrice->price_type_id == 2) && $price->price_type_id == $priceType) {

                    return $price;
                }
                if ($personalPrice->price_type_id == 2 && $price->price_type_id == $priceType) {

                    return $price;
                }
            }
        }
        return null;
    }

    public function getPersonalPriceForProduct(Product $product): ?PriceProduct
    {
        /* @var PersonalPrice|null $personalPrice */
        $personalPrice = $this->getPersonalPrice($product);
        return $personalPrice
            ? $this->getProductPrice($product, $personalPrice)
            : $this->getProductBasePrice($product); // probably should be replaced by some default value
    }

    private function getPersonalPrice(Product $product): ?PersonalPrice
    {
        $personalPrice = PersonalPrice::where('user_id', Auth::user()->id)->first();

        if($personalPrice) {
            return $personalPrice;
        }
        $productPriceTypeIds = $product->prices->pluck('price_type_id');

        foreach ($product->tags as $tag) {
            /* @var Tag $tag */
            foreach ($tag->personalPrices as $personalPrice) {
                if ($productPriceTypeIds->contains($personalPrice->price_type_id)) {
                    return $personalPrice;

                }
            }
        }

        return null;
    }

    private function getProductPrice(Product $product, PersonalPrice $personalPrice): ?PriceProduct
    {

        foreach ($product->prices as $price) {
            if ($price->price_type_id === $personalPrice->price_type_id) {
                return $price;
            }
        }
        return null;
    }


    private function getProductBasePrice(Product $product): ?PriceProduct
    {

        foreach ($product->prices as $price) {
            if ($price->price_type_id == 7) {
                return $price;
            }
        }
        return null;
    }

    public function getProductBySpecificField(Request $request, $products)
    {
        if ($request->title) {
            $products = $products->where('products.title', 'LIKE', "%{$request->title}%");
        }
        if ($request->short_title) {
            $products = $products->where('products.short_title', 'LIKE', "%{$request->short_title}%");
        }
        if ($request->article) {
            $products = $products->where('products.article', 'LIKE', "%{$request->article}%");
        }

        return $products;
    }

    function sortProducts($request, $products)
    {
        ini_set('max_execution_time', 0);
        if (isset($request->sortBy) && isset($request->orderBy)) {
            if ($request->sortBy != "total_price" || auth()->user()->all_price && $request->sortBy == "total_price") {
                $products = $products->orderBy("products.".$request->sortBy, $request->orderBy);
            } else {
                $products = $products->orderBy('price_products.price', $request->orderBy);
            }
        }

        return $products;
    }

    public function updateStoreImage($product, $url)
    {
        $path = $this->storeImageFromUrl($product->id, $url);
        if ($path != "/images/no-image-icon.png") {
            $arr = explode("/", $path);
            $file_name = array_pop($arr);
            $file = $product->files()->create(['url' => $path, "file_name" => $file_name,]);
//            \Log::debug("file");
//            \Log::debug($file);
            $search = "images/".$product->id."/";
            $replace = "images/".$product->id."/thumbnail_";
            $thumbnail_path = str_replace($search, $replace, $path);
            $this->imageCompression($path, $thumbnail_path);
            $path = $thumbnail_path;
        } else {
            if ($product->images_request) {
                $product->images_request = "";
                $product->save();
            }
        }
        \Log::debug("path = ".$path);
//        $this->imageCompression($path);

        if ($path != '') {
            $product->thumb = $path;
            $product->save();
        }
    }

    public function updateStoreFile($product, $url)
    {
        ini_set('memory_limit', '-1');
        $count = 0;
        foreach ($url as $file) {
            $count++;
            if ($file) {
                $path = $this->storeFileFromUrl($product->id, $file);
                $search_path = str_replace("files/", 'images/', $path);
                \Log::debug($search_path);
                $stored_file = FileProducts::where('url', $search_path)->where("product_id", $product->id)->first();
                if (!$stored_file) {
                    if ($path != "/images/no-image-icon.png") {
                        $arr = explode("/", $path);
                        $file_name = array_pop($arr);
                        $product->files()->create(['url' => $path, "file_name" => $file_name,]);
                    } else {
                        if ($count == count($url)) {
                            $product->files_request = "";
                            $product->save();
                        }
                    }
                }
            }
        }
    }

    private function storeFileFromUrl($productId, $fileUrl): string
    {
        return $this->storeFileFromUrlToFolder(Constants::FILES_FOLDER, $productId, $fileUrl);
    }
    private function storeImageFromUrl($productId, $fileUrl): string
    {
        return $this->storeFileFromUrlToFolder(Constants::IMAGES_FOLDER, $productId, $fileUrl);
    }

    private function storeFileFromUrlToFolder($folder, $productId, $fileUrl): string
    {
        if ($fileUrl) {
            stream_context_set_default(array(
                'ssl'                => array(
                    'peer_name'          => 'generic-server',
                    'verify_peer'        => FALSE,
                    'verify_peer_name'   => FALSE,
                    'allow_self_signed'  => TRUE
                )));
            try {
                $fileContent = file_get_contents($fileUrl, false);
            } catch (\Exception $e) {
                try {

                    $fileUrl = $this->mediaUrlStrReplace($fileUrl);
                    $fileContent = file_get_contents($fileUrl, false);
                } catch (\Exception $e) {
                    \Log::debug($e);
                    return "/images/no-image-icon.png";
                }
            }

            $explodedFileUrl = explode("/", $fileUrl);
            $filename = array_pop($explodedFileUrl);
            $filename = urldecode($filename);

            $storagePath = "$folder/$productId/$filename";

            Storage::put($storagePath, $fileContent);

            return $storagePath;
        }
    }

    public function imageCompression($path, $thumbnail_path)
    {
        \Log::debug("path = ".$path);
        $full_path = storage_path("app/public/".$path);
        $thumbnail_path = storage_path("app/public/".$thumbnail_path);
//        \Log::debug("full_path1 = ".$full_path);
//        \Log::debug("thumbnail_path = ".$thumbnail_path);
        ini_set('memory_limit', '-1');
        $image_resize = Image::make($full_path);
        \Log::debug("after Image::make");
//        \Log::debug("full_path2 = ".$full_path);
        if($image_resize->width()>420)
        {
            // resize the image to a height of 200 and constraint aspect ratio (auto width)
            $image_resize->resize(420, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
//        \Log::debug("before save resize");
        $image_resize->save($thumbnail_path);
//        \Log::debug("after save resize");
    }

    public function addNewQueueForDownloading($product_id, $new_image_path, $new_files_path)
    {
        $this->checkFilesArray($new_files_path);
        $new_image_path = $this->encodeSpaces($new_image_path);
        $new_files_path = $this->checkFilesArray($new_files_path);
        ini_set('memory_limit', '-1');
        return WaitingForDownload::create([
            "image_url" => $new_image_path,
            "file_url" => $new_files_path,
            "product_id" => $product_id
        ]);
    }
    public function downloadProductContent($queue)
    {
        $product = $queue->product;
        $product->files()->delete();
        if ($queue->image_url) {
//            \Log::debug($product);
//            \Log::debug("before updateStoreImage");
            $this->updateStoreImage($product, $queue->image_url);
//            \Log::debug("after updateStoreImage");
        }

        if ($queue->file_url) {
            $this->updateStoreFile($product, $queue->file_url);
        }
//        \Log::debug("Finish downloadProductContent");
    }

    function encodeSpaces($str)
    {
        return str_replace(' ', '%20', $str);
    }

    function checkFilesArray($arr)
    {
        if (!empty($arr)) {
            for ($i = 0; $i < count($arr); $i++) {
                $arr[$i] = $this->encodeSpaces($arr[$i]);
            }
        }

        return $arr;
    }

    public static function getTotalPrice(Product $product)
    {
        $total_price = 0;
        $prices = $product->prices;
        if ($prices->count()) {
            $retailPrice = $product->getRetailPrice();
            $personalPrice = $product->getPersonalPrice();
            if ($personalPrice) {
                $total_price = $personalPrice->price;
            } elseif ($retailPrice) {
                $total_price = $retailPrice->price;
            } else {
                $total_price = $prices->first()->price;
            }
        }

        return $total_price;
    }

    public function apiUpdateProduct($product, $request)
    {
        try {
            \Log::debug("apiUpdateProduct product_id = ".$product->id);
        } catch (\Exception $e) {

        }
        DB::transaction(function () use ($request, $product) {
            $new_files_path = "";
            $new_image_path = "";
            $ext_request = [];
            ini_set('memory_limit', '-1');

            $this->removeMedia($product);
            // Images
            if(!empty($request->get('images'))) {
                $new_image_path = $request->get('images');
                $ext_request["images_request"] = $new_image_path;
            }

            $ext_request["thumb"] = "/images/no-image-icon.png";

            if ($request->get('parent')) {
                // Category
                $category = Category::where('uuid', $request->get('parent'))->first();
                if (!empty($category))
                    $ext_request['category_id'] = $category->id;
            }

            // Tags
            $this->detachOldAttachNewTags($product, $request);

            // Stores
            $ext_request["total_amount"] = $this->updateStores($product, $request);

            // Prices
            (new UpdateProductPriceService)->addQueue($product, $request);

// Files
            if (!empty($request->get('files'))) {
                $new_files_path = $request->get('files');
                $ext_request['files_request'] = json_encode($new_files_path);
            }

            $product->update(array_merge($request->all(), $ext_request));

            $product->files()->delete();

            $this->checkOnAddingNewQueue($product, $new_image_path, $new_files_path);
//            (new CacheService)->clearCache();
        });

        if (!empty($request->get('files')) || !empty($request->get('images'))) {
            $queue = WaitingForDownload::where('product_id', $product->id)->first();
            if (!$queue) {
                return abort(500, 'queue didn\'t added.');
            }
        }
    }

    public function apiStoreProduct($request)
    {
        DB::transaction(function () use ($request)  {
            $new_files_path = "";
            $new_image_path = "";
            $data = $request->all();
            // Images
            if(!empty($request->get('images'))) {
                $new_image_path = $request->get('images');
                $data["images_request"] = $new_image_path;
            }

            $data["thumb"] = "images/no-image-icon.png";
            // Files
            if (!empty($request->get('files'))) {
                $new_files_path = count($request->get('files')) ? $request->get('files') : "";
                $data['files_request'] = json_encode($new_files_path);
            }

            $product = $this->store($data);

            $this->attachNewTags($product, $request);

            // Stores
            $total_amount = $this->storeStores($product, $request);

            // Prices
            $this->storePrices($product, $request);

            $this->updateTotalPriceAndStores($product, $total_amount);



            $this->checkOnAddingNewQueue($product, $new_image_path, $new_files_path);
        });

        $product = Product::where('uuid', '=', $request->uuid)->with([
            "tags",
            'prices',
            'stores',
            'category',
            'files'
        ])->first();

        return $product;
    }

    function checkOnAddingNewQueue($product, $new_image_path, $new_files_path)
    {
        if ($new_image_path || $new_files_path) {
            ini_set('memory_limit', '-1');
            $queue = WaitingForDownload::where('product_id', $product->id)->first();
            try {
                \Log::debug('checkOnAddingNewQueue product_id = '.$product->id);
            } catch (\Exception $e) {

            }
            while (!$queue) {
                $queue = $this->addNewQueueForDownloading($product->id, $new_image_path, $new_files_path);
            }
            try {
                \Log::debug('after checkOnAddingNewQueue product_id = '.$product->id);
                \Log::debug($queue);
            } catch (\Exception $e) {

            }

        }
    }

    function store($data)
    {
        $ext_request = [];
        $category = Category::where('uuid', $data['parent'])->firstOrFail();
        if(!empty($category))
            $data['category_id'] = $category->id;

        if(empty($data['slug']))
            $data['slug'] = Str::slug($data['title']);

        // Images

        return Product::create($data);
    }

    function mediaUrlStrReplace($url)
    {
//        $url = str_replace("%2B", "+", $url);
        $url = str_replace(".jpg", ".JPG", $url);
//        $url = str_replace("http://194.44.241.13", "https://192.168.5.5/", $url);

        return $url;
    }

    function switchExtensionRegister($url)
    {
        if (str_contains($url, '.jpg')) {
            $url = str_replace(".jpg", ".JPG", $url);
        } elseif (str_contains($url, '.JPG')) {
            $url = str_replace(".JPG", ".jpg", $url);
        }

        return $url;
    }

    function attachNewTags($product, $request)
    {
        // Tags
        $tagList = [];
        $tags = Tag::whereIn('uuid', $request->get('tags'))->get();

        foreach ($tags->all() as $tag)
            $tagList[] = $tag->id;

        $product->tags()->attach($tagList);
    }

    function detachOldAttachNewTags($product, $request)
    {
        $product->tags()->detach();
        if (!empty($request->get('tags'))){

            $tagList = [];
            $tags = Tag::whereIn('uuid', $request->get('tags'))->get();

            foreach ($tags->all() as $tag)
                $tagList[] = $tag->id;

            $product->tags()->attach($tagList);
        }
    }

    function storeStores($product, $request)
    {
        // Stores
        $storeIdList = [];
        $storeUuidAmount = [];
        foreach ($request->get('stores') as $store) {
            $storeIdList[] = $store['store_uuid'];
            $storeUuidAmount[$store['store_uuid']] = $store['amount'];
        }

        $stores = Store::whereIn('uuid', $storeIdList)->get();
        $storeList = [];
        $storeIdAmount = [];
        foreach ($stores->all() as $store) {
            $storeList[] = $store->id;
            $storeIdAmount[$store->id] = $storeUuidAmount[$store->uuid];
        }

        $total_amount = 0;
        $product->stores()->attach($storeList);
        $product->stores()->each(function($store) use ($storeIdAmount) {
            $store->pivot->amount = $storeIdAmount[$store->id];
            $store->pivot->save();
        });

        foreach ($product->stores as $store) {
            $total_amount = $total_amount + $store->amount;
        }

        return $total_amount;
    }

    function updateStores($product, $request)
    {
        $total_amount = 0;
        if (!empty($request->get('stores'))) {

            $storeIdList = [];
            $storeUuidAmount = [];
            foreach ($request->get('stores') as $store) {
                $storeIdList[] = $store['store_uuid'];
                $storeUuidAmount[$store['store_uuid']] = $store['amount'];
            }

            $stores = Store::whereIn('uuid', $storeIdList)->get();
            $storeList = [];
            $storeIdAmount = [];
            foreach ($stores->all() as $store) {
                $storeList[] = $store->id;
                $storeIdAmount[$store->id] = $storeUuidAmount[$store->uuid];
            }

            $product->stores()->detach();
            $product->stores()->attach($storeList);
            $product->stores()->each(function ($store) use ($storeIdAmount) {
                $store->pivot->amount = $storeIdAmount[$store->id];
                $store->pivot->save();
            });

            foreach ($product->stores as $store) {
                $total_amount = $total_amount + $store->amount;
            }

        } else {
            $product->stores()->each(function ($store) {
                $store->pivot->amount = 0;
                $store->pivot->save();
            });
        }

        return $total_amount;
    }

    function storePrices($product, $request)
    {
        foreach ($request->get('prices') as $price) {
            if ($price) {
                $priceType = PriceType::where('uuid', $price['price_type_uuid'])->firstOrFail();

                $priceProduct = PriceProduct::create(['price' => (string)$price['value']]);

                $priceProduct->priceType()->associate($priceType)->save();
                $priceProduct->product()->associate($product)->save();
            }
        }
    }

    function updateTotalPriceAndStores($product, $total_amount)
    {
        $product->update([
            "total_amount" => $total_amount,
            "total_price" => $product->findSmallestPrice(),
        ]);
    }

    function updatePrices($product, $prices)
    {
        if(count($prices)) {

            $product->prices()->delete();

            foreach ($prices as $price) {
                if ($price) {
                    $priceType = PriceType::where('uuid', $price->price_type_uuid)->firstOrFail();
                    $data = [
                        'price' => (string)$price->value,
                    ];
                    $priceProduct = PriceProduct::create($data);

                    $priceProduct->priceType()->associate($priceType)->save();
                    $priceProduct->product()->associate($product)->save();
                }
            }

            $this->defineFactPrices($product);
        } else {
            foreach ($product->prices as $price) {
                $price->update([
                    "price" => 0
                ]);
            }
        }
    }

    function defineFactPrices($product)
    {
        $users = User::whereHas('factPrice')->where('email', '!=', "")->get();
        (new FacticalPriceService)->updateFacticalPriceByUsers($product, $users);
    }

    public function removeProduct(Product $product)
    {
        $this->removeMedia($product);
        $product->delete();
        (new CacheService)->clearCache();
    }

    public function removeMedia(Product $product)
    {
        try {
            if ($product->thumb != "/images/no-image-icon.png" && $product->thumb != ""
                && $product->thumb != "images/no-image-icon.png") {
                $exists = \Storage::disk('public')->exists('/'.$product->thumb);
                if ($exists) {
                    \Storage::disk('public')->delete("/".$product->thumb);
                }
            }
        } catch (\Exception $e) {
            \Log::debug($e);
        }

        foreach ($product->files as $file) {
            try {
                if ($file) {
                    if ($file->url) {
                        $exists = \Storage::disk('public')->exists('/'.$file->url);
                        if ($exists) {
                            \Storage::disk('public')->delete("/".$file->url);
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::debug($e);
            }

        }
    }

    public function checkFileExists($image)
    {
        if (!file_exists(storage_path("/app/public/".$image))) {
            return $this->switchExtensionRegister($image);
        }

        return $image;
    }
}
