<?php

class PublicController extends Controller {
    
    public function inventory() {
        $data = [
            'title' => 'Inventario Público - InventiWhats',
            'page' => 'inventory'
        ];
        
        // Get search parameters
        $search = sanitizeInput($_GET['search'] ?? '');
        $category = (int)($_GET['category'] ?? 0);
        $branch = (int)($_GET['branch'] ?? 0);
        $page = (int)($_GET['page'] ?? 1);
        
        try {
            $db = getDBConnection();
            
            // Get categories for filter
            $stmt = $db->query("SELECT * FROM categories WHERE status = 'active' ORDER BY name");
            $data['categories'] = $stmt->fetchAll();
            
            // Get branches for filter
            $stmt = $db->query("SELECT * FROM branches WHERE status = 'active' ORDER BY name");
            $data['branches'] = $stmt->fetchAll();
            
            // Build query for products
            $where_conditions = ["p.status = 'active'"];
            $params = [];
            
            if (!empty($search)) {
                $where_conditions[] = "(p.name LIKE ? OR p.code LIKE ? OR p.description LIKE ?)";
                $search_term = "%{$search}%";
                $params[] = $search_term;
                $params[] = $search_term;
                $params[] = $search_term;
            }
            
            if ($category > 0) {
                $where_conditions[] = "p.category_id = ?";
                $params[] = $category;
            }
            
            $where_clause = implode(' AND ', $where_conditions);
            
            // Get products with inventory
            $sql = "
                SELECT 
                    p.*,
                    c.name as category_name,
                    COALESCE(SUM(CASE WHEN i.branch_id = ? OR ? = 0 THEN i.quantity ELSE 0 END), 0) as total_stock,
                    GROUP_CONCAT(
                        DISTINCT CONCAT(b.name, ':', COALESCE(i.quantity, 0)) 
                        ORDER BY b.name 
                        SEPARATOR '|'
                    ) as branch_stock
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN inventory i ON p.id = i.product_id
                LEFT JOIN branches b ON i.branch_id = b.id AND b.status = 'active'
                WHERE {$where_clause}
                GROUP BY p.id
                HAVING total_stock > 0 OR ? = 0
                ORDER BY p.name
                LIMIT ? OFFSET ?
            ";
            
            $items_per_page = 12;
            $offset = ($page - 1) * $items_per_page;
            
            // Add branch filter and pagination parameters
            $query_params = array_merge(
                [$branch, $branch], 
                $params, 
                [$branch], 
                [$items_per_page, $offset]
            );
            
            $stmt = $db->prepare($sql);
            $stmt->execute($query_params);
            $products = $stmt->fetchAll();
            
            // Process branch stock information
            foreach ($products as &$product) {
                $product['branch_inventory'] = [];
                if ($product['branch_stock']) {
                    $branches_data = explode('|', $product['branch_stock']);
                    foreach ($branches_data as $branch_data) {
                        list($branch_name, $quantity) = explode(':', $branch_data);
                        $product['branch_inventory'][$branch_name] = (int)$quantity;
                    }
                }
            }
            
            // Get total count for pagination
            $count_sql = "
                SELECT COUNT(DISTINCT p.id) as total
                FROM products p
                LEFT JOIN inventory i ON p.id = i.product_id
                WHERE {$where_clause}
                AND (EXISTS(SELECT 1 FROM inventory WHERE product_id = p.id AND quantity > 0) OR ? = 0)
            ";
            
            $count_params = array_merge($params, [$branch]);
            $stmt = $db->prepare($count_sql);
            $stmt->execute($count_params);
            $total_products = $stmt->fetch()['total'];
            
            $data['products'] = $products;
            $data['pagination'] = [
                'current_page' => $page,
                'total_pages' => ceil($total_products / $items_per_page),
                'items_per_page' => $items_per_page,
                'total_items' => $total_products
            ];
            
            $data['filters'] = [
                'search' => $search,
                'category' => $category,
                'branch' => $branch
            ];
            
        } catch (Exception $e) {
            $data['error'] = 'Error al cargar inventario: ' . $e->getMessage();
            $data['products'] = [];
            $data['categories'] = [];
            $data['branches'] = [];
        }
        
        $this->view('public/inventory', $data);
    }
}
?>