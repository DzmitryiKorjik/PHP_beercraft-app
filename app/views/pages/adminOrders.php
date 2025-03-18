<div class="order-container">
    <?php if (!empty($orders)): ?>
        <h2>Liste des commandes</h2>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email client</th>
                    <th>Bière</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['email']) ?></td>
                        <td><?= htmlspecialchars($order['beer_title']) ?></td>
                        <td><?= htmlspecialchars($order['quantity']) ?></td>
                        <td><?= number_format($order['price'], 2) ?> €</td>
                        <td><?= number_format($order['total_price'], 2) ?> €</td>
                        <td>
                            <a href="<?= BASE_URL ?>?action=deleteOrderItem&id=<?= $order['id'] ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune commande trouvée.</p>
    <?php endif; ?>
</div>