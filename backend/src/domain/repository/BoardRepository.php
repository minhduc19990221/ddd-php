<?php

namespace Domain\repository;

use Infrastructure\Database;
use PDO;
use PDOException;

class BoardRepository
{
    private static ?BoardRepository $instance = null;

    private PDO $connection;

    private string $table_name = "boards";

    private function __construct()
    {
        $db = Database::getInstance();
        $this->connection = $db?->getConnection();
    }

    public static function getInstance(): ?BoardRepository
    {
        if (self::$instance === null) {
            self::$instance = new BoardRepository();
        }
        return self::$instance;
    }

    public function createTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
                id INT PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (email) REFERENCES users(email))";

        $this->connection->exec($sql);
    }

    public function read(string $email): array
    {
        $sql = "SELECT * FROM $this->table_name WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne(int $id): ?array
    {
        try {
            $sql = "SELECT * FROM $this->table_name WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return null;
    }

    public function create(string $title, string $email): void
    {
        try {
            $sql = "INSERT INTO $this->table_name (title, email) VALUES (:title, :email)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update(string $title, string $id): void
    {
        try {
            $sql = "UPDATE $this->table_name SET title = :title WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function delete(int $id): void
    {
        try {
            $sql = "DELETE FROM $this->table_name WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function count(): int
    {
        try {
            $sql = "SELECT COUNT(*) FROM $this->table_name";
            return $this->connection->query($sql)->fetchColumn();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return 0;
    }
}
