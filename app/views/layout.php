<?php
// var_dump($_SESSION);  // Devrait afficher array('test' => 'Session OK')

?>
<!doctype html>
<html lang="fr">
<?php include 'app/views/partials/head.php'; ?>

<body>
    <?php include 'app/views/partials/header.php'; ?>
    <main>
        <?php $file = __DIR__ . "/{$view}.php";
        var_dump($view);
        if (file_exists($file)) {
            require $file;
        } else {
            echo "<h1>Erreur 404</h1><p>Fichier de vue non trouvé.</p>";
        } ?> 
    </main>

    <?php include 'app/views/partials/footer.php'; ?>
    <script src="<?= $_SERVER['REQUEST_URI'] ?>/assets/js/script.js"></script>
</body>

</html>