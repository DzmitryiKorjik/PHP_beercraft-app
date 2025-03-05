<?php

require_once 'app/controllers/AuthController.php';

class Router
{
    private $authController;

    public function __construct()
    {
        $this->authController = new AuthController();
    }

    public function handleRequest()
    {
        $action = $_GET['action'] ?? 'home';

        switch ($action) {
            case 'signup':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Traitement des données du formulaire d'inscription
                    $this->authController->signup($_POST);
                }
                $view = 'signup';  // Affichage du formulaire d'inscription
                break;

            case 'signin':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Traitement des données du formulaire de connexion
                    $this->authController->signin($_POST);
                }
                $view = 'signin';  // Affichage du formulaire de connexion
                break;

            case 'home':
                $view = 'home';
                break;

            case 'signout':
                $this->authController->signout();
                exit;  // Terminer l'exécution après la sortie
                break;

            default:
                $view = '404';
        }

        require_once 'app/views/layout.php';  // Chargement du modèle
    }
}

$router = new Router();
$router->handleRequest();
?>