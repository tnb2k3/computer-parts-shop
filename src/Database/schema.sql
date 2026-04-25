-- Computer Parts E-commerce Database Schema

-- Drop and create database
DROP DATABASE IF EXISTS computer_shop;
CREATE DATABASE computer_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE computer_shop;

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    img_url VARCHAR(255),
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    email_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(64) NULL,
    token_expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    payment_method ENUM('cod', 'qr_bank') DEFAULT 'cod',
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    shipping_address TEXT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample categories
INSERT INTO categories (name, description) VALUES
('CPU', 'Bộ vi xử lý - Central Processing Unit'),
('GPU', 'Card đồ họa - Graphics Processing Unit'),
('RAM', 'Bộ nhớ truy cập ngẫu nhiên'),
('Mainboard', 'Bo mạch chủ'),
('SSD', 'Ổ cứng thể rắn'),
('HDD', 'Ổ cứng cơ học'),
('PSU', 'Nguồn máy tính'),
('Case', 'Vỏ case máy tính'),
('Cooling', 'Tản nhiệt'),
('Monitor', 'Màn hình máy tính');

-- Insert sample products
INSERT INTO products (category_id, name, description, price, image, stock) VALUES
-- CPU
(1, 'Intel Core i9-13900K', 'CPU Intel thế hệ 13, 24 nhân 32 luồng, tốc độ tối đa 5.8GHz', 12990000, '10.png', 15),
(1, 'AMD Ryzen 9 7950X', 'CPU AMD Ryzen 9, 16 nhân 32 luồng, 5.7GHz boost', 13990000, '17.png', 12),
(1, 'Intel Core i7-13700K', 'CPU Intel thế hệ 13, 16 nhân 24 luồng', 9990000, '16.png', 20),
(1, 'AMD Ryzen 7 7700X', 'CPU AMD Ryzen 7, 8 nhân 16 luồng', 7990000, '15.png', 18),

-- GPU
(2, 'NVIDIA RTX 4090', 'Card đồ họa NVIDIA RTX 4090 24GB GDDR6X', 45990000, '14.png', 8),
(2, 'AMD Radeon RX 7900 XTX', 'Card đồ họa AMD Radeon RX 7900 XTX 24GB', 25990000, '13.png', 10),
(2, 'NVIDIA RTX 4070 Ti', 'Card đồ họa NVIDIA RTX 4070 Ti 12GB', 19990000, '12.png', 15),
(2, 'AMD Radeon RX 6750 XT', 'Card đồ họa AMD Radeon RX 6750 XT 12GB', 10990000, '11.png', 12),

-- RAM
(3, 'Corsair Vengeance 32GB DDR5-6000', 'Bộ nhớ RAM DDR5 32GB (2x16GB) 6000MHz', 3990000, '1.png', 25),
(3, 'G.Skill Trident Z5 RGB 32GB DDR5-6400', 'RAM DDR5 32GB RGB 6400MHz', 4490000, '9.png', 20),
(3, 'Kingston Fury Beast 16GB DDR4-3200', 'RAM DDR4 16GB 3200MHz', 1290000, '8.png', 30),

-- Mainboard
(4, 'ASUS ROG Maximus Z790 Hero', 'Bo mạch chủ Z790, Socket LGA1700', 13990000, '7.png', 10),
(4, 'MSI MPG X670E Carbon WiFi', 'Bo mạch chủ X670E, Socket AM5', 12990000, '6.png', 12),
(4, 'Gigabyte B650 AORUS Elite', 'Bo mạch chủ B650, Socket AM5', 5990000, '5.png', 15),

-- SSD
(5, 'Samsung 990 PRO 2TB', 'SSD NVMe Gen 4, 2TB, 7450MB/s', 4990000, '4.png', 20),
(5, 'WD Black SN850X 1TB', 'SSD NVMe Gen 4, 1TB, 7300MB/s', 2790000, '3.png', 25),
(5, 'Kingston KC3000 512GB', 'SSD NVMe PCIe 4.0, 512GB', 1490000, '2.png', 30);

-- Insert default admin users
-- admin password: admin123
-- admin_bao password: baodz123
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@computershop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin'),
('admin_bao', 'bao089492@gmail.com', '$2y$10$XynIJCkyOPJRcFJL0HUu/O2pcklQ17uaFgpNMsZhpiyl0xlhnFW5u', 'Administrator', 'admin');

-- Insert sample customer (password: customer123)
INSERT INTO users (username, email, password, full_name, phone, address, role) VALUES
('customer', 'customer@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyễn Văn A', '0123456789', '123 Đường ABC, Quận 1, TP.HCM', 'customer');

-- Table: reviews
-- Stores product reviews from users
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product_review (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: coupons
-- Stores discount coupon codes
CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    type ENUM('percentage', 'fixed') NOT NULL,
    value DECIMAL(10, 2) NOT NULL,
    min_order_value DECIMAL(10, 2) DEFAULT 0,
    max_discount DECIMAL(10, 2) NULL,
    usage_limit INT DEFAULT NULL,
    times_used INT DEFAULT 0,
    valid_from TIMESTAMP NULL,
    valid_to TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: coupon_usage
-- Tracks coupon usage history
CREATE TABLE coupon_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coupon_id INT NOT NULL,
    user_id INT NOT NULL,
    order_id INT NOT NULL,
    discount_amount DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    INDEX idx_coupon (coupon_id),
    INDEX idx_user (user_id),
    INDEX idx_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample coupons for testing
INSERT INTO coupons (code, type, value, min_order_value, max_discount, usage_limit, valid_from, valid_to, description) VALUES
('WELCOME10', 'percentage', 10.00, 500000, 100000, NULL, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), 'Giảm 10% cho đơn hàng từ 500k, tối đa 100k'),
('DISCOUNT50K', 'fixed', 50000, 300000, NULL, 100, NOW(), DATE_ADD(NOW(), INTERVAL 6 MONTH), 'Giảm 50k cho đơn hàng từ 300k'),
('NEWUSER', 'percentage', 15.00, 1000000, 200000, NULL, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), 'Giảm 15% cho khách hàng mới, đơn từ 1 triệu'),
('FLASH20', 'percentage', 20.00, 2000000, 500000, 50, NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH), 'Flash sale - Giảm 20% đơn từ 2 triệu');

-- Insert sample reviews for testing (for existing products)
INSERT INTO reviews (product_id, user_id, rating, comment) VALUES
(1, 3, 5, 'CPU rất mạnh, đáng đồng tiền! Chơi game và render video mượt mà.'),
(2, 3, 5, 'Ryzen 9 7950X quá đỉnh! Đa luồng cực mạnh cho công việc của tôi.'),
(3, 3, 4, 'Sản phẩm tốt nhưng giá hơi cao. Nhiệt độ ổn định khi sử dụng.'),
(5, 3, 5, 'VGA tốt, chơi game 4K mượt mà. Giá cả hợp lý.');
