<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        $posts = Post::factory(200)
            ->withFixture()
            ->has(Comment::factory(15)->recycle($users))
            ->recycle($users)
            ->create();

        User::factory()
            ->has(
                Post::factory(5)->has(Comment::factory(15)->recycle($posts))
            )
            ->create([
                'name' => 'Abdo',
                'email' => 'aboodabdallah38@gmail.com',
            ]);
    }
}
