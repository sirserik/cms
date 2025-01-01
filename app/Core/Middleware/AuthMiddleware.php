<?php


namespace App\Core\Middleware;

use App\Core\SessionManager\SessionManager;

class AuthMiddleware extends Middleware
{
    private $sessionManager;

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    public function handle($request, $next)
    {
        if (!$this->sessionManager->has('user_id')) {
            header('Location: /login');
            exit;
        }

        return $next($request);
    }
}