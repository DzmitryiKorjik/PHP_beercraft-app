<div class="container contact-form">
    <h2>Contact</h2>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success">
            <p><?= htmlspecialchars($success) ?></p>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>?action=contact" method="POST">
        <input type="text" id="name" name="name" placeholder="Votre nom :" required>
        <input type="email" id="email" name="email" placeholder="Votre email :" required>
        <textarea id="message" name="message" placeholder="Votre message :" required></textarea>

        <button type="submit">Envoyer</button>
    </form>
</section>