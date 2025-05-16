-- Create airlines table
CREATE TABLE IF NOT EXISTS airlines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create flights table
CREATE TABLE IF NOT EXISTS flights (
    id INT PRIMARY KEY AUTO_INCREMENT,
    airline_id INT NOT NULL,
    flight_number VARCHAR(20) NOT NULL,
    origin VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    departure_time DATETIME NOT NULL,
    arrival_time DATETIME NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    available_seats INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (airline_id) REFERENCES airlines(id)
);

-- Insert some sample airlines
INSERT INTO airlines (name) VALUES 
('Air India'),
('IndiGo'),
('SpiceJet'),
('Vistara');

-- Insert some sample flights
INSERT INTO flights (airline_id, flight_number, origin, destination, departure_time, arrival_time, price, available_seats) VALUES
(1, 'AI101', 'Delhi', 'Mumbai', '2024-03-20 08:00:00', '2024-03-20 10:00:00', 5000.00, 100),
(2, '6E201', 'Mumbai', 'Bangalore', '2024-03-20 09:00:00', '2024-03-20 11:00:00', 4500.00, 80),
(3, 'SG301', 'Bangalore', 'Chennai', '2024-03-20 10:00:00', '2024-03-20 11:30:00', 3500.00, 120),
(4, 'UK401', 'Chennai', 'Delhi', '2024-03-20 11:00:00', '2024-03-20 13:30:00', 6000.00, 90); 