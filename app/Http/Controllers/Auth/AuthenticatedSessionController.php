<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            'token' => $token,
            'user' => $user
        ], 200);
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
{
    $sessionInvalidated = false;
    $tokensDeleted = false;

    try {
        $request->session()->invalidate();
        $sessionInvalidated = true;
    } catch (\Exception $e) {
        $sessionInvalidated = false;
    }

    try {
        $request->user()->currentAccessToken()->delete();
        $tokensDeleted = true;
    } catch (\Exception $e) {
        $tokensDeleted = false;
    }

    return response()->json([
        'message' => 'User logged out successfully',
        'session_invalidated' => $sessionInvalidated,
        'tokens_deleted' => $tokensDeleted,
    ]);
}
}
