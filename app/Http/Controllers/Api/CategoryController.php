<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Services\CategoryService;
use App\Services\CacheService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public function index(): Collection
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $ext_request = [];
        $parent = Category::where('uuid', $request->get('parent'))->first();
        if(!empty($parent))
            $ext_request['parent_id'] = $parent->id;

        if(empty($request->get('slug')))
            $ext_request['slug'] = Str::slug($request->get('title'));

        $category = Category::create(array_merge($request->all(),$ext_request));
//        (new CacheService)->clearCache();

        return response()->json($category, 201);
    }

    public function show(Category $category,  $uuid)
    {
        $category = Category::where('uuid', '=', $uuid)->firstOrFail();
        return response()->json($category, 200);
    }

    public function update(Request $request,  $uuid)
    {
        $ext_request = [];
        if($request->get('parent')){
            $parent = Category::where('uuid', $request->get('parent'))->first();
            if(!empty($parent))
                $ext_request['parent_id'] = $parent->id;
        }
        $category = Category::where('uuid', '=', $uuid)->firstOrFail();
        $category->update(array_merge($request->all(),$ext_request));
//        (new CacheService)->clearCache();

        return response()->json($category, 200);
    }

    public function delete($uuid)
    {
        (new CategoryService)->removeCategory($uuid);

        return response()->json(null, 204);
    }
}
