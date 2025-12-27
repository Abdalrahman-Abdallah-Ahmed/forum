<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return inertia('Posts/Index', [
            'posts' => PostResource::collection(Post::with('user')->latest()->latest('id')->paginate())
        ]);
    }

    public function show(Post $post)
    {
        // dd(CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(5)));
        $post->load('user');

        return inertia('Posts/Show', [
            'post' => fn() => PostResource::make($post),
            'comments' => fn() => CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(5))
        ]);
    }

    public function store(Request $request){

        // dd($request->all());
        $data = $request->validate([
            'title' => 'required|string|min:10|max:120',
            'body' => 'required|string|min:100|max:10000',
        ]);
        $post = Post::create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);
        
        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    public function create(){
        return inertia('Posts/Create');
    }
}
