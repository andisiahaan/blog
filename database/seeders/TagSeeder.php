<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Laravel', 'slug' => 'laravel', 'description' => 'Posts about Laravel PHP framework'],
            ['name' => 'PHP', 'slug' => 'php', 'description' => 'PHP programming language topics'],
            ['name' => 'JavaScript', 'slug' => 'javascript', 'description' => 'JavaScript and frontend development'],
            ['name' => 'Tutorial', 'slug' => 'tutorial', 'description' => 'Step-by-step tutorials and guides'],
            ['name' => 'Tips', 'slug' => 'tips', 'description' => 'Quick tips and tricks'],
            ['name' => 'News', 'slug' => 'news', 'description' => 'Latest news and updates'],
            ['name' => 'Review', 'slug' => 'review', 'description' => 'Product and service reviews'],
            ['name' => 'Guide', 'slug' => 'guide', 'description' => 'Comprehensive guides'],
            ['name' => 'Beginner', 'slug' => 'beginner', 'description' => 'Content for beginners'],
            ['name' => 'Advanced', 'slug' => 'advanced', 'description' => 'Advanced topics'],
            ['name' => 'Web Development', 'slug' => 'web-development', 'description' => 'Web development topics'],
            ['name' => 'Mobile', 'slug' => 'mobile', 'description' => 'Mobile development and apps'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
