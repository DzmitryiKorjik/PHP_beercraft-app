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
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">Panneau de contrôle ▼</a>
                        <div class="dropdown-menu">
                            <a href="<?= BASE_URL ?>?action=order">Détails de la commande</a>
                            <a href="<?= BASE_URL ?>?action=users">Utilisateur</a>
                            <a href="<?= BASE_URL ?>?action=listItems">Liste de produits</a>
                            <a href="<?= BASE_URL ?>?action=addBeer">Ajouter un produit</a>
                        </div>
                    </li>
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
            <form action="<?= BASE_URL ?>" method="get">
                <input type="hidden" name="action" value="search">
                <input type="text" name="query" placeholder="Rechercher une bière..." required>
                <button type="submit">Rechercher</button>
            </form>
            <a href="<?= BASE_URL ?>?action=buyBeer"><img src="<?= BASE_URL ?>assets/icons/buy-icon.svg" alt="Panier" class="buy-icon"></a>
        </div>
    </div>
</header>

