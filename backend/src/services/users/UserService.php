<?php

namespace D002834\Backend\handlers\users;

use D002834\Backend\interface\users\UserFactory;
use D002834\Backend\repository\UserRepository;
use Exception;


class UserService
{
    public function __construct()
    {
    }

    public static function register($fullname, $email, $password): void
    {
        try {
            $user = UserFactory::getInstance();
            $user->createOne($fullname, $email, $password);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'User created successfully']);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function login($email, $password): bool
    {
        try {
            $user = UserRepository::getInstance();
            return $user->userExists($email, $password);
        } catch (Exception $e) {
            error_log($e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['error' => 'An error occurred while logging in. Please try again later.']);
            return false;
        }
    }

    public function update($fullname, $email): void
    {
        if (!$fullname || !$email) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }
        try {
            $user = UserRepository::getInstance();
            $user->updateOne($fullname, $email);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'User updated successfully']);
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getOne($email): array
    {
        if (!$email) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing parameters']);
        }
        try {
            $user_repository = UserRepository::getInstance();
            $user = $user_repository->readOne($email);
            if (empty($user) || empty($user_repository)) {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'User not found']);
                return [];
            } else {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode(['message' => 'User retrieved successfully', 'user' => $user]);
                return $user;
            }
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            return [];
        }
    }
}
