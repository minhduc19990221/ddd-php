<?php

namespace D002834\Backend\services\users;

use D002834\Backend\domain\repository\UserRepository;
use D002834\Backend\interface\users\UserFactory;


class UserService
{

    public static function register(string $fullname, string $email, string $password): void
    {
        if (!$fullname || !$email || !$password) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }
        $user = UserFactory::getInstance();
        $user->createOne($fullname, $email, $password);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'User created successfully']);
    }

    public function login(string $email, string $password): bool
    {
        if (!$email || !$password) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing parameters']);
            return false;
        }
        $user = UserRepository::getInstance();
        return $user->userExists($email, $password);
    }

    public function update(string $fullname, string $email): void
    {
        if (!$fullname || !$email) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }
        $this->isUserExisted($email);
        $user = UserRepository::getInstance();
        $user->updateOne($fullname, $email);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'User updated successfully']);
    }

    private function isUserExisted(string $email): bool
    {
        if (!$email) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing parameters']);
            return false;
        }
        $user_repository = UserRepository::getInstance();
        $user = $user_repository->readOne($email);
        if (empty($user)) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'User not found']);
            return false;
        } else {
            return true;
        }
    }

    public function getOne(string $email): array
    {
        if (!$this->isUserExisted($email)) {
            return [];
        }
        $user_repository = UserRepository::getInstance();
        $user = $user_repository->readOne($email);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'User retrieved successfully', 'user' => $user]);
        return $user;
    }
}
