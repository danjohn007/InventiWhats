<?php

class TestController extends Controller {
    
    public function connection() {
        $data = [
            'title' => 'Test de Conexión - InventiWhats',
            'tests' => []
        ];
        
        // Test 1: PHP Version
        $data['tests']['php_version'] = [
            'name' => 'Versión de PHP',
            'status' => version_compare(PHP_VERSION, '7.0.0', '>='),
            'message' => 'PHP ' . PHP_VERSION,
            'required' => 'PHP 7.0+'
        ];
        
        // Test 2: Base URL Detection
        $data['tests']['base_url'] = [
            'name' => 'Detección de URL Base',
            'status' => !empty(SITE_URL),
            'message' => SITE_URL,
            'required' => 'URL base válida'
        ];
        
        // Test 3: Directory Structure
        $required_dirs = ['config', 'controllers', 'models', 'views', 'assets', 'sql', 'core'];
        $missing_dirs = [];
        foreach ($required_dirs as $dir) {
            if (!is_dir(ROOT_PATH . '/' . $dir)) {
                $missing_dirs[] = $dir;
            }
        }
        
        $data['tests']['directories'] = [
            'name' => 'Estructura de Directorios',
            'status' => empty($missing_dirs),
            'message' => empty($missing_dirs) ? 'Todos los directorios existen' : 'Faltan: ' . implode(', ', $missing_dirs),
            'required' => 'Estructura MVC completa'
        ];
        
        // Test 4: Database Connection
        try {
            $db = getDBConnection();
            $stmt = $db->query("SELECT VERSION() as version");
            $version = $stmt->fetch();
            
            $data['tests']['database'] = [
                'name' => 'Conexión a Base de Datos',
                'status' => true,
                'message' => 'MySQL ' . $version['version'],
                'required' => 'MySQL 5.7+'
            ];
            
            // Test 5: Database Tables
            $stmt = $db->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $required_tables = ['branches', 'users', 'products', 'categories', 'inventory', 'sales', 'customers'];
            $missing_tables = array_diff($required_tables, $tables);
            
            $data['tests']['tables'] = [
                'name' => 'Tablas de Base de Datos',
                'status' => empty($missing_tables),
                'message' => empty($missing_tables) ? 
                    count($tables) . ' tablas encontradas' : 
                    'Faltan tablas: ' . implode(', ', $missing_tables),
                'required' => 'Esquema completo de BD'
            ];
            
            // Test 6: Sample Data
            if (empty($missing_tables)) {
                $stmt = $db->query("SELECT COUNT(*) as count FROM products");
                $product_count = $stmt->fetch();
                
                $data['tests']['sample_data'] = [
                    'name' => 'Datos de Ejemplo',
                    'status' => $product_count['count'] > 0,
                    'message' => $product_count['count'] . ' productos encontrados',
                    'required' => 'Datos de muestra'
                ];
            }
            
        } catch (Exception $e) {
            $data['tests']['database'] = [
                'name' => 'Conexión a Base de Datos',
                'status' => false,
                'message' => $e->getMessage(),
                'required' => 'MySQL 5.7+'
            ];
        }
        
        // Test 7: Write Permissions
        $data['tests']['permissions'] = [
            'name' => 'Permisos de Escritura',
            'status' => is_writable(ROOT_PATH),
            'message' => is_writable(ROOT_PATH) ? 'Directorio escribible' : 'Sin permisos de escritura',
            'required' => 'Permisos de escritura'
        ];
        
        // Test 8: Session Support
        $data['tests']['sessions'] = [
            'name' => 'Soporte de Sesiones',
            'status' => session_status() === PHP_SESSION_ACTIVE,
            'message' => session_status() === PHP_SESSION_ACTIVE ? 'Sesiones activas' : 'Sesiones no disponibles',
            'required' => 'Soporte de sesiones PHP'
        ];
        
        // Overall status
        $all_passed = true;
        foreach ($data['tests'] as $test) {
            if (!$test['status']) {
                $all_passed = false;
                break;
            }
        }
        
        $data['overall_status'] = $all_passed;
        $data['system_info'] = [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'No disponible',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'No disponible',
            'root_path' => ROOT_PATH,
            'site_url' => SITE_URL,
            'current_time' => date('Y-m-d H:i:s')
        ];
        
        $this->view('test/connection', $data);
    }
}
?>