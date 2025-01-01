<?php


namespace App\Core\Middleware;

use App\Core\Logger\Logger;

class LogMiddleware extends Middleware
{
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function handle($request, $next)
    {
        $this->logger->log("Request: {$request['method']} {$request['uri']}");
        return $next($request);
    }
}