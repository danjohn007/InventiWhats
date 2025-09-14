-- =============================================
-- InventiWhats System - Database Column Fixes
-- =============================================
-- This script adds missing columns to make the system functional
-- Execute this script to fix the column mismatch errors

USE `inventiwhats`;

-- Add missing columns to products table for backward compatibility
-- These columns are referenced in the existing code but missing from schema

-- Add 'price' column (copy of retail_price for compatibility)
ALTER TABLE `products` 
ADD COLUMN `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Main selling price (copy of retail_price)';

-- Add 'sku' column (copy of code for compatibility)
ALTER TABLE `products` 
ADD COLUMN `sku` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Product SKU (copy of code)';

-- Add 'cost' column (copy of cost_price for compatibility)  
ALTER TABLE `products`
ADD COLUMN `cost` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Product cost (copy of cost_price)';

-- Update existing records to sync the new columns with existing data
UPDATE `products` SET 
    `price` = `retail_price`,
    `sku` = `code`, 
    `cost` = `cost_price`
WHERE 1=1;

-- Add 'barcode' column (separate from code for barcode scanning)
ALTER TABLE `products`
ADD COLUMN `barcode` VARCHAR(100) NULL COMMENT 'Product barcode for scanning';

-- Add indexes for better performance
ALTER TABLE `products`
ADD INDEX `idx_products_sku` (`sku`),
ADD INDEX `idx_products_barcode` (`barcode`),
ADD INDEX `idx_products_price` (`price`);

-- Add missing columns to sales table that are referenced in code
ALTER TABLE `sales`
ADD COLUMN `user_id` INT(11) NULL COMMENT 'User who created the sale',
ADD CONSTRAINT `fk_sales_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Add missing columns to sale_details table that are referenced in code  
ALTER TABLE `sale_details`
ADD COLUMN `price` DECIMAL(10,2) GENERATED ALWAYS AS (`unit_price`) STORED COMMENT 'Alias for unit_price';

-- Insert some default settings that the system expects
INSERT IGNORE INTO `settings` (`key`, `value`, `description`, `type`) VALUES
('tax_rate', '16.00', 'Tax rate percentage for sales', 'number'),
('company_name', 'InventiWhats', 'Company name', 'text'),
('company_address', '', 'Company address', 'text'),
('company_phone', '', 'Company phone', 'text'),
('company_email', '', 'Company email', 'text'),
('low_stock_threshold', '10', 'Default low stock threshold', 'number'),
('currency_symbol', '$', 'Currency symbol', 'text'),
('currency_code', 'MXN', 'Currency code', 'text');

-- Create default admin user if not exists
INSERT IGNORE INTO `users` (`username`, `email`, `password`, `name`, `role`, `status`) VALUES
('admin', 'admin@inventiwhats.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin', 'active');

-- Create default branch if not exists
INSERT IGNORE INTO `branches` (`name`, `address`, `status`) VALUES
('Sucursal Principal', 'Dirección Principal', 'active');

-- Create some default categories if not exists
INSERT IGNORE INTO `categories` (`name`, `description`, `status`) VALUES
('General', 'Categoría general para productos', 'active'),
('Electrónicos', 'Productos electrónicos', 'active'),
('Ropa', 'Artículos de vestir', 'active'),
('Hogar', 'Artículos para el hogar', 'active'),
('Alimentación', 'Productos alimenticios', 'active');

-- Create a default supplier if not exists
INSERT IGNORE INTO `suppliers` (`name`, `company`, `status`) VALUES
('Proveedor General', 'Proveedor General S.A.', 'active');

-- Update the schema version
INSERT INTO `settings` (`key`, `value`, `description`, `type`) VALUES
('schema_version', '1.1', 'Database schema version', 'text')
ON DUPLICATE KEY UPDATE `value` = '1.1', `updated_at` = CURRENT_TIMESTAMP;

-- Show completion message
SELECT 'Database schema updated successfully! All missing columns have been added.' as message;