<?php

namespace App\Http\Controllers;

use Gate;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePost as UpdatePostRequest;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::published()->paginate();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function drafts()
    {
        $postsQuery = Post::unpublished();
        if(Gate::denies('see-all-drafts')) {
            $postsQuery = $postsQuery->where('user_id', Auth::user()->id);
        }
        $posts = $postsQuery->paginate();
        return view('posts.drafts', compact('posts'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }
    public function update(Post $post, UpdatePostRequest $request)
    {
        $data = $request->only('title', 'body');
        $data['slug'] = str_slug($data['title']);
        $post->fill($data)->save();
        return back();
    }

    public function publish(Post $post)
    {
        $post->published = true;
        $post->save();
        return back();
    }

    public function show($id)
    {
        $post = Post::published()->findOrFail($id);
        return view('posts.show', compact('post'));
    }
}
