# Beercraft

Beercraft is an e-commerce web application specialized in craft beer sales. It allows users to browse, order, and manage their beer purchases.

## Features

### For Users
- Browse beer catalog
- Search for specific beers
- Create account and login
- Shopping cart management
- Update cart quantities
- Place orders
- View order history

### For Administrators
- Manage beer catalog
- Add new beers
- Edit existing beers
- Delete beers
- User management
- Order management

## Project Structure
```
Beercraft/
├── app/
│   ├── controllers/               
│   │   ├── AuthController.php       
|   |   ├── BuyBeerController.php    
│   │   └── BeerController.php       
│   │
│   ├── models/ 
|   |   ├── Database.php                       
│   │   ├── Beer.php                 
│   │   ├── User.php                 
|   |   └── BuyBeer.php              
│   │
│   ├── views/                      
│   │   ├── partials/   
│   │   |   ├── head.php     
│   │   |   ├── header.php 
│   │   │   └── footer.php     
│   │   ├── 404.php                        
│   │   ├── addBeer.php           
│   │   ├── cert.php        
│   │   ├── contact.php                  
│   │   ├── home.php           
│   │   ├── layout.php        
│   │   ├── signin.php       
│   │   └── signup.php        
│   │   ├── updateBeer.php.php                
│   │
│   └── routes/                           
│       └── router.php              
│
├── assets/                       
│   ├── css/                  
│   ├── js/                      
│   └── icons/                 
│
├── uploads/                        
│   └── beers/                     
│
├── config/                          
│   └── config.php               
│
├── .gitignore
├── .env
├── index.php
├── vendor/                       
├── .htaccess                  
├── composer.json               
└── README.md                      
```

## Technical Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Docker (optional)

## Installation
1. Clone the repository
2. Configure your web server to point to the project directory
3. Set up the database
4. Copy and configure environment variables
5. Run the application

## Author

-   Name : MARDOVITCH Dzmitryi
-   Formation : Web and Mobile Web Development.
-   Objective : Validation of skills in creating and deploying web applications.

## Usage
Access the application through your web browser and use the navigation to:
- Browse the beer catalog
- Add new beers (requires authentication)
- Manage existing entries

## Contributing
Contributions are welcome! Please feel free to submit a Pull Request.



