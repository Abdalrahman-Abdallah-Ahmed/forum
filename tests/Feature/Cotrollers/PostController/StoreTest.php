<?php

use App\Models\Post;
use App\Models\User;
use Pest\Plugins\Only;
use function Pest\Laravel\actingAs;


beforeEach(function(){
    
    $this->validData = [
        'title' => 'My First Post',
        'body' => 'This is the body of my first post This is the body of my first post. This is the body of my first post..',
    ];
});

it('requires authentication', function () {
    $this->post(route('posts.store'))
        ->assertRedirect(route('login'));
});

it('stores a post', function(){
    $user = User::factory()->create(); 

    actingAs($user)
        ->post(route('posts.store'), $this->validData);

    $this->assertDatabaseHas(Post::class, [
        ...$this->validData,
        'user_id' => $user->id,
    ]);
});

it('it redirects to post.show', function(){ 

    actingAs(User::factory()->create())
        ->post(route('posts.store'), $this->validData)
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('requires a valid data', function(array $badData, array|string $errors){ 

    actingAs(User::factory()->create())
        ->post(route('posts.store'), [...$this->validData, ...$badData])
        ->assertInvalid($errors);
})->with([
    [['title' => null], 'title'],
    [['title' => 123], 'title'],
    [['title' => true], 'title'],
    [['title' => 1.5], 'title'],
    [['title' => str_repeat('a', 121)], 'title'],
    [['title' => str_repeat('a', 9)], 'title'],
    [['body' => null], 'body'],
    [['body' => 123], 'body'],
    [['body' => true], 'body'],
    [['body' => 1.5], 'body'],
    [['body' => str_repeat('a', 10_001)], 'body'],
    [['body' => str_repeat('a', 99)], 'body']
    ]);

