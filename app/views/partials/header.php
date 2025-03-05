<header>
    <div class="header">
        <h1>Beercraft</h1>
        <nav>
            <ul>
                <li><a href="<?= BASE_URL ?>">Accueil</a></li>
                <li><a href="<?= BASE_URL ?>">Contact</a></li>
                <?php if (!isset($_SESSION['users'])): ?>
                    <li><a id="conn" href="#">Connexion</a></li>
                    <li><a id="register" href="#">Registre</a></li>
                <?php else: ?>
                    <li><a id="deconn" href="<?= BASE_URL ?>">Déconnexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="search-bar">
            <form id="search-bar" action="search.php" method="get">
                <input type="text" name="q" placeholder="Rechercher...">
                <button type="submit">Rechercher</button>
            </form>
        </div>
    </div>
</header>

<!-- Formulaires de connexion et d’inscription -->
<div id="signin-form" class="modal">
    <span class="close-btn" onclick="closeForm('signin-form')">&times;</span>
    <?php include 'app/views/signin.php'; ?>
</div>

<div id="signup-form" class="modal">
    <span class="close-btn" onclick="closeForm('signup-form')">&times;</span>
    <?php include 'app/views/signup.php'; ?>
</div>

