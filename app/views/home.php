<?php
/**
 * Page d'accueil
 * Affiche la liste des bières disponibles
 */
?>
<section class="container">
    <!-- Titre de la page -->
    <h1>Bienvenue sur Beercraft 🍺</h1>
    <p>Découvrez nos meilleures bières artisanales.</p>

    <!-- Liste des bières -->
    <?php if (!empty($beers)): ?>
        <div class="beer-list">
            <?php foreach ($beers as $beer): ?>
                <div class="beer-item">
                    <?php if (!empty($beer['image'])): ?>
                        <img src="<?= htmlspecialchars($beer['image']) ?>" alt="<?= htmlspecialchars($beer['title']) ?>" width="150">
                    <?php endif; ?>
                    <h2><?= htmlspecialchars($beer['title']) ?></h2>
                    <p><strong>Origine :</strong> <?= htmlspecialchars($beer['origin']) ?></p>
                    <p><strong>Alcool :</strong> <?= htmlspecialchars($beer['alcohol']) ?>%</p>
                    <p><?= nl2br(htmlspecialchars($beer['description'])) ?></p>
                    <p><strong>Prix moyen :</strong> <?= htmlspecialchars($beer['average_price']) ?> €</p>
                    
                    <?php if (!empty($_SESSION['users']) && $_SESSION['users']['role'] === 'admin'): ?>
                        <ul>
                            <!-- modifier -->
                            <li><a class="link-opacity-50-hover" href="<?= BASE_URL ?>?action=updateBeer&id=<?= $beer['id'] ?>">Modifier</a></li>
                            
                            <!-- delete -->
                            <li>
                                <a class="link-opacity-50-hover" 
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
