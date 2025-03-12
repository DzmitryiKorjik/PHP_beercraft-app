<!DOCTYPE html>
<html lang="fr">
    <?php require_once 'app/views/partials/head.php'; ?>
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