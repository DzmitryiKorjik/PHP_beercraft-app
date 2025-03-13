## 📌 Modèle Logique des Données (MLD)

- Users(id, username, email, password, role, created_at)
- Beer(id, name, origin, alcohol, description, image, average_price, created_at)
- Comment(id, content, rating, user_id → Users.id, beer_id → Beer.id, created_at)
- Category(id, name, created_at)
- Beer_Category(beer_id → Beer.id, category_id → Category.id)
- Cart(id, user_id → Users.id, beer_id → Beer.id, quantity)
- Orders(id, user_id → Users.id, total, status, created_at)
- Order_Items(id, order_id → Orders.id, beer_id → Beer.id, quantity, price)

## 📌 Modèle Conceptuel des Données (MCD)

- Utilisateur (users) : Gère les utilisateurs avec un rôle.
- Bière (beer) : Contient les informations sur chaque bière.
- Commentaire (comment) : Associe un utilisateur et une bière avec un avis.
- Catégorie (category) : Permet de classer les bières.
- Relation bière-catégorie (beer_category) : Associe une bière à plusieurs catégories.
- Panier (cart) : Stocke les articles en attente d’achat par un utilisateur.
- Commande (orders) : Enregistre une commande d’un utilisateur.
- Articles de commande (order_items) : Détaille les articles d’une commande.

