<?php

namespace App\Http\Middleware;

use App\Constants\AppConstants;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckOrderToPay
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        $order = Auth::user()->order;
        if (in_array($order->status, [AppConstants::PENDING, AppConstants::APPROVED])) {
            return redirect()->back()->with('error', 'This order is in ' . $order->status . ' status');
        }
        return $next($request);
    }
}
