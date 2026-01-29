<?php
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Models\User;
use function Pest\Laravel\actingAs;

it('requires authentication', function () {
    $this->get(route('posts.create'))
        ->assertRedirect(route('login'));
});

it('return the correct component', function(){
    actingAs(User::factory()->create())
    ->get(route('posts.create'))
    ->assertComponent('Posts/Create');
}); 

it('it passes topics to the view', function(){
    $topics = Topic::factory(2)->create();

    actingAs(User::factory()->create())
    ->get(route('posts.create'))
    ->assertHasResource('topics', TopicResource::collection($topics));

});