<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General Settings
        $generalSettings = [
            ['key' => 'site_name', 'value' => 'My Blog', 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_description', 'value' => 'A modern blog built with Laravel', 'group' => 'general', 'type' => 'textarea'],
            ['key' => 'site_keywords', 'value' => 'blog, laravel, technology, lifestyle', 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_email', 'value' => 'contact@blog.test', 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_logo', 'value' => '', 'group' => 'general', 'type' => 'image'],
            ['key' => 'site_favicon', 'value' => '', 'group' => 'general', 'type' => 'image'],
            ['key' => 'footer_text', 'value' => '© ' . date('Y') . ' My Blog. All rights reserved.', 'group' => 'general', 'type' => 'textarea'],
            ['key' => 'google_analytics', 'value' => '', 'group' => 'general', 'type' => 'textarea'],
        ];

        // Custom Tags Settings
        $customTagSettings = [
            ['key' => 'head_tag', 'value' => '', 'group' => 'custom_tags', 'type' => 'textarea'],
            ['key' => 'body_end_tag', 'value' => '', 'group' => 'custom_tags', 'type' => 'textarea'],
            ['key' => 'header', 'value' => '', 'group' => 'custom_tags', 'type' => 'textarea'],
            ['key' => 'sidebar_top', 'value' => '', 'group' => 'custom_tags', 'type' => 'textarea'],
            ['key' => 'sidebar_bottom', 'value' => '', 'group' => 'custom_tags', 'type' => 'textarea'],
            ['key' => 'post_before_content', 'value' => '', 'group' => 'custom_tags', 'type' => 'textarea'],
            ['key' => 'post_in_content', 'value' => '', 'group' => 'custom_tags', 'type' => 'textarea'],
            ['key' => 'post_after_content', 'value' => '', 'group' => 'custom_tags', 'type' => 'textarea'],
            ['key' => 'between_posts', 'value' => '', 'group' => 'custom_tags', 'type' => 'textarea'],
            ['key' => 'footer', 'value' => '', 'group' => 'custom_tags', 'type' => 'textarea'],
            ['key' => 'popup', 'value' => '', 'group' => 'custom_tags', 'type' => 'textarea'],
        ];

        foreach (array_merge($generalSettings, $customTagSettings) as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

