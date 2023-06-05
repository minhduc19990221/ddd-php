<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenvPath = __DIR__ . '/.env';
if (file_exists($dotenvPath)) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} else {
    echo 'Error: .env file not found';
}

require __DIR__ . '/middleware/interceptor.php';

