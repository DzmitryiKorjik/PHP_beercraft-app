<div class="order-container">
    <?php if (!empty($orderItems)): ?>
        <h2>Liste des commandes</h2>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Id commande</th>
                    <th>Id utilisateur</th>
                    <th>Prix total</th>
                    <th>Statut</th>
                    <th>Date de commande</th>
                    <th>Id bière</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $orderItem): ?>
                    <tr>
                        <td><?= htmlspecialchars($orderItem['id']) ?></td>
                        <td><?= htmlspecialchars($orderItem['user_id']) ?></td>
                        <td><?= htmlspecialchars($orderItem['total']) ?></td>
                        <td><?= htmlspecialchars($orderItem['status']) ?></td>
                        <td><?= htmlspecialchars($orderItem['created_at']) ?></td>
                        <td><?= htmlspecialchars($orderItem['beer_id']) ?></td>
                        <td><?= htmlspecialchars($orderItem['quantity']) ?></td>
                        <td><?= htmlspecialchars($orderItem['price']) ?></td>
                        <td>
                            <form action="<?= BASE_URL ?>?action=deleteOrderItem" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($orderItem['id']) ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
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
