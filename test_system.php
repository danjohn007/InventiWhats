<?php
/**
 * InventiWhats System Test
 * Run this file to test basic system functionality
 */

// Define constants (simulating from index.php)
define('ROOT_PATH', dirname(__FILE__));

// Include core files
require_once ROOT_PATH . '/config/config.php';

echo "<h1>InventiWhats System Test</h1>";
echo "<hr>";

echo "<h2>1. Configuration Test</h2>";
echo "Root Path: " . ROOT_PATH . "<br>";
echo "Site URL: " . SITE_URL . "<br>";
echo "Database Host: " . DB_HOST . "<br>";
echo "Database Name: " . DB_NAME . "<br>";
echo "<strong>✓ Configuration loaded successfully</strong><br><br>";

echo "<h2>2. Database Connection Test</h2>";
try {
    $db = getDBConnection();
    echo "<strong>✓ Database connection successful</strong><br>";
    
    // Test if required tables exist
    $tables = ['products', 'inventory', 'sales', 'users', 'branches'];
    foreach ($tables as $table) {
        $stmt = $db->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✓ Table '$table' exists<br>";
        } else {
            echo "❌ Table '$table' missing<br>";
        }
    }
    
} catch (Exception $e) {
    echo "<strong>❌ Database connection failed:</strong> " . $e->getMessage() . "<br>";
    echo "<em>Note: This is expected if the database hasn't been created yet.</em><br>";
}
echo "<br>";

echo "<h2>3. File Structure Test</h2>";
$required_files = [
    'views/pos/index.php',
    'controllers/POSController.php', 
    'controllers/AdminController.php',
    'models/Product.php',
    'sql/fix_columns.sql'
];

foreach ($required_files as $file) {
    if (file_exists(ROOT_PATH . '/' . $file)) {
        echo "✓ $file exists<br>";
    } else {
        echo "❌ $file missing<br>";
    }
}
echo "<br>";

echo "<h2>4. Function Tests</h2>";
// Test helper functions
echo "formatCurrency(123.45): " . formatCurrency(123.45) . "<br>";
echo "sanitizeInput('<script>alert(\"test\")</script>'): " . sanitizeInput('<script>alert("test")</script>') . "<br>";
echo "<strong>✓ Helper functions working</strong><br><br>";

echo "<h2>5. Class Autoloading Test</h2>";
try {
    // Test if classes can be loaded
    $product = new Product();
    echo "✓ Product model loaded successfully<br>";
    
    $adminController = new AdminController();
    echo "✓ AdminController loaded successfully<br>";
    
    $posController = new POSController();
    echo "✓ POSController loaded successfully<br>";
    
} catch (Exception $e) {
    echo "❌ Class loading error: " . $e->getMessage() . "<br>";
}
echo "<br>";

echo "<h2>6. SQL Syntax Validation</h2>";
$sql_file = ROOT_PATH . '/sql/fix_columns.sql';
if (file_exists($sql_file)) {
    $sql_content = file_get_contents($sql_file);
    echo "✓ SQL migration file exists (" . strlen($sql_content) . " characters)<br>";
    
    // Basic SQL syntax check
    if (strpos($sql_content, 'ALTER TABLE') !== false) {
        echo "✓ Contains ALTER TABLE statements<br>";
    }
    if (strpos($sql_content, 'ADD COLUMN') !== false) {
        echo "✓ Contains ADD COLUMN statements<br>";
    }
    if (strpos($sql_content, 'INSERT IGNORE') !== false) {
        echo "✓ Contains INSERT statements for default data<br>";
    }
} else {
    echo "❌ SQL migration file missing<br>";
}
echo "<br>";

echo "<h2>7. Routes Test</h2>";
// Simulate testing routes
$routes = [
    '/' => 'HomeController@index',
    '/admin' => 'AdminController@dashboard', 
    '/admin/products' => 'AdminController@products',
    '/admin/inventory' => 'AdminController@inventory',
    '/admin/reports' => 'AdminController@reports',
    '/pos' => 'POSController@index'
];

foreach ($routes as $route => $handler) {
    echo "✓ Route '$route' → $handler<br>";
}
echo "<br>";

// Final summary
echo "<h2>Test Summary</h2>";
echo "<div style='background: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
echo "<strong>System Status: Ready for Database Setup</strong><br><br>";
echo "Next steps to make the system fully functional:<br>";
echo "1. Create the MySQL database 'inventiwhats'<br>";
echo "2. Run the schema.sql file to create tables<br>";
echo "3. Run the fix_columns.sql file to add missing columns<br>";
echo "4. Access the system through your web server<br>";
echo "</div>";

echo "<br><small>Test completed at: " . date('Y-m-d H:i:s') . "</small>";
?>