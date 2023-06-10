<?php

namespace Backend\interface\middleware;


use Backend\application\users\services\UserService;

include_once __DIR__ . '/Authentication.php';

class Router
{
    public function registerRouting(string $requestMethod, array $requestBody): void
    {
        switch ($requestMethod) {
            case 'POST':
                $this->handleRegisterRequest($requestBody);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
        }
    }

    private function handleRegisterRequest(array $requestBody): void
    {
        $fullname = $requestBody['fullname'];
        $email = $requestBody['email'];
        $password = $requestBody['password'];
        $userHandler = new UserService();
        $userHandler->register($fullname, $email, $password);
    }

    public function loginRouting(string $requestMethod, array $requestBody): void
    {
        switch ($requestMethod) {
            case 'POST':
                $this->handleLoginRequest($requestBody);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
        }
    }

    private function handleLoginRequest(array $requestBody): void
    {
        $auth = new Authentication($_ENV['JWT_SECRET']);
        $auth->login($requestBody);
    }

    public function userRouting(string $requestMethod, array $requestBody): void
    {
        $isAuthenticated = $this->validateToken();
        if (!$isAuthenticated) {
            http_response_code(401);
            echo json_encode(["message" => "Unauthorized"]);
            return;
        }
        switch ($requestMethod) {
            case 'PUT':
                $this->updateUserRequest($requestBody);
                break;
            case 'GET':
                $this->getUserRequest();
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
        }
    }

    private function validateToken(): bool
    {

        $auth = new Authentication($_ENV['JWT_SECRET']);
        return $auth->validateToken();
    }

    private function updateUserRequest(array $requestBody): void
    {
        $fullname = $requestBody['fullname'];
        $email = $requestBody['email'];
        $userHandler = new UserService();
        $userHandler->update($fullname, $email);
    }

    private function getUserRequest(): void
    {
        if (!isset($_GET['email'])) {
            http_response_code(400);
            echo json_encode(["message" => "Missing parameters"]);
            return;
        }
        $email = $_GET['email'];
        $userHandler = new UserService();
        $userHandler->getOne($email);
    }
}
