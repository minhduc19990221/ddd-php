<?php

namespace Interface\middleware;


use Application\boards\services\BoardService;
use Application\cards\services\CardService;
use Application\users\services\UserService;
use Utils\ResponseSender;

class Router
{
    private Authentication $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

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
        if ($requestMethod === 'POST') {
            $this->isRequestBodyEmpty($requestBody);
            $this->handleLoginRequest($requestBody);
        } else {
            ResponseSender::sendErrorResponse(405, "Method not allowed");
        }
    }

    private function isRequestBodyEmpty(array $requestBody): void
    {
        if (empty($requestBody)) {
            ResponseSender::sendErrorResponse(400, "Missing parameters");
            exit();
        }
    }

    private function handleLoginRequest(array $requestBody): void
    {
        $this->auth->login($requestBody);
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
                $this->isRequestBodyEmpty($requestBody);
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

        return $this->auth->validateToken();
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

    public function boardRouting(string $requestMethod, array $requestBody): void
    {
        $isAuthenticated = $this->validateToken();
        if (!$isAuthenticated) {
            ResponseSender::sendErrorResponse(401, "Unauthorized");
            return;
        }
        switch ($requestMethod) {
            case 'POST':
                $this->isRequestBodyEmpty($requestBody);
                $this->createBoardRequest($requestBody);
                break;
            case 'GET':
                $this->getBoardRequest();
                break;
            case 'PUT':
                $this->isRequestBodyEmpty($requestBody);
                $this->updateBoardRequest($requestBody);
                break;
            case 'DELETE':
                $this->isRequestBodyEmpty($requestBody);
                $this->deleteBoardRequest($requestBody);
                break;
            default:
                ResponseSender::sendErrorResponse(405, "Method not allowed");
        }
    }

    private function createBoardRequest(array $requestBody): void
    {
        $title = $requestBody['title'];
        $email = $requestBody['email'];
        $boardHandler = new BoardService();
        $boardHandler->create($title, $email);
    }

    private function getBoardRequest(): void
    {
        $email = $_GET['email'];
        $boardHandler = new BoardService();
        $result = $boardHandler->getAll($email);
        ResponseSender::sendSuccessResponse(200, $result);
    }

    private function updateBoardRequest(array $requestBody): void
    {
        $id = $requestBody['id'];
        $title = $requestBody['title'];
        $boardHandler = new BoardService();
        $boardHandler->updateOne($title, $id);
    }

    private function deleteBoardRequest(array $requestBody): void
    {
        $id = $requestBody['id'];
        $boardHandler = new BoardService();
        $boardHandler->deleteOne($id);
    }


    public function cardRouting(string $requestMethod, array $requestBody): void
    {
        $isAuthenticated = $this->validateToken();
        if (!$isAuthenticated) {
            ResponseSender::sendErrorResponse(401, "Unauthorized");
            return;
        }
        switch ($requestMethod) {
            case 'POST':
                $this->isRequestBodyEmpty($requestBody);
                $this->createCardRequest($requestBody);
                break;
            case 'GET':
                $this->getCardRequest();
                break;
            default:
                ResponseSender::sendErrorResponse(405, "Method not allowed");
        }
    }

    private function createCardRequest(array $requestBody): void
    {
        $title = $requestBody['title'];
        $board_id = $requestBody['board_id'];
        $index_board = $requestBody['index_board'];
        $cardHandler = new CardService();
        $cardHandler->create($title, $board_id, $index_board);
    }

    private function getCardRequest(): void
    {
        $board_id = $_GET['board_id'];
        $cardHandler = new CardService();
        $result = $cardHandler->getAll($board_id);
        ResponseSender::sendSuccessResponse(200, $result);
    }
}
