<?php

namespace App\Http\Controllers\API;

use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Store::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(empty($request->get('slug')))
            $ext_request['slug'] = Str::slug($request->get('title'));

        $store = Store::create(array_merge($request->all(),$ext_request));

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
        $priceType = Store::where('uuid', '=', $uuid)->firstOrFail();
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
        $store = Store::where('uuid', '=', $id)->firstOrFail();
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
        $store = Store::where('uuid', '=', $uuid)->firstOrFail();
        $store->delete();

        return response()->json(null, 204);
    }
}
