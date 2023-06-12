<?php

namespace Interface\middleware;


use Application\users\services\UserService;
use Backend\interface\middleware\Authentication;
use Utils\ResponseSender;

class Router
{
    public function registerRouting(string $requestMethod, array $requestBody): void
    {
        if ($requestMethod === 'POST') {
            $this->handleRegisterRequest($requestBody);
        } else {
            ResponseSender::sendErrorResponse(405, "Method not allowed");
        }
    }

    private function handleRegisterRequest(array $requestBody): void
    {
        $fullname = $requestBody['fullname'];
        $email = $requestBody['email'];
        $password = $requestBody['password'];
        UserService::register($fullname, $email, $password);
    }

    public function loginRouting(string $requestMethod, array $requestBody): void
    {
        switch ($requestMethod) {
            case 'POST':
                $this->handleLoginRequest($requestBody);
                break;
            default:
                ResponseSender::sendErrorResponse(405, "Method not allowed");
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
            ResponseSender::sendErrorResponse(401, "Unauthorized");
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
                ResponseSender::sendErrorResponse(405, "Method not allowed");
        }
    }

    private function validateToken(): bool
    {

        return (new Authentication($_ENV['JWT_SECRET']))->validateToken();
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
            ResponseSender::sendErrorResponse(400, "Missing parameters");
            return;
        }
        $email = $_GET['email'];
        $userHandler = new UserService();
        $userHandler->getOne($email);
    }
}
