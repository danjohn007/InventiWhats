<?php

class User extends Model {
    protected $table = 'users';
    
    public function authenticate($username, $password) {
        $user = $this->findOneBy('username', $username);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    public function getByBranch($branch_id) {
        return $this->findBy('branch_id', $branch_id);
    }
    
    public function getActiveUsers() {
        return $this->findBy('status', 'active');
    }
}
?>