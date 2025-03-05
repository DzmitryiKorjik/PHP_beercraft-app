<header>
    <div class="header text-bg-dark p-3">
        <h1 class="text-start fs-3 fst-italic">Beercraft</h1>
        <!-- <img class="icons_beercraft" src="<?= BASE_URL ?>assets/icons/beercraft.jpg" alt="beercraft"> -->
        <nav class="d-flex">
            <ul>
                <li><a class="link-light link-opacity-50-hover" href="<?= BASE_URL ?>">Accueil</a></li>
                <li><a class="link-light link-opacity-50-hover" href="<?= BASE_URL ?>?action=contact">Contact</a></li>
                <?php if (!empty($_SESSION['users']) && $_SESSION['users']['role'] === 'admin'): ?>
                    <li>
                        <a class="link-light link-opacity-50-hover" href="<?= BASE_URL ?>?action=create">Ajouter une recette</a>
                    </li>
                <?php endif; ?>
                <?php if (!empty($_SESSION['users'])): ?>
                    <li>
                        <a class="link-light link-opacity-50-hover" href="<?= BASE_URL ?>?action=signout">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li>
                        <a class="link-light link-opacity-50-hover" href="<?= BASE_URL ?>?action=signin">Se connecter</a>
                    </li>
                    <li>
                        <a class="link-light link-opacity-50-hover" href="<?= BASE_URL ?>?action=signup">S'inscrire</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="d-flex justify-content-end align-items-center">
            <form class="px-3" action="search.php" method="get">
                <input class="p-1" type="text" name="q" placeholder="Rechercher...">
            </form>
            <button class="btn btn-outline-light" type="submit">Rechercher</button>
        </div>
    </div>
</header>

