<?php

namespace App\Controllers;

use App\Core\Language\Language;

class BaseController
{
    protected $language;

    public function __construct(Language $language)
    {
        $this->language = $language;
    }
}