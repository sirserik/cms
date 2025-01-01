<?php


namespace App\Core\Language;

class Language
{
    private $language;
    private $messages = [];

    public function __construct($defaultLanguage)
    {
        $this->language = $defaultLanguage;
        $this->loadMessages();
    }

    private function loadMessages()
    {
        $file = __DIR__ . "/../../languages/{$this->language}/messages.php";
        if (file_exists($file)) {
            $this->messages = require $file;
        }
    }

    public function get($key)
    {
        return $this->messages[$key] ?? $key;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
        $this->loadMessages();
    }
}