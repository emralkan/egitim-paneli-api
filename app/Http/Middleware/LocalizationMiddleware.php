<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\LanguagesLine;

class LocalizationMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            $translations = LanguagesLine::where('locale', $locale)->pluck('translation', 'key')->toArray();
            App::setLocale($locale);
            view()->share('translations', $translations);
        }

        return $next($request);
    }
}

