<?php

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Pest\Plugins\Only;
use function Pest\Laravel\actingAs;


beforeEach(function(){
    
    $this->validData = [
        'title' => 'My First Post',
        'topic_id' => Topic::factory()->create()->getKey(),
        'body' => 'This is the body of my first post This is the body of my first post. This is the body of my first post..',
    ];
});

it('requires authentication', function () {
    $this->post(route('posts.store'))
        ->assertRedirect(route('login'));
});

it('stores a post', function(){
    $user = User::factory()->create();
    $data = value($this->validData);

    actingAs($user)
        ->post(route('posts.store'), $data);

    $this->assertDatabaseHas(Post::class, [
        ...$data,
        'user_id' => $user->id,
    ]);
});

it('it redirects to post.show', function(){ 

    actingAs(User::factory()->create())
        ->post(route('posts.store'), value($this->validData))
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('requires a valid data', function(array $badData, array|string $errors){ 

    actingAs(User::factory()->create())
        ->post(route('posts.store'), [...value($this->validData), ...$badData])
        ->assertInvalid($errors);
})->with([
    [['title' => null], 'title'],
    [['title' => 123], 'title'],
    [['title' => true], 'title'],
    [['title' => 1.5], 'title'],
    [['title' => str_repeat('a', 121)], 'title'],
    [['title' => str_repeat('a', 9)], 'title'],
    [['topic_id' => null], 'topic_id'],
    [['topic_id' => -1], 'topic_id'],
    [['body' => null], 'body'],
    [['body' => 123], 'body'],
    [['body' => true], 'body'],
    [['body' => 1.5], 'body'],
    [['body' => str_repeat('a', 10_001)], 'body'],
    [['body' => str_repeat('a', 99)], 'body']
    ]);

