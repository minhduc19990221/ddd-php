<?php

namespace Application\users\services;


use Application\users\factory\UserFactory;
use Domain\entity\User;
use Domain\repository\UserRepository;
use Utils\ResponseSender;

class UserService
{

    public static function register(string $fullname, string $email, string $password): void
    {
        if (!$fullname || !$email || !$password) {
            ResponseSender::sendErrorResponse(400, "Missing parameters");
            return;
        }
        $user = UserFactory::getInstance();
        $user?->createOne($fullname, $email, $password);
        ResponseSender::sendSuccessResponse(201, ['message' => 'User created successfully']);
    }

    public function login(string $email, string $password): bool
    {
        if (!$email || !$password) {
            ResponseSender::sendErrorResponse(400, "Missing parameters");
            return false;
        }
        return UserRepository::getInstance()?->userExists($email, $password);
    }

    public function update(string $fullname, string $email): void
    {
        if (!$fullname || !$email) {
            ResponseSender::sendErrorResponse(400, "Missing parameters");
            return;
        }
        $this->isUserExisted($email);
        $user = UserRepository::getInstance();
        $user?->updateOne($fullname, $email);
        ResponseSender::sendSuccessResponse(200, ['message' => 'User updated successfully']);
    }

    private function isUserExisted(string $email): bool
    {
        if (!$email) {
            ResponseSender::sendErrorResponse(400, "Missing parameters");
            return false;
        }
        $user_repository = UserRepository::getInstance();
        $user = $user_repository?->readOne($email);
        if (empty($user)) {
            ResponseSender::sendErrorResponse(404, "User not found");
            return false;
        }

        return true;
    }

    public function getOne(string $email): User|null
    {
        if (!$this->isUserExisted($email)) {
            return null;
        }
        $user_repository = UserRepository::getInstance();
        $user_record = $user_repository?->readOne($email);
        $user = new User($user_record['fullname'], $user_record['email'], $user_record['password']);
        $result = $user->toArray();
        ResponseSender::sendSuccessResponse(200, ['message' => 'User retrieved successfully', 'user' => $result]);
        return $user;
    }
}
