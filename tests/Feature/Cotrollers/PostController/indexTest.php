<?php

use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Inertia\Testing\AssertableInertia;
use Pest\Plugins\Only;

it('should return the correct component', function () {
    $this->get(route('posts.index'))
        ->assertComponent('Posts/Index');
});

it('passes posts to the view', function () {
    $posts = Post::factory(3)->create();

    $posts->load(['user', 'topic']);

    $this->get(route('posts.index'))
        ->assertHasPaginatedResource('posts', PostResource::Collection($posts->reverse()));
});

it('passes topics to the view' , function(){
    $topics = Topic::factory(3)->create();

    $this->get(route('posts.index'))
     ->assertHasResource('topics', TopicResource::Collection($topics));
});

it('it can filter to a topic', function () {

    $general = Topic::factory()->create();
    $posts = Post::factory(2)->for($general)->create();
    $otherPosts = Post::factory(3)->create();

    $posts->load(['user', 'topic']);

    $this->get(route('posts.index', ['topic'=>$general]))
        ->assertHasPaginatedResource('posts', PostResource::Collection($posts->reverse()));
});

it('passes the selected topic to the view', function () {

    $topic = Topic::factory()->create();

    $this->get(route('posts.index', ['topic'=>$topic]))
        ->assertHasResource('selectedTopic', TopicResource::make($topic));
});
 