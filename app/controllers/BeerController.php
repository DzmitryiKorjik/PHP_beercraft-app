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
        // Vérifier si tous les champs obligatoires sont remplis
        if (empty($data['title']) || empty($data['origin']) || empty($data['alcohol']) || empty($data['description']) || empty($data['average_price'])) {
            echo "Veuillez remplir tous les champs obligatoires.";
            return;
        }

        // Vérifier si une image a été uploadée
        if (isset($files['image']) && $files['image']['error'] === 0) {
            $targetDir = "uploads/"; // Dossier de stockage des images
            $imageName = time() . '_' . basename($files['image']['name']);
            $targetFile = $targetDir . $imageName;

            // Déplacer l'image vers le dossier défini
            if (move_uploaded_file($files['image']['tmp_name'], $targetFile)) {
                $data['image'] = $targetFile; // Enregistrer le chemin de l'image dans la base de données
            } else {
                echo "Erreur lors du téléchargement de l'image.";
                return;
            }
        } else {
            $data['image'] = NULL; // Si aucune image n'est fournie
        }

        // Appeler la méthode du modèle pour ajouter une bière
        if ($this->model->addBeer([
            ':title' => $data['title'],
            ':origin' => $data['origin'],
            ':alcohol' => $data['alcohol'],
            ':description' => $data['description'],
            ':image' => $data['image'],
            ':average_price' => $data['average_price']
        ])) {
            header("Location: index.php?action=home"); // Redirection après ajout
            exit;
        } else {
            echo "Erreur lors de l'ajout du produit.";
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
        // Récupérer la bière existante par son ID
        $beer = $this->model->getBeerById($id);

        if (!$beer) {
            echo "Bière non trouvée.";
            return;
        }

        // Vérifier si de nouvelles données sont fournies
        if (!empty($data)) {
            // Gestion de l'image : vérifier si une nouvelle image est uploadée
            if (isset($files['image']) && $files['image']['error'] === 0) {
                $targetDir = "uploads/";
                $imageName = time() . '_' . basename($files['image']['name']);
                $targetFile = $targetDir . $imageName;

                if (move_uploaded_file($files['image']['tmp_name'], $targetFile)) {
                    $data['image'] = $targetFile; // Mise à jour du chemin de l'image
                }
            } else {
                // Conserver l'ancienne image si aucune nouvelle n'est fournie
                $data['image'] = isset($data['old_image']) ? $data['old_image'] : null;
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
    }
}
