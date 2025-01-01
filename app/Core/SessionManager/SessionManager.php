<?php


namespace App\Core\SessionManager;

use Exception;

class SessionManager
{
    /**
     * Инициализация сессии.
     */
    public function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Установка значения в сессию.
     */
    public function set($key, $value)
    {
        $this->start();
        $_SESSION[$key] = $value;
    }

    /**
     * Получение значения из сессии.
     */
    public function get($key, $default = null)
    {
        $this->start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Проверка наличия ключа в сессии.
     */
    public function has($key)
    {
        $this->start();
        return isset($_SESSION[$key]);
    }

    /**
     * Удаление значения из сессии.
     */
    public function remove($key)
    {
        $this->start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Очистка всех данных сессии.
     */
    public function clear()
    {
        $this->start();
        session_unset();
    }

    /**
     * Уничтожение сессии.
     */
    public function destroy()
    {
        $this->start();
        session_destroy();
    }

    /**
     * Установка куки.
     */
    public function setCookie($name, $value, $expire = 0, $path = '/', $domain = '', $secure = false, $httponly = true)
    {
        if (setcookie($name, $value, $expire, $path, $domain, $secure, $httponly)) {
            return true;
        }
        throw new Exception("Failed to set cookie: {$name}");
    }

    /**
     * Получение значения куки.
     */
    public function getCookie($name, $default = null)
    {
        return $_COOKIE[$name] ?? $default;
    }

    /**
     * Удаление куки.
     */
    public function deleteCookie($name, $path = '/', $domain = '')
    {
        if (isset($_COOKIE[$name])) {
            setcookie($name, '', time() - 3600, $path, $domain);
            unset($_COOKIE[$name]);
        }
    }

    /**
     * Проверка наличия куки.
     */
    public function hasCookie($name)
    {
        return isset($_COOKIE[$name]);
    }
}