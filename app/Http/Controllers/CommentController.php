<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Comment::class);
    }
    public function store(Request $request, Post $post)
    {
        // dd($request->all());
        $validated = $request->validate([
            'body' => 'required|string|max:2500',
        ]);

        Comment::make($validated)
            ->user()->associate(($request->user()))
            ->post()->associate($post)
            ->save();

        return redirect()->route('posts.show', $post)->banner('Comment created successfully.');
        // below is the same thing as above
        // return redirect()->route('posts.show', $post)->with('flash', ['bannerStyle' => 'success', 'banner'=>'Comment added']);
    }

    public function destroy(Request $request, Comment $comment)
    {
        $comment->delete();

        return redirect()->route('posts.show', ['post' => $comment->post, 'page' => $request->query('page')])->banner('Comment deleted successfully.');
    }

    public function update(Request $request, Comment $comment)
    {
        $date = $request->validate(([
            'body' => 'required|string|max:2500',
        ]));

        $comment->update($date);
        return redirect()->route('posts.show', ['post' => $comment->post, 'page' => $request->query('page')])->banner('Comment updated successfully.');
    }
}
