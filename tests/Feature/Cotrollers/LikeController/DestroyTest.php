<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use Illuminate\Database\Eloquent\Model;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\post;


it ('requires authentication', function(Model $likeable){
    delete(route('likes.destroy', [$likeable->getMorphClass(), $likeable->id]))->assertRedirect(route('login'));
})->with([
    fn()=> Post::factory()->create()
]
);;

it ('it allows to unlike a likeable', function(Model $likeable){
    $this->withoutExceptionHandling();
    $user = User::factory()->create();
    $like = Like::factory()->for($user)->for($likeable, 'likeable')->create();

    actingAs($user)
    ->fromRoute('dashboard')
    ->delete(route('likes.destroy', [$likeable->getMorphClass(), $likeable->id]))
    ->assertRedirect(route('dashboard'));

    $this->assertDatabaseEmpty(Like::class);

    expect($likeable->refresh()->likes_count)->toBe(0);
})->with([
    fn()=> Post::factory()->create(['likes_count'=>1]),
    fn()=> Comment::factory()->create(['likes_count'=>1])
]
);

it('prevents unliking what havent liked', function(){
    $likeable = Post::factory()->create();

    actingAs(User::factory()->create())
    ->delete(route('likes.destroy', [$likeable->getMorphClass(), $likeable->id]))
    ->assertForbidden();
});

it('only allows unlikeing supported models', function(){
    $user = User::factory()->create();
    
    actingAs($user)
    ->delete(route('likes.destroy', [$user->getMorphClass(), $user->id]))
    ->assertForbidden();
});

it('throughs 404 error if the type is unsupported',function(){
    actingAs(User::factory()->create())
    ->delete(route('likes.destroy', ['foo',1]))
    ->assertNotFound();
});