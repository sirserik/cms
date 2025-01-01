<?php

namespace App\Controllers;

use App\Core\SessionManager\SessionManager;

class AuthController extends BaseController
{
    private $sessionManager;

    public function __construct(SessionManager $sessionManager, View $view, Language $language, Auth $auth)
    {
        parent::__construct($view, $language, $auth);
        $this->sessionManager = $sessionManager;
    }

    /**
     * Отображение формы входа (GET).
     */
    public function loginForm()
    {
        $this->render('auth/login');
    }

    /**
     * Обработка входа (POST).
     */
    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($this->auth->login($username, $password)) {
            $this->sessionManager->set('user_id', 123);
            echo "Logged in successfully!";
        } else {
            echo "Invalid credentials!";
        }
    }

    /**
     * Выход (GET).
     */
    public function logout()
    {
        $this->sessionManager->remove('user_id');
        echo "Logged out successfully!";
    }
}