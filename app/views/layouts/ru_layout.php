<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Мой CMS' ?></title>
</head>
<body>
<header>
    <h1>Добро пожаловать в Мой CMS</h1>
</header>
<main>
    <?= $content ?>
</main>
<footer>
    <p>&copy; 2023 Мой CMS</p>
</footer>
</body>
</html>