<?php

/** 
 * Routeur principal de l'application
 * Gère toutes les routes et leurs actions associées
 * Contrôle le flux de l'application et la distribution des requêtes
 */

require_once 'app/controllers/AuthController.php';
require_once 'app/controllers/BeerController.php';
require_once 'app/controllers/BuyBeerController.php';
require_once 'app/controllers/CheckoutController.php'; // Add this line


/**
 * Classe Router
 * Gère les routes et les actions associées aux différentes pages de l'application.
 */
class Router
{
    private $authController;
    private $beerController;
    private $buyBeerController;
    private $checkoutController; // Add this line

    public function __construct()
    {
        // Initialisation des contrôleurs pour l'authentification et la gestion des bières
        $this->authController = new AuthController();
        $this->beerController = new BeerController();
        $this->buyBeerController = new BuyBeerController();
        $this->checkoutController = new CheckoutController(); // Add this line
    }

    /**
     * Traite la requête entrante et exécute l'action appropriée
     * @return void
     */
    public function handleRequest()
    {
        try {
            $action = $_GET['action'] ?? 'home';

            switch ($action) {
                // Gestion de l'inscription
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
                    
                case 'error':
                    $this->authController->error();
                    return;

                case 'contact':
                    $success = $_SESSION['success'] ?? null;
                    $errors = [];
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $this->authController->contact();
                    }
                    $viewData = [
                        'view' => 'contact',
                        'success' => $success,
                        'errors' => $errors
                    ];
                    extract($viewData);
                    require_once 'app/views/layout.php';
                    return;

                case 'addBeer':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $this->beerController->addBeer($_POST, $_FILES);
                    }
                    $view = 'addBeer';  // Afficher le formulaire d'ajout
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
                    $this->beerController->index();
                    $view = 'home';
                    break;

                case 'signout':
                    // Déconnexion de l'utilisateur
                    $this->authController->signout();
                    exit; // Arrêt de l'exécution après la déconnexion
                    break;

                case 'search':
                    $query = $_GET['query'] ?? '';
                    $this->beerController->search($query);
                    break;

                // Gestion du panier
                case 'buyBeer':
                    $id = $_GET['id'] ?? null;
                    // Ajout au panier si un ID est fourni
                    if ($id) {
                        $this->buyBeerController->buyBeer($id);
                    } else {
                        // Nous obtenons les données du panier et les transmettons à la vue
                        $cartData = $this->buyBeerController->buyBeer();
                        $cartItems = $cartData['cartItems'];
                        $total = $cartData['total'];
                        $view = 'cart'; // Changer pour 'cart'
                    }
                    break;

                case 'removeFromCart':
                    $id = $_GET['id'] ?? null;
                    if ($id) {
                        $this->buyBeerController->removeFromCart($id);
                    }
                    header('Location: ' . BASE_URL . '?action=buyBeer');
                    exit;
                    break;

                case 'updateQuantity':
                    $this->buyBeerController->updateQuantity();
                    header('Location: ' . BASE_URL . '?action=buyBeer');
                    exit;
                    break;

                case 'placeOrder':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Assuming $cartData is retrieved from the session or another source
                        $cartData = $this->buyBeerController->buyBeer();
                        $total = $cartData['total'];
                        $this->checkoutController->handleCheckout($total);
                    }
                    break;

                case 'checkout':
                    // Assuming $cartData is retrieved from the session or another source
                    $cartData = $this->buyBeerController->buyBeer();
                    $total = $cartData['total'];
                    $this->checkoutController->handleCheckout($total);
                    break;

                case 'paymentSuccess':
                    $view = 'paymentSuccess';
                    break;

                case 'paymentError':
                    $view = 'paymentError';
                    break;

                default:
                    require_once 'app/views/404.php';
                    return;
            }

            // Only for successful cases
            $viewData = [
                'view' => $view,
                'cartItems' => $cartItems ?? [],
                'total' => $total ?? 0
            ];
            extract($viewData);
            require_once 'app/views/layout.php';

        } catch (Exception $e) {
            $_SESSION['errors'] = ['Une erreur inattendue s\'est produite: ' . $e->getMessage()];
            header('Location: ' . BASE_URL . '?action=error');
            exit;
        }
    }
}

// Création du routeur et traitement de la requête
$router = new Router();
$router->handleRequest();
