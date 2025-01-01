<?php

namespace App\Models;

use PDO;

class ProductModel extends BaseModel
{
    protected $table = 'products';

    public function getFilteredProducts($filters)
    {
        $query = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        // Фильтрация по категории
        if (!empty($filters['category'])) {
            $query .= " AND category = :category";
            $params[':category'] = $filters['category'];
        }

        // Фильтрация по подкатегории
        if (!empty($filters['subcategory'])) {
            $query .= " AND subcategory = :subcategory";
            $params[':subcategory'] = $filters['subcategory'];
        }

        // Фильтрация по бренду
        if (!empty($filters['brand'])) {
            $query .= " AND brand IN (" . implode(',', array_fill(0, count($filters['brand']), '?')) . ")";
            $params = array_merge($params, $filters['brand']);
        }

        // Фильтрация по цене
        if (!empty($filters['price_min'])) {
            $query .= " AND price >= :price_min";
            $params[':price_min'] = $filters['price_min'];
        }
        if (!empty($filters['price_max'])) {
            $query .= " AND price <= :price_max";
            $params[':price_max'] = $filters['price_max'];
        }

        // Фильтрация по рейтингу
        if (!empty($filters['rating_min'])) {
            $query .= " AND rating >= :rating_min";
            $params[':rating_min'] = $filters['rating_min'];
        }

        // Фильтрация по наличию
        if (!empty($filters['availability'])) {
            $query .= " AND availability = :availability";
            $params[':availability'] = $filters['availability'];
        }

        // Фильтрация по цвету
        if (!empty($filters['color'])) {
            $query .= " AND color IN (" . implode(',', array_fill(0, count($filters['color']), '?')) . ")";
            $params = array_merge($params, $filters['color']);
        }

        // Фильтрация по характеристикам
        if (!empty($filters['features'])) {
            foreach ($filters['features'] as $feature) {
                $query .= " AND FIND_IN_SET(:feature_$feature, features)";
                $params[":feature_$feature"] = $feature;
            }
        }

        // Сортировка
        $query .= " ORDER BY {$filters['sort_by']} {$filters['order']}";

        // Пагинация
        $offset = ($filters['page'] - 1) * $filters['items_per_page'];
        $query .= " LIMIT :limit OFFSET :offset";
        $params[':limit'] = $filters['items_per_page'];
        $params[':offset'] = $offset;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}