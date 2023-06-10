<?php

namespace Backend\application\users\factory;

interface IUserFactory
{
    public function createOne(string $fullname, string $email, string $password);
}
