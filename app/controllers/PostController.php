<?php
namespace App\Controllers;

use App\Models\PostModel;

class PostController extends BaseController
{
    private $postModel;

    public function __construct(PostModel $postModel)
    {
        $this->postModel = $postModel;
    }

    public function show($slug)
    {
        $languageCode = $this->language->getCurrentLanguage(); // Метод для получения текущего языка
        $post = $this->postModel->getPostBySlug($slug, $languageCode);

        if ($post) {
            echo "<h1>{$post['title']}</h1>";
            echo "<p>{$post['content']}</p>";
        } else {
            echo "Post not found.";
        }
    }
}