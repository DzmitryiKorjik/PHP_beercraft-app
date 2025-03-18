<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** 
 * Routeur principal de l'application
 * Gère toutes les routes et leurs actions associées
 * Contrôle le flux de l'application et la distribution des requêtes
 */

require_once 'app/controllers/AuthController.php';
require_once 'app/controllers/BeerController.php';
require_once 'app/controllers/BuyBeerController.php';
require_once 'app/controllers/CheckoutController.php';


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

    public function __construct()
    {
        // Initialisation des contrôleurs pour l'authentification et la gestion des bières
        $this->authController = new AuthController();
        $this->beerController = new BeerController();
        $this->buyBeerController = new BuyBeerController();
        $this->checkoutController = new CheckoutController();
    }

    /**
     * Traite la requête entrante et exécute l'action appropriée
     * @return void
     */
    public function handleRequest()
    {
        try {
            $action = $_GET['action'] ?? 'home';
            $view = ''; // Initialize $view to avoid undefined variable warning

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
                    // Убедитесь, что ошибка обрабатывается только один раз
                    if (!isset($_SESSION['error_handled'])) {
                        $_SESSION['error_handled'] = true;
                        $this->authController->error();
                    }
                    $errors = $_SESSION['errors'] ?? [];
                    $view = 'error';
                    break;

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

                case 'order':
                    if (!isset($_SESSION['users'])) {
                        header('Location: ' . BASE_URL . '?action=signin');
                        exit;
                    }

                    if ($_SESSION['users']['role'] === 'admin') {
                        $result = $this->buyBeerController->getAllOrders();
                        $viewData = [
                            'view' => 'adminOrders',
                            'orders' => $result['orders'] ?? []
                        ];
                    } else {
                        $cartData = $this->buyBeerController->buyBeer();
                        $viewData = [
                            'view' => 'cart',
                            'cartItems' => $cartData['cartItems'] ?? [],
                            'total' => floatval($cartData['total'] ?? 0)
                        ];
                    }
                    extract($viewData);
                    require_once 'app/views/layout.php';
                    return;

                case 'users':
                    $result = $this->authController->allUsersAction();
                    extract($result);
                    // $view = 'users';         
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
                    $beerId = $_GET['beerId'] ?? $_GET['id'] ?? null;
                    if ($beerId) {
                        $this->buyBeerController->removeFromCart($beerId);
                    }
                    if (isset($_GET['type']) && $_GET['type'] === 'order') {
                        header('Location: ' . BASE_URL . '?action=order');
                    } else {
                        header('Location: ' . BASE_URL . '?action=buyBeer');
                    }
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

                case 'listItems':
                    $items = $this->beerController->getAllBeers();
                    $view = 'listItems';
                    break;

                case 'deleteOrderItem':
                    if (!isset($_SESSION['users']) || $_SESSION['users']['role'] !== 'admin') {
                        header('Location: ' . BASE_URL . '?action=signin');
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
                    require_once 'app/views/pages/404.php';
                    return;
            }

            // Only for successful cases
            if ($view) {
                $viewData = [
                    'view' => $view,
                    'cartItems' => $cartItems ?? [],
                    'total' => $total ?? 0,
                    'errors' => $errors ?? []
                ];
                extract($viewData);
                require_once 'app/views/layout.php';
            }
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
