<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\actingAs;

it('requires authentication', function () {
    $this->post(route('posts.comments.store', Post::factory()->create()))
        ->assertRedirect(route('login'));
})->only();

it('can store a comment', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    actingAs($user)->post(route('posts.comments.store', $post), [
        'body' => 'This is a comment.',
    ]);

    $this->assertDatabaseHas(Comment::class, [
        'body' => 'This is a comment.',
        'post_id' => $post->id,
        'user_id' => $user->id,
    ]);
});

it('redirects back to post page', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $response = actingAs($user)->post(route('posts.comments.store', $post), [
        'body' => 'This is a comment.',
    ]);

    $response->assertRedirect(route('posts.show', $post));
});

it('requires a valid body', function ($value) {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $response = actingAs($user)->post(route('posts.comments.store', $post), [
        'body' => $value,
    ]);

    $response->assertInvalid(['body']);
})->with([
            null,
            1,
            2.5,
            true,
            str_repeat('a', 2501),
        ]);