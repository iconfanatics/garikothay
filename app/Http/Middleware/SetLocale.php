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
        $isAdmin = $request->is('admin*') || ($request->is('livewire*') && str_contains($request->header('referer', ''), '/admin'));
        $sessionKey = $isAdmin ? 'admin_locale' : 'locale';

        $locale = $this->resolveLocale($request, $sessionKey, $isAdmin);
        App::setLocale($locale);
        Session::put($sessionKey, $locale);

        return $next($request);
    }

    private function resolveLocale(Request $request, string $sessionKey, bool $isAdmin): string
    {
        // 1. Session
        if (Session::has($sessionKey) && in_array(Session::get($sessionKey), $this->supportedLocales)) {
            return Session::get($sessionKey);
        }

        // 2. URL segment for frontend (if they use it)
        $urlLocale = $request->segment(1);
        if (!$isAdmin && in_array($urlLocale, $this->supportedLocales)) {
            return $urlLocale;
        }

        // 3. Authenticated user preference
        if (!$isAdmin && $user = $request->user()) {
            return in_array($user->locale, $this->supportedLocales) ? $user->locale : 'bn';
        }

        // Default: Admin defaults to English, frontend to Bangla
        return $isAdmin ? 'en' : 'bn';
    }
}
