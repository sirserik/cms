<?php

namespace App\Core\Language;

class Language
{
    private $language;

    public function __construct($defaultLanguage)
    {
        $this->language = $defaultLanguage;
    }

    public function getCurrentLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }
}