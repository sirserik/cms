<?php

namespace App\Controllers;

use App\Models\PostModel;

class ApiController
{
    private $postModel;

    public function __construct(PostModel $postModel)
    {
        $this->postModel = $postModel;
    }

    public function getPost($slug)
    {
        $post = $this->postModel->getPostBySlug($slug, 'en');
        header('Content-Type: application/json');
        echo json_encode($post);
    }
}