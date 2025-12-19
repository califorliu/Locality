<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Check for lang in query string first
        $locale = $request->query('lang');
        
        // If no lang in query, check session
        if (!$locale) {
            $locale = $request->session()->get('app_locale', config('app.locale'));
        }

        // Validate and set locale
        if (in_array($locale, ['en', 'zh-CN', 'zh-TW'])) {
            App::setLocale($locale);
            $request->session()->put('app_locale', $locale);
        } else {
            // Fallback to default
            $locale = config('app.locale');
            App::setLocale($locale);
        }

        return $next($request);
    }
}