<?php


use Application\users\factory\UserFactory;
use Backend\utils\Helper;
use Domain\repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    protected ?UserRepository $user_repository;
    protected ?UserFactory $user_factory;
    protected ?Helper $helper;

    public function testCreateOne(): void
    {
        $fullname = $this->helper->createMockName();
        $email = $this->helper->createMockEmail();
        $password = $this->helper->createMockPassword();
        $this->user_factory->createOne($fullname, $email, $password);
        $user = $this->user_repository->readOne($email);
        $this->assertEquals($fullname, $user['fullname']);
        $this->assertEquals($email, $user['email']);
        $this->assertEquals($password, $user['password']);
    }

    protected function setUp(): void
    {
        $this->user_repository = UserRepository::getInstance();
        $this->user_factory = UserFactory::getInstance();
        $this->helper = new Helper();
    }
}
