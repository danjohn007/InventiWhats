<?php

class Router {
    private $routes = [];
    
    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }
    
    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }
    
    public function put($path, $callback) {
        $this->routes['PUT'][$path] = $callback;
    }
    
    public function delete($path, $callback) {
        $this->routes['DELETE'][$path] = $callback;
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_GET['url'] ?? '';
        $url = '/' . trim($url, '/');
        
        if ($url === '//') {
            $url = '/';
        }
        
        // Check for exact route match
        if (isset($this->routes[$method][$url])) {
            $this->callController($this->routes[$method][$url]);
            return;
        }
        
        // Check for parameterized routes
        foreach ($this->routes[$method] ?? [] as $route => $callback) {
            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches); // Remove full match
                $this->callController($callback, $matches);
                return;
            }
        }
        
        // 404 Not Found
        $this->show404();
    }
    
    private function callController($callback, $params = []) {
        if (is_string($callback)) {
            list($controller, $method) = explode('@', $callback);
            
            if (class_exists($controller)) {
                $controllerInstance = new $controller();
                if (method_exists($controllerInstance, $method)) {
                    call_user_func_array([$controllerInstance, $method], $params);
                } else {
                    $this->show404();
                }
            } else {
                $this->show404();
            }
        } else if (is_callable($callback)) {
            call_user_func_array($callback, $params);
        }
    }
    
    private function show404() {
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The requested page could not be found.</p>";
        echo "<a href='" . SITE_URL . "'>Go to Home</a>";
    }
}
?>