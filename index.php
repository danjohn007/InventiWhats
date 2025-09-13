<?php
/**
 * Sistema de Control de Inventarios Global con POS por Sucursal
 * Main entry point
 * 
 * @author Sistema InventiWhats
 * @version 1.0
 */

// Start session
session_start();

// Define constants
define('ROOT_PATH', dirname(__FILE__));
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/');

// Include core files
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/core/Router.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Model.php';

// Initialize router
$router = new Router();

// Define routes
$router->get('/', 'HomeController@index');
$router->get('/admin', 'AdminController@dashboard');
$router->get('/admin/login', 'AdminController@login');
$router->post('/admin/login', 'AdminController@doLogin');
$router->get('/admin/logout', 'AdminController@logout');
$router->get('/pos', 'POSController@index');
$router->get('/inventario', 'PublicController@inventory');
$router->get('/test-connection', 'TestController@connection');

// Handle the request
$router->dispatch();
?>