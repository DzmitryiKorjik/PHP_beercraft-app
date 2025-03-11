<?php
require_once 'app/models/Beer.php';

/**
 * Contrôleur pour la gestion des bières
 */
class BeerController
{
    private $model;
    private $username;

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
        // Récupérer le nom d'utilisateur depuis la session
        $this->username = isset($_SESSION['users']['username']) ? $_SESSION['users']['username'] : 'Visiteur';

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
        $errors = [];

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
                    $errors[] = "Erreur lors du téléchargement de l'image.";
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
                $errors[] = "Erreur lors de l'ajout du produit.";
            }

            if (!empty($errors)) {
                $this->handleError($errors);
            }
        } catch (Exception $e) {
            $this->handleError(["Erreur: " . $e->getMessage()]);
        }
    }

    /**
     * Supprime une bière de la base de données
     * @param int $id L'identifiant unique de la bière
     */
    public function deleteBeer($id)
    {
        $errors = [];
        try {
            if ($this->model->deleteBeer($id)) {
                header("Location: index.php?action=home"); // Redirection après suppression
                exit;
            } else {
                $this->handleError(["Erreur lors de la suppression de la bière."]);
            }
        } catch (Exception $e) {
            $this->handleError(["Erreur: " . $e->getMessage()]);
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
        $errors = [];

        try {
            // Récupérer la bière existante par son ID
            $beer = $this->model->getBeerById($id);

            if (!$beer) {
                $errors[] = "Bière non trouvée.";
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
                    $errors[] = "Erreur lors de la mise à jour de la bière.";
                }
            }

            if (!empty($errors)) {
                $this->handleError($errors);
            }

            // Retourner la vue et les données de la bière sans affichage direct
            return ['view' => 'updateBeer', 'beer' => $beer];
        } catch (Exception $e) {
            $this->handleError(["Erreur: " . $e->getMessage()]);
        }
    }

    /**
     * Recherche des bières
     * @param string $query Terme de recherche
     */
    public function search($query)
    {
        $beers = $this->model->searchBeers($query);
        $view = 'home';
        require_once __DIR__ . "/../views/layout.php";
    }

    private function isValidImage($file) {
        // Obtenir le type MIME du fichier
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        // Types de fichiers autorisés
        $allowedTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/bmp',
            'image/svg+xml'
        ];

        // Taille maximale du fichier (5MB)
        $maxSize = 5 * 1024 * 1024;

        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception("Type de fichier non autorisé. Types acceptés: JPG, PNG, GIF, WEBP, BMP, SVG");
        }

        if ($file['size'] > $maxSize) {
            throw new Exception("Fichier trop volumineux. Maximum 5MB.");
        }

        return true;
    }

    private function handleError($errors) {
        $_SESSION['errors'] = $errors;
        header('Location: ' . BASE_URL . '?action=error');
        exit;
    }
}
