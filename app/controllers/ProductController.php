<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    private $productModel;

    public function __construct(ProductModel $productModel)
    {
        $this->productModel = $productModel;
    }

    public function index($params)
    {
        // Извлечение параметров запроса
        $filters = [
            'category' => $params['category'] ?? null,
            'subcategory' => $params['subcategory'] ?? null,
            'brand' => isset($params['brand']) ? explode(',', $params['brand']) : [],
            'price_min' => $params['price_min'] ?? null,
            'price_max' => $params['price_max'] ?? null,
            'rating_min' => $params['rating_min'] ?? null,
            'availability' => $params['availability'] ?? null,
            'sort_by' => $params['sort_by'] ?? 'price',
            'order' => $params['order'] ?? 'asc',
            'page' => $params['page'] ?? 1,
            'items_per_page' => $params['items_per_page'] ?? 20,
            'color' => isset($params['color']) ? explode(',', $params['color']) : [],
            'features' => isset($params['features']) ? explode(',', $params['features']) : [],
        ];

        // Получение товаров с учетом фильтров
        $products = $this->productModel->getFilteredProducts($filters);

        // Передача данных в шаблон
        $this->render('product/index', [
            'products' => $products,
            'filters' => $filters,
            'title' => 'Filtered Products',
        ]);
    }
}