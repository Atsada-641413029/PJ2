-- ===================================
-- Construction Materials Management System
-- Database Initialization Script
-- ===================================

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS construction_mart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE construction_mart;

-- ===================================
-- Table: users
-- ===================================
CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'seller', 'customer') NOT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- Table: shops
-- ===================================
CREATE TABLE IF NOT EXISTS shops (
    shop_id INT PRIMARY KEY AUTO_INCREMENT,
    seller_id INT NOT NULL,
    shop_name VARCHAR(100) NOT NULL,
    shop_description TEXT,
    business_license VARCHAR(50),
    tax_id VARCHAR(20),
    address TEXT NOT NULL,
    province VARCHAR(50),
    district VARCHAR(50),
    postal_code VARCHAR(10),
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    logo_url VARCHAR(255),
    approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by INT,
    approved_at TIMESTAMP NULL,
    rejection_reason TEXT,
    rating_avg DECIMAL(3,2) DEFAULT 0.00,
    total_reviews INT DEFAULT 0,
    total_orders INT DEFAULT 0,
    response_time_hours DECIMAL(5,2) DEFAULT 0.00,
    on_time_delivery_rate DECIMAL(5,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_approval_status (approval_status),
    INDEX idx_seller (seller_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- Table: categories
-- ===================================
CREATE TABLE IF NOT EXISTS categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    parent_category_id INT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
    INDEX idx_parent (parent_category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- Table: products
-- ===================================
CREATE TABLE IF NOT EXISTS products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    shop_id INT NOT NULL,
    category_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    description TEXT,
    brand VARCHAR(100),
    unit VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    min_order_quantity INT DEFAULT 1,
    image_url VARCHAR(255),
    specifications JSON,
    status ENUM('active', 'inactive', 'out_of_stock') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id) REFERENCES shops(shop_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE RESTRICT,
    INDEX idx_shop (shop_id),
    INDEX idx_category (category_id),
    INDEX idx_status (status),
    FULLTEXT idx_search (product_name, description, brand)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- Insert Default Admin User
-- ===================================
INSERT INTO users (username, email, password_hash, full_name, role, status) 
VALUES (
    'admin', 
    'admin@constructionmart.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'System Administrator', 
    'admin', 
    'active'
) ON DUPLICATE KEY UPDATE email=email;

-- ===================================
-- Insert Sample Categories
-- ===================================
INSERT INTO categories (category_name, description) VALUES
('ปูนซีเมนต์', 'ปูนซีเมนต์และวัสดุก่อสร้างพื้นฐาน'),
('อิฐและบล็อก', 'อิฐมอญ อิฐแดง บล็อกคอนกรีต'),
('เหล็กเส้น', 'เหล็กเส้นและเหล็กก่อสร้าง'),
('ทราย', 'ทรายก่อสร้างและทรายหยาบ'),
('สีและวัสดุทาสี', 'สีทาบ้านและอุปกรณ์ทาสี'),
('ไม้และวัสดุไม้', 'ไม้แปรรูปและวัสดุไม้'),
('กระเบื้อง', 'กระเบื้องปูพื้นและผนัง'),
('สุขภัณฑ์', 'อุปกรณ์สุขภัณฑ์และห้องน้ำ')
ON DUPLICATE KEY UPDATE category_name=category_name;

-- ===================================
-- Success Message
-- ===================================
SELECT 'Database initialized successfully!' AS message;
