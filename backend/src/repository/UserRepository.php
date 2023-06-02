<?php

namespace D002834\Backend\repository;


use D002834\Backend\configs\Database;
use PDO;
use PDOStatement;


class UserRepository
{
    private static ?UserRepository $instance = null;
    public int $id;
    public string $fullname;
    public string $email;
    public string $password;
    private PDO $connection;
    private string $table_name = "users";

    private function __construct()
    {
        $db = Database::getInstance();
        $this->connection = $db->getConnection();
    }

    public static function getInstance(): ?UserRepository
    {
        if (self::$instance == null) {
            self::$instance = new UserRepository();
        }
        return self::$instance;
    }

    public function createTable(): void
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

    public function read($limit, $offset): bool|PDOStatement
    {
        $sql = "SELECT * FROM $this->table_name LIMIT $limit OFFSET $offset";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt;
    }

    public function readOne($email): bool|PDOStatement
    {
        $sql = "SELECT * FROM $this->table_name WHERE email = $email LIMIT 1;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        return $stmt;
    }

    public function createOne($fullname, $email, $password): bool|PDOStatement
    {
        $sql = "INSERT INTO $this->table_name (fullname, email, password) VALUES ($fullname, $email, $password)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $this->fullname);
        $stmt->bindParam(2, $this->email);
        $stmt->bindParam(3, $this->password);
        $stmt->execute();

        return $stmt;
    }

    public function updateOne($fullname, $email): bool|PDOStatement
    {
        $sql = "UPDATE $this->table_name SET fullname = $fullname WHERE email = $email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $this->fullname);
        $stmt->execute();

        return $stmt;
    }

    public function deleteOne($email): bool|PDOStatement
    {
        $sql = "DELETE FROM $this->table_name WHERE email = $email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        return $stmt;
    }
}

