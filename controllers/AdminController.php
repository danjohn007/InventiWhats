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
    
    public function logout() {
        session_destroy();
        flash('success', 'Sesión cerrada correctamente');
        redirect('admin/login');
    }
}
?>