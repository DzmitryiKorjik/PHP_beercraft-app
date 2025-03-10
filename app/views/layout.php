<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beercraft</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
    <?php require_once 'app/views/partials/header.php'; ?>
    
    <main>
        <?php 
        if (isset($view)) {
            $viewPath = "app/views/{$view}.php";
            if (file_exists($viewPath)) {
                require_once $viewPath;
            } else {
                require_once "app/views/404.php";
            }
        } else {
            require_once "app/views/404.php";
        }
        ?>
    </main>

    <?php require_once 'app/views/partials/footer.php'; ?>
</body>
</html>