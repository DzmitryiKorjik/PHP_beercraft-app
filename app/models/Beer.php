<?php
require_once 'Database.php';

class Beer
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getPDO();
    }

    public function getAllBeers()
    {
        $stmt = $this->db->prepare("SELECT 
                                        `title`, 
                                        `origin`, 
                                        `alcohol`, 
                                        `description`, 
                                        `image`, 
                                        `average_price`
                                    FROM beer ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBeerById($id)
    {
        $stmt = $this->db->prepare("SELECT 
                                        `title`, 
                                        `origin`, 
                                        `alcohol`, 
                                        `description`, 
                                        `image`, 
                                        `average_price`
                                    FROM beer WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addBeer($data)
    {
        $stmt = $this->db->prepare("INSERT INTO beer (title, origin, alcohol, description, image, average_price) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute($data);
    }

    public function updateBeer($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE beer SET title = ?, origin = ?, alcohol = ?, description = ?, image = ?, average_price = ? WHERE id = ?");
        return $stmt->execute(array_merge($data, [$id]));
    }

    public function deleteBeer($id)
    {
        $stmt = $this->db->prepare("DELETE FROM beer WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
