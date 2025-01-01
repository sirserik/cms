<?php


namespace App\Core\Logger;

class Logger
{
    private $logPath = __DIR__ . '/../../../app/logs/app.log';

    public static function log($message)
    {
        file_put_contents(self::$logPath, date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND);
    }
}