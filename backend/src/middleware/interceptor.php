<?php

namespace D002834\Backend\middleware;

use function D002834\Backend\middleware\router\login_routing;
use function D002834\Backend\middleware\router\register_routing;
use function D002834\Backend\middleware\router\user_routing;

// Overwrite header to avoid CORS error when integration testing
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Access, Content-Type, Authorization");

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

include __DIR__ . "/router.php";

// Use IIFE to avoid polluting global namespace
(function ($resource, $request_method): void {
    $request_body = json_decode(file_get_contents('php://input'), true);
    switch ($resource) {
        case 'login':
            login_routing($request_method, $request_body);
            break;
        case 'register':
            register_routing($request_method, $request_body);
            break;
        case 'users':
            user_routing($request_method, $request_body);
            break;
        default:
            http_response_code(404);
            echo json_encode(["message" => "Resource not found"]);
    }
})(RESOURCE, REQUEST_METHOD);

