<?php

namespace D002834\Backend\handlers\users;

use D002834\Backend\repository\UserRepository;

class UserHandler
{
    public static function register($fullname, $email, $password): void
    {
        $body = json_decode(file_get_contents('php://input'), true);

        $user = UserRepository::getInstance();

        $user->createOne($fullname, $email, $password);

        header('Content-Type: application/json');
        echo json_encode(['message' => 'User created successfully']);
    }
}