<?php

class ContactController
{

    /**
     * Affiche la page de contact.
     * Cette méthode est utilisée pour afficher le formulaire de contact.
     */
    public function index()
    {
        // Nettoie toute erreur résiduelle dans la session
        unset($_SESSION['errors']);

        // Données de vue pour la page de contact
        $viewData = [
            'view' => 'contact'  // Spécifie la vue à afficher (contact)
        ];

        // Extraction des données de vue et inclusion du layout
        extract($viewData);
        require_once 'app/views/layout.php';  // Charge le layout qui inclut la vue contact
    }

    /**
     * Soumet le formulaire de contact.
     * Valide les données et traite l'envoi du message.
     */
    public function submit($postData)
    {
        // Valider les données d'entrée soumises par l'utilisateur
        $this->validateInput($postData);

        try {
            // Logique de traitement du formulaire ici
            $_SESSION['success'] = "Message envoyé avec succès";
            header('Location: ' . BASE_URL . '?action=contact');
            exit;
        } catch (Exception $e) {
            $_SESSION['errors'][] = "Une erreur est survenue: " . $e->getMessage();
            header('Location: ' . BASE_URL . '?action=error');
            exit;
        }
    }

    /**
     * Valide les entrées du formulaire de contact.
     * Vérifie que le nom, l'email et le message sont valides.
     * @param array $data Les données soumises par l'utilisateur
     */
    private function validateInput($data)
    {
        $errors = [];

        // Vérifie si le nom est vide ou trop court (moins de 2 caractères)
        if (!isset($data['name']) || strlen(trim($data['name'])) < 2) {
            $errors[] = "Le nom doit comporter au moins 2 caractères";
        }

        // Vérifie si l'email est vide ou invalide
        if (!isset($data['email']) || empty($data['email'])) {
            $errors[] = "L'email est requis";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email n'est pas valide";
        }

        // Vérifie si le message est vide ou trop court (moins de 10 caractères)
        if (!isset($data['message']) || strlen(trim($data['message'])) < 10) {
            $errors[] = "Le message doit comporter au moins 10 caractères";
        }

        // Si des erreurs sont détectées, on les enregistre dans la session et on redirige vers la page d'erreur
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ' . BASE_URL . '?action=error');
            exit;
        }
    }
}
