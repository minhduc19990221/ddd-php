<?php

namespace Backend\utils;

use Exception;

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
            $email .= $characters[random_int(0, $charactersLength - 1)];
        }
        $email .= '@example.com';
        return $email;
    }

    /**
     * @throws Exception
     */
    public function createMockName(): string
    {
        $name = '';
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < 10; $i++) {
            $name .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $name;
    }

    /**
     * @throws Exception
     */
    public function createMockPassword(): string
    {
        $password = '';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < 10; $i++) {
            $password .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $password;
    }

}
