<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TopicSeeder::class);
        $topics = Topic::all();
        $users = User::factory(10)->create();

        $posts = Post::factory(200)
            ->withFixture()
            ->has(Comment::factory(15)->recycle($users))
            ->recycle([$users, $topics])
            ->create();

        $abdo = User::factory()
            ->has(Post::factory(5)->recycle($topics)
            ->has(Comment::factory(15)->recycle($posts))
            )
            ->create([
                'name' => 'Abdo',
                'email' => 'aboodabdallah38@gmail.com',
            ]);

        $posts->take(100)->each(function ($post) use ($abdo) {
            Like::factory()
            ->forPost($post)
            ->create([
                'user_id' => $abdo->id,
                ]);
        });
    }
}
