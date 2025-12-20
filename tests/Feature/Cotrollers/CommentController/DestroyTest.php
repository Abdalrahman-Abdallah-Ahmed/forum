<?php

use App\Models\Comment;
use App\Models\User;

it('requires authentication', function () {
    $this->delete(route('comments.destroy', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can delete a comment', function () {
    $comment = Comment::factory()->create();

    $this
        ->actingAs($comment->user)
        ->delete(route('comments.destroy', $comment));
    $this
        ->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

it('redirects to post page', function () {
    $comment = Comment::factory()->create();

    $response = $this
        ->actingAs($comment->user)
        ->delete(route('comments.destroy', $comment));

    $response->assertRedirect(route('posts.show', $comment->post));
});

it('prevents unauthorized deletion', function () {
    $comment = Comment::factory()->create();

    $response = $this
        ->actingAs(User::factory()->create())
        ->delete(route('comments.destroy', $comment->id));

    $response->assertForbidden();
});

it('prevents deleting a comment from one hour ago', function () {
    $this->freezeTime();

    $comment = Comment::factory()->create();

    $this->travel(1)->hour();

    $response = $this
        ->actingAs($comment->user)
        ->delete(route('comments.destroy', $comment->id));

    $response->assertForbidden();
});

it('redirects to post page with the page query parameter', function () {
    $comment = Comment::factory()->create();

    $response = $this
        ->actingAs($comment->user)
        ->delete(route('comments.destroy', ['comment' => $comment, 'page' => 3]));

    $response->assertRedirect(route('posts.show', ['post' => $comment->post, 'page' => 3]));
});