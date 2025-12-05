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
        // 1. Revoke sanctum token if present (INI SUDAH BENAR)
        // Mencabut token akses saat ini (wajib untuk Sanctum)
        if ($request->user() && method_exists($request->user(), 'currentAccessToken') && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        // 2. Untuk session-based logouts (Jika Anda menggunakan session/cookie Sanctum SPA)
        // Kita harus secara eksplisit menggunakan 'web' guard untuk memanggil logout sesi.
        if (Auth::guard('web')->check()) { // Cek otentikasi sesi web
            Auth::guard('web')->logout(); // 🎯 PERBAIKAN: Gunakan 'web' guard secara eksplisit
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // 3. Respons (Ini juga sudah benar)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Logged out'], 200);
        }

        return redirect('/');
    }
}
