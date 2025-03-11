<?php

/**
 * Page d'accueil
 * Affiche la liste des bières disponibles
 */
$username = isset($_SESSION['users']['username']) ? $_SESSION['users']['username'] : 'Visiteur';
?>
<section class="container">
    <!-- Titre de la page -->
    <div class="titre">
        <h1><?= $username ?>, Bienvenue sur Beercraft 🍺</h1>
        <p>Découvrez nos meilleures bières artisanales.</p>
    </div>

    <!-- Liste des bières -->
    <?php if (!empty($beers)): ?>
        <div class="beer-list">
            <?php foreach ($beers as $beer): ?>
                <div class="beer-item">
                    <?php if (!empty($beer['image']) && file_exists($beer['image'])): ?>
                        <img src="<?= htmlspecialchars(BASE_URL . '/' . $beer['image']) ?>"
                            alt="<?= htmlspecialchars($beer['title']) ?>"
                            loading="lazy">
                    <?php else: ?>
                        <img src="<?= BASE_URL ?>/uploads/default-beer.jpg"
                            alt="Image par défaut"
                            loading="lazy">
                    <?php endif; ?>
                    <h2><?= htmlspecialchars($beer['title']) ?></h2>
                    <p><strong>Origine :</strong> <?= htmlspecialchars($beer['origin']) ?></p>
                    <p><strong>Alcool :</strong> <?= htmlspecialchars($beer['alcohol']) ?>%</p>
                    <p><?= nl2br(htmlspecialchars($beer['description'])) ?></p>
                    <p><strong>Prix moyen :</strong> <?= htmlspecialchars($beer['average_price']) ?> €</p>

                    <?php if (!empty($_SESSION['users']) && $_SESSION['users']['role'] === 'user'): ?>
                        <ul>
                            <li>
                                <a class="link-opacity-50-hover" href="<?= BASE_URL?>?action=buyBeer&id=<?= $beer['id']?>">
                                    Ajouter au panier
                                </a>
                            </li>
                        </ul>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['users']) && $_SESSION['users']['role'] === 'admin'): ?>
                        <ul>
                            <!-- modifier -->
                            <li><a class="link-opacity-50-hover btn-modifier" href="<?= BASE_URL ?>?action=updateBeer&id=<?= $beer['id'] ?>">Modifier</a></li>

                            <!-- delete -->
                            <li>
                                <a class="link-opacity-50-hover btn-delete"
                                    href="<?= BASE_URL ?>?action=deleteBeer&id=<?= $beer['id'] ?>"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette bière ?')">Supprimer</a>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucune bière disponible pour le moment.</p>
    <?php endif; ?>
</section>