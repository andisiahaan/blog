<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TokenController extends Controller
{
    /**
     * Display a listing of the tokens.
     */
    public function index()
    {
        $tokens = auth()->user()->tokens()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.tokens.index', compact('tokens'));
    }

    /**
     * Store a newly created token.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $token = $request->user()->createToken($validated['name']);

        return redirect()->route('admin.tokens.index')
            ->with('success', __('admin.token_created'))
            ->with('new_token', $token->plainTextToken);
    }

    /**
     * Remove the specified token.
     */
    public function destroy(string $token)
    {
        // Find token that belongs to current user
        $deleted = auth()->user()->tokens()->where('id', $token)->delete();

        if ($deleted) {
            return redirect()->route('admin.tokens.index')
                ->with('success', __('admin.token_deleted'));
        }

        return redirect()->route('admin.tokens.index')
            ->with('error', __('admin.token_not_found'));
    }
}


