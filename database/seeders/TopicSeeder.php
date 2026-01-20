<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'slug' => 'general',
                'name' => 'General',
                'description' => 'General discussions, news, and topics related to films and movies.'
            ],
            [
                'slug' => 'reviews',
                'name' => 'Reviews',
                'description' => 'In-depth reviews, ratings, and opinions on movies and films.'
            ],
            [
                'slug' => 'questions',
                'name' => 'Questions',
                'description' => 'Ask questions and get answers about movies, films, and the film industry.'
            ],
            [
                'slug' => 'conspirecies',
                'name' => 'Conspirecies',
                'description' => 'Discuss movie-related conspiracies, hidden meanings, fan theories, and unexplained film mysteries.'
            ],
        ];

        Topic::upsert($data, ['slug']);
    }
}
