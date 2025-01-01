<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Core\View;

class PostController extends BaseController
{
    private $postModel;
    private $view;

    public function __construct(PostModel $postModel, View $view)
    {
        $this->postModel = $postModel;
        $this->view = $view;
    }

    public function show($slug)
    {
        $languageCode = $this->language->getCurrentLanguage();
        $post = $this->postModel->getPostBySlug($slug, $languageCode);

        if ($post) {
            // Указываем layout в зависимости от языка
            $layout = ($languageCode === 'ru') ? 'ru_layout' : 'default';

            echo $this->view->render('post/show', [
                'post' => $post,
                'title' => $post['title'],
            ], $layout); // Передаем layout третьим аргументом
        } else {
            echo "Post not found.";
        }
    }
}