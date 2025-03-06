<?php
/**
 * Vue pour la modification d'une bière
 * Affiche un formulaire pré-rempli avec les données actuelles de la bière
 */
?>
<?php if (isset($beer) && !empty($beer)): ?>
    <!-- Formulaire de modification -->
    <form class="update-beer-form" method="POST" action="<?= BASE_URL ?>?action=updateBeer&id=<?= htmlspecialchars($beer['id']) ?>" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($beer['title'] ?? '') ?>" required>

        <label for="origin">Origin:</label>
        <input type="text" name="origin" value="<?= htmlspecialchars($beer['origin'] ?? '') ?>" required>

        <label for="alcohol">Alcohol:</label>
        <input type="number" name="alcohol" value="<?= htmlspecialchars($beer['alcohol'] ?? '') ?>" required>

        <label for="description">Description:</label>
        <textarea name="description" required><?= htmlspecialchars($beer['description'] ?? '') ?></textarea>

        <label for="average_price">Average Price:</label>
        <input type="number" name="average_price" value="<?= htmlspecialchars($beer['average_price'] ?? '') ?>" required>

        <label for="image">Image:</label>
        <input type="file" name="image">

        <div class="form-buttons">
            <input type="submit" value="Update Beer" class="btn-update">
            <a href="<?= BASE_URL ?>?action=home" class="btn-cancel">Annuler</a>
        </div>
    </form>
<?php else: ?>
    <div class="error-message">
        <p>Bière non trouvée.</p>
        <?php if (isset($beer)): ?>
            <pre><?php print_r($beer); ?></pre>
        <?php else: ?>
            <p>Variable $beer non définie.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>
