<?php

namespace D002834\Backend\application\users\factory;

interface IUserFactory
{
    public function createOne(string $fullname, string $email, string $password);
}
