<div class="container connexion_form">
    <!-- style="display: none;" -->
    <div id="login-form">
        <h2>Connexion</h2>
        <form id="signin-form" action="<?= BASE_URL ?>?action=signin" method="POST">
            <input type="text" id="username" name="username" placeholder="Username" required><br>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required><br>
            <button type="submit">Login</button>
        </form>

    </div>
</div>