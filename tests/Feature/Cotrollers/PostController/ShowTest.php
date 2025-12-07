<?php

use App\Http\Resources\PostResource;
use App\Models\Post;
use Inertia\Testing\AssertableInertia;

it('can show a post', function () {
    $post = Post::factory()->create();

    $response = $this->get(route('posts.show', $post));

    $response->assertComponent('Posts/Show');
});

it('passes a post to the view', function(){
    $post = Post::factory()->create();

    $post->load('user');

    $response = $this->get(route('posts.show', $post));

    $response->assertHasResource('post', PostResource::make($post));
})->only();