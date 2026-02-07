<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use Illuminate\Database\Eloquent\Model;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;


it ('requires authentication', function(Model $likeable){
    post(route('likes.store', [$likeable->getMorphClass(), $likeable->id]))->assertRedirect(route('login'));
})->with([
    fn()=> Post::factory()->create()
]
);;

it ('it allows to like a likeable', function(Model $likeable){
    $user = User::factory()->create();
    
    actingAs($user)
    ->fromRoute('dashboard')
    ->post(route('likes.store', [$likeable->getMorphClass(), $likeable->id]))
    ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas(Like::class, [
        'user_id' => $user->id,
        'likeable_id'=> $likeable->id,
        'likeable_type'=> $likeable->getMorphClass(),
    ]);

    expect($likeable->refresh()->likes_count)->toBe(1);
})->with([
    fn()=> Post::factory()->create(),
    fn()=> Comment::factory()->create()
]
);

it('prevents liking what already liked', function(){
    $like = Like::factory()->create();

    $likeable = $like->likeable;

    actingAs($like->user)
    ->post(route('likes.store', [$likeable->getMorphClass(), $likeable->id]))
    ->assertForbidden();
});

it('only allows likeing supported models', function(){
    $user = User::factory()->create();
    
    actingAs($user)
    ->post(route('likes.store', [$user->getMorphClass(), $user->id]))
    ->assertForbidden();
});

it('throughs 404 error if the type is unsupported',function(){
    actingAs(User::factory()->create())
    ->post(route('likes.store', ['foo',1]))
    ->assertNotFound();
});