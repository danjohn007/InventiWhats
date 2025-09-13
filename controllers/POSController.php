<?php

class POSController extends Controller {
    
    public function index() {
        requireAuth();
        
        // Only allow cashiers and above
        if (!in_array($_SESSION['user_role'], ['admin', 'manager', 'cashier'])) {
            flash('error', 'No tienes permisos para acceder al POS');
            redirect('admin');
        }
        
        $data = [
            'title' => 'Punto de Venta - InventiWhats POS',
            'page' => 'pos',
            'user' => $_SESSION['user_name'] ?? 'Usuario',
            'user_branch' => $_SESSION['user_branch']
        ];
        
        try {
            $db = getDBConnection();
            
            // Get products for the POS (active products with stock)
            $branch_filter = $_SESSION['user_branch'] ? " AND i.branch_id = {$_SESSION['user_branch']}" : "";
            
            $stmt = $db->query("
                SELECT 
                    p.*,
                    c.name as category_name,
                    COALESCE(SUM(i.quantity), 0) as stock
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN inventory i ON p.id = i.product_id {$branch_filter}
                WHERE p.status = 'active'
                GROUP BY p.id
                HAVING stock > 0
                ORDER BY p.name
                LIMIT 50
            ");
            $data['products'] = $stmt->fetchAll();
            
            // Get categories for quick filter
            $stmt = $db->query("SELECT * FROM categories WHERE status = 'active' ORDER BY name");
            $data['categories'] = $stmt->fetchAll();
            
            // Get customers for the sale
            $stmt = $db->query("SELECT * FROM customers WHERE status = 'active' ORDER BY name LIMIT 100");
            $data['customers'] = $stmt->fetchAll();
            
            // Get current branch info
            if ($_SESSION['user_branch']) {
                $stmt = $db->prepare("SELECT * FROM branches WHERE id = ?");
                $stmt->execute([$_SESSION['user_branch']]);
                $data['branch'] = $stmt->fetch();
            }
            
            // Get tax rate from settings
            $stmt = $db->query("SELECT value FROM settings WHERE `key` = 'tax_rate' LIMIT 1");
            $tax_rate = $stmt->fetch();
            $data['tax_rate'] = $tax_rate ? (float)$tax_rate['value'] : 16.0;
            
        } catch (Exception $e) {
            $data['error'] = 'Error al cargar el POS: ' . $e->getMessage();
            $data['products'] = [];
            $data['categories'] = [];
            $data['customers'] = [];
        }
        
        $this->view('pos/index', $data);
    }
    
    public function searchProduct() {
        requireAuth();
        
        if (!$this->isAjaxRequest()) {
            redirect('pos');
        }
        
        $search = sanitizeInput($_GET['q'] ?? '');
        
        if (strlen($search) < 2) {
            $this->json(['products' => []]);
            return;
        }
        
        try {
            $db = getDBConnection();
            $branch_filter = $_SESSION['user_branch'] ? " AND i.branch_id = {$_SESSION['user_branch']}" : "";
            
            $stmt = $db->prepare("
                SELECT 
                    p.*,
                    c.name as category_name,
                    COALESCE(SUM(i.quantity), 0) as stock
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN inventory i ON p.id = i.product_id {$branch_filter}
                WHERE p.status = 'active'
                AND (p.name LIKE ? OR p.code LIKE ?)
                GROUP BY p.id
                HAVING stock > 0
                ORDER BY p.name
                LIMIT 20
            ");
            
            $search_term = "%{$search}%";
            $stmt->execute([$search_term, $search_term]);
            $products = $stmt->fetchAll();
            
            $this->json(['products' => $products]);
            
        } catch (Exception $e) {
            $this->json(['error' => $e->getMessage()]);
        }
    }
    
    public function processSale() {
        requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('pos');
        }
        
        if (!in_array($_SESSION['user_role'], ['admin', 'manager', 'cashier'])) {
            flash('error', 'No tienes permisos para procesar ventas');
            redirect('pos');
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->json(['success' => false, 'message' => 'Datos inválidos']);
            return;
        }
        
        try {
            $db = getDBConnection();
            $db->beginTransaction();
            
            // Validate sale data
            $items = $input['items'] ?? [];
            $customer_id = $input['customer_id'] ?? null;
            $payment_method = $input['payment_method'] ?? 'cash';
            $discount = (float)($input['discount'] ?? 0);
            
            if (empty($items)) {
                throw new Exception('No hay productos en la venta');
            }
            
            // Calculate totals
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            
            $discount_amount = $subtotal * ($discount / 100);
            $subtotal_after_discount = $subtotal - $discount_amount;
            
            // Get tax rate
            $stmt = $db->query("SELECT value FROM settings WHERE `key` = 'tax_rate' LIMIT 1");
            $tax_rate_setting = $stmt->fetch();
            $tax_rate = $tax_rate_setting ? (float)$tax_rate_setting['value'] : 16.0;
            
            $tax_amount = $subtotal_after_discount * ($tax_rate / 100);
            $total = $subtotal_after_discount + $tax_amount;
            
            // Generate sale number
            $sale_number = 'VTA-' . str_pad($_SESSION['user_branch'] ?? 1, 3, '0', STR_PAD_LEFT) . '-' . date('Y') . '-' . time();
            
            // Insert sale
            $stmt = $db->prepare("
                INSERT INTO sales (
                    sale_number, branch_id, customer_id, cashier_id,
                    subtotal, discount, tax, total, payment_method
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $sale_number,
                $_SESSION['user_branch'] ?? 1,
                $customer_id,
                $_SESSION['user_id'],
                $subtotal,
                $discount_amount,
                $tax_amount,
                $total,
                $payment_method
            ]);
            
            $sale_id = $db->lastInsertId();
            
            // Insert sale details and update inventory
            foreach ($items as $item) {
                // Insert sale detail
                $stmt = $db->prepare("
                    INSERT INTO sale_details (
                        sale_id, product_id, quantity, unit_price, subtotal
                    ) VALUES (?, ?, ?, ?, ?)
                ");
                
                $item_total = $item['price'] * $item['quantity'];
                $stmt->execute([
                    $sale_id,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price'],
                    $item_total
                ]);
                
                // Update inventory
                $stmt = $db->prepare("
                    UPDATE inventory 
                    SET quantity = quantity - ? 
                    WHERE product_id = ? AND branch_id = ?
                ");
                $stmt->execute([
                    $item['quantity'],
                    $item['product_id'],
                    $_SESSION['user_branch'] ?? 1
                ]);
                
                // Insert stock movement
                $stmt = $db->prepare("
                    INSERT INTO stock_movements (
                        product_id, branch_id, type, quantity, 
                        reference_type, reference_id, user_id
                    ) VALUES (?, ?, 'out', ?, 'sale', ?, ?)
                ");
                $stmt->execute([
                    $item['product_id'],
                    $_SESSION['user_branch'] ?? 1,
                    $item['quantity'],
                    $sale_id,
                    $_SESSION['user_id']
                ]);
            }
            
            // Update customer loyalty points if customer selected
            if ($customer_id) {
                $points_earned = floor($total);
                
                $stmt = $db->prepare("
                    UPDATE customers 
                    SET loyalty_points = loyalty_points + ?, 
                        total_purchases = total_purchases + ?
                    WHERE id = ?
                ");
                $stmt->execute([$points_earned, $total, $customer_id]);
                
                // Insert loyalty history
                $stmt = $db->prepare("
                    INSERT INTO loyalty_history (
                        customer_id, points, type, reference_type, 
                        reference_id, description
                    ) VALUES (?, ?, 'earned', 'sale', ?, ?)
                ");
                $stmt->execute([
                    $customer_id,
                    $points_earned,
                    $sale_id,
                    "Compra {$sale_number}"
                ]);
                
                // Update sale with loyalty points
                $stmt = $db->prepare("UPDATE sales SET loyalty_points_earned = ? WHERE id = ?");
                $stmt->execute([$points_earned, $sale_id]);
            }
            
            $db->commit();
            
            $this->json([
                'success' => true,
                'sale_id' => $sale_id,
                'sale_number' => $sale_number,
                'total' => $total,
                'message' => 'Venta procesada exitosamente'
            ]);
            
        } catch (Exception $e) {
            $db->rollBack();
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
?>