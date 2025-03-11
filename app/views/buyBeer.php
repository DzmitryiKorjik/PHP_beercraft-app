<section class="container">
    <div class="cart-container">
        <h1>Votre Panier</h1>
        
        <?php if (empty($cartItems)): ?>
            <p>Votre panier est vide</p>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <img src="<?= BASE_URL . '/' . $item['image'] ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                        <div class="cart-item-details">
                            <h3><?= htmlspecialchars($item['title']) ?></h3>
                            <p>Prix: <?= htmlspecialchars($item['average_price']) ?> €</p>
                            <form action="<?= BASE_URL ?>?action=updateQuantity" method="post" class="quantity-form">
                                <input type="hidden" name="beer_id" value="<?= $item['id'] ?>">
                                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="99">
                                <button type="submit">Mettre à jour</button>
                            </form>
                            <a href="<?= BASE_URL ?>?action=removeFromCart&id=<?= $item['id'] ?>" 
                               class="remove-item">Supprimer</a>
                        </div>
                        <div class="item-total">
                            <?= $item['average_price'] * $item['quantity'] ?> €
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="cart-total">
                <h2>Total: <?= number_format($total, 2) ?> €</h2>
                <button class="checkout-button">Procéder au paiement</button>
            </div>
        <?php endif; ?>
    </div>
</section>
