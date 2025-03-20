<?php
/** 
 * Routeur principal de l'application
 * Gère toutes les routes et leurs actions associées
 * Contrôle le flux de l'application et la distribution des requêtes
 */

require_once 'app/controllers/AuthController.php';  // Inclut le contrôleur d'authentification
require_once 'app/controllers/BeerController.php';  // Inclut le contrôleur des bières
require_once 'app/controllers/BuyBeerController.php';  // Inclut le contrôleur de l'achat de bière
require_once 'app/controllers/CheckoutController.php';  // Inclut le contrôleur de paiement
require_once 'app/controllers/ContactController.php';  // Inclut le contrôleur de contact


/**
 * Classe Router
 * Gère les routes et les actions associées aux différentes pages de l'application.
 */
class Router
{
    private $authController;
    private $beerController;
    private $buyBeerController;
    private $checkoutController;
    private $contactController;

    public function __construct()
    {
        // Initialisation des contrôleurs pour l'authentification et la gestion des bières
        $this->authController = new AuthController();
        $this->beerController = new BeerController();
        $this->buyBeerController = new BuyBeerController();
        $this->checkoutController = new CheckoutController();
        $this->contactController = new ContactController();
    }

    /**
     * Traite la requête entrante et exécute l'action appropriée
     * @return void
     */
    public function handleRequest()
    {
        try {
            // Récupère l'action demandée depuis l'URL, ou par défaut 'home'
            $action = $_GET['action'] ?? 'home';
            $view = ''; // Initialise $view pour éviter des avertissements de variable non définie

            // Sélectionne l'action en fonction de l'URL (paramètre 'action')
            switch ($action) {
                // Gestion de l'inscription
                case 'signup':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Si la requête est POST, traite le formulaire d'inscription
                        $this->authController->signup($_POST);
                    }
                    $view = 'signup'; // Affichage du formulaire d'inscription
                    break;

                // Gestion de la connexion
                case 'signin':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Si la requête est POST, traite le formulaire de connexion
                        $this->authController->signin($_POST);
                    }
                    $view = 'signin'; // Affichage du formulaire de connexion
                    break;

                // Gestion des erreurs
                case 'error':
                    $errors = $_SESSION['errors'] ?? [];
                    if (isset($_SESSION['error_handled'])) {
                        unset($_SESSION['errors']);
                        unset($_SESSION['error_handled']);
                        if (!headers_sent()) {
                            header('Location: ' . BASE_URL . '?action=contact');
                            exit;
                        }
                    }
                    $_SESSION['error_handled'] = true;
                    $view = 'error'; 
                    break;

                // Gestion de la page contact     
                case 'contact':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->contactController->submit($_POST);
                    } else {
                        $this->contactController->index();
                    }
                    return;
                    break;

