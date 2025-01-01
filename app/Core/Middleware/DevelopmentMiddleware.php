<?php


namespace App\Core\Middleware;

class DevelopmentMiddleware extends Middleware
{
    public function handle($request, $next)
    {
        if (config('mode') === 'development') {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }

        return $next($request);
    }
}