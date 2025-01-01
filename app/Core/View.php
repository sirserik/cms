<?php

namespace App\Core\View;

class View
{
    private $viewsPath = __DIR__ . '/../../../app/views/';
    private $theme = 'default';

    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    public function render($view, $data = [], $layout = 'default')
    {
        $viewContent = $this->renderView("themes/{$this->theme}/{$view}", $data);
        $layoutPath = $this->viewsPath . "themes/{$this->theme}/layouts/{$layout}.php";
        if (file_exists($layoutPath)) {
            ob_start();
            extract($data);
            include $layoutPath;
            return ob_get_clean();
        }
        throw new \Exception("Layout {$layout} not found.");
    }

    private function renderView($view, $data)
    {
        $viewPath = $this->viewsPath . $view . '.php';
        if (file_exists($viewPath)) {
            ob_start();
            extract($data);
            include $viewPath;
            return ob_get_clean();
        }
        throw new \Exception("View {$view} not found.");
    }
}