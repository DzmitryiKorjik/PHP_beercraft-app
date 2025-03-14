<div class="order-container liste-items">
    <h2>Liste des produits</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Origin</th>
                <th>Alcohol</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['id']) ?></td>
                    <td><?= htmlspecialchars($item['title']) ?></td>
                    <td><?= htmlspecialchars($item['origin']) ?></td>
                    <td><?= htmlspecialchars($item['alcohol']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>