<?php
/**
 * Configuration file for InventiWhats System
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('America/Mexico_City');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'ejercito_inventiwhats');
define('DB_USER', 'ejercito_inventiwhats');
define('DB_PASS', 'Danjohn007!');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('APP_NAME', 'InventiWhats - Sistema de Inventarios');
define('APP_VERSION', '1.0.0');
define('APP_DEBUG', true);

// Security
define('HASH_SALT', 'inventwhats_2024_secure_salt');
define('SESSION_TIMEOUT', 3600); // 1 hour

// Pagination
define('ITEMS_PER_PAGE', 20);

// File uploads
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('UPLOAD_PATH', ROOT_PATH . '/uploads/');

// Base URL auto-detection
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';
$base_path = rtrim(dirname($script_name), '/\\') . '/';

define('SITE_URL', $protocol . $host . $base_path);

// Database connection function
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        // For testing purposes, throw exception instead of dying
        throw $e;
    }
}

// Autoload function for classes
spl_autoload_register(function($class) {
    $directories = [
        ROOT_PATH . '/controllers/',
        ROOT_PATH . '/models/',
        ROOT_PATH . '/core/'
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            break;
        }
    }
});

// Helper functions
function redirect($url) {
    header('Location: ' . SITE_URL . ltrim($url, '/'));
    exit;
}

function flash($key, $message = null) {
    if ($message === null) {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    $_SESSION['flash'][$key] = $message;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireAuth() {
    if (!isLoggedIn()) {
        redirect('admin/login');
    }
}

function formatCurrency($amount) {
    return '$' . number_format($amount, 2, '.', ',');
}

function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>
