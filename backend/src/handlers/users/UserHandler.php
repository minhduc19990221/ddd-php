<?php

namespace D002834\Backend\handlers\users;

use D002834\Backend\interface\users\UserFactory;
use D002834\Backend\repository\UserRepository;
use Exception;


class UserHandler
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
}
