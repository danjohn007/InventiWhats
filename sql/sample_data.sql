-- Datos de ejemplo para el Sistema InventiWhats
USE `inventiwhats`;

-- Insertar sucursales
INSERT INTO `branches` (`name`, `address`, `phone`, `email`, `manager`, `status`) VALUES
('Sucursal Centro', 'Calle Principal #123, Centro', '555-0001', 'centro@inventiwhats.com', 'María González', 'active'),
('Sucursal Norte', 'Av. Revolución #456, Norte', '555-0002', 'norte@inventiwhats.com', 'Carlos Rodríguez', 'active'),
('Sucursal Sur', 'Boulevard Sur #789, Sur', '555-0003', 'sur@inventiwhats.com', 'Ana Martínez', 'active');

-- Insertar usuarios
INSERT INTO `users` (`username`, `email`, `password`, `name`, `role`, `branch_id`, `status`) VALUES
('admin', 'admin@inventiwhats.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador Sistema', 'admin', NULL, 'active'),
('manager1', 'maria@inventiwhats.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María González', 'manager', 1, 'active'),
('manager2', 'carlos@inventiwhats.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos Rodríguez', 'manager', 2, 'active'),
('cashier1', 'ana@inventiwhats.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana Martínez', 'cashier', 3, 'active'),
('cashier2', 'jose@inventiwhats.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'José López', 'cashier', 1, 'active');

-- Insertar categorías
INSERT INTO `categories` (`name`, `description`, `parent_id`, `status`) VALUES
('Electrónicos', 'Productos electrónicos y tecnológicos', NULL, 'active'),
('Teléfonos', 'Teléfonos móviles y accesorios', 1, 'active'),
('Computadoras', 'Laptops, desktops y componentes', 1, 'active'),
('Hogar', 'Artículos para el hogar', NULL, 'active'),
('Cocina', 'Electrodomésticos de cocina', 4, 'active'),
('Limpieza', 'Productos de limpieza', 4, 'active'),
('Ropa', 'Vestimenta y accesorios', NULL, 'active'),
('Deportes', 'Artículos deportivos', NULL, 'active');

-- Insertar proveedores
INSERT INTO `suppliers` (`name`, `company`, `email`, `phone`, `address`, `tax_id`, `status`) VALUES
('TechSupply SA', 'TechSupply SA de CV', 'ventas@techsupply.com', '555-1001', 'Zona Industrial #100', 'TSA123456789', 'active'),
('HomeProducts', 'HomeProducts Ltda', 'contacto@homeproducts.com', '555-1002', 'Centro Comercial #200', 'HPL987654321', 'active'),
('SportGear', 'SportGear Corporation', 'info@sportgear.com', '555-1003', 'Parque Industrial #300', 'SGC456789123', 'active'),
('CleanCorp', 'CleanCorp SA', 'pedidos@cleancorp.com', '555-1004', 'Distrito Industrial #400', 'CCS789123456', 'active');

-- Insertar productos
INSERT INTO `products` (`code`, `name`, `description`, `category_id`, `supplier_id`, `unit`, `cost_price`, `retail_price`, `wholesale_price`, `min_stock`, `max_stock`, `has_expiry`, `status`) VALUES
('IPHONE14-128', 'iPhone 14 128GB', 'Apple iPhone 14 de 128GB en varios colores', 2, 1, 'pcs', 15000.00, 20000.00, 18000.00, 5, 50, 0, 'active'),
('SAMSUNG-A54', 'Samsung Galaxy A54', 'Samsung Galaxy A54 5G 128GB', 2, 1, 'pcs', 8000.00, 12000.00, 10000.00, 10, 100, 0, 'active'),
('LAPTOP-HP15', 'Laptop HP Pavilion 15', 'Laptop HP Pavilion 15 Intel i5 8GB RAM', 3, 1, 'pcs', 12000.00, 18000.00, 15000.00, 3, 20, 0, 'active'),
('MICROONDAS-LG', 'Microondas LG 1.1 pies', 'Microondas LG de 1.1 pies cúbicos', 5, 2, 'pcs', 2500.00, 4000.00, 3500.00, 5, 25, 0, 'active'),
('LICUADORA-OSTER', 'Licuadora Oster Clásica', 'Licuadora Oster de 3 velocidades', 5, 2, 'pcs', 800.00, 1500.00, 1200.00, 10, 50, 0, 'active'),
('DETERGENTE-ACE', 'Detergente Ace 3kg', 'Detergente en polvo Ace de 3 kilogramos', 6, 4, 'pcs', 80.00, 120.00, 100.00, 50, 200, 0, 'active'),
('PLAYERA-NIKE', 'Playera Nike Dri-Fit', 'Playera deportiva Nike Dri-Fit varios colores', 7, 3, 'pcs', 400.00, 800.00, 600.00, 20, 100, 0, 'active'),
('BALON-FUTBOL', 'Balón de Fútbol Nike', 'Balón de fútbol Nike oficial tamaño 5', 8, 3, 'pcs', 300.00, 600.00, 450.00, 15, 50, 0, 'active'),
('ARROZ-VERDE-1KG', 'Arroz Verde Valle 1kg', 'Arroz blanco Verde Valle de 1 kilogramo', 4, 2, 'pcs', 25.00, 35.00, 30.00, 100, 500, 1, 'active'),
('ACEITE-CAPULLO-1L', 'Aceite Capullo 1L', 'Aceite vegetal Capullo de 1 litro', 4, 2, 'pcs', 45.00, 65.00, 55.00, 50, 300, 1, 'active');

-- Insertar inventario inicial
INSERT INTO `inventory` (`product_id`, `branch_id`, `quantity`, `batch_number`, `expiry_date`, `location`) VALUES
-- Sucursal Centro
(1, 1, 15, NULL, NULL, 'A1-01'),
(2, 1, 25, NULL, NULL, 'A1-02'),
(3, 1, 8, NULL, NULL, 'B1-01'),
(4, 1, 12, NULL, NULL, 'C1-01'),
(5, 1, 20, NULL, NULL, 'C1-02'),
(6, 1, 80, 'DET001', NULL, 'D1-01'),
(7, 1, 35, NULL, NULL, 'E1-01'),
(8, 1, 18, NULL, NULL, 'E1-02'),
(9, 1, 150, 'ARR001', '2025-06-15', 'F1-01'),
(10, 1, 75, 'ACE001', '2025-03-20', 'F1-02'),

-- Sucursal Norte
(1, 2, 10, NULL, NULL, 'A2-01'),
(2, 2, 30, NULL, NULL, 'A2-02'),
(3, 2, 5, NULL, NULL, 'B2-01'),
(4, 2, 8, NULL, NULL, 'C2-01'),
(5, 2, 15, NULL, NULL, 'C2-02'),
(6, 2, 60, 'DET002', NULL, 'D2-01'),
(7, 2, 25, NULL, NULL, 'E2-01'),
(8, 2, 12, NULL, NULL, 'E2-02'),
(9, 2, 120, 'ARR002', '2025-06-10', 'F2-01'),
(10, 2, 90, 'ACE002', '2025-03-25', 'F2-02'),

-- Sucursal Sur
(1, 3, 8, NULL, NULL, 'A3-01'),
(2, 3, 20, NULL, NULL, 'A3-02'),
(3, 3, 3, NULL, NULL, 'B3-01'),
(4, 3, 10, NULL, NULL, 'C3-01'),
(5, 3, 18, NULL, NULL, 'C3-02'),
(6, 3, 70, 'DET003', NULL, 'D3-01'),
(7, 3, 30, NULL, NULL, 'E3-01'),
(8, 3, 15, NULL, NULL, 'E3-02'),
(9, 3, 180, 'ARR003', '2025-06-20', 'F3-01'),
(10, 3, 85, 'ACE003', '2025-03-18', 'F3-02');

-- Insertar clientes
INSERT INTO `customers` (`name`, `email`, `phone`, `address`, `tax_id`, `loyalty_points`, `total_purchases`, `status`) VALUES
('Juan Pérez García', 'juan.perez@email.com', '555-2001', 'Calle Flores #123, Colonia Centro', 'PEGJ850101ABC', 150, 2500.00, 'active'),
('María del Carmen López', 'maria.lopez@email.com', '555-2002', 'Av. Independencia #456, Norte', 'LOCM900215DEF', 75, 1200.00, 'active'),
('Carlos Alberto Ruiz', 'carlos.ruiz@email.com', '555-2003', 'Boulevard Sur #789, Sur', 'RUCA750320GHI', 220, 4500.00, 'active'),
('Ana Isabel Torres', 'ana.torres@email.com', '555-2004', 'Calle Morelos #321, Centro', 'TOIA880712JKL', 95, 1800.00, 'active'),
('Roberto Fernández Silva', 'roberto.fernandez@email.com', '555-2005', 'Av. Juárez #654, Norte', 'FESR920605MNO', 180, 3200.00, 'active');

-- Insertar configuraciones del sistema
INSERT INTO `settings` (`key`, `value`, `description`, `type`) VALUES
('company_name', 'InventiWhats', 'Nombre de la empresa', 'text'),
('company_address', 'Calle Principal #100, Ciudad', 'Dirección de la empresa', 'text'),
('company_phone', '555-0000', 'Teléfono de la empresa', 'text'),
('company_email', 'info@inventiwhats.com', 'Email de la empresa', 'text'),
('tax_rate', '16.00', 'Tasa de impuesto por defecto (%)', 'number'),
('loyalty_points_per_peso', '1', 'Puntos de lealtad por peso gastado', 'number'),
('loyalty_peso_per_point', '1.00', 'Pesos por punto de lealtad', 'number'),
('min_stock_alert', '1', 'Activar alertas de stock mínimo', 'boolean'),
('auto_backup', '1', 'Respaldo automático diario', 'boolean'),
('pos_receipt_copies', '2', 'Número de copias del recibo POS', 'number');

-- Insertar impuestos
INSERT INTO `taxes` (`name`, `rate`, `type`, `status`) VALUES
('IVA', 16.00, 'percentage', 'active'),
('IEPS', 8.00, 'percentage', 'active'),
('Exento', 0.00, 'percentage', 'active');

-- Insertar algunas ventas de ejemplo
INSERT INTO `sales` (`sale_number`, `branch_id`, `customer_id`, `cashier_id`, `subtotal`, `discount`, `tax`, `total`, `payment_method`, `loyalty_points_earned`, `loyalty_points_used`) VALUES
('VTA-001-2024', 1, 1, 2, 20000.00, 0.00, 3200.00, 23200.00, 'card', 232, 0),
('VTA-002-2024', 1, 2, 2, 1500.00, 0.00, 240.00, 1740.00, 'cash', 17, 0),
('VTA-003-2024', 2, 3, 3, 12000.00, 500.00, 1840.00, 13340.00, 'mixed', 133, 50),
('VTA-004-2024', 3, 4, 4, 4000.00, 0.00, 640.00, 4640.00, 'card', 46, 0),
('VTA-005-2024', 1, 5, 5, 800.00, 0.00, 128.00, 928.00, 'cash', 9, 0);

-- Insertar detalles de ventas
INSERT INTO `sale_details` (`sale_id`, `product_id`, `quantity`, `unit_price`, `discount`, `subtotal`) VALUES
-- Venta 1: iPhone 14
(1, 1, 1, 20000.00, 0.00, 20000.00),
-- Venta 2: Licuadora
(2, 5, 1, 1500.00, 0.00, 1500.00),
-- Venta 3: Laptop HP
(3, 3, 1, 18000.00, 500.00, 17500.00),
-- Venta 4: Microondas
(4, 4, 1, 4000.00, 0.00, 4000.00),
-- Venta 5: Playera Nike
(5, 7, 1, 800.00, 0.00, 800.00);

-- Insertar pagos
INSERT INTO `payments` (`sale_id`, `method`, `amount`, `reference`) VALUES
(1, 'card', 23200.00, '****1234'),
(2, 'cash', 1740.00, NULL),
(3, 'card', 10000.00, '****5678'),
(3, 'cash', 3340.00, NULL),
(4, 'card', 4640.00, '****9012'),
(5, 'cash', 928.00, NULL);

-- Insertar historial de puntos de lealtad
INSERT INTO `loyalty_history` (`customer_id`, `points`, `type`, `reference_type`, `reference_id`, `description`) VALUES
(1, 232, 'earned', 'sale', 1, 'Compra VTA-001-2024'),
(2, 17, 'earned', 'sale', 2, 'Compra VTA-002-2024'),
(3, 133, 'earned', 'sale', 3, 'Compra VTA-003-2024'),
(3, -50, 'redeemed', 'sale', 3, 'Descuento aplicado VTA-003-2024'),
(4, 46, 'earned', 'sale', 4, 'Compra VTA-004-2024'),
(5, 9, 'earned', 'sale', 5, 'Compra VTA-005-2024');

-- Insertar movimientos de stock para las ventas
INSERT INTO `stock_movements` (`product_id`, `branch_id`, `type`, `quantity`, `reference_type`, `reference_id`, `user_id`) VALUES
-- Movimientos de venta
(1, 1, 'out', 1, 'sale', 1, 2),
(5, 1, 'out', 1, 'sale', 2, 2),
(3, 2, 'out', 1, 'sale', 3, 3),
(4, 3, 'out', 1, 'sale', 4, 4),
(7, 1, 'out', 1, 'sale', 5, 5),
-- Movimientos de entrada inicial
(1, 1, 'in', 16, 'adjustment', NULL, 1),
(2, 1, 'in', 25, 'adjustment', NULL, 1),
(3, 1, 'in', 9, 'adjustment', NULL, 1),
(4, 1, 'in', 13, 'adjustment', NULL, 1),
(5, 1, 'in', 21, 'adjustment', NULL, 1);

-- Crear una promoción de ejemplo
INSERT INTO `promotions` (`name`, `description`, `type`, `value`, `min_quantity`, `start_date`, `end_date`, `status`) VALUES
('Descuento Electrónicos 10%', 'Descuento del 10% en todos los productos electrónicos', 'percentage', 10.00, 1, '2024-01-01', '2024-12-31', 'active'),
('2x1 en Productos de Limpieza', 'Compra 2 y llévate 3 productos de limpieza', 'bogo', 1.00, 2, '2024-01-01', '2024-06-30', 'active');

-- Insertar productos en promoción
INSERT INTO `promotion_products` (`promotion_id`, `product_id`) VALUES
(1, 1), -- iPhone en promoción
(1, 2), -- Samsung en promoción
(1, 3), -- Laptop en promoción
(2, 6); -- Detergente en promoción 2x1