<?php

// Vérifier si une session est déjà active avant d'en démarrer une
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 3600, // 1 heure
        'cookie_secure' => isset($_SERVER['HTTPS']), // Activer uniquement si HTTPS
        'cookie_httponly' => true
    ]);
}

// Définir un chemin de base pour les URLs et les fichiers
define('BASE_URL', '/beercraft/');

?>
