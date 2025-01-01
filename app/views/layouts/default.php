<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'My CMS' ?></title>
</head>
<body>
<header>
    <h1>Welcome to My CMS</h1>
</header>
<main>
    <?= $content ?>
</main>
<footer>
    <p>&copy; 2023 My CMS</p>
</footer>
</body>
</html>