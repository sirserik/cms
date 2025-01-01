<?php

namespace App\Core\ErrorHandler;

use Exception;
use App\Core\View\View;

class ErrorHandler
{
    /**
     * @var View - Экземпляр View для рендеринга страниц ошибок.
     */
    private View $view;

    /**
     * @var string - Путь к папке с шаблонами ошибок.
     */
    private string $errorViewsPath;

    /**
     * Конструктор.
     *
     * @param View $view
     * @param string $errorViewsPath
     */
    public function __construct(View $view, string $errorViewsPath)
    {
        $this->view = $view;
        $this->errorViewsPath = $errorViewsPath;
    }

    /**
     * Регистрирует обработчики ошибок и исключений.
     */
    public function register(): void
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
    }

    /**
     * Обрабатывает ошибки.
     *
     * @param int $errno - Уровень ошибки.
     * @param string $errstr - Сообщение об ошибке.
     * @param string $errfile - Файл, в котором произошла ошибка.
     * @param int $errline - Строка, на которой произошла ошибка.
     */
    public function handleError(int $errno, string $errstr, string $errfile, int $errline): void
    {
        $message = "Error: [$errno] $errstr in $errfile on line $errline";
        $this->logError($message);

        if (ini_get('display_errors')) {
            $this->renderErrorPage(500, $message);
        } else {
            $this->renderErrorPage(500, 'An error occurred. Please try again later.');
        }
    }

    /**
     * Обрабатывает исключения.
     *
     * @param Exception $exception - Исключение.
     */
    public function handleException(Exception $exception): void
    {
        $message = "Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
        $this->logError($message);

        if (ini_get('display_errors')) {
            $this->renderErrorPage(500, $message);
        } else {
            $this->renderErrorPage(500, 'An exception occurred. Please try again later.');
        }
    }

    /**
     * Рендерит страницу ошибки.
     *
     * @param int $statusCode - HTTP статус код.
     * @param string $message - Сообщение об ошибке.
     */
    private function renderErrorPage(int $statusCode, string $message): void
    {
        http_response_code($statusCode);

        try {
            echo $this->view->render("errors/{$statusCode}", [
                'message' => $message,
            ]);
        } catch (Exception $e) {
            echo "Error: $message";
        }
    }

    /**
     * Логирует ошибку.
     *
     * @param string $message - Сообщение об ошибке.
     */
    private function logError(string $message): void
    {
        // Здесь можно использовать любой логгер (например, Monolog)
        error_log($message);
    }
}