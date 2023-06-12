<?php

namespace Domain\entity;

class User
{
    private string $fullname;
    private string $email;
    private string $password;

    public function __construct(string $fullname, string $email, string $password)
    {
        $this->fullname = $fullname;
        $this->email = $email;
        $this->password = $password;
    }

    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function toArray(): array
    {
        return [
            'fullname' => $this->fullname,
            'email' => $this->email,
        ];
    }

}
