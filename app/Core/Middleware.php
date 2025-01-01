<?php


namespace App\Core\Middleware;

class Middleware
{
    /**
     * Метод для обработки запроса.
     */
    public function handle($request, $next)
    {
        return $next($request);
    }
}