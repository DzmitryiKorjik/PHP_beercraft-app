<?php
require_once 'app/models/User.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function signup()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Réception des données du formulaire
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Validation des données (des contrôles supplémentaires peuvent être ajoutés)
            if (empty($username) || empty($email) || empty($password)) {
                echo "Tous les champs sont requis.";
                return;
            }

            // Vérifie si l'adresse email est déjà utilisée
            if ($this->userModel->getUserByEmail($email)) {
                echo "Cet email est déjà utilisé.";
                return;
            }

            // Validation du format de l'adresse email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "L'adresse email n'est pas valide.";
                return;
            }

            // Validation du mot de passe (des contrôles supplémentaires peuvent être ajoutés)
            if (strlen($password) < 8) {
                echo "Le mot de passe doit contenir au moins 8 caractères.";
                return;
            }

            // Validation du format du mot de passe (des contrôles supplémentaires peuvent être ajoutés)
            // if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            //     echo "Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre.";
            //     return;
            // }

            // Validation de la correspondance des mots de passe
            if ($password!== $confirm_password) {
                echo "Les mots de passe ne correspondent pas.";
                return;
            }

            // Hachez le mot de passe avant de l'enregistrer
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Créer un utilisateur dans la base de données
            if ($this->userModel->createUser($username, $email, $hashedPassword)) {
                header('Location: ' . BASE_URL);
                exit;
            } else {
                echo "Erreur lors de l'inscription.";
            }
        }
        $this->render('signup');
    }

    public function signin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Réception des données du formulaire
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Validation des données
            if (empty($username) || empty($password)) {
                echo "Tous les champs sont requis.";
                return;
            }

            // Vérifier si l'utilisateur existe
            $user = $this->userModel->getUserByEmail($username);
            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user'] = $user;
                header('Location: ' . BASE_URL);
                exit;
            } else {
                echo "Email ou mot de passe incorrect.";
            }
        }
        $this->render('signin');
    }

    public function signout()
    {
        session_start();
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }

    // Méthode de rendu des vues
    private function render($view, $data = [])
    {
        extract($data);
        require "app/views/{$view}.php";
    }
}
?>