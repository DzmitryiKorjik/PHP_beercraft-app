<div class="error-container" id="unique-error-container">
    <h1>Une erreur s'est produite</h1>
    
    <?php if (!empty($_SESSION['errors'])): ?>
        <ul class="error-list">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <a href="<?= BASE_URL ?>" class="btn-home">Retour à l'accueil</a>
</div>
