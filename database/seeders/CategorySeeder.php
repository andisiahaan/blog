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
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Latest news and articles about technology, gadgets, and software.',
            ],
            [
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'description' => 'Tips and inspiration for a better lifestyle.',
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business news, entrepreneurship, and career advice.',
            ],
            [
                'name' => 'Health',
                'slug' => 'health',
                'description' => 'Health tips, wellness advice, and medical news.',
            ],
            [
                'name' => 'Travel',
                'slug' => 'travel',
                'description' => 'Travel guides, destination reviews, and travel tips.',
            ],
            [
                'name' => 'Food',
                'slug' => 'food',
                'description' => 'Recipes, restaurant reviews, and culinary inspiration.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
