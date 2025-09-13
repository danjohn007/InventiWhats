<?php

class Product extends Model {
    protected $table = 'products';
    
    public function getWithInventory($branch_id = null) {
        $sql = "
            SELECT 
                p.*,
                c.name as category_name,
                s.name as supplier_name,
                COALESCE(SUM(i.quantity), 0) as total_stock
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN suppliers s ON p.supplier_id = s.id
            LEFT JOIN inventory i ON p.id = i.product_id
        ";
        
        if ($branch_id) {
            $sql .= " AND i.branch_id = ?";
            $params = [$branch_id];
        } else {
            $params = [];
        }
        
        $sql .= " WHERE p.status = 'active' GROUP BY p.id ORDER BY p.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getLowStock() {
        $sql = "
            SELECT 
                p.*,
                COALESCE(SUM(i.quantity), 0) as total_stock,
                p.min_stock
            FROM products p
            LEFT JOIN inventory i ON p.id = i.product_id
            WHERE p.status = 'active'
            GROUP BY p.id
            HAVING total_stock <= p.min_stock
            ORDER BY total_stock ASC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function search($term, $category_id = null) {
        $sql = "
            SELECT p.*, c.name as category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'active'
            AND (p.name LIKE ? OR p.code LIKE ? OR p.description LIKE ?)
        ";
        
        $params = ["%{$term}%", "%{$term}%", "%{$term}%"];
        
        if ($category_id) {
            $sql .= " AND p.category_id = ?";
            $params[] = $category_id;
        }
        
        $sql .= " ORDER BY p.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
?>