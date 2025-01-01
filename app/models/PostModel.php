<?php

namespace App\Models;

use PDO;

class PostModel extends BaseModel
{
    protected $table = 'posts';

    public function getPostBySlug($slug, $languageCode)
    {
        $query = "
            SELECT p.id, p.slug, pt.title, pt.content
            FROM posts p
            JOIN post_translations pt ON p.id = pt.post_id
            WHERE p.slug = :slug AND pt.language_code = :languageCode
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':slug' => $slug,
            ':languageCode' => $languageCode,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}