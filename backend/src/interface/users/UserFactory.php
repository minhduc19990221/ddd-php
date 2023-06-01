<?php
namespace D002834\Backend\interface\users;

use D002834\Backend\repository\UserRepository;

class UserFactory implements IUserFactory
{
    private static ?UserFactory $instance = null;
    private static ?UserRepository $user_repository;

    private function __construct()
    {
        self::$user_repository = UserRepository::getInstance();
    }

    public static function getInstance(): ?UserFactory
    {
        if (self::$instance == null) {
            self::$instance = new UserFactory();
        }
        return self::$instance;
    }

    public function createOne($fullname, $email, $password): void
    {
        self::$user_repository->createOne($fullname, $email, $password);
    }
}

