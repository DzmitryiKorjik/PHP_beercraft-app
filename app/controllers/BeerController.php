<?php
require_once 'app/models/Beer.php';

/**
 * Contrôleur pour la gestion des bières
 */
class BeerController
{
    private $model;

    public function __construct()
    {
        // Initialisation du modèle de gestion des bières
        $this->model = new Beer();
    }

    /**
     * Affiche la liste des bières
     * @param string $view La vue à charger (par défaut : 'home')
     */
    public function index($view = 'home')
    {
        $beers = $this->model->getAllBeers();
        require_once __DIR__ . "/../views/layout.php";
    }

    /**
     * Ajoute une nouvelle bière
     * @param array $data Les données du formulaire
     * @param array $files Les fichiers uploadés (image)
     */
    public function addBeer($data, $files)
    {
        try {
            // Gestion de l'image
            if (isset($files['image']) && $files['image']['error'] === 0) {
                // Vérification du type et de la taille
                $this->isValidImage($files['image']);

                $uploadDir = 'uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $extension = pathinfo($files['image']['name'], PATHINFO_EXTENSION);
                $imageName = time() . '_' . uniqid() . '.' . $extension;
                $targetFile = $uploadDir . $imageName;

                if (!move_uploaded_file($files['image']['tmp_name'], $targetFile)) {
                    throw new Exception("Erreur lors du téléchargement de l'image.");
                }

                $data['image'] = $targetFile;
            } else {
                $data['image'] = NULL; // Si aucune image n'est fournie
            }

            // Appeler la fonction du modèle pour ajouter la bière
            if ($this->model->addBeer($data)) {
                header("Location: index.php?action=home");
                exit;
            } else {
                echo "Erreur lors de l'ajout du produit.";
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return;
        }
    }

    /**
     * Supprime une bière de la base de données
     * @param int $id L'identifiant unique de la bière
     */
    public function deleteBeer($id)
    {
        try {
            if ($this->model->deleteBeer($id)) {
                header("Location: index.php?action=home"); // Redirection après suppression
                exit;
            } else {
                echo "Erreur lors de la suppression de la bière.";
            }
        } catch (Exception $e) {
            echo "Erreur lors de la suppression : " . $e->getMessage();
        }
    }

    /**
     * Met à jour les informations d'une bière
     * @param int $id L'identifiant unique de la bière à modifier
     * @param array $data Les nouvelles données de la bière
     * @param array $files Les nouveaux fichiers uploadés (image)
     * @return array Retourne la vue et les données de la bière
     */
    public function updateBeer($id, $data = [], $files = [])
    {
        try {
            // Récupérer la bière existante par son ID
            $beer = $this->model->getBeerById($id);

            if (!$beer) {
                echo "Bière non trouvée.";
                return;
            }

            // Vérifier si de nouvelles données sont fournies
            if (!empty($data)) {
                // Gestion de l'image
                if (isset($files['image']) && $files['image']['error'] === 0) {
                    // Vérification du type et de la taille
                    $this->isValidImage($files['image']);

                    $uploadDir = 'uploads/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $extension = pathinfo($files['image']['name'], PATHINFO_EXTENSION);
                    $imageName = time() . '_' . uniqid() . '.' . $extension;
                    $targetFile = $uploadDir . $imageName;

                    if (move_uploaded_file($files['image']['tmp_name'], $targetFile)) {
                        // Supprime l'ancienne image si elle existe
                        if (!empty($beer['image']) && file_exists($beer['image'])) {
                            unlink($beer['image']);
                        }
                        $data['image'] = $targetFile;
                    }
                } else {
                    $data['image'] = $beer['image']; // Garde l'ancienne image
                }

                // Mise à jour des informations de la bière
                if ($this->model->updateBeer($id, $data)) {
                    header("Location: index.php?action=home"); // Redirection après mise à jour
                    exit;
                } else {
                    echo "Erreur lors de la mise à jour de la bière.";
                }
            }

            // Retourner la vue et les données de la bière sans affichage direct
            return ['view' => 'updateBeer', 'beer' => $beer];
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return;
        }
    }

    private function isValidImage($file) {
        // Получаем MIME-тип файла
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        // Разрешенные типы файлов
        $allowedTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/bmp',
            'image/svg+xml'
        ];

        // Максимальный размер файла (5MB)
        $maxSize = 5 * 1024 * 1024;

        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception("Type de fichier non autorisé. Types acceptés: JPG, PNG, GIF, WEBP, BMP, SVG");
        }

        if ($file['size'] > $maxSize) {
            throw new Exception("Fichier trop volumineux. Maximum 5MB.");
        }

        return true;
    }
}
