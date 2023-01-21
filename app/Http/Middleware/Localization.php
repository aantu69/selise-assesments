<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class Localization
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('locale') && in_array(session()->get('locale'), Helper::activeLanguages())) {
            App::setLocale(session()->get('locale'));
        } else {
            App::setLocale(Config::get('app.fallback_locale'));
        }
        return $next($request);
    }
}
