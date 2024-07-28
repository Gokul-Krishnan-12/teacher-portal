<?php

require_once __DIR__ . '/../models/User.php';
require_once '../helpers/jwt.php';

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($username, $password) {
        $user = new User($this->db);
        $authenticated = $user->authenticate($username, $password);
    
        if ($authenticated) {
            // Generate JWT token
            $jwt = new JwtHandler();
            $token = $jwt->generateToken(['username' => $username]);
    
            // Store token in session (or local storage in a client-side application)
            $_SESSION['jwt_token'] = $token;
    
            return true; // Return true on successful authentication and token generation
        }
            return false; // Return false if authentication fails

    }

    public function logout() {
        // Clear token from session or local storage
        unset($_SESSION['jwt_token']);
    }

    public function verifyToken($jwt_token) {
        // Verify token validity
        $jwt = new JwtHandler();
        return $jwt->validateToken($jwt_token);
    }
}