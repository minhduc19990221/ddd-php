<?php

namespace Application\users\factory;

interface IUserFactory
{
    public function createOne(string $fullname, string $email, string $password);
}
