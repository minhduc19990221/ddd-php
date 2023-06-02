<?php

namespace D002834\Backend\middleware;

use D002834\Backend\handlers\users\UserHandler;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


$key = "your_secret_key";
function handle_login_request(): void
{
    global $key;
    try {
        global $request_body;
        $email = $request_body['email'];
        $password = $request_body['password'];
        $user_handler = new UserHandler();
        $user = $user_handler->login($email, $password);
        if (!$user) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'User not found']);
        } else {
            $account = [
                "email" => $email,
                "password" => $password
            ];
            $payload = array(
                "iat" => time(),  //When the token was issued
                "exp" => time() + (60 * 60),  //Expiration time
                "data" => $account  //Data related to the signer user
            );
            $jwt = JWT::encode($payload, $key, "HS256");  //generate the token
            header('Content-Type: application/json');
            echo json_encode(['message' => 'User logged in successfully', 'token' => $jwt]);
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function validate_token(): bool
{
    global $key;
    $headers = apache_request_headers();
    $token = $headers['Authorization'];
    if ($token) {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        if ($decoded->data->email && $decoded->data->password) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
