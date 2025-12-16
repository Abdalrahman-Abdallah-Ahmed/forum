<?php 

use App\Models\Comment;
use App\Models\User;

it('requires authentication', function () {
    $this->delete(route('comments.destroy', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can delete a comment', function () {
    $comment = Comment::factory()->create();

    $response = $this
    ->actingAs($comment->user)
    ->delete(route('comments.destroy', $comment));

    $response->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

it('redirects to post page', function(){
    $comment = Comment::factory()->create();

    $response = $this
    ->actingAs($comment->user)
    ->delete(route('comments.destroy', $comment));

    $response->assertRedirect(route('posts.show', $comment->post));
});

it('prevents unauthorized deletion', function(){
    $comment = Comment::factory()->create();

    $response = $this
    ->actingAs(User::factory()->create())
    ->delete(route('comments.destroy', $comment->id));

    $response->assertForbidden();
})->only();