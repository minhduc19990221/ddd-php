<?php

namespace D002834\Backend\interface\users;

interface IUserFactory
{
    public function createOne(string $fullname, string $email, string $password);
}
