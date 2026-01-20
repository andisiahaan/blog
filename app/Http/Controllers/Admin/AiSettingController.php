<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\Ai\AiContentService;
use App\Services\Ai\AiProviderFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiSettingController extends Controller
{
    public function __construct(
        protected AiProviderFactory $providerFactory,
        protected AiContentService $contentService
    ) {
    }

    /**
     * Display AI settings page (read-only from .env).
     */
    public function index(): View
    {
        $config = $this->providerFactory->getConfigurationInfo();
        $stats = $this->contentService->getStats();
        $providers = $this->providerFactory->getAvailableProviders();

        // Get thumbnail images from Settings (these can still be managed via admin)
        $thumbnailImages = Setting::get('thumbnail_images', []);
        if (is_string($thumbnailImages)) {
            $thumbnailImages = json_decode($thumbnailImages, true) ?? [];
        }

        return view('admin.ai.settings', compact('config', 'stats', 'providers', 'thumbnailImages'));
    }

    /**
     * Test AI connection.
     */
    public function testConnection(Request $request): RedirectResponse
    {
        $provider = $request->input('provider', config('ai.default_provider', 'openai'));

        try {
            $success = $this->contentService->testConnection($provider);

            if ($success) {
                return redirect()->route('admin.ai.settings')
                    ->with('success', __('ai.connection_success'));
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.ai.settings')
                ->with('error', __('ai.connection_failed') . ': ' . $e->getMessage());
        }

        return redirect()->route('admin.ai.settings')
            ->with('error', __('ai.connection_failed'));
    }

    /**
     * Upload thumbnail background images.
     */
    public function uploadThumbnails(Request $request): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['image', 'max:5120'], // 5MB max
        ]);

        $currentImages = Setting::get('thumbnail_images', []);
        if (is_string($currentImages)) {
            $currentImages = json_decode($currentImages, true) ?? [];
        }

        foreach ($request->file('images') as $image) {
            $path = $image->store('ai/thumbnails', config('filesystems.default'));
            $currentImages[] = $path;
        }

        Setting::set('thumbnail_images', $currentImages, 'ai', 'json');

        return redirect()->route('admin.ai.settings')
            ->with('success', __('ai.thumbnails_uploaded'));
    }

    /**
     * Delete a thumbnail background image.
     */
    public function deleteThumbnail(Request $request): RedirectResponse
    {
        $path = $request->input('path');

        $currentImages = Setting::get('thumbnail_images', []);
        if (is_string($currentImages)) {
            $currentImages = json_decode($currentImages, true) ?? [];
        }

        $currentImages = array_filter($currentImages, fn($img) => $img !== $path);

        // Delete file
        \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->delete($path);

        Setting::set('thumbnail_images', array_values($currentImages), 'ai', 'json');

        return redirect()->route('admin.ai.settings')
            ->with('success', __('ai.thumbnail_deleted'));
    }
}
