<?php

abstract class Controller {
    
    protected function view($view, $data = []) {
        // Extract data array to variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = ROOT_PATH . '/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "View file not found: {$view}";
        }
        
        // Get the content
        $content = ob_get_clean();
        
        // Include layout if not an AJAX request
        if (!$this->isAjaxRequest()) {
            $this->loadLayout($content, $data);
        } else {
            echo $content;
        }
    }
    
    protected function loadLayout($content, $data = []) {
        extract($data);
        include ROOT_PATH . '/views/layouts/main.php';
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    protected function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    protected function validateCSRF($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    protected function generateCSRF() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? '';
            $rulesParts = explode('|', $rule);
            
            foreach ($rulesParts as $rulePart) {
                if ($rulePart === 'required' && empty($value)) {
                    $errors[$field][] = "El campo {$field} es requerido";
                }
                
                if (strpos($rulePart, 'min:') === 0) {
                    $min = (int)substr($rulePart, 4);
                    if (strlen($value) < $min) {
                        $errors[$field][] = "El campo {$field} debe tener al menos {$min} caracteres";
                    }
                }
                
                if (strpos($rulePart, 'max:') === 0) {
                    $max = (int)substr($rulePart, 4);
                    if (strlen($value) > $max) {
                        $errors[$field][] = "El campo {$field} no puede tener más de {$max} caracteres";
                    }
                }
                
                if ($rulePart === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "El campo {$field} debe ser un email válido";
                }
                
                if ($rulePart === 'numeric' && !is_numeric($value)) {
                    $errors[$field][] = "El campo {$field} debe ser numérico";
                }
            }
        }
        
        return $errors;
    }
}
?>