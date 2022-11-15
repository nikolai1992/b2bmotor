<?php

namespace App\Http\Controllers\API;

use App\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class TagController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tag::all();
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

        $tag = Tag::create(array_merge($request->all(),$ext_request));

        return response()->json($tag, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $priceType = Tag::where('uuid', '=', $uuid)->firstOrFail();
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
        $store = Tag::where('uuid', '=', $id)->firstOrFail();
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
        $store = Tag::where('uuid', '=', $uuid)->firstOrFail();
        $store->delete();

        return response()->json(null, 204);
    }
}
