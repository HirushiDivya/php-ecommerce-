CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;

-- 1. Users Table (Password hashing )
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- password_hash 255
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Products Table (Inventory )
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL 
);

-- Sample Data (Products)
INSERT INTO products (title, price, stock) VALUES 
('Asus ROG Gaming Laptop', 350000.00, 5),
('iPhone 15 Pro Max', 420000.00, 2),
('Logitech G Pro Wireless Mouse', 28000.00, 0); 