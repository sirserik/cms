<?php

namespace App\Core\Mailer;

class Mailer
{
    public function send($to, $subject, $message)
    {
        mail($to, $subject, $message);
    }
}