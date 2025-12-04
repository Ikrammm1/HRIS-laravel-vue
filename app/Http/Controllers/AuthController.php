<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * API helper: return current authenticated user as resource or null.
     * Kept for API usage where controller is invoked as a single-action controller.
     */
    public function __invoke()
    {
        return Auth::check() ? new UserResource(Auth::user()) : null;
    }

    /**
     * API: Log the user out.
     * - If using session guard, invalidate session.
     * - If using Sanctum tokens, revoke the current access token.
     */
    public function logout(Request $request)
    {
        // Revoke sanctum token if present
        if ($request->user() && method_exists($request->user(), 'currentAccessToken') && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        // For session-based logouts (just in case):
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Logged out'], 200);
        }

        return redirect('/');
    }
}
