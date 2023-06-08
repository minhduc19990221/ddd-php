<?php

namespace D002834\Backend\interface\users;

use D002834\Backend\domain\repository\UserRepository;

class UserFactory implements IUserFactory
{
    private static ?UserFactory $instance = null;
    private static ?UserRepository $user_repository;

    private function __construct()
    {
        self::$user_repository = UserRepository::getInstance();
        self::$user_repository->createTable();
    }

    public static function getInstance(): ?UserFactory
    {
        if (self::$instance == null) {
            self::$instance = new UserFactory();
        }
        return self::$instance;
    }

    public function createOne(string $fullname, string $email, string $password): void
    {
        self::$user_repository->createOne($fullname, $email, $password);
    }
}

