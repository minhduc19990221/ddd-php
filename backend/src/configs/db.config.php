<?php
namespace D002834\Backend\configs;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private $host = 'localhost';
    private $username = 'username';
    private $password = 'password';
    private $database = 'myDB';
    private $charset = 'utf8mb4';

    private function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, $opt);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    private function __clone() { }

    private function __wakeup() { }

    public function getConnection()
    {
        return $this->connection;
    }

    public function createDatabase($db_name)
    {
        try {
            $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
            $this->connection->exec($sql);
            echo "Database $db_name created successfully.<br/>";
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }

    public function createTable($db_name, $table_name)
    {
        try {
            $this->connection->exec("USE $db_name");
            $sql = "CREATE TABLE IF NOT EXISTS $table_name
            (
               id         INT PRIMARY KEY auto_increment NOT NULL,
               email      VARCHAR(255) NOT NULL,
               password   VARCHAR(100) NOT NULL,
               fullname   VARCHAR(100) NOT NULL,
               created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
               updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
               UNIQUE (email)
            );";

            $this->connection->exec($sql);
            echo "Table $table_name created successfully in database $db_name.<br/>";
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
}

