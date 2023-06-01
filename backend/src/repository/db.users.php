<?php
namespace D002834\Backend\repository\UserRepository;


use D002834\Backend\configs\Database;


class User
{
    private $connection;
    private $table_name = "users";

    public $id;
    public $fullname;
    public $email;
    public $password;

    public function __construct()
    {
        $db = Database::getInstance();
        $this->connection = $db->getConnection();
    }

    public function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id         INT PRIMARY KEY auto_increment NOT NULL,
            email      VARCHAR(255) NOT NULL,
            password   VARCHAR(100) NOT NULL,
            fullname   VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE (email)
        )";

        $this->connection->exec($sql);
    }

    public function read($limit, $offset)
    {
        $sql = "SELECT * FROM $this->table_name LIMIT $limit OFFSET $offset";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt;
    }

    public function readOne($email)
    {
        $sql = "SELECT * FROM $this->table_name WHERE email = $email LIMIT 1;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        return $stmt;
    }

    public function createOne($fullname, $email, $password)
    {
        $sql = "INSERT INTO $this->table_name (fullname, email, password) VALUES ($fullname, $email, $password)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $this->fullname);
        $stmt->bindParam(2, $this->email);
        $stmt->bindParam(3, $this->password);
        $stmt->execute();

        return $stmt;
    }

    public function updateOne($fullname, $email)
    {
        $sql = "UPDATE $this->table_name SET fullname = $fullname WHERE email = $email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $this->fullname);
        $stmt->execute();

        return $stmt;
    }

    public function deleteOne($email)
    {
        $sql = "DELETE FROM $this->table_name WHERE email = $email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        return $stmt;
    }
}

