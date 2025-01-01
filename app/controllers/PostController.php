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

    public function show($params)
    {
        $slug = $params['slug'] ?? null;
        $post = $this->postModel->getPostBySlug($slug, $this->language->getCurrentLanguage());

        if ($post) {
            $this->render('post/show', [
                'post' => $post,
                'title' => $post['title'],
            ]);
        } else {
            echo "Post not found.";
        }
    }
}