<div class="container">
    <form class="update-beer-form" method="POST" action="<?= BASE_URL ?>?action=addBeer" enctype="multipart/form-data">
        <h2>Ajouter une nouvelle bière</h2>

        <label for="title">Titre:</label>
        <input type="text" name="title" required>

        <label for="origin">Origine:</label>
        <input type="text" name="origin" required>

        <label for="alcohol">Alcool (%):</label>
        <input type="number" step="0.1" name="alcohol" required>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea>

        <label for="average_price">Prix moyen (€):</label>
        <input type="number" step="0.01" name="average_price" required>

        <label for="image">Image:</label>
        <input type="file" name="image">

        <div class="form-buttons">
            <input type="submit" value="Ajouter" class="btn-update">
            <a href="<?= BASE_URL ?>?action=home" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>