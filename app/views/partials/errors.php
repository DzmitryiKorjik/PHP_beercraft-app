<?php 

// Vérifie si des erreurs existent et les affiche
if (!empty($_SESSION['errors'])) {
    echo '<div class="error-messages">';
    foreach ($_SESSION['errors'] as $error) {
        echo "<p style='color: red;'>$error</p>";
    }
    echo '</div>';
    unset($_SESSION['errors']); // Supprime les erreurs après affichage
}
?>