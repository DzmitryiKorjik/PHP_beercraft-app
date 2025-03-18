# BEERCRAFT

## Description

Beercraft is an e-commerce web application specializing in craft beer sales. It allows users to browse, order, and manage their beer purchases seamlessly.

## Learning Objectives

This project aims to:
- Strengthen web development skills using PHP.
- Understand the structure of an e-commerce platform.
- Implement the MVC architecture for better code organization.
- Integrate payment processing with Stripe.

## Main Features

- **User Authentication**: Secure login and registration system.
- **Product Management**: Admins can add, edit, and delete beers.
- **Shopping Cart**: Users can add beers to their cart and proceed to checkout.
- **Order Processing**: Manages user orders and order status.
- **Payment Integration**: Secure payment processing with Stripe API.

## Project Structure

```
Beercraft/
|
├── README.md
├── app
│   ├── controllers
│   │   ├── AuthController.php
│   │   ├── BeerController.php
│   │   ├── BuyBeerController.php
│   │   └── CheckoutController.php
│   ├── models
│   │   ├── Beer.php
│   │   ├── BuyBeer.php
│   │   ├── Database.php
│   │   ├── StripeModel.php
│   │   └── User.php
│   ├── routes
│   │   └── router.php
│   └── views
│       ├── checkout.php
│       ├── layout.php
│       ├── pages
│       │   ├── 404.php
│       │   ├── addBeer.php
│       │   ├── cart.php
│       │   ├── contact.php
│       │   ├── error.php
│       │   ├── home.php
│       │   ├── listItems.php
│       │   ├── order.php
│       │   ├── orderBeers.php
│       │   ├── orderConfirmation.php
│       │   ├── paymentError.php
│       │   ├── paymentSuccess.php
│       │   ├── signin.php
│       │   ├── signup.php
│       │   ├── updateBeer.php
│       │   └── users.php
│       └── partials
│           ├── footer.php
│           ├── head.php
│           └── header.php
├── assets
│   ├── css
│   │   └── style.css
│   ├── icons
│   │   └── buy-icon.svg
│   ├── img
│   │   └── page-404.jpg
│   └── js
│       ├── main.js
│       └── script.js
├── composer.json
├── composer.lock
├── config
│   └── config.php
├── index.php
├── robots.txt
└── uploads
    ├── 1741976530_67d473d2b55b4.jpg
    └── default-beer.jpg
```

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/DzmitryiKorjik/PHP_beercraft-app.git
   ```

2. **Navigate to the project directory:**
   ```bash
   cd PHP_beercraft-app
   ```

3. **Install dependencies:**
   ```bash
   composer install
   ```

4. **Set up the database:**
   - Create a MySQL database named `beercraft`.
   - Import the provided SQL file to initialize the tables.

5. **Configure environment settings:**
   - Rename `config.php.example` to `config.php`.
   - Update the database credentials and Stripe API keys.

6. **Start the local server:**
   ```bash
   php -S localhost:8000
   ```

7. **Access the application:**
   Open a browser and go to `http://localhost:8000`.

## Deployment

1. **Choose a hosting provider** that supports PHP and MySQL.
2. **Upload files** via FTP or SSH.
3. **Set up a production database** and import the database schema.
4. **Configure environment settings** in `config.php`.
5. **Ensure proper file permissions**, especially for `uploads/`.
6. **Enable SSL (HTTPS)** for secure transactions.

## Author

- **Name:** Dzmitryi Mardovitch
- **Education:** Web and Mobile Development
- **Objective:** Validation of web application development and deployment skills.

## Future Improvements 🚀

- **Enhanced UI/UX** for a modern and responsive design.
- **Admin Dashboard** for managing products and orders.
- **User Profiles** to track order history and preferences.
- **Advanced Search** with filtering and sorting options.
- **Multilingual Support** to expand user accessibility.
- **Dockerization** for an optimized deployment environment.

---

