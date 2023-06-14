<?php

use Infrastructure\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    protected ?Database $db;

    protected ?Database $db_test;

    public function testConnection(): void
    {
        $this->assertInstanceOf(PDO::class, $this->db->getConnection());
    }

    public function testGetInstance(): void
    {
        $this->assertInstanceOf(Database::class, $this->db);
        $this->assertSame($this->db, $this->db_test, 'The two instances of the database should be the same.');
    }

    public function testCreateDatabase(): void
    {
        $db_name = 'test_db';
        $this->db->createDatabase($db_name);
        $this->assertContains($db_name, $this->db->getConnection()->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN));
    }

    public function testCreateTable(): void
    {
        $db_name = 'test_db';
        $table_name = 'test_table';
        $this->db->createDatabase($db_name);
        $this->db->createTable($db_name, $table_name);
        $this->assertContains($table_name, $this->db->getConnection()->query("SHOW TABLES FROM $db_name")->fetchAll(PDO::FETCH_COLUMN));
    }

    protected function setUp(): void
    {
        $dotenvPath = __DIR__ . '/.env';
        if (file_exists($dotenvPath)) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv->load();
        } else {
            echo 'Error: .env file not found';
        }
        $this->db = Database::getInstance();
        $this->db_test = Database::getInstance();
        parent::setUp();

    }
}
