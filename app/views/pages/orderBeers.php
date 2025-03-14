<?php if (!empty($orderBeers)): ?>
    <h2>Liste des bières commandées</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Commande</th>
                <th>ID Bière</th>
                <th>Quantité</th>
                <th>Prix</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderBeers as $orderBeer): ?>
                <tr>
                    <td><?= htmlspecialchars($orderBeer['id']) ?></td>
                    <td><?= htmlspecialchars($orderBeer['order_id']) ?></td>
                    <td><?= htmlspecialchars($orderBeer['beer_id']) ?></td>
                    <td><?= htmlspecialchars($orderBeer['quantity']) ?></td>
                    <td><?= htmlspecialchars($orderBeer['price']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucune bière trouvée pour cette commande.</p>
<?php endif; ?>
