<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya aktifkan di production
        if (app()->isProduction()) {
            // Jika request belum secure, redirect ke HTTPS
            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri());
            }
        }

        return $next($request);
    }
}
