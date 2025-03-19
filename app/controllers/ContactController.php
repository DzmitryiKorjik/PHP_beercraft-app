<?php

class ContactController {

    /**
     * Affiche la page de contact.
     * Cette méthode est utilisée pour afficher le formulaire de contact.
     */
    public function index() {
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
    public function submit() {
        // Valider les données d'entrée soumises par l'utilisateur
        $this->validateInput($_POST);

        try {
            // Logique de traitement du formulaire (par exemple, envoi d'email ou enregistrement dans la base de données)
            
            // Si le message est envoyé avec succès, on stocke un message de succès en session
            $_SESSION['success'] = "Message envoyé avec succès";
            
            // Redirection vers la page de contact avec le message de succès
            header('Location: ' . BASE_URL . '?action=contact');
            exit;  // Assure l'arrêt du script après la redirection
        } catch (Exception $e) {
            // Si une erreur se produit, on attrape l'exception et affiche un message d'erreur
            $_SESSION['errors'][] = "Une erreur est survenue lors de l'envoi du message: " . $e->getMessage();
            
            // Redirection vers la page d'erreur si une exception est levée
            header('Location: ' . BASE_URL . '?action=error');
            exit;
        }
    }

    /**
     * Valide les entrées du formulaire de contact.
     * Vérifie que le nom, l'email et le message sont valides.
     * @param array $data Les données soumises par l'utilisateur
     */
    private function validateInput($data) {
        $errors = [];

        // Vérifie si le nom est vide ou trop court (moins de 2 caractères)
        if (empty($data['name']) || strlen($data['name']) < 2) {
            $errors[] = "Le nom doit comporter au moins 2 caractères";
        }

        // Vérifie si l'email est vide ou invalide
        if (empty($data['email'])) {
            $errors[] = "L'email est requis";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email n'est pas valide";
        }

        // Vérifie si le message est vide ou trop court (moins de 10 caractères)
        if (empty($data['message']) || strlen($data['message']) < 10) {
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
