<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PublishScheduledPostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_publishes_scheduled_posts_that_are_due()
    {
        $user = User::factory()->create();
        
        // Create a scheduled post that is due
        $duePost = Post::create([
            'user_id' => $user->id,
            'title' => 'Due Post',
            'slug' => 'due-post',
            'content' => 'Content',
            'status' => 'scheduled',
            'published_at' => now()->subMinute(),
        ]);

        // Create a scheduled post that is NOT due
        $futurePost = Post::create([
            'user_id' => $user->id,
            'title' => 'Future Post',
            'slug' => 'future-post',
            'content' => 'Content',
            'status' => 'scheduled',
            'published_at' => now()->addMinute(),
        ]);

        // Create a draft post (should not be touched)
        $draftPost = Post::create([
            'user_id' => $user->id,
            'title' => 'Draft Post',
            'slug' => 'draft-post',
            'content' => 'Content',
            'status' => 'draft',
            'published_at' => now()->subMinute(),
        ]);

        // Run the command
        Artisan::call('posts:publish-scheduled');

        // Assertions
        $this->assertEquals('published', $duePost->fresh()->status);
        $this->assertEquals('scheduled', $futurePost->fresh()->status);
        $this->assertEquals('draft', $draftPost->fresh()->status);
    }
}
