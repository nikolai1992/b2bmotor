<?php

namespace App\Http\Controllers\API;

use App\PriceProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PriceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PriceProduct::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $priceProduct = PriceProduct::create([
            'uuid' => $request->get('uuid')
        ]);

        return response()->json($priceProduct, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $priceType = PriceProduct::where('uuid', '=', $uuid)->firstOrFail();
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
        $store = PriceProduct::where('uuid', '=', $id)->firstOrFail();
        $store->update($request->all());

        return response()->json($store, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $store = PriceProduct::where('uuid', '=', $uuid)->firstOrFail();
        $store->delete();

        return response()->json(null, 204);
    }
}
