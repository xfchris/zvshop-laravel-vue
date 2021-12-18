<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckBanned
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        if (Auth::check() && Auth::user()->banned_until && now()->lessThan(Auth::user()->banned_until)) {
            $bannedDays = now()->diffInDays(Auth::user()->banned_until);
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $days = ($bannedDays <= 28 && $bannedDays >= 2) ? ' for ' . $bannedDays . ' days' : '';
            $message = 'Your account has been suspended' . $days . '. Please contact administrator.';

            return redirect()->route('login')->with('error', $message);
        }
        return $next($request);
    }
}
