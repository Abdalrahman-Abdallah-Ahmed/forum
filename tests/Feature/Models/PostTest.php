<?php 

use App\Models\Post;

it('uses title case for the titles', function(){
    $post = Post::factory()->create([
        'title' => 'title case test',
    ]);

    expect($post->title)->toBe('Title Case Test');
})->only();