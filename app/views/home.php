<section class="container">
    <h1>Bienvenue sur Beercraft 🍺</h1>
    <p>Découvrez nos meilleures bières artisanales.</p>

    <?php if (!empty($beers)): ?>
        <div class="beer-list">
            <?php foreach ($beers as $beer): ?>
                <div class="beer-item">
                    <h2><?= htmlspecialchars($beer['title']) ?></h2>
                    <p><strong>Origine :</strong> <?= htmlspecialchars($beer['origin']) ?></p>
                    <p><strong>Alcool :</strong> <?= htmlspecialchars($beer['alcohol']) ?>%</p>
                    <p><?= nl2br(htmlspecialchars($beer['description'])) ?></p>
                    <?php if (!empty($beer['image'])): ?>
                        <img src="images/<?= htmlspecialchars($beer['image']) ?>" alt="<?= htmlspecialchars($beer['title']) ?>" width="150">
                    <?php endif; ?>
                    <p><strong>Prix moyen :</strong> <?= htmlspecialchars($beer['average_price']) ?> €</p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucune bière disponible pour le moment.</p>
    <?php endif; ?>
</section>
