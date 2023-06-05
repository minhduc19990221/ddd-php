<?php

use D002834\Backend\handlers\users\UserHandler;
use D002834\Backend\repository\UserRepository;
use D002834\Backend\utils\Helper;
use PHPUnit\Framework\TestCase;

class UserHandlerTest extends TestCase
{
    protected ?UserHandler $user_handler;
    protected ?Helper $helper;

    protected ?UserRepository $user_repository;

    protected string $fullname;

    protected string $email;

    protected string $password;

    public function testRegister()
    {
        $this->user_handler->register($this->fullname, $this->email, $this->password);
        $this->assertTrue($this->user_handler->login($this->email, $this->password));
        $this->user_repository->deleteOne($this->email);
    }

    public function testLogin()
    {

        $this->user_handler->register($this->fullname, $this->email, $this->password);

        // Test login with correct password
        $this->assertTrue($this->user_handler->login($this->email, $this->password));

        // Test login with incorrect password
        $this->assertFalse($this->user_handler->login($this->email, 'wrong_password_@'));

        // Test login with incorrect email
        $this->assertFalse($this->user_handler->login('wrong_email_1@example.com', $this->password));

        // Clean up test data
        $this->user_repository->deleteOne($this->email);
    }

    protected function setUp(): void
    {
        $this->user_repository = UserRepository::getInstance();
        $this->user_handler = new UserHandler();
        $this->helper = new Helper();
        $this->fullname = $this->helper->createMockName();
        $this->email = $this->helper->createMockEmail();
        $this->password = $this->helper->createMockPassword();
        parent::setUp(); // TODO: Change the autogenerated stub
    }
}
