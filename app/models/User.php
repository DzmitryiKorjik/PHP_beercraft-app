<?php
require_once 'Database.php';

class User
{
    private $db;

    // Constructeur : Initialise la connexion à la base de données
    public function __construct()
    {
        try {
            $this->db = (new Database())->getPDO();

            // Vérifie si la connexion a réussi
            // if (!$this->db) {
            //     die("Erreur : Connexion à la base de données impossible.");
            // } else {
            //     echo "Connexion réussie à la base de données !";
            // }
        } catch (Exception $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    // Méthode pour créer un nouvel utilisateur
    public function createUser($username, $email, $password)
    {
        // Préparation de la requête SQL
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");

        // Exécute la requête avec les données fournies
        if ($stmt->execute([$username, $email, $password, 'user'])) {
            return "Inscription réussie !"; // Si l'inscription réussie
        } else {
            return "Une erreur s'est produite lors de l'inscription."; // Si une erreur survient
        }
    }


    public function getUserByUsername($username)
    {
        // Prépare la requête pour récupérer un utilisateur par son nom d'utilisateur
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Vérifie si le mot de passe fourni correspond au mot de passe haché enregistré
    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }
}
