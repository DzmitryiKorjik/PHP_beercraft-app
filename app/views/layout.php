<!DOCTYPE html>
<html lang="fr">
    <?php require_once 'app/views/partials/head.php'; ?>
<body>
    <?php require_once 'app/views/partials/header.php'; ?>
    
    <main>
        <?php 
        if (isset($view)) {
            $viewPath = "app/views/pages/{$view}.php";
            if (file_exists($viewPath)) {
                require_once $viewPath;
            } else {
                require_once "app/views/pages/404.php";
            }
        } else {
            require_once "app/views/pages/404.php";
        }
        ?>
    </main>

    <?php require_once 'app/views/partials/footer.php'; ?>
    <script src="<?= BASE_URL ?>assets/js/script.js"></script>
    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>