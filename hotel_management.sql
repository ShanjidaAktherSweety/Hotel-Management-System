CREATE DATABASE IF NOT EXISTS hotel_management;
USE hotel_management;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('Admin','Manager','Receptionist','Accountant','Housekeeping','Activity Staff','Restaurant Staff') NOT NULL,
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE public_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    country VARCHAR(80),
    room_type VARCHAR(50) NOT NULL,
    room_count INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    total_guests INT NOT NULL,
    bed_preference VARCHAR(50),
    activity_request VARCHAR(100) DEFAULT NULL,
    activity_date DATE DEFAULT NULL,
    activity_time VARCHAR(50) DEFAULT NULL,
    activity_guests INT DEFAULT NULL,
    restaurant_request VARCHAR(100) DEFAULT NULL,
    restaurant_date DATE DEFAULT NULL,
    restaurant_time VARCHAR(50) DEFAULT NULL,
    restaurant_guests INT DEFAULT NULL,
    special_request TEXT,
    booking_status ENUM('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE activity_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guest_name VARCHAR(100) NOT NULL,
    activity_type VARCHAR(50) NOT NULL,
    booking_date DATE NOT NULL,
    time_slot VARCHAR(50) NOT NULL,
    guest_weight DECIMAL(6,2) DEFAULT NULL,
    guest_age INT DEFAULT NULL,
    assigned_staff VARCHAR(100) DEFAULT NULL,
    billing_type VARCHAR(100) NOT NULL,
    notes TEXT,
    status ENUM('Pending','Confirmed','Completed','Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);