<?php

namespace Domain\repository;

use Infrastructure\Database;
use PDO;
use PDOException;

class CardRepository
{
    private static ?CardRepository $instance = null;

    private PDO $connection;

    private string $table_name = "cards";

    private function __construct()
    {
        $db = Database::getInstance();
        $this->connection = $db?->getConnection();
    }

    public static function getInstance(): ?CardRepository
    {
        if (self::$instance === null) {
            self::$instance = new CardRepository();
        }
        return self::$instance;
    }

    public function createTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
                id INT PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                index_board INT,
                board_id INT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY(board_id, index_board),
                FOREIGN KEY(board_id) REFERENCES boards(id)
        )";

        $this->connection->exec($sql);
    }

    public function read(int $board_id): array
    {
        $sql = "SELECT * FROM $this->table_name WHERE board_id = :board_id ORDER BY index_board ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':board_id', $board_id);
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

    public function create(string $title, int $board_id, int $index_board): void
    {
        try {
            $sql = "INSERT INTO $this->table_name (title, board_id, index_board) VALUES (:title, :board_id, :index_board)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':board_id', $board_id);
            $stmt->bindParam(':index_board', $index_board);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update(int $id, string $title, int $index_board): void
    {
        try {
            $sql = "UPDATE $this->table_name SET title = :title, index_board = :index_board WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':index_board', $index_board);
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
