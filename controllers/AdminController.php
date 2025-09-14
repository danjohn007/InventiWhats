<?php

class AdminController extends Controller {
    
    public function dashboard() {
        requireAuth();
        
        $data = [
            'title' => 'Dashboard - InventiWhats Admin',
            'page' => 'dashboard',
            'user' => $_SESSION['user_name'] ?? 'Usuario'
        ];
        
        try {
            $db = getDBConnection();
            
            // Dashboard statistics
            $stats = [];
            
            // Total products
            $stmt = $db->query("SELECT COUNT(*) as count FROM products WHERE status = 'active'");
            $stats['total_products'] = $stmt->fetch()['count'];
            
            // Low stock products
            $stmt = $db->query("
                SELECT COUNT(DISTINCT p.id) as count
                FROM products p
                LEFT JOIN (
                    SELECT product_id, SUM(quantity) as total_stock
                    FROM inventory
                    GROUP BY product_id
                ) i ON p.id = i.product_id
                WHERE p.status = 'active' 
                AND (i.total_stock <= p.min_stock OR i.total_stock IS NULL)
            ");
            $stats['low_stock'] = $stmt->fetch()['count'];
            
            // Today's sales
            $stmt = $db->query("
                SELECT COUNT(*) as count, COALESCE(SUM(total), 0) as amount
                FROM sales 
                WHERE DATE(sale_date) = CURDATE() 
                AND status = 'completed'
            ");
            $today_sales = $stmt->fetch();
            $stats['today_sales'] = $today_sales['count'];
            $stats['today_amount'] = $today_sales['amount'];
            
            // Active branches
            $stmt = $db->query("SELECT COUNT(*) as count FROM branches WHERE status = 'active'");
            $stats['active_branches'] = $stmt->fetch()['count'];
            
            // Recent sales for chart
            $stmt = $db->query("
                SELECT DATE(sale_date) as date, COUNT(*) as count, SUM(total) as amount
                FROM sales 
                WHERE sale_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                AND status = 'completed'
                GROUP BY DATE(sale_date)
                ORDER BY date DESC
            ");
            $stats['recent_sales'] = $stmt->fetchAll();
            
            // Top selling products
            $stmt = $db->query("
                SELECT p.name, SUM(sd.quantity) as quantity_sold, SUM(sd.subtotal) as total_sales
                FROM sale_details sd
                JOIN products p ON sd.product_id = p.id
                JOIN sales s ON sd.sale_id = s.id
                WHERE s.sale_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                AND s.status = 'completed'
                GROUP BY p.id, p.name
                ORDER BY quantity_sold DESC
                LIMIT 5
            ");
            $stats['top_products'] = $stmt->fetchAll();
            
            $data['stats'] = $stats;
            
        } catch (Exception $e) {
            $data['error'] = 'Error al cargar estadísticas: ' . $e->getMessage();
        }
        
        $this->view('admin/dashboard', $data);
    }
    
    public function login() {
        if (isLoggedIn()) {
            redirect('admin');
        }
        
        $data = [
            'title' => 'Iniciar Sesión - InventiWhats Admin',
            'page' => 'login'
        ];
        
        $this->view('admin/login', $data);
    }
    
    public function doLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/login');
        }
        
        $username = sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            flash('error', 'Por favor ingrese usuario y contraseña');
            redirect('admin/login');
        }
        
        try {
            $userModel = new User();
            $user = $userModel->findOneBy('username', $username);
            
            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] !== 'active') {
                    flash('error', 'Usuario inactivo');
                    redirect('admin/login');
                }
                
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_branch'] = $user['branch_id'];
                $_SESSION['last_activity'] = time();
                
                // Update last login
                $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
                
                flash('success', '¡Bienvenido ' . $user['name'] . '!');
                redirect('admin');
            } else {
                flash('error', 'Usuario o contraseña incorrectos');
                redirect('admin/login');
            }
        } catch (Exception $e) {
            flash('error', 'Error en el sistema: ' . $e->getMessage());
            redirect('admin/login');
        }
    }
    
    public function products() {
        requireAuth();
        
        $data = [
            'title' => 'Gestión de Productos - InventiWhats Admin',
            'page' => 'products'
        ];
        
        try {
            $db = getDBConnection();
            
            // Get all products with category info
            $stmt = $db->query("
                SELECT p.*, c.name as category_name,
                       COALESCE(SUM(i.quantity), 0) as total_stock
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN inventory i ON p.id = i.product_id
                WHERE p.status = 'active'
                GROUP BY p.id
                ORDER BY p.created_at DESC
            ");
            $data['products'] = $stmt->fetchAll();
            
            // Get categories for filter
            $stmt = $db->query("SELECT * FROM categories ORDER BY name");
            $data['categories'] = $stmt->fetchAll();
            
        } catch (Exception $e) {
            $data['error'] = 'Error al cargar productos: ' . $e->getMessage();
            $data['products'] = [];
            $data['categories'] = [];
        }
        
        $this->view('admin/products', $data);
    }
    
    public function inventory() {
        requireAuth();
        
        $data = [
            'title' => 'Control de Inventario - InventiWhats Admin',
            'page' => 'inventory'
        ];
        
        try {
            $db = getDBConnection();
            
            // Get inventory by branch with product info
            $stmt = $db->query("
                SELECT i.*, p.name as product_name, p.code, p.min_stock,
                       b.name as branch_name, p.retail_price
                FROM inventory i
                JOIN products p ON i.product_id = p.id
                JOIN branches b ON i.branch_id = b.id
                WHERE p.status = 'active'
                ORDER BY b.name, p.name
            ");
            $data['inventory'] = $stmt->fetchAll();
            
            // Get branches
            $stmt = $db->query("SELECT * FROM branches WHERE status = 'active' ORDER BY name");
            $data['branches'] = $stmt->fetchAll();
            
            // Low stock alerts
            $stmt = $db->query("
                SELECT p.name, p.code, p.min_stock, b.name as branch_name,
                       i.quantity, (p.min_stock - i.quantity) as deficit
                FROM inventory i
                JOIN products p ON i.product_id = p.id
                JOIN branches b ON i.branch_id = b.id
                WHERE i.quantity <= p.min_stock
                AND p.status = 'active'
                ORDER BY deficit DESC
            ");
            $data['low_stock'] = $stmt->fetchAll();
            
        } catch (Exception $e) {
            $data['error'] = 'Error al cargar inventario: ' . $e->getMessage();
            $data['inventory'] = [];
            $data['branches'] = [];
            $data['low_stock'] = [];
        }
        
        $this->view('admin/inventory', $data);
    }
    
    public function sales() {
        requireAuth();
        
        $data = [
            'title' => 'Gestión de Ventas - InventiWhats Admin',
            'page' => 'sales'
        ];
        
        try {
            $db = getDBConnection();
            
            // Get recent sales with details
            $stmt = $db->query("
                SELECT s.*, b.name as branch_name, u.name as user_name,
                       c.name as customer_name, c.email as customer_email
                FROM sales s
                LEFT JOIN branches b ON s.branch_id = b.id
                LEFT JOIN users u ON s.cashier_id = u.id
                LEFT JOIN customers c ON s.customer_id = c.id
                ORDER BY s.sale_date DESC
                LIMIT 50
            ");
            $data['sales'] = $stmt->fetchAll();
            
            // Sales summary for today
            $stmt = $db->query("
                SELECT COUNT(*) as count, COALESCE(SUM(total), 0) as amount,
                       COALESCE(SUM(tax), 0) as tax_amount
                FROM sales 
                WHERE DATE(sale_date) = CURDATE() 
                AND status = 'completed'
            ");
            $data['today_summary'] = $stmt->fetch();
            
            // Sales by status
            $stmt = $db->query("
                SELECT status, COUNT(*) as count, COALESCE(SUM(total), 0) as amount
                FROM sales
                WHERE DATE(sale_date) = CURDATE()
                GROUP BY status
            ");
            $data['status_summary'] = $stmt->fetchAll();
            
        } catch (Exception $e) {
            $data['error'] = 'Error al cargar ventas: ' . $e->getMessage();
            $data['sales'] = [];
            $data['today_summary'] = ['count' => 0, 'amount' => 0, 'tax_amount' => 0];
            $data['status_summary'] = [];
        }
        
        $this->view('admin/sales', $data);
    }
    
    public function customers() {
        requireAuth();
        
        $data = [
            'title' => 'Gestión de Clientes - InventiWhats Admin',
            'page' => 'customers'
        ];
        
        try {
            $db = getDBConnection();
            
            // Get all customers with purchase summary
            $stmt = $db->query("
                SELECT c.*, 
                       COUNT(s.id) as total_purchases,
                       COALESCE(SUM(s.total), 0) as total_spent,
                       MAX(s.sale_date) as last_purchase
                FROM customers c
                LEFT JOIN sales s ON c.id = s.customer_id AND s.status = 'completed'
                GROUP BY c.id, c.name, c.email, c.phone, c.address, 
                         c.tax_id, c.status, c.created_at
                ORDER BY c.created_at DESC
            ");
            $data['customers'] = $stmt->fetchAll();
            
            // Customer statistics
            $stmt = $db->query("
                SELECT 
                    COUNT(*) as total_customers,
                    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_customers,
                    COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as new_this_month
                FROM customers
            ");
            $data['stats'] = $stmt->fetch();
            
        } catch (Exception $e) {
            $data['error'] = 'Error al cargar clientes: ' . $e->getMessage();
            $data['customers'] = [];
            $data['stats'] = ['total_customers' => 0, 'active_customers' => 0, 'new_this_month' => 0];
        }
        
        $this->view('admin/customers', $data);
    }
    
    public function reports() {
        requireAuth();
        
        $data = [
            'title' => 'Reportes y Análisis - InventiWhats Admin',
            'page' => 'reports'
        ];
        
        try {
            $db = getDBConnection();
            
            // Monthly sales report
            $stmt = $db->query("
                SELECT 
                    YEAR(sale_date) as year,
                    MONTH(sale_date) as month,
                    MONTHNAME(sale_date) as month_name,
                    COUNT(*) as total_sales,
                    SUM(total) as total_amount,
                    AVG(total) as average_sale
                FROM sales 
                WHERE status = 'completed'
                AND sale_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY YEAR(sale_date), MONTH(sale_date)
                ORDER BY year DESC, month DESC
            ");
            $data['monthly_sales'] = $stmt->fetchAll();
            
            // Top selling products
            $stmt = $db->query("
                SELECT p.name, p.code,
                       SUM(sd.quantity) as total_quantity,
                       SUM(sd.subtotal) as total_revenue,
                       AVG(sd.unit_price) as avg_price
                FROM sale_details sd
                JOIN products p ON sd.product_id = p.id
                JOIN sales s ON sd.sale_id = s.id
                WHERE s.status = 'completed'
                AND s.sale_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY p.id, p.name, p.code
                ORDER BY total_quantity DESC
                LIMIT 10
            ");
            $data['top_products'] = $stmt->fetchAll();
            
            // Branch performance
            $stmt = $db->query("
                SELECT b.name as branch_name,
                       COUNT(s.id) as total_sales,
                       SUM(s.total) as total_revenue,
                       AVG(s.total) as avg_sale_value
                FROM branches b
                LEFT JOIN sales s ON b.id = s.branch_id 
                    AND s.status = 'completed'
                    AND s.sale_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                WHERE b.status = 'active'
                GROUP BY b.id, b.name
                ORDER BY total_revenue DESC
            ");
            $data['branch_performance'] = $stmt->fetchAll();
            
        } catch (Exception $e) {
            $data['error'] = 'Error al generar reportes: ' . $e->getMessage();
            $data['monthly_sales'] = [];
            $data['top_products'] = [];
            $data['branch_performance'] = [];
        }
        
        $this->view('admin/reports', $data);
    }
    
    public function logout() {
        session_destroy();
        flash('success', 'Sesión cerrada correctamente');
        redirect('admin/login');
    }
}
?>