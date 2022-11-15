<?php

namespace App\Http\Controllers\Api;

use App\PriceType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PriceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PriceType::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $store = PriceType::create($request->all());

        return response()->json($store, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $priceType = PriceType::where('uuid', '=', $uuid)->firstOrFail();
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
        $priceType = PriceType::where('uuid', '=', $id)->firstOrFail();
        $priceType->update($request->all());

        return response()->json($priceType, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $priceType = PriceType::where('uuid', '=', $uuid)->firstOrFail();
        $priceType->delete();

        return response()->json(null, 204);
    }
}
