<?php

namespace D002834\Backend\middleware;

use D002834\Backend\handlers\users\UserHandler;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


function handle_login_request($request_body): void
{
    $key = $_ENV['SECRET_KEY'];
    try {
        $email = $request_body['email'];
        $password = $request_body['password'];
        $user_handler = new UserHandler();
        $user = $user_handler->login($email, $password);
        if (!$user) {
            header("HTTP/1.1 404 Not Found");
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
            header("HTTP/1.1 200 OK");
            echo json_encode(['message' => 'User logged in successfully', 'token' => $jwt]);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        header("HTTP/1.1 500 Internal Server Error");
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function validate_token(): bool
{
    $key = $_ENV['SECRET_KEY'];
    $headers = apache_request_headers();
    $token = $headers['Authorization'];
    $token = explode(" ", $token)[1];
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
