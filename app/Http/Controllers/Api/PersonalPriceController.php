<?php

namespace App\Http\Controllers\API;

use App\PersonalPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TagService;
use App\Services\CacheService;
use App\Services\FacticalPriceService;
use App\Http\Requests\PersonalPriceStoreRequest;
use App\PriceType;
use App\Product;
use App\Category;
use App\Store;
use App\Tag;
use App\User;

class PersonalPriceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PersonalPrice::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonalPriceStoreRequest $request)
    {
        $personalPrice = PersonalPrice::create([
            'uuid' => $request->get('uuid')
        ]);

        // PriceType
        $priceType = PriceType::where('uuid', $request->get('price_type_uuid') )->firstOrFail();
        $personalPrice->priceType()->associate($priceType)->save();

        // Tag
        $Tag= Tag::where('uuid', $request->get('category_uuid') )->firstOrFail();
        $personalPrice->tag()->associate($Tag)->save();

        // User
        $User= User::where('uuid', '=', $request->get('user_uuid'))->firstOrFail();
        $personalPrice->user()->associate($User)->save();

        (new TagService)->updateProductsTotalPrice($Tag, $User);
//        (new CacheService)->clearCache();

        return response()->json($personalPrice, 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $priceType = PersonalPrice::where('uuid', '=', $uuid)->firstOrFail();
        return response()->json($priceType, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $personalPrice = PersonalPrice::where('uuid', '=', $id)->firstOrFail();

        // PriceType
        if(!empty($request->get('price_type_uuid'))) {
            $priceType = PriceType::where('uuid', $request->get('price_type_uuid'))->firstOrFail();
            $personalPrice->priceType()->associate($priceType)->save();
        }

        // Tag
        if(!empty($request->get('category_uuid'))) {
            $Tag = Tag::where('uuid', $request->get('category_uuid'))->firstOrFail();
            $personalPrice->tag()->associate($Tag)->save();
        }

        // User
        if(!empty($request->get('user_uuid'))) {
            $User = User::where('uuid', '=', $request->get('user_uuid'))->firstOrFail();
            $personalPrice->user()->associate($User)->save();
        }

        (new TagService)->updateProductsTotalPrice($Tag, $User);
//        (new CacheService)->clearCache();

        return response()->json($personalPrice, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($uuid)
    {
        $store = personalPrice::where('uuid', '=', $uuid)->firstOrFail();
        $Tag = $store->tag;
        $User = $store->user;
        $products = $Tag->products;
        $store->delete();

        foreach ($products as $product) {
            (new FacticalPriceService)->updateFacticalPrice($product, $User);
        }
        (new CacheService)->clearCache();

        return response()->json(null, 204);
    }
}
