<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use function Pest\Laravel\actingAs;


it('requires authentication', function () {
    $this->put(route('comments.update', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can update a comment', function () {
    $comment = Comment::factory()->create(['body' => 'Old body']);
    $newBody = 'Updated body';

    actingAs($comment->user)
        ->put(
            route('comments.update', $comment),
            ['body' => $newBody]
        );

    $this->assertDatabaseHas(Comment::class, [
        'id' => $comment->id,
        'body' => $newBody,
    ]);

});

it('redirects to the post show page ', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => 'Updated body'])
        ->assertRedirect($comment->post->showRoute());
});

it('redirects to the correct page of comment', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment, 'page' => 2]), ['body' => 'Updated body'])
        ->assertRedirect($comment->post->showRoute(['page' => 2]));
});


it('cannot update a comment from another user', function () {
    $comment = Comment::factory()->create();

    actingAs(User::factory()->create())
        ->put(route('comments.update', $comment), ['body' => 'Updated body'])
        ->assertForbidden();
});

it('requires a valid body', function ($value) {
    $comment = Comment::factory()->create();

    actingAs($comment->user)->put(route('comments.update', $comment), ['body' => $value,])
        ->assertInvalid(['body']);

})
    ->with([
        null,
        1,
        2.5,
        true,
        str_repeat('a', 2501),
    ])
    ;