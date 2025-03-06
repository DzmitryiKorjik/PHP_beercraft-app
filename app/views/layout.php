<?php
// Décommenter la ligne suivante pour afficher le contenu de la session (utile pour le débogage)
// var_dump($_SESSION);  // Devrait afficher array('test' => 'Session OK')

?>
<!doctype html>
<html lang="fr">

<?php include 'app/views/partials/head.php'; // Inclusion de l'en-tête HTML (balises <head>) 
?>

<body class="page-<?= $view ?>">
    <?php include 'app/views/partials/header.php'; // Inclusion du header 
    ?>

    <main>
        <?php
        // Définition du chemin du fichier de la vue à inclure
        $file = __DIR__ . "/{$view}.php";

        // Vérifier si le fichier de la vue existe
        if (file_exists($file)) {
            // Rendre la variable $beer accessible globalement si elle est définie
            if (isset($beer)) {
                $GLOBALS['beer'] = $beer;
            }
            require $file; // Inclusion du fichier de la vue
        } else {
            // Affichage d'un message d'erreur si la vue n'est pas trouvée
            echo "<h1>Erreur 404</h1><p>Fichier de vue non trouvé.</p>";
        }
        ?>
    </main>

    <?php include 'app/views/partials/footer.php'; // Inclusion du pied de page 
    ?>

    <!-- Inclusion du script principal de l'application -->
    <script src="<?= $_SERVER['REQUEST_URI'] ?>/assets/js/script.js"></script>

</body>

</html>