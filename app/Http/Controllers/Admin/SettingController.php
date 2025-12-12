<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Show general settings.
     */
    public function general()
    {
        $settings = Setting::getByGroup('general');
        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        // Text fields
        $textFields = [
            'site_name' => 'text',
            'site_description' => 'textarea',
            'site_keywords' => 'text',
            'site_email' => 'text',
            'footer_text' => 'textarea',
            'google_analytics' => 'textarea',
        ];

        foreach ($textFields as $key => $type) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key), 'general', $type);
            }
        }

        // Integer fields (display settings)
        $integerFields = [
            'posts_per_page',
            'related_posts_limit',
            'sidebar_popular_limit',
            'sidebar_random_limit',
            'sidebar_categories_limit',
            'sidebar_tags_limit',
        ];

        foreach ($integerFields as $key) {
            if ($request->has($key)) {
                Setting::set($key, (int) $request->input($key), 'general', 'integer');
            }
        }

        // Boolean fields (sidebar toggles)
        $booleanFields = [
            'sidebar_popular_enabled',
            'sidebar_random_enabled',
            'sidebar_categories_enabled',
            'sidebar_tags_enabled',
        ];

        foreach ($booleanFields as $key) {
            Setting::set($key, $request->boolean($key) ? '1' : '0', 'general', 'boolean');
        }

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $path, 'general', 'image');
        }

        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            $path = $request->file('site_favicon')->store('settings', 'public');
            Setting::set('site_favicon', $path, 'general', 'image');
        }

        return redirect()->route('admin.settings.general')
            ->with('success', __('admin.settings_updated'));
    }


    /**
     * Show custom tags settings.
     */
    public function customTags()
    {
        $settings = Setting::getByGroup('custom_tags');
        return view('admin.settings.custom-tags', compact('settings'));
    }

    /**
     * Update custom tags settings.
     */
    public function updateCustomTags(Request $request)
    {
        $tagSlots = [
            'head_tag' => 'textarea',              // Inside <head>
            'body_end_tag' => 'textarea',          // Before </body>
            'header' => 'textarea',                // Below navigation
            'sidebar_top' => 'textarea',           // Sidebar top
            'sidebar_bottom' => 'textarea',        // Sidebar bottom
            'post_before_content' => 'textarea',   // Before post content
            'post_in_content' => 'textarea',       // In middle of post
            'post_after_content' => 'textarea',    // After post content
            'between_posts' => 'textarea',         // Between post listings
            'footer' => 'textarea',                // Above footer
            'popup' => 'textarea',                 // Popup
        ];

        foreach ($tagSlots as $key => $type) {
            Setting::set($key, $request->input($key, ''), 'custom_tags', $type);
        }

        return redirect()->route('admin.settings.custom-tags')
            ->with('success', __('admin.settings_updated'));
    }
}

