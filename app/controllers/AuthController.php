<?php
require_once 'app/models/User.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController
{
    private $userModel;

    public function __construct()
    {
        // Initialisation du modèle utilisateur
        $this->userModel = new User();
    }

    // Méthode pour l'inscription des utilisateurs
    public function signup()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $errors = [];

            // Validation des données saisies
            if (empty($username) || empty($email) || empty($password)) {
                $errors[] = "Tous les champs sont requis.";
            }

            // Vérification de la validité de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse email n'est pas valide.";
            }

            // Vérification de la longueur du mot de passe
            if (strlen($password) < 8) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
            }

            // Vérification de la complexité du mot de passe
            if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
                $errors[] = "Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre.";
            }

            // Vérification de la confirmation du mot de passe
            if ($password !== $confirm_password) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }

            // Si aucune erreur, hachage du mot de passe et enregistrement de l'utilisateur
            if (empty($errors)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                if ($this->userModel->createUser($username, $email, $hashedPassword)) {
                    header('Location: ' . BASE_URL . '?action=signin');
                    exit;
                } else {
                    $errors[] = "Erreur lors de l'inscription.";
                }
            }

            // Redirection vers la page d'erreur en cas d'échec
            $_SESSION['errors'] = $errors;
            header('Location: ' . BASE_URL . '?action=error');
            exit;
        }
    }

    // Méthode pour la connexion des utilisateurs
    public function signin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $errors = [];

            // Vérification si tous les champs sont remplis
            if (empty($username) || empty($password)) {
                $errors[] = "Tous les champs sont requis.";
            }

            // Vérification des informations d'identification
            if (empty($errors)) {
                $user = $this->userModel->getUserByUsername($username);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['users'] = $user;
                    header('Location: ' . BASE_URL);
                    exit;
                } else {
                    $errors[] = "Nom d'utilisateur ou mot de passe incorrect.";
                }
            }

            // Redirection vers la page d'erreur en cas d'échec
            $_SESSION['errors'] = $errors;
            header('Location: ' . BASE_URL . '?action=error');
            exit;
        }
    }

    public function allUsersAction()
    {
        $users = $this->userModel->getAllUsers();
        return [
            'users' => $users,
            'view' => 'users'
        ];
        // $this->render('users', ['users' => $users]); 
    }


    // Méthode pour afficher les erreurs
    public function error()
    {
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']); // Nettoyage des erreurs après affichage
        $this->render('error', ['errors' => $errors]);
    }

    // Méthode pour la déconnexion des utilisateurs
    public function signout()
    {
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }

    // Méthode pour la gestion du formulaire de contact
    public function contact()
    {
        $errors = [];
        $success = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Nettoyage des données d'entrée
            $name = filter_var(trim($_POST["name"]));
            $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
            $message = filter_var(trim($_POST["message"]));

            // Validation des données du formulaire
            if (empty($name) || empty($email) || empty($message)) {
                $errors[] = "Tous les champs sont obligatoires.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email est invalide.";
            } elseif (strlen($message) < 10) {
                $errors[] = "Le message doit contenir au moins 10 caractères.";
            } elseif (strlen($message) > 1000) {
                $errors[] = "Le message ne doit pas dépasser 1000 caractères.";
            }

            // Si aucune erreur, envoi de l'email via PHPMailer
            if (empty($errors)) {
                require 'vendor/autoload.php';

                // Vérification des variables d'environnement pour l'email
                if (!isset($_ENV['EMAIL_USER']) || !isset($_ENV['EMAIL_PASS'])) {
                    throw new Exception("Configuration email manquante");
                }

                $mail = new PHPMailer(true);
                try {
                    // Configuration du serveur SMTP
                    $mail->SMTPDebug = 0; // Désactiver le mode debug en production
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = $_ENV['EMAIL_USER'];
                    $mail->Password = $_ENV['EMAIL_PASS'];
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Sécurisation SMTPS
                    $mail->Port = 465; // Port utilisé pour SMTPS

                    // Configuration de l'expéditeur et du destinataire
                    $mail->setFrom($_ENV['EMAIL_USER'], $name);
                    $mail->addReplyTo($email, $name);
                    $mail->addAddress('dzmitryimardovitch@gmail.com');

                    // Configuration du message
                    $mail->isHTML(true);
                    $mail->Subject = 'Nouveau message du site web';
                    $mail->Body    = "Nom: $name<br>Email: $email<br>Message:<br>$message";

                    // Désactivation de la vérification SSL pour les certificats auto-signés
                    $mail->SMTPOptions = [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        ]
                    ];

                    // Envoi de l'email
                    if (!$mail->send()) {
                        throw new Exception($mail->ErrorInfo);
                    }

                    $success = "Message envoyé avec succès !";
                } catch (Exception $e) {
                    error_log("Erreur Email: " . $e->getMessage());
                    $errors[] = "Impossible d'envoyer le message. Veuillez réessayer plus tard.";
                }
            }

            // Gestion des erreurs et succès
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['form_data'] = ['name' => $name, 'email' => $email, 'message' => $message];
            } else {
                $_SESSION['success'] = $success;
            }

            header('Location: ' . BASE_URL . '?action=contact');
            exit;
        }

        // Récupération des données du formulaire en cas d'erreur
        $form_data = $_SESSION['form_data'] ?? [];
        unset($_SESSION['form_data']);

        require "app/views/pages/contact.php";
    }

    // Méthode pour le rendu des vues
    private function render($view, $data = [])
    {
        extract($data);
        require "app/views/pages/{$view}.php";
    }
}
