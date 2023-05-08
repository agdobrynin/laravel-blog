<?php

namespace App\Http\Middleware;

use App\Enums\LocaleEnums;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestLocale = $request->route()->parameter('locale');

        if (!Session::has('locale')) {
            // Check if auth user and has language preference
            $locale = $requestLocale ?: LocaleEnums::cases()[0]->value;
            Session::put('locale', $locale);
        } else {
            $locale = Session::get('locale');

            if ($requestLocale && $locale !== $requestLocale) {
                Session::put('locale', $requestLocale);
                $locale = $requestLocale;
            }
        }

        app()->setLocale($locale);
        app()->setFallbackLocale($locale);
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
