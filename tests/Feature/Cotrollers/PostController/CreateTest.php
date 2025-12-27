<?php
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