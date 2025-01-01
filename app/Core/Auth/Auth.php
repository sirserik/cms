<?php


namespace App\Core\Auth;

class Auth
{
    public function login($username, $password)
    {
        // Простая проверка (замените на реальную логику)
        if ($username === 'admin' && $password === 'password') {
            $_SESSION['user'] = $username;
            return true;
        }
        return false;
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user']);
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }
}