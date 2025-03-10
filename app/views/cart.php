<?php
/**
 * Vue du panier d'achat
 * Affiche le contenu du panier et permet les actions suivantes :
 * - Visualisation des articles
 * - Modification des quantités
 * - Suppression d'articles
 * - Calcul du total
 */
?>
<section class="container">
    <!-- Conteneur principal du panier -->
    <div class="cart-container">
        <!-- En-tête du panier -->
        <h1>Votre Panier</h1>
        
        <?php if (empty($cartItems)): ?>
            <!-- Message affiché si le panier est vide -->
            <p>Votre panier est vide</p>
        <?php else: ?>
            <!-- Liste des articles dans le panier -->
            <div class="cart-items">
                <?php foreach ($cartItems as $item): ?>
                    <!-- Affichage de chaque article -->
                    <div class="cart-item">
                        <!-- Image du produit -->
                        <img src="<?= BASE_URL . '/' . $item['image'] ?>" 
                             alt="<?= htmlspecialchars($item['title']) ?>">
                        
                        <!-- Détails de l'article -->
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

                        <!-- Total pour cet article -->
                        <div class="item-total">
                            <?= $item['average_price'] * $item['quantity'] ?> €
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Résumé du panier et actions -->
            <div class="cart-total">
                <h2>Total: <?= number_format($total, 2) ?> €</h2>
                <form action="<?= BASE_URL ?>?action=placeOrder" method="POST">
                    <button type="submit" class="checkout-button">Procéder au paiement</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>
