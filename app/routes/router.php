<?php

require_once 'app/controllers/AuthController.php';
require_once 'app/controllers/BeerController.php';

/**
 * Classe Router
 * Gère les routes et les actions associées aux différentes pages de l'application.
 */
class Router
{
    private $authController;
    private $beerController;

    public function __construct()
    {
        // Initialisation des contrôleurs pour l'authentification et la gestion des bières
        $this->authController = new AuthController();
        $this->beerController = new BeerController();
    }

    /**
     * Gère les requêtes entrantes et exécute l'action correspondante
     */
    public function handleRequest()
    {
        // Récupération de l'action demandée, par défaut 'home' si aucune action n'est spécifiée
        $action = $_GET['action'] ?? 'home';

        switch ($action) {
            case 'signup':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Traitement du formulaire d'inscription
                    $this->authController->signup($_POST);
                }
                $view = 'signup'; // Affichage du formulaire d'inscription
                break;

            case 'signin':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Traitement du formulaire de connexion
                    $this->authController->signin($_POST);
                }
                $view = 'signin'; // Affichage du formulaire de connexion
                break;

            case 'contact':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Traitement du formulaire de contact
                    $this->authController->contact();
                }
                $view = 'contact'; // Affichage du formulaire de contact
                break;

            case 'addBeer':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $this->beerController->addBeer($_POST, $_FILES);
                }
                $view = 'addBeer';  // Отображение формы добавления
                break;

            case 'updateBeer':
                // Mise à jour d'une bière existante
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $result = $this->beerController->updateBeer($id, $_POST, $_FILES);
                    if (is_array($result)) {
                        extract($result); // Extraction des données retournées (création des variables $view et $beer)
                    }
                } else {
                    // Redirection vers l'accueil si aucun ID n'est fourni
                    header("Location: index.php?action=home");
                    exit;
                }
                break;

            case 'deleteBeer':
                // Suppression d'une bière
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $this->beerController->deleteBeer($id);
                }
                $view = 'home'; // Redirection vers l'accueil après suppression
                break;

            case 'home':
                // Affichage de la page d'accueil avec la liste des bières
                $this->beerController->index('home');
                exit();
                break;

            case 'signout':
                // Déconnexion de l'utilisateur
                $this->authController->signout();
                exit; // Arrêt de l'exécution après la déconnexion
                break;

            default:
                $view = '404'; // Affichage de la page 404 en cas d'action inconnue
        }

        // Chargement du layout principal contenant la vue sélectionnée
        require_once 'app/views/layout.php';
    }
}

// Création du routeur et traitement de la requête
$router = new Router();
$router->handleRequest();
