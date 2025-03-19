<?php
require_once 'Database.php';

class User
{
    private $db;

    // Constructeur : Initialise la connexion à la base de données
    public function __construct()
    {
        try {
            // Création d'une instance de la classe Database et récupération de la connexion PDO
            $this->db = (new Database())->getPDO();

            // Vérification si la connexion a réussi (commenté ici car on gère l'exception dans le catch)
            // if (!$this->db) {
            //     die("Erreur : Connexion à la base de données impossible.");
            // } else {
            //     echo "Connexion réussie à la base de données !";
            // }
        } catch (Exception $e) {
            // En cas d'erreur de connexion, on arrête l'exécution et affiche l'erreur
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    // Méthode pour créer un nouvel utilisateur
    public function createUser($username, $email, $password)
    {
        // Préparation de la requête SQL pour insérer un nouvel utilisateur dans la base de données
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");

        // Exécution de la requête avec les données fournies (nom d'utilisateur, email, mot de passe, rôle par défaut 'user')
        if ($stmt->execute([$username, $email, $password, 'user'])) {
            // Si l'insertion réussie, renvoie un message de succès
            return "Inscription réussie !";
        } else {
            // Si une erreur survient lors de l'insertion, renvoie un message d'erreur
            return "Une erreur s'est produite lors de l'inscription.";
        }
    }

    // Méthode pour récupérer un utilisateur par son nom d'utilisateur
    public function getUserByUsername($username)
    {
        // Préparation de la requête pour récupérer un utilisateur en fonction de son nom d'utilisateur
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        // Exécution de la requête
        $stmt->execute([$username]);
        // Retourne l'utilisateur trouvé sous forme de tableau associatif
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Méthode pour vérifier si le mot de passe fourni correspond au mot de passe haché enregistré
    public function verifyPassword($password, $hashedPassword)
    {
        // Utilisation de password_verify pour comparer le mot de passe fourni et le mot de passe haché stocké
        return password_verify($password, $hashedPassword);
    }

    // Méthode pour récupérer tous les utilisateurs
    public function getAllUsers()
    {
        // Prépare et exécute la requête pour récupérer tous les utilisateurs
        $stmt = $this->db->query("SELECT `id`, `username`, `email`, `role`, `created_at` FROM users");
        // Retourne la liste de tous les utilisateurs sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
