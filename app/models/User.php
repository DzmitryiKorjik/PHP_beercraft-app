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
        // Vérifie si l'email est déjà enregistré
        if ($this->getUserByEmail($email)) {
            return "Cet email est déjà utilisé !"; // Retourne un message si l'email existe déjà
        }

        // Hachage du mot de passe avant insertion
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Préparation de la requête SQL
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");

        // Exécute la requête avec les données fournies
        if ($stmt->execute([$username, $email, $hashedPassword, 'user'])) {
            return "Inscription réussie !"; // Si l'inscription réussie
        } else {
            return "Une erreur s'est produite lors de l'inscription."; // Si une erreur survient
        }
    }


    // Méthode pour récupérer un utilisateur par son email
    public function getUserByEmail($email)
    {
        // Prépare la requête SQL pour chercher l'utilisateur par email
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        // Retourne les informations de l'utilisateur (ou false si non trouvé)
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Vérifie si le mot de passe fourni correspond au mot de passe haché enregistré
    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }
}
