<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDesktopOnly
{
    /**
     * Handle an incoming request.
     * Restrict admin routes to PC / Laptop only.
     * Redirect mobile devices (HP / Smartphone / Tablet) to home page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = strtolower($request->header('User-Agent', ''));

        // Pola User-Agent untuk perangkat HP / mobile / tablet
        $mobilePatterns = [
            'android',
            'iphone',
            'ipod',
            'ipad',
            'mobile',
            'blackberry',
            'webos',
            'windows phone',
            'iemobile',
            'opera mini',
            'opera mobi',
            'symbian',
            'tablet',
            'silk/',
            'kindle',
        ];

        foreach ($mobilePatterns as $pattern) {
            if (str_contains($userAgent, $pattern)) {
                return redirect()->route('home');
            }
        }

        // Cek Client Hints Sec-CH-UA-Mobile dari browser modern (Chrome, Edge, Samsung Internet)
        if ($request->header('Sec-CH-UA-Mobile') === '?1') {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
