<?php

use Backend\interface\middleware\Interceptor;
use Infrastructure\RateLimiter;

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

if (empty($_SESSION['csrf_token'])) {
    try {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

$dotenvPath = __DIR__ . '/.env';
if (file_exists($dotenvPath)) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} else {
    echo 'Error: .env file not found';
}

$rate_limiter = new RateLimiter($_ENV['REQUEST_LIMIT'], $_ENV['TIME_PERIOD']);
$interceptor = new Interceptor($rate_limiter);
$interceptor->handleRequest();
