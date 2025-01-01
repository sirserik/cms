<?php
namespace App\Core;

class AppMode
{
    /**
     * Переключение в режим технических работ.
     */
    public static function enableMaintenanceMode()
    {
        $config = include __DIR__ . '/../../app/config/config.php';
        $config['mode'] = 'maintenance';
        file_put_contents(__DIR__ . '/../../app/config/config.php', '<?php return ' . var_export($config, true) . ';');
    }

    /**
     * Переключение в режим разработчика.
     */
    public static function enableDevelopmentMode()
    {
        $config = include __DIR__ . '/../../app/config/config.php';
        $config['mode'] = 'development';
        file_put_contents(__DIR__ . '/../../app/config/config.php', '<?php return ' . var_export($config, true) . ';');
    }

    /**
     * Переключение в рабочий режим.
     */
    public static function enableProductionMode()
    {
        $config = include __DIR__ . '/../../app/config/config.php';
        $config['mode'] = 'production';
        file_put_contents(__DIR__ . '/../../app/config/config.php', '<?php return ' . var_export($config, true) . ';');
    }

    /**
     * Получение текущего режима.
     */
    public static function getMode()
    {
        $config = include __DIR__ . '/../../app/config/config.php';
        return $config['mode'];
    }
}