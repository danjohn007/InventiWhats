# InventiWhats - Installation Guide

## Sistema de Control de Inventarios Global con POS por Sucursal

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- PDO MySQL extension enabled

### Installation Steps

#### 1. Database Setup

Create the database and user:
```sql
CREATE DATABASE inventiwhats CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'inventiwhats'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON inventiwhats.* TO 'inventiwhats'@'localhost';
FLUSH PRIVILEGES;
```

#### 2. Database Schema

Run the following SQL scripts in order:

1. **Create the main schema:**
```bash
mysql -u inventiwhats -p inventiwhats < sql/schema.sql
```

2. **Add sample data (optional):**
```bash
mysql -u inventiwhats -p inventiwhats < sql/sample_data.sql
```

3. **Apply fixes for missing columns:**
```bash
mysql -u inventiwhats -p inventiwhats < sql/fix_columns.sql
```

#### 3. Configuration

Update the database connection in `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'inventiwhats');
define('DB_USER', 'inventiwhats');
define('DB_PASS', 'your_password');
```

#### 4. Web Server Setup

##### Apache (.htaccess already included)
Ensure mod_rewrite is enabled and AllowOverride is set to All.

##### Nginx
Add this to your server block:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

#### 5. Permissions

Set proper permissions:
```bash
chmod 755 -R .
chmod 777 uploads/
```

### System Test

Run the system test to verify installation:
```
http://your-domain/test_system.php
```

### Default Login

After running the fix_columns.sql script, you can login with:
- **Username:** admin
- **Password:** password
- **Email:** admin@inventiwhats.com

### Features Fixed

✅ **PUNTOS DE VENTA** - Complete POS interface with cart functionality
✅ **PRODUCTOS** - Product management with correct column mapping  
✅ **INVENTARIOS** - Inventory tracking with stock alerts
✅ **REPORTES** - Sales and product reports

### Usage

1. **Access Admin Panel:** `/admin`
2. **Point of Sale:** `/pos` 
3. **Public Inventory:** `/inventario`

### SQL Commands Summary

The following SQL commands are executed by `sql/fix_columns.sql` to make the system functional:

```sql
-- Add missing columns for backward compatibility
ALTER TABLE products ADD COLUMN price DECIMAL(10,2) NOT NULL DEFAULT 0.00;
ALTER TABLE products ADD COLUMN sku VARCHAR(50) NOT NULL DEFAULT '';
ALTER TABLE products ADD COLUMN cost DECIMAL(10,2) NOT NULL DEFAULT 0.00;
ALTER TABLE products ADD COLUMN barcode VARCHAR(100) NULL;

-- Update existing data
UPDATE products SET price = retail_price, sku = code, cost = cost_price;

-- Add indexes
ALTER TABLE products ADD INDEX idx_products_sku (sku);
ALTER TABLE products ADD INDEX idx_products_barcode (barcode);
ALTER TABLE products ADD INDEX idx_products_price (price);

-- Add missing foreign key
ALTER TABLE sales ADD COLUMN user_id INT(11) NULL;
ALTER TABLE sales ADD CONSTRAINT fk_sales_user FOREIGN KEY (user_id) REFERENCES users (id);

-- Insert default settings and data
INSERT IGNORE INTO settings (key, value, description) VALUES
('tax_rate', '16.00', 'Tax rate percentage'),
('company_name', 'InventiWhats', 'Company name');

-- Create default admin user  
INSERT IGNORE INTO users (username, email, password, name, role) VALUES
('admin', 'admin@inventiwhats.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin');
```

### Troubleshooting

**Database Connection Issues:**
- Verify MySQL service is running
- Check credentials in config/config.php  
- Ensure database exists and user has proper privileges

**Missing Tables:**
- Run schema.sql first, then fix_columns.sql
- Check if SQL files executed without errors

**Permission Errors:**
- Ensure web server has read access to all files
- Create uploads directory with write permissions

**POS Not Loading:**
- Verify views/pos/index.php exists
- Check that user has proper role (admin/manager/cashier)

### Support

For issues or questions, check the system test page first:
`http://your-domain/test_system.php`

This will verify all components are properly installed and configured.