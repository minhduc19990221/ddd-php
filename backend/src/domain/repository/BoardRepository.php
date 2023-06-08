<?php

namespace D002834\Backend\domain\repository;

use D002834\Backend\infrastructure\Database;
use PDO;

class BoardRepository
{
    private static ?BoardRepository $instance = null;

    private PDO $connection;

    private string $table_name = "boards";

    private function __construct()
    {
        $db = Database::getInstance();
        $this->connection = $db->getConnection();
    }

    public static function getInstance(): ?BoardRepository
    {
        if (self::$instance == null) {
            self::$instance = new BoardRepository();
        }
        return self::$instance;
    }

    public function createTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id         INT PRIMARY KEY auto_increment NOT NULL,
            title      VARCHAR(255) NOT NULL,
            content    VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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

    public function readOne(string $title): ?array
    {
        try {
            $sql = "SELECT * FROM $this->table_name WHERE title = :title";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }

        return null;
    }

    public function create(string $title, string $content): void
    {
        try {
            $sql = "INSERT INTO $this->table_name (title, content) VALUES (:title, :content)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update(string $title, string $content): void
    {
        try {
            $sql = "UPDATE $this->table_name SET content = :content WHERE title = :title";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function delete(string $title): void
    {
        try {
            $sql = "DELETE FROM $this->table_name WHERE title = :title";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->execute();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function count(): int
    {
        try {
            $sql = "SELECT COUNT(*) FROM $this->table_name";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }

        return 0;
    }
}
