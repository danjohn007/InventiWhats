<?php

class HomeController extends Controller {
    
    public function index() {
        $data = [
            'title' => 'InventiWhats - Sistema de Control de Inventarios',
            'page' => 'home'
        ];
        
        // Get some basic statistics if database is available
        try {
            $db = getDBConnection();
            
            // Get branches count
            $stmt = $db->query("SELECT COUNT(*) as count FROM branches WHERE status = 'active'");
            $data['branches_count'] = $stmt->fetch()['count'];
            
            // Get products count
            $stmt = $db->query("SELECT COUNT(*) as count FROM products WHERE status = 'active'");
            $data['products_count'] = $stmt->fetch()['count'];
            
            // Get total inventory value (estimated)
            $stmt = $db->query("
                SELECT SUM(i.quantity * p.retail_price) as total_value 
                FROM inventory i 
                JOIN products p ON i.product_id = p.id 
                WHERE p.status = 'active'
            ");
            $result = $stmt->fetch();
            $data['inventory_value'] = $result['total_value'] ?? 0;
            
            // Get recent sales count (last 30 days)
            $stmt = $db->query("
                SELECT COUNT(*) as count 
                FROM sales 
                WHERE sale_date >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
                AND status = 'completed'
            ");
            $data['recent_sales'] = $stmt->fetch()['count'];
            
        } catch (Exception $e) {
            // Database not available, set default values
            $data['branches_count'] = 0;
            $data['products_count'] = 0;
            $data['inventory_value'] = 0;
            $data['recent_sales'] = 0;
            $data['db_error'] = true;
        }
        
        $this->view('home/index', $data);
    }
}
?>