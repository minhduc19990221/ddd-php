<?php

use D002834\Backend\repository\UserRepository;
use D002834\Backend\utils\Helper;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    protected ?UserRepository $user_repository;
    protected ?Helper $helper;

    protected string $fullname;

    protected string $email;

    protected string $password;

    public function testCreateOne()
    {
        $stmt = $this->user_repository->createOne($this->fullname, $this->email, $this->password);
        $this->assertInstanceOf(PDOStatement::class, $stmt);
        $this->assertTrue($this->user_repository->userExists($this->email, $this->password));
        $this->user_repository->deleteOne($this->email);
    }

    public function testUserExists()
    {
        $this->user_repository->createOne($this->fullname, $this->email, $this->password);
        $this->assertTrue($this->user_repository->userExists($this->email, $this->password));
        $this->assertFalse($this->user_repository->userExists($this->email, 'wrong_password'));
        $this->assertFalse($this->user_repository->userExists('wrong_email@example.com', $this->password));
        $this->user_repository->deleteOne($this->email);

    }

    public function testReadOne()
    {
        $this->user_repository->createOne($this->fullname, $this->email, $this->password);
        $user = $this->user_repository->readOne($this->email);
        $this->assertEquals($this->fullname, $user['fullname']);
        $this->assertEquals($this->email, $user['email']);
        $this->assertEquals($this->password, $user['password']);
        $this->user_repository->deleteOne($this->email);
    }

    public function testUpdateOne()
    {
        $this->user_repository->createOne($this->fullname, $this->email, $this->password);
        $new_fullname = 'Jane Doe';
        $stmt = $this->user_repository->updateOne($new_fullname, $this->email);
        $this->assertInstanceOf(PDOStatement::class, $stmt);
        $this->assertTrue($this->user_repository->userExists($this->email, $this->password));
        $user = $this->user_repository->readOne($this->email);
        $this->assertEquals($new_fullname, $user['fullname']);
        $this->user_repository->deleteOne($this->email);
    }

    public function testDeleteOne()
    {
        $this->user_repository->createOne($this->fullname, $this->email, $this->password);
        $stmt = $this->user_repository->deleteOne($this->email);
        $this->assertInstanceOf(PDOStatement::class, $stmt);
        $this->assertFalse($this->user_repository->userExists($this->email, $this->password));
    }

    protected function setUp(): void
    {
        $this->helper = new Helper();
        $this->user_repository = UserRepository::getInstance();
        $this->fullname = $this->helper->createMockName();
        $this->email = $this->helper->createMockEmail();
        $this->password = $this->helper->createMockPassword();
        parent::setUp(); // TODO: Change the autogenerated stub
    }
}