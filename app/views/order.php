<div class="order-container">
    <?php if (!empty($orderItems)): ?>
        <h2>Liste des commandes</h2>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Commande</th>
                    <th>ID Bière</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $orderItem): ?>
                    <tr>
                        <td><?= htmlspecialchars($orderItem['id']) ?></td>
                        <td><?= htmlspecialchars($orderItem['order_id']) ?></td>
                        <td><?= htmlspecialchars($orderItem['beer_id']) ?></td>
                        <td><?= htmlspecialchars($orderItem['quantity']) ?></td>
                        <td><?= htmlspecialchars($orderItem['price']) ?></td>
                        <td>
                            <form action="<?= BASE_URL ?>?action=deleteOrderItem" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article de la commande ?');">
                                <input type="hidden" name="order_item_id" value="<?= htmlspecialchars($orderItem['id']) ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune commande trouvée.</p>
    <?php endif; ?>
</div>
