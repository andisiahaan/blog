<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateAiPost;
use App\Models\AiGenerationLog;
use App\Services\Ai\AiContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiLogController extends Controller
{
    /**
     * Display a listing of generation logs.
     */
    public function index(Request $request): View
    {
        $query = AiGenerationLog::with(['topic', 'post']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('provider')) {
            $query->where('provider', $request->provider);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => AiGenerationLog::count(),
            'successful' => AiGenerationLog::successful()->count(),
            'failed' => AiGenerationLog::failed()->count(),
            'pending' => AiGenerationLog::pending()->count(),
        ];

        return view('admin.ai.logs.index', compact('logs', 'stats'));
    }

    /**
     * Display the specified log.
     */
    public function show(AiGenerationLog $log): View
    {
        $log->load(['topic', 'post']);

        return view('admin.ai.logs.show', compact('log'));
    }

    /**
     * Retry a failed generation.
     */
    public function retry(AiGenerationLog $log, AiContentService $service): RedirectResponse
    {
        if (!$log->canRetry()) {
            return redirect()->route('admin.ai.logs.index')
                ->with('error', __('ai.cannot_retry'));
        }

        if (!$log->topic) {
            return redirect()->route('admin.ai.logs.index')
                ->with('error', __('ai.topic_not_found'));
        }

        try {
            GenerateAiPost::dispatch($log->topic);

            return redirect()->route('admin.ai.logs.index')
                ->with('success', __('ai.retry_dispatched'));
        } catch (\Exception $e) {
            return redirect()->route('admin.ai.logs.index')
                ->with('error', __('ai.retry_failed', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Display statistics dashboard.
     */
    public function stats(AiContentService $service): View
    {
        $stats = $service->getStats();

        // Get daily stats for chart
        $dailyStats = AiGenerationLog::selectRaw('DATE(created_at) as date, COUNT(*) as total, SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END) as successful')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();

        // Get provider breakdown
        $providerStats = AiGenerationLog::selectRaw('provider, COUNT(*) as total, SUM(tokens_used) as tokens, SUM(cost) as cost')
            ->successful()
            ->groupBy('provider')
            ->get();

        return view('admin.ai.logs.stats', compact('stats', 'dailyStats', 'providerStats'));
    }

    /**
     * Delete old logs.
     */
    public function cleanup(Request $request): RedirectResponse
    {
        $days = $request->input('days', 30);

        $deleted = AiGenerationLog::where('created_at', '<', now()->subDays($days))->delete();

        return redirect()->route('admin.ai.logs.index')
            ->with('success', __('ai.logs_cleaned', ['count' => $deleted]));
    }
}
