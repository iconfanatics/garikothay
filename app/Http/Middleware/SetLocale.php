<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private array $supportedLocales = ['bn', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);
        App::setLocale($locale);
        Session::put('locale', $locale);

        return $next($request);
    }

    private function resolveLocale(Request $request): string
    {
        // 1. URL segment (/en/ or /bn/)
        $urlLocale = $request->segment(1);
        if (in_array($urlLocale, $this->supportedLocales)) {
            return $urlLocale;
        }

        // 2. Session
        if (Session::has('locale') && in_array(Session::get('locale'), $this->supportedLocales)) {
            return Session::get('locale');
        }

        // 3. Authenticated user preference
        if ($user = $request->user()) {
            return in_array($user->locale, $this->supportedLocales) ? $user->locale : 'bn';
        }

        // 4. Browser Accept-Language
        $browserLocale = substr($request->getPreferredLanguage($this->supportedLocales) ?? 'bn', 0, 2);
        if (in_array($browserLocale, $this->supportedLocales)) {
            return $browserLocale;
        }

        return 'bn';
    }
}
