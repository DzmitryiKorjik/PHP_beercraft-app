<?php
require_once 'Database.php';

class Order {
    private $db;

    public function __construct() {
        $this->db = Database::getPDO(); // Utilisation de l'instance PDO directement
    }

    /**
     * Récupère toutes les commandes avec détails de la bière et de l'utilisateur.
     * 
     * @return array Liste des commandes ou un tableau vide en cas d'erreur.
     */
    public function getAllOrders(): array {
        $query = "SELECT c.id, c.quantity, c.beer_id, c.user_id, 
                        b.title AS beer_title, b.average_price AS price, 
                        u.email, (c.quantity * b.average_price) AS total_price 
                 FROM cart c 
                 JOIN beer b ON c.beer_id = b.id 
                 JOIN users u ON c.user_id = u.id 
                 ORDER BY c.id DESC";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('❌ Erreur lors de la récupération des commandes : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Supprime une commande par l'ID de la bière et l'email de l'utilisateur.
     * 
     * @param int $beerId ID de la bière.
     * @param string $userEmail Email de l'utilisateur.
     * @return bool Succès ou échec de la suppression.
     */
    public function deleteOrder(int $beerId, string $userEmail): bool {
        $query = "DELETE c FROM cart c 
                 INNER JOIN users u ON c.user_id = u.id 
                 WHERE c.beer_id = :beer_id 
                 AND u.email = :email";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':beer_id' => $beerId,
                ':email' => $userEmail
            ]);

            return $stmt->rowCount() > 0; // Vérifie si une ligne a été supprimée
        } catch (PDOException $e) {
            error_log('❌ Erreur lors de la suppression de la commande : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une commande par son ID.
     * 
     * @param int $orderId ID de la commande.
     * @return bool Succès ou échec de la suppression.
     */
    public function deleteOrderById(int $orderId): bool {
        $query = "DELETE FROM cart WHERE id = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $orderId]);

            return $stmt->rowCount() > 0; // Vérifie si une ligne a été supprimée
        } catch (PDOException $e) {
            error_log('❌ Erreur lors de la suppression de la commande : ' . $e->getMessage());
            return false;
        }
    }
}
