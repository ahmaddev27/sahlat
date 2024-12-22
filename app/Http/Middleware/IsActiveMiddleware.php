<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->expectsJson()) {
            if (Auth::user()->is_active) {
                return $next($request);
            }

            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => trans('messages.blocked'),
                'data' => [
                    'message' => [trans('messages.blocked')]
                ]
            ], 400);
        }

        if (Auth::user()->is_active) {
            return $next($request);
        }

        return redirect()->back()->withErrors(['error' => trans('messages.401')]);
    }
}
