<?php

use App\Models\Post;
use App\Models\User;
use Pest\Plugins\Only;
use function Pest\Laravel\actingAs;

it('requires authentication', function () {
    $this->post(route('posts.store'))
        ->assertRedirect(route('login'));
});

it('stores a post', function(){
    $user = User::factory()->create(); 

    $data = [
        'title' => 'My First Post',
        'body' => 'This is the body of my first post.',
    ];

    actingAs($user)
        ->post(route('posts.store'), $data);

    $this->assertDatabaseHas(Post::class, [
        ...$data,
        'user_id' => $user->id,
    ]);
});

it('it redirects to post.show', function(){
    $user = User::factory()->create(); 

    $data = [
        'title' => 'My First Post',
        'body' => 'This is the body of my first post.',
    ];

    actingAs($user)
        ->post(route('posts.store'), $data)
        ->assertRedirect(route('posts.show', Post::latest('id')->first()));
});