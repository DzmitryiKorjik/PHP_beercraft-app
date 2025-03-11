<?php require_once 'app/views/layout.php'; ?>

<div class="container contact-form">
    <h2>Contact</h2>

    <!-- Affichage des erreurs s'il y en a -->
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="error">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p style="color: red; margin: 5px"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?> <!-- Suppression des erreurs après affichage -->
    <?php endif; ?>

    <!-- Affichage du message de succès s'il y en a un -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success">
            <p style="color: green; margin: 5px"><?= htmlspecialchars($_SESSION['success']) ?></p>
        </div>
        <?php unset($_SESSION['success']); ?> <!-- Suppression du message de succès après affichage -->
    <?php endif; ?>

    <!-- Formulaire de contact -->
    <form action="<?= BASE_URL ?>?action=contact" method="POST">
        <input type="text" id="name" name="name" placeholder="Votre nom :" required>
        <input type="email" id="email" name="email" placeholder="Votre email :" required>
        <textarea id="message" name="message" placeholder="Votre message :" required></textarea>
        <button type="submit">Envoyer</button>
    </form>
</div>
