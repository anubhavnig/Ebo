-- Create Database
CREATE DATABASE IF NOT EXISTS ebostay_tours;
USE ebostay_tours;

-- Users Table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin', 'employee') DEFAULT 'customer',
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Packages Table
CREATE TABLE packages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    duration INT,
    price DECIMAL(10, 2),
    max_travelers INT,
    itinerary TEXT,
    popularity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tours Table
CREATE TABLE tours (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    description TEXT,
    duration INT,
    price DECIMAL(10, 2),
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bookings Table
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    package_id INT NOT NULL,
    booking_ref VARCHAR(50) UNIQUE,
    travelers INT,
    start_date DATE,
    email VARCHAR(100),
    phone VARCHAR(20),
    total_amount DECIMAL(10, 2),
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    payment_id VARCHAR(100),
    payment_date TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (package_id) REFERENCES packages(id),
    INDEX (user_id),
    INDEX (status)
);

-- Coupons Table
CREATE TABLE coupons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount_percent DECIMAL(5, 2),
    max_uses INT,
    uses INT DEFAULT 0,
    expires_at DATETIME,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (code)
);

-- Activities Table
CREATE TABLE activities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    destination VARCHAR(100),
    description TEXT,
    price DECIMAL(10, 2),
    duration VARCHAR(50),
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hotels Table
CREATE TABLE hotels (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    destination VARCHAR(100),
    description TEXT,
    price_per_night DECIMAL(10, 2),
    rating DECIMAL(3, 1),
    amenities TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Expenses Table
CREATE TABLE expenses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT,
    category VARCHAR(50),
    description TEXT,
    amount DECIMAL(10, 2),
    employee_id INT,
    receipt_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (employee_id) REFERENCES users(id),
    INDEX (booking_id)
);

-- Employees Table
CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE,
    department VARCHAR(50),
    salary DECIMAL(10, 2),
    joining_date DATE,
    status ENUM('active', 'inactive', 'on_leave') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Reviews Table
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tour_id INT,
    user_id INT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES tours(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Customize Requests Table
CREATE TABLE customize_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    destination VARCHAR(100),
    budget DECIMAL(10, 2),
    duration INT,
    preferences TEXT,
    gemini_response LONGTEXT,
    status ENUM('pending', 'completed', 'booked') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create some initial sample data
INSERT INTO packages (name, destination, description, duration, price, max_travelers) VALUES
('Goa Beach Getaway', 'Goa', 'Experience the beautiful beaches and culture of Goa', 3, 15000, 4),
('Kerala Backwaters Tour', 'Kerala', 'Explore the serene backwaters of Kerala', 4, 20000, 5),
('Himalayas Adventure', 'Himachal Pradesh', 'Trek and explore the majestic Himalayas', 5, 25000, 6),
('Rajasthan Heritage', 'Rajasthan', 'Discover the palaces and forts of Rajasthan', 4, 18000, 4),
('Northeast Explorer', 'Assam', 'Wildlife and nature in Northeast India', 5, 22000, 5),
('South India Discovery', 'Tamil Nadu', 'Temples, beaches, and culture of South India', 4, 16000, 4);

INSERT INTO coupons (code, discount_percent, max_uses, expires_at) VALUES
('SUMMER20', 20.00, 100, '2024-06-30 23:59:59'),
('FIRSTBOOKING10', 10.00, 50, '2024-12-31 23:59:59'),
('MONSOON15', 15.00, 75, '2024-09-30 23:59:59');