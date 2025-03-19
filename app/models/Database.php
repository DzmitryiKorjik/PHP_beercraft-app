<?php
// Chargement des dépendances via Composer
require_once __DIR__ . '/../../vendor/autoload.php'; 

// Inclusion du fichier de configuration global
require_once __DIR__ . '/../../config/config.php'; 

/**
 * Classe Database
 * Gère la connexion à la base de données via PDO en utilisant des variables d'environnement.
 */
class Database
{
    private static ?PDO $pdo = null; // Instance unique de connexion PDO
    private $connection; // Instance de connexion pour l'objet courant

    public function __construct() {
        // Initialisation de la connexion lors de la création d'une instance de la classe
        $this->connection = self::connect();
    }

    /**
     * Établit une connexion à la base de données en utilisant PDO.
     * Utilise le pattern Singleton pour éviter plusieurs connexions simultanées.
     * 
     * @return PDO Instance de connexion PDO
     * @throws Exception En cas d'erreur de connexion
     */
    public static function connect(): PDO
    {
        if (is_null(self::$pdo)) {
            // Chargement du fichier .env contenant les variables d'environnement
            $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2)); // Remonte de 2 niveaux dans l'arborescence
            $dotenv->load();

            // Vérification des variables d'environnement essentielles
            $required_vars = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'];
            foreach ($required_vars as $var) {
                if (!isset($_SERVER[$var])) { // Vérification via $_SERVER
                    throw new Exception("Variable d'environnement manquante : $var");
                }
            }

            // Récupération des valeurs des variables d'environnement
            $db_host = $_ENV['DB_HOST'];
            $db_name = $_ENV['DB_NAME'];
            $db_user = $_ENV['DB_USER'];
            $db_pass = $_ENV['DB_PASSWORD'];

            try {
                // Création de la connexion PDO avec gestion des erreurs et encodage UTF-8
                self::$pdo = new PDO(
                    "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
                    $db_user,
                    $db_pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active la gestion des erreurs
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4" // Définit l'encodage
                    ]
                );
            } catch (PDOException $e) {
                // Lève une exception en cas d'échec de connexion
                throw new Exception("Erreur de connexion : " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    /**
     * Retourne l'instance PDO existante.
     * 
     * @return PDO Instance PDO
     */
    public static function getPDO()
    {
        return self::connect();
    }

    /**
     * Déconnecte la base de données en supprimant l'instance PDO.
     */
    public static function disconnect(): void
    {
        self::$pdo = null;
    }

    /**
     * Exécute une requête SQL avec des paramètres.
     * 
     * @param string $sql La requête SQL à exécuter
     * @param array $params Paramètres pour la requête préparée
     * @return PDOStatement Résultat de la requête
     * @throws Exception En cas d'erreur de requête SQL
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            throw new Exception("Erreur de requête SQL : " . $e->getMessage());
        }
    }

    /**
     * Retourne l'ID de la dernière insertion en base de données.
     * 
     * @return string ID de l'enregistrement inséré
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }

    /**
     * Prépare une requête SQL pour exécution ultérieure.
     * 
     * @param string $query La requête SQL à préparer
     * @return PDOStatement Requête préparée
     */
    public function prepare($query) {
        return $this->connection->prepare($query);
    }
}
