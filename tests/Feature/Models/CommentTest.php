<?php

use App\Models\Comment;

it('generates html from markdown body', function(){
    $comment = Comment::factory()->create(['body' => "# Hello World"]);
    expect($comment->html)->toEqual(str($comment->body)->markdown());
});