# Airline Reservation System

A complete web-based airline reservation system built with HTML, CSS, JavaScript, PHP, and MySQL.

## Features

- User registration and login
- Flight search by origin, destination, and date
- View available flights with detailed information
- Book flights
- View booking history
- Admin panel for flight management
- Responsive design for all devices

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

## Installation

1. Clone this repository to your web server directory
2. Create a MySQL database named `airline_reservation`
3. Import the `database.sql` file to set up the database structure
4. Configure database connection in `config/database.php`
5. Access the website through your web server

## Directory Structure

```
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── config/
│   └── database.php
├── includes/
│   ├── header.php
│   └── footer.php
├── admin/
│   ├── index.php
│   ├── flights.php
│   └── bookings.php
├── index.php
├── login.php
├── register.php
├── search.php
├── flights.php
├── booking.php
└── history.php
```

## Security Features

- Password hashing
- SQL injection prevention
- XSS protection
- Session management
- Input validation

## License

This project is licensed under the MIT License. 