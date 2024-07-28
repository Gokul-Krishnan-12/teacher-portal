<?php

require_once '../vendor/autoload.php'; // Composer autoload

use Firebase\JWT\JWT;

class JwtHandler {
    private $secret_key = "your_secret_key"; // Change this to your secret key
    private $algo;

    public function __construct() {
        $this->algo = 'HS256';
    }

    // Function to generate JWT token
    public function generateToken($data) {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // Token valid for 1 hour
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $data
        );

        return JWT::encode($payload, $this->secret_key, $this->algo);
    }

    // Function to decode JWT token
    public function decodeToken($jwt) {
        return JWT::decode($jwt, $this->secret_key, array($this->algo));
    }

    // Function to validate JWT token
    public function validateToken($jwt) {
        try {
            $decoded = JWT::decode($jwt, $this->secret_key, array($this->algo));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
?>
