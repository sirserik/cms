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
        max-width: 600px;
        margin: 0 auto;
    }
    .product h1 {
        margin-top: 0;
    }
    .product p {
        margin: 10px 0;
    }
</style>
</head>
<body>
<div class="product">
    <h1><?= $product['name'] ?></h1>
    <p><strong>Price:</strong> $<?= $product['price'] ?></p>
    <p><strong>Description:</strong> <?= $product['description'] ?></p>
</div>
</body>
</html>