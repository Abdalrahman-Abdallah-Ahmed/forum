<?php

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;

it('can show a post', function () {
    $post = Post::factory()->create();

    $response = $this->get($post->showRoute());

    $response->assertComponent('Posts/Show');
});

it('passes a post to the view', function () {
    $post = Post::factory()->create();

    $post->load('user', 'topic');

    $response = $this->get($post->showRoute());

    $response->assertHasResource('post', PostResource::make($post));
});

it('passes comments to the view', function () {
    $post = Post::factory()->create();

    $comments = Comment::factory(2)->for($post)->create();

    $comments->load('user');

    $this->get($post->showRoute())
        ->assertHasPaginatedResource('comments', CommentResource::collection($comments->reverse()));
});

it('will redirect if the slug is incorrect', function (string $incorrectSlug) {
    $post = Post::factory()->create(['title' => 'hello world']);

    $this->get(route('posts.show', [$post, $incorrectSlug, 'page' => 2]))
        ->assertRedirect($post->showRoute(['page' => 2]));
})->with([
    'nothing',
    'hello'
]);
