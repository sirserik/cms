<?php

namespace App\Core\View;

use Exception;

class View
{
    /**
     * @var string - Путь к папке с шаблонами.
     */
    private string $viewsPath;

    /**
     * @var string - Текущая тема оформления.
     */
    private string $theme = 'default';

    /**
     * @var string - Расширение файлов шаблонов.
     */
    private string $templateExtension = 'php';

    /**
     * Конструктор.
     *
     * @param string $viewsPath - Путь к папке с шаблонами.
     * @throws Exception - Если путь не является валидной директорией.
     */
    public function __construct(string $viewsPath)
    {
        $this->setViewsPath($viewsPath);
    }

    /**
     * Устанавливает тему оформления.
     *
     * @param string $theme - Имя темы.
     */
    public function setTheme(string $theme): void
    {
        $this->theme = $theme;
    }

    /**
     * Устанавливает расширение файлов шаблонов.
     *
     * @param string $extension - Расширение (например, 'php', 'html').
     */
    public function setTemplateExtension(string $extension): void
    {
        $this->templateExtension = ltrim($extension, '.');
    }

    /**
     * Рендерит шаблон.
     *
     * @param string $template - Имя шаблона.
     * @param array $data - Данные для передачи в шаблон.
     * @return string
     * @throws Exception - Если шаблон не найден.
     */
    public function render(string $template, array $data = []): string
    {
        $templatePath = $this->getTemplatePath($template);

        if (!file_exists($templatePath)) {
            throw new Exception("Template not found: {$templatePath}");
        }

        // Извлекаем переменные из массива данных
        extract($data);

        // Буферизация вывода
        ob_start();
        include $templatePath;
        return ob_get_clean();
    }

    /**
     * Рендерит шаблон с использованием layout.
     *
     * @param string $template - Имя шаблона.
     * @param array $data - Данные для передачи в шаблон.
     * @param string $layout - Имя layout.
     * @return string
     * @throws Exception - Если layout или шаблон не найдены.
     */
    public function renderWithLayout(string $template, array $data = [], string $layout = 'default'): string
    {
        $content = $this->render($template, $data);
        $layoutPath = $this->getTemplatePath("layouts/{$layout}");

        if (!file_exists($layoutPath)) {
            throw new Exception("Layout not found: {$layoutPath}");
        }

        // Передаем контент в layout
        $data['content'] = $content;
        return $this->render("layouts/{$layout}", $data);
    }

    /**
     * Проверяет, существует ли шаблон.
     *
     * @param string $template - Имя шаблона.
     * @return bool
     */
    public function templateExists(string $template): bool
    {
        return file_exists($this->getTemplatePath($template));
    }

    /**
     * Устанавливает путь к шаблонам.
     *
     * @param string $viewsPath - Новый путь к папке с шаблонами.
     * @throws Exception - Если путь не является валидной директорией.
     */
    public function setViewsPath(string $viewsPath): void
    {
        if (!is_dir($viewsPath)) {
            throw new Exception("Views path is not a valid directory: {$viewsPath}");
        }

        $this->viewsPath = rtrim($viewsPath, '/');
    }

    /**
     * Возвращает текущую тему оформления.
     *
     * @return string
     */
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * Возвращает путь к шаблонам.
     *
     * @return string
     */
    public function getViewsPath(): string
    {
        return $this->viewsPath;
    }

    /**
     * Возвращает путь к шаблону.
     *
     * @param string $template - Имя шаблона.
     * @return string
     */
    private function getTemplatePath(string $template): string
    {
        return "{$this->viewsPath}/{$this->theme}/{$template}.{$this->templateExtension}";
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