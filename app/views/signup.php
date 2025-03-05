<div class="container">
    <div id="register-form">
        <h2>Inscription</h2>
        <form id="signup-form" action="<?= BASE_URL ?>?action=signup" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
            <button type="submit">S'inscrire</button>
        </form>
    </div>
</div>