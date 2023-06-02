<?php

namespace D002834\Backend\interface\users;

interface IUserFactory
{
    public function createOne($fullname, $email, $password);
}
