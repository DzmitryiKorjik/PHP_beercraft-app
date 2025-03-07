<?php
require_once 'Database.php'; // Inclusion du fichier de connexion à la base de données

class Beer
{
    private $db;

    public function __construct()
    {
        // Initialisation de la connexion à la base de données via l'objet Database
        $this->db = (new Database())->getPDO();
    }

    /**
     * Récupère toutes les bières de la base de données
     * @return array Tableau associatif contenant les informations des bières
     */
    public function getAllBeers()
    {
        $stmt = $this->db->prepare("SELECT
                                        `id`, 
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

    /**
     * Récupère une bière spécifique par son ID
     * @param int $id L'identifiant unique de la bière
     * @return array|false Retourne un tableau associatif ou false si non trouvé
     */
    public function getBeerById($id)
    {
        $stmt = $this->db->prepare("SELECT
                                        `id`, 
                                        `title`, 
                                        `origin`, 
                                        `alcohol`, 
                                        `description`, 
                                        `image`, 
                                        `average_price`
                                    FROM beer WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute une nouvelle bière à la base de données
     * @param array $data Tableau contenant les informations de la bière
     * @return bool Retourne true si l'insertion réussit, sinon false
     * @throws Exception En cas de nombre incorrect de paramètres
     */
    public function addBeer($data)
    {
        $stmt = $this->db->prepare("INSERT INTO beer (title, origin, alcohol, description, image, average_price) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'], 
            $data['origin'], 
            $data['alcohol'], 
            $data['description'], 
            $data['image'], 
            $data['average_price']
        ]);
    }
    

    /**
     * Met à jour les informations d'une bière
     * @param int $id L'identifiant unique de la bière à modifier
     * @param array $data Tableau contenant les nouvelles informations de la bière
     * @return bool Retourne true si la mise à jour réussit, sinon false
     * @throws Exception En cas de champs manquants
     */
    public function updateBeer($id, $data)
    {
        // Vérifie que tous les champs requis sont présents
        if (!isset($data['title']) || !isset($data['origin']) || !isset($data['alcohol']) || !isset($data['description']) || !isset($data['average_price'])) {
            throw new Exception("Champs obligatoires manquants");
        }

        // Vérifie si une nouvelle image est fournie, sinon conserve l'ancienne
        $image = isset($data['image']) && !empty($data['image']) ? $data['image'] : (isset($data['old_image']) ? $data['old_image'] : null);

        // Prépare les paramètres dans le bon ordre
        $params = [
            $data['title'],
            $data['origin'],
            $data['alcohol'],
            $data['description'],
            $image,
            $data['average_price'],
            $id
        ];

        // Prépare la requête SQL de mise à jour
        $stmt = $this->db->prepare("UPDATE beer SET title = ?, origin = ?, alcohol = ?, description = ?, image = ?, average_price = ? WHERE id = ?");

        // Exécute la requête avec les paramètres fournis
        return $stmt->execute($params);
    }

    /**
     * Supprime une bière et son image associée de la base de données
     * @param int $id L'identifiant unique de la bière à supprimer
     * @return bool Retourne true si la suppression réussit, sinon false
     */
    public function deleteBeer($id)
    {
        // Récupérer l'information sur l'image avant la suppression
        $stmt = $this->db->prepare("SELECT image FROM beer WHERE id = ?");
        $stmt->execute([$id]);
        $beer = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si l'image existe, la supprimer du système de fichiers
        if ($beer && $beer['image'] && file_exists($beer['image'])) {
            unlink($beer['image']);
        }

        // Supprimer l'enregistrement de la base de données
        $stmt = $this->db->prepare("DELETE FROM beer WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Recherche des bières par titre
     * @param string $query Terme de recherche
     * @return array Résultats de la recherche
     */
    public function searchBeers($query)
    {
        $query = "%{$query}%";
        $stmt = $this->db->prepare("SELECT
                                    `id`, 
                                    `title`, 
                                    `origin`, 
                                    `alcohol`, 
                                    `description`, 
                                    `image`, 
                                    `average_price`
                                FROM beer 
                                WHERE title LIKE :query 
                                ORDER BY created_at DESC");
        $stmt->bindParam(':query', $query, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
