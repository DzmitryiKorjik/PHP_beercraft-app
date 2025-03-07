# Beercraft

Beercraft is a web application for managing and exploring different types of beers. Users can view, add, and manage beer information in a user-friendly interface.

## Project Structure
```
Beercraft/
├── app/
│   ├── controllers/                  # Application controllers
│   │   ├── AuthController.php        # Handles authentication and user management
│   │   └── BeerController.php        # Manages beer CRUD operations
│   │
│   ├── models/                       # Data models
│   │   ├── Beer.php                 # Beer entity and database operations 
│   │   └── User.php                 # User entity and authentication
│   │
│   ├── views/                        # Template files
│   │   ├── layouts/                 # Reusable layout templates
│   │   │   └── layout.php           # Main site layout
│   │   ├── auth/                    # Authentication related views
│   │   │   ├── login.php           # Login form
│   │   │   └── register.php        # Registration form
│   │   ├── beers/                   # Beer related views
│   │   │   ├── index.php           # Beer listing page
│   │   │   ├── show.php            # Single beer details
│   │   │   ├── create.php          # Add new beer form
│   │   │   └── edit.php            # Edit beer form
│   │   └── home.php                # Homepage view
│   │
│   └── config/                       # Configuration files
│       ├── database.php             # Database configuration
│       └── app.php                  # Application settings
│
├── assets/                           # Static resources
│   ├── css/                         # Stylesheets
│   ├── js/                          # JavaScript files
│   └── images/                      # Static images
│
├── uploads/                          # User uploaded files
│   └── beers/                       # Beer images
│
├── public/                           # Public directory
│   └── index.php                    # Application entry point
│
├── vendor/                           # Composer dependencies
├── .htaccess                        # Apache configuration
├── composer.json                    # Composer package file
└── README.md                        # Project documentation
```

## Features
- Beer catalog browsing
- Add new beers with details and images
- User authentication system
- Responsive design

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

## Usage
Access the application through your web browser and use the navigation to:
- Browse the beer catalog
- Add new beers (requires authentication)
- Manage existing entries

## Contributing
Contributions are welcome! Please feel free to submit a Pull Request.

## License

