<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the supported locales from LaravelLocalization
        $supportedLocales = LaravelLocalization::getSupportedLanguagesKeys();

        // Default locale
        $defaultLocale = config('app.locale', 'en');

        // Determine the locale
        $locale = $defaultLocale;

        // Check if the user is authenticated and has a preferred language
        if ($request->user()) {

            $userPreferredLocale = Auth::user()->lang;
            if (in_array($userPreferredLocale, $supportedLocales)) {
                $locale = $userPreferredLocale;
            }
        }


        // Set the locale
        LaravelLocalization::setLocale($locale);

        // Continue processing the request
        return $next($request);

    }
}
