<?php 
require_once 'Database.php';

class BuyBeer {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
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
     * @param int $userId ID de l'utilisateur
     */
    public function getCartItems($userId) {
        $sql = "SELECT b.*, c.quantity FROM beer b 
                JOIN cart c ON b.id = c.beer_id 
                WHERE c.user_id = ?";
        return $this->db->query($sql, [$userId])->fetchAll();
    }

    /**
     * @param int $beerId ID de la bière
     * @param int $userId ID de l'utilisateur
     */
    public function removeFromCart($beerId, $userId) {
        $sql = "DELETE FROM cart WHERE user_id = ? AND beer_id = ?";
        return $this->db->query($sql, [$userId, $beerId]);
    }

    /**
     * @param int $beerId ID de la bière
     * @param int $userId ID de l'utilisateur
     * @param int $quantity Nouvelle quantité
     */
    public function updateQuantity($beerId, $userId, $quantity) {
        $sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND beer_id = ?";
        return $this->db->query($sql, [$quantity, $userId, $beerId]);
    }

    /**
     * @param int $userId ID de l'utilisateur
     */
    public function getCartTotal($userId) {
        $sql = "SELECT SUM(b.average_price * c.quantity) as total 
                FROM cart c JOIN beer b ON c.beer_id = b.id 
                WHERE c.user_id = ?";
        $total = $this->db->query($sql, [$userId])->fetch()['total'] ?? 0;
        return number_format($total, 2) . ' €';
    }
}
?>
