<?php


namespace App\Core\Middleware;

class MaintenanceMiddleware extends Middleware
{
    public function handle($request, $next)
    {
        if (config('mode') === 'maintenance') {
            header('HTTP/1.1 503 Service Unavailable');
            echo "The site is currently under maintenance. Please check back later.";
            exit;
        }

        return $next($request);
    }
}