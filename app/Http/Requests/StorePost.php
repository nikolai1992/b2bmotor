<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\StorePost as StorePostRequest;
use Auth;

class StorePost extends FormRequest
{
    public function authorize()
    {
        return true; // gate will be responsible for access
    }

    public function rules()
    {
        return [
            'title' => 'required|unique:posts',
            'body' => 'required',
        ];
    }

    public function store(StorePostRequest $request)
    {
//        $data = $request->only('title', 'body');
//        $data['slug'] = str_slug($data['title']);
//        $data['user_id'] = Auth::user()->id;
//        $post = Post::create($data);
//        return redirect()->route('edit_post', ['id' => $post->id]);
    }



}
