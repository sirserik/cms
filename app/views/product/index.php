<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .product {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .product h2 {
            margin-top: 0;
        }
        .product p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
<h1>Filtered Products</h1>
<?php foreach ($products as $product): ?>
    <div class="product">
        <h2><?= $product['name'] ?></h2>
        <p><strong>Price:</strong> $<?= $product['price'] ?></p>
        <p><strong>Brand:</strong> <?= $product['brand'] ?></p>
        <p><strong>Rating:</strong> <?= $product['rating'] ?></p>
        <p><strong>Availability:</strong> <?= $product['availability'] ?></p>
    </div>
<?php endforeach; ?>
</body>
</html>