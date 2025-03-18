<?php
require_once 'Database.php';

class Order {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllOrders() {
        $query = "SELECT c.id, c.quantity, c.beer_id, c.user_id, 
                        b.title as beer_title, b.average_price as price, 
                        u.email, (c.quantity * b.average_price) as total_price 
                 FROM cart c 
                 JOIN beer b ON c.beer_id = b.id 
                 JOIN users u ON c.user_id = u.id 
                 ORDER BY c.id DESC";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error getting orders: ' . $e->getMessage());
            return [];
        }
    }

    public function deleteOrder($beerId, $userEmail) {
        $query = "DELETE c FROM cart c 
                 INNER JOIN users u ON c.user_id = u.id 
                 WHERE c.beer_id = :beer_id 
                 AND u.email = :email";
        try {
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([
                ':beer_id' => $beerId,
                ':email' => $userEmail
            ]);
            
            if (!$result) {
                error_log('Failed to delete order: No rows affected');
                return false;
            }
            
            return true;
        } catch (PDOException $e) {
            error_log('Error deleting order: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteOrderById($orderId) {
        $query = "DELETE FROM cart WHERE id = :id";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([':id' => $orderId]);
        } catch (PDOException $e) {
            error_log('Error deleting order: ' . $e->getMessage());
            return false;
        }
    }
}
