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

            $data = trans('messages.blocked');
            $message = trans('messages.blocked');
            $array = [
                'status' => false,
                'code' => 400,
                'message' => $message,
                'data' => ['error'=>[$data]],

            ];

            return response()->json($array, 400);

        }


        if (Auth::user()->is_active) {
            return $next($request);
        }

        return redirect()->back()->withErrors(['error' => trans('messages.401')]);
    }
}
