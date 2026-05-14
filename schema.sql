CREATE DATABASE IF NOT EXISTS web2_catalog;
USE web2_catalog;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    author VARCHAR(100) NOT NULL,
    review_text TEXT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

INSERT INTO products (name, image, price, description) VALUES
('Laptop Dell XPS 13', 'products/laptop.jpg', 1299.99, 'Powerful ultrabook with Intel Core i7 processor, 16GB RAM, 512GB SSD.'),
('Wireless Headphones Sony WH-1000XM4', 'products/headphones.jpg', 349.99, 'Industry-leading noise canceling wireless headphones with 30-hour battery life.'),
('Smartphone Samsung Galaxy S21', 'products/phone.jpg', 799.99, 'Latest smartphone with 5G connectivity, triple camera system, and AMOLED display.'),
('Gaming Mouse Logitech G305', 'products/mouse.jpg', 49.99, 'Lightweight wireless gaming mouse with HERO sensor and 1ms report rate.');

INSERT INTO reviews (product_id, author, review_text, rating) VALUES
(1, 'John Doe', 'Great laptop for work and gaming!', 5),
(1, 'Jane Smith', 'Battery life could be better.', 4),
(2, 'Bob Johnson', 'Excellent sound quality and noise cancellation.', 5);