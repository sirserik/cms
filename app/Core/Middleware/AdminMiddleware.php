<?php
namespace App\Core\Middleware;

use App\Core\SessionManager\SessionManager;

class AdminMiddleware extends Middleware
{
    private $sessionManager;

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    public function handle($request, $next)
    {
        if (!$this->sessionManager->has('is_admin')) {
            header('Location: /login');
            exit;
        }

        return $next($request);
    }
}