                // Ajout d'une bière
                case 'addBeer':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $this->beerController->addBeer($_POST, $_FILES); // Ajout d'une bière via le formulaire
                    }
                    $view = 'addBeer';  // Affichage du formulaire d'ajout
                    break;

                // Gestion des commandes
                case 'order':
                    if (!isset($_SESSION['users'])) {
                        header('Location: ' . BASE_URL . '?action=signin'); // Redirection vers la page de connexion si non authentifié
                        exit;
                    }

                    if ($_SESSION['users']['role'] === 'admin') {
                        // Si l'utilisateur est un administrateur, on affiche toutes les commandes
                        $result = $this->buyBeerController->getAllOrders();
                        $viewData = [
                            'view' => 'adminOrders',
                            'orders' => $result['orders'] ?? []
                        ];
                    } else {
                        // Sinon, on affiche les éléments du panier
                        $cartData = $this->buyBeerController->buyBeer();
                        $viewData = [
                            'view' => 'cart',
                            'cartItems' => $cartData['cartItems'] ?? [],
                            'total' => floatval($cartData['total'] ?? 0)
                        ];
                    }
                    extract($viewData);
                    require_once 'app/views/layout.php'; // Rendu de la vue correspondante
                    return;

                // Liste des utilisateurs (uniquement pour admin)
                case 'users':
                    $result = $this->authController->allUsersAction(); // Récupération de la liste des utilisateurs
                    extract($result);
                    break;

                // Mise à jour d'une bière existante
                case 'updateBeer':
                    $id = $_GET['id'] ?? null;
                    if ($id) {
                        $result = $this->beerController->updateBeer($id, $_POST, $_FILES); // Mise à jour de la bière
                        if (is_array($result)) {
                            extract($result); // Extraction des résultats pour la vue
                        }
                    } else {
                        header("Location: index.php?action=home"); // Redirection vers la page d'accueil si aucun ID
                        exit;
                    }
                    break;

                // Suppression d'une bière
                case 'deleteBeer':
                    $id = $_GET['id'] ?? null;
                    if ($id) {
                        $this->beerController->deleteBeer($id); // Suppression de la bière
                    }
                    $view = 'home'; // Redirection vers la page d'accueil après suppression
                    break;

                // Affichage de la page d'accueil avec la liste des bières
                case 'home':
                    $this->beerController->index(); // Récupération de la liste des bières
                    $view = 'home';
                    break;

                // Déconnexion de l'utilisateur
                case 'signout':
                    $this->authController->signout(); // Traitement de la déconnexion
                    exit;
                    break;

                // Recherche de bières
                case 'search':
                    $query = $_GET['query'] ?? '';
                    $this->beerController->search($query); // Exécution de la recherche de bière
                    break;

                // Ajout au panier
                case 'buyBeer':
                    $id = $_GET['id'] ?? null;
                    if ($id) {
                        $this->buyBeerController->buyBeer($id); // Ajout de la bière au panier
                    } else {
                        // Récupération des données du panier
                        $cartData = $this->buyBeerController->buyBeer();
                        $cartItems = $cartData['cartItems'];
                        $total = $cartData['total'];
                        $view = 'cart'; // Affichage du panier
                    }
                    break;

                // Retrait d'un élément du panier
                case 'removeFromCart':
                    $beerId = $_GET['beerId'] ?? $_GET['id'] ?? null;
                    if ($beerId) {
                        $this->buyBeerController->removeFromCart($beerId); // Suppression de l'élément du panier
                    }
                    header('Location: ' . BASE_URL . '?action=buyBeer'); // Redirection vers le panier
                    exit;
                    break;

                // Mise à jour de la quantité dans le panier
                case 'updateQuantity':
                    $this->buyBeerController->updateQuantity(); // Mise à jour de la quantité
                    header('Location: ' . BASE_URL . '?action=buyBeer');
                    exit;
                    break;

                // Passation de la commande
                case 'placeOrder':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $cartData = $this->buyBeerController->buyBeer();
                        $total = $cartData['total'];
                        $this->checkoutController->handleCheckout($total); // Traitement du paiement
                    }
                    break;

                // Affichage de la page de paiement réussi
                case 'paymentSuccess':
                    $view = 'paymentSuccess';
                    break;

                // Affichage de la page d'erreur de paiement
                case 'paymentError':
                    $view = 'paymentError';
                    break;

                // Liste des items de bière
                case 'listItems':
                    $items = $this->beerController->getAllBeers(); // Récupération de toutes les bières
                    $view = 'listItems';
                    break;

                // Suppression d'un élément de commande (admin uniquement)
                case 'deleteOrderItem':
                    if (!isset($_SESSION['users']) || $_SESSION['users']['role'] !== 'admin') {
                        header('Location: ' . BASE_URL . '?action=signin'); // Redirection si non admin
                        exit;
                    }
                    $orderId = $_GET['id'] ?? null;
                    if ($orderId) {
                        $orderModel = new Order();
                        if ($orderModel->deleteOrderById($orderId)) {
                            $_SESSION['success'] = "Commande supprimée avec succès";
                        } else {
                            $_SESSION['error'] = "Erreur lors de la suppression de la commande";
                        }
                    }
                    header('Location: ' . BASE_URL . '?action=order');
                    exit;
                    break;

                default:
                    // Si l'action demandée n'existe pas, affiche une page d'erreur 404
                    require_once 'app/views/pages/404.php';
                    return;
            }

            // Rendu de la vue si tout est ok
            if ($view) {
                $viewData = [
                    'view' => $view,
                    'cartItems' => $cartItems ?? [],
                    'total' => $total ?? 0,
                    'errors' => $errors ?? []
                ];
                extract($viewData);
                require_once 'app/views/layout.php'; // Rendu de la vue avec le layout
            }
        } catch (Exception $e) {
            // Gestion des exceptions : affiche une erreur et redirige vers la page d'erreur
            $_SESSION['errors'] = ['Une erreur inattendue s\'est produite: ' . $e->getMessage()];
            header('Location: ' . BASE_URL . '?action=error');
            exit;
        }
    }
}

// Création du routeur et traitement de la requête
$router = new Router();
$router->handleRequest(); // Exécution du traitement de la requête
