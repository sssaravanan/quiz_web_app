<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'GK',
            'Science',
            'Mathematics',
            'History',
            'Technology',
            'Sports',
        ];

        foreach ($categories as $categoryName) {
            Category::updateOrCreate(
                ['name' => $categoryName],
                ['name' => $categoryName]
            );
        }
    }
}
