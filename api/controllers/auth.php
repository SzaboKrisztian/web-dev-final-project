<?php
require_once(__DIR__ . "/../models/customer.php");
require_once(__DIR__ . "/../utils.php");

class AuthController {
    static function login($email, $password) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $customers = CustomerDAO::getInstance();
    
        if ($email == 'admin') {
            $stmt = $customers->getPdo()->query("SELECT Password FROM admin;");
            $hash = $stmt->fetchAll();
            $hash = $hash[0]['Password'];
    
            if (password_verify($password, $hash)) {
                $_SESSION['role'] = 'admin';
            } else {
                Responde::badRequest("Password mismatch");
            }
        } else {
            $user = $customers->findByEmail($email);
        
            if (is_null($user)) {
                Responde::notFound();
            }
        
            if (password_verify($password, $user['Password'])) {
                $_SESSION['role'] = 'user';
                $_SESSION['userData'] = $user;
            } else {
                Responde::badRequest("Password mismatch");
            }
        }
    }
    
    static function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['role'])) {
            echo "{\"message\":\"You are not logged in.\"}";
        } else {
            session_destroy();
            echo "{\"message\":\"Successfully logged out.\"}";
        }
    }
}
?>