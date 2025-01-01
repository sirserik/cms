<?php

namespace App\Controllers;

use App\Core\View\View;
use App\Core\Language\Language;
use App\Core\SessionManager\SessionManager;

class BaseController
{
    protected View $view;
    protected Language $language;
    protected SessionManager $session;

    public function __construct(View $view, Language $language, SessionManager $session)
    {
        $this->view = $view;
        $this->language = $language;
        $this->session = $session;
    }

    /**
     * Рендерит шаблон.
     *
     * @param string $template - Имя шаблона.
     * @param array $data - Данные для передачи в шаблон.
     */
    protected function render(string $template, array $data = []): void
    {
        echo $this->view->render($template, $data);
    }

    /**
     * Рендерит шаблон с использованием layout.
     *
     * @param string $template - Имя шаблона.
     * @param array $data - Данные для передачи в шаблон.
     * @param string $layout - Имя layout.
     */
    protected function renderWithLayout(string $template, array $data = [], string $layout = 'default'): void
    {
        echo $this->view->renderWithLayout($template, $data, $layout);
    }
}