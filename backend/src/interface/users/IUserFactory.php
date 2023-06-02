<?php


interface IUserFactory
{
    public function createOne($fullname, $email, $password);
}