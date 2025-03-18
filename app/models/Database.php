<?php
require_once __DIR__ . '/../../vendor/autoload.php'; // Connexion Composer
require_once __DIR__ . '/../../config/config.php'; // Inclure les configurations globales

class Database
{
    private static ?PDO $pdo = null;
    private $connection;

    public function __construct() {
        $this->connection = self::connect();
    }

    public static function connect(): PDO
    {
        if (is_null(self::$pdo)) {
            // Chargement du fichier .env
            $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2)); // Поднимаемся на 2 уровня вверх
            $dotenv->load();

            // Vérification des variables d'environnement
            $required_vars = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'];
            foreach ($required_vars as $var) {
                if (!isset($_SERVER[$var])) {  // ⬅️ utiliser $_SERVER 
                    throw new Exception("Missing environment variable: $var");
                }
            }

            // Récupération des valeurs
            $db_host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
            $db_name = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
            $db_user = $_ENV['DB_USER'] ?? getenv('DB_USER');
            $db_pass = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');

            try {
                self::$pdo = new PDO(
                    "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
                    $db_user,
                    $db_pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                    ]
                );
            } catch (PDOException $e) {
                throw new Exception("Erreur de connexion : " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    public static function getPDO()
    {
        return self::connect(); // Return object PDO
    }

    public static function disconnect(): void
    {
        self::$pdo = null;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            throw new Exception("Database query error: " . $e->getMessage());
        }
    }

    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }

    public function prepare($query) {
        return $this->connection->prepare($query);
    }
}
