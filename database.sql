-- Create database
CREATE DATABASE IF NOT EXISTS airline_reservation;
USE airline_reservation;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_admin BOOLEAN DEFAULT FALSE
);

-- Airlines table
CREATE TABLE IF NOT EXISTS airlines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(10) NOT NULL
);

-- Flights table
CREATE TABLE IF NOT EXISTS flights (
    id INT PRIMARY KEY AUTO_INCREMENT,
    flight_number VARCHAR(20) NOT NULL,
    airline_id INT,
    origin VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    departure_time DATETIME NOT NULL,
    arrival_time DATETIME NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    available_seats INT NOT NULL,
    FOREIGN KEY (airline_id) REFERENCES airlines(id)
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    flight_id INT,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('confirmed', 'cancelled', 'completed') DEFAULT 'confirmed',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (flight_id) REFERENCES flights(id)
);

-- Insert sample airlines
INSERT INTO airlines (name, code) VALUES
('Air India', 'AI'),
('IndiGo', '6E'),
('SpiceJet', 'SG'),
('Vistara', 'UK');

-- Insert sample flights
INSERT INTO flights (flight_number, airline_id, origin, destination, departure_time, arrival_time, price, available_seats) VALUES
('AI101', 1, 'Delhi', 'Mumbai', '2025-06-20 08:00:00', '2025-06-20 10:00:00', 5000.00, 150),
('6E202', 2, 'Mumbai', 'Bangalore', '2025-06-20 09:30:00', '2025-06-20 11:30:00', 4500.00, 180),
('SG306', 3, 'Bangalore', 'Chennai', '2025-06-20 11:00:00', '2025-06-20 12:30:00', 3500.00, 120),
('UK404', 4, 'Chennai', 'Delhi', '2025-06-20 13:00:00', '2025-06-20 15:30:00', 6000.00, 160); 