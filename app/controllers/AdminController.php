<?php

namespace App\Controllers;

use App\Core\Auth\Auth;
use App\Core\View\View;

class AdminController
{
    private $auth;
    private $view;

    public function __construct(Auth $auth, View $view)
    {
        $this->auth = $auth;
        $this->view = $view;
    }

    public function dashboard()
    {
        if (!$this->auth->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        echo $this->view->render('admin/dashboard');
    }
}