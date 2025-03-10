<?php 
require_once 'Database.php';

/**
 * Modèle de gestion du panier
 * Gère toutes les opérations liées au panier d'achat
 */
class BuyBeer {
    private $db;

    // Constructeur - initialise la connexion à la base de données
    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Ajoute un produit au panier
     * @param int $beerId ID de la bière
     * @param int $userId ID de l'utilisateur
     */
    public function addToCart($beerId, $userId) {
        $sql = "INSERT INTO cart (user_id, beer_id, quantity) 
                VALUES (?, ?, 1) 
                ON DUPLICATE KEY UPDATE quantity = quantity + 1";
        return $this->db->query($sql, [$userId, $beerId]);
    }

    /**
     * Récupère tous les articles du panier d'un utilisateur
     * @param int $userId ID de l'utilisateur
     * @return array Liste des articles du panier
     */
    public function getCartItems($userId) {
        $sql = "SELECT b.*, c.quantity FROM beer b 
                JOIN cart c ON b.id = c.beer_id 
                WHERE c.user_id = ?";
        return $this->db->query($sql, [$userId])->fetchAll();
    }

    /**
     * Supprime un article du panier
     * @param int $beerId ID de la bière à supprimer
     * @param int $userId ID de l'utilisateur
     */
    public function removeFromCart($beerId, $userId) {
        $sql = "DELETE FROM cart WHERE user_id = ? AND beer_id = ?";
        return $this->db->query($sql, [$userId, $beerId]);
    }

    /**
     * Met à jour la quantité d'un article dans le panier
     * @param int $beerId ID de la bière
     * @param int $userId ID de l'utilisateur
     * @param int $quantity Nouvelle quantité
     */
    public function updateQuantity($beerId, $userId, $quantity) {
        $sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND beer_id = ?";
        return $this->db->query($sql, [$quantity, $userId, $beerId]);
    }

    /**
     * Calcule le total du panier
     * @param int $userId ID de l'utilisateur
     * @return float Montant total du panier
     */
    public function getCartTotal($userId) {
        $sql = "SELECT SUM(b.average_price * c.quantity) as total 
                FROM cart c JOIN beer b ON c.beer_id = b.id 
                WHERE c.user_id = ?";
        return $this->db->query($sql, [$userId])->fetch()['total'] ?? 0;
    }

    // Passer la commande
    public function placeOrder($userId) {
        // Obtenez les éléments du panier
        $cartItems = $this->getCartItems($userId);
        if (empty($cartItems)) {
            return "Votre panier est vide.";
        }

        // Calculer le total de la commande
        $total = $this->getCartTotal($userId);

        // Insérer la commande
        $sql = "INSERT INTO orders (user_id, total) VALUES (?, ?)";
        $this->db->query($sql, [$userId, $total]);
        $orderId = $this->db->lastInsertId();

        // Insérer les éléments de la commande
        foreach ($cartItems as $item) {
            $sql = "INSERT INTO order_items (order_id, beer_id, quantity, price) 
                    VALUES (?, ?, ?, ?)";
            $this->db->query($sql, [$orderId, $item['id'], $item['quantity'], $item['average_price']]);
        }

        // Vider le panier après la commande
        $sql = "DELETE FROM cart WHERE user_id = ?";
        $this->db->query($sql, [$userId]);

        return "Commande passée avec succès.";
    }
}
?>
