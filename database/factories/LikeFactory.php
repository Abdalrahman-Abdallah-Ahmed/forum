<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(['email'=> 'test+'.Str::uuid().'@example.com']),
        ];
    }

    public function forPost(Post $post)
    {
        return $this->state(fn () => [
            'likeable_id'   => $post->id,
            'likeable_type' => $post->getMorphClass(),
        ]);
    }
}
