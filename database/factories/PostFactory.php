<?php

namespace Database\Factories;

use App\Models\Topic;
use App\Models\User;
use App\Support\PostFixtures;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private static Collection $fixtures;
    public function definition(): array
    {
        return [
            'user_id'=> User::factory(),
            'topic_id'=>Topic::factory(),
            'title'=> str(fake()->sentence())->beforeLast('.')->title(),
            'body'=> Collection::times(4 , fn ()=>fake()->realText(1250))->join(PHP_EOL.PHP_EOL),
        ];
    }

    public function withFixture(){

        return $this->sequence(...PostFixtures::getFixtures());
    }
}
