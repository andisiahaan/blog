<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $categories = Category::all();
        $tags = Tag::all();

        $posts = [
            [
                'title' => 'Getting Started with Laravel 12',
                'slug' => 'getting-started-with-laravel-12',
                'excerpt' => 'Learn the basics of Laravel 12 and how to set up your first project with this comprehensive guide.',
                'content' => '<p>Laravel 12 is the latest version of the popular PHP framework that makes web development a breeze. In this guide, we\'ll walk through the installation process and basic concepts.</p>
<h2>Installation</h2>
<p>To get started with Laravel 12, you\'ll need to have PHP 8.2 or higher and Composer installed on your system.</p>
<pre><code>composer create-project laravel/laravel my-project</code></pre>
<h2>Directory Structure</h2>
<p>Laravel follows the MVC (Model-View-Controller) pattern. The main directories you\'ll work with are:</p>
<ul>
<li><strong>app/</strong> - Contains your application code</li>
<li><strong>resources/views/</strong> - Contains your Blade templates</li>
<li><strong>routes/</strong> - Contains your route definitions</li>
<li><strong>database/</strong> - Contains migrations and seeders</li>
</ul>
<h2>Conclusion</h2>
<p>Laravel 12 provides an elegant syntax and powerful features that make PHP development enjoyable. Start building your next project today!</p>',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'views' => 1250,
                'categories' => [1], // Technology
                'tags' => [1, 2, 4, 9], // Laravel, PHP, Tutorial, Beginner
            ],
            [
                'title' => 'Top 10 JavaScript Frameworks in 2024',
                'slug' => 'top-10-javascript-frameworks-2024',
                'excerpt' => 'Discover the most popular JavaScript frameworks that are shaping modern web development.',
                'content' => '<p>JavaScript continues to dominate the web development landscape. Here are the top frameworks you should consider for your next project.</p>
<h2>1. React</h2>
<p>Developed by Facebook, React remains the most popular choice for building user interfaces.</p>
<h2>2. Vue.js</h2>
<p>Vue offers a progressive approach to building web interfaces with excellent documentation.</p>
<h2>3. Angular</h2>
<p>Google\'s Angular is a comprehensive framework for enterprise applications.</p>
<h2>4. Next.js</h2>
<p>Built on React, Next.js provides server-side rendering and static site generation.</p>
<h2>5. Svelte</h2>
<p>Svelte compiles your code at build time, resulting in smaller bundle sizes.</p>
<p>Each framework has its strengths. Choose based on your project requirements and team expertise.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'views' => 890,
                'categories' => [1], // Technology
                'tags' => [3, 6, 11], // JavaScript, News, Web Development
            ],
            [
                'title' => '5 Healthy Habits for Remote Workers',
                'slug' => '5-healthy-habits-remote-workers',
                'excerpt' => 'Working from home? Here are essential habits to maintain your physical and mental health.',
                'content' => '<p>Remote work has become the norm for many professionals. While it offers flexibility, it also comes with unique challenges for our health.</p>
<h2>1. Establish a Morning Routine</h2>
<p>Start your day with intention. Wake up at a consistent time and include exercise or meditation.</p>
<h2>2. Create an Ergonomic Workspace</h2>
<p>Invest in a good chair and desk setup. Your back will thank you.</p>
<h2>3. Take Regular Breaks</h2>
<p>Use the Pomodoro technique or simply set reminders to stand up and stretch.</p>
<h2>4. Stay Connected</h2>
<p>Schedule virtual coffee chats with colleagues to maintain social connections.</p>
<h2>5. Set Boundaries</h2>
<p>Define clear work hours and stick to them. Work-life balance is crucial.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'views' => 650,
                'categories' => [2, 4], // Lifestyle, Health
                'tags' => [5, 8], // Tips, Guide
            ],
            [
                'title' => 'Building a Successful Startup: Lessons Learned',
                'slug' => 'building-successful-startup-lessons',
                'excerpt' => 'Key insights from founders who have built successful companies from the ground up.',
                'content' => '<p>Starting a business is one of the most challenging yet rewarding journeys you can embark on. Here are lessons from successful founders.</p>
<h2>Start with a Problem</h2>
<p>The best startups solve real problems. Validate your idea before building.</p>
<h2>Build a Strong Team</h2>
<p>Your team is your greatest asset. Hire people who share your vision and complement your skills.</p>
<h2>Focus on Customers</h2>
<p>Listen to your customers. Their feedback is invaluable for product development.</p>
<h2>Embrace Failure</h2>
<p>Failure is part of the journey. Learn from mistakes and iterate quickly.</p>
<h2>Manage Cash Flow</h2>
<p>Many startups fail due to poor financial management. Keep a close eye on your runway.</p>',
                'status' => 'published',
                'published_at' => now()->subDay(),
                'views' => 420,
                'categories' => [3], // Business
                'tags' => [5, 8], // Tips, Guide
            ],
            [
                'title' => 'Exploring the Hidden Gems of Bali',
                'slug' => 'exploring-hidden-gems-bali',
                'excerpt' => 'Discover lesser-known destinations in Bali that offer authentic experiences away from the crowds.',
                'content' => '<p>Bali is known for its beaches and temples, but there\'s so much more to discover beyond the tourist hotspots.</p>
<h2>Sidemen Valley</h2>
<p>Experience traditional Balinese life surrounded by stunning rice terraces and Mount Agung views.</p>
<h2>Munduk</h2>
<p>A highland village known for its waterfalls, coffee plantations, and cooler climate.</p>
<h2>Nusa Penida</h2>
<p>An island paradise with dramatic cliffs, crystal-clear waters, and incredible diving spots.</p>
<h2>Amed</h2>
<p>A quiet fishing village perfect for snorkeling, diving, and watching sunrise over Mount Agung.</p>
<h2>Travel Tips</h2>
<p>Rent a scooter to explore these areas at your own pace. Best time to visit is during the dry season (April-October).</p>',
                'status' => 'published',
                'published_at' => now(),
                'views' => 180,
                'categories' => [5], // Travel
                'tags' => [8, 7], // Guide, Review
            ],
            [
                'title' => 'Introduction to API Development with Laravel',
                'slug' => 'introduction-api-development-laravel',
                'excerpt' => 'Learn how to build RESTful APIs using Laravel\'s powerful features and best practices.',
                'content' => '<p>APIs are the backbone of modern applications. Laravel makes it easy to build robust APIs.</p>
<h2>Setting Up API Routes</h2>
<p>Laravel provides a dedicated routes file for API routes at routes/api.php.</p>
<h2>Resource Controllers</h2>
<p>Use resource controllers to handle CRUD operations with consistent naming conventions.</p>
<h2>API Resources</h2>
<p>Transform your models into JSON responses with API Resources for clean, consistent output.</p>
<h2>Authentication</h2>
<p>Use Laravel Sanctum for simple token-based authentication in your APIs.</p>
<h2>Best Practices</h2>
<ul>
<li>Version your APIs</li>
<li>Use proper HTTP status codes</li>
<li>Implement rate limiting</li>
<li>Document your endpoints</li>
</ul>',
                'status' => 'draft',
                'published_at' => null,
                'views' => 0,
                'categories' => [1], // Technology
                'tags' => [1, 2, 4, 10], // Laravel, PHP, Tutorial, Advanced
            ],
        ];

        foreach ($posts as $postData) {
            $categoryIds = $postData['categories'];
            $tagIds = $postData['tags'];
            unset($postData['categories'], $postData['tags']);

            $postData['user_id'] = $admin->id;
            $post = Post::create($postData);

            // Attach categories
            $post->categories()->attach($categoryIds);
            
            // Attach tags
            $post->tags()->attach($tagIds);

            // Add SEO metas
            $post->setMeta('meta_title', $post->title);
            $post->setMeta('meta_description', $post->excerpt);
        }
    }
}
