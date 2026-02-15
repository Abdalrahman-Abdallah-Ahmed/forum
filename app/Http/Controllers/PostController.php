<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class);
    }
    public function index(Request $request, Topic $topic = null)
    {
        $posts = Post::with(['user','topic'])
            ->when($topic, fn (Builder $query) => $query->whereBelongsTo($topic))
            ->when($request->query(), fn(Builder $query) => $query
                ->whereAny(['title','body'], 'like', '%'.$request->query('query').'%'))
            ->latest()
            ->latest('id')
            ->paginate()
            ->withQueryString();
        return inertia('Posts/Index', [
            'posts' => PostResource::collection($posts),
            'topics' => fn () => TopicResource::collection(Topic::all()),
            'selectedTopic' => fn () => $topic ? TopicResource::make($topic) :null,
            'query' => $request->query('query'),
        ]);
    }

    public function show(Request $request, Post $post)
    {
        if(!Str::endsWith($post->showRoute(), $request->path())){
            return redirect($post->showRoute($request->query()), 301);
        }
        // dd(CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(5)));
        $post->load('user', 'topic');

        return inertia('Posts/Show', [
            'post' => fn() => PostResource::make($post)->withLikePermision(),
            'comments' => function() use ($post){
                    $commentResource = CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(5));
                    $commentResource->collection->transform(fn($resource)=> $resource->withLikePermision());
                    return $commentResource;
                }
    ]);
    }

    public function store(Request $request){

        // dd($request->all());
        $data = $request->validate([
            'title' => 'required|string|min:10|max:120',
            'topic_id' => 'required|exists:topics,id',
            'body' => 'required|string|min:100|max:10000',
        ]);
        $post = Post::create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);
        
        return redirect($post->showRoute());
    }

    public function create(){
        return inertia('Posts/Create', [
            'topics' => fn () => TopicResource::collection(Topic::all()),
        ]);
    }
}
