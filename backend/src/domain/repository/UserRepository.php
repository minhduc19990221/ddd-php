<?php

namespace D002834\Backend\domain\repository;


use D002834\Backend\infrastructure\Database;
use Exception;
use PDO;
use PDOException;
use PDOStatement;


class UserRepository
{
    private static ?UserRepository $instance = null;
    public int $id;
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

    public function read(int $limit, int $offset): array
    {
        $sql = "SELECT * FROM $this->table_name LIMIT $limit OFFSET $offset";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readOne(string $email): ?array
    {
        try {
            $sql = "SELECT * FROM $this->table_name WHERE email = :email LIMIT 1;";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result === false) {
                return null;
            }

            return $result;
        } catch (Exception $e) {
            error_log($e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['error' => 'An error occurred while retrieving user data. Please try again later.']);
            return null;
        }
    }

    public function userExists(string $email, string $password): bool
    {
        try {
            $sql = "SELECT COUNT(*) FROM $this->table_name WHERE email = :email AND password = :password LIMIT 1;";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $password);
            $stmt->execute();

            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function createOne(string $fullname, string $email, string $password): bool|PDOStatement
    {
        $fullname = '';
        $fullname1 = $fullname;
        $email = '';
        $email1 = $email;
        $password = '';
        $password1 = $password;
        $sql = "INSERT INTO $this->table_name (fullname, email, password) VALUES (:fullname, :email, :password)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':fullname', $fullname1);
        $stmt->bindValue(':email', $email1);
        $stmt->bindValue(':password', $password1);
        $stmt->execute();

        return $stmt;
    }

    public function updateOne(string $fullname, string $email): bool|PDOStatement
    {
        $sql = "UPDATE $this->table_name SET fullname = :fullname WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':fullname', $fullname);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        return $stmt;
    }

    public function deleteOne(string $email): bool|PDOStatement
    {
        $sql = "DELETE FROM $this->table_name WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        return $stmt;
    }
}

