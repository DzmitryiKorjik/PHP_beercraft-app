<header>
    <div class="header">
        <h1 class="title">Beercraft</h1>
        <button class="burger-menu" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <nav class="main-nav">
            <ul>
                <li><a href="<?= BASE_URL ?>">Accueil</a></li>
                <li><a href="<?= BASE_URL ?>?action=contact">Contact</a></li>
                <?php if (!empty($_SESSION['users']) && $_SESSION['users']['role'] === 'admin'): ?>
                    <li><a href="<?= BASE_URL ?>?action=addBeer">Ajouter un produit</a></li>
                <?php endif; ?>
                <?php if (!empty($_SESSION['users'])): ?>
                    <li><a href="<?= BASE_URL ?>?action=signout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>?action=signin">Se connecter</a></li>
                    <li><a href="<?= BASE_URL ?>?action=signup">S'inscrire</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="search-bar">
            <form action="search.php" method="get">
                <input type="text" name="q" placeholder="Rechercher...">
                <button type="submit">Rechercher</button>
            </form>
        </div>
    </div>
</header>

