<?php


namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        echo $this->language->get('welcome_message');
    }
}