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

            header('Content-Type: application/json');
            echo json_encode(['message' => 'User created successfully']);
        } catch (Exception $e) {
            // Handle the exception here
            // For example, you can log the error or return an error response to the client
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function login($email, $password): bool
    {
        try {
            $user = UserRepository::getInstance();
            define("USER", $user->userExists($email, $password));
            return USER;
        } catch (Exception $e) {
            // Handle the exception here
            // For example, you can log the error or return an error response to the client
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            return false;
        }
    }
}
