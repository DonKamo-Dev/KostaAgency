<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported application locales.
     *
     * @var array<int, string>
     */
    public const SUPPORTED = ['es', 'en'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->determineLocale($request);

        App::setLocale($locale);
        Carbon::setLocale($locale);

        $response = $next($request);

        // Ensure cookie is persisted for 1 year if not already matching
        if ($request->cookie('locale') !== $locale) {
            $response->headers->setCookie(cookie()->forever('locale', $locale));
        }

        return $response;
    }

    /**
     * Determine the active locale based on session, cookie, or default.
     */
    protected function determineLocale(Request $request): string
    {
        if ($request->hasSession() && $request->session()->has('locale')) {
            $candidate = $request->session()->get('locale');
            if (in_array($candidate, self::SUPPORTED, true)) {
                return $candidate;
            }
        }

        $candidate = $request->cookie('locale');
        if ($candidate && in_array($candidate, self::SUPPORTED, true)) {
            if ($request->hasSession()) {
                $request->session()->put('locale', $candidate);
            }
            return $candidate;
        }

        return config('app.locale', 'es');
    }
}
