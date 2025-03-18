-- Create table for customization options
CREATE TABLE IF NOT EXISTS customization_options (
    option_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    option_name VARCHAR(50) NOT NULL,
    option_type ENUM('color', 'text', 'size', 'material') NOT NULL,
    option_values TEXT,
    is_required BOOLEAN DEFAULT FALSE,
    additional_cost DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Create table for packages
CREATE TABLE IF NOT EXISTS packages (
    package_id INT PRIMARY KEY AUTO_INCREMENT,
    package_name VARCHAR(100) NOT NULL,
    description TEXT,
    base_price DECIMAL(10,2) NOT NULL,
    max_items INT DEFAULT 5,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create table for package_products (many-to-many relationship)
CREATE TABLE IF NOT EXISTS package_products (
    package_id INT,
    product_id INT,
    quantity INT DEFAULT 1,
    FOREIGN KEY (package_id) REFERENCES packages(package_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    PRIMARY KEY (package_id, product_id)
);

-- Create table for booking_customizations
CREATE TABLE IF NOT EXISTS booking_customizations (
    customization_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_detail_id INT,
    option_id INT,
    custom_value TEXT,
    additional_cost DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (booking_detail_id) REFERENCES booking_details(booking_id),
    FOREIGN KEY (option_id) REFERENCES customization_options(option_id)
);

-- Create payments table
CREATE TABLE IF NOT EXISTS payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    transaction_id VARCHAR(100),
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
);

-- Add payment_status column to bookings table
ALTER TABLE bookings ADD COLUMN payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending';

-- Insert sample customization options
INSERT INTO customization_options (product_id, option_name, option_type, option_values, is_required, additional_cost) VALUES
(1, 'Color', 'color', 'Brown,Black,Tan,Navy', TRUE, 0.00),
(1, 'Name Engraving', 'text', NULL, FALSE, 100.00),
(1, 'Size', 'size', 'Small,Medium,Large', TRUE, 0.00),
(1, 'Material Grade', 'material', 'Standard,Premium,Luxury', FALSE, 200.00);

-- Insert sample packages
INSERT INTO packages (package_name, description, base_price, max_items) VALUES
('Birthday Bundle', 'Perfect for birthday celebrations', 1500.00, 3),
('Christmas Special', 'Festive leather accessories', 2000.00, 4),
('Wedding Collection', 'Elegant leather gifts for weddings', 2500.00, 5),
('Corporate Package', 'Professional leather accessories', 3000.00, 5); 