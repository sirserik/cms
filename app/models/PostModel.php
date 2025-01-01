<?php

namespace App\Models;

use PDO;

class PostModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // В PostModel
    public function getPostBySlug($slug, $languageCode)
    {
        $cacheKey = "post_{$slug}_{$languageCode}";
        $cache = new Cache(); // Ваш класс для работы с кэшем

        if ($cache->has($cacheKey)) {
            return $cache->get($cacheKey);
        }

        $query = "SELECT p.id, p.slug, pt.title, pt.content
              FROM posts p
              JOIN post_translations pt ON p.id = pt.post_id
              WHERE p.slug = :slug AND pt.language_code = :languageCode";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':slug' => $slug, ':languageCode' => $languageCode]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        $cache->set($cacheKey, $post, 3600); // Кэшируем на 1 час
        return $post;
    }
    public function getPosts($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $query = "SELECT p.id, p.slug, pt.title, pt.content
              FROM posts p
              JOIN post_translations pt ON p.id = pt.post_id
              WHERE pt.language_code = :languageCode
              LIMIT :offset, :perPage";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':languageCode' => $this->language->getCurrentLanguage(),
            ':offset' => $offset,
            ':perPage' => $perPage,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}