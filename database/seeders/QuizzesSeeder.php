<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizzesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizzes = [
            [
                'title' => 'General Knowledge Basics',
                'category' => 'GK',
                'difficulty' => 'easy',
                'time_limit' => 15,
                'is_published' => true,
            ],
            [
                'title' => 'Everyday Science Challenge',
                'category' => 'Science',
                'difficulty' => 'medium',
                'time_limit' => 20,
                'is_published' => true,
            ],
            [
                'title' => 'Mathematics Mastery',
                'category' => 'Mathematics',
                'difficulty' => 'medium',
                'time_limit' => 25,
                'is_published' => true,
            ],
            [
                'title' => 'World History Essentials',
                'category' => 'History',
                'difficulty' => 'medium',
                'time_limit' => 20,
                'is_published' => true,
            ],
            [
                'title' => 'Modern Technology Trends',
                'category' => 'Technology',
                'difficulty' => 'hard',
                'time_limit' => 25,
                'is_published' => true,
            ],
            [
                'title' => 'Sports Trivia Arena',
                'category' => 'Sports',
                'difficulty' => 'easy',
                'time_limit' => 15,
                'is_published' => true,
            ],
        ];

        foreach ($quizzes as $quizData) {
            $category = Category::where('name', $quizData['category'])->firstOrFail();

            Quiz::updateOrCreate(
                ['title' => $quizData['title']],
                [
                    'category_id' => $category->id,
                    'difficulty' => $quizData['difficulty'],
                    'time_limit' => $quizData['time_limit'],
                    'is_published' => $quizData['is_published'],
                    'created_by' => 1, // Assuming admin user with ID 1 is the creator
                ]
            );
        }
    }
}
