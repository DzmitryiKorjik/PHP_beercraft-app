<?php
require_once 'Database.php';

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getPDO();
    }

    public function getOrder()
    {
        $stmt = $this->db->prepare("SELECT 
                                        `id`,
                                        `user_id`,
                                        `beer_id`,
                                        `quantity`
                                        FROM cart ORDER BY id DESC
                                ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteOrder($id)
    {
        try {
            $this->db->beginTransaction();

            // Then delete the order
            $stmtOrder = $this->db->prepare("DELETE FROM `cart` WHERE `id` = :id");
            $stmtOrder->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtOrder->execute();

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error deleting order: " . $e->getMessage());
            return false;
        }
    }
}
