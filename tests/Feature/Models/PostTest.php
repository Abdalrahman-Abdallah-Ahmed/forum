<?php

use App\Models\Post;

it('uses title case for the titles', function () {
    $post = Post::factory()->create([
        'title' => 'title case test',
    ]);

    expect($post->title)->toBe('Title Case Test');
});

it('can generate a route to the show page', function () {
    $post = Post::factory()->create();

    expect($post->showRoute())->toBe(route('posts.show', [$post, \Str::slug($post->title)]));
});

it('can generate additional query parameters on the show route', function () {
    $post = Post::factory()->create();

    expect($post->showRoute(['page' => 2]))->toBe(route('posts.show', [$post, \Str::slug($post->title), 'page' => 2]));

});

it('generates html from markdown body', function(){
    $post = Post::factory()->create(['body' => "# Hello World"]);
    expect($post->html)->toEqual(str($post->body)->markdown());
})->only();