# Travel & Booking Website System

A comprehensive travel booking platform built with PHP and MySQL that allows users to browse and book hotels, flights, and tours with an integrated mock payment system. The system includes user profiles with booking history, review functionality, and robust admin and travel agency dashboards with reporting capabilities.

## Features

### User Features
- User registration and login system
- User profile management with booking history
- Hotel, flight, and tour browsing and booking
- Rating and review system for services
- Contact form for user queries and support
- Mock payment system with invoice generation
- No cancellation feature currently available

### Admin Features
- Comprehensive user management
- Package management for tours, flights, and hotels
- Booking management and tracking
- Query resolution system
- Reporting and analytics for data-driven decisions

### Travel Agency Features
- Tour package management
- Listing management for flights and hotels
- Booking tracking and management
- Analytics dashboard for performance metrics

## Technology Stack

- **Frontend**: PHP, HTML, CSS, JavaScript, AJAX
- **Backend**: PHP
- **Database**: MySQL (also compatible with Oracle or PostgreSQL)
- **Server**: Apache (XAMPP/WAMP/LAMP)

## Prerequisites

- PHP 7.0 or higher
- MySQL 5.6 or higher
- XAMPP/WAMP/LAMP server

## Installation & Setup

### 1. Database Setup

1. Start your XAMPP/WAMP server and ensure both Apache and MySQL services are running
2. Open phpMyAdmin (typically at http://localhost/phpmyadmin)
3. Create a new database (you can name it as per your project requirements)
4. Import the SQL file from the `DATABASE` folder in your project

### 2. Project Setup

1. Clone or download the project files
2. Extract the files to your web server's document root (e.g., `xampp/htdocs/` folder)
3. Rename the project folder to your preferred name (default is "hbwebsite")

### 3. Configuration

#### Database Configuration

Edit the `db_config.php` file in the Admin folder:

```php
$hname = 'localhost';
$uname = 'root';    // Change if you have a different MySQL username
$pass = '';         // Add your MySQL password if you have set one
$db = 'your_database_name';  // Change to your database name
```

#### Site URL Configuration

Edit the `essentials.php` file in the Admin folder:

```php
define('SITE_URL','http://localhost/your_project_folder_name/');
define('UPLOAD_IMAGE_PATH',$_SERVER['DOCUMENT_ROOT'].'/your_project_folder_name/images/');
```

Replace `your_project_folder_name` with your actual project folder name.

#### Mock Payment System

The system uses a simulated payment gateway that doesn't require external API keys or accounts. All payment transactions are simulated within the application, and invoices are automatically generated for completed bookings.

## Running the Project

1. Start your XAMPP/WAMP/LAMP server
2. Make sure both Apache and MySQL services are running
3. Open your web browser and navigate to:
   ```
   http://localhost/your_project_folder_name/
   ```
4. For admin panel access, navigate to:
   ```
   http://localhost/your_project_folder_name/admin/
   ```
5. For travel agency dashboard access, navigate to:
   ```
   http://localhost/your_project_folder_name/agency/
   ```

Default login credentials can be found in your database or use the test accounts created during database setup.

## Important Notes

- **Project URL**: Always make sure the SITE_URL in `essentials.php` matches your actual project path.
- **Image Upload**: If you encounter image upload issues, ensure the images directory has proper write permissions.
- **PHP GD Library**: If you encounter image processing errors during registration, you may need to enable the GD extension in your PHP configuration:
  1. Open your `php.ini` file (located in your PHP installation directory or in C:/xampp/php/php.ini)
  2. Find the line `;extension=gd` and remove the semicolon: `extension=gd`
  3. Restart your web server
- **Booking Cancellation**: Currently, booking cancellation is not available in the system.

## Troubleshooting Registration Issues

If user registration is not working:

1. Open your browser's developer console (F12)
2. Go to the Network tab
3. Attempt to register
4. Check the response for the AJAX call to `login_register.php`
5. If you see an error like `Fatal error: Uncaught Error: Call to undefined function imagecreatefromjpeg()`:
   - Open your PHP configuration file (`php.ini` in your PHP or XAMPP directory)
   - Find the line `;extension=gd` and remove the semicolon to make it `extension=gd`
   - Save the file and restart your web server

## Project Structure

```
hbwebsite/
├── admin/              # Admin dashboard files
├── agency/             # Travel agency dashboard files
├── DATABASE/           # SQL database file
├── ajax 				 # Code Backend connection with Database
├── images/             # Uploaded images
├── inc/                # Include files and dependencies
├── css/                # Stylesheet files
├── js/                 # JavaScript files
├── index.php           # Homepage
├── rooms.php          # Hotel listings
├── flights.php         # Flight listings
├── tours.php           # Tour listings
├── profile.php         # User profile and booking history
├── contact.php         # Contact form for queries
└── README.md           # This file
```

## Setting Up From Scratch

If you want to set up this project from the beginning:

1. **Set up XAMPP**:
   - Download and install XAMPP from [Apache Friends](https://www.apachefriends.org/index.html)
   - Start the XAMPP Control Panel and start Apache and MySQL services

2. **Create Project Directory**:
   - Navigate to `xampp/htdocs/`
   - Create a new folder for your project or extract the project files here

3. **Database Setup**:
   - Open http://localhost/phpmyadmin in your browser
   - Create a new database
   - Import the SQL file from the DATABASE folder

4. **Configuration**:
   - Follow the configuration steps mentioned above
   - Ensure all paths and URLs are correctly set according to your setup

5. **Test the Installation**:
   - Navigate to your project URL (http://localhost/your_project_folder_name/)
   - Test user registration and login
   - Test the booking features and mock payment system
   - Verify that user profiles display booking history
   - Check that the review and rating system works properly
   - Test the admin and travel agency dashboards for all management features

## System Features in Detail

### User System
- **Registration and Login**: Secure user authentication
- **Profile Management**: Users can update their personal information
- **Booking History**: View all past and current bookings
- **Review System**: Rate and review previously used services

### Booking System
- **Multi-service Booking**: Book hotels, flights, and tour packages
- **Payment Processing**: Simulated payment flow with invoice generation
- **Booking Confirmation**: Email and on-screen confirmation (note: actual email sending is disabled)

### Admin System
- **User Management**: View, edit, and manage user accounts
- **Package Management**: Add, edit, or remove travel packages and services
- **Booking Management**: Track and manage all bookings in the system
- **Query Resolution**: Handle user inquiries and support requests
- **Reporting**: Generate custom reports on bookings, revenue, popular destinations, etc.

### Travel Agency System
- **Package Creation**: Create and manage tour packages
- **Listing Management**: Manage hotel and flight listings
- **Booking Tracking**: Monitor bookings for agency offerings
- **Performance Analytics**: View data on package performance and bookings
