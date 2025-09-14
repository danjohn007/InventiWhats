-- =============================================
-- InventiWhats System - Complete Database Setup
-- =============================================
-- This script creates the complete database with all fixes applied
-- Run this after creating the database to set up everything

USE `inventiwhats`;

-- First, ensure we have the base schema (run schema.sql first if not already done)
-- This file assumes schema.sql has been executed

-- =============================================
-- FIXES FOR MISSING COLUMNS
-- =============================================

-- Check if columns exist before adding them to avoid errors
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'products' 
     AND column_name = 'price' 
     AND table_schema = 'inventiwhats') = 0,
    'ALTER TABLE products ADD COLUMN price DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT "Main selling price (copy of retail_price)";',
    'SELECT "Column price already exists";'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'products' 
     AND column_name = 'sku' 
     AND table_schema = 'inventiwhats') = 0,
    'ALTER TABLE products ADD COLUMN sku VARCHAR(50) NOT NULL DEFAULT "" COMMENT "Product SKU (copy of code)";',
    'SELECT "Column sku already exists";'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'products' 
     AND column_name = 'cost' 
     AND table_schema = 'inventiwhats') = 0,
    'ALTER TABLE products ADD COLUMN cost DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT "Product cost (copy of cost_price)";',
    'SELECT "Column cost already exists";'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'products' 
     AND column_name = 'barcode' 
     AND table_schema = 'inventiwhats') = 0,
    'ALTER TABLE products ADD COLUMN barcode VARCHAR(100) NULL COMMENT "Product barcode for scanning";',
    'SELECT "Column barcode already exists";'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Update existing products to sync the new columns with existing data
UPDATE `products` SET 
    `price` = `retail_price`,
    `sku` = `code`, 
    `cost` = `cost_price`
WHERE (`price` = 0 OR `sku` = '' OR `cost` = 0);

-- Add indexes for better performance (ignore errors if they already exist)
SET @sql = 'CREATE INDEX idx_products_sku ON products (sku)';
SET @ignore = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
     WHERE table_name = 'products' 
     AND index_name = 'idx_products_sku' 
     AND table_schema = 'inventiwhats') = 0,
    @sql,
    'SELECT "Index idx_products_sku already exists"'
));
PREPARE stmt FROM @ignore;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = 'CREATE INDEX idx_products_barcode ON products (barcode)';
SET @ignore = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
     WHERE table_name = 'products' 
     AND index_name = 'idx_products_barcode' 
     AND table_schema = 'inventiwhats') = 0,
    @sql,
    'SELECT "Index idx_products_barcode already exists"'
));
PREPARE stmt FROM @ignore;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = 'CREATE INDEX idx_products_price ON products (price)';
SET @ignore = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
     WHERE table_name = 'products' 
     AND index_name = 'idx_products_price' 
     AND table_schema = 'inventiwhats') = 0,
    @sql,
    'SELECT "Index idx_products_price already exists"'
));
PREPARE stmt FROM @ignore;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add missing user_id column to sales table if it doesn't exist
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'sales' 
     AND column_name = 'user_id' 
     AND table_schema = 'inventiwhats') = 0,
    'ALTER TABLE sales ADD COLUMN user_id INT(11) NULL COMMENT "User who created the sale";',
    'SELECT "Column user_id already exists in sales";'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add foreign key constraint for sales.user_id if it doesn't exist
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
     WHERE table_name = 'sales' 
     AND constraint_name = 'fk_sales_user' 
     AND table_schema = 'inventiwhats') = 0,
    'ALTER TABLE sales ADD CONSTRAINT fk_sales_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL;',
    'SELECT "Foreign key fk_sales_user already exists";'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =============================================
-- DEFAULT DATA SETUP
-- =============================================

-- Insert default settings
INSERT IGNORE INTO `settings` (`key`, `value`, `description`, `type`) VALUES
('tax_rate', '16.00', 'Tax rate percentage for sales', 'number'),
('company_name', 'InventiWhats', 'Company name', 'text'),
('company_address', '', 'Company address', 'text'),
('company_phone', '', 'Company phone', 'text'),
('company_email', '', 'Company email', 'text'),
('low_stock_threshold', '10', 'Default low stock threshold', 'number'),
('currency_symbol', '$', 'Currency symbol', 'text'),
('currency_code', 'MXN', 'Currency code', 'text'),
('schema_version', '1.1', 'Database schema version', 'text');

-- Create default admin user (password is 'password')
INSERT IGNORE INTO `users` (`username`, `email`, `password`, `name`, `role`, `status`) VALUES
('admin', 'admin@inventiwhats.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin', 'active');

-- Create default branch
INSERT IGNORE INTO `branches` (`id`, `name`, `address`, `status`) VALUES
(1, 'Sucursal Principal', 'Dirección Principal', 'active');

-- Create default categories
INSERT IGNORE INTO `categories` (`name`, `description`, `status`) VALUES
('General', 'Categoría general para productos', 'active'),
('Electrónicos', 'Productos electrónicos', 'active'),
('Ropa', 'Artículos de vestir', 'active'),
('Hogar', 'Artículos para el hogar', 'active'),
('Alimentación', 'Productos alimenticios', 'active');

-- Create default supplier
INSERT IGNORE INTO `suppliers` (`name`, `company`, `status`) VALUES
('Proveedor General', 'Proveedor General S.A.', 'active');

-- =============================================
-- SAMPLE DATA (OPTIONAL)
-- =============================================

-- Insert sample products for testing
INSERT IGNORE INTO `products` (`id`, `code`, `sku`, `name`, `description`, `category_id`, `supplier_id`, `cost_price`, `cost`, `retail_price`, `price`, `wholesale_price`, `min_stock`, `status`) VALUES
(1, 'PROD001', 'PROD001', 'Producto de Prueba 1', 'Producto de ejemplo para testing', 1, 1, 50.00, 50.00, 100.00, 100.00, 80.00, 10, 'active'),
(2, 'PROD002', 'PROD002', 'Producto de Prueba 2', 'Segundo producto de ejemplo', 2, 1, 25.00, 25.00, 50.00, 50.00, 40.00, 5, 'active'),
(3, 'PROD003', 'PROD003', 'Producto de Prueba 3', 'Tercer producto de ejemplo', 1, 1, 75.00, 75.00, 150.00, 150.00, 120.00, 15, 'active');

-- Insert sample inventory
INSERT IGNORE INTO `inventory` (`product_id`, `branch_id`, `quantity`) VALUES
(1, 1, 25),
(2, 1, 8),
(3, 1, 20);

-- =============================================
-- COMPLETION MESSAGE
-- =============================================

SELECT 'InventiWhats database setup completed successfully!' as message,
       'You can now access the system with username: admin, password: password' as login_info,
       'All missing columns have been added and sample data has been inserted.' as status;

-- Show the final schema version
SELECT value as schema_version FROM settings WHERE `key` = 'schema_version';