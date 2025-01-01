<?php
namespace App\Controllers;

use App\Core\AppMode;
use App\Core\Cache\Cache;
use App\Core\Logger\Logger;

class SystemController extends BaseController
{
    private $cache;
    private $logger;

    public function __construct(Cache $cache, Logger $logger)
    {
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * Переключение в режим технических работ.
     */
    public function enableMaintenanceMode()
    {
        AppMode::enableMaintenanceMode();
        echo "Maintenance mode enabled.";
    }

    /**
     * Переключение в режим разработчика.
     */
    public function enableDevelopmentMode()
    {
        AppMode::enableDevelopmentMode();
        echo "Development mode enabled.";
    }

    /**
     * Переключение в рабочий режим.
     */
    public function enableProductionMode()
    {
        AppMode::enableProductionMode();
        echo "Production mode enabled.";
    }

    /**
     * Очистка кэша.
     */
    public function clearCache()
    {
        $this->cache->clear();
        echo "Cache cleared.";
    }

    /**
     * Очистка логов.
     */
    public function clearLogs()
    {
        $this->logger->clear();
        echo "Logs cleared.";
    }

    /**
     * Получение текущего режима.
     */
    public function getMode()
    {
        $mode = AppMode::getMode();
        echo "Current mode: $mode";
    }
}