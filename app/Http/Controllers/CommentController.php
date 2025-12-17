<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;
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

        return redirect()->route('posts.show', $post);
    }

    public function destroy(Request $request, Comment $comment)
    {   
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()->route('posts.show', ['post' => $comment->post, 'page' => $request->query('page')]);
    }
}
