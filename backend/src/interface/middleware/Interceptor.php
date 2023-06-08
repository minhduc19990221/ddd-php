<?php

namespace D002834\Backend\interface\middleware;

use D002834\Backend\middleware\router\Router;

class Interceptor
{
    public function __construct()
    {
        // Overwrite header to avoid CORS error when integration testing
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        header("Access-Control-Allow-Headers: Access, Content-Type, Authorization");
        // Setting content security policy to protect against XSS
        header("Content-Security-Policy: default-src 'self'; script-src 'self' http://localhost:3000; connect-src 'self' http://localhost:8000;");
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

        $request_body = json_decode(file_get_contents('php://input'), true);


        $router = new Router();

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
                http_response_code(404);
                echo json_encode(["message" => "Resource not found"]);
        }
    }
}

// Usage:
$interceptor = new Interceptor();
$interceptor->handleRequest();
