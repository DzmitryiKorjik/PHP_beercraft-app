<div class="container contact-form">
    <h2>Contact</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>?action=contact" method="POST">
        <input type="text" id="name" name="name" placeholder="Votre nom :" 
               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
        <input type="email" id="email" name="email" placeholder="Votre email :" 
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        <textarea id="message" name="message" placeholder="Votre message :" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        <button type="submit">Envoyer</button>
    </form>
</div>
