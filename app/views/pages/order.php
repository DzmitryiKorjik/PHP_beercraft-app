<div class="order-container">
    <h2>Votre commande</h2>
    <?php if (!empty($cartItems)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['title'] ?? '') ?></td>
                        <td><?= number_format(floatval($item['price'] ?? 0), 2) ?> €</td>
                        <td>
                            <form action="<?= BASE_URL ?>?action=updateQuantity" method="POST" class="d-inline">
                                <input type="hidden" name="beer_id" value="<?= $item['beer_id'] ?? '' ?>">
                                <input type="number" name="quantity" value="<?= intval($item['quantity'] ?? 1) ?>" min="1" max="99" class="form-control" style="width: 70px" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td><?= number_format(floatval(($item['price'] ?? 0) * ($item['quantity'] ?? 1)), 2) ?> €</td>
                        <td>
                            <a href="<?= BASE_URL ?>?action=removeFromCart&id=<?= $item['beer_id'] ?? '' ?>" class="btn btn-danger btn-sm">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td><strong><?= number_format(floatval($total ?? 0), 2) ?> €</strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div class="text-right mt-3">
            <a href="<?= BASE_URL ?>?action=checkout" class="btn btn-primary">Procéder au paiement</a>
        </div>
    <?php else: ?>
        <p>Votre panier est vide.</p>
    <?php endif; ?>
</div>
