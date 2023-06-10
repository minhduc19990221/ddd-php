<?php

namespace Backend\interface\middleware;

use Backend\infrastructure\RateLimiter;
use Utils\ResponseSender;

class Interceptor
{
    private RateLimiter $rate_limiter;

    private ResponseSender $response_sender;

    public function __construct(RateLimiter $rate_limiter, ResponseSender $response_sender)
    {
        $this->rate_limiter = $rate_limiter;
        $this->response_sender = $response_sender;
        $client = $_ENV['CLIENT'];
        $server = $_ENV['SERVER'];
        // Overwrite header to avoid CORS error when integration testing
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        header("Access-Control-Allow-Headers: Access, Content-Type, Authorization");
        // Setting content security policy to protect against XSS
        header("Content-Security-Policy: default-src 'self'; script-src 'self' $client; connect-src 'self' $server;");
    }
    
    public function handleRequest(): void
    {
        define("REQUEST_METHOD", $_SERVER["REQUEST_METHOD"]);
        $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $script_path = dirname($_SERVER['SCRIPT_NAME']);

        // Remove script path from request URI, if necessary
        if (str_starts_with($request_uri, $script_path)) {
            $request_uri = substr($request_uri, strlen($script_path));
        }

        $request_uri = explode('/', trim($request_uri, '/'));
        define("REQUEST_URI", $request_uri);

        $resource = array_shift($request_uri);
        define("RESOURCE", $resource);

        $request_body = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR) ?? [];


        $router = new Router();
        $this->rate_limiter->rateLimit($_SERVER['REMOTE_ADDR']);

        switch ($resource) {
            case 'login':
                $router->loginRouting(REQUEST_METHOD, $request_body);
                break;
            case 'register':
                $router->registerRouting(REQUEST_METHOD, $request_body);
                break;
            case 'users':
                $router->userRouting(REQUEST_METHOD, $request_body);
                break;
            default:
                $this->response_sender->sendErrorResponse("Resource not found", 404);
        }
    }
}

// Usage:
$rate_limiter = new RateLimiter($_ENV['REQUEST_LIMIT'], $_ENV['TIME_PERIOD']);
$interceptor = new Interceptor($rate_limiter, new ResponseSender());
$interceptor->handleRequest();
