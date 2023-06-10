<?php

namespace Backend\utils;

class Helper
{
    public function __construct()
    {
    }

    public function createMockEmail(): string
    {
        $email = '';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < 10; $i++) {
            $email .= $characters[rand(0, $charactersLength - 1)];
        }
        $email .= '@example.com';
        return $email;
    }

    public function createMockName(): string
    {
        $name = '';
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < 10; $i++) {
            $name .= $characters[rand(0, $charactersLength - 1)];
        }
        return $name;
    }

    public function createMockPassword(): string
    {
        $password = '';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < 10; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        return $password;
    }

}
