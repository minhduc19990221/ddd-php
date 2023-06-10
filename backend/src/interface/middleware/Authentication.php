<?php

namespace Backend\interface\middleware;

use Backend\application\users\services\UserService;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Authentication
{
    private string $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function login(array $request_body): void
    {
        $email = $request_body['email'] ?? null;
        $password = $request_body['password'] ?? null;
        $user_handler = new UserService();
        $user = $user_handler->login($email, $password);
        if (!$user) {
            header("HTTP/1.1 404 Not Found");
            header('Content-Type: application/json');
            echo json_encode(['error' => 'User not found']);
            return;
        }
        $account = [
            "email" => $email,
            "password" => $password
        ];
        $payload = array(
            "iat" => time(),  //When the token was issued
            "exp" => time() + (60 * 60),  //Expiration time
            "data" => $account  //Data related to the signer user
        );
        $jwt = JWT::encode($payload, $this->key, "HS256");  //generate the token
        header('Content-Type: application/json');
        header("HTTP/1.1 200 OK");
        echo json_encode(['message' => 'User logged in successfully', 'token' => $jwt]);
    }

    public function validateToken(): bool
    {
        $headers = apache_request_headers();
        $token = $headers['authorization'] ?? $headers['Authorization'] ?? null;
        if (!$token || strlen($token) < 1) {
            return false;
        }
        try {
            [, $token] = explode(" ", $token, 2);
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            return isset($decoded->data->email, $decoded->data->password);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
