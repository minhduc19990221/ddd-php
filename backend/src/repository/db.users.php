<?php
namespace D002834\Backend\repository\DatabaseEntry;


use D002834\Backend\configs\Database;

// Create a User class which will be used to create user table and user record in Database

class User
{
    private $connection;
    private $table_name = "users";

    public $id;
    public $firstname;
    public $lastname;
    public $email;

    public function __construct()
    {
        $db = Database::getInstance();
        $this->connection = $db->getConnection();
    }

    public function create()
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

    public function read()
    {
        $sql = "SELECT * FROM $this->table_name";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt;
    }

    public function readOne()
    {
        $sql = "SELECT * FROM $this->table_name WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        return $stmt;
    }

    public function createOne()
    {
        $sql = "INSERT INTO $this->table_name (firstname, lastname, email) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $this->firstname);
        $stmt->bindParam(2, $this->lastname);
        $stmt->bindParam(3, $this->email);
        $stmt->execute();

        return $stmt;
    }

    public function updateOne()
    {
        $sql = "UPDATE $this->table_name SET firstname = ?, lastname = ?, email = ? WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $this->firstname);
        $stmt->bindParam(2, $this->lastname);
        $stmt->bindParam(3, $this->email);
        $stmt->bindParam(4, $this->id);
        $stmt->execute();

        return $stmt;
    }

    public function deleteOne()
    {
        $sql = "DELETE FROM $this->table_name WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        return $stmt;
    }
}

